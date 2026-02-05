<?php

use Illuminate\Support\Facades\Route;
    use App\Http\Controllers\Admin\ZoneController;
use App\Http\Controllers\Admin\AreaController;
use App\Http\Controllers\Admin\SiteController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CustomersController;
use App\Http\Controllers\Admin\BillingController;
use App\Http\Controllers\Admin\BillTransactionController;

Route::get('/', [LoginController::class, 'showLoginForm'])
    ->name('login');

Route::post('/login', [LoginController::class, 'login'])
    ->name('login.submit');

Route::post('/logout', [LoginController::class, 'logout'])
    ->name('logout');

Route::get('/admin/dashboard', [DashboardController::class, 'index'])
    ->name('admin.dashboard');




Route::prefix('admin')->middleware('auth')->group(function () {

   Route::resource('zones', ZoneController::class)
    ->except(['show', 'create']);

    Route::resource('areas', AreaController::class)->except(['show', 'edit', 'update']);
    Route::resource('sites', SiteController::class)->except(['show', 'edit', 'update']);
   Route::resource('customers', CustomersController::class)
        ->except(['create', 'edit', 'show']);
        
Route::get('customers/{customer}/data', [CustomersController::class, 'editData'])
     ->name('customers.edit.data');

       Route::get('/transactions', [BillTransactionController::class, 'index'])
        ->name('transactions.index');
  // Bills tab - shows only bill_created transactions
    Route::get('/bills', [BillTransactionController::class, 'index'])->name('bills');
    
    // Payments tab - shows debit_to_credit and credit_to_debit transactions
    Route::get('/payments', [BillTransactionController::class, 'payments'])->name('payments');
    


});

Route::get('/customers/next-number', [CustomersController::class, 'getNextCustomerNumber'])
    ->name('customers.nextNumber');
Route::get('/areas/by-zone/{zone}', [AreaController::class, 'byZone'])
    ->name('areas.byZone');

Route::get('/customers/{customer}/billing', [BillingController::class, 'customerBills'])
    ->name('customers.billing');

Route::post('/billing', [BillingController::class, 'store'])
    ->name('billing.store');

Route::delete('/billing/{bill}', [BillingController::class, 'destroy'])
    ->name('billing.destroy');

    
    Route::post('/billing/transfer', 
    [BillTransactionController::class, 'transfer']
)->name('billing.transfer');
Route::get('/billing/{bill}/transactions', 
    [BillTransactionController::class, 'byBill']);
