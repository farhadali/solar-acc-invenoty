<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MainAccountHead extends Model
{
    use HasFactory;

    protected $table="main_account_head";

    public function _account_type(){
    	return $this->hasMany(AccountHead::class,'_account_id','id')->select('id','_name','_account_id')->with(['_account_group']);
    }
}
