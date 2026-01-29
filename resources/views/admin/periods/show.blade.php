<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $period->name }} ({{ $period->academic_year }})
            </h2>
            <div class="space-x-2">
                <a href="{{ route('admin.periods.instances', $period) }}" class="text-indigo-600 hover:text-indigo-800">
                    Keuzedelen beheren
                </a>
                <a href="{{ route('admin.periods.index') }}" class="text-gray-600 hover:text-gray-800">
                    &larr; Terug
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Period Info -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div>
                            <span class="text-gray-500 text-sm">Status</span>
                            <div class="mt-1">
                                @if($period->enrollment_open)
                                    <span class="px-2 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800">
                                        Inschrijving open
                                    </span>
                                @else
                                    <span class="px-2 py-1 text-sm font-semibold rounded-full bg-gray-100 text-gray-800">
                                        Inschrijving gesloten
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div>
                            <span class="text-gray-500 text-sm">Periode datums</span>
                            <div class="font-medium">{{ $period->start_date->format('d-m-Y') }} - {{ $period->end_date->format('d-m-Y') }}</div>
                        </div>
                        <div>
                            <span class="text-gray-500 text-sm">Totaal keuzedelen</span>
                            <div class="font-medium">{{ $period->keuzedeelInstances->count() }}</div>
                        </div>
                        <div>
                            <form method="POST" action="{{ route('admin.periods.toggle-enrollment', $period) }}">
                                @csrf
                                <button type="submit"
                                        class="w-full px-4 py-2 {{ $period->enrollment_open ? 'bg-orange-600 hover:bg-orange-700' : 'bg-green-600 hover:bg-green-700' }} text-white rounded-md">
                                    {{ $period->enrollment_open ? 'Inschrijving sluiten' : 'Inschrijving openen' }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Keuzedelen in this period -->
            @foreach($period->keuzedeelInstances as $instance)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-4">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">{{ $instance->display_name }}</h3>
                            <div class="flex items-center space-x-4">
                                <span class="text-sm text-gray-500">
                                    {{ $instance->activeEnrollments->count() }}/{{ $instance->keuzedeel->max_students }} studenten
                                </span>
                                @if(!$instance->hasMinimumStudents())
                                    <span class="px-2 py-1 text-xs rounded-full bg-orange-100 text-orange-800">
                                        Onder minimum ({{ $instance->keuzedeel->min_students }})
                                    </span>
                                @endif
                                @if($instance->isFull())
                                    <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">
                                        Vol
                                    </span>
                                @endif
                            </div>
                        </div>

                        @if($instance->activeEnrollments->isEmpty())
                            <p class="text-gray-500 text-sm">Nog geen inschrijvingen.</p>
                        @else
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 text-sm">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-4 py-2 text-left">Student</th>
                                            <th class="px-4 py-2 text-left">Studentnummer</th>
                                            <th class="px-4 py-2 text-left">Ingeschreven</th>
                                            <th class="px-4 py-2 text-left">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100">
                                        @foreach($instance->activeEnrollments as $enrollment)
                                            <tr>
                                                <td class="px-4 py-2">{{ $enrollment->user->name }}</td>
                                                <td class="px-4 py-2 text-gray-500">{{ $enrollment->user->student_number ?? '-' }}</td>
                                                <td class="px-4 py-2 text-gray-500">{{ $enrollment->enrolled_at->format('d-m-Y H:i') }}</td>
                                                <td class="px-4 py-2">
                                                    <span class="px-2 py-1 text-xs rounded-full {{ $enrollment->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                                        {{ $enrollment->status }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
