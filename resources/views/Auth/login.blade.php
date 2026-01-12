<!DOCTYPE html>
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>
<body class=" bg-zinc-950 flex justify-center items-center h-screen">


    
    <div class="bg-zinc-800 w-80 sm:w-96 p-6 rounded-tl-full shadow-lg">
        @if (session('success'))
            <div id="alert"  class="flex items-center gap-2 mb-4 rounded-tl-full rounded-lg bg-zinc-800 p-4 text-blue-700 mb-5">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M5 13l4 4L19 7" />
                </svg>
                <span>{{ session('success') }}</span>
                <script>
                    setTimeout(() => {
                        const alert = document.getElementById('alert');
                        alert.style.opacity = '0';
                        setTimeout(() => alert.remove(), 500);
                    }, 1000); // 4 seconds and hide
                </script>
            </div>
        @endif
        <h2 class="text-center text-2xl font-bold text-blue-700 mb-5">Login Form</h2>

        <form action="{{ route('login.user') }}" method="POST" class="space-y-4">
            @csrf
             
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <span class="text-red-500">{{ session('error') }}</span>
                    </ul>
                </div>
            
            
            <div>
                <label class="block mb-1  text-teal-100">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" HRTHRTHRTH placeholder="Email"
                       class="w-full p-2 border bg-zinc-500 placeholder:text-zinc-800 border-gray-300 rounded-lg focus:ring focus:ring-blue-300 outline-none" />
            </div>
 
            <div>
                <label class="block mb-1 text-teal-100">Password</label>
                <input type="password" name="password"  placeholder="Password"
                       class="w-full p-2 border  bg-zinc-500 border-gray-300 rounded-lg focus:ring focus:ring-blue-300 outline-none" />
            </div>
                
            <button type="submit"
                    class="w-full bg-blue-700 text-white py-2 rounded-lg  hover:bg-blue-800 transition"  >login</button>
                    {{-- <input type="checkbox" name="remember"> Remember Me --}}
        </form>
        <p class="py-3  text-teal-100 ">Forget password ? <a href="#" class="text-blue-400 hover:underline">Click Here</a></p>
        <p class=" text-teal-100 "> Dont have an account ?  <a href="{{ route('register') }}" class="text-blue-400 hover:underline">Click Here</a>
    </div>
</body>
</html>
