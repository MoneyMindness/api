<?php

use App\Http\Controllers\API\BudgetController;
use App\Http\Controllers\API\ItemController;
use App\Http\Controllers\API\TokenController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\WalletController;
use Illuminate\Support\Facades\Route;

Route::post('app-auth-token', [TokenController::class, 'createToken']);

Route::middleware(['auth:sanctum'])->group(function (){

    Route::prefix('user')->group(function (){
        Route::get('', [UserController::class, 'index']);
        Route::get('with-wallets', [UserController::class, 'withWallets']);
    });

    Route::resource('wallet', WalletController::class);
    Route::resource('item', ItemController::class);
    Route::resource('budget', BudgetController::class);

});
