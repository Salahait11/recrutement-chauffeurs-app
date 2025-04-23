<?php

namespace App\Http\Controllers;

use App\Models\LeaveType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class LeaveTypeController extends Controller
{
    // Protection des routes avec middleware
    

    /** Display a listing of the resource. */
    public function index(Request $request)
    {
        $query = LeaveType::query();

        // Filtre de recherche
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filtre par statut
        if ($request->has('is_active') && $request->input('is_active') !== '') {
            $query->where('is_active', $request->input('is_active'));
        }

        // Tri par défaut sur le nom
        $sort = $request->input('sort', 'name');
        $direction = $request->input('direction', 'asc');
        $query->orderBy($sort, $direction);

        $leaveTypes = $query->paginate(15);
        return view('admin.leave_types.index', compact('leaveTypes'));
    }

    /** Show the form for creating a new resource. */
    public function create()
    {
        $leaveType = new LeaveType([
            'requires_approval' => true,
            'affects_balance' => true,
            'is_active' => true,
            'color_code' => '#3498DB' // Couleur par défaut plus accessible
        ]);
        return view('admin.leave_types.create', compact('leaveType'));
    }

    /** Store a newly created resource in storage. */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:leave_types,name',
            'description' => 'nullable|string|max:500',
            'requires_approval' => 'required|boolean',
            'affects_balance' => 'required|boolean',
            'is_active' => 'required|boolean',
            'color_code' => [
                'required',
                'string',
                'max:7',
                'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/i'
            ],
        ], [
            'color_code.regex' => 'Le code couleur doit être au format hexadécimal (#RRGGBB ou #RGB).',
            'description.max' => 'La description ne doit pas dépasser 500 caractères.'
        ]);

        try {
            LeaveType::create($validatedData);
            return Redirect::route('admin.leave-types.index')
                ->with('success', 'Type de congé créé avec succès.');
        } catch (\Exception $e) {
            Log::error("Erreur création type de congé: " . $e->getMessage());
            return back()->withInput()
                ->with('error', 'Erreur lors de la création du type de congé.');
        }
    }

    /** Display the specified resource. */
    public function show(LeaveType $leaveType)
    {
        return view('admin.leave_types.show', compact('leaveType'));
    }

    /** Show the form for editing the specified resource. */
    public function edit(LeaveType $leaveType)
    {
        return view('admin.leave_types.edit', compact('leaveType'));
    }

    /** Update the specified resource in storage. */
    public function update(Request $request, LeaveType $leaveType)
    {
        $validatedData = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('leave_types')->ignore($leaveType->id)
            ],
            'description' => 'nullable|string|max:500',
            'requires_approval' => 'required|boolean',
            'affects_balance' => 'required|boolean',
            'is_active' => 'required|boolean',
            'color_code' => [
                'required',
                'string',
                'max:7',
                'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/i'
            ],
        ], [
            'color_code.regex' => 'Le code couleur doit être au format hexadécimal (#RRGGBB ou #RGB).',
            'description.max' => 'La description ne doit pas dépasser 500 caractères.'
        ]);

        try {
            $leaveType->update($validatedData);
            return Redirect::route('admin.leave-types.index')
                ->with('success', 'Type de congé mis à jour avec succès.');
        } catch (\Exception $e) {
            Log::error("Erreur mise à jour type de congé ID {$leaveType->id}: " . $e->getMessage());
            return back()->withInput()
                ->with('error', 'Erreur lors de la mise à jour du type de congé.');
        }
    }

    /** Remove the specified resource from storage. */
    public function destroy(LeaveType $leaveType)
    {
        if ($leaveType->leaveRequests()->exists()) {
            return Redirect::route('admin.leave-types.index')
                ->with('error', 'Impossible de supprimer ce type car il est utilisé par '.$leaveType->leaveRequests()->count().' demande(s) existante(s).');
        }

        try {
            $leaveType->delete();
            return Redirect::route('admin.leave-types.index')
                ->with('success', 'Type de congé supprimé avec succès.');
        } catch (\Exception $e) {
            Log::error("Erreur suppression type congé ID {$leaveType->id}: " . $e->getMessage());
            return Redirect::route('admin.leave-types.index')
                ->with('error', 'Erreur lors de la suppression du type de congé.');
        }
    }
}