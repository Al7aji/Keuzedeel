@extends('admin.layout')

@section('text') Keuzedeel @endsection
@section('content')

 <section class="p-6">
      <header class="bg-white shadow p-4 flex justify-center items-center">
        
        <a href="{{ route('Keuzedeel.create') }}"  class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
          Add Keuzedeel
        </a>
      </header>

     
        <div class=" bg-white rounded shadow overflow-x-auto">
          <table class="w-full text-left">
               @if (session('success'))
                    <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
               @endif
            <thead class="bg-gray-100">
              <tr>
                <th class="p-3">ID</th>
                <th class="p-3">Titel</th>
                <th class="p-3">status</th>
                <th class="p-3">Registered</th>
                <th class="p-3">Actions</th>
              </tr>
            </thead>
            <tbody >
              @foreach ( $keuzedeels as $keuzedeel )
                
              
              <tr class="border-t">
                <td class="p-3">{{ $keuzedeel->id }}</td>
                <td class="p-3">{{ $keuzedeel->titel }}</td>
                <td class="p-3">{{ $keuzedeel->status }}</td>
                <td class="p-3"> 1</td>
                <td class="p-3 flex space-x-2">
                  <button class="text-blue-500">Info</button>
                  <a href="{{ route('Keuzedeel.edit',$keuzedeel->id  ) }}" class="text-blue-600">Edit</a>
                  <form action="{{ route('Keuzedeel.destroy', $keuzedeel->id) }}" method="POST" >
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600">Delete</button>
                  </form>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
  </section>
 


@endsection