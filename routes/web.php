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

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes(['register' => false]);
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// MODEL ONE SECTIONS ==========
Route::resource('sections' , 'SectionController'); 

// MODEL TWO PRODUCRS ==========
Route::resource('products' , 'ProductsController');

// -------------------------- Invioces Controller ------------------------------

// MODEL THREE INVOICE LIST ===========================
Route::resource('invoices' , 'InvoicesController');
                    // Get Products With ID Ajax Code
Route::get('/section/{id}', 'InvoicesController@getproducts');
                    // Edit Invoice
Route::get('/edit_invoice/{id}', 'InvoicesController@edit');
                    // View Payment Statuses
Route::get('/Status_show/{id}', 'InvoicesController@show')->name('Status_show');
                    // Update  Payment Statuses
Route::post('/Status_Update/{id}', 'InvoicesController@Status_Update')->name('Status_Update');
                    // Invoice Paid
Route::get('Invoice_Paid','InvoicesController@Invoice_Paid');
                    // Invoice UnPaid
Route::get('Invoice_UnPaid','InvoicesController@Invoice_UnPaid');   
                    // Invoice Partial
Route::get('Invoice_Partial','InvoicesController@Invoice_Partial');
                    // Print_invoice
Route::get('Print_invoice/{id}','InvoicesController@Print_invoice');

// Invoices Details =============================
Route::get('/InvoicesDetails/{id}', 'InvoicesDetailsController@edit');
                    // View File
Route::get('View_file/{invoice_number}/{file_name}', 'InvoicesDetailsController@open_file');
                    // Download File
Route::get('download/{invoice_number}/{file_name}', 'InvoicesDetailsController@get_file');
                    // Delete File
Route::post('delete_file', 'InvoicesDetailsController@destroy')->name('delete_file');

// Invoices Attachments =========================
Route::resource('InvoiceAttachments', 'InvoicesAttachmentsController');

// Invoice Achive ===============================
Route::resource('Archive', 'InvoiceAchiveController');

// -------------------------- Invioces Controller Closed ------------------------------

// -------------------------- Permission Controller ------------------------------

Route::group(['middleware' => ['auth']], function() {
    Route::resource('roles','RoleController');
    Route::resource('users','UserController');
});
// -------------------------- Permission Controller Closed ------------------------------

// -------------------------- Reports Controller  ------------------------------
Route::get('invoices_report', 'Invoices_Report@index');
Route::post('Search_invoices', 'Invoices_Report@Search_invoices');

Route::get('customers_report', 'Customers_Report@index')->name("customers_report");
Route::post('Search_customers', 'Customers_Report@Search_customers');

// -------------------------- Reports Controller Closed  ------------------------------

// -------------------------- Notification Invoices Controller ------------------------------
Route::get('MarkAsRead_all','InvoicesController@MarkAsRead_all')->name('MarkAsRead_all');
Route::get('unreadNotifications_count', 'InvoicesController@unreadNotifications_count')->name('unreadNotifications_count');
Route::get('unreadNotifications', 'InvoicesController@unreadNotifications')->name('unreadNotifications');

// -------------------------- Notification Invoices Controller Closed  ------------------------------


Route::get('/{page}', 'AdminController@index');
