<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Créer un entretien') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('interviews.store') }}" method="POST">
                        
                        @csrf

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li class="text-red-500">{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div>
                            <label for="candidate_id">Candidat</label>
                            <select name="candidate_id" id="candidate_id">
                                @foreach(\App\Models\Candidate::all() as $candidate)
                                    <option value="{{ $candidate->id }}">{{ $candidate->first_name }} {{ $candidate->last_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="interviewer_id">Interviewer</label>
                            <select name="interviewer_id" id="interviewer_id">
                                @foreach(\App\Models\User::all() as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="interview_date">Date de l'entretien</label>
                            <input type="datetime-local" name="interview_date" id="interview_date">
                        </div>
                        <div>
                            <label for="type">Type</label>
                            <select name="type" id="type">
                                <option value="initial">Initial</option>
                                <option value="technique">Technique</option>
                                <option value="final">Final</option>
                            </select>
                        </div>
                       <div>
                            <label for="notes">Notes</label>
                            <textarea name="notes" id="notes"></textarea>
                        </div>
                        <button type="submit">Créer</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>