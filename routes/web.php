<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FamilyProfileController;
use App\Http\Controllers\GoBagController;
use App\Http\Controllers\EmergencyContactController;
use App\Http\Controllers\AlertController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

// Guest
Route::get('/', [DashboardController::class, 'guest'])->name('home');

// Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified', 'check.household'])
    ->name('dashboard');

// Breeze default profile
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Onboarding — logged in but no household yet
Route::middleware('auth')->group(function () {
    Route::get('/onboarding', [FamilyProfileController::class, 'onboarding'])->name('onboarding.index');
    Route::post('/onboarding/create', [FamilyProfileController::class, 'createHousehold'])->name('onboarding.create');
    Route::post('/onboarding/join', [FamilyProfileController::class, 'joinHousehold'])->name('onboarding.join');

    // Cancel a self-initiated join request — must be auth-only (NOT check.household),
    // because the user cancelling has no active household yet.
    Route::delete('/family/invitation/{invitation}/cancel', [FamilyProfileController::class, 'cancelJoinRequest'])->name('family.invitation.cancel');

    // Accept / Decline invitations also work without an active household
    Route::post('/family/invitation/{invitation}/accept', [FamilyProfileController::class, 'acceptInvitation'])->name('family.invitation.accept');
    Route::post('/family/invitation/{invitation}/decline', [FamilyProfileController::class, 'declineInvitation'])->name('family.invitation.decline');
});

// Family Profile
Route::middleware(['auth', 'check.household'])->group(function () {
    Route::get('/family', [FamilyProfileController::class, 'index'])->name('family.index');
    Route::put('/family/update', [FamilyProfileController::class, 'updateHousehold'])->name('family.update');
    Route::delete('/family/delete', [FamilyProfileController::class, 'deleteHousehold'])->name('family.delete');
    Route::post('/family/switch/{profile}', [FamilyProfileController::class, 'switchHousehold'])->name('family.switch');
    Route::post('/family/invite', [FamilyProfileController::class, 'inviteMember'])->name('family.invite');
    // Revoke an owner-sent invitation (different from cancel — this is the OWNER cancelling their own invite)
    Route::delete('/family/invitation/{invitation}/revoke', [FamilyProfileController::class, 'revokeInvitation'])->name('family.invitation.revoke');
    Route::post('/family/invitation/{invitation}/approve-request', [FamilyProfileController::class, 'approveJoinRequest'])->name('family.invitation.approve-request');
    Route::post('/family/invitation/{invitation}/decline-request', [FamilyProfileController::class, 'declineJoinRequest'])->name('family.invitation.decline-request');
    Route::put('/family/member/{member}/role', [FamilyProfileController::class, 'updateRole'])->name('family.member.role');
    Route::delete('/family/member/{member}', [FamilyProfileController::class, 'removeMember'])->name('family.member.remove');
    Route::post('/family/location', [FamilyProfileController::class, 'updateLocation'])->name('family.location');
    Route::put('/family/members/role', [FamilyProfileController::class, 'assignRole'])->name('family.members.role');
    Route::delete('/family/members/{id}', [FamilyProfileController::class, 'removeMember'])->name('family.members.remove');
    Route::delete('/family/roles/{roleId}/remove', [FamilyProfileController::class, 'removeRoleTask'])->name('family.roles.remove');
    Route::get('/contacts', [EmergencyContactController::class, 'index'])->name('contacts.index');
    Route::post('/contacts/personal', [EmergencyContactController::class, 'storePersonal'])->name('contacts.personal.store');
    Route::post('/contacts/household', [EmergencyContactController::class, 'storeHousehold'])->name('contacts.household.store');
    Route::put('/contacts/{contact}', [EmergencyContactController::class, 'update'])->name('contacts.update');
    Route::delete('/contacts/{contact}', [EmergencyContactController::class, 'destroy'])->name('contacts.destroy');
    Route::get('/contacts/hotlines', [EmergencyContactController::class, 'filterHotlines'])->name('contacts.hotlines');
});

// Go Bag
Route::middleware(['auth'])->group(function () {
    
    Route::get('/go-bag', [GoBagController::class, 'index'])->name('gobag.index');

    Route::post('/gobag/personal', [GoBagController::class, 'storePersonal'])->name('gobag.personal.store');
    Route::patch('/gobag/personal/{item}', [GoBagController::class, 'updatePersonal'])->name('gobag.personal.update');
    Route::delete('/gobag/personal/{item}', [GoBagController::class, 'destroyPersonal'])->name('gobag.personal.destroy');

    Route::middleware(['check.household'])->group(function () {
        Route::post('/gobag/family', [GoBagController::class, 'storeFamily'])->name('gobag.family.store');
        Route::patch('/gobag/family/{item}', [GoBagController::class, 'updateFamily'])->name('gobag.family.update');
        Route::delete('/gobag/family/{item}', [GoBagController::class, 'destroyFamily'])->name('gobag.family.destroy');
    });
});

// Alerts
Route::middleware('auth')->group(function () {
    Route::get('/alerts',              [AlertController::class, 'index']  )->name('alerts.index');
    Route::get('/alerts/create',       [AlertController::class, 'create'] )->name('alerts.create');
    Route::post('/alerts',             [AlertController::class, 'store']  )->name('alerts.store');
    Route::get('/alerts/{alert}/edit', [AlertController::class, 'edit']   )->name('alerts.edit');
    Route::put('/alerts/{alert}',      [AlertController::class, 'update'] )->name('alerts.update');
    Route::delete('/alerts/{alert}',   [AlertController::class, 'destroy'])->name('alerts.destroy');
});

require __DIR__.'/auth.php';
