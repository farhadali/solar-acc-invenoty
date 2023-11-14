<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Import\ImportReportController;


//##########################
//  Import Related Report Section
//
//#########
Route::group(['middleware' => ['auth']], function() {


Route::get('import-report-dashboard',[ImportReportController::class,'importReportDashboard']);
Route::get('master_vessel_wise_ligther_report',[ImportReportController::class,'masterVesselWiseLigtherReport']);




});