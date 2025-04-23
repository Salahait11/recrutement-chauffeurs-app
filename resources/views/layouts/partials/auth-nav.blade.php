{{-- resources/views/layouts/partials/auth-nav.blade.php --}}
<nav class="flex items-center gap-2 sm:gap-4 text-sm">
    @auth
        <x-button-link href="{{ url('/dashboard') }}" variant="outline">
            Tableau de bord
        </x-button-link>
        <form method="POST" action="{{ route('logout') }}" class="inline">
            @csrf
            <x-button-link href="{{ route('logout') }}"
                    onclick="event.preventDefault(); this.closest('form').submit();"
                    variant="ghost">
                Déconnexion
            </x-button-link>
        </form>
    @else
        <x-button-link href="{{ route('login') }}" variant="ghost">
            Connexion
        </x-button-link>
        @if (Route::has('register'))
            <x-button-link href="{{ route('register') }}" variant="outline">
                Inscription
            </x-button-link>
        @endif
    @endauth
</nav> 