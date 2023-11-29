<?php

namespace App\Models\HRM;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HrmEmployees extends Model
{
    use HasFactory;

    //HrmEmpCategory

    protected $table="hrm_employees";




public function _employee_cat(){
    return $this->hasOne(HrmEmpCategory::class,'id','_category_id')->select('id','_name');
}
public function _emp_department(){
    return $this->hasOne(HrmDepartment::class,'id','_department_id')->select('id','_department as _name');
}
public function _emp_designation(){
    return $this->hasOne(Designation::class,'id','_jobtitle_id')->select('id','_name');
}
public function _emp_grade(){
    return $this->hasOne(HrmGrade::class,'id','_grade_id')->select('id','_grade as _name');
}
public function _emp_location(){
    return $this->hasOne(HrmEmpLocation::class,'id','_location')->select('id','_name');
}

 public function _branch(){
        return $this->hasOne(\App\Models\Branch::class,'id','_branch_id')->select('id','_name');
}
 public function _cost_center(){
        return $this->hasOne(\App\Models\CostCenter::class,'id','_cost_center_id')->select('id','_name');
}
public function _organization(){
        return $this->hasOne(\App\Models\HRM\Company::class,'id','organization_id')->select('id','_name');
}



}
