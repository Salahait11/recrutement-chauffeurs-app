<div class="mb-4">
    <label for="status" class="block text-sm font-medium text-gray-700">Statut</label>
    <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        <option value="en_attente" {{ old('status', $leaveRequest->status) === 'en_attente' ? 'selected' : '' }}>En attente</option>
        <option value="approuve" {{ old('status', $leaveRequest->status) === 'approuve' ? 'selected' : '' }}>Approuvé</option>
        <option value="refuse" {{ old('status', $leaveRequest->status) === 'refuse' ? 'selected' : '' }}>Refusé</option>
        <option value="annule" {{ old('status', $leaveRequest->status) === 'annule' ? 'selected' : '' }}>Annulé</option>
    </select>
    @error('status')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>
