<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CostCenterAuthorisedChain extends Model
{
    use HasFactory;


    public function erp_user_detail(){
        return $this->hasOne(ERPUser::class,'office_id','erp_user_id')->with(['_designation']);
    }
}
