<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('home');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

Route::resource('post', PostController::class)->only(['create', 'store'])->names([
    'create' => 'post.create',
    'store' => 'post.store',
]);

Route::post('/saveSelectedDate', 'App\Http\Controllers\PostController@saveSelectedDate')->name('post.saveSelectedDate');

Route::get('/post/result', function () {
    return view('post.result');
})->name('post.result');

Route::get('/post/index', [PostController::class, 'index'])->name('post.index');
Route::post('/post/index', [PostController::class, 'getPostData'])->name('post.process');

// Route::post('/getPostData', [PostController::class, 'getPostData']);
