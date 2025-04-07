{{-- resources/views/admin/users/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Gestion des Utilisateurs') }}
            </h2>
             {{-- Bouton pour créer un nouvel utilisateur --}}
             <a href="{{ route('admin.users.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 active:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                 {{ __('+ Nouvel Utilisateur') }}
             </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    {{-- Messages Flash --}}
                    @if (session('success'))
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 dark:bg-green-900 dark:text-green-200 dark:border-green-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 dark:bg-red-900 dark:text-red-200 dark:border-red-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif

                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">ID</th>
                                    <th scope="col" class="px-6 py-3">Nom</th>
                                    <th scope="col" class="px-6 py-3">Email</th>
                                    <th scope="col" class="px-6 py-3">Rôle</th>
                                    <th scope="col" class="px-6 py-3">Créé le</th>
                                    <th scope="col" class="px-6 py-3">Vérifié le</th>
                                    <th scope="col" class="px-6 py-3"><span class="sr-only">Actions</span></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($users as $user)
                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                        <td class="px-6 py-4">{{ $user->id }}</td>
                                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap">{{ $user->name }}</td>
                                        <td class="px-6 py-4">{{ $user->email }}</td>
                                        <td class="px-6 py-4">
                                            {{-- Badge de rôle --}}
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                @if($user->role == 'admin') bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100 @elseif($user->role == 'manager') bg-purple-100 text-purple-800 dark:bg-purple-800 dark:text-purple-100 @elseif($user->role == 'recruiter') bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100 @else bg-gray-100 text-gray-800 dark:bg-gray-600 dark:text-gray-200 @endif">
                                                {{ ucfirst($user->role) }}
                                             </span>
                                        </td>
                                        <td class="px-6 py-4">{{ $user->created_at->format('d/m/Y') }}</td>
                                        <td class="px-6 py-4">{{ $user->email_verified_at ? $user->email_verified_at->format('d/m/Y H:i') : 'Non' }}</td>
                                        <td class="px-6 py-4 text-right space-x-2 whitespace-nowrap">
                                             {{-- Lien vers la page d'édition standard --}}
                                             <a href="{{ route('admin.users.edit', $user->id) }}" class="font-medium text-indigo-600 dark:text-indigo-500 hover:underline">Modifier</a>

                                             {{-- Bouton Supprimer standard (avec condition) --}}
                                             @if(Auth::id() !== $user->id)
                                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Supprimer cet utilisateur ? ATTENTION : peut causer des problèmes si lié à des données.');" class="inline">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="font-medium text-red-600 dark:text-red-500 hover:underline">Supprimer</button>
                                                </form>
                                             @else
                                                <span class="text-gray-400 dark:text-gray-500 text-xs italic">(Vous)</span>
                                             @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="7" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">Aucun utilisateur trouvé.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{-- Pagination --}}
                    <div class="mt-4">
                        {{ $users->links() }}
                    </div>

                </div> {{-- Fin p-6 --}}
            </div> {{-- Fin bg-white --}}
        </div> {{-- Fin max-w --}}
    </div> {{-- Fin py-12 --}}
</x-app-layout>