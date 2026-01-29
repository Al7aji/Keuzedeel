<!DOCTYPE html>
<html lang="nl">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Keuzedelen - Inschrijfportaal</title>
            @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
<body class="antialiased">
    <div class="min-h-screen bg-gradient-to-br from-indigo-900 via-purple-900 to-indigo-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <!-- Navigation -->
            <nav class="flex justify-between items-center mb-16">
                <div class="text-white text-2xl font-bold">
                    Keuzedelen
                </div>
                <div class="space-x-4">
                    @auth
                        <a href="{{ route('dashboard') }}" class="text-white hover:text-indigo-200">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="text-white hover:text-indigo-200">Inloggen</a>
                        <a href="{{ route('register') }}" class="bg-white text-indigo-900 px-4 py-2 rounded-lg hover:bg-indigo-100">
                            Registreren
                        </a>
                    @endauth
                </div>
                </nav>

            <!-- Hero Section -->
            <div class="text-center py-20">
                <h1 class="text-5xl md:text-6xl font-bold text-white mb-6">
                    Keuzedelen Inschrijfportaal
                </h1>
                <p class="text-xl text-indigo-200 mb-8 max-w-2xl mx-auto">
                    Bekijk beschikbare keuzedelen, schrijf je eenvoudig in en beheer je inschrijvingen op één centrale plek.
                </p>
                @guest
                    <div class="space-x-4">
                        <a href="{{ route('login') }}"
                           class="inline-block bg-white text-indigo-900 px-8 py-3 rounded-lg font-semibold hover:bg-indigo-100 transition">
                            Inloggen
                        </a>
                        <a href="{{ route('register') }}"
                           class="inline-block border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white/10 transition">
                            Account aanmaken
                        </a>
                    </div>
                @endguest
            </div>

            <!-- Features -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-16">
                <div class="bg-white/10 backdrop-blur-sm rounded-xl p-8 text-white">
                    <div class="text-3xl mb-4">📚</div>
                    <h3 class="text-xl font-semibold mb-2">Keuzedelen Overzicht</h3>
                    <p class="text-indigo-200">
                        Bekijk alle beschikbare keuzedelen met uitgebreide informatie over inhoud, capaciteit en beschikbaarheid.
                    </p>
                </div>

                <div class="bg-white/10 backdrop-blur-sm rounded-xl p-8 text-white">
                    <div class="text-3xl mb-4">✍️</div>
                    <h3 class="text-xl font-semibold mb-2">Eenvoudig Inschrijven</h3>
                    <p class="text-indigo-200">
                        Schrijf je met één klik in voor keuzedelen. Real-time updates over beschikbare plekken.
                    </p>
                </div>

                <div class="bg-white/10 backdrop-blur-sm rounded-xl p-8 text-white">
                    <div class="text-3xl mb-4">📊</div>
                    <h3 class="text-xl font-semibold mb-2">Beheer & Overzicht</h3>
                    <p class="text-indigo-200">
                        Bekijk je inschrijvingen, volg je voortgang en beheer alles vanuit je persoonlijke dashboard.
                    </p>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="mt-16 border-t border-white/10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <p class="text-center text-indigo-300 text-sm">
                    &copy; {{ date('Y') }} Keuzedelen Portaal
                </p>
            </div>
        </footer>
    </div>
    </body>
</html>
