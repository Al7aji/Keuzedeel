<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Periode Bewerken: {{ $period->name }}
            </h2>
            <a href="{{ route('admin.periods.index') }}" class="text-indigo-600 hover:text-indigo-800">
                &larr; Terug
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('admin.periods.update', $period) }}">
                        @csrf
                        @method('PUT')

                        <div class="space-y-6">
                            <!-- Name -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">Naam *</label>
                                <input type="text" name="name" id="name" value="{{ old('name', $period->name) }}" required
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Academic Year & Period Number -->
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="academic_year" class="block text-sm font-medium text-gray-700">Schooljaar *</label>
                                    <input type="text" name="academic_year" id="academic_year" value="{{ old('academic_year', $period->academic_year) }}" required
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @error('academic_year')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="period_number" class="block text-sm font-medium text-gray-700">Periodenummer *</label>
                                    <input type="number" name="period_number" id="period_number" value="{{ old('period_number', $period->period_number) }}" required min="1" max="10"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @error('period_number')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Period Dates -->
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="start_date" class="block text-sm font-medium text-gray-700">Startdatum *</label>
                                    <input type="date" name="start_date" id="start_date" value="{{ old('start_date', $period->start_date->format('Y-m-d')) }}" required
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @error('start_date')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="end_date" class="block text-sm font-medium text-gray-700">Einddatum *</label>
                                    <input type="date" name="end_date" id="end_date" value="{{ old('end_date', $period->end_date->format('Y-m-d')) }}" required
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @error('end_date')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Enrollment Dates -->
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="enrollment_start" class="block text-sm font-medium text-gray-700">Inschrijving start</label>
                                    <input type="datetime-local" name="enrollment_start" id="enrollment_start"
                                           value="{{ old('enrollment_start', $period->enrollment_start ? $period->enrollment_start->format('Y-m-d\TH:i') : '') }}"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @error('enrollment_start')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="enrollment_end" class="block text-sm font-medium text-gray-700">Inschrijving einde</label>
                                    <input type="datetime-local" name="enrollment_end" id="enrollment_end"
                                           value="{{ old('enrollment_end', $period->enrollment_end ? $period->enrollment_end->format('Y-m-d\TH:i') : '') }}"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @error('enrollment_end')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Submit -->
                            <div class="flex justify-between">
                                <form method="POST" action="{{ route('admin.periods.destroy', $period) }}" class="inline"
                                      onsubmit="return confirm('Weet je zeker dat je deze periode wilt verwijderen?')">
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
