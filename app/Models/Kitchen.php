<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kitchen extends Model
{
    use HasFactory;


    public function _finish_goods(){
    	return $this->hasMany(KitchenFinishGoods::class,'_no','id')->with(['_items']);
    }

     public function _row_goods(){
    	return $this->hasMany(KitchenRowGoods::class,'_no','id')->with(['_items']);
    }
     public function _sales_ref(){
    	return $this->hasOne(ResturantSales::class,'id','_res_sales_id')->with(['_ledger','_master_branch','_master_details']);
    }


}
