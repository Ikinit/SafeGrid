<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ContactApiController;
use App\Http\Controllers\Api\GoBagApiController;
use App\Http\Controllers\Api\HotlineApiController;
use App\Http\Controllers\Api\AlertApiController;
use App\Http\Controllers\Api\FamilyProfileApiController;
use App\Http\Controllers\Api\ProfileApiController;
use App\Http\Controllers\Api\DashboardApiController;

/*
|--------------------------------------------------------------------------
| API Routes — SafeGrid
|--------------------------------------------------------------------------
| All routes return JSON. Protected by Sanctum session-based auth
| (same cookie/session as web; no separate token needed for same-origin AJAX).
*/
// Dashboard & Feed
Route::get('/dashboard/stats', [DashboardApiController::class, 'index']);

Route::middleware(['web', 'auth'])->group(function () {

    // ── User Data
    Route::prefix('user')->group(function () {
        Route::patch('/profile', [ProfileApiController::class, 'update']);
        Route::delete('/profile', [ProfileApiController::class, 'destroy']);
    });

    // Alerts
    Route::get('/alerts', [AlertApiController::class, 'index']);
    Route::post('/alerts', [AlertApiController::class, 'store']);
    Route::delete('/alerts/{alert}', [AlertApiController::class, 'destroy']);

    // Family & Household Logic
    Route::prefix('family')->group(function () {

        Route::get('/details', [FamilyProfileApiController::class, 'getDetails']);

        Route::post('/switch/{profile}', [FamilyProfileApiController::class, 'switchHousehold']);
        Route::post('/create', [FamilyProfileApiController::class, 'createHousehold']);
        Route::post('/join', [FamilyProfileApiController::class, 'joinHousehold']);
        Route::post('/location', [FamilyProfileApiController::class, 'updateLocation']);
        Route::post('/invite', [FamilyProfileApiController::class, 'inviteMember']);
        Route::delete('/delete', [FamilyProfileApiController::class, 'destroyHousehold']);
        Route::delete('/members/{member}/remove', [FamilyProfileApiController::class, 'removeMember']);
        Route::put('/update', [FamilyProfileApiController::class, 'updateHousehold']);
        
        // Invitations/Requests
        Route::post('/invitation/{invitation}/accept', [FamilyProfileApiController::class, 'acceptInvitation']);
        Route::post('/invitation/{invitation}/approve', [FamilyProfileApiController::class, 'approveJoinRequest']);
        Route::delete('/invitation/{invitation}/cancel', [FamilyProfileApiController::class, 'cancelJoinRequest']);
        Route::delete('/invitation/{invitation}/decline', [FamilyProfileApiController::class, 'declineInvitation']);
    
        // Role Management
        Route::put('/members/role', [App\Http\Controllers\Api\FamilyProfileApiController::class, 'assignRole']);
        Route::delete('/roles/{role}/remove', [App\Http\Controllers\Api\FamilyProfileApiController::class, 'removeRoleTask']);
    });

    // Emergency Contacts & Hotlines
    Route::get('/contacts', [ContactApiController::class, 'index']);
    Route::post('/contacts/personal', [ContactApiController::class, 'storePersonal']);
    Route::post('/contacts/household', [ContactApiController::class, 'storeHousehold']);
    Route::put('/contacts/{contact}', [ContactApiController::class, 'update']);
    Route::delete('/contacts/{contact}', [ContactApiController::class, 'destroy']);
    Route::get('/hotlines', [HotlineApiController::class, 'index']);

    // Go Bag
    Route::prefix('gobag')->group(function () {
        Route::get('', [GoBagApiController::class, 'index']);
        Route::post('/personal', [GoBagApiController::class, 'storePersonal']);
        Route::patch('/personal/{item}', [GoBagApiController::class, 'updatePersonal']);
        Route::delete('/personal/{item}', [GoBagApiController::class, 'destroyPersonal']);
        Route::post('/family', [GoBagApiController::class, 'storeFamily']);
        Route::patch('/family/{item}', [GoBagApiController::class, 'updateFamily']);
        Route::delete('/family/{item}', [GoBagApiController::class, 'destroyFamily']);
    });
});