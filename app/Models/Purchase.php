<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;


     public function _master_branch(){
    	return $this->hasOne(Branch::class,'id','_branch_id')->select('id','_name');
    }

    public function _master_details(){
    	return $this->hasMany(PurchaseDetail::class,'_no','id')->with(['_detail_branch','_detail_cost_center','_store','_items','_trans_unit','_lot_product'])->where('_status',1);
    }

    public function purchase_account(){
    	return $this->hasMany(PurchaseAccount::class,'_no','id')->with(['_ledger','_detail_branch','_detail_cost_center'])->where('_status',1);


    }

    public function _ledger(){
    	return $this->hasOne(AccountLedger::class,'id','_ledger_id')->select('id','_name','_account_group_id','_account_head_id')
        ->with(['account_type','account_group']);
    }




    public function _master_cost_center(){
        return $this->hasOne(CostCenter::class,'id','_cost_center_id')->select('id','_name');
    }
    public function _organization(){
        return $this->hasOne(\App\Models\hrm\Company::class,'id','organization_id')->select('id','_name');
    }

    public function _master_store(){
        return $this->hasOne(StoreHouse::class,'id','_store_id')->select('id','_name');
    }

    public function _import_purchase(){
        return $this->hasOne(ImportPuchase::class,'id','import_invoice_no')->with(['_mother_vessel']);
    }
    public function _lighter_info(){
        return $this->hasOne(VesselInfo::class,'id','_vessel_no');
    }


    public function _route_info(){
        return $this->hasMany(VesselRoute::class,'_purchase_no','id')->where('_status',1);
    }

    public function _vessel_detail(){
        return $this->hasOne(ImportReceiveVesselInfo::class,'_purchase_no','id')
        ->with(['_lighter_info'])
        ->where('_status',1);
    }
}
