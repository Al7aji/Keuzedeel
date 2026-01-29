<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Beschikbare Keuzedelen
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Period Selector -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <form method="GET" action="{{ route('student.keuzedelen.index') }}" class="flex items-center gap-4">
                        <label for="period_id" class="font-medium text-gray-700">Periode:</label>
                        <select name="period_id" id="period_id" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" onchange="this.form.submit()">
                            @foreach($periods as $period)
                                <option value="{{ $period->id }}" {{ $currentPeriod && $currentPeriod->id == $period->id ? 'selected' : '' }}>
                                    {{ $period->full_name }}
                                    @if($period->enrollment_open)
                                        (Inschrijving open)
                                    @endif
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>
            </div>

            @if(!$currentPeriod)
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 text-center">
                    <p class="text-yellow-800">Er zijn momenteel geen periodes beschikbaar.</p>
                </div>
            @elseif($instances->isEmpty())
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-6 text-center">
                    <p class="text-gray-600">Er zijn geen keuzedelen beschikbaar voor jouw opleiding in deze periode.</p>
                </div>
            @else
                <!-- Keuzedelen Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($instances as $instance)
                        @php
                            $keuzedeel = $instance->keuzedeel;
                            $canEnroll = $instance->can_enroll_result;
                            $fillPercentage = $instance->fill_percentage;
                            $isCompleted = in_array($keuzedeel->id, $completedIds);
                        @endphp

                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg {{ !$canEnroll['can_enroll'] ? 'opacity-75' : '' }}">
                            <div class="p-6">
                                <!-- Status Badge -->
                                <div class="flex justify-between items-start mb-3">
                                    <h3 class="text-lg font-semibold text-gray-900">
                                        <a href="{{ route('student.keuzedelen.show', $keuzedeel) }}" class="hover:text-indigo-600">
                                            {{ $instance->display_name }}
                                        </a>
                                    </h3>
                                    @if($isCompleted)
                                        <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded">
                                            Afgerond
                                        </span>
                                    @elseif($instance->isFull())
                                        <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded">
                                            Vol
                                        </span>
                                    @elseif($fillPercentage >= 80)
                                        <span class="bg-orange-100 text-orange-800 text-xs font-medium px-2.5 py-0.5 rounded">
                                            Bijna vol
                                        </span>
                                    @else
                                        <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded">
                                            Beschikbaar
                                        </span>
                                    @endif
                                </div>

                                <!-- Description -->
                                <p class="text-gray-600 text-sm mb-4">
                                    {{ Str::limit($keuzedeel->short_description, 120) }}
                                </p>

                                <!-- Capacity Bar -->
                                <div class="mb-4">
                                    <div class="flex justify-between text-sm text-gray-600 mb-1">
                                        <span>{{ $instance->enrollment_count }}/{{ $keuzedeel->max_students }} plekken</span>
                                        <span>{{ $instance->available_spots }} vrij</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="h-2 rounded-full {{ $fillPercentage >= 100 ? 'bg-red-500' : ($fillPercentage >= 80 ? 'bg-orange-500' : 'bg-green-500') }}"
                                             style="width: {{ min($fillPercentage, 100) }}%"></div>
                                    </div>
                                </div>

                                <!-- Actions -->
                                <div class="flex justify-between items-center">
                                    <a href="{{ route('student.keuzedelen.show', $keuzedeel) }}"
                                       class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                                        Meer info &rarr;
                                    </a>

                                    @if($canEnroll['can_enroll'])
                                        <form method="POST" action="{{ route('student.enrollments.store') }}">
                                            @csrf
                                            <input type="hidden" name="keuzedeel_instance_id" value="{{ $instance->id }}">
                                            <button type="submit"
                                                    class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium py-2 px-4 rounded-md"
                                                    onclick="return confirm('Weet je zeker dat je je wilt inschrijven voor {{ $keuzedeel->name }}?')">
                                                Inschrijven
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-gray-400 text-sm">
                                            @switch($canEnroll['reason'] ?? '')
                                                @case('full')
                                                    Vol
                                                    @break
                                                @case('already_enrolled_period')
                                                    Al ingeschreven
                                                    @break
                                                @case('already_completed')
                                                    Al afgerond
                                                    @break
                                                @case('enrollment_closed')
                                                    Inschrijving gesloten
                                                    @break
                                                @default
                                                    Niet beschikbaar
                                            @endswitch
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
