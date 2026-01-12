<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>

    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="  bg-zinc-950 min-h-screen flex items-center justify-center">

    <div class=" bg-zinc-800 rounded-tl-full w-full max-w-md  rounded-xl shadow-lg p-8">
        <h2 class="  text-blue-700 text-2xl font-bold text-center mb-6">Create Account</h2>

        <form method="POST" action="{{ route('register.user') }}" class="space-y-4">
            @csrf
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li class="text-red-500">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif 
            
            <!-- Name -->
            <div>
                <label class="block text-sm font-medium text-teal-100 mb-1">
                    FirstName 
                </label>
                <input type="text" name="firstname" value="{{ old('firstname') }}"
                       class="w-full p-2 border bg-zinc-500 placeholder:text-zinc-800 border-gray-300 rounded-lg focus:ring focus:ring-blue-300 outline-none""
                       >
            </div>
                        <div>
                <label class="block text-sm font-medium text-teal-100 mb-1">
                    LastName 
                </label>
                <input type="text" name="lastname" value="{{ old('lastname') }}"
                       class="w-full p-2 border bg-zinc-500 placeholder:text-zinc-800 border-gray-300 rounded-lg focus:ring focus:ring-blue-300 outline-none""
                       >
            </div>

            <!-- Email -->
            <div>
                <label class="block text-sm font-medium text-teal-100 mb-1">
                    Email
                </label>
                <input type="email" name="email" value="{{ old('email') }}"
                       class="w-full p-2 border bg-zinc-500 placeholder:text-zinc-800 border-gray-300 rounded-lg focus:ring focus:ring-blue-300 outline-none"
                       >
            </div>

            <!-- Password -->
            <div>
                <label class="block text-sm font-medium text-teal-100 mb-1">
                    Password
                </label>
                <input type="password" name="password" "
                       class="w-full px-4 py-2 border   border-gray-300  bg-zinc-500 rounded-lg  focus:ring  focus:ring-blue-300"
                       >
            </div>
            

            <!-- Confirm Password -->
            <div>
                <label class="block text-sm font-medium  text-teal-100 mb-1">
                    Confirm Password
                </label>
                <input type="password" name="password_confirmation" 
                       class="w-full px-4 py-2 border rounded-lg  border-gray-300 bg-zinc-500   placeholder:text-zinc-800  focus:ring-blue-300  focus:ring-2 focus:ring-blue-500"
                       
                       >
                       

                       
            </div>

            <!-- Submit -->
            <button type="submit"
                    class="w-full bg-blue-700 text-white py-2  placeholder:text-zinc-800   focus:ring-blue-300 rounded-lg hover:bg-blue-700 transition">
                Register
            </button>
        </form>

        <p class="text-center text-sm text-gray-600 mt-4">
            Already have an account?
            <a href="{{ route('login') }}" class="text-blue-600 hover:underline">
                Login
            </a>
        </p>
    </div>

</body>
</html>