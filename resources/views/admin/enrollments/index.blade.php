<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Inschrijvingen Beheren
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Filters -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <form method="GET" action="{{ route('admin.enrollments.index') }}" class="flex flex-wrap gap-4">
                        <div class="flex-1 min-w-[200px]">
                            <input type="text" name="search" value="{{ request('search') }}"
                                   placeholder="Zoeken op naam, studentnummer of email..."
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div>
                            <select name="period_id" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Alle periodes</option>
                                @foreach($periods as $period)
                                    <option value="{{ $period->id }}" {{ request('period_id') == $period->id ? 'selected' : '' }}>
                                        {{ $period->name }} ({{ $period->academic_year }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <select name="status" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Alle statussen</option>
                                <option value="enrolled" {{ request('status') === 'enrolled' ? 'selected' : '' }}>Ingeschreven</option>
                                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Afgerond</option>
                                <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Geannuleerd</option>
                            </select>
                        </div>
                        <button type="submit" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                            Filteren
                        </button>
                        <a href="{{ route('admin.enrollments.index') }}" class="px-4 py-2 text-gray-600 hover:text-gray-800">
                            Reset
                        </a>
                    </form>
                </div>
            </div>

            <!-- Export -->
            @if(request('period_id'))
                <div class="mb-4">
                    <a href="{{ route('admin.enrollments.export', ['period_id' => request('period_id')]) }}"
                       class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                        Exporteren naar CSV
                    </a>
                </div>
            @endif

            <!-- Table -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if($enrollments->isEmpty())
                        <p class="text-gray-500 text-center py-4">Geen inschrijvingen gevonden.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Student</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Keuzedeel</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Periode</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ingeschreven</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Acties</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($enrollments as $enrollment)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">{{ $enrollment->user->name }}</div>
                                                <div class="text-sm text-gray-500">{{ $enrollment->user->student_number ?? $enrollment->user->email }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">{{ $enrollment->keuzedeelInstance->keuzedeel->name }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $enrollment->keuzedeelInstance->period->name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @switch($enrollment->status)
                                                    @case('enrolled')
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                            Ingeschreven
                                                        </span>
                                                        @break
                                                    @case('completed')
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                            Afgerond
                                                        </span>
                                                        @break
                                                    @case('cancelled')
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                            Geannuleerd
                                                        </span>
                                                        @break
                                                @endswitch
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $enrollment->enrolled_at->format('d-m-Y H:i') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                @if($enrollment->status === 'enrolled')
                                                    <form method="POST" action="{{ route('admin.enrollments.update-status', $enrollment) }}" class="inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="status" value="completed">
                                                        <button type="submit" class="text-green-600 hover:text-green-900">
                                                            Afronden
                                                        </button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            {{ $enrollments->appends(request()->query())->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
