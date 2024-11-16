<?php

use App\Http\Controllers\Analytics;
use Illuminate\Support\Facades\Route;

Route::view('/', 'todosHome');

Route::view('/report', 'analytics-page');
Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__ . '/auth.php';
