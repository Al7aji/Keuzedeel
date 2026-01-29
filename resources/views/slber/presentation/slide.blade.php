<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $keuzedeel->name }} - Presentatie</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-900 text-white min-h-screen flex items-center justify-center p-8">
    <div class="max-w-5xl w-full">
        <h1 class="text-5xl font-bold mb-6">{{ $keuzedeel->name }}</h1>

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
                    </div>
                @endforeach
            </div>
        </div>

        <div class="bg-gray-800 rounded-xl p-6">
            <h3 class="text-xl font-semibold mb-4 text-indigo-400">Opleidingen</h3>
            <div class="flex flex-wrap gap-2">
                @foreach($keuzedeel->programs as $program)
                    <span class="bg-gray-700 px-4 py-2 rounded-lg">{{ $program->name }}</span>
                @endforeach
            </div>
        </div>

        @if($keuzedeel->is_repeatable)
            <div class="mt-6 bg-blue-900/50 rounded-lg p-4 text-center">
                <span class="text-blue-300">Dit keuzedeel kan meerdere keren worden gevolgd</span>
            </div>
        @endif
    </div>

    <a href="{{ route('slber.presentation.index') }}"
       class="fixed bottom-8 right-8 px-4 py-2 bg-gray-700 rounded hover:bg-gray-600">
        Terug naar overzicht
    </a>
</body>
</html>
