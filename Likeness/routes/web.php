<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SearchController;

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


Route::get('/', function () {
    return view('auth/login');
});

Auth::routes();
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


//SEARCH ROUTES
Route::get('/search', [App\Http\Controllers\SearchController::class, 'show'])->name('search');
Route::post('/search', [App\Http\Controllers\SearchController::class, 'index']);


Route::get('/vue/test', [App\Http\Controllers\vueController::class, 'test']); 

//ENTERTAINMENT ROUTES
Route::get('/entertainment/{entertainment_id}', [App\Http\Controllers\EntertainmentController::class, 'show'])->name('showEntertainment');

//USER ROUTES
//Route::get('/user/{user_id}', [App\Http\Controllers\UserController::class, 'show'])->name('showUser');
//Route::post('/user', [App\Http\Controllers\UserController::class, 'create'])->name('createUser');
//Route::patch('/user/{user_id}', [App\Http\Controllers\UserController::class, 'update'])->middleware('AuthOrAdmin')->name('updateUser');
//Route::delete('/user/{user_id}', ['App\Http\Controllers\UserController::class, 'delete'])->middleware('AuthOrAdmin')->name('deleteUser');


//ADMIN ROUTES
//Route::get('/admin/{user_id}', [App\Http\Controllers\AdminController::class, 'show'])->middleware('admin')->name('adminUpdatePrivileges');
//Route::patch('/admin/{user_id}', [App\Http\Controllers\AdminController::class, 'update'])->middleware('admin')->name('adminUpdate');
//Route::delete('/admin/{user_id}', [App\Http\Controllers\AdminController::class, 'delete'])->middleware('admin')->name('adminDelete');


//PROFILE ROUTES 
//Route::get('/profile)
