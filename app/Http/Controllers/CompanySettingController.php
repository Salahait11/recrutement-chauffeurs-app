<?php

namespace App\Http\Controllers;

use App\Models\CompanySetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CompanySettingController extends Controller
{
    public function edit()
    {
        $settings = CompanySetting::getSettings();
        return view('company-settings.edit', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'legal_name' => 'required|string|max:255',
            'registration_number' => 'required|string|max:255',
            'vat_number' => 'nullable|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'postal_code' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'website' => 'nullable|url|max:255',
            'logo' => 'nullable|image|max:2048',
            'description' => 'nullable|string',
            'working_hours' => 'nullable|array',
            'holiday_calendar' => 'nullable|array',
            'leave_policy' => 'nullable|array',
        ]);

        $settings = CompanySetting::getSettings();

        if ($request->hasFile('logo')) {
            if ($settings->logo_path) {
                Storage::delete($settings->logo_path);
            }
            $validated['logo_path'] = $request->file('logo')->store('company-logos', 'public');
        }

        $settings->fill($validated);
        $settings->save();

        return redirect()->route('company-settings.edit')
            ->with('success', 'Les paramètres de l\'entreprise ont été mis à jour avec succès.');
    }
} 