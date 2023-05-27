<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MyPageController;
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

Route::get('/dashboard', [MyPageController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

Route::get('/post/create', [PostController::class, 'create'])->name('post.create');
Route::post('/post', [PostController::class, 'store'])->name('post.store');

Route::post('/saveSelectedDate', [PostController::class, 'saveSelectedDate'])->name('post.saveSelectedDate');

Route::get('/post/result', [PostController::class, 'result'])->name('post.result');

Route::get('/post/index', [PostController::class, 'index'])->name('post.index');
Route::post('/post/index', [PostController::class, 'getPostData'])->name('post.process');

Route::get('/post/dataList', [PostController::class, 'dataList'])->name('post.dataList');
Route::post('/post/dataList', [PostController::class, 'showDataList'])->name('post.showDataList');

Route::delete('/post/{date_id}', [PostController::class, 'destroy'])->name('post.destroy');
