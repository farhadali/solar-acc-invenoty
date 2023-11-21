<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HrmEmployeesController;
use App\Http\Controllers\HrmWeekworkdayController;
use App\Http\Controllers\HrmHolidaysController;
use App\Http\Controllers\HrmLeavetypesController;
use App\Http\Controllers\HrmPayheadsController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\HrmDepartmentController;
use App\Http\Controllers\HrmGradeController;
use App\Http\Controllers\HrmEmpCategoryController;
use App\Http\Controllers\HrmEmpLocationController;
use App\Http\Controllers\HrmDesignationController;
use App\Http\Controllers\HrmCurrentSalaryStruController;
use App\Http\Controllers\HrmAttendanceController;


//##########################
//  HRM Section Start
//
//#########

Route::group(['middleware' => ['auth']], function() {
Route::resource('hrm-employee',HrmEmployeesController::class);
Route::resource('attandance',HrmAttendanceController::class);

Route::get('employee-search','App\Http\Controllers\HrmEmployeesController@employeeSearch');
Route::get('employee-dataupdate','App\Http\Controllers\HrmEmployeesController@employeeDataUpdate');
Route::resource('hrm-designation',HrmDesignationController::class);

Route::resource('initial-salary-structure',HrmCurrentSalaryStruController::class);

Route::resource('weekworkday',HrmWeekworkdayController::class);

Route::resource('holidays',HrmHolidaysController::class);
Route::resource('leave-type',HrmLeavetypesController::class);
Route::resource('pay-heads',HrmPayheadsController::class);
Route::resource('companies',CompanyController::class);
Route::resource('hrm-department',HrmDepartmentController::class);
Route::resource('hrm-grade',HrmGradeController::class);
Route::resource('hrm-emp-location',HrmEmpLocationController::class);
Route::resource('hrm-emp-category',HrmEmpCategoryController::class);


});