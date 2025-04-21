<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Modifier un entretien') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('interviews.update', $interview) }}">
                        @csrf
                        @method('PUT')

                        <div>
                            <label for="interview_date">Date de l'entretien</label>
                            <input type="datetime-local" name="interview_date" id="interview_date" required>
                        </div>
                        <div>
                            <label for="type">Type</label>
                            <input type="text" name="type" id="type" required>
                        </div>
                        <div>
                            <label for="notes">Notes</label>
                            <textarea name="notes" id="notes"></textarea>
                        </div>

                        <button type="submit">Modifier</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>