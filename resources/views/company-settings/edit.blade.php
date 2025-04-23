@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Paramètres de l'entreprise</h1>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('company-settings.update') }}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Informations générales</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="company_name" class="form-label">Nom de l'entreprise *</label>
                            <input type="text" class="form-control @error('company_name') is-invalid @enderror" 
                                id="company_name" name="company_name" value="{{ old('company_name', $settings->company_name) }}" required>
                            @error('company_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="legal_name" class="form-label">Nom légal *</label>
                            <input type="text" class="form-control @error('legal_name') is-invalid @enderror" 
                                id="legal_name" name="legal_name" value="{{ old('legal_name', $settings->legal_name) }}" required>
                            @error('legal_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="registration_number" class="form-label">Numéro d'enregistrement *</label>
                            <input type="text" class="form-control @error('registration_number') is-invalid @enderror" 
                                id="registration_number" name="registration_number" value="{{ old('registration_number', $settings->registration_number) }}" required>
                            @error('registration_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="vat_number" class="form-label">Numéro de TVA</label>
                            <input type="text" class="form-control @error('vat_number') is-invalid @enderror" 
                                id="vat_number" name="vat_number" value="{{ old('vat_number', $settings->vat_number) }}">
                            @error('vat_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="logo" class="form-label">Logo</label>
                            @if($settings->logo_path)
                                <div class="mb-2">
                                    <img src="{{ Storage::url($settings->logo_path) }}" alt="Logo" class="img-thumbnail" style="max-height: 100px;">
                                </div>
                            @endif
                            <input type="file" class="form-control @error('logo') is-invalid @enderror" 
                                id="logo" name="logo" accept="image/*">
                            @error('logo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Coordonnées</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email *</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                id="email" name="email" value="{{ old('email', $settings->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">Téléphone *</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                id="phone" name="phone" value="{{ old('phone', $settings->phone) }}" required>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="website" class="form-label">Site web</label>
                            <input type="url" class="form-control @error('website') is-invalid @enderror" 
                                id="website" name="website" value="{{ old('website', $settings->website) }}">
                            @error('website')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Adresse *</label>
                            <input type="text" class="form-control @error('address') is-invalid @enderror" 
                                id="address" name="address" value="{{ old('address', $settings->address) }}" required>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="city" class="form-label">Ville *</label>
                                <input type="text" class="form-control @error('city') is-invalid @enderror" 
                                    id="city" name="city" value="{{ old('city', $settings->city) }}" required>
                                @error('city')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="state" class="form-label">État/Région *</label>
                                <input type="text" class="form-control @error('state') is-invalid @enderror" 
                                    id="state" name="state" value="{{ old('state', $settings->state) }}" required>
                                @error('state')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="postal_code" class="form-label">Code postal *</label>
                                <input type="text" class="form-control @error('postal_code') is-invalid @enderror" 
                                    id="postal_code" name="postal_code" value="{{ old('postal_code', $settings->postal_code) }}" required>
                                @error('postal_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="country" class="form-label">Pays *</label>
                                <input type="text" class="form-control @error('country') is-invalid @enderror" 
                                    id="country" name="country" value="{{ old('country', $settings->country) }}" required>
                                @error('country')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Informations supplémentaires</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" 
                        id="description" name="description" rows="3">{{ old('description', $settings->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="working_hours" class="form-label">Horaires de travail</label>
                    <textarea class="form-control @error('working_hours') is-invalid @enderror" 
                        id="working_hours" name="working_hours" rows="3">{{ old('working_hours', $settings->working_hours) }}</textarea>
                    @error('working_hours')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="holiday_calendar" class="form-label">Calendrier des jours fériés</label>
                    <textarea class="form-control @error('holiday_calendar') is-invalid @enderror" 
                        id="holiday_calendar" name="holiday_calendar" rows="3">{{ old('holiday_calendar', $settings->holiday_calendar) }}</textarea>
                    @error('holiday_calendar')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="leave_policy" class="form-label">Politique de congés</label>
                    <textarea class="form-control @error('leave_policy') is-invalid @enderror" 
                        id="leave_policy" name="leave_policy" rows="3">{{ old('leave_policy', $settings->leave_policy) }}</textarea>
                    @error('leave_policy')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
            <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
        </div>
    </form>
</div>
@endsection 