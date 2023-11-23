<?php

namespace App\Models\HRM;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class HrmDepartment extends Model
{
    use HasFactory;


 public function _entry_by(){
    	return $this->hasOne(\App\Models\User::class,'id','_user')->select('id','name','email');
    }
}
