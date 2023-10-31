<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RLP\RlpController;
use App\Http\Controllers\RLP\RlpChainController;

//##########################
//  RLP Section Start
//
//#########


Route::resource('rlp',RlpController::class);
Route::get('rlp-chain-wise-detail',[RlpController::class,'chainWiseDetail']);
Route::get('rlp-reset',[RlpController::class,'reset']);
Route::post('rlp-approve-reject',[RlpController::class,'rlpApproveReject']);
Route::resource('rlp-chain',RlpChainController::class);
