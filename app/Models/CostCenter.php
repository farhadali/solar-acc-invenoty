<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CostCenter extends Model
{
    use HasFactory;


    public function _branch()
    {
    	return $this->hasOne(Branch::class,'id','_branch_id')->select('id','_name');
    }

    public function chain(){
        return $this->hasMany(CostCenterAuthorisedChain::class,'_cost_center_id','id')->where('_status',1)->orderBy('ack_order','ASC');
    }
}
