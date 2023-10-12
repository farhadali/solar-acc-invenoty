<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ERPUser extends Model
{
    use HasFactory;

    protected $table="users_erp";


    public function _designation(){
        return $this->hasOne(\App\Models\HRM\Designation::class,'id','designation');
    }
}
