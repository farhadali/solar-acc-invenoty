<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IndividualReplaceOldItem extends Model
{
    use HasFactory;

    public function _items(){
    	return $this->hasOne(Inventory::class,'id','_item_id')->with(['_warranty_name'])->select('id','_item as _name','_unique_barcode','_warranty');
    }


     public function _detail_branch(){
    	return $this->hasOne(Branch::class,'id','_branch_id')->select('id','_name');
    }

    public function _detail_cost_center(){
    	return $this->hasOne(CostCenter::class,'id','_cost_center_id')->select('id','_name');
    }
}
