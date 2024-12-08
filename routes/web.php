<?php

use App\Http\Controllers\NewsController;
use App\Http\Controllers\PhotosController;
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


Route::get('/outerSpace', function () {
    return view('outerSpace.outerSpace');
})->name('outerspace');

Route::get('/register', function () {
    if (Auth::check()) {
        return redirect('/outerSpace');
    } else {
        return view('auth.register');
    }
});

Route::group(['prefix' => 'gallery', 'as' => 'gallery.'], function () {

    Route::get('/', [PhotosController::class, 'show']);

    Route::get('watch/{date}', [PhotosController::class, 'watch'])->name('watch');

   // Route::get('showSavedPictures', [PhotosController::class, 'showSavedPictures'])->name('showSavedPictures')->middleware(['auth']);

    Route::get('like', [PhotosController::class, 'like'])->name('like')->middleware(['auth']);
});

Route::group(['prefix' => 'news', 'as' => 'news.'], function () {

    Route::get('/', [NewsController::class, 'show']);

    Route::get('like', [NewsController::class, 'like'])->name('like')->middleware(['auth']);

    //Route::get('showSavedNews', [NewsController::class, 'showSavedNews'])->name('showSavedNews')->middleware(['auth']);

});

Route::get('/dashboard', function () {
    return redirect('/outerSpace');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
