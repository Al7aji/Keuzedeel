<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('titel')</title>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js' ,'resources/js/layout.js' ])
</head>
<body class="  flex flex-col  w-full">
    <header class="sticky top-0 left-0 w-full z-50 bg-[#911be0]">
        <nav class="  flex justify-between items-center p-2 w-[90%]  mx-auto">
            <div>
                <h1 class="text-lg text-white font-bold w-16 ">Keuzedeel</h1>
            </div>
            <div class=" nav-links md:static md:block md:min-h-fit absolute md:text-white md:bg-[#911be0] bg-white min-h-[60vh] md:w-auto left-0 hidden top-[100%]  w-full " >
                <ul class="flex md:flex-row flex-col items-center gap-[4vw]  ">
                    <li>
                        <a class=" hover:text-gray-500" href="{{ route('home') }}">Home</a>
                    </li>
                    @if (auth()->check)
                        <li>
                            <a class=" hover:text-gray-500" href="{{ route('dashboard') }}">Dashboard</a>
                        </li>
                    @endif
                    <li>
                        <a class=" hover:text-gray-500" href="{{ route('Keuzedeel') }}">Keuzedeel</a>
                    </li>
                    <li>
                        <a class=" hover:text-gray-500" href="{{ route('admin') }}">admin</a>
                    </li>
                </ul>
            </div>
            <div class=" flex items-center gap-6  ">
                <a href="{{ route('login')}}" class="bg-[#330469] text-white px-5 py-2  rounded-full hover:bg-[#87ac] " >Sign in</a>
                <ion-icon onclick="onToggleMenu(this)" name="menu-outline" class="text-3xl cursor-pointer md:hidden "></ion-icon>
            </div>
        </nav>
    </header>

     
     <div class="z-1 flex flex-col h:screen">
          @yield('content')
          
     </div>



       
</body>
</html>