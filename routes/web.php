<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;


//dd();
//dd(request()->path());



Route::get('/', function () {
    if (Auth::check()) {
        return redirect('/outerSpace');
    } else {
        return view('landingPage.landingPage');
    }
});



Route::get('login', function () {
    if (Auth::check()) {
        return redirect('/outerSpace');
    } else {
        return view('auth.login');
    }
});

Route::get('/login', function () {
    if (Auth::check()) {
        return redirect('/outerSpace');
    } else {
        return view('auth.login');
    }
});

Route::get('/register', function () {
    if (Auth::check()) {
        return redirect('/outerSpace');
    } else {
        return view('auth.register');
    }
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
