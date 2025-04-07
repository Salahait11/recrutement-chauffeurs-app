<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;

class VehicleController extends Controller
{
     // Pas de constructeur, middleware sur la route

    /** Display a listing of the resource. */
    public function index()
    {
        $vehicles = Vehicle::orderBy('brand')->orderBy('model')->paginate(15);
        return view('admin.vehicles.index', compact('vehicles')); // Vue admin/vehicles/index
    }

    /** Show the form for creating a new resource. */
    public function create()
    {
        $vehicle = new Vehicle(['is_available' => true]); // Pour valeurs par défaut
        return view('admin.vehicles.create', compact('vehicle')); // Vue admin/vehicles/create
    }

    /** Store a newly created resource in storage. */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'plate_number' => 'required|string|max:20|unique:vehicles,plate_number',
            'brand' => 'nullable|string|max:100',
            'model' => 'nullable|string|max:100',
            'type' => 'nullable|string|max:100',
            'year' => 'nullable|integer|min:1900|max:'.(date('Y') + 1), // Année valide
            'is_available' => 'required|boolean',
            'notes' => 'nullable|string',
        ]);
        $validatedData['is_available'] = (bool) $validatedData['is_available'];

        try {
            Vehicle::create($validatedData);
            return Redirect::route('admin.vehicles.index')->with('success', 'Véhicule ajouté.');
        } catch (\Exception $e) {
            Log::error("Erreur création véhicule: ".$e->getMessage());
            return Redirect::back()->withInput()->with('error', 'Erreur création véhicule.');
        }
    }

    /** Display the specified resource. */
    public function show(Vehicle $vehicle)
    {
          // Pas très utile, rediriger vers edit
         return redirect()->route('admin.vehicles.edit', $vehicle->id);
    }

    /** Show the form for editing the specified resource. */
    public function edit(Vehicle $vehicle)
    {
         return view('admin.vehicles.edit', compact('vehicle')); // Vue admin/vehicles/edit
    }

    /** Update the specified resource in storage. */
    public function update(Request $request, Vehicle $vehicle)
    {
         $validatedData = $request->validate([
            'plate_number' => ['required','string','max:20', Rule::unique('vehicles')->ignore($vehicle->id)],
            'brand' => 'nullable|string|max:100',
            'model' => 'nullable|string|max:100',
            'type' => 'nullable|string|max:100',
            'year' => 'nullable|integer|min:1900|max:'.(date('Y') + 1),
            'is_available' => 'required|boolean',
            'notes' => 'nullable|string',
        ]);
         $validatedData['is_available'] = (bool) $validatedData['is_available'];

        try {
            $vehicle->update($validatedData);
            return Redirect::route('admin.vehicles.index')->with('success', 'Véhicule mis à jour.');
        } catch (\Exception $e) {
             Log::error("Erreur MAJ véhicule ID {$vehicle->id}: ".$e->getMessage());
             return Redirect::back()->withInput()->with('error', 'Erreur mise à jour véhicule.');
        }
    }

    /** Remove the specified resource from storage. */
    public function destroy(Vehicle $vehicle)
    {
         // Vérifier si utilisé dans des tests de conduite ?
          if ($vehicle->drivingTests()->exists()) {
               return Redirect::route('admin.vehicles.index')->with('error', 'Suppression impossible: véhicule utilisé dans des tests.');
          }

         try {
            $vehicle->delete();
            return Redirect::route('admin.vehicles.index')->with('success', 'Véhicule supprimé.');
         } catch (QueryException $e) {
            Log::error("Erreur suppression véhicule FK ID {$vehicle->id}: ".$e->getMessage());
            return Redirect::route('admin.vehicles.index')->with('error', 'Erreur suppression: contrainte BDD.');
         } catch (\Exception $e) {
             Log::error("Erreur suppression véhicule ID {$vehicle->id}: ".$e->getMessage());
             return Redirect::route('admin.vehicles.index')->with('error', 'Erreur suppression.');
         }
    }
}