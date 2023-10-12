<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountGroup extends Model
{
    use HasFactory;


    public function account_type(){
    	return $this->hasOne(AccountHead::class,'id','_account_head_id')->select('id','_name')->with(['_account_group']);
    }
}
