<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\MejaController;
use App\Http\Controllers\PaymentController;

use App\Http\Controllers\PemesananController;

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

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('/dashboard');




// Tambahan route reservasi
Route::get('/reservasi', function () {
    return view('reservasi');
})->name('reservasi');

Route::get('pemesanan/create', [PemesananController::class, 'create'])->name('pemesanans.create');
Route::post('/pemesanans', [PemesananController::class, 'store'])->name('pemesanans.store');
// web.php
Route::get('/scan-meja', [PemesananController::class, 'handleQr'])->name('pemesanan.qr');


    // Route pembayaran
    Route::get('/payment/{pemesanan_id}/create', [PaymentController::class, 'create'])
         ->name('payment.create');
    Route::get('/payment/finish', [PaymentController::class, 'finish'])
         ->name('payment.finish');
    Route::post('/payment/notification', [PaymentController::class, 'notification'])
         ->name('payment.notification');


Route::middleware('auth')->group(function () {

    Route::resource('produk', ProdukController::class);
    Route::resource('meja', MejaController::class);

    Route::get('pemesanan/table', [PemesananController::class, 'index'])->name('pemesanans.table');
    Route::get('pemesanan/{id}/edit', [PemesananController::class, 'edit'])->name('pemesanans.edit');
    Route::put('pemesanan/{id}', [PemesananController::class, 'update'])->name('pemesanans.update');
    Route::delete('pemesanan/{id}', [PemesananController::class, 'destroy'])->name('pemesanans.destroy');


    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
