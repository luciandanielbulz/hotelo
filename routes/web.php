<?php

use App\Http\Controllers\ConditionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\InvoicepositionController;
use App\Http\Controllers\OfferpositionController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\PdfCreateController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ClientsController;
use App\Http\Controllers\LogoController;
use App\Http\Controllers\OutgoingEmailController;
use App\Models\OutgoingEmail;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BankDataController;

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
    return view('auth.login');
});

Route::get('/bankdata/upload', [BankDataController::class, 'showUploadForm'])->name('bankdata.upload.form');
Route::post('/bankdata/upload', [BankDataController::class, 'uploadJSON'])->name('bankdata.upload');

Route::middleware(['auth','verified'])->group(function(){

    /*Dashboard*/
    Route::get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard')
    ->middleware('permission:view_dashboard');

    /*Kunden*/
    Route::resource('customer',CustomerController::class)->middleware('permission:view_customers');


    /*Angebot*/
    Route::post('/offer/updatedetails', [OfferController::class, 'updateOfferDetails'])->name('offer.updatedetails');

    Route::post('/offer/updatedescription', [OfferController::class, 'updatedescription'])->name('offer.updatedescription');
    Route::post('/offer/updatecomment', [OfferController::class, 'updatecomment'])->name('offer.updatecomment');
    Route::get('/offer/create/{id}',[OfferController::class, 'create'])->name('offer.create');
    Route::get('/offer/index_archivated',[OfferController::class, 'index_archivated'])->name('offer.index_archivated');
    //Route::get('/livewire/offer/positiontable',[App\Livewire\Offer\Positiontable::class, 'render'])->name('livewire.offer.index');

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
    Route::get('/invoice/index_archivated',[InvoiceController::class, 'index_archivated'])->name('invoice.index_archivated');
    Route::get('/invoice/copy/{id}',[InvoiceController::class, 'copy'])->name('invoice.copy');
    Route::get('/invoice/createinvoicefromoffer',[InvoiceController::class, 'createinvoicefromoffer'])->name('invoice.createinvoicefromoffer');
    Route::post('/invoice/sendmail',[InvoiceController::class, 'sendmail'])->name('invoice.sendmail');
    Route::post('/send-invoice/email', [PdfCreateController::class, 'sendInvoiceByEmail'])->name('sendinvoice.email');
    Route::get('/emaillist', [OutgoingEmailController::class, 'index'])->name('outgoingemails.index');



    Route::resource('invoice',InvoiceController::class);

    /*Rechnungs-Positionen*/
    Route::resource('invoiceposition', InvoicepositionController::class);

    /*Sales-Analyse*/
    Route::resource('sales',SalesController::class);

    Route::resource('condition',ConditionController::class);

    /*Benutzer*/
    Route::resource('users', UsersController::class);

    Route::resource('logos', LogoController::class);

    /*Rolen*/
    Route::resource('roles', RoleController::class);

    /* Rechte */
    Route::resource('permissions', PermissionController::class);

    /*Rechte*/
    Route::resource('rolepermissions', RolePermissionController::class);
    Route::post('/rolepermissions/update/{role}', [RolePermissionController::class, 'update'])->name('rolepermissions.update');

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

    Route::put('/users/{id}/reset-password', [UsersController::class, 'resetUserPassword'])
        ->middleware('permission:reset_user_password')
        ->name('users.reset-password');


    Route::get('/users/{id}/reset-password', function ($id) {
        // Finde den Benutzer
        $user = App\Models\User::findOrFail($id);

        // Prüfe Berechtigung
        if (!Auth::user()->hasPermission('reset_user_password')) {
            abort(403, 'Zugriff verweigert.');
        }

        // Zeige die View
        return view('admin.reset_password', compact('user'));
    })->middleware('auth')->name('users.show-reset-password');


});

require __DIR__.'/auth.php';
