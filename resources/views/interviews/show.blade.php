<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Show Interview') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <p><strong>Candidate:</strong> {{ $interview->candidate ? $interview->candidate->getFullName() : '-' }}</p>
                    <p><strong>Interviewer:</strong> {{ $interview->interviewer ? $interview->interviewer->name : '-' }}</p>
                    <p><strong>Scheduler:</strong> {{ $interview->scheduler ? $interview->scheduler->name : '-' }}</p>
                    <p><strong>Date:</strong> {{ $interview->interview_date ? $interview->interview_date : '-' }}</p>
                    <p><strong>Type:</strong> {{ $interview->type ? $interview->type : '-' }}</p>
                    <p><strong>Notes:</strong> {{ $interview->notes ? $interview->notes : '-' }}</p>
                    <p><strong>Status:</strong> {{ $interview->status ? $interview->status : '-' }}</p>
                    @if($interview->result !=null)
                        <p><strong>Result:</strong> {{ $interview->result ? $interview->result : '-' }}</p>
                    @endif
                     @if($interview->feedback !=null)
                        <p><strong>Feedback:</strong> {{ $interview->feedback }}</p>
                    @endif
                    @if($interview->feedback)
                        <p><strong>Feedback:</strong> {{ $interview->feedback }}</p>
                    @endif

                    <a href="{{ route('interviews.index') }}" class="mt-4 inline-block px-4 py-2 bg-blue-600 text-white text-xs font-semibold rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        {{ __('Back to list') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>