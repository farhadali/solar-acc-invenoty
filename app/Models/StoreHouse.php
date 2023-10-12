<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreHouse extends Model
{
    use HasFactory;

    public function _branch()
    {
    	return $this->hasOne(Branch::class,'id','_branch_id')->select('id','_name');
    }
}
