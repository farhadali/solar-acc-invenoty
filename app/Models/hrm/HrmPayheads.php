<?php

namespace App\Models\hrm;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\AccountLedger;
use App\Models\User;

class HrmPayheads extends Model
{
    use HasFactory;

   protected $table="hrm_payheads";
   protected $fillable=['id', '_ledger', '_type', '_calculation', '_onhead', '_user', '_status', 'created_at', 'updated_at'];

   

    public function _entry_by(){
    	return $this->hasOne(User::class,'id','_user')->select('id','name','email');
    }

    public function _payhead_type(){
        return $this->hasOne(HrmPayHeadType::class,'id','_type');
    }
}
