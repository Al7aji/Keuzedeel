<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Keuzedeel Bewerken: {{ $keuzedeel->name }}
            </h2>
            <a href="{{ route('admin.keuzedelen.index') }}" class="text-indigo-600 hover:text-indigo-800">
                &larr; Terug
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('admin.keuzedelen.update', $keuzedeel) }}">
                        @csrf
                        @method('PUT')

                        <div class="space-y-6">
                            <!-- Name -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">Naam *</label>
                                <input type="text" name="name" id="name" value="{{ old('name', $keuzedeel->name) }}" required
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Code -->
                            <div>
                                <label for="code" class="block text-sm font-medium text-gray-700">Code *</label>
                                <input type="text" name="code" id="code" value="{{ old('code', $keuzedeel->code) }}" required
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('code')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Short Description -->
                            <div>
                                <label for="short_description" class="block text-sm font-medium text-gray-700">Korte Omschrijving</label>
                                <textarea name="short_description" id="short_description" rows="2"
                                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('short_description', $keuzedeel->short_description) }}</textarea>
                                @error('short_description')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Content -->
                            <div>
                                <label for="content" class="block text-sm font-medium text-gray-700">Inhoud (HTML toegestaan)</label>
                                <textarea name="content" id="content" rows="10"
                                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('content', $keuzedeel->content) }}</textarea>
                                @error('content')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Capacity -->
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="min_students" class="block text-sm font-medium text-gray-700">Min. Studenten *</label>
                                    <input type="number" name="min_students" id="min_students" value="{{ old('min_students', $keuzedeel->min_students) }}" required min="1"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @error('min_students')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="max_students" class="block text-sm font-medium text-gray-700">Max. Studenten *</label>
                                    <input type="number" name="max_students" id="max_students" value="{{ old('max_students', $keuzedeel->max_students) }}" required min="1"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @error('max_students')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Credits -->
                            <div>
                                <label for="credits" class="block text-sm font-medium text-gray-700">Studiepunten (EC)</label>
                                <input type="number" name="credits" id="credits" value="{{ old('credits', $keuzedeel->credits) }}" min="0"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('credits')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Programs -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Opleidingen *</label>
                                <div class="grid grid-cols-2 gap-2 max-h-48 overflow-y-auto border rounded-md p-3">
                                    @foreach($programs as $program)
                                        <label class="flex items-center">
                                            <input type="checkbox" name="programs[]" value="{{ $program->id }}"
                                                   {{ in_array($program->id, old('programs', $selectedPrograms)) ? 'checked' : '' }}
                                                   class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                            <span class="ml-2 text-sm text-gray-700">{{ $program->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                                @error('programs')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Options -->
                            <div class="space-y-3">
                                <label class="flex items-center">
                                    <input type="checkbox" name="is_repeatable" value="1"
                                           {{ old('is_repeatable', $keuzedeel->is_repeatable) ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                    <span class="ml-2 text-sm text-gray-700">Herhaalbaar (kan meerdere keren worden gevolgd)</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="is_active" value="1"
                                           {{ old('is_active', $keuzedeel->is_active) ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                    <span class="ml-2 text-sm text-gray-700">Actief</span>
                                </label>
                            </div>

                            <!-- Submit -->
                            <div class="flex justify-between">
                                <form method="POST" action="{{ route('admin.keuzedelen.destroy', $keuzedeel) }}" class="inline"
                                      onsubmit="return confirm('Weet je zeker dat je dit keuzedeel wilt verwijderen?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800">
                                        Verwijderen
                                    </button>
                                </form>
                                <button type="submit"
                                        class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                                    Opslaan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
