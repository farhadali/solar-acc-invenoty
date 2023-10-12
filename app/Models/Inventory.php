<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;


    public function _category(){
    	return $this->hasOne(ItemCategory::class,'id','_category_id')->select('id','_name');
    }

    public function _units(){
    	return $this->hasOne(Units::class,'id','_unit_id')->select('id','_name','_code');
    }

    public function _warranty_name(){
    	return $this->hasOne(Warranty::class,'id','_warranty')->select('id','_name');
    }

    public function unit_conversion(){
        return $this->hasMany(UnitConversion::class,'_item_id','id')->where('_status',1);
    }
}
