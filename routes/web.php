<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Auth\Events\Login;
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
    return view('welcome');
});

Route::get('/home', function () {
    return view('dashboard');
})->name('home');

Route::get('/test', function () {
    return view('test');
});

Route::get('/login', function () {
    return view('login');
});

Route::get('/register', function () {
    // dd('register');
    return view('register');
});

Route::get('logout', [LoginController::class, 'logout'])->name('logout');


Route::get('payment/form/{user}', [RegisterController::class,'showPaymentForm'])->name('payment.form');
Route::post('payment/process', [RegisterController::class,'processPayment'])->name('payment.process');
Route::get('/payment/success', [RegisterController::class,'paymentSuccess'])->name('payment.success')->middleware('guest');
Route::get('charge1/{user}', [RegisterController::class,'showPaymentCharge'])->name('payment.charge');

// Route::post('charge-post/{user}', [RegisterController::class,'paymentProcess'])->name('charge');
Route::post('charge/{user}', [RegisterController::class,'paymentProcess'])->name('charge')->middleware('guest');
Route::post('/login',[LoginController::class, 'login'])->name('login');
Route::post('/register',[RegisterController::class, 'register']);