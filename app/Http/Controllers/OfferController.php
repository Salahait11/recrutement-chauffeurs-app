<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use App\Models\Candidate;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;

class OfferController extends Controller
{
    public function index(Request $request)
    {
        // Récupérer les filtres
        $candidateFilter = $request->input('candidate_id');
        $statusFilter = $request->input('status');
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');
        
        // Commencer la requête avec eager loading des relations
        $query = Offer::with(['candidate', 'createdBy']);
        
        // Appliquer les filtres
        if ($candidateFilter) {
            $query->where('candidate_id', $candidateFilter);
        }
        
        if ($statusFilter && $statusFilter !== 'all') {
            $query->where('status', $statusFilter);
        }
        
        // Filtres de date
        if ($dateFrom) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }
        
        if ($dateTo) {
            $query->whereDate('created_at', '<=', $dateTo);
        }
        
        // Récupérer les offres paginées
        $offers = $query->latest()->paginate(10);
        
        // Récupérer les candidats pour le filtre (seulement ceux qui ont des offres)
        $candidatesWithOffers = Candidate::whereHas('offers')
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get();
        
        // Statuts possibles pour le filtre
        $statuses = [
            Offer::STATUS_BROUILLON,
            Offer::STATUS_ENVOYEE,
            Offer::STATUS_ACCEPTEE,
            Offer::STATUS_REFUSEE,
            Offer::STATUS_EXPIREE,
            Offer::STATUS_RETIREE
        ];
        
