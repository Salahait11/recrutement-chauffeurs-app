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

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div>
                            <label for="candidate_id">Candidat</label>
                            <select name="candidate_id" id="candidate_id" required>
                                @foreach(\App\Models\Candidate::all() as $candidate)
                                    <option value="{{ $candidate->id }}" {{ $interview->candidate_id == $candidate->id ? 'selected' : '' }}>
                                        {{ $candidate->getFullName() }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                         <div>
                            <label for="interviewer_id">Interviewer</label>
                            <select name="interviewer_id" id="interviewer_id" required>
                                @foreach(\App\Models\User::all() as $user)
                                    <option value="{{ $user->id }}" {{ $interview->interviewer_id == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="interview_date">Date et heure de l'entretien</label>
                            <input type="datetime-local" name="interview_date" id="interview_date" value="{{ \Carbon\Carbon::parse($interview->interview_date)->format('Y-m-d\TH:i') }}" required>
                        </div>

                        <div>
                            <label for="type">Type</label>
                            <select name="type" id="type" required>
                                <option value="initial" {{ $interview->type == 'initial' ? 'selected' : '' }}>initial</option>
                                <option value="technique" {{ $interview->type == 'technique' ? 'selected' : '' }}>technique</option>
                                <option value="final" {{ $interview->type == 'final' ? 'selected' : '' }}>final</option>
                            </select>
                        </div>

                        <div>
                            <label for="notes">Notes</label>
                            <textarea name="notes" id="notes">{{ $interview->notes }}</textarea>
                        </div>

                        <div>
                            <label for="result">Résultat</label>
                            <input type="text" name="result" id="result" value="{{ $interview->result }}">
                        </div>
                        <div>
                            <label for="feedback">Feedback</label>
                            <textarea name="feedback" id="feedback">{{ $interview->feedback }}</textarea>
                        </div>
                        <button type="submit">Modifier</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>