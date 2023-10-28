<?php

namespace App\Models\RLP;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RlpAcknowledgement extends Model
{
    use HasFactory;
    public function _employee(){
        return $this->hasOne(\App\Models\hrm\HrmEmployees::class,'id','user_id')->with(['_emp_department','_emp_designation']);
    }


    public function _check_group(){
        return $this->hasOne(\App\Models\RLP\RlpUserGroup::class,'id','ack_order');
    }
}
