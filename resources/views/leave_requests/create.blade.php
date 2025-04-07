{{-- resources/views/leave_requests/create.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Nouvelle Demande de Congé (par Admin)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                     {{-- Afficher les erreurs de validation --}}
                    @if ($errors->any())
                        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 dark:bg-red-900 dark:text-red-200 dark:border-red-700 px-4 py-3 rounded relative" role="alert">
                            <strong class="font-bold">Oups!</strong>
                            <span class="block sm:inline">Veuillez corriger les erreurs ci-dessous.</span>
                            <ul class="mt-3 list-disc list-inside text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('leave-requests.store') }}" enctype="multipart/form-data">
                        @csrf

                        {{-- Sélection de l'employé par l'Admin --}}
                        <div class="mb-6 pb-6 border-b border-gray-300 dark:border-gray-700">
                            <label for="employee_id" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Employé Concerné') }} <span class="text-red-500">*</span></label>
                            <select name="employee_id" id="employee_id" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600" required>
                                <option value="">-- Sélectionner un employé --</option>
                                {{-- $employees est passé par le contrôleur create() --}}
                                @foreach($employees ?? [] as $emp)
                                    <option value="{{ $emp->id }}" {{ old('employee_id') == $emp->id ? 'selected' : '' }}>
                                        {{ $emp->user->name ?? 'Employé ID: '.$emp->id }} {{-- Afficher nom user --}}
                                    </option>
                                @endforeach
                            </select>
                            @error('employee_id') <p class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</p> @enderror
                             @if(!isset($employees) || $employees->isEmpty())
                                 <p class="text-sm text-yellow-600 dark:text-yellow-400 mt-1">Aucun employé actif trouvé.</p>
                             @endif
                        </div>


                        {{-- Reste du formulaire --}}
                        <div class="space-y-6">
                            {{-- Type de Congé --}}
                            <div>
                                <label for="leave_type_id" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Type de Congé') }} <span class="text-red-500">*</span></label>
                                <select name="leave_type_id" id="leave_type_id" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600" required>
                                    <option value="">-- Sélectionner un type --</option>
                                    @foreach($leaveTypes as $type)
                                        <option value="{{ $type->id }}" {{ old('leave_type_id') == $type->id ? 'selected' : '' }}>
                                            {{ $type->name }}
                                        </option>
                                    @endforeach
                                </select>
                                 @error('leave_type_id') <p class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</p> @enderror
                            </div>

                            {{-- Dates Début et Fin --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="start_date" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Date et Heure de Début') }} <span class="text-red-500">*</span></label>
                                    <input type="datetime-local" name="start_date" id="start_date" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600" value="{{ old('start_date') }}" required>
                                     @error('start_date') <p class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="end_date" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Date et Heure de Fin') }} <span class="text-red-500">*</span></label>
                                    <input type="datetime-local" name="end_date" id="end_date" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600" value="{{ old('end_date') }}" required>
                                     @error('end_date') <p class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            {{-- Motif / Raison --}}
                            <div>
                                <label for="reason" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Motif / Commentaires') }}</label>
                                <textarea name="reason" id="reason" rows="4" placeholder="Raison de la demande (optionnel)..." class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">{{ old('reason') }}</textarea>
                                 @error('reason') <p class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</p> @enderror
                            </div>

                             {{-- Justificatif (Optionnel) --}}
                            <div>
                                <label for="attachment" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Justificatif (si nécessaire)') }}</label>
                                <input type="file" name="attachment" id="attachment" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 mt-1">
                                @error('attachment') <p class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        {{-- Boutons --}}
                        <div class="flex items-center justify-end mt-8 border-t border-gray-200 dark:border-gray-700 pt-6">
                            <a href="{{ route('leave-requests.index') }}" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 mr-4">{{ __('Annuler') }}</a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 active:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                {{ __('Soumettre la Demande') }}
                            </button>
                         </div>

                    </form>
                </div> {{-- Fin p-6 --}}
            </div> {{-- Fin bg-white --}}
        </div> {{-- Fin max-w --}}
    </div> {{-- Fin py-12 --}}
</x-app-layout>