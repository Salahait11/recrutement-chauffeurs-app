<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use App\Models\Candidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use App\Models\Employee; 
use App\Models\User;
use Illuminate\Support\Facades\DB;   
use Illuminate\Support\Facades\Hash; 
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class OfferController extends Controller
{
    public function index(Request $request)
    {
        // Récupérer les filtres
        $candidateFilter = $request->input('candidate_id');
        $statusFilter = $request->input('status');
        
        // Commencer la requête avec eager loading des relations
        $query = Offer::with(['candidate', 'createdBy']);
        
        // Appliquer les filtres
        if ($candidateFilter) {
            $query->where('candidate_id', $candidateFilter);
        }
        
        if ($statusFilter) {
            $query->where('status', $statusFilter);
        }
        
        // Récupérer les offres paginées
        $offers = $query->latest()->paginate(10);
        
        // Récupérer les candidats pour le filtre (seulement ceux qui sont en cours)
        $candidates = Candidate::where('status', Candidate::STATUS_EN_COURS)
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get();
        
        return view('offers.index', compact('offers', 'candidates', 'candidateFilter', 'statusFilter'));
    }

    public function createForCandidate(Candidate $candidate)
    {
        // Vérifier si le candidat a passé un test de conduite
        $lastDrivingTest = $candidate->drivingTests()->latest()->first();
        
        if (!$lastDrivingTest) {
            return redirect()->route('candidates.show', $candidate)
                ->with('error', 'Le candidat doit d\'abord passer un test de conduite avant de recevoir une offre.');
        }

        // Vérifier si le test de conduite est réussi
        if ($lastDrivingTest->status !== 'reussi') {
            return redirect()->route('candidates.show', $candidate)
                ->with('error', 'Le candidat doit avoir réussi son test de conduite avant de recevoir une offre.');
        }

        // Vérifier si le candidat est en cours
        if ($candidate->status !== Candidate::STATUS_EN_COURS) {
            return redirect()->route('candidates.show', $candidate)
                ->with('error', 'Le candidat doit être en cours de recrutement pour recevoir une offre.');
        }

        // Vérifier si le candidat a déjà une offre en cours
        $existingOffer = $candidate->offers()
            ->whereIn('status', [Offer::STATUS_BROUILLON, Offer::STATUS_ENVOYEE])
            ->first();

        if ($existingOffer) {
            return redirect()->route('candidates.show', $candidate)
                ->with('error', 'Le candidat a déjà une offre en cours.');
        }

        return view('offers.create', compact('candidate'));
    }

    public function store(Request $request)
    {
        // Valider les données
        $validatedData = $request->validate([
            'candidate_id' => 'required|exists:candidates,id',
            'salary' => 'required|numeric|min:0',
            'start_date' => 'required|date|after_or_equal:today',
            'details' => 'nullable|string',
            'status' => 'required|in:brouillon,envoyee'
        ]);

        try {
            DB::beginTransaction();

            // Vérifier si le candidat existe et est en cours
            $candidate = Candidate::findOrFail($validatedData['candidate_id']);
            if ($candidate->status !== Candidate::STATUS_EN_COURS) {
                throw new \Exception('Le candidat doit être en cours de recrutement pour recevoir une offre.');
            }

            // Vérifier si le candidat a déjà une offre en cours
            $existingOffer = $candidate->offers()
                ->whereIn('status', [Offer::STATUS_BROUILLON, Offer::STATUS_ENVOYEE])
                ->first();

            if ($existingOffer) {
                throw new \Exception('Le candidat a déjà une offre en cours.');
            }

            // Ajouter l'ID du créateur
            $validatedData['created_by'] = Auth::id();

            // Définir la date d'envoi si le statut est 'envoyee'
            if ($validatedData['status'] === Offer::STATUS_ENVOYEE) {
                $validatedData['sent_at'] = now();
            }

            // Créer l'offre
            $offer = Offer::create($validatedData);

            DB::commit();

            // Rediriger avec message de succès
            $message = $offer->status === Offer::STATUS_ENVOYEE ? 
                'Offre enregistrée et envoyée !' : 
                'Offre enregistrée comme brouillon.';
            
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
        return view('offers.show', compact('offer'));
    }

    public function edit(Offer $offer)
    {
        if (!$offer->isBrouillon()) {
            return redirect()->route('offers.show', $offer)
                ->with('error', 'Seules les offres en brouillon peuvent être modifiées.');
        }

        return view('offers.edit', compact('offer'));
    }

    public function update(Request $request, Offer $offer)
    {
        if (!$offer->isBrouillon()) {
            return redirect()->route('offers.show', $offer)
                ->with('error', 'Seules les offres en brouillon peuvent être modifiées.');
        }

        $validatedData = $request->validate([
            'salary' => 'required|numeric|min:0',
            'start_date' => 'required|date|after_or_equal:today',
            'details' => 'nullable|string',
            'status' => 'required|in:brouillon,envoyee'
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

    public function updateStatus(Request $request, Offer $offer)
    {
        $validatedData = $request->validate([
            'status' => 'required|in:acceptee,refusee'
        ]);

        try {
            DB::beginTransaction();

            if (!$offer->isEnvoyee()) {
                throw new \Exception('Seules les offres envoyées peuvent être acceptées ou refusées.');
            }

            $offer->update([
                'status' => $validatedData['status']
            ]);

            // Si l'offre est acceptée, mettre à jour le statut du candidat
            if ($validatedData['status'] === Offer::STATUS_ACCEPTEE) {
                $offer->candidate->update(['status' => Candidate::STATUS_EMBAUCHE]);
            }

            DB::commit();

            $message = $validatedData['status'] === Offer::STATUS_ACCEPTEE ? 
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