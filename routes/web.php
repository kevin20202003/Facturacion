<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('welcome');
});

// Authentication
Route::middleware('guest')->group(function () {
    Route::get('login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
    Route::get('register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('register', [AuthController::class, 'register']);
});

Route::post('logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::middleware('auth')->group(function () {
    Route::resource('clients', ClientController::class)->only(['index','create','store']);
    Route::resource('products', ProductController::class)->only(['index','create','store']);
    Route::resource('invoices', InvoiceController::class)->only(['index','create','store','show']);
    Route::get('invoices/{invoice}/pdf', [InvoiceController::class, 'pdf'])->name('invoices.pdf');
    Route::get('reports/sales', [\App\Http\Controllers\ReportsController::class, 'sales'])->name('reports.sales')->middleware(\App\Http\Middleware\RoleMiddleware::class . ':admin');
    Route::get('audits', [\App\Http\Controllers\AuditController::class, 'index'])->name('audits.index')->middleware(\App\Http\Middleware\RoleMiddleware::class . ':admin');
    Route::get('invoices/{invoice}/pay', [InvoiceController::class, 'pay'])->name('invoices.pay');
    Route::post('invoices/{invoice}/checkout', [InvoiceController::class, 'checkout'])->name('invoices.checkout');
    Route::get('invoices/{invoice}/success', [InvoiceController::class, 'paymentSuccess'])->name('invoices.success');
});

// Stripe webhook endpoint (no auth)
Route::post('stripe/webhook', [\App\Http\Controllers\StripeWebhookController::class, 'handle']);

// Load API routes under /api prefix
Route::prefix('api')->group(function () {
    require __DIR__ . '/api.php';
});
