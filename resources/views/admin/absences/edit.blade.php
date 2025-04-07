{{-- resources/views/admin/absences/edit.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Modifier Absence #') }}{{ $absence->id }} ({{ $absence->employee->user->name ?? 'N/A' }} - {{ $absence->absence_date->format('d/m/Y') }})
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    @if ($errors->any())
                        <div class="mb-6 bg-red-100 ..." role="alert"> ... </div>
                    @endif

                    <form method="POST" action="{{ route('admin.absences.update', $absence->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="space-y-6">

                            {{-- Employé --}}
                            <div>
                                <label for="employee_id" class="block font-medium text-sm ...">{{ __('Employé Concerné') }} <span class="text-red-500">*</span></label>
                                <select name="employee_id" id="employee_id" class="block mt-1 w-full rounded-md ..." required>
                                    <option value="">-- Sélectionner --</option>
                                    @foreach($employees ?? [] as $emp)
                                        <option value="{{ $emp->id }}" {{ old('employee_id', $absence->employee_id) == $emp->id ? 'selected' : '' }}>
                                            {{ $emp->user->name ?? 'ID: '.$emp->id }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('employee_id') <p class="text-sm text-red-600 ...">{{ $message }}</p> @enderror
                            </div>

                             {{-- Date Absence --}}
                            <div>
                                <label for="absence_date" class="block font-medium text-sm ...">{{ __('Date de l\'Absence') }} <span class="text-red-500">*</span></label>
                                <input type="date" name="absence_date" id="absence_date" class="block mt-1 w-full rounded-md ..." value="{{ old('absence_date', $absence->absence_date->format('Y-m-d')) }}" required>
                                @error('absence_date') <p class="text-sm text-red-600 ...">{{ $message }}</p> @enderror
                            </div>

                             {{-- Heures (Optionnel) --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="start_time" class="block font-medium text-sm ...">{{ __('Heure Début (si pertinent)') }}</label>
                                    <input type="time" name="start_time" id="start_time" class="block mt-1 w-full rounded-md ..." value="{{ old('start_time', $absence->start_time ? Carbon\Carbon::parse($absence->start_time)->format('H:i') : '') }}">
                                    @error('start_time') <p class="text-sm text-red-600 ...">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="end_time" class="block font-medium text-sm ...">{{ __('Heure Fin (si pertinent)') }}</label>
                                    <input type="time" name="end_time" id="end_time" class="block mt-1 w-full rounded-md ..." value="{{ old('end_time', $absence->end_time ? Carbon\Carbon::parse($absence->end_time)->format('H:i') : '') }}">
                                    @error('end_time') <p class="text-sm text-red-600 ...">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            {{-- Motif --}}
                            <div>
                                <label for="reason_type" class="block font-medium text-sm ...">{{ __('Type / Motif') }}</label>
                                <input list="reason_type_list" name="reason_type" id="reason_type" class="block mt-1 w-full rounded-md ..." value="{{ old('reason_type', $absence->reason_type) }}" placeholder="Ex: Maladie, Injustifiée...">
                                <datalist id="reason_type_list">
                                    @foreach($reasonTypes as $reason)
                                        <option value="{{ $reason }}">
                                    @endforeach
                                </datalist>
                                @error('reason_type') <p class="text-sm text-red-600 ...">{{ $message }}</p> @enderror
                            </div>

                             {{-- Justifiée ? --}}
                            <div>
                                <label for="is_justified" class="block font-medium text-sm ...">{{ __('Absence Justifiée ?') }}</label>
                                <select name="is_justified" id="is_justified" class="block mt-1 w-full rounded-md ..." required>
                                    <option value="0" {{ old('is_justified', $absence->is_justified) == 0 ? 'selected' : '' }}>Non</option>
                                    <option value="1" {{ old('is_justified', $absence->is_justified) == 1 ? 'selected' : '' }}>Oui</option>
                                </select>
                                @error('is_justified') <p class="text-sm text-red-600 ...">{{ $message }}</p> @enderror
                            </div>

                            {{-- Notes --}}
                            <div>
                                <label for="notes" class="block font-medium text-sm ...">{{ __('Notes / Détails') }}</label>
                                <textarea name="notes" id="notes" rows="3" class="block mt-1 w-full rounded-md ...">{{ old('notes', $absence->notes) }}</textarea>
                                @error('notes') <p class="text-sm text-red-600 ...">{{ $message }}</p> @enderror
                            </div>

                        </div> {{-- Fin space-y-6 --}}

                        {{-- Boutons --}}
                        <div class="flex items-center justify-end mt-8 border-t pt-6">
                            <a href="{{ route('admin.absences.index') }}" class="underline text-sm ... mr-4">{{ __('Annuler') }}</a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 ...">
                                {{ __('Mettre à Jour Absence') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>