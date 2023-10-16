<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RLP\RlpController;
use App\Http\Controllers\RLP\RlpChainController;

//##########################
//  RLP Section Start
//
//#########


Route::resource('rlp',RlpController::class);
Route::resource('rlp-chain',RlpChainController::class);
