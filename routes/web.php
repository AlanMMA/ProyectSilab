<?php

use App\Http\Controllers\MaterialReportController;
use App\Http\Controllers\PrestamoReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
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
        return view('ViewUser/Categoria-index');
    })->name('categoria');

    Route::get('/Area', function () {
        return view('ViewUser/Area-index');
    })->name('area');

    Route::get('/Rol', function () {
        return view('ViewUser/Rol-index');
    })->name('rol');

    Route::get('/Localizacion', function () {
        return view('ViewUser/Localizacion-index');
    })->name('localizacion');

    Route::get('/Users', function () {
        return view('ViewUser/Usuario-index');
    })->name('user');

    Route::get('/Solicitantes', function () {
        return view('ViewUser/Solicitante-index');
    })->name('solicitante');

    Route::get('/Laboratorio', function () {
        return view('ViewUser/Laboratorio-index');
    })->name('laboratorio');

    Route::get('/Marca', function () {
        return view('ViewUser/Marca-index');
    })->name('marca');

    Route::get('/Encargado', function () {
        return view('ViewUser/Encargado-index');
    })->name('encargado');

    Route::get('/Material', function () {
        return view('ViewUser/Material-index');
    })->name('material');

    Route::get('/Prestamos', function () {
        return view('ViewUser/Prestamo-index');
    })->name('prestamo');

    Route::get('/Prestamos/Form', function () {
        return view('ViewUser/createPrestamo');
    })->name('prestamosc');

    Route::get('/Prestamos/Update', function () {
        return view('ViewUser/UpPrestamo');
    })->name('upprestamo');

    Route::get('Material/Entradas', function () {
        return view('ViewUser/MaterialUpStock');
    })->name('materialEntradas');

    Route::get('/Prestamos/{id}', function ($id) {
        return view('ViewUser/detallePrestamo-index', compact('id'));
    })->name('prestamos.detalle');

    Route::get('/materiales/pdf', [MaterialReportController::class, 'generatePDF'])->name('materiales.pdf');
    //Route::get('/materiales/xml', [MaterialReportController::class, 'generateXML'])->name('materiales.xml');
    //Route::get('/materiales/excel', [MaterialReportController::class, 'generateExcel'])->name('materiales.excel');

    Route::get('/prestamos/pdf', [PrestamoReportController::class, 'generatePDF'])->name('prestamos.pdf');
});
