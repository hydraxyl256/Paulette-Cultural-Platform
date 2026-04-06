<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TribeController;
use App\Http\Controllers\Api\ComicController;
use App\Http\Controllers\Api\ContentController;
use App\Http\Controllers\Api\BundleController;
use App\Http\Controllers\Api\SyncController;
use App\Http\Controllers\Api\SyncStatusController;
use App\Http\Controllers\Api\ProgressController;
use App\Http\Controllers\Api\LessonPlanController;
use App\Http\Controllers\Admin\SuperAdminController;
use App\Http\Controllers\Admin\ComicCMSController;

// Authentication routes (public)
Route::post('/auth/login', [AuthController::class, 'login'])->name('api.auth.login');
Route::post('/auth/register', [AuthController::class, 'register'])->name('api.auth.register');

// Protected routes (require Sanctum token)
Route::middleware(['auth:sanctum'])->group(function () {
    // Auth
    Route::post('/auth/logout', [AuthController::class, 'logout'])->name('api.auth.logout');
    Route::get('/auth/user', [AuthController::class, 'user'])->name('api.auth.user');

    // Content & Tribes
    Route::get('/tribes', [TribeController::class, 'index'])->name('api.tribes.index');
    Route::get('/tribes/{tribe}', [TribeController::class, 'show'])->name('api.tribes.show');
    Route::get('/tribes/{tribe}/comics', [TribeController::class, 'comics'])->name('api.tribes.comics');

    // Comics
    Route::get('/comics', [ComicController::class, 'index'])->name('api.comics.index');
    Route::get('/comics/{comic}', [ComicController::class, 'show'])->name('api.comics.show');
    Route::get('/comics/{comic}/panels', [ContentController::class, 'panels'])->name('api.comics.panels');
    Route::get('/comics/{comic}/download', [ComicController::class, 'download'])->name('api.comics.download');

    // Content Management
    Route::get('/age-profiles', [ContentController::class, 'ageProfiles'])->name('api.age-profiles.index');
    Route::get('/content/manifest', [ContentController::class, 'manifest'])->name('api.content.manifest');

    // Bundles (offline download)
    Route::get('/bundles/{tribe_id}', [BundleController::class, 'index'])->name('api.bundles.index');
    Route::get('/bundles/{comic}/download', [BundleController::class, 'download'])->name('api.bundles.download');
    Route::post('/bundles/{comic}/verify', [BundleController::class, 'verify'])->name('api.bundles.verify');

    // Progress & Sync
    Route::post('/sync', [SyncController::class, 'sync'])->name('api.sync');
    Route::get('/sync/status', [SyncStatusController::class, 'status'])->name('api.sync.status');
    Route::get('/sync/history', [SyncStatusController::class, 'history'])->name('api.sync.history');
    Route::post('/progress/events', [ProgressController::class, 'recordEvent'])->name('api.progress.record');
    Route::get('/progress/child/{child}', [ProgressController::class, 'childProgress'])->name('api.progress.show');
    Route::get('/child-profiles', [ProgressController::class, 'childProfiles'])->name('api.child-profiles.index');
    Route::post('/child-profiles', [ProgressController::class, 'createChildProfile'])->name('api.child-profiles.create');
    Route::put('/child-profiles/{child}', [ProgressController::class, 'updateChildProfile'])->name('api.child-profiles.update');
    Route::delete('/child-profiles/{child}', [ProgressController::class, 'deleteChildProfile'])->name('api.child-profiles.delete');

    // Teacher Lesson Plans
    Route::middleware('role:teacher')->group(function () {
        Route::get('/lesson-plans', [LessonPlanController::class, 'index'])->name('api.lesson-plans.index');
        Route::post('/lesson-plans', [LessonPlanController::class, 'store'])->name('api.lesson-plans.store');
        Route::put('/lesson-plans/{lessonPlan}', [LessonPlanController::class, 'update'])->name('api.lesson-plans.update');
        Route::delete('/lesson-plans/{lessonPlan}', [LessonPlanController::class, 'destroy'])->name('api.lesson-plans.destroy');
        Route::post('/lesson-plans/{lessonPlan}/complete', [LessonPlanController::class, 'complete'])->name('api.lesson-plans.complete');
    });

    // CMS (org-admin & cms_editor only)
    Route::middleware(['can:content.edit'])->group(function () {
        Route::post('/cms/comics/upload', [ComicCMSController::class, 'upload'])->name('api.cms.comics.upload');
        Route::put('/cms/comics/{comic}/publish', [ComicCMSController::class, 'publish'])->name('api.cms.comics.publish');
    });

    // Super Admin Only
    Route::middleware(['role:super_admin'])->prefix('admin')->group(function () {
        Route::get('/dashboard', [SuperAdminController::class, 'dashboard'])->name('api.admin.dashboard');
        Route::get('/organisations', [SuperAdminController::class, 'organisations'])->name('api.admin.organisations.index');
        Route::post('/organisations', [SuperAdminController::class, 'createOrganisation'])->name('api.admin.organisations.store');
        Route::put('/organisations/{organisation}/modules', [SuperAdminController::class, 'updateOrgModules'])->name('api.admin.organisations.modules');
        Route::put('/age-profiles/{ageProfile}', [SuperAdminController::class, 'updateAgeProfile'])->name('api.admin.age-profiles.update');
        Route::put('/themes/{organisation}', [SuperAdminController::class, 'updateTheme'])->name('api.admin.themes.update');
        Route::post('/users/{user}/impersonate', [SuperAdminController::class, 'impersonate'])->name('api.admin.users.impersonate');
    });
});
