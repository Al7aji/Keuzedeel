<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Keuzedelen Presentatie - {{ $period->name }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .slide {
            display: none;
            min-height: 100vh;
            padding: 4rem;
        }
        .slide.active {
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .progress-bar {
            position: fixed;
            bottom: 0;
            left: 0;
            height: 4px;
            background: #4f46e5;
            transition: width 0.3s;
        }
        .controls {
            position: fixed;
            bottom: 20px;
            right: 20px;
            display: flex;
            gap: 10px;
        }
    </style>
</head>
<body class="bg-gray-900 text-white" x-data="presentation()">
    <!-- Title Slide -->
    <div class="slide" :class="{ 'active': currentSlide === 0 }">
        <div class="text-center">
            <h1 class="text-6xl font-bold mb-8">Keuzedelen</h1>
            <p class="text-3xl text-gray-400 mb-4">{{ $period->name }}</p>
            <p class="text-xl text-gray-500">{{ $period->academic_year }}</p>
        </div>
    </div>

    <!-- Keuzedeel Slides -->
    @foreach($keuzedelen as $index => $keuzedeel)
        <div class="slide" :class="{ 'active': currentSlide === {{ $index + 1 }} }">
            <div class="max-w-5xl mx-auto w-full">
                <h2 class="text-5xl font-bold mb-6">{{ $keuzedeel->name }}</h2>

                @if($keuzedeel->short_description)
                    <p class="text-2xl text-gray-300 mb-8">{{ $keuzedeel->short_description }}</p>
                @endif

                <div class="grid grid-cols-2 gap-8 mb-8">
                    <div class="bg-gray-800 rounded-xl p-6">
                        <h3 class="text-xl font-semibold mb-4 text-indigo-400">Informatie</h3>
                        <dl class="space-y-3 text-lg">
                            <div class="flex justify-between">
                                <dt class="text-gray-400">Code</dt>
                                <dd>{{ $keuzedeel->code }}</dd>
                            </div>
                            @if($keuzedeel->credits)
                                <div class="flex justify-between">
                                    <dt class="text-gray-400">Studiepunten</dt>
                                    <dd>{{ $keuzedeel->credits }} EC</dd>
                                </div>
                            @endif
                            <div class="flex justify-between">
                                <dt class="text-gray-400">Min. studenten</dt>
                                <dd>{{ $keuzedeel->min_students }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-gray-400">Max. studenten</dt>
                                <dd>{{ $keuzedeel->max_students }}</dd>
                            </div>
                        </dl>
                    </div>

                    <div class="bg-gray-800 rounded-xl p-6">
                        <h3 class="text-xl font-semibold mb-4 text-indigo-400">Beschikbaarheid</h3>
                        @foreach($keuzedeel->instances as $instance)
                            <div class="mb-4">
                                <div class="flex justify-between text-lg mb-2">
                                    <span>{{ $instance->display_name }}</span>
                                    <span class="{{ $instance->isFull() ? 'text-red-400' : 'text-green-400' }}">
                                        {{ $instance->enrollment_count }}/{{ $keuzedeel->max_students }}
                                    </span>
                                </div>
                                <div class="w-full bg-gray-700 rounded-full h-4">
                                    <div class="h-4 rounded-full {{ $instance->fill_percentage >= 100 ? 'bg-red-500' : ($instance->fill_percentage >= 80 ? 'bg-orange-500' : 'bg-green-500') }}"
                                         style="width: {{ min($instance->fill_percentage, 100) }}%"></div>
                                </div>
                                <div class="text-sm text-gray-400 mt-1">
                                    {{ $instance->available_spots }} plekken vrij
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                @if($keuzedeel->is_repeatable)
                    <div class="bg-blue-900/50 rounded-lg p-4 text-center">
                        <span class="text-blue-300">Dit keuzedeel kan meerdere keren worden gevolgd</span>
                    </div>
                @endif
            </div>
        </div>
    @endforeach

    <!-- End Slide -->
    <div class="slide" :class="{ 'active': currentSlide === {{ $keuzedelen->count() + 1 }} }">
        <div class="text-center">
            <h2 class="text-5xl font-bold mb-8">Vragen?</h2>
            <p class="text-2xl text-gray-400">Inschrijven kan via het keuzedelen portaal</p>
        </div>
    </div>

    <!-- Progress Bar -->
    <div class="progress-bar" :style="{ width: ((currentSlide / totalSlides) * 100) + '%' }"></div>

    <!-- Controls -->
    <div class="controls">
        <span class="text-gray-400 mr-4" x-text="(currentSlide + 1) + ' / ' + (totalSlides + 1)"></span>
        <button @click="prevSlide" class="px-4 py-2 bg-gray-700 rounded hover:bg-gray-600">
            &larr; Vorige
        </button>
        <button @click="nextSlide" class="px-4 py-2 bg-indigo-600 rounded hover:bg-indigo-700">
            Volgende &rarr;
        </button>
        <a href="{{ route('slber.presentation.index') }}" class="px-4 py-2 bg-gray-700 rounded hover:bg-gray-600">
            Afsluiten
        </a>
    </div>

    <script>
        function presentation() {
            return {
                currentSlide: 0,
                totalSlides: {{ $keuzedelen->count() + 1 }},

                init() {
                    document.addEventListener('keydown', (e) => {
                        if (e.key === 'ArrowRight' || e.key === ' ') {
                            this.nextSlide();
                        } else if (e.key === 'ArrowLeft') {
                            this.prevSlide();
                        } else if (e.key === 'Escape') {
                            window.location.href = '{{ route("slber.presentation.index") }}';
                        }
                    });
                },

                nextSlide() {
                    if (this.currentSlide < this.totalSlides) {
                        this.currentSlide++;
                    }
                },

                prevSlide() {
                    if (this.currentSlide > 0) {
                        this.currentSlide--;
                    }
                }
            }
        }
    </script>
</body>
</html>
