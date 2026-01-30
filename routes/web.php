<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

use Illuminate\Http\Request;
Route::get('/', function (Request $request) {
    return  Inertia::render('auth/Login', [
                    'canResetPassword' => Features::enabled(Features::resetPasswords()),
                    'canRegister' => Features::enabled(Features::registration()),
                    'status' => $request->session()->get('status'),
            ]);
})->middleware('guest')->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/settings.php';
