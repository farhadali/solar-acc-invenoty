<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemCategory extends Model
{
    use HasFactory;

    public function _parents(){
    	return $this->hasOne(ItemCategory::class,'id','_parent_id')->select('id','_name');
    }
}
