<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserNotificationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $sliders = \App\Models\Slider::active()->ordered()->get();
    return view('welcome', compact('sliders'));
});

Route::get('/contact', [ContactController::class, 'create'])->name('contact.create');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

Route::middleware('auth')->group(function () {
    Route::get('/my-notifications', [UserNotificationController::class, 'index'])->name('notifications.inbox');
    Route::post('/my-notifications/{id}/read', [UserNotificationController::class, 'markAsRead'])->name('notifications.markRead');
    Route::post('/my-notifications/read-all', [UserNotificationController::class, 'markAllAsRead'])->name('notifications.markAllRead');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/web/dashboard.php';
require __DIR__.'/web/auth.php';
