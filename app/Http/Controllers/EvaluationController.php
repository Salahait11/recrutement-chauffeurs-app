<?php

namespace App\Http\Controllers;

use App\Models\Evaluation;
use App\Models\Interview;
use App\Models\EvaluationCriterion;
use App\Models\EvaluationResponse;
use App\Models\Candidate;
use App\Models\User;
use App\Models\DrivingTest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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

        // Récupérer les critères d'évaluation actifs pour les entretiens
        $criteria = EvaluationCriterion::where('is_active', true)
            ->where(function($query) {
                $query->where('category', 'Entretien RH')
                      ->orWhere('category', 'Entretien Technique')
                      ->orWhere('category', 'Général');
            })
            ->orderBy('category')
            ->orderBy('name')
            ->get();

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
        // Validation des données
        $validated = $request->validate([
            'interview_id' => 'required_without:driving_test_id|exists:interviews,id',
            'driving_test_id' => 'required_without:interview_id|exists:driving_tests,id',
            'candidate_id' => 'required|exists:candidates,id',
            'recommendation' => 'required|in:positive,neutral,negative',
            'conclusion' => 'nullable|string|max:1000',
            'responses' => 'required|array',
            'responses.*.criterion_id' => 'required|exists:evaluation_criteria,id',
            'responses.*.rating' => 'required|integer|min:1|max:5',
            'responses.*.comments' => 'nullable|string|max:500',
        ]);

        try {
            DB::beginTransaction();

            // Créer l'évaluation
            $evaluation = new Evaluation([
                'candidate_id' => $validated['candidate_id'],
                'recommendation' => $validated['recommendation'],
                'conclusion' => $validated['conclusion'],
                'evaluator_id' => auth()->id(),
            ]);

            if (isset($validated['interview_id'])) {
                $evaluation->interview_id = $validated['interview_id'];
                $interview = Interview::findOrFail($validated['interview_id']);
            } else {
                $evaluation->driving_test_id = $validated['driving_test_id'];
                $drivingTest = DrivingTest::findOrFail($validated['driving_test_id']);
            }

            $evaluation->save();

            // Créer les réponses aux critères
            foreach ($validated['responses'] as $response) {
                $evaluation->responses()->create([
                    'criterion_id' => $response['criterion_id'],
                    'rating' => $response['rating'],
                    'comments' => $response['comments'] ?? null,
                ]);
            }

            // Mettre à jour le statut de l'entretien si nécessaire
            if (isset($interview)) {
                $interview->update(['status' => Interview::STATUS_EVALUE]);
            }

            DB::commit();

            // Rediriger vers la page appropriée
            if (isset($interview)) {
                return redirect()->route('interviews.show', $interview)
                    ->with('success', __('L\'évaluation a été créée avec succès.'));
            } else {
                return redirect()->route('driving-tests.show', $drivingTest)
                    ->with('success', __('L\'évaluation a été créée avec succès.'));
            }

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors de la création de l\'évaluation : ' . $e->getMessage());
            
            return back()->with('error', __('Une erreur est survenue lors de la création de l\'évaluation.'));
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
        // 'driving_test' : Pour savoir à quel test de conduite elle est liée
        // 'responses.criterion' : Pour avoir les réponses ET le nom du critère associé à chaque réponse
        $evaluation->load(['candidate', 'evaluator', 'interview', 'responses.criterion']);
        return view('evaluations.show', compact('evaluation'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Evaluation $evaluation)
    {
        // Charger les relations nécessaires
        $evaluation->load(['candidate', 'interview', 'drivingTest', 'responses.criterion']);

        // Récupérer les critères appropriés selon le type d'évaluation
        if ($evaluation->interview_id) {
            $criteria = EvaluationCriterion::where('is_active', true)
                ->where(function($query) {
                    $query->where('category', 'Entretien RH')
                          ->orWhere('category', 'Entretien Technique')
                          ->orWhere('category', 'Général');
                })
                ->orderBy('category')
                ->orderBy('name')
                ->get();
            $interview = $evaluation->interview;
            return view('evaluations.edit', compact('evaluation', 'criteria', 'interview'));
        } else {
            $criteria = EvaluationCriterion::where('is_active', true)
                ->where('category', 'Test Conduite')
                ->orderBy('name')
                ->get();
            $drivingTest = $evaluation->drivingTest;
            return view('evaluations.edit', compact('evaluation', 'criteria', 'drivingTest'));
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Evaluation $evaluation)
    {
        // Validation des données
        $validated = $request->validate([
            'recommendation' => 'required|in:positive,neutral,negative',
            'conclusion' => 'nullable|string|max:1000',
            'responses' => 'required|array',
            'responses.*.criterion_id' => 'required|exists:evaluation_criteria,id',
            'responses.*.rating' => 'required|integer|min:1|max:5',
            'responses.*.comments' => 'nullable|string|max:500',
        ]);

        try {
            DB::beginTransaction();

            // Mettre à jour l'évaluation
            $evaluation->update([
                'recommendation' => $validated['recommendation'],
                'conclusion' => $validated['conclusion'],
            ]);

            // Mettre à jour les réponses aux critères
            foreach ($validated['responses'] as $criterionId => $response) {
                $evaluation->responses()->updateOrCreate(
                    ['criterion_id' => $response['criterion_id']],
                    [
                        'rating' => $response['rating'],
                        'comments' => $response['comments'] ?? null,
                    ]
                );
            }

            DB::commit();

            // Rediriger vers la page de détail de l'évaluation
            return redirect()->route('evaluations.show', $evaluation)
                ->with('success', __('L\'évaluation a été mise à jour avec succès.'));

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erreur lors de la mise à jour de l\'évaluation : ' . $e->getMessage());
            
            return back()->with('error', __('Une erreur est survenue lors de la mise à jour de l\'évaluation.'));
        }
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

    public function createForDrivingTest(DrivingTest $drivingTest)
    {
        // Charger le candidat associé au test
        $drivingTest->load('candidate');

        // Récupérer les critères d'évaluation actifs pour les tests de conduite
        $criteria = EvaluationCriterion::where('is_active', true)
            ->where('category', 'Test Conduite')
            ->orderBy('name')
            ->get();

        // Passer le test et les critères à la vue
        return view('evaluations.create', compact('drivingTest', 'criteria'));
    }

    /**
     * Store a newly created evaluation for a driving test in storage.
     */
    public function storeForDrivingTest(Request $request, DrivingTest $drivingTest)
    {
        // Ajouter l'ID du test de conduite aux données de la requête
        $request->merge(['driving_test_id' => $drivingTest->id]);
        
        // Utiliser la méthode store existante
        return $this->store($request);
    }
}