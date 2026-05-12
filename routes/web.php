<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FamilyProfileController;
use App\Http\Controllers\GoBagController;
use App\Http\Controllers\EmergencyContactController;
use App\Http\Controllers\AlertController;

// Public / Guest
Route::get('/', [DashboardController::class, 'guest'])->middleware('guest')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    
    // View Routes (Initial Page Loads)
    Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('check.household')->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::get('/onboarding', [FamilyProfileController::class, 'onboarding'])->name('onboarding.index');
    
    // 🟢 MOVED: Go Bag is now accessible to all authenticated users, even without a household!
    Route::get('/go-bag', [GoBagController::class, 'index'])->name('gobag.index');
    
    // Restricted: Only users with an active household can access these
    Route::middleware('check.household')->group(function () {
        Route::get('/family', [FamilyProfileController::class, 'index'])->name('family.index');
        Route::get('/contacts', [EmergencyContactController::class, 'index'])->name('contacts.index');
    });

    // Alerts (Views)
    Route::get('/alerts', [AlertController::class, 'index'])->name('alerts.index');
    Route::get('/alerts/create', [AlertController::class, 'create'])->name('alerts.create');
    Route::get('/alerts/{alert}/edit', [AlertController::class, 'edit'])->name('alerts.edit');
});

require __DIR__.'/auth.php';