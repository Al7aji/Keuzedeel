<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Student\KeuzedeelController as StudentKeuzedeelController;
use App\Http\Controllers\Student\EnrollmentController as StudentEnrollmentController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\KeuzedeelController as AdminKeuzedeelController;
use App\Http\Controllers\Admin\PeriodController as AdminPeriodController;
use App\Http\Controllers\Admin\EnrollmentController as AdminEnrollmentController;
use App\Http\Controllers\Admin\ProgramController as AdminProgramController;
use App\Http\Controllers\Slber\PresentationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    if (auth()->check()) {
        $user = auth()->user();
        return match ($user->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'slber' => redirect()->route('slber.presentation.index'),
            default => redirect()->route('student.keuzedelen.index'),
        };
    }
    return view('welcome');
})->name('home');

/*
|--------------------------------------------------------------------------
| Authenticated User Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Dashboard redirect based on role
    Route::get('/dashboard', function () {
        $user = auth()->user();
        return match ($user->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'slber' => redirect()->route('slber.presentation.index'),
            default => redirect()->route('student.keuzedelen.index'),
        };
    })->name('dashboard');
});

/*
|--------------------------------------------------------------------------
| Student Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:student', 'student.has_program'])
    ->prefix('student')
    ->name('student.')
    ->group(function () {
        // Keuzedelen
        Route::get('/keuzedelen', [StudentKeuzedeelController::class, 'index'])->name('keuzedelen.index');
        Route::get('/keuzedelen/{keuzedeel}', [StudentKeuzedeelController::class, 'show'])->name('keuzedelen.show');

        // Enrollments
        Route::get('/inschrijvingen', [StudentEnrollmentController::class, 'index'])->name('enrollments.index');
        Route::post('/inschrijven', [StudentEnrollmentController::class, 'store'])->name('enrollments.store');
        Route::delete('/inschrijvingen/{enrollment}', [StudentEnrollmentController::class, 'destroy'])->name('enrollments.destroy');
    });

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        // Dashboard
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

        // Keuzedelen CRUD
        Route::resource('keuzedelen', AdminKeuzedeelController::class)->parameters([
            'keuzedelen' => 'keuzedeel',
        ]);
        Route::post('/keuzedelen/{keuzedeel}/toggle-active', [AdminKeuzedeelController::class, 'toggleActive'])->name('keuzedelen.toggle-active');

        // Periods CRUD
        Route::resource('periods', AdminPeriodController::class);
        Route::post('/periods/{period}/toggle-enrollment', [AdminPeriodController::class, 'toggleEnrollment'])->name('periods.toggle-enrollment');
        Route::get('/periods/{period}/instances', [AdminPeriodController::class, 'manageInstances'])->name('periods.instances');
        Route::post('/periods/{period}/instances', [AdminPeriodController::class, 'addInstance'])->name('periods.add-instance');
        Route::delete('/periods/{period}/instances/{instance}', [AdminPeriodController::class, 'removeInstance'])->name('periods.remove-instance');

        // Enrollments
        Route::get('/enrollments', [AdminEnrollmentController::class, 'index'])->name('enrollments.index');
        Route::get('/enrollments/{enrollment}', [AdminEnrollmentController::class, 'show'])->name('enrollments.show');
        Route::patch('/enrollments/{enrollment}/status', [AdminEnrollmentController::class, 'updateStatus'])->name('enrollments.update-status');
        Route::post('/enrollments/bulk-complete', [AdminEnrollmentController::class, 'bulkComplete'])->name('enrollments.bulk-complete');
        Route::get('/enrollments-export', [AdminEnrollmentController::class, 'export'])->name('enrollments.export');

        // Programs CRUD
        Route::resource('programs', AdminProgramController::class)->except(['show']);
        Route::post('/programs/{program}/toggle-active', [AdminProgramController::class, 'toggleActive'])->name('programs.toggle-active');
    });

/*
|--------------------------------------------------------------------------
| SLB-er Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:slber,admin'])
    ->prefix('slber')
    ->name('slber.')
    ->group(function () {
        Route::get('/presentatie', [PresentationController::class, 'index'])->name('presentation.index');
        Route::get('/presentatie/start', [PresentationController::class, 'present'])->name('presentation.present');
        Route::get('/presentatie/slide/{keuzedeel}', [PresentationController::class, 'slide'])->name('presentation.slide');
    });

require __DIR__.'/auth.php';
