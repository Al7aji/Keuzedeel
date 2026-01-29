<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Keuzedelen Beheren
            </h2>
            <a href="{{ route('admin.keuzedelen.create') }}"
               class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                + Nieuw Keuzedeel
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Filters -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <form method="GET" action="{{ route('admin.keuzedelen.index') }}" class="flex flex-wrap gap-4">
                        <div class="flex-1 min-w-[200px]">
                            <input type="text" name="search" value="{{ request('search') }}"
                                   placeholder="Zoeken op naam of code..."
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div>
                            <select name="active" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Alle statussen</option>
                                <option value="1" {{ request('active') === '1' ? 'selected' : '' }}>Actief</option>
                                <option value="0" {{ request('active') === '0' ? 'selected' : '' }}>Inactief</option>
                            </select>
                        </div>
                        <button type="submit" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                            Filteren
                        </button>
                        <a href="{{ route('admin.keuzedelen.index') }}" class="px-4 py-2 text-gray-600 hover:text-gray-800">
                            Reset
                        </a>
                    </form>
                </div>
            </div>

            <!-- Table -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if($keuzedelen->isEmpty())
                        <p class="text-gray-500 text-center py-4">Geen keuzedelen gevonden.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Naam</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Code</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Opleidingen</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Capaciteit</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Acties</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($keuzedelen as $keuzedeel)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">{{ $keuzedeel->name }}</div>
                                                @if($keuzedeel->is_repeatable)
                                                    <span class="text-xs text-blue-600">Herhaalbaar</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $keuzedeel->code }}
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="flex flex-wrap gap-1">
                                                    @foreach($keuzedeel->programs->take(3) as $program)
                                                        <span class="text-xs bg-gray-100 px-2 py-1 rounded">{{ $program->code }}</span>
                                                    @endforeach
                                                    @if($keuzedeel->programs->count() > 3)
                                                        <span class="text-xs text-gray-500">+{{ $keuzedeel->programs->count() - 3 }}</span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $keuzedeel->min_students }} - {{ $keuzedeel->max_students }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($keuzedeel->is_active)
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                        Actief
                                                    </span>
                                                @else
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                        Inactief
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                                <a href="{{ route('admin.keuzedelen.show', $keuzedeel) }}" class="text-gray-600 hover:text-gray-900">Bekijken</a>
                                                <a href="{{ route('admin.keuzedelen.edit', $keuzedeel) }}" class="text-indigo-600 hover:text-indigo-900">Bewerken</a>
                                                <form method="POST" action="{{ route('admin.keuzedelen.toggle-active', $keuzedeel) }}" class="inline">
                                                    @csrf
                                                    <button type="submit" class="text-orange-600 hover:text-orange-900">
                                                        {{ $keuzedeel->is_active ? 'Deactiveren' : 'Activeren' }}
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            {{ $keuzedelen->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
