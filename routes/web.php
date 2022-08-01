<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TrainController;
use App\Http\Controllers\PublicAreaController;
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



Route::get('/', [PublicAreaController::class, 'viewTrains'])->name('view_trains');
Route::match(['get','post'],'/get_trains', [TrainController::class, 'getTrains'])->name('trains.get');
Auth::routes(['register' => false]);

Route::group(['middleware' => ['role:admin']], function () {
    // Common Features
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/settings', [SettingsController::class, 'viewSettings'])->name('settings.index');
    Route::post('/settings', [SettingsController::class, 'StoreSettings'])->name('settings.store');
    Route::get('/new_user', [UserController::class, 'new_user'])->name('new_user');
    Route::post('/search_users', [UserController::class, 'new_user'])->name('search_users');
    Route::post('/saveUserStatus', [UserController::class, 'saveUserStatus'])->name('saveUserStatus');
    Route::post('/new_user_save', [UserController::class, 'new_user_save'])->name('new_user_save');
    Route::post('/reset_password', [UserController::class, 'reset_password'])->name('reset_password');

    // Train Master Routes
    Route::get('/trains', [TrainController::class, 'trains'])->name('trains');
    Route::post('/add_train', [TrainController::class, 'addTrain'])->name('trains.add');
    Route::get('/get_train/{id}', [TrainController::class, 'getTrain'])->name('trains.getTrain');
    Route::post('/update_train', [TrainController::class, 'updateTrain'])->name('trains.updateTrain');
    Route::get('/changePassword', [UserController::class, 'changePassword'])->name('changePassword');
    Route::post('/changePasswordSave', [UserController::class, 'changePasswordSave'])->name('changePasswordSave');
});
