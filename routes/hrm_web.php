<?php


Route::get('hrm-employee',[HrmEmployeesController::class,'index'])->name('hrm-employee');

Route::resource('weekworkday',HrmWeekworkdayController::class);
Route::resource('holidays',HrmHolidaysController::class);
