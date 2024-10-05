<?php

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

Route::get('/', function () {
    return view('auth/login');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/Categoria', function () {
        return view('Categoria/index');
    })->name('categoria');

    Route::get('/Laboratorio', function () {
        return view('Laboratorio/index');
    })->name('laboratorio');

    Route::get('/Marca', function () {
        return view('marca/index');
    })->name('marca');

    Route::get('/Encargado', function () {
        return view('encargado/index');
    })->name('encargado');

    Route::get('/Material', function () {
        return view('material/index');
    })->name('material');

});