        return view('offers.index', compact('offers', 'candidatesWithOffers', 'candidateFilter', 'statusFilter', 'statuses', 'dateFrom', 'dateTo'));
    }

    public function createForCandidate(Candidate $candidate)
    {
        // Vérifier si le candidat est dans un statut valide pour recevoir une offre
        if ($candidate->status !== Candidate::STATUS_TEST && $candidate->status !== Candidate::STATUS_OFFRE) {
            return redirect()->route('candidates.show', $candidate)
                ->with('error', 'Le candidat doit être en cours de test ou avoir déjà une offre pour recevoir une nouvelle offre.');
        }

        // Vérifier si le candidat a déjà une offre en cours
        $existingOffer = Offer::where('candidate_id', $candidate->id)
            ->whereIn('status', ['brouillon', 'envoyee'])
            ->first();

        if ($existingOffer) {
            return redirect()->route('offers.show', $existingOffer)
                ->with('error', 'Ce candidat a déjà une offre en cours.');
        }

        // Récupérer tous les candidats pour le select
        $candidates = Candidate::orderBy('first_name')->orderBy('last_name')->get();

        return view('offers.create', compact('candidate', 'candidates'));
    }

    public function store(Request $request)
    {
        // Valider les données
        $validatedData = $request->validate([
            'candidate_id' => 'required|exists:candidates,id',
            'position_offered' => 'required|string|max:255',
            'contract_type' => 'required|string|max:255',
            'salary' => 'required|numeric|min:0',
            'salary_period' => 'required|string|max:255',
            'start_date' => 'required|date|after_or_equal:today',
            'benefits' => 'nullable|string',
            'specific_conditions' => 'nullable|string',
            'expires_at' => 'nullable|date|after:today',
            'details' => 'nullable|string',
            'status' => 'required|in:' . implode(',', [
                Offer::STATUS_BROUILLON,
                Offer::STATUS_ENVOYEE
            ])
        ]);

        try {
            DB::beginTransaction();

            // Vérifier si le candidat existe et est en cours ou a déjà une offre
            $candidate = Candidate::findOrFail($validatedData['candidate_id']);
            if ($candidate->status !== Candidate::STATUS_TEST && $candidate->status !== Candidate::STATUS_OFFRE) {
                throw new \Exception('Le candidat doit être en cours de recrutement ou avoir déjà une offre pour recevoir une nouvelle offre.');
            }

            // Vérifier si le candidat a déjà une offre en cours
            $existingOffer = $candidate->offers()
                ->whereIn('status', [Offer::STATUS_BROUILLON, Offer::STATUS_ENVOYEE])
                ->first();

            if ($existingOffer) {
                throw new \Exception('Le candidat a déjà une offre en cours.');
            }

            // Forcer le statut initial de l'offre à 'envoyee'
            $validatedData['status'] = Offer::STATUS_ENVOYEE;

            // Ajouter l'ID du créateur
            $validatedData['creator_id'] = Auth::id();

            // Définir la date d'envoi si le statut est 'envoyee'
            $validatedData['sent_at'] = now();

            // Créer l'offre
            $offer = Offer::create($validatedData);
            
            // Mettre à jour le statut du candidat si nécessaire
            if ($candidate->status !== Candidate::STATUS_OFFRE) {
                $candidate->update(['status' => Candidate::STATUS_OFFRE]);
            }

            DB::commit();

            // Rediriger avec message de succès
            $message = 'Offre enregistrée et envoyée !';
            
            return redirect()->route('offers.show', $offer)
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Erreur création offre: " . $e->getMessage());
            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    public function show(Offer $offer)
    {
        $offer->load(['candidate', 'createdBy']);
        
        // Ne pas formater les dates ici, elles seront formatées dans la vue
        return view('offers.show', compact('offer'));
    }

    public function edit(Offer $offer)
    {
        // Permettre la modification des offres en brouillon ou envoyées
        if (!in_array($offer->status, [Offer::STATUS_BROUILLON, Offer::STATUS_ENVOYEE])) {
            return redirect()->route('offers.show', $offer)
                ->with('error', 'Seules les offres en brouillon ou envoyées peuvent être modifiées.');
        }

        return view('offers.edit', compact('offer'));
    }

    public function update(Request $request, Offer $offer)
    {
        // Vérifier si c'est une action de statut (accept/reject)
        if ($request->has('status_action')) {
            if (!$offer->isEnvoyee()) {
                return redirect()->route('offers.show', $offer)
                    ->with('error', 'Seules les offres envoyées peuvent être acceptées ou refusées.');
            }

            try {
                DB::beginTransaction();

                $newStatus = $request->input('status_action') === 'accept' ? 
                    Offer::STATUS_ACCEPTEE : 
                    Offer::STATUS_REFUSEE;

                $offer->update([
                    'status' => $newStatus,
                    'responded_at' => now()
                ]);

                // Si l'offre est acceptée, mettre à jour le statut du candidat
                if ($newStatus === Offer::STATUS_ACCEPTEE) {
                    $offer->candidate->update(['status' => Candidate::STATUS_EMBAUCHE]);
                    
                    // Créer un nouvel utilisateur pour l'employé
                    $user = User::create([
                        'name' => $offer->candidate->first_name . ' ' . $offer->candidate->last_name,
                        'email' => $offer->candidate->email,
                        'password' => Hash::make(Str::random(16)), // Mot de passe aléatoire
                        'email_verified_at' => now(),
                        'role' => 'employee'
                    ]);

                    // Créer un nouvel employé à partir du candidat
                    Employee::create([
                        'user_id' => $user->id,
                        'candidate_id' => $offer->candidate_id,
                        'hire_date' => $offer->start_date,
                        'job_title' => $offer->position_offered,
                        'status' => 'active'
                    ]);
                }

                DB::commit();

                $message = $newStatus === Offer::STATUS_ACCEPTEE ? 
                    'Offre acceptée avec succès.' : 
                    'Offre refusée.';

                return redirect()->route('offers.show', $offer)
                    ->with('success', $message);

            } catch (\Exception $e) {
                DB::rollBack();
                Log::error("Erreur mise à jour statut offre: " . $e->getMessage());
                return back()->with('error', $e->getMessage());
            }
        }

        // Pour les autres modifications, vérifier si l'offre est en brouillon ou envoyée
        if (!in_array($offer->status, [Offer::STATUS_BROUILLON, Offer::STATUS_ENVOYEE])) {
            return redirect()->route('offers.show', $offer)
                ->with('error', 'Seules les offres en brouillon ou envoyées peuvent être modifiées.');
        }

        $validatedData = $request->validate([
            'position_offered' => 'required|string|max:255',
            'contract_type' => 'required|string|max:255',
            'salary' => 'required|numeric|min:0',
            'salary_period' => 'required|string|max:255',
            'start_date' => 'required|date|after_or_equal:today',
            'benefits' => 'nullable|string',
            'specific_conditions' => 'nullable|string',
            'expires_at' => 'nullable|date|after:today',
            'details' => 'nullable|string',
            'status' => 'required|in:' . implode(',', [
                Offer::STATUS_BROUILLON,
                Offer::STATUS_ENVOYEE
            ])
        ]);

        try {
            DB::beginTransaction();

            // Si le statut change pour "envoyee"
            if ($validatedData['status'] === Offer::STATUS_ENVOYEE && $offer->status !== Offer::STATUS_ENVOYEE) {
                $validatedData['sent_at'] = now();
            }

            $offer->update($validatedData);

            DB::commit();

            $message = $offer->status === Offer::STATUS_ENVOYEE ? 
                'Offre mise à jour et envoyée !' : 
                'Offre mise à jour.';

            return redirect()->route('offers.show', $offer)
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Erreur mise à jour offre: " . $e->getMessage());
            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    public function destroy(Offer $offer)
    {
        if (!$offer->isBrouillon()) {
            return redirect()->route('offers.show', $offer)
                ->with('error', 'Seules les offres en brouillon peuvent être supprimées.');
        }

        try {
            $offer->delete();
            return redirect()->route('offers.index')
                ->with('success', 'Offre supprimée avec succès.');
        } catch (\Exception $e) {
            Log::error("Erreur suppression offre: " . $e->getMessage());
            return back()->with('error', 'Erreur lors de la suppression de l\'offre.');
        }
    }

    public function downloadOfferPdf(Offer $offer)
    {
        // Charger les relations nécessaires
        $offer->load(['candidate', 'createdBy']);

        // Générer le PDF
        $pdf = PDF::loadView('offers.pdf', compact('offer'));

        // Retourner le PDF pour téléchargement
        return $pdf->download('offre_' . $offer->id . '.pdf');
    }

    public function updateStatus(Request $request, Offer $offer)
    {
        if (!$offer->isEnvoyee()) {
            return redirect()->route('offers.show', $offer)
                ->with('error', 'Seules les offres envoyées peuvent être acceptées ou refusées.');
        }

        try {
            DB::beginTransaction();

            $newStatus = $request->input('status') === 'acceptee' ? 
                Offer::STATUS_ACCEPTEE : 
                Offer::STATUS_REFUSEE;

            $offer->update([
                'status' => $newStatus,
                'responded_at' => now()
            ]);

            // Si l'offre est acceptée, mettre à jour le statut du candidat
            if ($newStatus === Offer::STATUS_ACCEPTEE) {
                $offer->candidate->update(['status' => Candidate::STATUS_EMBAUCHE]);
                
                // Créer un nouvel utilisateur pour l'employé
                $user = User::create([
                    'name' => $offer->candidate->first_name . ' ' . $offer->candidate->last_name,
                    'email' => $offer->candidate->email,
                    'password' => Hash::make(Str::random(16)),
                    'email_verified_at' => now(),
                    'role' => 'employee'
                ]);

                // Créer un nouvel employé à partir du candidat
                Employee::create([
                    'user_id' => $user->id,
                    'candidate_id' => $offer->candidate_id,
                    'hire_date' => $offer->start_date,
                    'job_title' => $offer->position_offered,
                    'status' => 'active'
                ]);
            }

            DB::commit();

            $message = $newStatus === Offer::STATUS_ACCEPTEE ? 
                'Offre acceptée avec succès.' : 
                'Offre refusée.';

            return redirect()->route('offers.show', $offer)
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Erreur mise à jour statut offre: " . $e->getMessage());
            return back()->with('error', $e->getMessage());
        }
    }
}