@php
// Helper function placeholder for initials (you might put this in a service provider or helper file)
if (!function_exists('getUserInitials')) {
    function getUserInitials($name) {
        $words = explode(' ', $name);
        $initials = '';
        foreach ($words as $word) {
            $initials .= strtoupper(substr($word, 0, 1));
        }
        return $initials;
    }
}

// Fonction helper (à mettre dans un fichier helper idéalement)
if (!function_exists('isNavGroupActive')) {
    function isNavGroupActive($group) {
        $currentPath = request()->path();
        $groupPaths = [
            'rh' => ['candidates', 'interviews', 'driving-tests', 'offers', 'employees', 'leave-requests', 'calendar'],
            'admin' => ['reports', 'vehicles', 'evaluation-criteria', 'leave-types', 'users']
        ];
        
        return in_array($currentPath, $groupPaths[$group] ?? []) || 
               str_starts_with($currentPath, $groupPaths[$group][0] ?? '');
    }
}

$userInitials = Auth::check() ? getUserInitials(Auth::user()->name) : '';
$rhActive = isNavGroupActive('rh');
$adminActive = isNavGroupActive('admin');
@endphp

<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-blue-100 dark:border-blue-800 shadow-lg">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center">
                        <x-application-logo class="block h-9 w-auto fill-current text-blue-600 dark:text-blue-400" />
                        <span class="ml-2 text-xl font-bold text-blue-800 dark:text-blue-300">Recrutement Chauffeurs</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
                        <i class="fas fa-tachometer-alt mr-2"></i> Tableau de bord
                    </x-nav-link>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ml-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-blue-600 dark:text-blue-400 bg-white dark:bg-gray-800 hover:text-blue-800 dark:hover:text-blue-300 focus:outline-none transition ease-in-out duration-150">
                            <div class="flex items-center">
                                <div class="w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center text-blue-600 dark:text-blue-400 font-bold mr-2">
                                    {{ $userInitials }}
                                </div>
                                <div>
                                    <div class="font-medium text-blue-800 dark:text-blue-300">{{ Auth::check() ? Auth::user()->name : '' }}</div>
                                    <div class="text-xs text-blue-600 dark:text-blue-400">{{ Auth::check() ? Auth::user()->email : '' }}</div>
                                </div>
                                <i class="fas fa-chevron-down ml-2 text-xs"></i>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
                            <i class="fas fa-user-circle mr-2"></i> Profil
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();"
                                    class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
                                <i class="fas fa-sign-out-alt mr-2"></i> Déconnexion
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 hover:bg-blue-100 dark:hover:bg-blue-900 focus:outline-none focus:bg-blue-100 dark:focus:bg-blue-900 focus:text-blue-800 dark:focus:text-blue-300 transition duration-150 ease-in-out">
                    <i class="fas" :class="{'fa-bars': !open, 'fa-times': open}"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
                <i class="fas fa-tachometer-alt mr-2"></i> Tableau de bord
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-blue-100 dark:border-blue-800">
            <div class="px-4">
                <div class="font-medium text-base text-blue-800 dark:text-blue-300">{{ Auth::check() ? Auth::user()->name : '' }}</div>
                <div class="font-medium text-sm text-blue-600 dark:text-blue-400">{{ Auth::check() ? Auth::user()->email : '' }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
                    <i class="fas fa-user-circle mr-2"></i> Profil
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();"
                            class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
                        <i class="fas fa-sign-out-alt mr-2"></i> Déconnexion
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>

<!-- Secondary Navigation Menu -->
<div class="bg-white dark:bg-gray-800 border-b border-blue-100 dark:border-blue-800 shadow-md">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-12">
            <div class="flex space-x-8">
                @if(Auth::user()->hasRole('admin'))
                    <!-- RH Dropdown -->
                    <x-nav-dropdown :active="isNavGroupActive('rh')">
                        <x-slot name="trigger">
                            <div class="flex items-center text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
                                <i class="fas fa-users mr-2"></i> Gestion RH
                                
                            </div>
                        </x-slot>
                        <x-slot name="content">
                            <x-dropdown-link :href="route('candidates.index')" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
                                <i class="fas fa-user-tie mr-2"></i> Candidats
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('interviews.index')" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
                                <i class="fas fa-comments mr-2"></i> Entretiens
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('driving-tests.index')" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
                                <i class="fas fa-car mr-2"></i> Tests de conduite
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('offers.index')" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
                                <i class="fas fa-file-alt mr-2"></i> Offres
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('employees.index')" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
                                <i class="fas fa-user-friends mr-2"></i> Employés
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('leave-requests.index')" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
                                <i class="fas fa-calendar-alt mr-2"></i> Congés
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('admin.absences.index')" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
                                <i class="fas fa-user-clock mr-2"></i> Absences
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('calendar.index')" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
                                <i class="fas fa-calendar mr-2"></i> Calendrier
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('evaluations.index')" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
                                <i class="fas fa-clipboard-check mr-2"></i> Évaluations
                            </x-dropdown-link>
                        </x-slot>
                    </x-nav-dropdown>

                    <!-- Administration Dropdown -->
                    <x-nav-dropdown :active="isNavGroupActive('admin')">
                        <x-slot name="trigger">
                            <div class="flex items-center text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
                                <i class="fas fa-cog mr-2"></i> Paramètres Système
                            </div>
                        </x-slot>
                        <x-slot name="content">
                            <x-dropdown-link :href="route('admin.reports.index')" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
                                <i class="fas fa-chart-bar mr-2"></i> Rapports
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('admin.vehicles.index')" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
                                <i class="fas fa-truck mr-2"></i> Véhicules
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('admin.evaluation-criteria.index')" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
                                <i class="fas fa-clipboard-check mr-2"></i> Critères d'évaluation
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('admin.leave-types.index')" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
                                <i class="fas fa-calendar-plus mr-2"></i> Types de congés
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('admin.users.index')" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
                                <i class="fas fa-users-cog mr-2"></i> Utilisateurs
                            </x-dropdown-link>
                        </x-slot>
                    </x-nav-dropdown>
                @elseif(Auth::user()->hasRole('employee'))
                    <x-nav-link :href="route('leave-requests.index')" :active="request()->routeIs('leave-requests.*')" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
                        <i class="fas fa-calendar-alt mr-2"></i> Mes congés
                    </x-nav-link>
                    <x-nav-link :href="route('profile.edit')" :active="request()->routeIs('profile.*')" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
                        <i class="fas fa-user-circle mr-2"></i> Mon profil
                    </x-nav-link>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- You might need a new component for dropdown navigation links if you want different styling --}}
{{-- Create resources/views/components/dropdown-nav-link.blade.php --}}
{{-- Example content (adjust styling as needed): --}}
{{--
@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full px-4 py-2 text-start text-sm leading-5 text-gray-900 dark:text-gray-100 bg-gray-100 dark:bg-gray-800 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-800 transition duration-150 ease-in-out'
            : 'block w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-800 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes . ' flex items-center']) }}>
    {{ $slot }}
</a>
--}}