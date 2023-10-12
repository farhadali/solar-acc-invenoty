<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VoucherMasterDetail extends Model
{
    use HasFactory;


    public function _voucher_ledger(){
    	return $this->hasOne(AccountLedger::class,'id','_ledger_id')->select('id','_name','_address','_phone','_account_group_id','_account_head_id')->with(['account_type','account_group']);
    }


    public function _detail_branch(){
    	return $this->hasOne(Branch::class,'id','_branch_id')->select('id','_name');
    }

    public function _detail_cost_center(){
    	return $this->hasOne(CostCenter::class,'id','_cost_center')->select('id','_name');
    }



}
