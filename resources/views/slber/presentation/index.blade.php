<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Presentatiemodus
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Period Selector -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <form method="GET" action="{{ route('slber.presentation.index') }}" class="flex items-center gap-4">
                        <label for="period_id" class="font-medium text-gray-700">Periode:</label>
                        <select name="period_id" id="period_id" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" onchange="this.form.submit()">
                            @foreach($periods as $period)
                                <option value="{{ $period->id }}" {{ $currentPeriod && $currentPeriod->id == $period->id ? 'selected' : '' }}>
                                    {{ $period->name }} ({{ $period->academic_year }})
                                </option>
                            @endforeach
                        </select>

                        @if($currentPeriod)
                            <a href="{{ route('slber.presentation.present', ['period_id' => $currentPeriod->id]) }}"
                               class="ml-auto inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                                Start Presentatie
                            </a>
                        @endif
                    </form>
                </div>
            </div>

            @if($keuzedelen->isEmpty())
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-6 text-center">
                    <p class="text-gray-600">Geen keuzedelen beschikbaar in deze periode.</p>
                </div>
            @else
                <!-- Keuzedelen Preview -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($keuzedelen as $keuzedeel)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow">
                            <a href="{{ route('slber.presentation.slide', $keuzedeel) }}" class="block p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $keuzedeel->name }}</h3>
                                <p class="text-gray-600 text-sm mb-4">{{ Str::limit($keuzedeel->short_description, 100) }}</p>

                                @foreach($keuzedeel->instances as $instance)
                                    <div class="flex justify-between items-center text-sm">
                                        <span class="text-gray-500">{{ $instance->enrollment_count }}/{{ $keuzedeel->max_students }}</span>
                                        <div class="w-24 bg-gray-200 rounded-full h-2">
                                            <div class="h-2 rounded-full {{ $instance->fill_percentage >= 100 ? 'bg-red-500' : ($instance->fill_percentage >= 80 ? 'bg-orange-500' : 'bg-green-500') }}"
                                                 style="width: {{ min($instance->fill_percentage, 100) }}%"></div>
                                        </div>
                                    </div>
                                @endforeach
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
