<?php

use App\Http\Controllers\PagesController;
use App\Http\Controllers\PostsController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;

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



// Маршрут для главной страницы
Route::get('/', [PagesController::class, 'index'])->name('index');

// Маршрут для страницы "About Us"
Route::get('/about', [PagesController::class, 'about'])->name('pages.about');

// Маршруты для работы с постами
Route::get('/posts', [PostsController::class, 'index']);
Route::get('/posts/show/{id}', [PostsController::class, 'show']);
Route::get('/posts/add', [PostsController::class, 'add']);
Route::post('/posts/add', [PostsController::class, 'store']);
Route::get('/posts/edit/{id}', [PostsController::class, 'edit']);
Route::post('/posts/edit/{id}', [PostsController::class, 'update']);
Route::post('/posts/delete/{id}', [PostsController::class, 'destroy']);



// Маршруты для работы с пользователями
Route::get('/users/register', [UsersController::class, 'register'])->name('users.register');
Route::post('/users/register', [UsersController::class, 'register']); // Обновлено
Route::get('/users/login', [UsersController::class, 'login'])->name('users.login');
Route::post('/users/login', [UsersController::class, 'login']); // Обновлено
Route::get('/users/logout', [UsersController::class, 'logout']);
Route::get('/posts', [PostsController::class, 'index'])->name('posts.index');
Route::get('/posts/add', [PostsController::class, 'add'])->name('posts.add');
Route::get('/posts/{id}', 'PostsController@show')->name('posts.show');
Route::resource('posts', PostsController::class);

