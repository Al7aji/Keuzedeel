<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Admin Dashboard
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-2xl font-bold text-gray-900">{{ $stats['total_students'] }}</div>
                    <div class="text-sm text-gray-500">Studenten</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-2xl font-bold text-gray-900">{{ $stats['total_keuzedelen'] }}</div>
                    <div class="text-sm text-gray-500">Keuzedelen</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-2xl font-bold text-green-600">{{ $stats['active_keuzedelen'] }}</div>
                    <div class="text-sm text-gray-500">Actieve Keuzedelen</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-2xl font-bold text-indigo-600">{{ $stats['total_enrollments'] }}</div>
                    <div class="text-sm text-gray-500">Inschrijvingen</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-2xl font-bold text-orange-600">{{ $stats['periods_with_open_enrollment'] }}</div>
                    <div class="text-sm text-gray-500">Open Periodes</div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Current Period Stats -->
                @if($currentPeriodStats)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                                Huidige Periode: {{ $currentPeriodStats['period']->name }}
                            </h3>

                            <div class="grid grid-cols-3 gap-4 mb-4">
                                <div class="text-center">
                                    <div class="text-xl font-bold text-indigo-600">{{ $currentPeriodStats['total_enrollments'] }}</div>
                                    <div class="text-xs text-gray-500">Inschrijvingen</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-xl font-bold text-orange-600">{{ $currentPeriodStats['instances_below_minimum'] }}</div>
                                    <div class="text-xs text-gray-500">Onder minimum</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-xl font-bold text-red-600">{{ $currentPeriodStats['instances_full'] }}</div>
                                    <div class="text-xs text-gray-500">Vol</div>
                                </div>
                            </div>

                            <div class="mt-4">
                                <a href="{{ route('admin.periods.show', $currentPeriodStats['period']) }}"
                                   class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                                    Bekijk details &rarr;
                                </a>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Recent Enrollments -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">Recente Inschrijvingen</h3>
                            <a href="{{ route('admin.enrollments.index') }}" class="text-indigo-600 hover:text-indigo-800 text-sm">
                                Alle bekijken
                            </a>
                        </div>

                        @if($recentEnrollments->isEmpty())
                            <p class="text-gray-500 text-sm">Geen recente inschrijvingen.</p>
                        @else
                            <div class="space-y-3">
                                @foreach($recentEnrollments as $enrollment)
                                    <div class="flex justify-between items-center text-sm border-b pb-2">
                                        <div>
                                            <span class="font-medium">{{ $enrollment->user->name }}</span>
                                            <span class="text-gray-500">-</span>
                                            <span class="text-gray-700">{{ $enrollment->keuzedeelInstance->keuzedeel->name }}</span>
                                        </div>
                                        <span class="text-gray-400 text-xs">
                                            {{ $enrollment->enrolled_at->diffForHumans() }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="mt-8 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Snelle Acties</h3>
                    <div class="flex flex-wrap gap-4">
                        <a href="{{ route('admin.keuzedelen.create') }}"
                           class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                            + Nieuw Keuzedeel
                        </a>
                        <a href="{{ route('admin.periods.create') }}"
                           class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                            + Nieuwe Periode
                        </a>
                        <a href="{{ route('admin.programs.create') }}"
                           class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700">
                            + Nieuwe Opleiding
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
