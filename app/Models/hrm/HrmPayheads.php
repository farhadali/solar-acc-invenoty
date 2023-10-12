<?php

namespace App\Models\hrm;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\AccountLedger;
use App\Models\User;

class HrmPayheads extends Model
{
    use HasFactory;

    public function _ledger_info(){
    	return $this->hasOne(AccountLedger::class,'id','_ledger')->select('id','_name','_account_group_id','_account_head_id');
    }

    public function _entry_by(){
    	return $this->hasOne(User::class,'id','_user')->select('id','name','email');
    }
}
