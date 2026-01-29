<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Keuzedelen voor {{ $period->name }}
            </h2>
            <a href="{{ route('admin.periods.index') }}" class="text-indigo-600 hover:text-indigo-800">
                &larr; Terug
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Current Instances -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Huidige Keuzedelen</h3>

                        @if($instances->isEmpty())
                            <p class="text-gray-500">Geen keuzedelen toegevoegd aan deze periode.</p>
                        @else
                            <div class="space-y-3">
                                @foreach($instances as $instance)
                                    <div class="flex justify-between items-center border rounded-lg p-3">
                                        <div>
                                            <div class="font-medium">{{ $instance->display_name }}</div>
                                            <div class="text-sm text-gray-500">
                                                {{ $instance->activeEnrollments->count() }}/{{ $instance->keuzedeel->max_students }} studenten
                                            </div>
                                        </div>
                                        <form method="POST" action="{{ route('admin.periods.remove-instance', [$period, $instance]) }}"
                                              onsubmit="return confirm('Weet je zeker dat je dit keuzedeel wilt verwijderen uit deze periode?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 text-sm">
                                                Verwijderen
                                            </button>
                                        </form>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Add Keuzedeel -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Keuzedeel Toevoegen</h3>

                        @if($availableKeuzedelen->isEmpty())
                            <p class="text-gray-500">Alle beschikbare keuzedelen zijn al toegevoegd aan deze periode.</p>
                        @else
                            <form method="POST" action="{{ route('admin.periods.add-instance', $period) }}">
                                @csrf

                                <div class="space-y-4">
                                    <div>
                                        <label for="keuzedeel_id" class="block text-sm font-medium text-gray-700">Keuzedeel *</label>
                                        <select name="keuzedeel_id" id="keuzedeel_id" required
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            <option value="">Selecteer een keuzedeel...</option>
                                            @foreach($availableKeuzedelen as $keuzedeel)
                                                <option value="{{ $keuzedeel->id }}">{{ $keuzedeel->name }} ({{ $keuzedeel->code }})</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div>
                                        <label for="instance_number" class="block text-sm font-medium text-gray-700">Instance nummer (voor herhaalbare keuzedelen)</label>
                                        <input type="number" name="instance_number" id="instance_number" value="1" min="1"
                                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <p class="text-xs text-gray-500 mt-1">Alleen relevant voor keuzedelen die meerdere keren gevolgd kunnen worden.</p>
                                    </div>

                                    <button type="submit"
                                            class="w-full inline-flex justify-center items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                                        Toevoegen
                                    </button>
                                </div>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
