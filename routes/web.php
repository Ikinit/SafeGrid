<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FamilyProfileController;
use Illuminate\Support\Facades\Route;

// Guest
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Dashboard
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'check.household'])->name('dashboard');

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
});

// Family Profile
Route::middleware(['auth', 'check.household'])->group(function () {
    Route::get('/family', [FamilyProfileController::class, 'index'])->name('family.index');
    Route::put('/family/update', [FamilyProfileController::class, 'updateHousehold'])->name('family.update');
    Route::delete('/family/delete', [FamilyProfileController::class, 'deleteHousehold'])->name('family.delete');
    Route::post('/family/switch/{profile}', [FamilyProfileController::class, 'switchHousehold'])->name('family.switch');
    Route::post('/family/invite', [FamilyProfileController::class, 'inviteMember'])->name('family.invite');
    Route::post('/family/invitation/{invitation}/accept', [FamilyProfileController::class, 'acceptInvitation'])->name('family.invitation.accept');
    Route::post('/family/invitation/{invitation}/decline', [FamilyProfileController::class, 'declineInvitation'])->name('family.invitation.decline');
    Route::put('/family/member/{member}/role', [FamilyProfileController::class, 'updateRole'])->name('family.member.role');
    Route::delete('/family/member/{member}', [FamilyProfileController::class, 'removeMember'])->name('family.member.remove');
    Route::post('/family/location', [FamilyProfileController::class, 'updateLocation'])->name('family.location');
    Route::put('/family/members/role', [FamilyProfileController::class, 'assignRole'])->name('family.members.role');
    Route::delete('/family/members/{id}', [FamilyProfileController::class, 'removeMember'])->name('family.members.remove');
    Route::delete('/family/roles/{roleId}/remove', [FamilyProfileController::class, 'removeRoleTask'])->name('family.roles.remove');
});

require __DIR__.'/auth.php';