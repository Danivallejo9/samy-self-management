<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\ContactClientController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\WalletController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::get('ContactClients', [ContactClientController::class, 'getClients']);

Route::get('Orders/Detail/{order}', [OrderController::class, 'getOrderDetail']);
Route::get('Orders/{order}', [OrderController::class, 'find']);
Route::get('Orders', [OrderController::class, 'getOrdersByClient']);

Route::get('Invoice', [InvoiceController::class, 'getInvoices']);

Route::get('Wallet/Client', [WalletController::class, 'getClientWallet']);

Route::get('Client/{mobile}', [ClientController::class, 'getClientDataByMobile']);
Route::get('Client/{Client}/{Invoice}', [ClientController::class, 'getInfoCashReceipt']);
