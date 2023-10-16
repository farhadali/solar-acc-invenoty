<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RLP\RlpController;

//##########################
//  RLP Section Start
//
//#########


Route::resource('rlp',RlpController::class);
