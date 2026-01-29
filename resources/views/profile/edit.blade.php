<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Profiel
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            @if(auth()->user()->isStudent())
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <div class="max-w-xl">
                        <section>
                            <header>
                                <h2 class="text-lg font-medium text-gray-900">
                                    Student Informatie
                                </h2>
                                <p class="mt-1 text-sm text-gray-600">
                                    Selecteer je opleiding om keuzedelen te kunnen bekijken.
                                </p>
                            </header>

                            <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
                                @csrf
                                @method('patch')

                                <div>
                                    <x-input-label for="student_number" value="Studentnummer" />
                                    <x-text-input id="student_number" name="student_number" type="text" class="mt-1 block w-full"
                                                  :value="old('student_number', $user->student_number)" />
                                    <x-input-error class="mt-2" :messages="$errors->get('student_number')" />
                                </div>

                                <div>
                                    <x-input-label for="program_id" value="Opleiding" />
                                    <select id="program_id" name="program_id"
                                            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                        <option value="">Selecteer een opleiding...</option>
                                        @foreach($programs as $program)
                                            <option value="{{ $program->id }}" {{ old('program_id', $user->program_id) == $program->id ? 'selected' : '' }}>
                                                {{ $program->name }} ({{ $program->code }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <x-input-error class="mt-2" :messages="$errors->get('program_id')" />
                                </div>

                                <div class="flex items-center gap-4">
                                    <x-primary-button>Opslaan</x-primary-button>

                                    @if (session('status') === 'profile-updated')
                                        <p x-data="{ show: true }"
                                           x-show="show"
                                           x-transition
                                           x-init="setTimeout(() => show = false, 2000)"
                                           class="text-sm text-gray-600">Opgeslagen.</p>
                                    @endif
                                </div>
                            </form>
                        </section>
                    </div>
                </div>
            @endif

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
