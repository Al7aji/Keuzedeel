@extends('admin.layout')
@section('text') keuzedeel @endsection
@section('content')

    <section class="py-10">
        <div class="max-w-xl mx-auto bg-white p-6 rounded-2xl shadow">

               @if (session('success'))
                    <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
               @endif

            <h2 class="text-2xl font-bold mb-6">Add Keuzedeel</h2>
            <form method="POST" action="{{ route('Keuzedeel.store') }}" 
                enctype="multipart/form-data"
                class="space-y-4">
                @csrf

                <!-- Title -->
                <input
                    name="titel"
                    value="{{ old('titel') }}"
                    placeholder="Title"
                    class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-gray-900"
                >
                @error('titel')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror


                <!-- Image Upload -->
                <div class="relative">
                    <label
                        for="image"
                        class="flex flex-col items-center justify-center w-full h-40 border-2 border-dashed border-gray-300 rounded-xl cursor-pointer
                            hover:border-gray-900 hover:bg-gray-50 transition"
                    >
                        <svg class="w-10 h-10 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 16V4a1 1 0 011-1h8a1 1 0 011 1v12M7 16l-2 2m2-2l2 2m6-2l2 2m-2-2l-2 2M5 20h14" />
                        </svg>
                        <span class="text-sm text-gray-500">Click to upload image</span>
                        <span class="text-xs text-gray-400 mt-1">PNG, JPG, WEBP (max 2MB)</span>
                    </label>
                    <input
                        id="image"
                        name="image"
                        type="file"
                        accept="image/*"
                        class="hidden"
                    >
                @error('image')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                
                </div>


                <!-- code -->
                <input
                    name="code"
                    placeholder="keuzedeelcode"
                    value="{{ old('code') }}"
                    class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-gray-900"
                >
                @error('code')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                

                <!-- Status -->
                <select
                    name="status"
                    
                    class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-gray-900">
                    <option selected disabled value="">Select</option>
                    <option value="Active">Active</option>
                    <option value="InActive">InActive</option>
                </select>
                @error('status')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                

                <!-- Description -->
                <textarea
                    name="description"
                    
                    placeholder="Description"
                    class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-gray-900"
                >{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                <!-- Submit -->
                <button class="w-full bg-gray-900 text-white py-3 rounded-lg font-semibold hover:bg-gray-800 transition">
                    Create
                </button>
            </form>
        </div>
    </section>

@endsection
