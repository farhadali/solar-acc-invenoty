<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceMaster extends Model
{
    use HasFactory;

     public function _master_branch(){
    	return $this->hasOne(Branch::class,'id','_branch_id')->select('id','_name');
    }

    public function _master_details(){
    	return $this->hasMany(ServiceDetail::class,'_no','id')->with(['_detail_branch','_detail_cost_center','_items'])->where('_status',1);
    }

    public function _s_account(){
    	return $this->hasMany(ServiceAccount::class,'_no','id')->with(['_ledger','_detail_branch','_detail_cost_center'])->where('_status',1);


    }

    public function _ledger(){
    	return $this->hasOne(AccountLedger::class,'id','_ledger_id')->select('id','_name','_account_group_id','_account_head_id')
        ->with(['account_type','account_group']);
    }




    public function _master_cost_center(){
        return $this->hasOne(CostCenter::class,'id','_cost_center_id')->select('id','_name');
    }

    
}
