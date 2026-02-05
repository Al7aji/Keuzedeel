# Keuzedeel Website

Een webapplicatie voor het **beheer van keuzedeelinschrijvingen** binnen onderwijsinstellingen. 
Het doel van dit project is om het proces van inschrijven, beheren en bekijken van keuzedelen **veel eenvoudiger en overzichtelijker** te maken voor zowel studenten als administratiemedewerkers.

---

## 📝 Probleemstelling

Op dit moment verloopt de inschrijving voor keuzedelen via losse e-mails en handmatig bijgehouden lijsten. 
Extra stappen zijn nodig om studenten bij hun tweede keuze in te schrijven wanneer keuzedelen vol zijn.
Studenten krijgen uitleg over keuzedelen via SLBers en PowerPoint-presentaties, waardoor studenten die ziek zijn de uitleg missen.

**Oplossing:** Een centrale website waar:
- Studenten hun keuzedeel kunnen kiezen en zich inschrijven.
- Administratiemedewerkers eenvoudig overzicht hebben van alle inschrijvingen.
- Informatie over keuzedelen eenvoudig kan worden toegevoegd of aangepast.

---

## ⚡ Functionaliteiten

**Voor studenten:**
- Bekijk per keuzedeel informatie en uitleg.
- Presentatie-achtige interface voor SLBers (PowerPoint-stijl) voor uitleg aan de klas.
- Inschrijven voor keuzedelen.
- Automatische indicatie als een keuzedeel al voltooid is of vol zit.
- Maximaal 1 keuzedeel per periode; sommige keuzedelen kunnen meerdere keren gevolgd worden (bijv. verdieping software).

**Voor administratiemedewerkers:**
- Keuzedelen eenvoudig toevoegen, bewerken en activeren/deactiveren via een **Content Management System**.
- Overzicht van alle inschrijvingen per keuzedeel.
- Inschrijvingsperiode handmatig openen of sluiten.
- Limiet van 15 tot 30 studenten per keuzedeel.
- Beveiligd login systeem.

---

## 🛠️ Vereisten & Technische stack

- **Backend:** Laravel (PHP framework)
- **Frontend:** Blade templates, CSS, JavaScript, Vite
- **Database:** MySQL of vergelijkbaar
- **Dependency management:** Composer & NPM
- **Server:** Lokaal via XAMPP, Laragon, Valet, of andere PHP-server

---

## 🚀 Lokaal draaien

1. Clone de repository:
```bash
git clone https://github.com/Al7aji/Keuzedeel.git
cd Keuzedeel
```

2. Installeer backend dependencies:
```bash
composer install
```

3. Installeer frontend dependencies:
```bash
npm install
npm run dev
```

4. Configureer de .env:
```bash
cp .env.example .env
php artisan key:generate
```
- Vul databasegegevens in `.env` (`DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`)

5. Voer database migraties uit:
```bash
php artisan migrate
```

6. Start de server:
```bash
php artisan serve
```

7. Open je browser:
```
http://localhost:8000
```

---

## 🏗️ Projectstructuur

```
/app/           ← Backend code (Controllers, Models)
/bootstrap/     ← Laravel bootstrap files
/config/        ← Configuratie instellingen
/database/      ← Migrations en seeders
/public/        ← Publieke frontend bestanden (CSS, JS, images)
/resources/     ← Blade templates, CSS/JS bronbestanden
/routes/        ← Web en API routes
/storage/       ← Logs en tijdelijke bestanden
/tests/         ← Unit en feature tests
.env            ← Configuratie (niet gedeeld)
```

---

## 🔧 Ontwikkeling en backlog

Gebaseerd op de projectbeschrijving en eerste eisen:

**User Stories:**
- Als student wil ik keuzedelen kunnen bekijken en informatie lezen.
- Als student wil ik me kunnen inschrijven voor een keuzedeel.
- Als student wil ik een indicatie zien als een keuzedeel vol of afgerond is.
- Als administratiemedewerker wil ik keuzedelen kunnen beheren zonder te programmeren.
- Als administratiemedewerker wil ik een overzicht van alle inschrijvingen.
- Als beheerder wil ik de inschrijvingsperiode kunnen openen en sluiten.
- Als systeembeheerder wil ik dat alles beveiligd is via login/authenticatie.

> Extra backlog-items kunnen worden toegevoegd zodra ontwerp en eerste versie klaar zijn, in lijn met SCRUM-technieken.

---

## ⚙️ Verwijderen van libraries

**Verwijderen van Breeze (Laravel auth):**
```bash
composer remove laravel/breeze
php artisan vendor:publish --tag=breeze-config --force
```
Verwijder daarna eventueel de routes, views of controllers die Breeze heeft toegevoegd.

**Verwijderen van TailwindCSS:**
```bash
npm uninstall tailwindcss postcss autoprefixer
```
Verwijder ook `tailwind.config.js` en verwijzingen naar Tailwind in `resources/css/app.css` of andere CSS-bestanden.

---

## 📜 Licentie

Er is momenteel geen licentiebestand aanwezig. Voeg bijvoorbeeld een **MIT license** toe als je het project open source wilt maken.

