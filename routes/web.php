<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RolePermissionsController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\InvoicepositionController;
use App\Http\Controllers\OfferpositionController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\PdfCreateController;
use App\Http\Controllers\PermissionsController;
use App\Http\Controllers\ClientsController;
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
    return view('welcome');
});


Route::middleware(['auth','verified'])->group(function(){

    /*Dashboard*/
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    /*Kunden*/
    Route::resource('customer',CustomerController::class);

    /*Angebot*/
    Route::post('/offer/updatetaxrate', [OfferController::class, 'updatetaxrate'])->name('offer.updatetaxrate');
    Route::post('/offer/updateofferdate', [OfferController::class, 'updateofferdate'])->name('offer.updateofferdate');
    Route::post('/offer/updatedescription', [OfferController::class, 'updatedescription'])->name('offer.updatedescription');
    Route::post('/offer/updatecomment', [OfferController::class, 'updatecomment'])->name('offer.updatecomment');
    Route::post('/offer/updatenumber', [OfferController::class, 'updatenumber'])->name('offer.updatenumber');
    Route::get('/offer/create/{id}',[OfferController::class, 'create'])->name('offer.create');
    Route::resource('offer',OfferController::class);

    /*Angebots-Positionen*/
    Route::resource('offerposition',OfferpositionController::class);

    /*Rechnungen*/
    Route::post('/invoice/updatetaxrate', [InvoiceController::class, 'updatetaxrate'])->name('invoice.updatetaxrate');
    Route::post('/invoice/updateinvoicedate', [InvoiceController::class, 'updateinvoicedate'])->name('invoice.updateinvoicedate');
    Route::post('/invoice/updatenumber', [InvoiceController::class, 'updatenumber'])->name('invoice.updatenumber');
    Route::post('/invoice/updatecondition', [InvoiceController::class, 'updatecondition'])->name('invoice.updatecondition');
    Route::post('/invoice/updatedescription', [InvoiceController::class, 'updatedescription'])->name('invoice.updatedescription');
    Route::post('/invoice/updatecomment', [InvoiceController::class, 'updatecomment'])->name('invoice.updatecomment');
    Route::post('/invoice/updatedeposit', [InvoiceController::class, 'updatedeposit'])->name('invoice.updatedeposit');
    Route::get('/invoice/create/{id}',[InvoiceController::class, 'create'])->name('invoice.create');
    Route::resource('invoice',InvoiceController::class);

    /*Rechnungs-Positionen*/
    Route::resource('invoiceposition', InvoicepositionController::class);

    /*Sales-Analyse*/
    Route::resource('sales',SalesController::class);

    /*Benutzer*/
    Route::resource('users', UsersController::class);

    /*Rolen*/
    Route::resource('roles', RolesController::class);

    /* Rechte */
    Route::resource('permissions', PermissionsController::class);

    /*Rechte*/
    Route::resource('rolepermissions', RolePermissionsController::class);
    Route::post('/rolepermissions/update/{role}', [RolePermissionsController::class, 'update'])->name('rolepermissions.update');

    /*Klienten*/
    Route::resource('clients', ClientsController::class);

    /*Middleware*/
    Route::middleware('auth')->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

    Route::get('/createOfferPdf', [PdfCreateController::class,'createOfferPdf'])->name('createoffer.pdf');
    Route::get('/createInvoicePdf', [PdfCreateController::class,'createInvoicePdf'])->name('createinvoice.pdf');


});

require __DIR__.'/auth.php';
