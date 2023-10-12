<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountHead extends Model
{
    use HasFactory;
    protected $table="account_heads";


    public function _main_account_head(){
    	return $this->hasOne(MainAccountHead::class,'id','_account_id')->select('id','_name');
    }

    public function _account_group(){
    	return $this->hasMany(AccountGroup::class,'_account_head_id','id')->select('id','_name','_account_head_id');
    }
}
