<?php

namespace App\Models\RLP;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RlpAccessChain extends Model
{
    use HasFactory;
    protected $table="rlp_access_chains";


    public function _branch(){
        return $this->hasOne(\App\Models\Branch::class,'id','_branch_id')->select('id','_name');
        }
         public function _cost_center(){
                return $this->hasOne(\App\Models\CostCenter::class,'id','_cost_center_id')->select('id','_name');
        }
        public function _organization(){
                return $this->hasOne(\App\Models\hrm\Company::class,'id','organization_id')->select('id','_name');
        }

        public function _chain_user(){
            return $this->hasMany(AccessChainUser::class,'chain_id','id')->where('_status',1)->orderBy('_order','ASC');
        }

}
