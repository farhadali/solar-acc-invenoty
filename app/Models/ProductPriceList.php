<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductPriceList extends Model
{
    use HasFactory;

    public function _items(){
    	return $this->hasOne(Inventory::class,'id','_item_id','_unit_id')->with(['_units','unit_conversion'])->select('id','_item as _name','_pur_rate','_unit_id');
    }


     public function _detail_branch(){
    	return $this->hasOne(Branch::class,'id','_branch_id')->select('id','_name');
    }

    public function _detail_cost_center(){
    	return $this->hasOne(CostCenter::class,'id','_cost_center_id')->select('id','_name');
    }

    public function _store(){
    	return $this->hasOne(StoreHouse::class,'id','_store_id')->select('id','_name');
    }

    

    public function _units(){
        return $this->hasOne(Units::class,'id','_unit_id')->select('id','_name','_code');
    }
    public function _trans_unit(){
        return $this->hasOne(Units::class,'id','_transection_unit')->select('id','_name','_code');
    }
    public function _warranty_name(){
        return $this->hasOne(Warranty::class,'id','_warranty')->select('id','_name');
    }

    public function unit_conversion(){
       return $this->hasMany(UnitConversion::class,'_item_id','_item_id')->where('_status',1);
    }

    public function _lot_wise_sales_details(){
        return $this->hasMany(SalesDetail::class,'_p_p_l_id','id')->with(['_sales_barcodes','_sales_master']);
    }
    public function _lot_wise_sales_return_details(){
        return $this->hasMany(SalesReturnDetail::class,'_p_p_l_id','id')->with(['_sales_return_barcodes','_sales_return_master']);
    }
    public function _purchase_detail(){
        return $this->hasMany(Purchase::class,'id','_master_id')->with(['_master_details']);
    }
    
    //Material Issue

    //Material Issue Return 


    //Damage Adjustment


    

}
