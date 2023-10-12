<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestaurantCategorySetting extends Model
{
    use HasFactory;
    protected $table="restaurant_category_settings";

     public function _branch()
    {
    	return $this->hasOne(Branch::class,'id','_branch_ids')->select('id','_name');
    }
}
