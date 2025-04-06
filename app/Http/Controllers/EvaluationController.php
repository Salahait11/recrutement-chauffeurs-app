<?php

namespace App\Http\Controllers;

use App\Models\Evaluation;
use App\Models\Interview;
use App\Models\DrivingTest;
use App\Models\EvaluationCriterion;
use App\Models\EvaluationResponse;
use App\Models\Candidate; // Bien que non utilisé directement ici, bon à avoir
use App\Models\User;      // Idem
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;

class EvaluationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
          // Lister toutes les évaluations (peut-être utile pour un admin/RH)
         $evaluations = Evaluation::with(['candidate', 'evaluator', 'interview'])
                                 ->orderBy('created_at', 'desc')
                                 ->paginate(20);
        return view('evaluations.index', compact('evaluations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function createForInterview(Interview $interview)
    {
        // Charger le candidat associé à l'entretien
        $interview->load('candidate');

        // Récupérer les critères d'évaluation actifs
        $criteria = EvaluationCriterion::where('is_active', true)->orderBy('category')->orderBy('name')->get();

        // Passer l'entretien et les critères à la vue
        return view('evaluations.create', compact('interview', 'criteria'));
    }
     public function create()
     {
         // Probablement rediriger ou afficher une erreur car on veut créer via un entretien/test
         abort(404, 'Veuillez créer une évaluation à partir d\'un entretien ou d\'un test.');
     }

    /**
     * Store a newly created resource in storage.
     */
   public function store(Request $request)
    {
        // 1. Valider les données
        // Note: j'utilise $validatedData ici pour stocker le résultat de la validation
        $validatedData = $request->validate([
            'interview_id' => 'nullable|required_without:driving_test_id|exists:interviews,id',
            'driving_test_id' => 'nullable|required_without:interview_id|exists:driving_tests,id',
            'candidate_id' => 'required|exists:candidates,id',
            'conclusion' => 'nullable|string',
            'recommendation' => 'nullable|in:positive,neutral,negative',
            // 'overall_rating' => 'nullable|integer|min:1|max:5', // Si activé
            'ratings' => 'required|array',
            'ratings.*' => 'required|integer|min:1|max:5',
            'comments' => 'nullable|array',
            'comments.*' => 'nullable|string',
        ], [
            'ratings.required' => 'Veuillez noter tous les critères.',
            'ratings.*.required' => 'Une note est manquante pour un critère.',
            'ratings.*.integer' => 'Les notes doivent être des chiffres.',
            'ratings.*.min' => 'Les notes doivent être au minimum 1.',
            'ratings.*.max' => 'Les notes doivent être au maximum 5.',
            'interview_id.required_without' => 'L\'identifiant de l\'entretien ou du test est manquant.',
            'driving_test_id.required_without' => 'L\'identifiant de l\'entretien ou du test est manquant.',
            'interview_id.exists' => 'L\'entretien spécifié n\'existe pas.',
            'driving_test_id.exists' => 'Le test de conduite spécifié n\'existe pas.',
            'candidate_id.required' => 'L\'identifiant du candidat est manquant.',
            'candidate_id.exists' => 'Le candidat spécifié n\'existe pas.',
        ]);

        // Vérifier que tous les critères actifs ont reçu une note
        $activeCriteriaIds = EvaluationCriterion::where('is_active', true)->pluck('id')->all();
        $ratedCriteriaIds = array_keys($validatedData['ratings']);
         if (count(array_diff($activeCriteriaIds, $ratedCriteriaIds)) > 0) {
             // Trouver les IDs manquants pour un message plus précis (optionnel)
             $missingIds = array_diff($activeCriteriaIds, $ratedCriteriaIds);
             $missingNames = EvaluationCriterion::whereIn('id', $missingIds)->pluck('name')->implode(', ');
             return Redirect::back()->withInput()->with('error', 'Erreur : Les critères suivants n\'ont pas été notés : ' . $missingNames);
             // Ou message générique: return Redirect::back()->withInput()->with('error', 'Erreur : Tous les critères n\'ont pas été notés.');
         }


        DB::beginTransaction();

        try {
            // 2. Créer l'enregistrement principal 'Evaluation'
            // Utilisation de $validatedData partout ici
            $evaluation = Evaluation::create([
                'candidate_id' => $validatedData['candidate_id'],
                'evaluator_id' => Auth::id(),
                'interview_id' => $validatedData['interview_id'] ?? null,
                'driving_test_id' => $validatedData['driving_test_id'] ?? null,
                'conclusion' => $validatedData['conclusion'] ?? null,
                'recommendation' => $validatedData['recommendation'] ?? null,
               // 'overall_rating' => $validatedData['overall_rating'] ?? null, // Si activé
            ]);

            // 3. Préparer les données pour les 'EvaluationResponse'
            $responsesData = [];
            foreach ($validatedData['ratings'] as $criterionId => $rating) {
                // Utilisation de $validatedData ici aussi pour les commentaires
                 if (in_array($criterionId, $activeCriteriaIds)) {
                    $responsesData[] = [
                        'evaluation_id' => $evaluation->id,
                        'criterion_id' => $criterionId,
                        'rating' => $rating,
                        'comment' => $validatedData['comments'][$criterionId] ?? null, // Correction ici
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }

             // 4. Insérer toutes les réponses
            if (!empty($responsesData)) {
                 foreach ($responsesData as $responseData) {
                     EvaluationResponse::create($responseData);
                 }
            } else {
                 throw new \Exception("Aucune réponse d'évaluation valide à enregistrer.");
            }

            // Optionnel : Mettre à jour le statut de l'élément évalué
            $itemEvaluated = null;
            if ($evaluation->interview_id) {
                 $itemEvaluated = Interview::find($evaluation->interview_id);
            } elseif ($evaluation->driving_test_id) {
                 $itemEvaluated = DrivingTest::find($evaluation->driving_test_id);
                 // Mettre à jour 'passed' et 'results_summary' du test ?
                 // $itemEvaluated->passed = ($evaluation->recommendation === 'positive'); // Exemple simple
                 // $itemEvaluated->results_summary = $evaluation->conclusion; // Exemple simple
            }

            if ($itemEvaluated && $itemEvaluated->status === 'scheduled') {
                 $itemEvaluated->status = 'completed';
                 $itemEvaluated->save();
            }

            // 5. Confirmer la transaction
            DB::commit();

            // 6. Rediriger
             if ($evaluation->interview_id) {
                 return Redirect::route('interviews.show', $evaluation->interview_id)->with('success', 'Évaluation enregistrée avec succès !');
             } elseif ($evaluation->driving_test_id) {
                 return Redirect::route('driving-tests.show', $evaluation->driving_test_id)->with('success', 'Évaluation enregistrée avec succès !');
             } else {
                  return Redirect::route('evaluations.index')->with('success', 'Évaluation enregistrée avec succès !'); // Fallback
             }

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error("Erreur enregistrement évaluation: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return Redirect::back()->withInput()->with('error', 'Une erreur est survenue lors de l\'enregistrement de l\'évaluation.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Evaluation $evaluation)
{
     // Charger les relations pour l'affichage détaillé
     // 'candidate' : Pour savoir qui a été évalué
     // 'evaluator' : Pour savoir qui a fait l'évaluation
     // 'interview' : Pour savoir à quel entretien elle est liée
     // 'responses.criterion' : Pour avoir les réponses ET le nom du critère associé à chaque réponse
    $evaluation->load(['candidate', 'evaluator', 'interview', 'responses.criterion']);
    return view('evaluations.show', compact('evaluation'));
}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Evaluation $evaluation)
    {
        // Logique pour modifier une évaluation existante (peut-être complexe)
        $evaluation->load(['candidate', 'interview', 'responses.criterion']);
        $criteria = EvaluationCriterion::where('is_active', true)->orderBy('category')->orderBy('name')->get();

        return view('evaluations.edit', compact('evaluation', 'criteria'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Evaluation $evaluation)
    {
        // Logique de mise à jour à implémenter ici
         return Redirect::route('evaluations.index')->with('info', 'Fonctionnalité de modification évaluation non implémentée.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Evaluation $evaluation)
{
    // Récupérer l'ID de l'entretien lié (si applicable) pour la redirection
    $interviewId = $evaluation->interview_id;

    try {
        // Grâce à onDelete('cascade') sur la table evaluation_responses,
        // la suppression de l'évaluation supprimera automatiquement les réponses associées.
        $evaluation->delete();

        // Rediriger vers la page de l'entretien (si elle vient de là) ou la liste des évaluations
        if ($interviewId) {
            return Redirect::route('interviews.show', $interviewId)->with('success', 'Évaluation supprimée avec succès !');
        } else {
            // Si l'évaluation n'est pas liée à un entretien (ex: test de conduite), rediriger vers la liste des évaluations ou du candidat
             return Redirect::route('evaluations.index')->with('success', 'Évaluation supprimée avec succès !');
             // Ou peut-être : return Redirect::route('candidates.show', $evaluation->candidate_id)->with('success', 'Évaluation supprimée avec succès !');
        }

    } catch (\Exception $e) {
        \Log::error("Erreur suppression évaluation ID {$evaluation->id}: " . $e->getMessage());

         // Redirection arrière avec message d'erreur
         return Redirect::back()->with('error', 'Erreur lors de la suppression de l\'évaluation.');
    }
}
 public function createForDrivingTest(DrivingTest $drivingTest) // Nouvelle méthode
    {
        // Charger le candidat associé au test
        $drivingTest->load('candidate');

        // Récupérer les critères d'évaluation actifs
        // Peut-être filtrer les critères spécifiques aux tests de conduite?
        // Pour l'instant, on prend tous les critères actifs.
        $criteria = EvaluationCriterion::where('is_active', true)
                                         // ->where('category', 'Test Conduite') // Exemple de filtre
                                         ->orderBy('category')->orderBy('name')->get();

        // Passer le test de conduite et les critères à la vue de création d'évaluation
        // On réutilise la même vue 'evaluations.create'
        return view('evaluations.create', compact('drivingTest', 'criteria'));
         // Attention: la vue 'evaluations.create' doit maintenant gérer
         // soit $interview, soit $drivingTest.
    }
}
