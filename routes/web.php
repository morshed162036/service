<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\server\AdminController;
use App\Http\Controllers\server\CategoryController;
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
    return redirect('login');
});
Route::prefix('/')->group(function () {
      Route::match(['get','post'],'login',[AdminController::class,'login'])->name('login');
        Route::group(['middleware' => ['user']], function () {
            Route::get('logout', [AdminController::class, 'logout'])->name('logout');
            Route::get('dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

            Route::resource('category', CategoryController::class);

         });

    });