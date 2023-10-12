<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Production extends Model
{
    use HasFactory;


    public function _stock_in(){
    	return $this->hasMany(StockIn::class,'_no','id')->with(['_items','_units','_trans_unit','unit_conversion'])->where('_status',1);
    }


    public function _stock_out(){
    	return $this->hasMany(StockOut::class,'_no','id')->with(['_items','_units','_trans_unit','unit_conversion'])->where('_status',1);
    }

    public function _master_details(){
        return $this->hasMany(StockIn::class,'_no','id')->with(['_items','_units','_trans_unit','unit_conversion','_lot_product'])->where('_status',1);
    }
}


