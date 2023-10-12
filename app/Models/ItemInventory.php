<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemInventory extends Model
{
    use HasFactory;


    public function _category(){
    	return $this->hasOne(ItemCategory::class,'id','_category_id')->select('id','_name');
    }
}
