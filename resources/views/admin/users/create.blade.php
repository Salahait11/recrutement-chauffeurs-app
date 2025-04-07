{{-- resources/views/admin/users/create.blade.php --}}
<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">{{ __('Créer Nouvel Utilisateur (Admin)') }}</h2></x-slot>
    <div class="py-12"><div class="max-w-4xl mx-auto sm:px-6 lg:px-8"><div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg"><div class="p-6 text-gray-900 dark:text-gray-100">
        {{-- Affichage erreurs --}}
        @if ($errors->any())<div class="mb-6 bg-red-100 border border-red-400 text-red-700 dark:text-red-200 dark:border-red-700 px-4 py-3 rounded relative" role="alert"><strong class="font-bold">Oups!</strong><ul class="mt-3 list-disc list-inside text-sm">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
        {{-- Formulaire avec action préfixée --}}
        <form method="POST" action="{{ route('admin.users.store') }}">@csrf<div class="space-y-6">
        <div><label for="name" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Nom Complet') }} <span class="text-red-500">*</span></label><input type="text" name="name" id="name" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600" value="{{ old('name') }}" required autofocus>@error('name')<p class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</p>@enderror</div>
        <div><label for="email" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Email (Login)') }} <span class="text-red-500">*</span></label><input type="email" name="email" id="email" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600" value="{{ old('email') }}" required>@error('email')<p class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</p>@enderror</div>
<div>
    <label for="role" class="block font-medium text-sm text-gray-700 dark:text-gray-300">
        {{ __('Rôle') }} <span class="text-red-500">*</span>
    </label>
    
    <select name="role_disabled" id="role" disabled
        class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">
        <option value="admin" selected>Admin</option>
    </select>
    
    <!-- Champ caché pour envoyer la valeur -->
    <input type="hidden" name="role" value="admin">

    @error('role')
        <p class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</p>
    @enderror
</div>
        <hr class="dark:border-gray-700 my-2"><h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Mot de Passe Initial</h3><div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div><label for="password" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Mot de Passe') }} <span class="text-red-500">*</span></label><input type="password" name="password" id="password" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600" required autocomplete="new-password">@error('password')<p class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</p>@enderror</div>
        <div><label for="password_confirmation" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Confirmer Mot de Passe') }} <span class="text-red-500">*</span></label><input type="password" name="password_confirmation" id="password_confirmation" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600" required autocomplete="new-password"></div>
        </div></div> {{-- Fin space-y-6 --}}
        {{-- Boutons avec lien Annuler préfixé --}}
        <div class="flex items-center justify-end mt-8 border-t border-gray-200 dark:border-gray-700 pt-6"><a href="{{ route('admin.users.index') }}" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 mr-4">{{ __('Annuler') }}</a><button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 active:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">{{ __('Créer Utilisateur') }}</button></div>
        </form></div></div></div></div>
</x-app-layout>