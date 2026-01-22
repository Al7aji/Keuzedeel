@extends('admin.layout')

@section('text') Dashboard @endsection
@section('content')
 <section class="p-6 grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white p-4 rounded shadow">
            <h2 class="text-gray-500">Users</h2>
            <p class="text-2xl font-bold">1,245</p>
            </div>

            <div class="bg-white p-4 rounded shadow">
            <h2 class="text-gray-500">Orders</h2>
            <p class="text-2xl font-bold">320</p>
            </div>

            <div class="bg-white p-4 rounded shadow">
            <h2 class="text-gray-500">Revenue</h2>
            <p class="text-2xl font-bold">$9,430</p>
            </div>
 </section>
 <section class="p-6">
      <header class="bg-white shadow p-4 flex justify-between items-center">
        <h1 class="text-xl font-semibold">Users</h1>
        <button  class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
          Add User
        </button>
      </header>

     
        <div class=" bg-white rounded shadow overflow-x-auto">
          <table class="w-full text-left">
            <thead class="bg-gray-100">
              <tr>
                <th class="p-3">ID</th>
                <th class="p-3">Name</th>
                <th class="p-3">Email</th>
                <th class="p-3">roll</th>
                <th class="p-3">Actions</th>
              </tr>
            </thead>
            <tbody id="usersTable">
              <tr class="border-t">
                <td class="p-3">1</td>
                <td class="p-3">Ali</td>
                <td class="p-3">ali@mail.com</td>
                <td class="p-3">student</td>
                <td class="p-3 space-x-2">
                  <button class="text-blue-600">Edit</button>
                  <button class="text-red-600">Delete</button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
  </section>
 


@endsection