<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Cms\AnalyticsController;
use App\Http\Controllers\Cms\DashboardController;
use App\Http\Controllers\Cms\PageController;
use App\Http\Controllers\Cms\NewsController as CmsNewsController;
use App\Http\Controllers\Cms\ProfileController;
use App\Http\Controllers\Cms\SettingController;
use App\Http\Controllers\Cms\ToolController;
use App\Http\Controllers\Cms\UserController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\NewsController;
use Illuminate\Support\Facades\Route;

Route::get('/', [FrontendController::class, 'index'])->name('home');
Route::get('/berita', [NewsController::class, 'index'])->name('news.index');
Route::get('/berita/{article}', [NewsController::class, 'show'])->name('news.show');
Route::post('/analytics/section', [FrontendController::class, 'section'])->middleware('throttle:120,1')->name('analytics.section');

Route::middleware('guest')->group(function () {
    Route::get('/cms/login', [AuthController::class, 'showLogin'])->name('cms.login');
    Route::post('/cms/login', [AuthController::class, 'login'])->name('cms.login.submit');
});

Route::prefix('cms')->name('cms.')->middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/', DashboardController::class)->name('dashboard');
    Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics');
    Route::get('/profile/password', [ProfileController::class, 'edit'])->name('profile.password.edit');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
    Route::get('/pages', [PageController::class, 'index'])->name('pages.index');
    Route::get('/pages/create', [PageController::class, 'create'])->name('pages.create');
    Route::post('/pages', [PageController::class, 'store'])->name('pages.store');
    Route::get('/pages/{page}/edit', [PageController::class, 'edit'])->name('pages.edit');
    Route::put('/pages/{page}', [PageController::class, 'update'])->name('pages.update');
    Route::resource('news', CmsNewsController::class)->parameters(['news' => 'article'])->except('show');
    Route::get('/settings', [SettingController::class, 'edit'])->name('settings.edit');
    Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');

    Route::middleware('role:superadmin')->group(function () {
        Route::resource('users', UserController::class)->except('show');
    });

    Route::middleware('role:superadmin,developer')->group(function () {
        Route::get('/developer-tools', [ToolController::class, 'index'])->name('tools.index');
        Route::post('/developer-tools', [ToolController::class, 'run'])->name('tools.run');
    });
});
