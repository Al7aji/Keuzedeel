<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $keuzedeel->name }}
            </h2>
            <div class="space-x-2">
                <a href="{{ route('admin.keuzedelen.edit', $keuzedeel) }}" class="text-indigo-600 hover:text-indigo-800">
                    Bewerken
                </a>
                <a href="{{ route('admin.keuzedelen.index') }}" class="text-gray-600 hover:text-gray-800">
                    &larr; Terug
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Info -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Details</h3>

                            <dl class="grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <dt class="text-gray-500">Code</dt>
                                    <dd class="font-medium">{{ $keuzedeel->code }}</dd>
                                </div>
                                <div>
                                    <dt class="text-gray-500">Status</dt>
                                    <dd>
                                        @if($keuzedeel->is_active)
                                            <span class="text-green-600 font-medium">Actief</span>
                                        @else
                                            <span class="text-gray-600 font-medium">Inactief</span>
                                        @endif
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-gray-500">Capaciteit</dt>
                                    <dd>{{ $keuzedeel->min_students }} - {{ $keuzedeel->max_students }} studenten</dd>
                                </div>
                                <div>
                                    <dt class="text-gray-500">Herhaalbaar</dt>
                                    <dd>{{ $keuzedeel->is_repeatable ? 'Ja' : 'Nee' }}</dd>
                                </div>
                            </dl>

                            @if($keuzedeel->short_description)
                                <div class="mt-4 pt-4 border-t">
                                    <h4 class="text-sm font-medium text-gray-500 mb-2">Korte omschrijving</h4>
                                    <p class="text-gray-700">{{ $keuzedeel->short_description }}</p>
                                </div>
                            @endif

                            <div class="mt-4 pt-4 border-t">
                                <h4 class="text-sm font-medium text-gray-500 mb-2">Opleidingen</h4>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($keuzedeel->programs as $program)
                                        <span class="bg-gray-100 px-3 py-1 rounded text-sm">{{ $program->name }}</span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Instances & Enrollments -->
                    @foreach($keuzedeel->instances->groupBy('period_id') as $periodId => $instances)
                        @php $period = $instances->first()->period; @endphp
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                                    {{ $period->name }} ({{ $period->academic_year }})
                                </h3>

                                @foreach($instances as $instance)
                                    <div class="border rounded-lg p-4 mb-4">
                                        <div class="flex justify-between items-center mb-3">
                                            <span class="font-medium">{{ $instance->display_name }}</span>
                                            <span class="text-sm text-gray-500">
                                                {{ $instance->activeEnrollments->count() }}/{{ $keuzedeel->max_students }} studenten
                                            </span>
                                        </div>

                                        @if($instance->activeEnrollments->isEmpty())
                                            <p class="text-gray-500 text-sm">Geen inschrijvingen.</p>
                                        @else
                                            <div class="overflow-x-auto">
                                                <table class="min-w-full divide-y divide-gray-200 text-sm">
                                                    <thead>
                                                        <tr>
                                                            <th class="text-left py-2">Student</th>
                                                            <th class="text-left py-2">Ingeschreven</th>
                                                            <th class="text-left py-2">Status</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="divide-y divide-gray-100">
                                                        @foreach($instance->activeEnrollments as $enrollment)
                                                            <tr>
                                                                <td class="py-2">{{ $enrollment->user->name }}</td>
                                                                <td class="py-2 text-gray-500">{{ $enrollment->enrolled_at->format('d-m-Y') }}</td>
                                                                <td class="py-2">
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
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-1">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Acties</h3>
                            <div class="space-y-3">
                                <a href="{{ route('admin.keuzedelen.edit', $keuzedeel) }}"
                                   class="block w-full text-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                                    Bewerken
                                </a>
                                <form method="POST" action="{{ route('admin.keuzedelen.toggle-active', $keuzedeel) }}">
                                    @csrf
                                    <button type="submit"
                                            class="block w-full text-center px-4 py-2 {{ $keuzedeel->is_active ? 'bg-orange-600 hover:bg-orange-700' : 'bg-green-600 hover:bg-green-700' }} text-white rounded-md">
                                        {{ $keuzedeel->is_active ? 'Deactiveren' : 'Activeren' }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
