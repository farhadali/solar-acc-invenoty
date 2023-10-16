<?php

namespace App\Models\RLP;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccessChainUser extends Model
{
    use HasFactory;

    protected $table="rlp_access_chain_users";
    protected $fillable=['id', 'chain_id', 'user_row_id', 'user_id', '_order', '_status', 'created_at', 'updated_at'];

    


}
