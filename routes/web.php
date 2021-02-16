<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
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

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/provider1', [HomeController::class, 'provider1'])->name('provider1');
Route::get('/provider2', [HomeController::class, 'provider2'])->name('provider2');
Route::get('/getlink', [HomeController::class, 'getlink'])->name('getlink');
Route::post('/sendlink', [HomeController::class, 'redirectprovider'])->name('redirectprovider');
Route::get('/temizle', [HomeController::class, 'temizle'])->name('temizle');
Route::post('/goruntule', [HomeController::class, 'goruntule'])->name('goruntule');

