<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneralSettings extends Model
{
    use HasFactory;

    protected $table="general_settings";
    protected $fillable=['title', 'name', '_address', 'keywords', 'author', 'url', '_bin', '_tin', 'logo', 'bg_image', 'footerContent', 'description', 'created_by', 'updated_by', 'created_at', 'updated_at', '_phone', '_email', '_sales_note', '_sales_return__note', '_purchse_note', '_purchase_return_note', '_top_title', '_ac_type', '_sms_service', '_barcode_service', '_bank_group', '_cash_group', '_auto_lock', '_pur_base_model_barcode', '_opening_ledger', '_employee_group'];


    


}
