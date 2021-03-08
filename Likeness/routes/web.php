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


//ENTERTAINMENT ROUTES
Route::get('/entertainment/{entertainment_id}', [App\Http\Controllers\EntertainmentController::class, 'show'])->name('showEntertainment');

//USER ROUTES
//Route::get('/user/{user_id}', [App\Http\Controllers\UserController::class, 'show'])->name('showUser');
//Route::post('/user/{user_id}/update', [App\Http\Controllers\UserController::class, 'update'])->middleware('AuthOrAdmin')->name('updateUser');
//Route::post('/user/{user_id}/delete', ['App\Http\Controllers\UserController::class, 'delete'])->middleware('AuthOrAdmin')->name('deleteUser');


//ADMIN ROUTES
//Route::get('/admin/{user_id}/privileges', [App\Http\Controllers\AdminController::class, 'update'])->middleware('admin')->name('adminUpdatePrivileges');
//Route::post('/admin/{user_id}/privileges', [App\Http\Controllers\AdminController::class, '?????'])->middleware('admin')->name('adminUpdatePrivilegesPost');



//PROFILE ROUTES 

