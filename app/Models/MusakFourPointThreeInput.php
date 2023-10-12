<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MusakFourPointThreeInput extends Model
{
    use HasFactory;

    public function _input_item(){
    	return $this->hasOne(Inventory::class,'id','_item_id')->select('id','_item as _name','_unit_id','_unique_barcode')->with(['_units','unit_conversion']);
    }

    public function unit_conversion(){
        return $this->hasMany(UnitConversion::class,'_item_id','_item_id')->where('_status',1);
    }
}
