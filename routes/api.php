<?php

use App\Http\Controllers\Api\UsersController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/', function () {
    return response()->json([
        'success' => true,
    ]);
});

Route::group(['as' => 'users.', 'prefix' => 'users'], function() {

    Route::get('/', [UsersController::class, 'index'])->name('index');
    Route::post('/', [UsersController::class, 'store'])->name('store');
    Route::get('/{id}', [UsersController::class, 'show'])->name('show');
    Route::put('/{id}', [UsersController::class, 'update'])->name('update');
    Route::delete('/{id}', [UsersController::class, 'destroy'])->name('delete');

});
