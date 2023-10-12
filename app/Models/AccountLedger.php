<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountLedger extends Model
{
    use HasFactory;

    protected $table = 'account_ledgers';

    
    public function account_type(){
    	return $this->hasOne(AccountHead::class,'id','_account_head_id')->select('id','_name');
    }


    public function account_group(){
    	return $this->hasOne(AccountGroup::class,'id','_account_group_id')->select('id','_name');
    }


    public function last_balance(){
    	return $this->hasOne(Accounts::class,'_account_ledger','id');
    }

    public function _entry_branch(){
        return $this->hasOne(Branch::class,'id','_branch_id')->select('id','_name');
    }


}
