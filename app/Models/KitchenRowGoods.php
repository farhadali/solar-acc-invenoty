<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KitchenRowGoods extends Model
{
    use HasFactory;

     public function _items(){
        return $this->hasOne(Inventory::class,'id','_item_id')->select('id','_item as _name','_unique_barcode');
    }
}
