<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Admin Panel</title>
  <script src="https://cdn.tailwindcss.com"></script>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">

  <div class="flex min-h-screen">

    <!-- Sidebar -->

    <aside id="model" class="w-64 bg-gray-900 text-white">
      <div class="p-4 text-xl font-bold border-b border-gray-700">
        Admin Panel
      </div>
      <nav class="p-4 space-y-2">
        <a href="{{ route('admin') }}" class="block px-4 py-2 rounded hover:bg-gray-700">Dashboard</a>
        <a href="{{ route('dashboard.keuezedeel') }}" class="block px-4 py-2 rounded hover:bg-gray-700">Keuzedeel</a>
        <a href="#" class="block px-4 py-2 rounded hover:bg-gray-700">Settings</a>
      </nav>
    </aside>

      <!-- Main -->
      
      <main class="flex-1">
          <!-- Header -->
          <header class="bg-white shadow p-4 flex justify-between items-center">
            <h1 class="text-xl font-semibold">@yield('text')</h1>
            <div class="flex items-center gap-3">
              <span class="text-gray-600">Hussein</span>
              <img src="https://i.pravatar.cc/40" class="rounded-full" />
            </div>
          </header>


            @yield('content')
      </main>



</body>
</html>