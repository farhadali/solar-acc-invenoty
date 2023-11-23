<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;



class Sales extends Model 
{
     
    protected $guarded = [];

    public function _master_branch(){
    	return $this->hasOne(Branch::class,'id','_branch_id')->select('id','_name');
    }

    public function _master_details(){
    	return $this->hasMany(SalesDetail::class,'_no','id')->with(['_detail_branch','_detail_cost_center','_store','_items','_warrant','_units','_trans_unit','unit_conversion'])->where('_status',1);
    }





    public function s_account(){
    	return $this->hasMany(SalesAccount::class,'_no','id')->with(['_ledger','_detail_branch','_detail_cost_center'])->where('_status',1);


    }

    public function _ledger(){
    	return $this->hasOne(AccountLedger::class,'id','_ledger_id')->select('id','_account_group_id','_account_head_id','_name','_balance','_alious');
    }
    public function _delivery_man(){
        return $this->hasOne(AccountLedger::class,'id','_delivery_man_id')->select('id','_account_group_id','_account_head_id','_name','_balance');
    } 
    public function _sales_man(){
        return $this->hasOne(AccountLedger::class,'id','_sales_man_id')->select('id','_account_group_id','_account_head_id','_name','_balance');
    }

     public function _master_cost_center(){
        return $this->hasOne(CostCenter::class,'id','_cost_center_id')->select('id','_name');
    }
   public function _organization(){
        return $this->hasOne(\App\Models\HRM\Company::class,'id','organization_id');
    }

   

    public function _master_store(){
        return $this->hasOne(StoreHouse::class,'id','_store_id')->select('id','_name');
    }

    public function _terms_con(){
        return $this->hasOne(TransectionTerms::class,'id','_payment_terms')->select('id','_name','_detail','_days');
    }

    public function _sales_return(){
        return $this->hasMany(SalesReturn::class,'_order_ref_id','id')->with(['_master_details']);
    }

    

    
}
