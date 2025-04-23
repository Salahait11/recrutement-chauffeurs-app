{{-- resources/views/profile/edit.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="space-y-6">

    <!-- Messages Flash (session status) -->
    @if (session('status') === 'profile-updated')
        <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
             class="rounded-md bg-green-50 p-4 shadow-sm border border-green-200 dark:bg-green-900/20 dark:border-green-800">
             <div class="flex"> <div class="flex-shrink-0"> <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor"> <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" /> </svg> </div> <div class="ml-3"> <p class="text-sm font-medium text-green-800 dark:text-green-300">{{ __('Saved.') }}</p> </div> </div>
        </div>
    @endif
     @if (session('status') === 'password-updated')
        <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
             class="rounded-md bg-green-50 p-4 shadow-sm border border-green-200 dark:bg-green-900/20 dark:border-green-800">
             <div class="flex"> <div class="flex-shrink-0"> <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor"> <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" /> </svg> </div> <div class="ml-3"> <p class="text-sm font-medium text-green-800 dark:text-green-300">{{ __('Saved.') }}</p> </div> </div>
        </div>
    @endif
    {{-- Add general error flash if needed --}}
    @if (session('error'))
        <div class="rounded-md bg-red-50 p-4 shadow-sm border border-red-200 dark:bg-red-900/20 dark:border-red-800"> <div class="flex"> <div class="flex-shrink-0"> <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor"> <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" /> </svg> </div> <div class="ml-3"> <p class="text-sm font-medium text-red-800 dark:text-red-300">{{ session('error') }}</p> </div> </div> </div>
    @endif


    <!-- En-tête avec titre -->
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl rounded-lg border border-blue-100 dark:border-blue-800">
        <div class="p-6">
            <div class="md:flex md:items-center md:justify-between">
                <div class="min-w-0 flex-1">
                    <h2 class="text-2xl font-bold leading-7 text-blue-800 dark:text-blue-300 sm:truncate sm:text-3xl sm:tracking-tight">
                        <div class="flex items-center">
                            {{-- Icône User Circle (Exemple) --}}
                            <svg class="h-8 w-8 mr-3 text-blue-600 dark:text-blue-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            </svg>
                            Profil
                        </div>
                    </h2>
                </div>
                 {{-- Pas de boutons d'action dans l'en-tête ici --}}
            </div>
        </div>
    </div>

    <!-- Bloc Informations du Profil -->
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl rounded-lg border border-blue-100 dark:border-blue-800">
        <form method="post" action="{{ route('profile.update') }}" class="p-6 space-y-6">
            @csrf
            @method('patch')

            <h3 class="text-lg font-medium text-blue-800 dark:text-blue-300 border-b border-gray-200 dark:border-gray-700 pb-3">
                Informations du Profil
            </h3>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Mettez à jour les informations de profil et l'adresse e-mail de votre compte.
            </p>

            <div class="space-y-4 max-w-xl"> {{-- max-w-xl pour limiter la largeur comme dans Breeze --}}
                {{-- Nom --}}
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nom</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name"
                           class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-300 sm:text-sm">
                    @error('name') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required autocomplete="username"
                           class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-300 sm:text-sm">
                    @error('email') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror

                    {{-- Logique de vérification email (si activée) --}}
                    @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                        <div class="mt-2">
                            <p class="text-sm text-gray-800 dark:text-gray-200">
                                Votre adresse e-mail n'est pas vérifiée.
                                <button form="send-verification" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                                    Cliquez ici pour renvoyer l'e-mail de vérification.
                                </button>
                            </p>
                            @if (session('status') === 'verification-link-sent')
                                <p class="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
                                    Un nouveau lien de vérification a été envoyé à votre adresse e-mail.
                                </p>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

             <div class="flex items-center gap-4 pt-4 border-t border-gray-200 dark:border-gray-700 mt-6">
                 <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                    <svg class="-ml-0.5 mr-1.5 h-5 w-5" viewBox="0 0 20 20" fill="currentColor"> <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" /> </svg>
                    Enregistrer
                </button>
                 {{-- Message 'Saved.' retiré car géré par le flash message en haut --}}
            </div>
        </form>
        {{-- Formulaire séparé pour renvoyer la vérification --}}
        <form id="send-verification" method="post" action="{{ route('verification.send') }}"> @csrf </form>
    </div>

    <!-- Bloc Mise à jour Mot de Passe -->
     <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl rounded-lg border border-blue-100 dark:border-blue-800">
        <form method="post" action="{{ route('password.update') }}" class="p-6 space-y-6">
            @csrf
            @method('put')

            <h3 class="text-lg font-medium text-blue-800 dark:text-blue-300 border-b border-gray-200 dark:border-gray-700 pb-3">
                Mettre à jour le mot de passe
            </h3>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Assurez-vous que votre compte utilise un mot de passe long et aléatoire pour rester sécurisé.
            </p>

            <div class="space-y-4 max-w-xl">
                {{-- Mot de passe actuel --}}
                <div>
                     <label for="current_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Mot de passe actuel</label>
                    <input type="password" name="current_password" id="current_password" autocomplete="current-password" required
                           class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-300 sm:text-sm">
                    @error('current_password', 'updatePassword') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>

                {{-- Nouveau mot de passe --}}
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nouveau mot de passe</label>
                    <input type="password" name="password" id="password" autocomplete="new-password" required
                           class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-300 sm:text-sm">
                    @error('password', 'updatePassword') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>

                 {{-- Confirmation --}}
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Confirmer le mot de passe</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" autocomplete="new-password" required
                           class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-300 sm:text-sm">
                     @error('password_confirmation', 'updatePassword') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>
            </div>

             <div class="flex items-center gap-4 pt-4 border-t border-gray-200 dark:border-gray-700 mt-6">
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                    <svg class="-ml-0.5 mr-1.5 h-5 w-5" viewBox="0 0 20 20" fill="currentColor"> <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" /> </svg>
                    Enregistrer
                </button>
                 {{-- Message 'Saved.' retiré --}}
            </div>
        </form>
    </div>

    <!-- Bloc Supprimer Compte -->
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl rounded-lg border border-blue-100 dark:border-blue-800">
        <div class="p-6 space-y-6">
             <h3 class="text-lg font-medium text-red-700 dark:text-red-400 border-b border-gray-200 dark:border-gray-700 pb-3">
                Supprimer le compte
            </h3>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400 max-w-xl">
               Une fois votre compte supprimé, toutes ses ressources et données seront définitivement effacées. Avant de supprimer votre compte, veuillez télécharger les données ou informations que vous souhaitez conserver.
            </p>

            {{-- Formulaire de suppression simplifié avec confirmation JS --}}
            <form method="post" action="{{ route('profile.destroy') }}" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer votre compte ? Cette action est irréversible et toutes vos données seront perdues.');">
                @csrf
                @method('delete')

                {{-- Champ mot de passe pour confirmation (si nécessaire par la logique backend) --}}
                <div class="mt-6 max-w-xl">
                    <label for="password_delete" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Mot de passe <span class="text-red-500">*</span>
                    </label>
                    <input type="password" name="password" id="password_delete" required autocomplete="current-password"
                           placeholder="Confirmer avec votre mot de passe"
                           class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-red-500 focus:ring-red-500 dark:bg-gray-700 dark:text-gray-300 sm:text-sm">
                     @error('password', 'userDeletion') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>

                <div class="mt-6 flex justify-end">
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition duration-150 ease-in-out">
                        <svg class="-ml-0.5 mr-1.5 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        Supprimer le Compte
                    </button>
                </div>
            </form>

        </div>
    </div>

</div>
@endsection