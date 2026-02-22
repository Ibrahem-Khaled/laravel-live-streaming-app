<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ContactMessageController;
use App\Http\Controllers\Admin\ContentController;
use App\Http\Controllers\Admin\DashboardApiController;
use App\Http\Controllers\Admin\EpisodeController;
use App\Http\Controllers\Admin\homeController;
use App\Http\Controllers\Admin\NotificationController as AdminNotificationController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Dashboard Routes (admin panel)
|--------------------------------------------------------------------------
| Prefix: /dashboard, Middleware: auth, verified, admin
*/

Route::get('/home/dashboard', [homeController::class, 'index'])
    ->middleware(['auth', 'verified', 'admin'])
    ->name('dashboard');

Route::middleware(['auth', 'verified', 'admin'])->prefix('dashboard')->name('dashboard.')->group(function () {
    // Users
    Route::resource('users', UserController::class);
    Route::patch('users/{user}/status', [UserController::class, 'status'])->name('users.status');

    // Categories
    Route::resource('categories', CategoryController::class);
    Route::patch('categories/{category}/status', [CategoryController::class, 'toggleStatus'])->name('categories.status');
    Route::post('categories/reorder', [CategoryController::class, 'reorder'])->name('categories.reorder');
    Route::post('categories/bulk-action', [CategoryController::class, 'bulkAction'])->name('categories.bulk');

    // Contents
    Route::resource('contents', ContentController::class);
    Route::get('contents/{content}/preview', [ContentController::class, 'preview'])->name('contents.preview');
    Route::patch('contents/{content}/status', [ContentController::class, 'toggleStatus'])->name('contents.status');
    Route::patch('contents/{content}/feature', [ContentController::class, 'toggleFeature'])->name('contents.feature');
    Route::post('contents/reorder', [ContentController::class, 'reorder'])->name('contents.reorder');
    Route::post('contents/bulk-action', [ContentController::class, 'bulkAction'])->name('contents.bulk');

    // Episodes (standalone + nested under content)
    Route::get('episodes', [EpisodeController::class, 'index'])->name('episodes.index');
    Route::get('episodes/create', [EpisodeController::class, 'create'])->name('episodes.create');
    Route::post('episodes', [EpisodeController::class, 'store'])->name('episodes.store');
    Route::get('episodes/{episode}/edit', [EpisodeController::class, 'edit'])->name('episodes.edit');
    Route::put('episodes/{episode}', [EpisodeController::class, 'update'])->name('episodes.update');
    Route::delete('episodes/{episode}', [EpisodeController::class, 'destroy'])->name('episodes.destroy');
    Route::post('episodes/reorder', [EpisodeController::class, 'reorder'])->name('episodes.reorder');
    Route::get('episodes/{episode}/preview', [EpisodeController::class, 'preview'])->name('episodes.preview');

    // Nested: contents.episodes
    Route::get('contents/{content}/episodes', [EpisodeController::class, 'index'])->name('contents.episodes.index');
    Route::get('contents/{content}/episodes/create', [EpisodeController::class, 'create'])->name('contents.episodes.create');
    Route::post('contents/{content}/episodes', [EpisodeController::class, 'store'])->name('contents.episodes.store');

    // Sliders
    Route::resource('sliders', SliderController::class);
    Route::patch('sliders/{slider}/status', [SliderController::class, 'toggleStatus'])->name('sliders.status');

    // Contact messages (support)
    Route::get('contact_messages', [ContactMessageController::class, 'index'])->name('contact_messages.index');
    Route::get('contact_messages/{contact_message}', [ContactMessageController::class, 'show'])->name('contact_messages.show');
    Route::put('contact_messages/{contact_message}', [ContactMessageController::class, 'update'])->name('contact_messages.update');

    // Notifications (admin)
    Route::get('notifications', [AdminNotificationController::class, 'index'])->name('notifications.index');
    Route::get('notifications/create', [AdminNotificationController::class, 'create'])->name('notifications.create');
    Route::post('notifications', [AdminNotificationController::class, 'store'])->name('notifications.store');
    Route::get('notifications/broadcasts/{broadcast}', [AdminNotificationController::class, 'show'])->name('notifications.broadcasts.show');
    Route::get('notifications/broadcasts/{broadcast}/edit', [AdminNotificationController::class, 'edit'])->name('notifications.broadcasts.edit');
    Route::put('notifications/broadcasts/{broadcast}', [AdminNotificationController::class, 'update'])->name('notifications.broadcasts.update');
    Route::delete('notifications/broadcasts/{broadcast}', [AdminNotificationController::class, 'destroy'])->name('notifications.broadcasts.destroy');

    // Settings
    Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('settings/update', [SettingsController::class, 'update'])->name('settings.update');
    Route::post('settings/clear-cache', [SettingsController::class, 'clearCache'])->name('settings.clear-cache');

    // Dashboard API (AJAX) — للاستخدام في Select2 وغيرها
    Route::get('api/users/search', [DashboardApiController::class, 'usersSearch'])->name('api.users.search');
});
