<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Import\ImportReportController;
use App\Http\Controllers\ReportController;


//##########################
//  Import Related Report Section
//
//#########
Route::group(['middleware' => ['auth']], function() {


Route::get('report-panel',[ReportController::class,'reportPanel']);
Route::get('import-report-dashboard',[ImportReportController::class,'importReportDashboard']);
Route::get('master_vessel_wise_ligther_report',[ImportReportController::class,'masterVesselWiseLigtherReport']);




});