<?php

use App\Http\Controllers\PaymentController;
use App\Http\Middleware\VerifyCsrfToken;
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

Route::get('/', [PaymentController::class, 'index'])->name('payment.index');
Route::post('/', [PaymentController::class, 'store'])->name('payment.store');
Route::any('callback/{uuid}', [PaymentController::class, 'verify'])->name('payment.verify')->withoutMiddleware(VerifyCsrfToken::class);
Route::get('/callback', [PaymentController::class, 'callback'])->name('payment.callback');
