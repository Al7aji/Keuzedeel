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
                <td class="p-3 space-x-2">
                  <button class="text-blue-500">Info</button>
                  <a href="{{ route('Keuzedeel.edit',$keuzedeel->id  ) }}" class="text-blue-600">Edit</a>
                  <button class="text-red-600">Delete</button>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
  </section>
 


@endsection