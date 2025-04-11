{{-- resources/views/candidates/show.blade.php --}}
<x-app-layout>
    @php use App\Models\Candidate; @endphp

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Détails du Candidat :') }} {{ $candidate->first_name }} {{ $candidate->last_name }}
            </h2>
            {{-- Bouton pour retourner à la liste --}}
            <a href="{{ route('candidates.index') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150">
                {{ __('Retour à la liste') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6"> {{-- Ajout space-y-6 pour espacer les blocs --}}

            {{-- Bloc Informations Candidat --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 space-y-6">

                    {{-- Afficher les messages flash généraux --}}
                    @if (session('success'))
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif

                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                        Informations Personnelles
                    </h3>

                    {{-- Détails du candidat --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="md:col-span-1 font-semibold">{{ __('Prénom') }}</div>
                        <div class="md:col-span-2">{{ $candidate->first_name }}</div>

                        <div class="md:col-span-1 font-semibold">{{ __('Nom') }}</div>
                        <div class="md:col-span-2">{{ $candidate->last_name }}</div>

                        <div class="md:col-span-1 font-semibold">{{ __('Email') }}</div>
                        <div class="md:col-span-2">{{ $candidate->email }}</div>

                        <div class="md:col-span-1 font-semibold">{{ __('Téléphone') }}</div>
                        <div class="md:col-span-2">{{ $candidate->phone }}</div>

                        <div class="md:col-span-1 font-semibold">{{ __('Adresse') }}</div>
                        <div class="md:col-span-2">{{ $candidate->address ?? 'Non renseignée' }}</div>

                        <div class="md:col-span-1 font-semibold">{{ __('Date de Naissance') }}</div>
                        <div class="md:col-span-2">{{ $candidate->birth_date ? \Carbon\Carbon::parse($candidate->birth_date)->format('d/m/Y') : 'Non renseignée' }}</div>

                        <div class="md:col-span-1 font-semibold">{{ __('Numéro Permis') }}</div>
                        <div class="md:col-span-2">{{ $candidate->driving_license_number ?? 'Non renseigné' }}</div>

                        <div class="md:col-span-1 font-semibold">{{ __('Expiration Permis') }}</div>
                        <div class="md:col-span-2">{{ $candidate->driving_license_expiry ? \Carbon\Carbon::parse($candidate->driving_license_expiry)->format('d/m/Y') : 'Non renseignée' }}</div>

                        <div class="md:col-span-1 font-semibold">{{ __('Années d\'expérience') }}</div>
                        <div class="md:col-span-2">{{ $candidate->years_of_experience }} {{ Str::plural('an', $candidate->years_of_experience) }}</div>

                        <div class="md:col-span-1 font-semibold">{{ __('Statut') }}</div>
                        <div class="md:col-span-2 flex items-center space-x-4">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                @if($candidate->status === Candidate::STATUS_EMBAUCHE)
                                    bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100
                                @elseif($candidate->status === Candidate::STATUS_REFUSE)
                                    bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100
                                @else
                                    bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100
                                @endif
                            ">
                                {{ Candidate::$statuses[$candidate->status] }}
                            </span>
                            
                            <form method="POST" action="{{ route('candidates.update', $candidate->id) }}">
                                @csrf
                                @method('PUT')
                                <select name="status" onchange="this.form.submit()" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <option disabled>{{ __('Changer le statut') }}</option>
                                    @foreach(Candidate::$statuses as $value => $label)
                                        <option value="{{ $value }}" {{ $candidate->status === $value ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </form>
                        </div>
                        <div class="md:col-span-1 font-semibold">{{ __('Notes') }}</div>
                        <div class="md:col-span-2 whitespace-pre-wrap">{{ $candidate->notes ?? 'Aucune note' }}</div>

                        <div class="md:col-span-1 font-semibold">{{ __('Date de Création') }}</div>
                        <div class="md:col-span-2">{{ $candidate->created_at->format('d/m/Y H:i') }}</div>

                        <div class="md:col-span-1 font-semibold">{{ __('Dernière Mise à Jour') }}</div>
                        <div class="md:col-span-2">{{ $candidate->updated_at->format('d/m/Y H:i') }}</div>
                    </div>

                    <hr class="dark:border-gray-600">

                    {{-- Actions sur le candidat --}}
                    <div class="flex justify-between items-center mt-6">
                        {{-- Bouton Créer Offre --}}
                        <div>
                            <a href="{{ route('offers.create-for-candidate', $candidate) }}" class="inline-flex items-center px-4 py-2 bg-teal-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-teal-500 active:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                {{ __('Créer une Offre') }}
                            </a>
                            {{-- Autres boutons d'action rapide possibles ici --}}
                            {{-- <a href="{{ route('interviews.create', ['candidate_id' => $candidate->id]) }}" class="ml-3 ...">Planifier Entretien</a> --}}
                            {{-- <a href="{{ route('driving-tests.create', ['candidate_id' => $candidate->id]) }}" class="ml-3 ...">Planifier Test</a> --}}
                        </div>

                        {{-- Boutons Modifier/Supprimer Candidat --}}
                        <div class="flex space-x-3">
                            <a href="{{ route('candidates.edit', $candidate->id) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 active:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                {{ __('Modifier') }}
                            </a>
                            <form method="POST" action="{{ route('candidates.destroy', $candidate->id) }}" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce candidat ?');" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                    {{ __('Supprimer') }}
                                </button>
                            </form>
                        </div>
                    </div>

                </div> {{-- Fin p-6 --}}
            </div> {{-- Fin bg-white --}}


            {{-- Bloc Documents --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 space-y-6">
                     <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                        {{ __('Documents Associés') }}
                    </h3>

                    {{-- Afficher les messages flash spécifiques à l'upload/suppression --}}
                    @if (session('document_success'))
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('document_success') }}</span>
                        </div>
                    @endif
                     @if (session('document_error'))
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('document_error') }}</span>
                        </div>
                    @endif
                    {{-- Afficher les erreurs de validation du formulaire d'upload --}}
                     @if ($errors->has('document') || $errors->has('document_type'))
                         <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                             <strong class="font-bold">Erreur lors de l'ajout du document !</strong>
                             <ul class="mt-3 list-disc list-inside text-sm">
                                 @foreach ($errors->get('document') as $error)<li>{{ $error }}</li>@endforeach
                                 @foreach ($errors->get('document_type') as $error)<li>{{ $error }}</li>@endforeach
                             </ul>
                         </div>
                     @endif

                    {{-- Formulaire pour ajouter un nouveau document --}}
                    <form action="{{ route('candidates.documents.store', $candidate->id) }}" method="POST" enctype="multipart/form-data" class="mb-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                         @csrf
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                            <div>
                                <label for="document" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Fichier') }}</label>
                                <input type="file" name="document" id="document" class="block w-full text-sm ... mt-1" required>
                            </div>
                            <div>
                                <label for="document_type" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Type de Document') }}</label>
                                <select name="document_type" id="document_type" class="block mt-1 w-full rounded-md ...">
                                    <option value="">-- Sélectionner --</option>
                                    <option value="cv">CV</option>
                                    <option value="permit">Permis de conduire</option>
                                    <option value="letter">Lettre de motivation</option>
                                    <option value="certification">Certification</option>
                                    <option value="other">Autre</option>
                                </select>
                            </div>
                            <div class="md:col-start-3">
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 ... w-full justify-center">
                                    {{ __('Ajouter Document') }}
                                </button>
                            </div>
                        </div>
                    </form>

                    {{-- Liste des documents existants --}}
                    @if($candidate->documents->count() > 0)
                        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                            <table class="w-full text-sm ...">
                                <thead class="text-xs ...">
                                    <tr>
                                        <th scope="col" class="px-6 py-3">Nom Fichier</th>
                                        <th scope="col" class="px-6 py-3">Type</th>
                                        <th scope="col" class="px-6 py-3">Taille</th>
                                        <th scope="col" class="px-6 py-3">Ajouté le</th>
                                        <th scope="col" class="px-6 py-3"><span class="sr-only">Actions</span></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($candidate->documents as $document)
                                        <tr class="bg-white border-b dark:bg-gray-800 ...">
                                            <th scope="row" class="px-6 py-4 ...">
                                                <a href="{{ Storage::url($document->file_path) }}" target="_blank" class="hover:underline">
                                                    {{ $document->original_name }}
                                                </a>
                                            </th>
                                            <td class="px-6 py-4">{{ ucfirst($document->type ?? 'N/A') }}</td>
                                            <td class="px-6 py-4">{{ $document->size ? number_format($document->size / 1024, 1) . ' KB' : 'N/A' }}</td>
                                            <td class="px-6 py-4">{{ $document->created_at->format('d/m/Y') }}</td>
                                            <td class="px-6 py-4 text-right">
                                                <form action="{{ route('documents.destroy', $document->id) }}" method="POST" onsubmit="return confirm('Supprimer ce document ?');" class="inline">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="font-medium text-red-600 ... hover:underline">Supprimer</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-center text-gray-500 dark:text-gray-400">Aucun document associé.</p>
                    @endif

                </div> {{-- Fin p-6 Documents --}}
            </div> {{-- Fin bg-white Documents --}}


            {{-- Ajouter ici les blocs pour Entretiens, Tests de conduite, Évaluations liées au candidat --}}

        </div> {{-- Fin max-w-7xl --}}
    </div> {{-- Fin py-12 --}}
</x-app-layout>