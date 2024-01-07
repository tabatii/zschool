<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

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

Route::get('/', fn () => view('home'))->name('home');
Route::get('offline', fn () => view('laravelpwa::offline'))->name('offline');

if (env('APP_ENV') !== 'production') {
    // view pdf
    Route::get('pdf/exams', fn () => view('pdf.exams'));

    // create storage shortcut
    Route::get('artisan/storage/link', fn () => Artisan::call('storage:link'));

    // migrate database
    Route::get('artisan/migrate', function (Request $request) {
        return Artisan::call('migrate');
    });

    // refresh database
    Route::get('artisan/migrate/fresh', function (Request $request) {
        return Artisan::call('migrate:fresh', [
            '--seed' => $request->query('seed') === 'true' ? true : false
        ]);
    });
}
