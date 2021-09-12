<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/', [BlogController::class, 'index']);
Route::get('/home', [BlogController::class, 'index'])->name('home');
Route::get('/article/{article_id}', [BlogController::class, 'article'])->name('blog.article');


Route::prefix('dashboard')->middleware('auth')->group(function () {

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('users', UserController::class);
    Route::resource('articles', ArticleController::class);
    Route::resource('tags', TagController::class);
    Route::resource('categories', CategoryController::class)->except('show');
    Route::get('categories/search', [CategoryController::class, 'search'])->name('categories.search');

});

