<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CountryController;
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
    return view('welcome');
});

//Country
Route::get('/countries-list',[CountryController::class,'index'])->name('countries.list');
Route::post('country-create',[CountryController::class,'store'])->name('country.create');
Route::get('getcountries-list',[CountryController::class,'getCountry'])->name('getcountries.list');
Route::post('getcountries-detail',[CountryController::class,'getCountryDetail'])->name('get.country.detail');
Route::post('updatecountries-detail',[CountryController::class,'updateCountryDetail'])->name('update.country.detail');
Route::post('deletecountries-detail',[CountryController::class,'deleteCountry'])->name('delete.country.detail');