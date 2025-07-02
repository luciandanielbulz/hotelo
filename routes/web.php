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
use App\Http\Controllers\ClientSettingsController;
use App\Http\Controllers\LogoController;
use App\Http\Controllers\OutgoingEmailController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BankDataController;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Response;
use App\Http\Controllers\InvoiceUploadController;




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

/*
|==========================================================================
| ÖFFENTLICHE ROUTEN
|==========================================================================
*/
Route::get('/', function () {
    return view('auth.login');
});

/*
|==========================================================================
| GESCHÜTZTE ROUTEN (Auth & Verified Middleware)
|==========================================================================
*/
Route::middleware(['auth','verified'])->group(function(){

    /*
    |--------------------------------------------------------------------------
    | BANKDATEN-MANAGEMENT
    |--------------------------------------------------------------------------
    */
    Route::get('/bankdata/upload', [BankDataController::class, 'showUploadForm'])->name('bankdata.upload.form');

    Route::post('/bankdata/upload', [BankDataController::class, 'uploadJSON'])->name('bankdata.upload');

    /*
    |--------------------------------------------------------------------------
    | DASHBOARD
    |--------------------------------------------------------------------------
    */
    Route::get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard') 
    ->middleware('permission:view_dashboard');

    /*
    |--------------------------------------------------------------------------
    | KUNDENVERWALTUNG
    |--------------------------------------------------------------------------
    */
    Route::resource('customer',CustomerController::class)->middleware('permission:view_customers');

    /*
    |--------------------------------------------------------------------------
    | ANGEBOTSVERWALTUNG
    |--------------------------------------------------------------------------
    | - CRUD-Operationen für Angebote
    | - Positions-Management
    | - Detail-Updates
    */
    Route::post('/offer/updatedetails', [OfferController::class, 'updateOfferDetails'])->name('offer.updatedetails');

    Route::post('/offer/updatedescription', [OfferController::class, 'updatedescription'])->name('offer.updatedescription');
    Route::post('/offer/updatecomment', [OfferController::class, 'updatecomment'])->name('offer.updatecomment');
    Route::get('/offer/create/{id}',[OfferController::class, 'create'])->name('offer.create-with-customer');
    Route::get('/offer/index_archivated',[OfferController::class, 'index_archivated'])->name('offer.index_archivated');
    //Route::get('/livewire/offer/positiontable',[App\Livewire\Offer\Positiontable::class, 'render'])->name('livewire.offer.index');

    Route::resource('offer',OfferController::class);

    /*Angebots-Positionen*/
    Route::resource('offerposition',OfferpositionController::class);

    /*
    |--------------------------------------------------------------------------
    | RECHNUNGSVERWALTUNG
    |--------------------------------------------------------------------------
    | - CRUD-Operationen
    | - PDF-Erstellung
    | - E-Mail-Versand
    | - Positionen
    | - Detail-Updates
    */
    Route::post('/invoice/updatetaxrate', [InvoiceController::class, 'updatetaxrate'])->name('invoice.updatetaxrate');
    Route::post('/invoice/updateinvoicedate', [InvoiceController::class, 'updateinvoicedate'])->name('invoice.updateinvoicedate');
    Route::post('/invoice/updatenumber', [InvoiceController::class, 'updatenumber'])->name('invoice.updatenumber');
    Route::post('/invoice/updatecondition', [InvoiceController::class, 'updatecondition'])->name('invoice.updatecondition');
    Route::post('/invoice/updatedescription', [InvoiceController::class, 'updatedescription'])->name('invoice.updatedescription');
    Route::post('/invoice/updatecomment', [InvoiceController::class, 'updatecomment'])->name('invoice.updatecomment');
    Route::post('/invoice/updatedeposit', [InvoiceController::class, 'updatedeposit'])->name('invoice.updatedeposit');
    Route::get('/invoice/create/{id}',[InvoiceController::class, 'create'])->name('invoice.create-with-customer');
    Route::get('/invoice/index_archivated',[InvoiceController::class, 'index_archivated'])->name('invoice.index_archivated');
    Route::get('/invoice/copy/{id}',[InvoiceController::class, 'copy'])->name('invoice.copy');
    Route::get('/invoice/createinvoicefromoffer',[InvoiceController::class, 'createinvoicefromoffer'])->name('invoice.createinvoicefromoffer');
    Route::post('/invoice/sendmail',[InvoiceController::class, 'sendmail'])->name('invoice.sendmail');
    Route::post('/offer/sendmail',[OfferController::class, 'sendmail'])->name('offer.sendmail');
    Route::post('/send-invoice/email', [PdfCreateController::class, 'sendInvoiceByEmail'])->name('sendinvoice.email');
Route::post('/send-offer/email', [PdfCreateController::class, 'sendOfferByEmail'])->name('sendoffer.email');
    Route::get('/emaillist', [OutgoingEmailController::class, 'index'])->name('outgoingemails.index');

    Route::resource('invoice',InvoiceController::class);

    /*Rechnungs-Positionen*/
    Route::resource('invoiceposition', InvoicepositionController::class);

    /*
    |--------------------------------------------------------------------------
    | VERKAUFSANALYSE
    |--------------------------------------------------------------------------
    */
    Route::resource('sales',SalesController::class);

    Route::resource('condition',ConditionController::class);
    Route::get('condition-trashed', [ConditionController::class, 'trashed'])->name('condition.trashed');
    Route::patch('condition/{id}/restore', [ConditionController::class, 'restore'])->name('condition.restore');
    Route::delete('condition/{id}/force-delete', [ConditionController::class, 'forceDelete'])->name('condition.force-delete');

    /*
    |--------------------------------------------------------------------------
    | BENUTZERVERWALTUNG & BERECHTIGUNGEN
    |--------------------------------------------------------------------------
    | - Benutzer-CRUD
    | - Rollen
    | - Berechtigungen
    | - Passwort-Reset
    */
    Route::resource('users', UsersController::class);

    Route::resource('logos', LogoController::class);

    /*Rolen*/
    Route::resource('roles', RoleController::class);

    /* Rechte */
    Route::resource('permissions', PermissionController::class);

    /*Rechte*/
    Route::resource('rolepermissions', RolePermissionController::class);
    Route::post('/rolepermissions/update/{role}', [RolePermissionController::class, 'update'])->name('rolepermissions.update-role');

    /*Klienten*/
    Route::resource('clients', ClientsController::class);
    
    /*Client Versionshistorie*/
    Route::get('/clients/{client}/versions', [ClientsController::class, 'showVersionHistory'])->name('clients.versions')
        ->middleware('permission:view_client_versions');
    Route::get('/clients/{client}/versions/{version}', [ClientsController::class, 'showVersion'])->name('clients.show-version')
        ->middleware('permission:view_client_versions');
    Route::delete('/clients/{client}/versions/{version}', [ClientsController::class, 'deleteVersion'])->name('clients.delete-version')
        ->middleware('permission:view_client_versions');
    
    /*Persönliche Klienten-Einstellungen*/
    Route::get('/my-client-settings', [ClientsController::class, 'myClientSettings'])->name('clients.my-settings')
        ->middleware('permission:edit_my_client_settings');
    Route::put('/my-client-settings', [ClientsController::class, 'updateMyClientSettings'])->name('clients.update-my-settings')
        ->middleware('permission:edit_my_client_settings');

    /*Statische Client-Einstellungen (Nummerierung, Präfixe, etc.)*/
    Route::get('/client-settings', [ClientSettingsController::class, 'edit'])->name('client-settings.edit')
        ->middleware('permission:edit_client_settings');
    Route::put('/client-settings', [ClientSettingsController::class, 'update'])->name('client-settings.update')
        ->middleware('permission:edit_client_settings');
    Route::get('/client-settings/preview', [ClientSettingsController::class, 'previewNumbers'])->name('client-settings.preview');
    
    /*Admin: Alle Client-Einstellungen verwalten*/
    Route::get('/admin/client-settings', [ClientSettingsController::class, 'index'])->name('client-settings.index')
        ->middleware('permission:manage_all_clients');
    Route::get('/admin/client-settings/{id}', [ClientSettingsController::class, 'show'])->name('client-settings.show')
        ->middleware('permission:manage_all_clients');

    /*
    |--------------------------------------------------------------------------
    | DOKUMENTEN-MANAGEMENT
    |--------------------------------------------------------------------------
    | - Logo-Verwaltung
    | - PDF-Downloads
    | - Rechnungs-Upload
    */
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

    Route::get('/download/{filename}', function ($filename) {
        $path = storage_path('app/objects/' . $filename);

        if (!File::exists($path)) {
            abort(404);
        }

        // Optional: setze Header für den Download oder Anzeige
        return response()->file($path, [
            'Content-Type' => 'application/pdf',
            // 'Content-Disposition' => 'attachment; filename="Rechnung.pdf"', // für direkten Download
        ]);
    })->name('download.file');

    Route::get('/invoiceupload/upload', [InvoiceUploadController::class, 'create'])->name('invoiceupload.upload.create');
    Route::post('/invoiceupload/upload', [InvoiceUploadController::class, 'store'])->name('invoiceupload.upload.store');
    //Route::resource('invoiceupload', InvoiceUploadController::class);
    Route::get ('/invoiceupload/index', [InvoiceUploadController::class, 'index'])->name('invoiceupload.index');
    Route::get ('/invoiceupload/{id}/edit', [InvoiceUploadController::class, 'edit'])->name('invoiceupload.edit');
    Route::put ('/invoiceupload/{id}', [InvoiceUploadController::class, 'update'])->name('invoiceupload.update');
    Route::get ('/invoiceupload/{id}/show_invoice', [InvoiceUploadController::class, 'show_invoice'])->name('invoiceupload.show_invoice');
    Route::get('/invoiceupload/{id}', [InvoiceUploadController::class, 'show'])->name('invoiceupload.show');
    Route::get('/invoiceuploads/filter/{month}', [InvoiceUploadController::class, 'filterByMonth'])->name('invoiceupload.filterByMonth');

    Route::get('/invoiceuploads/download-zip/{month}', [InvoiceUploadController::class, 'downloadZipForMonth'])
        ->name('invoiceupload.downloadZipForMonth');

    //Route::get('/invoiceuploads/download-zip/{month}', [InvoiceUploadController::class, 'testCreateZip'])
    //    ->name('invoiceupload.downloadZipForMonth');

    Route::get('/invoiceupload/filter-by-month/{month}', [InvoiceUploadController::class, 'filterByMonth'])
        ->name('invoiceupload.filter-by-month');
});

/*
|==========================================================================
| AUTH ROUTEN
|==========================================================================
*/
require __DIR__.'/auth.php';
