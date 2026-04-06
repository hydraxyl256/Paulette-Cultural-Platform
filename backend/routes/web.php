<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\PageController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ComicCMSController;
use App\Http\Controllers\Admin\OrganisationController;
use App\Http\Controllers\Teacher\KioskController;

/*
|--------------------------------------------------------------------------
| Web Routes (Blade UI, Admin Dashboards, Auth Pages)
|--------------------------------------------------------------------------
*/

// Public routes (guest)
Route::middleware('guest')->group(function () {
    Route::get('/', [PageController::class, 'welcome'])->name('welcome');
    Route::get('/about', [PageController::class, 'about'])->name('about');
    Route::get('/tribes', [PageController::class, 'tribes'])->name('tribes.index');
    Route::get('/tribes/{id}', [PageController::class, 'showTribe'])->name('tribes.show');

    // Auth routes
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
});

// Protected routes (authenticated users)
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Parent dashboard
    Route::get('/dashboard', [PageController::class, 'parentDashboard'])->name('parent.dashboard');
    Route::get('/children', [PageController::class, 'parentChildren'])->name('parent.children');
    Route::get('/children/{id}/progress', [PageController::class, 'childProgress'])->name('parent.child.progress');
    Route::get('/parent/child/{id}/progress', [PageController::class, 'childProgress'])->name('parent.child.progress.alt');

    // Teacher dashboard
    Route::middleware('role:teacher')->group(function () {
        Route::get('/teacher/dashboard', [PageController::class, 'teacherDashboard'])->name('teacher.dashboard');
        Route::get('/teacher/kiosk', [KioskController::class, 'index'])->name('teacher.kiosk');
        Route::get('/teacher/roster', [PageController::class, 'teacherRoster'])->name('teacher.roster');
    });

    // Admin dashboard (super_admin only)
    Route::middleware('role:super_admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        
        // Organisations
        Route::get('/organisations', [OrganisationController::class, 'index'])->name('organisations.index');
        Route::get('/organisations/create', [OrganisationController::class, 'create'])->name('organisations.create');
        Route::post('/organisations', [OrganisationController::class, 'store'])->name('organisations.store');
        Route::get('/organisations/{id}/edit', [OrganisationController::class, 'edit'])->name('organisations.edit');
        Route::put('/organisations/{id}', [OrganisationController::class, 'update'])->name('organisations.update');

        // CMS Management
        Route::get('/cms/comics', [ComicCMSController::class, 'index'])->name('cms.comics.index');
        Route::get('/cms/comics/create', [ComicCMSController::class, 'create'])->name('cms.comics.create');
        Route::post('/cms/comics', [ComicCMSController::class, 'store'])->name('cms.comics.store');
        Route::get('/cms/comics/{id}/edit', [ComicCMSController::class, 'edit'])->name('cms.comics.edit');
        Route::put('/cms/comics/{id}', [ComicCMSController::class, 'update'])->name('cms.comics.update');
        Route::get('/cms/comics/{id}/panels', [ComicCMSController::class, 'managePanels'])->name('cms.comics.panels');
        Route::post('/cms/comics/{id}/publish', [ComicCMSController::class, 'publish'])->name('cms.comics.publish');

        // Age profiles
        Route::get('/age-profiles', [DashboardController::class, 'ageProfiles'])->name('age-profiles.index');
        Route::put('/age-profiles/{id}', [DashboardController::class, 'updateAgeProfile'])->name('age-profiles.update');

        // Themes
        Route::get('/themes', [DashboardController::class, 'themes'])->name('themes.index');
        Route::put('/themes/{id}', [DashboardController::class, 'updateTheme'])->name('themes.update');

        // Audit logs
        Route::get('/audit-logs', [DashboardController::class, 'auditLogs'])->name('audit-logs.index');
    });
});

// Fallback (404)
Route::fallback(function () {
    return view('errors.404');
});
