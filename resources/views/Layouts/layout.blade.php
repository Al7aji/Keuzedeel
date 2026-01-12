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
<body class=" bg-gradient-to-t from-[#fbc2ed] to-[#a6c1ee] h-screen">
    <header class=" bg-white">
        <nav class=" flex justify-between items-center p-2 w-[90%]  mx-auto">
            <div>
                <h1 class="text-lg font-bold w-16 ">Keuzedeel</h1>
            </div>
            <div class=" nav-links md:static md:min-h-fit absolute bg-white min-h-[60vh] md:w-auto left-0 top-[100%] w-full " >
                <ul class="flex md:flex-row flex-col items-center gap-[4vw]  ">
                    <li>
                        <a class=" hover:text-gray-500" href="#" >Home</a>
                    </li>
                    <li>
                        <a class=" hover:text-gray-500" href="#">Keuzedeel</a>
                    </li>
                </ul>
            </div>
            <div class=" flex items-center gap-6  ">
                <button  class="bg-[#a6c1ee] text-white px-5 py-2  rounded-full hover:bg-[#87ac] " >Sign in</button>
                <ion-icon onclick="onToggleMenu(this)" name="menu-outline" class="text-3xl cursor-pointer md:hidden "></ion-icon>
            </div>
            

          

        </nav>
    </header>

     
     <div class="">
          @yield('content')
      
     </div>

     <footer class="">


     </footer>

       
</body>
</html>: