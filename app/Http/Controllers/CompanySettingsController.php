<?php

namespace App\Http\Controllers;

use App\Models\CompanySettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CompanySettingsController extends Controller
{
    public function index()
    {
        $settings = CompanySettings::first();
        return view('settings.company', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'company_name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'website' => 'nullable|url|max:255',
            'description' => 'nullable|string',
            'primary_color' => 'required|string|max:7',
            'secondary_color' => 'required|string|max:7',
        ]);

        $settings = CompanySettings::first() ?? new CompanySettings();

        if ($request->hasFile('logo')) {
            if ($settings->logo_path) {
                Storage::delete($settings->logo_path);
            }
            $logoPath = $request->file('logo')->store('company-logos', 'public');
            $settings->logo_path = $logoPath;
        }

        $settings->fill($request->except('logo'));
        $settings->save();

        return redirect()->route('admin.settings.company')->with('success', 'Paramètres de l\'entreprise mis à jour avec succès.');
    }
}
