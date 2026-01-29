<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $keuzedeel->name }}
            </h2>
            <a href="{{ route('student.keuzedelen.index') }}" class="text-indigo-600 hover:text-indigo-800">
                &larr; Terug naar overzicht
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Content -->
                <div class="lg:col-span-2">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <!-- Badges -->
                            <div class="flex flex-wrap gap-2 mb-4">
                                @if($hasCompleted)
                                    <span class="bg-green-100 text-green-800 text-sm font-medium px-3 py-1 rounded-full">
                                        Je hebt dit keuzedeel afgerond
                                    </span>
                                @endif
                                @if($keuzedeel->is_repeatable)
                                    <span class="bg-blue-100 text-blue-800 text-sm font-medium px-3 py-1 rounded-full">
                                        Herhaalbaar
                                    </span>
                                @endif
                                @if($keuzedeel->credits)
                                    <span class="bg-purple-100 text-purple-800 text-sm font-medium px-3 py-1 rounded-full">
                                        {{ $keuzedeel->credits }} EC
                                    </span>
                                @endif
                            </div>

                            <!-- Short Description -->
                            @if($keuzedeel->short_description)
                                <p class="text-lg text-gray-700 mb-6">
                                    {{ $keuzedeel->short_description }}
                                </p>
                            @endif

                            <!-- Full Content -->
                            @if($keuzedeel->content)
                                <div class="prose max-w-none">
                                    {!! $keuzedeel->content !!}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-1">
                    <!-- Available Instances -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Inschrijven</h3>

                            @if($instances->isEmpty())
                                <p class="text-gray-500">
                                    Er zijn momenteel geen open inschrijvingen voor dit keuzedeel.
                                </p>
                            @else
                                <div class="space-y-4">
                                    @foreach($instances as $instance)
                                        @php
                                            $canEnroll = $instance->can_enroll_result;
                                        @endphp
                                        <div class="border rounded-lg p-4">
                                            <div class="font-medium text-gray-900 mb-2">
                                                {{ $instance->period->name }}
                                            </div>
                                            <div class="text-sm text-gray-600 mb-2">
                                                {{ $instance->period->academic_year }} - Periode {{ $instance->period->period_number }}
                                            </div>

                                            <!-- Capacity -->
                                            <div class="mb-3">
                                                <div class="flex justify-between text-sm text-gray-600 mb-1">
                                                    <span>{{ $instance->enrollment_count }}/{{ $keuzedeel->max_students }}</span>
                                                    <span>{{ $instance->available_spots }} vrij</span>
                                                </div>
                                                <div class="w-full bg-gray-200 rounded-full h-2">
                                                    <div class="h-2 rounded-full {{ $instance->fill_percentage >= 100 ? 'bg-red-500' : ($instance->fill_percentage >= 80 ? 'bg-orange-500' : 'bg-green-500') }}"
                                                         style="width: {{ min($instance->fill_percentage, 100) }}%"></div>
                                                </div>
                                            </div>

                                            @if($canEnroll['can_enroll'])
                                                <form method="POST" action="{{ route('student.enrollments.store') }}">
                                                    @csrf
                                                    <input type="hidden" name="keuzedeel_instance_id" value="{{ $instance->id }}">
                                                    <button type="submit"
                                                            class="w-full bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium py-2 px-4 rounded-md"
                                                            onclick="return confirm('Weet je zeker dat je je wilt inschrijven?')">
                                                        Inschrijven
                                                    </button>
                                                </form>
                                            @else
                                                <div class="text-center text-gray-500 text-sm py-2">
                                                    @switch($canEnroll['reason'] ?? '')
                                                        @case('full')
                                                            Dit keuzedeel is vol
                                                            @break
                                                        @case('already_enrolled_period')
                                                            Je bent al ingeschreven in deze periode
                                                            @break
                                                        @case('already_completed')
                                                            Je hebt dit keuzedeel al afgerond
                                                            @break
                                                        @case('enrollment_closed')
                                                            Inschrijving is gesloten
                                                            @break
                                                        @default
                                                            Niet beschikbaar
                                                    @endswitch
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Info Box -->
                    <div class="bg-gray-50 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Informatie</h3>
                            <dl class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <dt class="text-gray-500">Code</dt>
                                    <dd class="text-gray-900 font-medium">{{ $keuzedeel->code }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-gray-500">Min. studenten</dt>
                                    <dd class="text-gray-900">{{ $keuzedeel->min_students }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-gray-500">Max. studenten</dt>
                                    <dd class="text-gray-900">{{ $keuzedeel->max_students }}</dd>
                                </div>
                                @if($keuzedeel->credits)
                                    <div class="flex justify-between">
                                        <dt class="text-gray-500">Studiepunten</dt>
                                        <dd class="text-gray-900">{{ $keuzedeel->credits }} EC</dd>
                                    </div>
                                @endif
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
