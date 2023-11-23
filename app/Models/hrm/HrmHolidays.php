<?php

namespace App\Models\HRM;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\HRM\HrmHolidayDetail;
use App\Models\User;

class HrmHolidays extends Model
{
    use HasFactory;


    public function holiday_details(){
    	return $this->hasMany(HrmHolidayDetail::class,'_holidaysid','id')->orderBy('_date','asc');
    }

    public function _entry_by(){
    	return $this->hasOne(User::class,'id','_user')->select('id','name','email');
    }
}
