{{-- resources/views/admin/users/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- En-tête avec titre et bouton d'ajout -->
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl rounded-lg border border-blue-100 dark:border-blue-800">
        <div class="p-6">
            <div class="md:flex md:items-center md:justify-between">
                <div class="min-w-0 flex-1">
                    <h2 class="text-2xl font-bold leading-7 text-blue-800 dark:text-blue-300 sm:truncate sm:text-3xl sm:tracking-tight">
                        <div class="flex items-center">
                            {{-- Icône Users (Exemple) --}}
                             <svg class="h-8 w-8 mr-3 text-blue-600 dark:text-blue-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                               <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372M17.375 16.128a9.38 9.38 0 0 0 2.625-.372M17.375 16.128L18.375 15.13a2.25 2.25 0 0 0-3.182-3.182l-1.002 1.002a2.25 2.25 0 0 1-3.182 0l-1.002-1.002a2.25 2.25 0 0 0-3.182 3.182L6.625 16.128a9.38 9.38 0 0 0 2.625.372M17.375 16.128a9.38 9.38 0 0 1 2.625-.372M6.625 16.128a9.38 9.38 0 0 1-2.625-.372M10.5 17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125h-3c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125h3Z" /> <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 7.5h-9a.75.75 0 0 0 0 1.5h9a.75.75 0 0 0 0-1.5Z" />
                             </svg>
                            Gestion des Utilisateurs
                        </div>
                    </h2>
                </div>
                <div class="mt-4 flex md:ml-4 md:mt-0">
                     <a href="{{ route('admin.users.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                         <svg class="-ml-0.5 mr-1.5 h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                        </svg>
                         Nouvel Utilisateur
                     </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Messages Flash -->
    @if (session('success'))
    <div class="rounded-md bg-green-50 p-4 shadow-sm border border-green-200 dark:bg-green-900/20 dark:border-green-800"> <div class="flex"> <div class="flex-shrink-0"> <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor"> <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" /> </svg> </div> <div class="ml-3"> <p class="text-sm font-medium text-green-800 dark:text-green-300">{{ session('success') }}</p> </div> </div> </div>
    @endif
    @if (session('error'))
    <div class="rounded-md bg-red-50 p-4 shadow-sm border border-red-200 dark:bg-red-900/20 dark:border-red-800"> <div class="flex"> <div class="flex-shrink-0"> <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor"> <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" /> </svg> </div> <div class="ml-3"> <p class="text-sm font-medium text-red-800 dark:text-red-300">{{ session('error') }}</p> </div> </div> </div>
    @endif

    <!-- Filtres -->
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl rounded-lg border border-blue-100 dark:border-blue-800">
        <div class="p-6">
            <form method="GET" action="{{ route('admin.users.index') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label for="search" class="block text-sm font-medium text-blue-700 dark:text-blue-400">Recherche</label>
                        <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Nom, email..."
                               class="mt-1 block w-full rounded-md border-blue-300 dark:border-blue-700 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-300 sm:text-sm">
                    </div>
                    <div>
                        <label for="role" class="block text-sm font-medium text-blue-700 dark:text-blue-400">Rôle</label>
                        <select name="role" id="role" class="mt-1 block w-full rounded-md border-blue-300 dark:border-blue-700 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-300 sm:text-sm">
                            <option value="">Tous</option>
                            @foreach($roles as $role)
                                <option value="{{ $role }}" {{ request('role') == $role ? 'selected' : '' }}>
                                    {{ ucfirst($role) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="is_active" class="block text-sm font-medium text-blue-700 dark:text-blue-400">Statut</label>
                        <select name="is_active" id="is_active" class="mt-1 block w-full rounded-md border-blue-300 dark:border-blue-700 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-300 sm:text-sm">
                            <option value="">Tous</option>
                            <option value="1" {{ request('is_active') == '1' ? 'selected' : '' }}>Actif</option>
                            <option value="0" {{ request('is_active') == '0' ? 'selected' : '' }}>Inactif</option>
                        </select>
                    </div>
                    <div class="flex items-end space-x-2">
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                            <svg class="-ml-0.5 mr-1.5 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd" />
                            </svg>
                            Filtrer
                        </button>
                        <a href="{{ route('admin.users.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                            <svg class="-ml-0.5 mr-1.5 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" />
                            </svg>
                            Réinitialiser
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Liste des Utilisateurs -->
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl rounded-lg border border-blue-100 dark:border-blue-800">
        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            {{-- Helper function for sort icon --}}
                            @php
                            $sortIcon = function($field) { /* ... Same helper as before ... */
                                if (request('sort') == $field) { return request('direction', 'asc') == 'asc' ? '<svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M14.77 12.79a.75.75 0 01-1.06-.02L10 8.832 6.29 12.77a.75.75 0 11-1.08-1.04l4.25-4.5a.75.75 0 011.08 0l4.25 4.5a.75.75 0 01-.02 1.06z" clip-rule="evenodd" /></svg>' : '<svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" /></svg>'; } return '<svg class="invisible h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" /></svg>';
                            };
                            @endphp
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">ID</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                <a href="{{ route('admin.users.index', array_merge(request()->query(), ['sort' => 'name', 'direction' => (request('sort') == 'name' && request('direction') == 'asc') ? 'desc' : 'asc'])) }}" class="group inline-flex">
                                    Nom
                                    <span class="ml-2 flex-none rounded text-gray-400 {{ request('sort') == 'name' ? '' : 'invisible' }} group-hover:visible group-focus:visible"> {!! $sortIcon('name') !!} </span>
                                </a>
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                <a href="{{ route('admin.users.index', array_merge(request()->query(), ['sort' => 'email', 'direction' => (request('sort') == 'email' && request('direction') == 'asc') ? 'desc' : 'asc'])) }}" class="group inline-flex">
                                    Email
                                     <span class="ml-2 flex-none rounded text-gray-400 {{ request('sort') == 'email' ? '' : 'invisible' }} group-hover:visible group-focus:visible"> {!! $sortIcon('email') !!} </span>
                                </a>
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                <a href="{{ route('admin.users.index', array_merge(request()->query(), ['sort' => 'role', 'direction' => (request('sort') == 'role' && request('direction') == 'asc') ? 'desc' : 'asc'])) }}" class="group inline-flex">
                                    Rôle
                                    <span class="ml-2 flex-none rounded text-gray-400 {{ request('sort') == 'role' ? '' : 'invisible' }} group-hover:visible group-focus:visible"> {!! $sortIcon('role') !!} </span>
                                </a>
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                <a href="{{ route('admin.users.index', array_merge(request()->query(), ['sort' => 'created_at', 'direction' => (request('sort') == 'created_at' && request('direction') == 'asc') ? 'desc' : 'asc'])) }}" class="group inline-flex">
                                    Créé le
                                    <span class="ml-2 flex-none rounded text-gray-400 {{ request('sort') == 'created_at' ? '' : 'invisible' }} group-hover:visible group-focus:visible"> {!! $sortIcon('created_at') !!} </span>
                                </a>
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                <a href="{{ route('admin.users.index', array_merge(request()->query(), ['sort' => 'email_verified_at', 'direction' => (request('sort') == 'email_verified_at' && request('direction') == 'asc') ? 'desc' : 'asc'])) }}" class="group inline-flex">
                                    Vérifié le
                                    <span class="ml-2 flex-none rounded text-gray-400 {{ request('sort') == 'email_verified_at' ? '' : 'invisible' }} group-hover:visible group-focus:visible"> {!! $sortIcon('email_verified_at') !!} </span>
                                </a>
                            </th>
                            <th scope="col" class="relative px-6 py-3">
                                <span class="sr-only">Actions</span>
                            </th>
                        </tr>
                    </thead>
                     <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                        @forelse ($users as $user)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $user->id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                     {{-- Colonne Nom avec Avatar/Initiales --}}
                                     <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            @if($user->profile_photo_path) {{-- Adapt if using a different field --}}
                                                <img class="h-10 w-10 rounded-full" src="{{ asset('storage/' . $user->profile_photo_path) }}" alt="{{ $user->name }}">
                                            @else
                                                @php /* Simple initials logic */ $nameParts = explode(' ', $user->name ?? 'N A'); $initials = count($nameParts) >= 2 ? strtoupper(substr($nameParts[0], 0, 1) . substr($nameParts[count($nameParts)-1], 0, 1)) : strtoupper(substr($user->name ?? 'NA', 0, 1)); @endphp
                                                <div class="h-10 w-10 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center"> <span class="text-blue-600 dark:text-blue-300 font-medium">{{ $initials }}</span> </div>
                                            @endif
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $user->name }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $user->email }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{-- Badge de rôle --}}
                                     @php
                                        $roleLabel = ucfirst($user->role);
                                        $roleClass = 'bg-gray-100 text-gray-800 dark:bg-gray-600 dark:text-gray-200'; // Default
                                        if ($user->role == 'admin') $roleClass = 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300';
                                        elseif ($user->role == 'manager') $roleClass = 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300';
                                        elseif ($user->role == 'recruiter') $roleClass = 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300';
                                        // Add more roles if needed
                                    @endphp
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $roleClass }}">
                                        {{ $roleLabel }}
                                     </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $user->created_at->format('d/m/Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    @if($user->email_verified_at)
                                         <span class="text-green-600 dark:text-green-400" title="{{ $user->email_verified_at->format('d/m/Y H:i') }}">✓ Vérifié</span>
                                    @else
                                         <span class="text-yellow-600 dark:text-yellow-400">Non vérifié</span>
                                         {{-- Option: Add resend verification link --}}
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                     <div class="flex justify-end space-x-2">
                                         <a href="{{ route('admin.users.edit', $user->id) }}" class="text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300" title="Modifier">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </a>
                                         @if(Auth::id() !== $user->id)
                                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline" onsubmit="return confirm('Supprimer cet utilisateur ? ATTENTION : peut causer des problèmes si lié à des données.');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300" title="Supprimer">
                                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
                                         @else
                                            <span class="text-gray-400 dark:text-gray-500 text-xs italic px-2 py-1">(Vous)</span>
                                         @endif
                                     </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500 dark:text-gray-400">
                                Aucun utilisateur trouvé.
                            </td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{-- Pagination --}}
            <div class="mt-4">
                 {{ $users->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>
@endsection