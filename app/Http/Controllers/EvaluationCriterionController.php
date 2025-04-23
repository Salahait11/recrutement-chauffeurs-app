<?php

namespace App\Http\Controllers;

use App\Models\EvaluationCriterion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;

class EvaluationCriterionController extends Controller
{
    // Middleware appliqué par la route

    /** Display a listing of the resource. */
    public function index(Request $request)
    {
        $query = EvaluationCriterion::query();

        // Filtre de recherche
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
            });
        }

        // Filtre par catégorie
        if ($request->has('category') && $request->input('category') !== '') {
            $query->where('category', $request->input('category'));
        }

        // Tri par défaut sur la catégorie puis le nom
        $sort = $request->input('sort', 'category');
        $direction = $request->input('direction', 'asc');
        $query->orderBy($sort, $direction)
              ->orderBy('name', 'asc');

        $criteria = $query->paginate(20);
        $categories = EvaluationCriterion::select('category')->distinct()->whereNotNull('category')->pluck('category');
        
        return view('admin.evaluation_criteria.index', compact('criteria', 'categories'));
    }

    /** Show the form for creating a new resource. */
    public function create()
    {
        $criterion = new EvaluationCriterion(['is_active' => true]);
         // On pourrait passer les catégories existantes pour un select/datalist
         $categories = EvaluationCriterion::select('category')->distinct()->whereNotNull('category')->pluck('category');
        return view('admin.evaluation_criteria.create', compact('criterion', 'categories'));
    }

    /** Store a newly created resource in storage. */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:evaluation_criteria,name',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:100',
            'is_active' => 'required|boolean',
        ]);
        $validatedData['is_active'] = (bool) $validatedData['is_active'];

        try {
            EvaluationCriterion::create($validatedData);
            return Redirect::route('admin.evaluation-criteria.index')->with('success', 'Critère créé.');
        } catch (\Exception $e) {
             Log::error("Erreur création critère: ".$e->getMessage());
             return Redirect::back()->withInput()->with('error', 'Erreur création critère.');
        }
    }

     /** Display the specified resource. */
    public function show(EvaluationCriterion $criterion) // Utilise $criterion grâce à parameters()
    {
         return redirect()->route('admin.evaluation-criteria.edit', $criterion->id);
    }

    /** Show the form for editing the specified resource. */
    public function edit(EvaluationCriterion $criterion)
    {
         $categories = EvaluationCriterion::select('category')->distinct()->whereNotNull('category')->pluck('category');
         return view('admin.evaluation_criteria.edit', compact('criterion', 'categories'));
    }

    /** Update the specified resource in storage. */
    public function update(Request $request, EvaluationCriterion $criterion)
    {
         $validatedData = $request->validate([
            'name' => ['required','string','max:255', Rule::unique('evaluation_criteria')->ignore($criterion->id)],
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:100',
            'is_active' => 'required|boolean',
         ]);
         $validatedData['is_active'] = (bool) $validatedData['is_active'];

         try {
             $criterion->update($validatedData);
             return Redirect::route('admin.evaluation-criteria.index')->with('success', 'Critère mis à jour.');
         } catch (\Exception $e) {
              Log::error("Erreur MAJ critère ID {$criterion->id}: ".$e->getMessage());
              return Redirect::back()->withInput()->with('error', 'Erreur mise à jour.');
         }
    }

    /** Remove the specified resource from storage. */
    public function destroy(EvaluationCriterion $criterion)
    {
         // Vérifier si utilisé dans des réponses d'évaluation
         if ($criterion->responses()->exists()) {
             return Redirect::route('admin.evaluation-criteria.index')->with('error', 'Suppression impossible: critère utilisé dans des évaluations.');
         }

         try {
             $criterion->delete();
             return Redirect::route('admin.evaluation-criteria.index')->with('success', 'Critère supprimé.');
          } catch (QueryException $e) {
             Log::error("Erreur suppression critère FK ID {$criterion->id}: ".$e->getMessage());
             return Redirect::route('admin.evaluation-criteria.index')->with('error', 'Erreur suppression: contrainte BDD.');
          } catch (\Exception $e) {
              Log::error("Erreur suppression critère ID {$criterion->id}: ".$e->getMessage());
              return Redirect::route('admin.evaluation-criteria.index')->with('error', 'Erreur suppression.');
          }
    }
}