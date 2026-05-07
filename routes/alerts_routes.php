<?php

// ─────────────────────────────────────────────────────────────────────────────
// ADD THESE ROUTES to your routes/web.php, inside the auth middleware group.
// ─────────────────────────────────────────────────────────────────────────────

use App\Http\Controllers\AlertController;

// Alerts — visible to all authenticated users (index), admin-only for CUD
Route::get('/alerts',                 [AlertController::class, 'index']  )->name('alerts.index');
Route::get('/alerts/create',          [AlertController::class, 'create'] )->name('alerts.create');
Route::post('/alerts',                [AlertController::class, 'store']  )->name('alerts.store');
Route::get('/alerts/{alert}/edit',    [AlertController::class, 'edit']   )->name('alerts.edit');
Route::put('/alerts/{alert}',         [AlertController::class, 'update'] )->name('alerts.update');
Route::delete('/alerts/{alert}',      [AlertController::class, 'destroy'])->name('alerts.destroy');
