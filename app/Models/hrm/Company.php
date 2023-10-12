<?php

namespace App\Models\hrm;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Company extends Model
{
    use HasFactory;

    public function _entry_by(){
    	return $this->hasOne(User::class,'id','_user')->select('id','name','email');
    }
}
