{{-- resources/views/leave_types/show.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Détails Type de Congé :') }} {{ $leaveType->name }}
            </h2>
             <a href="{{ route('admin.leave-types.index') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150">{{ __('Retour à la liste') }}</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 space-y-4">

                     <div class="grid grid-cols-1 md:grid-cols-3 gap-x-4 gap-y-2">
                         <div class="md:col-span-1 font-semibold text-gray-600 dark:text-gray-400">{{ __('ID') }}</div>
                         <div class="md:col-span-2">{{ $leaveType->id }}</div>

                         <div class="md:col-span-1 font-semibold text-gray-600 dark:text-gray-400">{{ __('Nom') }}</div>
                         <div class="md:col-span-2">{{ $leaveType->name }}</div>

                         <div class="md:col-span-1 font-semibold text-gray-600 dark:text-gray-400 pt-1">{{ __('Description') }}</div>
                         <div class="md:col-span-2 whitespace-pre-wrap">{{ $leaveType->description ?? '-' }}</div>

                         <div class="md:col-span-1 font-semibold text-gray-600 dark:text-gray-400">{{ __('Approbation Requise') }}</div>
                         <div class="md:col-span-2">{{ $leaveType->requires_approval ? 'Oui' : 'Non' }}</div>

                         <div class="md:col-span-1 font-semibold text-gray-600 dark:text-gray-400">{{ __('Affecte le Solde') }}</div>
                         <div class="md:col-span-2">{{ $leaveType->affects_balance ? 'Oui' : 'Non' }}</div>

                         <div class="md:col-span-1 font-semibold text-gray-600 dark:text-gray-400">{{ __('Actif') }}</div>
                         <div class="md:col-span-2">
                             <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $leaveType->is_active ? 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100' : 'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100' }}">
                                {{ $leaveType->is_active ? 'Oui' : 'Non' }}
                            </span>
                         </div>

                         <div class="md:col-span-1 font-semibold text-gray-600 dark:text-gray-400">{{ __('Code Couleur') }}</div>
                         <div class="md:col-span-2">
                             @if($leaveType->color_code)
                                <span class="inline-block w-4 h-4 rounded-full border border-gray-300 dark:border-gray-600 mr-1" style="background-color: {{ $leaveType->color_code }}"></span>
                                <span class="font-mono text-sm">{{ $leaveType->color_code }}</span>
                             @else - @endif
                         </div>

                         <div class="md:col-span-1 font-semibold text-gray-600 dark:text-gray-400">{{ __('Créé le') }}</div>
                         <div class="md:col-span-2">{{ $leaveType->created_at->format('d/m/Y H:i') }}</div>
                         <div class="md:col-span-1 font-semibold text-gray-600 dark:text-gray-400">{{ __('Modifié le') }}</div>
                         <div class="md:col-span-2">{{ $leaveType->updated_at->format('d/m/Y H:i') }}</div>
                     </div>

                     <div class="flex items-center justify-end mt-8 border-t border-gray-200 dark:border-gray-700 pt-6">
                        <a href="{{ route('admin.leave-types.edit', $leaveType->id) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 active:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">{{ __('Modifier') }}</a>
                         {{-- Le bouton Supprimer est sur la liste index --}}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>