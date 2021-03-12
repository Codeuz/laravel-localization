<?php

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

// The root url must redirect to the localized homepage
Route::get('/', function () {
    $lang = localization()->browser_language();
    return redirect(localization()->currentRoute($lang));
})->name('home');

// Localized homepage
Route::locales(function() {
    Route::get('/', function () {
        return view('welcome');
    })->name('home');
});
