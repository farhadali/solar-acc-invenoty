<?php

namespace App\Http\Controllers;

use App\Models\Sales;
use App\Models\SalesAccount;
use App\Models\SalesDetail;
use App\Models\VoucherMaster;
use App\Models\AccountLedger;
use App\Models\AccountGroup;
use App\Models\AccountHead;
use App\Models\Accounts;
use App\Models\Branch;
use App\Models\VoucherType;
use App\Models\VoucherMasterDetail;
use App\Models\StoreHouse;
use App\Models\PurchaseReturnFormSetting;
use App\Models\PurchaseDetail;
use App\Models\PurchaseReturnAccount;
use App\Models\PurchaseReturnDetail;
use App\Models\ProductPriceList;
use App\Models\ItemInventory;
use App\Models\Inventory;
use App\Models\ItemCategory;
use App\Models\Units;
use App\Models\SalesFormSetting;
use App\Models\SalesReturn;
use App\Models\SalesReturnDetail;
use App\Models\SalesReturnAccount;
use App\Models\SalesReturnFormSetting;
use App\Models\GeneralSettings;
use App\Models\BarcodeDetail;
use App\Models\SalesBarcode;
use App\Models\Warranty;
use App\Models\TransectionTerms;
use App\Models\HRM\Company;
use Auth;
use DB;
use Illuminate\Http\Request;
use Session;

class SalesController extends Controller
{

        function __construct()
    {
         $this->middleware('permission:sales-list|sales-create|sales-edit|sales-delete|sales-print', ['only' => ['index','store']]);
         $this->middleware('permission:sales-print', ['only' => ['salesPrint']]);
         $this->middleware('permission:sales-create', ['only' => ['create','store']]);
         $this->middleware('permission:sales-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:sales-delete', ['only' => ['destroy']]);
         $this->page_name = "Sales";
    }



    public function posSales(){
        $users = Auth::user();
        $page_name = $this->page_name;
        $permited_branch = permited_branch(explode(',',$users->branch_ids));
        $store_houses = permited_stores(explode(',',$users->store_ids));
         $account_types = AccountHead::select('id','_name')->orderBy('_name','asc')->get();
        $form_settings = SalesFormSetting::first();
        $categories = ItemCategory::with(['_parents'])->select('id','_name','_parent_id')->orderBy('_name','asc')->get();
        $units = Units::select('id','_name','_code')->orderBy('_name','asc')->get();
        $account_groups = [];
        $_warranties = Warranty::select('id','_name')->orderBy('_name','asc')->where('_status',1)->get();
        return view('backend.pos.index',compact('permited_branch','store_houses','account_types','form_settings','categories','units','_warranties','account_groups'));
    }

    public function posPaymentRow(Request $request){
        $row_count = $request->payment_row_count;
        $settings = GeneralSettings::first();
        $payment_accounts = \DB::select(" SELECT id,_name FROM account_ledgers WHERE _account_group_id IN($settings->_bank_group,$settings->_cash_group) order by id ASC ");

        return view('backend.pos.payment_row',compact('payment_accounts','row_count'));
    }



    public function salesAfterReturn($id){
        $page_name = "Sales After Return";

         $users = Auth::user();
        $page_name = $this->page_name;
        $permited_branch = permited_branch(explode(',',$users->branch_ids));
        $permited_costcenters = permited_costcenters(explode(',',$users->cost_center_ids));
       
        $data =  Sales::with(['_master_branch','_master_details','s_account','_ledger','_terms_con'])->find($id);
        $form_settings = SalesFormSetting::first();
           $permited_branch = permited_branch(explode(',',$users->branch_ids));
        $permited_costcenters = permited_costcenters(explode(',',$users->cost_center_ids));
         
$store_houses = permited_stores(explode(',',$users->store_ids));
     $after_sales_return=     DB::select(" SELECT p1._date,p1._ledger_id,p1._item_id,p2._item as _item_name,p3._name as _unit_name,SUM(p1._qty) AS _qty,p1._sales_rate,SUM(p1._vat_amount) AS _vat_amount,SUM(p1._discount_amount) AS _discount_amount,SUM(p1._value) AS _value 
FROM(
SELECT s1.id,s1._date,s1._ledger_id,s2._item_id,s2._qty,s2._sales_rate,s2._value,s2._discount_amount,s2._vat_amount
FROM sales AS s1
INNER JOIN sales_details AS s2 ON s1.id=s2._no
WHERE s1.id=$id
UNION ALL
SELECT t1.id,t1._date,t1._ledger_id,t2._item_id,-t2._qty,t2._sales_rate,-(t2._value) as _value,-t2._discount_amount,-t2._vat_amount
FROM sales_returns AS t1
INNER JOIN sales_return_details AS t2 ON t1.id=t2._no
WHERE t1._order_ref_id=$id
    ) AS p1
    INNER JOIN inventories AS p2 ON p1._item_id=p2.id
    INNER JOIN units as p3 ON p2._unit_id=p3.id
    GROUP BY p1._item_id,p1._sales_rate ");

    $history_sales_invoices=[];


 $sales_returns = SalesReturn::with(['_master_details'])->where('_order_ref_id',$id)->get();
         
   
return view('backend.sales.net_sales_after_return',compact('page_name','permited_branch','permited_costcenters','data','form_settings','permited_branch','permited_costcenters','store_houses','sales_returns','after_sales_return','history_sales_invoices'));
        

        
    }


    public function posSalesSave(Request $request){

       //return $request->all();
        
         $users = Auth::user();
        $settings = GeneralSettings::first();
        $__total = $request->tot_grand ?? 0;
        $_main_ledger_id = $request->_main_ledger_id;
        $ledger_info = AccountLedger::find($_main_ledger_id);
        $_main_branch = $request->_main_branch;
        $_main_store = $request->_main_store;
        $hidden_rowcount = $request->hidden_rowcount;
        $_line_total_discount = $request->_line_total_discount ?? 0;
        $_line_vat_total = $request->_line_vat_total ?? 0;
        $_reference = $request->_reference;
        $__discount_input = $request->tot_disc ?? 0;
        $__sub_total = $request->tot_amt ?? 0;
        $_cost_center_id = $request->_cost_center_id ?? 1;
        $_delivery_man_id = 0;
        $_sales_man_id = 0;
        $_sales_type = $request->_status;
        $_lock = $settings->_auto_lock ?? 0;
        $_date = date('Y-m-d');
        $_time = date('h:m:s');
        $_user = $users->name ?? '';
        $_address = $ledger_info->_address ?? '';
        $_phone = $ledger_info->_phone ?? '';
        $_created_by =$users->id."-".$users->_name ?? '';
        $_status = 1;
        $_note ='Sales From Pos';


        $_base_unit_ids = $request->_base_unit_id ?? [];
        $conversion_qtys = $request->conversion_qty ?? [];
        $_transection_units = $request->_transection_unit ?? [];



       // return $request->all();
      DB::beginTransaction();
       try {

        $_p_balance = _l_balance_update($_main_ledger_id);

         $_sales_man_id = $_sales_man_id;
         $__total_discount = ((float) $__discount_input)  + ((float)$_line_total_discount);

       
        
        $Sales = new Sales();
        $Sales->_date = change_date_format($_date);
        $Sales->_time = date('H:i:s');
        $Sales->_order_ref_id = $request->_order_ref_id ?? null;
        $Sales->_order_number = $request->_order_number ?? '';
        $Sales->_referance = $request->_referance ?? '';
        $Sales->_ledger_id = $_main_ledger_id;
        $Sales->_user_id = $users->id;
        $Sales->_created_by = $users->id."-".$users->name;
        $Sales->_user_id = $users->id;
        $Sales->_user_name = $users->name;
        $Sales->_note = $_note;
        $Sales->_sub_total = $__sub_total;
        $Sales->_discount_input = $__discount_input;
        $Sales->_total_discount = $__total_discount;
        $Sales->_total_vat = $_line_vat_total;
        $Sales->_total =  $__total;
        $Sales->_branch_id = $_main_branch;
        $Sales->_cost_center_id = $request->_cost_center_id ?? 1;
        $Sales->_address = $users->_address;
        $Sales->_phone = $users->_phone;
        $Sales->_delivery_man_id = $_delivery_man_id ?? 0;
        $Sales->_sales_man_id = $_sales_man_id ?? 0;
        $Sales->_sales_type = $_sales_type ?? 'sales';
        $Sales->_status = 1;
        $Sales->_lock = $_lock ?? 0;

        $Sales->save();
        $_master_id = $Sales->id;  

                  

        //###########################
        // Purchase Master information Save End
        //###########################

        //###########################
        // Purchase Details information Save Start
        //###########################
        
   
  
    $__total_vat =$_line_vat_total;
    $_total_cost_value=0;

    $row_count = intval($_POST["hidden_rowcount"]) ;
    for($i=0; $i<$row_count; $i++){
        $tr_row_id = $_POST["tr_item_id_".$i.""];
        $ProductPriceList = ProductPriceList::find($tr_row_id);
        $_item_ids= $ProductPriceList->_item_id;
        $_unit_id = $ProductPriceList->_unit_id;

        $_purchase_invoice_nos =$ProductPriceList->_master_id;
        $_purchase_detail_ids =$ProductPriceList->_purchase_detail_id ;
        $_manufacture_dates =$ProductPriceList->_manufacture_date ;
        $_expire_dates =$ProductPriceList->_expire_date ;
        $_warrantys =$ProductPriceList->_warranty ;

        $_qtys = (float) $_POST["item_qty_".$tr_row_id.""];
        $_sales_rates = (float) $_POST["sales_price_".$i.""];
        $item_discount = (float) $_POST["item_discount_".$i.""];
        $vat = (float) $_POST["td_data_".$i."_11"];
        $sub_total = (float) $_POST["td_data_".$i."_4"];
        $_sales_rates = (float) $_POST["tr_sales_price_temp_".$i.""];
        $tr_tax_type = $_POST["tr_tax_type_".$i.""];
        $tr_tax_id = (float) $_POST["tr_tax_id_".$i.""];
        $tax_rate= (float) $_POST["tr_tax_value_".$i.""];
       
        if($tax_rate > 0){
            $_vat_amount= (($_sales_rates*$_qtys)*$tax_rate)/100;
        }else{
            $_vat_amount= 0;
        }

        $__sub_total +=$sub_total;
        
        $line_description = $_POST["description_".$i.""];
        $item_discount_type = $_POST["item_discount_type_".$i.""];
        $_rates = $_POST["tr_item_cost_".$i.""];
        $item_discount_ = $_POST["item_discount_".$i.""];
        
        $item_discount_input = $_POST["item_discount_input_".$i.""];
        if($item_discount_type=="Fixed"){
        $item_discount_input =0;    
        }
        $_barcode = $_POST["_barcode_".$i.""];

                $_total_cost_value += ($_rates*$_qtys);

                $SalesDetail = new SalesDetail();
                $SalesDetail->_item_id = $_item_ids;
                $SalesDetail->_p_p_l_id = $tr_row_id;
                $SalesDetail->_purchase_invoice_no = $_purchase_invoice_nos;
                $SalesDetail->_purchase_detail_id = $_purchase_detail_ids;
                $SalesDetail->_qty = $_qtys;
                $barcode_string=$_barcode ?? '';
                $SalesDetail->_barcode = $barcode_string;

                $SalesDetail->_transection_unit = $_unit_id;
                $SalesDetail->_unit_conversion = 1;
                $SalesDetail->_base_unit = $_unit_id;
                $SalesDetail->_base_rate =$_rates ?? 0;



                $SalesDetail->_manufacture_date = $_manufacture_dates;
                $SalesDetail->_expire_date = $_expire_dates;
                $SalesDetail->_rate = $_rates ?? 0;
                $SalesDetail->_warranty = $_warrantys ?? 0;
                $SalesDetail->_sales_rate = $_sales_rates ?? 0;
                $SalesDetail->_discount = $item_discount_input ?? 0;
                $SalesDetail->_discount_amount = $item_discount_ ?? 0;
                $SalesDetail->_vat = $tax_rate ?? 0;
                $SalesDetail->_vat_amount = $_vat_amount ?? 0;
                $SalesDetail->_value =($_sales_rates*$_qtys) ?? 0;
                $SalesDetail->_store_id = $_main_store ?? 1;
                $SalesDetail->_cost_center_id = $_main_cost_center ?? 1;
                $SalesDetail->_store_salves_id = $_store_salves_ids ?? '';
                $SalesDetail->_branch_id = $_main_branch_id_detail ?? 1;
                $SalesDetail->_no = $_master_id;
                $SalesDetail->_status = 1;
                $SalesDetail->_created_by = $users->id."-".$users->name;
                $SalesDetail->save();
                $_sales_details_id = $SalesDetail->id;

                $item_info = Inventory::where('id',$_item_ids)->first();
                $_p_qty = $ProductPriceList->_qty;
                $_unique_barcode = $ProductPriceList->_unique_barcode;
                //Barcode  deduction from old string data
                if($_unique_barcode ==1){
                     $_old_barcode_strings =  $ProductPriceList->_barcode;
                        $_new_barcode_array = array();
                        if($_old_barcode_strings !=""){
                            $_old_barcode_array = explode(",",$_old_barcode_strings);
                        }
                        if($barcode_string !=""){
                            $_new_barcode_array = explode(",",$barcode_string);
                        }
                        if(sizeof($_new_barcode_array) > 0 && sizeof($_old_barcode_array) > 0){
                          $_last_barcode_array =  array_diff($_old_barcode_array,$_new_barcode_array);
                          if(sizeof($_last_barcode_array ) > 0){
                            $_last_barcode_string = implode(",",$_last_barcode_array);
                          }else{
                            $_last_barcode_string = $barcode_string;
                          }
                          
                          $ProductPriceList->_barcode = $_last_barcode_string;
                        }
                }else{
                  $ProductPriceList->_barcode = $barcode_string;
                }
                //Barcode  deduction from old string data
                $_status = (($_p_qty - $_qtys) > 0) ? 1 : 0;
                $ProductPriceList->_qty = ($_p_qty - $_qtys);
                $ProductPriceList->_status = $_status;
                $ProductPriceList->save();

                $product_price_id =  $ProductPriceList->id;
             if($_unique_barcode ==1){
                  if($barcode_string !=""){
                       $barcode_array=  explode(",",$barcode_string);
                       $_qty = 1;
                       $_stat = 1;
                       $_return=1;
                       
                       foreach ($barcode_array as $_b_v) {
                        _barcode_insert_update('BarcodeDetail', $product_price_id,$_item_ids,$_master_id,$_sales_details_id,$_qty,$_b_v,$_stat,1,1);
                        _barcode_insert_update('SalesBarcode', $product_price_id,$_item_ids,$_master_id,$_sales_details_id,$_qty,$_b_v,$_stat,0,0);
                         
                       }
                    }
             }


             $ItemInventory = new ItemInventory();
            $ItemInventory->_item_id =  $_item_ids;
            $ItemInventory->_item_name =  $item_info->_item ?? '';
             $ItemInventory->_unit_id =  $item_info->_unit_id ?? '';
            $ItemInventory->_category_id = _item_category($_item_ids);
            $ItemInventory->_date = change_date_format($request->_date);
            $ItemInventory->_time = date('H:i:s');
            $ItemInventory->_transection = "Sales";
            $ItemInventory->_transection_ref = $_master_id;
            $ItemInventory->_transection_detail_ref_id = $_sales_details_id;
            $ItemInventory->_qty = -($_qtys);

            $ItemInventory->_transection_unit = $_unit_id;
            $ItemInventory->_unit_conversion = $conversion_qtys[$i] ?? 1;
            $ItemInventory->_base_unit = $_unit_id;

            $ItemInventory->_rate = $_sales_rates;
            $ItemInventory->_cost_rate = $_rates;
            $ItemInventory->_manufacture_date = $_manufacture_dates;
            $ItemInventory->_expire_date = $_expire_dates;
            $ItemInventory->_cost_value = ($_qtys*$_rates);
            $ItemInventory->_value = $_values ?? 0;
            $ItemInventory->_branch_id = $_main_branch_id_detail ?? 1;
            $ItemInventory->_store_id = $_store_ids ?? 1;
            $ItemInventory->_cost_center_id = $_main_cost_center ?? 1;
            $ItemInventory->_store_salves_id = $_store_salves_ids ?? '';
            $ItemInventory->_status = 1;
            $ItemInventory->_created_by = $users->id."-".$users->name;
            $ItemInventory->save(); 
            inventory_stock_update($_item_ids);

    }

   


      /// return json_encode($_master_id); 
        //###########################
        // Purchase Details information Save End
        //###########################

        //###########################
        // Purchase Account information Save End
        //###########################
        $_total_dr_amount = 0;
        $_total_cr_amount = 0;
        $_date = $request->_date ?? date('Y-m-d');
       $SalesFormSetting =  SalesFormSetting::first();
        $_default_inventory = $SalesFormSetting->_default_inventory;
        $_default_sales = $SalesFormSetting->_default_sales;
        $_default_discount = $SalesFormSetting->_default_discount;
        $_default_vat_account = $SalesFormSetting->_default_vat_account;
        $_default_cost_of_solds = $SalesFormSetting->_default_cost_of_solds;

        $_ref_master_id=$_master_id;
        $_ref_detail_id=$_master_id;
        $_short_narration='N/A';
        $_narration = $request->_note;
        $_reference= $request->_referance;
        $_transaction= 'Sales';
        $_date = change_date_format($_date ?? date('Y-m-d'));
        $_table_name = $request->_form_name ?? 'sales';
        $_branch_id = $_main_branch;
        $_cost_center =$request->_cost_center ??  1;
        $_name =$users->name;
       // return $__sub_total;
        if($__sub_total > 0){

            //#################
            // Account Receiveable Dr.
            //      Sales Cr
            //#################

            //Default Sales DR.
            account_data_save($_ref_master_id,$_ref_detail_id,_find_ledger($_default_sales),$_narration,$_reference,$_transaction,$_date,$_table_name,$_main_ledger_id,$__sub_total,0,$_branch_id,$_cost_center,$_name,1);
           //Default Account Receivable  Cr.
            account_data_save($_ref_master_id,$_ref_detail_id,_find_ledger($request->_main_ledger_id),$_narration,$_reference,$_transaction,$_date,$_table_name,$_default_sales,0,$__sub_total,$_branch_id,$_cost_center,$_name,2);

            //#################
            // Cost of Goods Sold Dr.
            //      Inventory  Cr
            //#################

            //Cost of Goods Sold Dr.
            account_data_save($_ref_master_id,$_ref_detail_id,_find_ledger($_default_inventory),$_narration,$_reference,$_transaction,$_date,$_table_name,$_default_cost_of_solds,$_total_cost_value,0,$_branch_id,$_cost_center,$_name,3);
            //Inventory  Cr
            account_data_save($_ref_master_id,$_ref_detail_id,_find_ledger($_default_cost_of_solds),$_narration,$_reference,$_transaction,$_date,$_table_name,$_default_inventory,0,$_total_cost_value,$_branch_id,$_cost_center,$_name,4);
        }

        if($__total_discount > 0){
            //return $__total_discount;
             //#################
            // Sales Discount Dr.
            //      Account Receivable  Cr
            //#################
            //Default Discount
            account_data_save($_ref_master_id,$_ref_detail_id,_find_ledger($request->_main_ledger_id),$_narration,$_reference,$_transaction,$_date,$_table_name,$_default_discount,$__total_discount,0,$_branch_id,$_cost_center,$_name,5);
            //  Account Receivable  Cr
            account_data_save($_ref_master_id,$_ref_detail_id,_find_ledger($_default_discount),$_narration,$_reference,$_transaction,$_date,$_table_name,$request->_main_ledger_id,0,$__total_discount,$_branch_id,$_cost_center,$_name,6);
             
        
        }
        $__total_vat = (float) $request->_total_vat ?? 0;
        if($__total_vat > 0){
             //#################
            // Account Receivable Dr.
            //      Vat  Cr
            //#################
            //Default Vat Account
            account_data_save($_ref_master_id,$_ref_detail_id,_find_ledger($_default_vat_account),$_narration,$_reference,$_transaction,$_date,$_table_name,$request->_main_ledger_id,$request->_total_vat,0,$_branch_id,$_cost_center,$_name,7);
            //Account Receivable
            account_data_save($_ref_master_id,$_ref_detail_id,_find_ledger($request->_main_ledger_id),$_narration,$_reference,$_transaction,$_date,$_table_name,$_default_vat_account,0,$request->_total_vat,$_branch_id,$_cost_center,$_name,8);
        
        }

       

      
        //return $_POST["pay_all"];
        $payment_row_count = intval($_POST["payment_row_count"] ?? 0);
        //Start Full Payment with first Account/Cash in Hand
        if($_POST["pay_all"] ==="true"){
            $ledger = intval($_POST["payment_group"][0]);
            $_dr_amount = $_POST["amount_1"];
            $_cr_amount = 0;
            $_short_narr = $_POST["payment_note_1"];

                        $_account_type_id =  ledger_to_group_type($ledger)->_account_head_id;
                        $_account_group_id =  ledger_to_group_type($ledger)->_account_group_id;

                        $SalesAccount = new SalesAccount();
                        $SalesAccount->_no = $_master_id;
                        $SalesAccount->_account_type_id = $_account_type_id;
                        $SalesAccount->_account_group_id = $_account_group_id;
                        $SalesAccount->_ledger_id = $ledger;
                        $SalesAccount->_cost_center = $_cost_center ?? 1;
                        $SalesAccount->_branch_id = $_branch_id_detail ?? 1;
                        $SalesAccount->_short_narr = $_short_narr ?? 'N/A';
                        $SalesAccount->_dr_amount = $_dr_amount ?? 0;
                        $SalesAccount->_cr_amount = $_cr_amount ?? 0;
                        $SalesAccount->_status = 1;
                        $SalesAccount->_created_by = $users->id."-".$users->name;
                        $SalesAccount->save();

                        $_sales_account_id = $SalesAccount->id;

                        //Reporting Account Table Data Insert
                        $_ref_master_id=$_master_id;
                        $_ref_detail_id=$_sales_account_id;
                        $_short_narration=$_short_narr ?? 'N/A';
                        $_narration = $request->_note;
                        $_reference= $request->_referance ?? 'N/A';
                        $_transaction= 'Sales';
                        $_date = change_date_format($_date);
                        $_table_name ='sales_accounts';
                        $_account_ledger = $ledger;
                        $_dr_amount_a = $_dr_amount ?? 0;
                        $_cr_amount_a = $_cr_amount ?? 0;
                        $_branch_id_a = $_branch_id_detail ?? 1;
                        $_cost_center_a = $_cost_center ?? 1;
                        $_name =$users->name;
                        account_data_save($_ref_master_id,$_ref_detail_id,$_short_narration,$_narration,$_reference,$_transaction,$_date,$_table_name,$_account_ledger,$_dr_amount_a,$_cr_amount_a,$_branch_id_a,$_cost_center_a,$_name,(9));



                        $_account_type_id =  ledger_to_group_type($_main_ledger_id)->_account_head_id;
                        $_account_group_id =  ledger_to_group_type($_main_ledger_id)->_account_group_id;

                        $SalesAccount = new SalesAccount();
                        $SalesAccount->_no = $_master_id;
                        $SalesAccount->_account_type_id = $_account_type_id;
                        $SalesAccount->_account_group_id = $_account_group_id;
                        $SalesAccount->_ledger_id = $_main_ledger_id;
                        $SalesAccount->_cost_center = $_cost_center ?? 1;
                        $SalesAccount->_branch_id = $_branch_id_detail ?? 1;
                        $SalesAccount->_short_narr = $_short_narr ?? 'N/A';
                        $SalesAccount->_dr_amount =  0;
                        $SalesAccount->_cr_amount = $_dr_amount ?? 0;
                        $SalesAccount->_status = 1;
                        $SalesAccount->_created_by = $users->id."-".$users->name;
                        $SalesAccount->save();

                        $_sales_account_id = $SalesAccount->id;

                        //Reporting Account Table Data Insert
                        $_ref_master_id=$_master_id;
                        $_ref_detail_id=$_sales_account_id;
                        $_short_narration=$_short_narr ?? 'N/A';
                        $_narration = $request->_note;
                        $_reference= $request->_referance ?? 'N/A';
                        $_transaction= 'Sales';
                        $_date = change_date_format($_date);
                        $_table_name ='sales_accounts';
                        $_account_ledger = $_main_ledger_id;
                        $_dr_amount_a =  0;
                        $_cr_amount_a = $_dr_amount ?? 0;
                        $_branch_id_a = $_branch_id_detail ?? 1;
                        $_cost_center_a = $_cost_center ?? 1;
                        $_name =$users->name;
                        account_data_save($_ref_master_id,$_ref_detail_id,$_short_narration,$_narration,$_reference,$_transaction,$_date,$_table_name,$_account_ledger,$_dr_amount_a,$_cr_amount_a,$_branch_id_a,$_cost_center_a,$_name,(10));
        }else{
            $_total_cr = 0;
            $___amounts = $_POST["amount"];
            $___payment_group = $_POST["payment_type"];
            $___payment_note = $_POST["payment_note"];
    for($j=0; $j<$payment_row_count; $j++){
      $ledger = intval($___payment_group[$j]);
      $_dr_amount =(float) $___amounts[$j] ?? 0;
      $_cr_amount =0;
      $_total_cr += (float) $_dr_amount;
      $_short_narr=$___payment_note[$j] ?? 'N/A';

        $_account_type_id =  ledger_to_group_type($ledger)->_account_head_id;
        $_account_group_id =  ledger_to_group_type($ledger)->_account_group_id;

        $SalesAccount = new SalesAccount();
        $SalesAccount->_no = $_master_id;
        $SalesAccount->_account_type_id = $_account_type_id;
        $SalesAccount->_account_group_id = $_account_group_id;
        $SalesAccount->_ledger_id = $ledger;
        $SalesAccount->_cost_center = $_cost_center ?? 1;
        $SalesAccount->_branch_id = $_branch_id_detail ?? 1;
        $SalesAccount->_short_narr = $_short_narr ?? 'N/A';
        $SalesAccount->_dr_amount = $_dr_amount ?? 0;
        $SalesAccount->_cr_amount = $_cr_amount ?? 0;
        $SalesAccount->_status = 1;
        $SalesAccount->_created_by = $users->id."-".$users->name;
        $SalesAccount->save();

        $_sales_account_id = $SalesAccount->id;

        //Reporting Account Table Data Insert
        $_ref_master_id=$_master_id;
        $_ref_detail_id=$_sales_account_id;
        $_short_narration=$_short_narr ?? 'N/A';
        $_narration = $request->_note;
        $_reference= $request->_referance ?? 'N/A';
        $_transaction= 'Sales';
        $_date = change_date_format($_date);
        $_table_name ='sales_accounts';
        $_account_ledger = $ledger;
        $_dr_amount_a = $_dr_amount ?? 0;
        $_cr_amount_a = $_cr_amount ?? 0;
        $_branch_id_a = $_branch_id_detail ?? 1;
        $_cost_center_a = $_cost_center ?? 1;
        $_name =$users->name;
        account_data_save($_ref_master_id,$_ref_detail_id,$_short_narration,$_narration,$_reference,$_transaction,$_date,$_table_name,$_account_ledger,$_dr_amount_a,$_cr_amount_a,$_branch_id_a,$_cost_center_a,$_name,(9+$j));
            

    }

                        $_account_type_id =  ledger_to_group_type($_main_ledger_id)->_account_head_id;
                        $_account_group_id =  ledger_to_group_type($_main_ledger_id)->_account_group_id;
                        $_short_narr = 'Bill Paid';
                        $SalesAccount = new SalesAccount();
                        $SalesAccount->_no = $_master_id;
                        $SalesAccount->_account_type_id = $_account_type_id;
                        $SalesAccount->_account_group_id = $_account_group_id;
                        $SalesAccount->_ledger_id = $_main_ledger_id;
                        $SalesAccount->_cost_center = $_cost_center ?? 1;
                        $SalesAccount->_branch_id = $_branch_id_detail ?? 1;
                        $SalesAccount->_short_narr = $_short_narr ?? 'N/A';
                        $SalesAccount->_dr_amount =  0;
                        $SalesAccount->_cr_amount = $_total_cr ?? 0;
                        $SalesAccount->_status = 1;
                        $SalesAccount->_created_by = $users->id."-".$users->name;
                        $SalesAccount->save();

                        $_sales_account_id = $SalesAccount->id;

                        //Reporting Account Table Data Insert
                        $_ref_master_id=$_master_id;
                        $_ref_detail_id=$_sales_account_id;
                        $_short_narration=$_short_narr ?? 'N/A';
                        $_narration = $request->_note;
                        $_reference= $request->_referance ?? 'N/A';
                        $_transaction= 'Sales';
                        $_date = change_date_format($_date);
                        $_table_name ='sales_accounts';
                        $_account_ledger = $_main_ledger_id;
                        $_dr_amount_a =  0;
                        $_cr_amount_a = $_total_cr ?? 0;
                        $_branch_id_a = $_branch_id_detail ?? 1;
                        $_cost_center_a = $_cost_center ?? 1;
                        $_name =$users->name;
                        account_data_save($_ref_master_id,$_ref_detail_id,$_short_narration,$_narration,$_reference,$_transaction,$_date,$_table_name,$_account_ledger,$_dr_amount_a,$_cr_amount_a,$_branch_id_a,$_cost_center_a,$_name,(10));
}
       
       

          $_l_balance = _l_balance_update($request->_main_ledger_id);
          $_pfix = _sales_pfix().$_master_id;
             \DB::table('sales')
             ->where('id',$_master_id)
             ->update(['_p_balance'=>$_p_balance,'_l_balance'=>$_l_balance,'_order_number'=>$_pfix]);

               //SMS SEND to Customer and Supplier
             $_send_sms = $request->_send_sms ?? '';
             if($_send_sms=='yes'){
                $_ledger_info = AccountLedger::where($request->_main_ledger_id)->first();
                $_name = _ledger_name($request->_main_ledger_id);
                $_phones = $request->_phone ?? $_ledger_info->phone;
                $messages = "Dear ".$_name.", Date: ".$_date." Invoice N0.".$_pfix." Invoice Amount: ".prefix_taka()."."._report_amount($request->_total ?? $_total_cr).". Payment Amount. ".prefix_taka()."."._report_amount($_total_dr_amount);
                  sms_send($messages, $_phones);
             }


        $page_name = $this->page_name;
        $permited_branch = permited_branch(explode(',',$users->branch_ids));
        $permited_costcenters = permited_costcenters(explode(',',$users->cost_center_ids));
       
         $data =  Sales::with(['_master_branch','_master_details','s_account','_ledger'])->find($_master_id);
        $form_settings = SalesFormSetting::first();
        $permited_branch = permited_branch(explode(',',$users->branch_ids));
        $permited_costcenters = permited_costcenters(explode(',',$users->cost_center_ids));
        $store_houses = permited_stores(explode(',',$users->store_ids));
         
         
      // return   $invoice_print =   view('backend.pos.pos_template',compact('page_name','permited_branch','permited_costcenters','data','form_settings','permited_branch','permited_costcenters','store_houses'));
         

             $_mess['message']='success';
             $_mess['_master_id'] =$_master_id;
             


             
             //End Sms Send to customer and Supplier

           DB::commit();
            return json_encode( $_mess);
       } catch (\Exception $e) {
           DB::rollback();
           $_mess['message']='error';
             $_mess['_master_id'] =$_master_id;
        }


    }

    public function holdInvoiceList(){
        $invoice_list = [];
        $data["invoice_list"]=$invoice_list;
        return json_encode($data);
    }

    public function categoryWiseItem(request $request){
       // return $request->all();
        $category_id = $request->_item_category;
        $brach_id = intval($request->brach_id ?? 1);
        $store_id = intval($request->store_id ?? 1);
        $limit = 20;
       
        $sub_category = ItemCategory::where('_parent_id',$category_id)
        ->select('id','_name','_parent_id','_image')
        ->get();
        if(sizeof($sub_category) > 0){
            $data['category']=$sub_category;

            $data['items']=[];

        }else{
 
            if($category_id =='All'){
                $sub_category = ItemCategory::where('_parent_id',$category_id)->get();
                $items = [];
                $data['category']=[];
                $data['items']= $items;
            }else{

                $items = DB::select(" SELECT t1._unique_barcode, t1._image, t1._category_id as _category,t2.id as _row_id,t2._item_id AS _id,t2._item,t2._unit_id AS _unit,t2._sales_rate AS _saleprice,t2._pur_rate AS _purprice,1 as _sales_vat,1 as _p_vat,t2._barcode AS _itemcode,t2._qty as available_qty,t2._sales_vat as _vat,t2._sales_discount  FROM inventories as t1 
                    INNER JOIN product_price_lists AS t2 ON t1.id=t2._item_id
                    WHERE t1._category_id=".$category_id." AND t2._qty > 0 AND t2._branch_id=".$brach_id." AND t2._store_id=".$store_id."  ");
                $data['category']=[];
                $data['items']= $items;
            }
            

        }
return json_encode( $data);

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //return $request->all();
        $auth_user = Auth::user();
       if($request->has('limit')){
            $limit = $request->limit ??  default_pagination();
            session()->put('_sales_limit', $request->limit);
        }else{
             $limit= \Session::get('_sales_limit') ??  default_pagination();
            
        }
        
        $_asc_desc = $request->_asc_desc ?? 'DESC';
        $asc_cloumn =  $request->asc_cloumn ?? 'id';

        $datas = DB::table('sales')
                ->join('account_ledgers', 'account_ledgers.id', '=', 'sales._ledger_id')
                 ->select('sales.*', 'account_ledgers._name as _ledger_name')
                 ->where('sales._status',1);
       

      $datas = Sales::with(['_organization','_master_branch','_ledger'])->where('_status',1);
        //$datas = $datas->whereIn('sales._branch_id',explode(',',\Auth::user()->branch_ids));
      $datas = $datas->whereIn('_branch_id',explode(',',$auth_user->branch_ids));
        $datas = $datas->whereIn('_cost_center_id',explode(',',$auth_user->cost_center_ids));
        $datas = $datas->whereIn('organization_id',explode(',',$auth_user->organization_ids));
        if($auth_user->user_type !='admin'){
                $datas = $datas->where('_user_id',$auth_user->id);   
        } 
        
        

        if($request->has('_user_date') && $request->_user_date=="yes" && $request->_datex !="" && $request->_datex !=""){
            $_datex =  change_date_format($request->_datex);
            $_datey=  change_date_format($request->_datey);

             $datas = $datas->whereDate('_date','>=', $_datex);
            $datas = $datas->whereDate('_date','<=', $_datey);
        }

        if($request->has('id') && $request->id !=""){

             $ids =  array_map('intval', explode(',', $request->id ));
            $datas = $datas->where('id', $ids); 
        }
        
        if($request->has('_lock') && $request->_lock !=''){
            $datas = $datas->where('_lock','=',$request->_lock);
        }
        if($request->has('_order_ref_id') && $request->_order_ref_id !=''){
            $datas = $datas->where('_order_ref_id','like',"%$request->_order_ref_id%");
        }
        if($request->has('_order_number') && $request->_order_number !=''){
            $datas = $datas->where('_order_number','like',"%$request->_order_number%");
        }
        if($request->has('_cost_center_id') && $request->_cost_center_id !=''){
            $datas = $datas->where('_cost_center_id','=',$request->_cost_center_id);
        }
        if($request->has('_store_salves_id') && $request->_store_salves_id !=''){
            $datas = $datas->where('_store_salves_id','=',$request->_store_salves_id);
        }
        if($request->has('_delivery_man_id') && $request->_delivery_man_id !=''){
            $datas = $datas->where('_delivery_man_id','=',$request->_delivery_man_id);
        }

        if($request->has('_sales_man_id') && $request->_sales_man_id !=''){
            $datas = $datas->where('_sales_man_id','=',$request->_sales_man_id);
        }

        if($request->has('_sales_type') && $request->_sales_type !=''){
            $datas = $datas->where('_sales_type','=',$request->_sales_type);
        }

        if($request->has('_referance') && $request->_referance !=''){
            $datas = $datas->where('_referance','like',"%$request->_referance%");
        }
        if($request->has('_note') && $request->_note !=''){
            $datas = $datas->where('_note','like',"%$request->_note%");
        }
        if($request->has('_user_name') && $request->_user_name !=''){

            $datas = $datas->where('_user_name','like',"%$request->_user_name%");
        }
        
        if($request->has('_sub_total') && $request->_sub_total !=''){
            $datas = $datas->where('_sub_total','=',trim($request->_sub_total));
        }
        if($request->has('_total_discount') && $request->_total_discount !=''){
            $datas = $datas->where('_total_discount','=',trim($request->_total_discount));
        }
        if($request->has('_total_vat') && $request->_total_vat !=''){
            $datas = $datas->where('_total_vat','=',trim($request->_total_vat));
        }
        if($request->has('_total') && $request->_total !=''){
            $datas = $datas->where('_total','=',trim($request->_total));
        }
        if($request->has('_ledger_id') && $request->_ledger_id !='' && $request->has('_search_main_ledger_id') && $request->_search_main_ledger_id ){
            $datas = $datas->where('_ledger_id','=',trim($request->_ledger_id));
        }
        
     $datas = $datas->orderBy($asc_cloumn,$_asc_desc)
                        ->paginate($limit);

         $page_name = $this->page_name;
         $account_types = AccountHead::select('id','_name')->orderBy('_name','asc')->get();
         $account_groups = AccountGroup::select('id','_name')->orderBy('_name','asc')->get();
          $current_date = date('Y-m-d');
          $current_time = date('H:i:s');
          $users = Auth::user();
           $form_settings = SalesFormSetting::first();
           $permited_branch = permited_branch(explode(',',$users->branch_ids));
         $permited_costcenters = permited_costcenters(explode(',',$users->cost_center_ids));
         $store_houses = permited_stores(explode(',',$users->store_ids));
         
        //return $datas;
         if($request->has('print')){
            if($request->print =="single"){
                return view('backend.sales.master_print',compact('datas','page_name','account_types','request','account_groups','current_date','current_time','limit','form_settings','permited_branch','permited_costcenters','store_houses'));
            }

            if($request->print =="detail"){
                return view('backend.sales.details_print',compact('datas','page_name','account_types','request','account_groups','current_date','current_time','limit','form_settings','permited_branch','permited_costcenters','store_houses'));
            }
         }

        return view('backend.sales.index',compact('datas','page_name','account_types','request','account_groups','current_date','limit','form_settings','permited_branch','permited_costcenters','store_houses'));
    }

     public function reset(){
        Session::flash('_sales_limit');
       return  \Redirect::to('sales?limit='.default_pagination());
    }


    public function invoiceWiseDetail(Request $request){
         $users = Auth::user();
        $invoice_id = $request->invoice_id;
        $key = $request->_attr_key;
         $data = Sales::with(['_master_details','s_account'])->where('id',$invoice_id)->first();
        $permited_branch = permited_branch(explode(',',$users->branch_ids));
        $permited_costcenters = permited_costcenters(explode(',',$users->cost_center_ids));
        $store_houses = permited_stores(explode(',',$users->store_ids));
        
          $form_settings = SalesFormSetting::first();

        return view('backend.sales.sales_details',compact('data','permited_branch','permited_costcenters','store_houses','key','form_settings'));

    }

      public function moneyReceipt($id){
        $users = Auth::user();
        $page_name = 'Money Receipt';
        
        $branchs = Branch::orderBy('_name','asc')->get();
        $permited_branch = permited_branch(explode(',',$users->branch_ids));
        $permited_costcenters = permited_costcenters(explode(',',$users->cost_center_ids));
        $data = Sales::with(['_master_branch','s_account','_ledger'])->find($id);
         $form_settings = SalesFormSetting::first();

       return view('backend.sales.money_receipt',compact('page_name','branchs','permited_branch','permited_costcenters','data','form_settings'));
    }


    public function checkAvailableQty(Request $request){
        //return $request->all();
        $unique_p_q = [];
         $_over_qty = array();

        $unique_p_ids = $request->unique_p_ids ?? [];
        $_p_ids = implode(",",$unique_p_ids); //Product Price list table ID

      //Unique Barcode Available Check 
         $_all_barcode= $request->_all_barcode ?? '';
        $_all_barcodes = array();
        if($_all_barcode !=''){
          $_all_barcodes = explode(",",$_all_barcode);
        }
        $_sales_return_id = $request->_sales_return_id ?? 0;
        $_sales_id = $request->_order_ref_id ?? 0;
        
        if(sizeof($_all_barcodes) > 0){
          $productPriceListTableData =DB::select(" SELECT  _barcode FROM product_price_lists AS s1 WHERE s1.id IN(".$_p_ids.")  ");
          $_available_barcode_numbers = [];
          foreach ($productPriceListTableData as $_p_barcodes) {
             $_ppl_barcodes= $_p_barcodes->_barcode;
              if($_ppl_barcodes !=""){
                 $_price_list_barcode_arrray = explode(",",$_ppl_barcodes);
                 if(sizeof($_price_list_barcode_arrray) > 0){
                      foreach ($_price_list_barcode_arrray as $value) {
                        array_push($_available_barcode_numbers, $value);
                      }
                 }
              }
          }
         
          
          

          foreach ($_all_barcodes as $c_value) {
         
            if(!in_array($c_value, $_available_barcode_numbers)){
               array_push($_over_qty, $c_value);

            }
          }

          if(sizeof($_over_qty) > 0){
            return json_encode($_over_qty); 
          }

        }

        foreach($request->_p_p_l_ids_qtys as $index=> $val){
         $unique_p_q[$val["_p_id"]][]=$val;
        }
        $_id_qty=array();
        foreach ($unique_p_q as $key=>$value) {
            $qty_sum =0;
            foreach ($value as $row) {
                 $qty_sum +=floatval($row["_p_qty"]);
             }
            array_push($_id_qty, ['_id'=>$key,'_qty'=>$qty_sum]);  
        }

        $_over_qty = array();
        if(sizeof($_id_qty) > 0){
            foreach ($_id_qty as $value) {
                $check_qty = ProductPriceList::where('id',$value["_id"])->where('_qty','<',floatval($value["_qty"]))->first();
                if($check_qty){
                    array_push($_over_qty, $value["_id"]);
                }
            }
        }
       

        return json_encode($_over_qty); 
    }
    public function checkAvailableQtyUpdate(Request $request){
       $_over_qty = array();

       $unique_p_ids = $request->unique_p_ids ?? [];
      $_p_ids = implode(",",$unique_p_ids); //Product Price list table ID
      //return $request->_sales_id;
        $previous_sales_details = \DB::select(" SELECT t1._p_p_l_id,t1._item_id,SUM(t1._qty) as _total_qty
FROM (
SELECT s1._p_p_l_id,s1._item_id,sum(s1._qty) as _qty
    FROM sales_details as s1
WHERE s1._no=".$request->_sales_id." GROUP BY s1._p_p_l_id
UNION ALL
SELECT s1.id as _p_p_l_id,s1._item_id,s1._qty 
    FROM product_price_lists AS s1 WHERE s1.id IN(".$_p_ids.")
    ) as t1 GROUP BY t1._p_p_l_id ");

      //  return ($previous_sales_details);

        $unique_p_q = [];
        foreach($request->_p_p_l_ids_qtys as $index=> $val){
         $unique_p_q[$val["_p_id"]][]=$val;
        }
        $_id_qty=array();
        foreach ($unique_p_q as $key=>$value) {
            $qty_sum =0;
            foreach ($value as $row) {
                 $qty_sum +=floatval($row["_p_qty"]);
             }
            array_push($_id_qty, ['_id'=>$key,'_qty'=>$qty_sum]);  
        }

       
       foreach ($previous_sales_details as $value) {
           foreach ($_id_qty as $c_val) {
            
               if($value->_p_p_l_id ==$c_val["_id"]   ){
                if(floatval($value->_total_qty) < floatval($c_val["_qty"])){
                    array_push($_over_qty, $c_val["_id"]);
                }
               }
           }
       }
       
        
        return json_encode($_over_qty); 
    }


   

    

    public function checkAvailableQtyUpdateDamage(Request $request){
      

       $unique_p_ids = $request->unique_p_ids ?? [];
      $_p_ids = implode(",",$unique_p_ids); //Product Price list table ID
      $unique_p_q = [];
         $_over_qty = array();

        $unique_p_ids = $request->unique_p_ids ?? [];
        $_p_ids = implode(",",$unique_p_ids); //Product Price list table ID

      //Unique Barcode Available Check 
         $_all_barcode= $request->_all_barcode ?? '';
        $_all_barcodes = array();
        if($_all_barcode !=''){
          $_all_barcodes = explode(",",$_all_barcode);
        }
       
        $_sales_id = $request->_sales_id ?? 0;
        
        if(sizeof($_all_barcodes) > 0){
          $productPriceListTableData =DB::select(" SELECT  _barcode FROM barcode_details AS s1 WHERE s1._p_p_id IN(".$_p_ids.")  AND s1._status=1
            UNION ALL
            SELECT _barcode FROM damage_barcodes WHERE _no_id=".$_sales_id." AND _status =1
           ");
          $_available_barcode_numbers = [];
          foreach ($productPriceListTableData as $_p_barcodes) {
             $_ppl_barcodes= $_p_barcodes->_barcode;
              if($_ppl_barcodes !=""){
                 $_price_list_barcode_arrray = explode(",",$_ppl_barcodes);
                 if(sizeof($_price_list_barcode_arrray) > 0){
                      foreach ($_price_list_barcode_arrray as $value) {
                        array_push($_available_barcode_numbers, $value);
                      }
                 }
              }
          }
         
          
          

          foreach ($_all_barcodes as $c_value) {
         
            if(!in_array($c_value, $_available_barcode_numbers)){
               array_push($_over_qty, $c_value);

            }
          }

          if(sizeof($_over_qty) > 0){
            return json_encode($_over_qty); 
          }

        }  //End of Wrong barcode check


        $previous_sales_details = \DB::select(" SELECT t1._p_p_l_id,t1._item_id,SUM(t1._qty) as _total_qty
FROM (
SELECT s1._p_p_l_id,s1._item_id,(s1._qty*s1._unit_conversion) as _qty
    FROM damage_adjustment_details as s1
WHERE s1._no=".$request->_sales_id." GROUP BY s1._p_p_l_id
UNION ALL
SELECT s1.id as _p_p_l_id,s1._item_id,s1._qty 
    FROM product_price_lists AS s1 WHERE s1.id IN(".$_p_ids.")
    ) as t1 GROUP BY t1._p_p_l_id ");

        $unique_p_q = [];
        foreach($request->_p_p_l_ids_qtys as $index=> $val){
         $unique_p_q[$val["_p_id"]][]=$val;
        }
        $_id_qty=array();
        foreach ($unique_p_q as $key=>$value) {
            $qty_sum =0;
            foreach ($value as $row) {
                 $qty_sum +=floatval($row["_p_qty"]);
             }
            array_push($_id_qty, ['_id'=>$key,'_qty'=>$qty_sum]);  
        }

       
       foreach ($previous_sales_details as $value) {
           foreach ($_id_qty as $c_val) {
            
               if($value->_p_p_l_id ==$c_val["_id"]   ){
                if(floatval($value->_total_qty) < floatval($c_val["_qty"])){
                    array_push($_over_qty, $c_val["_id"]);
                }
               }
           }
       }
       
        
        return json_encode($_over_qty); 
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = Auth::user();
        $page_name = $this->page_name;
        $account_types = AccountHead::select('id','_name')->orderBy('_name','asc')->get();
        $account_groups = [];
        $branchs = Branch::select('id','_name')->orderBy('_name','asc')->get();
        $permited_branch = permited_branch(explode(',',$users->branch_ids));
        $permited_costcenters = permited_costcenters(explode(',',$users->cost_center_ids));
        $voucher_types = VoucherType::select('id','_name','_code')->orderBy('_code','asc')->get();
        $store_houses = permited_stores(explode(',',$users->store_ids));
        
        $form_settings = SalesFormSetting::first();
        $inv_accounts = [];
        $p_accounts = [];
        $dis_accounts = [];
        $vat_accounts =[];
        $categories = ItemCategory::with(['_parents'])->select('id','_name','_parent_id')->orderBy('_name','asc')->get();
        $units = Units::select('id','_name','_code')->orderBy('_name','asc')->get();
        $_warranties = Warranty::select('id','_name')->orderBy('_name','asc')->where('_status',1)->get();

        $payment_terms = TransectionTerms::where('_status',1)->get();

       return view('backend.sales.create',compact('account_types','page_name','account_groups','branchs','permited_branch','permited_costcenters','voucher_types','store_houses','form_settings','inv_accounts','p_accounts','dis_accounts','vat_accounts','categories','units','_warranties','payment_terms'));
    }

    public function formSettingAjax(){
        $form_settings = SalesFormSetting::first();
        $inv_accounts = AccountLedger::orderBy('_name','asc')->get();
        $p_accounts = $inv_accounts;
        $dis_accounts = $inv_accounts;
        $cost_of_solds = $inv_accounts;
        $_cash_customers = $inv_accounts;
        return view('backend.sales.form_setting_modal',compact('form_settings','inv_accounts','p_accounts','dis_accounts','cost_of_solds','_cash_customers'));
    }


    public function itemSalesSearch(Request $request){
        $users = Auth::user();
        $limit = $request->limit ?? default_pagination();
        $_asc_desc = $request->_asc_desc ?? 'ASC';
        $asc_cloumn =  $request->asc_cloumn ?? '_qty';
       $text_val = trim($request->_text_val);
        if($text_val =='%'){ $text_val=''; }
        
        $datas = DB::select(" SELECT p._order_number, s._name as _store_name, p.id, p._item as _name, p._item_id, p._unit_id, p._barcode,p._warranty, p._manufacture_date,p._unique_barcode, p._expire_date, p._qty, p._sales_rate, p._pur_rate, p._sales_discount, p._sales_vat, p._purchase_detail_id, p._master_id, p._branch_id, p._cost_center_id, p._store_id, p._store_salves_id,un._name as _unit_name
         FROM  product_price_lists as p
         INNER JOIN units as un ON un.id=p._unit_id
         LEFT JOIN store_houses as s ON s.id=p._store_id
           WHERE  p._status = 1 and  (p._barcode like '%$text_val%' OR p._item like '%$text_val%' OR p._order_number like '%$text_val%'  ) and p._branch_id in ($users->branch_ids) AND p._cost_center_id in ($users->cost_center_ids) AND p._store_id in ($users->store_ids) AND p._unique_barcode !=1 AND  p._qty > 0 order by $asc_cloumn $_asc_desc LIMIT $limit ");
        $datas["data"]=$datas;
        return json_encode( $datas);
    }


    public function itemDamageSearch(Request $request){
        $users = Auth::user();
        $limit = $request->limit ?? default_pagination();
        $_asc_desc = $request->_asc_desc ?? 'ASC';
        $asc_cloumn =  $request->asc_cloumn ?? '_qty';
       $text_val = trim($request->_text_val);
        if($text_val =='%'){ $text_val=''; }
        
        $datas = DB::select(" SELECT t1.id, t1._item as _name, t1._item_id, t1._unit_id, t1._barcode,t1._warranty, t1._manufacture_date,t1._unique_barcode, t1._expire_date, t1._qty, t1._sales_rate, t1._pur_rate, t1._sales_discount, t1._sales_vat, t1._purchase_detail_id, t1._master_id, t1._branch_id, t1._cost_center_id, t1._store_id, t1._store_salves_id,un._name as _unit_name
            FROM  product_price_lists AS t1
            INNER JOIN units as un ON un.id=t1._unit_id
              WHERE  t1._status = 1 and  (t1._barcode like '%$text_val%' OR t1._item like '%$text_val%' OR t1.id LIKE '%$text_val%'  ) AND t1._branch_id in ($users->branch_ids) AND t1._cost_center_id in ($users->cost_center_ids) AND   t1._qty > 0 order by t1.$asc_cloumn $_asc_desc LIMIT $limit ");
        $datas["data"]=$datas;
        return json_encode( $datas);
    }

    public function itemDamageSearchEdit(Request $request){
        $users = Auth::user();
        $limit = $request->limit ?? default_pagination();
        $_asc_desc = $request->_asc_desc ?? 'ASC';
        $asc_cloumn =  $request->asc_cloumn ?? '_qty';
       $text_val = trim($request->_text_val);
        if($text_val =='%'){ $text_val=''; }
        
        $datas = DB::select(" select id, _item as _name, _item_id, _unit_id, _barcode,_warranty, _manufacture_date,_unique_barcode, _expire_date, _qty, _sales_rate, _pur_rate, _sales_discount, _sales_vat, _purchase_detail_id, _master_id, _branch_id, _cost_center_id, _store_id, _store_salves_id from product_price_lists where  _status = 1 and  (_barcode like '%$text_val%' OR _item like '%$text_val%' OR id LIKE '%$text_val%'  ) and _branch_id in ($users->branch_ids) and _cost_center_id in ($users->cost_center_ids) AND   _qty> 0 order by _item ASC LIMIT 10 ");
        $datas["data"]=$datas;
        return json_encode( $datas);
    }

    public function itemSalesBarcodeSearch(Request $request){
     // return $request->all();
        $users = Auth::user();
        $limit = $request->limit ?? default_pagination();
        $_asc_desc = $request->_asc_desc ?? 'ASC';
        $asc_cloumn =  $request->asc_cloumn ?? '_qty';
        $text_val = strtolower(trim($request->_text_val));
        if($text_val =='%'){ $text_val=''; }
        $_this_barcode='';

        //First Check Unique Barcode or Model Barcode to compare barcode details table qty
        // if qty =1 then we can deside that's it's an unique barcode then we fetch baroce base all information from product prince list table as we use without barcode version 

        // $check_barcode_types = BarcodeDetail::select('_p_p_id','_item_id','_barcode','_qty')
        //                       ->where('_barcode',$text_val)
        //                       ->where('_status',1) 
        //                       ->where('_qty','>',0)
        //                       ->get();

         

         if($request->has('_pos_sales')){
              $datas = DB::select(" SELECT t1._category_id as _category,t2.id as _row_id,t2._item_id AS _id,t2._item,t2._unit_id AS _unit,t2._sales_rate AS _saleprice,t2._pur_rate AS _purprice,t2.    _sales_vat as _sales_vat,t2._barcode AS _itemcode,t2._qty as available_qty,t2._sales_vat as _vat,t2._sales_discount,t2._unique_barcode,t2._warranty,t2._cost_center_id,t2._purchase_detail_id,t2._branch_id,t2._store_salves_id,t2._pur_rate,un._name as _unit_name  
                    FROM inventories as t1 
                    INNER JOIN product_price_lists AS t2 ON t1.id=t2._item_id
                    INNER JOIN units as un ON un.id=t1._unit_id
                    WHERE  t2._qty > 0 AND t2._status = 1 and  (LOWER(t2._barcode) like '%$text_val%' 
                    OR t2._item like '%$text_val%' OR t2.id LIKE '%$text_val%'  ) and t2._branch_id in ($users->branch_ids) and t2._cost_center_id in ($users->cost_center_ids)   ");            
         }else{

/*$datas = DB::select(" SELECT t1._category_id as _category,t2.id as _row_id,t2._item_id AS _id,t2._item,t2._unit_id AS _unit,t2._sales_rate AS _saleprice,t2._pur_rate AS _purprice,t2.    _sales_vat as _sales_vat,t2._barcode AS _itemcode,t2._qty as available_qty,t2._sales_vat as _vat,t2._sales_discount,t2._unique_barcode,t2._warranty,t2._cost_center_id,t2._purchase_detail_id,t2._branch_id,t2._store_salves_id,t2._pur_rate  FROM inventories as t1 
                    INNER JOIN product_price_lists AS t2 ON t1.id=t2._item_id
                    WHERE  t2._qty > 0 AND t2._status = 1 and  (t2._barcode like '%$text_val%' 
                    OR t2._item like '%$text_val%' OR t2.id LIKE '%$text_val%'  ) and t2._branch_id in ($users->branch_ids) and t2._cost_center_id in ($users->cost_center_ids)   ");  
*/

            //Only Barcode Search

            $datas = DB::select(" SELECT DISTINCT t1.id,t1._unique_barcode, t1._item as _name, t1._item_id, t1._unit_id, t1._barcode,t1._warranty, t1._manufacture_date, t1._expire_date, t1._qty, t1._sales_rate, t1._pur_rate, t1._sales_discount, t1._sales_vat, t1._purchase_detail_id, t1._master_id, t1._branch_id, t1._cost_center_id, t1._store_id, t1._store_salves_id,un._name as _unit_name
             FROM product_price_lists AS t1
             INNER JOIN barcode_details AS t2 ON t1.id=t2._p_p_id
             INNER JOIN units as un ON un.id=t1._unit_id
             WHERE  t1._status = 1 AND    ( LOWER(t2._barcode) LIKE '%$text_val%' 
                    OR t1._item LIKE '%$text_val%' OR t1._item_id LIKE '%$text_val%'  ) AND t1._branch_id in ($users->branch_ids) AND t1._cost_center_id IN ($users->cost_center_ids)  ");
         }

         //$_item_qty  = $check_barcode_types->_qty ?? 0;
        // if(sizeof($check_barcode_types) ==1){ 
        //     $_search_type='unique_barcode';
        // }elseif(sizeof($check_barcode_types) > 1) {
        //   $_search_type='model_barcode';
        // }else{
        //   $_search_type='item_search';
        //   $_this_barcode=$text_val;
        // }



        $_this_barcode=$text_val;
        $data["datas"]=$datas;
        $data["_this_barcode"]=$_this_barcode;
        $data["_search_type"]='unique_barcode';
        return json_encode( $data);
    }


    public function itemSalesEditBarcodeSearch(Request $request){
     // return $request->all();
        $users = Auth::user();
        $limit = $request->limit ?? default_pagination();
        $_asc_desc = $request->_asc_desc ?? 'ASC';
        $asc_cloumn =  $request->asc_cloumn ?? '_qty';
        $_master_id =  $request->_master_id;
       $text_val = trim($request->_text_val);
        if($text_val =='%'){ $text_val=''; }
        $_this_barcode='';

        //First Check Unique Barcode or Model Barcode to compare barcode details table qty
        // if qty =1 then we can deside that's it's an unique barcode then we fetch baroce base all information from product prince list table as we use without barcode version 

        // $check_barcode_types = BarcodeDetail::select('_p_p_id','_item_id','_barcode','_qty')
        //                       ->where('_barcode',$text_val)
        //                       ->where('_status',1) 
        //                       ->where('_qty','>',0)
        //                       ->get();

     


         $datas = DB::select(" SELECT s1.id,s1._master_id, s1._name,s1._item_id,s1._unit_id, s1._barcode,s1._warranty,s1._unique_barcode, s1._manufacture_date, s1._expire_date,  s1._qty,s1._sales_rate, s1._pur_rate,  s1._sales_discount,s1._sales_vat, s1._purchase_detail_id, s1._branch_id, s1._cost_center_id,  s1._store_id,  s1._store_salves_id FROM (
SELECT id,_master_id, _item as _name, _item_id, _unit_id, _barcode,_warranty,_unique_barcode, _manufacture_date, _expire_date, _qty, _sales_rate, _pur_rate, _sales_discount, _sales_vat, _purchase_detail_id, _branch_id, _cost_center_id, _store_id, _store_salves_id 
from product_price_lists
where  _status = 1 and  (_barcode like '%$text_val%' OR _item like '%$text_val%' OR _item_id LIKE '%$text_val%'  ) and _branch_id in ($users->branch_ids) and _cost_center_id in ($users->cost_center_ids) AND  _qty > 0
UNION ALL
SELECT t1._p_p_l_id as id,t1._purchase_invoice_no as _master_id,  t2._item as _name,t1._item_id,t2._unit_id as _unit_id, t1._barcode,t1._warranty,t2._unique_barcode, t1._manufacture_date, t1._expire_date,  t1._qty,t1._sales_rate, t1._rate AS _pur_rate,  t1._discount as _sales_discount, t1._vat AS _sales_vat, t1._purchase_detail_id,   t1._branch_id, t1._cost_center_id,  t1._store_id,  t1._store_salves_id
FROM sales_details AS t1
INNER JOIN inventories AS t2 ON t1._item_id=t2.id
where  t1._status = 1 and  (t1._barcode like '%$text_val%' OR t2._item like '%$text_val%' OR t2.id LIKE '%$text_val%'  ) and t1._branch_id in ($users->branch_ids) and t1._cost_center_id in ($users->cost_center_ids) AND  t1._qty> 0
   )  AS s1   ORDER BY s1._name ASC LIMIT $limit ");

        
    $_search_type='item_search';
         


        $_this_barcode=$text_val;
        $data["datas"]=$datas;
        $data["_search_type"]=$_search_type;
        $data["_this_barcode"]=$_this_barcode;
        return json_encode( $data);
    }


    public function Settings (Request $request){
        $data = SalesFormSetting::first();
        if(empty($data)){
            $data = new SalesFormSetting();
        }

        $_cash_ledger_ids = 0;
        $_cash_customer = $request->_cash_customer ?? [];
        if(sizeof($_cash_customer) > 0){
            $_cash_ledger_ids  =  implode(",",$_cash_customer);
        }
        
        $data->_default_inventory = $request->_default_inventory;
        $data->_default_sales = $request->_default_sales;
        $data->_default_discount = $request->_default_discount;
        $data->_default_cost_of_solds = $request->_default_cost_of_solds;
        $data->_default_sd_account = $request->_default_sd_account;
        $data->_show_barcode = $request->_show_barcode;
        $data->_show_vat = $request->_show_vat;
        $data->_show_store = $request->_show_store;
        $data->_show_self = $request->_show_self;
        $data->_default_vat_account = $request->_default_vat_account;
        $data->_inline_discount = $request->_inline_discount ?? 1;
        $data->_show_delivery_man = $request->_show_delivery_man ?? 1;
        $data->_show_sales_man = $request->_show_sales_man ?? 1;
        $data->_show_cost_rate = $request->_show_cost_rate ?? 1;
        $data->_show_payment_terms = $request->_show_payment_terms ?? 1;
        $data->_show_manufacture_date = $request->_show_manufacture_date ?? 1;
        $data->_show_expire_date = $request->_show_expire_date ?? 1;
        $data->_show_p_balance = $request->_show_p_balance ?? 1;
        $data->_invoice_template = $request->_invoice_template ?? 1;
        $data->_cash_customer = $_cash_ledger_ids ?? 0;
        $data->_show_warranty =$request->_show_warranty ?? 0;
        $data->_show_unit =$request->_show_unit ?? 0;
        $data->_defaut_customer =$request->_defaut_customer ?? 0;
        $data->_show_due_history =$request->_show_due_history ?? 0;
        $data->_show_ar_date =$request->_show_ar_date ?? 0;
        $data->_show_dis_date =$request->_show_dis_date ?? 0;
        $data->_show_vn =$request->_show_vn ?? 0;
        $data->_show_expected_qty =$request->_show_expected_qty ?? 0;
        $data->_show_sd =$request->_show_sd ?? 0;
        $data->_show_loding_point =$request->_show_loding_point ?? 0;
        $data->_show_unloading_point =$request->_show_unloading_point ?? 0;






        $data->_is_header =$request->_is_header ?? 1;
        $data->_is_footer =$request->_is_footer ?? 1;
        $data->_margin_top =$request->_margin_top ?? "0px";
        $data->_margin_bottom =$request->_margin_bottom ?? "0px";
        $data->_margin_left =$request->_margin_left ?? "0px";
        $data->_margin_right =$request->_margin_right ?? "0px";

        if($request->hasFile('_seal_image')){ 
                $_seal_image = UserImageUpload($request->_seal_image); 
                $data->_seal_image = $_seal_image;
        }
        $data->save();


        return redirect()->back();
                       

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        //return dump($request->all());
         $all_req= $request->all();
         $this->validate($request, [
            '_date' => 'required',
            '_branch_id' => 'required',
            '_main_ledger_id' => 'required',
            '_form_name' => 'required'
        ]);
    //###########################
    // Purchase Master information Save Start
    //###########################
    $SalesFormSetting = SalesFormSetting::first();
    //_cash_customer_check($_cutomer_id,$_selected_customers,$_bill_amount,$_total)
  $check_cash_customers=  _cash_customer_check($request->_main_ledger_id,$SalesFormSetting->_cash_customer,$request->_total,$request->_total_dr_amount);
  if($check_cash_customers=='no'){
     return redirect()->back()
     ->with('request',$request->all())
     ->with('error','Cash Customer Must Paid Full Amount!');
  }

        $_item_ids = $request->_item_id;
        $_barcodes = $request->_barcode ?? [];
        $_qtys = $request->_qty;
        $_rates = $request->_rate;
        $_sales_rates = $request->_sales_rate;
        $_vats = $request->_vat;
        $_vat_amounts = $request->_vat_amount;
        $_values = $request->_value;
        $_main_branch_id_detail = $request->_main_branch_id_detail;
        $_main_cost_center = $request->_main_cost_center;
        $_store_ids = $request->_main_store_id;
        $_store_salves_ids = $request->_store_salves_id;
        $_p_p_l_ids = $request->_p_p_l_id;
        $_purchase_invoice_nos = $request->_purchase_invoice_no;
        $_purchase_detail_ids = $request->_purchase_detail_id;
        $_discounts = $request->_discount;
        $_discount_amounts = $request->_discount_amount;
        $_manufacture_dates = $request->_manufacture_date;
        $_expire_dates = $request->_expire_date;
        $_ref_counters = $request->_ref_counter;
        $_warrantys = $request->_warranty;
        $organization_id = $request->organization_id ?? 1;


        $_base_unit_ids = $request->_base_unit_id ?? [];
        $conversion_qtys = $request->conversion_qty ?? [];
        $_transection_units = $request->_transection_unit ?? [];
        $_expected_qtys = $request->_expected_qty ?? [];

      DB::beginTransaction();
        try {

            $_p_balance = _l_balance_update($request->_main_ledger_id);

         $_sales_man_id = $request->_sales_man_id;
         $sales_man_name_leder = $request->sales_man_name_leder;
         $_delivery_man_id = $request->_delivery_man_id;
         $delivery_man_name_leder = $request->delivery_man_name_leder;
        
         $__sub_total = (float) $request->_sub_total;
         $__total = (float) $request->_total;
         $__discount_input = (float) $request->_discount_input;
         $__total_discount = (float) $request->_total_discount;

       $_print_value = $request->_print ?? 0;
         $users = Auth::user();
        $Sales = new Sales();
        $Sales->_date = change_date_format($request->_date);
        $Sales->_time = date('H:i:s');
        $Sales->_order_ref_id = $request->_order_ref_id;
        $Sales->_order_number = $request->_order_number ?? '';
        $Sales->_referance = $request->_referance;
        $Sales->_ledger_id = $request->_main_ledger_id;
        $Sales->_user_id = $users->id;
        $Sales->_created_by = $users->id."-".$users->name;
        $Sales->_user_id = $users->id;
        $Sales->_user_name = $users->name;
        $Sales->_note = $request->_note;
        $Sales->_payment_terms = $request->_payment_terms ?? 1;
        $Sales->_sub_total = $__sub_total;
        $Sales->_discount_input = $__discount_input;
        $Sales->_total_discount = $__total_discount;
        $Sales->_total_vat = $request->_total_vat;
        $Sales->_total =  $__total;
        $Sales->organization_id = $organization_id;
        $Sales->_branch_id = $request->_branch_id;
        $Sales->_cost_center_id = $request->_cost_center_id;
        $Sales->_address = $request->_address;
        $Sales->_phone = $request->_phone;
        $Sales->_delivery_details = $request->_delivery_details ?? '';

        $Sales->_direct_purchase_no = $request->_direct_purchase_no ?? '';
        $Sales->_is_direct_sales = $request->_is_direct_sales ?? 1;
        
        
        $Sales->_vessel_no = $request->_vessel_no ?? 0;
        $Sales->_arrival_date_time = $request->_arrival_date_time ?? '';
        $Sales->_discharge_date_time = $request->_discharge_date_time ?? '';
        $Sales->_loding_point = $request->_loding_point ?? '';
        $Sales->_unloading_point = $request->_unloading_point ?? '';

        $Sales->_sd_input = $request->_sd_input ?? 0;
        $Sales->_total_sd_amount = $request->total_sd_amount ?? 0;


        $Sales->_delivery_man_id = $request->_delivery_man_id ?? 0;
        $Sales->_sales_man_id = $request->_sales_man_id ?? 0;
       
        $Sales->_sales_type = $request->_sales_type ?? 'sales';
        $Sales->_status = 1;
        $Sales->_lock = $request->_lock ?? 0;

        $Sales->save();
        $_master_id = $Sales->id;             

        //###########################
        // Purchase Master information Save End
        //###########################

        //###########################
        // Purchase Details information Save Start
        //###########################
        
       
        $_total_cost_value=0;

        if(sizeof($_item_ids) > 0){
            for ($i = 0; $i <sizeof($_item_ids) ; $i++) {
                $_total_cost_value += (($_rates[$i]*$conversion_qtys[$i] ?? 1)*$_qtys[$i]);

               // $_base_rate = ($_values[$i]/($_qtys[$i]*$conversion_qtys[$i] ?? 1));
                 $converted_l=($_qtys[$i]*$conversion_qtys[$i] ?? 1);
                if($converted_l ==0){$converted_l=1;};
                $_base_rate = ($_values[$i]/$converted_l);


                $SalesDetail = new SalesDetail();
                $SalesDetail->_item_id = $_item_ids[$i];
                $SalesDetail->_p_p_l_id = $_p_p_l_ids[$i];
                $SalesDetail->_purchase_invoice_no = $_purchase_invoice_nos[$i];
                $SalesDetail->_purchase_detail_id = $_purchase_detail_ids[$i];
                $SalesDetail->_qty = $_qtys[$i];
                $SalesDetail->_expected_qty = $_expected_qtys[$i] ?? 0;

                $SalesDetail->_transection_unit = $_transection_units[$i] ?? 1;
                $SalesDetail->_unit_conversion = $conversion_qtys[$i] ?? 1;
                $SalesDetail->_base_unit = $_base_unit_ids[$i] ?? 1;
                $SalesDetail->_base_rate = $_base_rate;
                $SalesDetail->_rate = $_rates[$i];

                $barcode_string=$all_req[$_ref_counters[$i]."__barcode__".$_p_p_l_ids[$i]] ?? '';
                $SalesDetail->_barcode = $barcode_string;

                $SalesDetail->_manufacture_date = $_manufacture_dates[$i];
                $SalesDetail->_expire_date = $_expire_dates[$i];
                $SalesDetail->_warranty = $_warrantys[$i] ?? 0;
                $SalesDetail->_sales_rate = $_sales_rates[$i];
                $SalesDetail->_discount = $_discounts[$i] ?? 0;
                $SalesDetail->_discount_amount = $_discount_amounts[$i] ?? 0;
                $SalesDetail->_vat = $_vats[$i] ?? 0;
                $SalesDetail->_vat_amount = $_vat_amounts[$i] ?? 0;
                $SalesDetail->_value = $_values[$i] ?? 0;
                $SalesDetail->_store_id = $_store_ids[$i] ?? 1;
                $SalesDetail->_cost_center_id = $_main_cost_center[$i] ?? 1;
                $SalesDetail->_store_salves_id = $_store_salves_ids[$i] ?? '';
                $SalesDetail->organization_id = $organization_id;
                $SalesDetail->_branch_id = $_main_branch_id_detail[$i] ?? 1;
                $SalesDetail->_no = $_master_id;
                $SalesDetail->_status = 1;
                $SalesDetail->_created_by = $users->id."-".$users->name;
                $SalesDetail->save();
                $_sales_details_id = $SalesDetail->id;

                $item_info = Inventory::where('id',$_item_ids[$i])->first();
                $ProductPriceList = ProductPriceList::find($_p_p_l_ids[$i]);
                $_p_qty = $ProductPriceList->_qty;
                $_unique_barcode = $ProductPriceList->_unique_barcode;
                //Barcode  deduction from old string data
                if($_unique_barcode ==1){
                     $_old_barcode_strings =  $ProductPriceList->_barcode;
                        $_new_barcode_array = array();
                        if($_old_barcode_strings !=""){
                            $_old_barcode_array = explode(",",$_old_barcode_strings);
                        }
                        if($barcode_string !=""){
                            $_new_barcode_array = explode(",",$barcode_string);
                        }
                        if(sizeof($_new_barcode_array) > 0 && sizeof($_old_barcode_array) > 0){
                          $_last_barcode_array =  array_diff($_old_barcode_array,$_new_barcode_array);
                          if(sizeof($_last_barcode_array ) > 0){
                            $_last_barcode_string = implode(",",$_last_barcode_array);
                          }else{
                            $_last_barcode_string = $barcode_string;
                          }
                          
                          $ProductPriceList->_barcode = $_last_barcode_string;
                        }
                }else{
                  $ProductPriceList->_barcode = $barcode_string;
                }
                //Barcode  deduction from old string data


                // $_status = (($_p_qty - $_qtys[$i]) > 0) ? 1 : 0;
                // $ProductPriceList->_qty = ($_p_qty - $_qtys[$i]);
                // $ProductPriceList->_status = $_status;
                // $ProductPriceList->save();

                $_status = (($_p_qty - ($_qtys[$i]* $conversion_qtys[$i] ?? 1)) > 0) ? 1 : 0;
                $ProductPriceList->_qty = ($_p_qty - ($_qtys[$i]* $conversion_qtys[$i] ?? 1));
                $ProductPriceList->_status = $_status;
                $ProductPriceList->save();


/***************************************
   Barcode insert into database section
   _barcode_insert_update($modelName, $_p_p_id,$_item_id,$_no_id,$_no_detail_id,$_qty,$_barcode,$_status,$_return=0,$p=0)
   IF RETURN ACTION THEN $_return = 1; and BarcodeDetail avoid 
   [  $data->_no_id = $_no_id; $data->_no_detail_id = $_no_detail_id; ] use  $p=1;
**************************************************/
                 $product_price_id =  $ProductPriceList->id;
             if($_unique_barcode ==1){
                  if($barcode_string !=""){
                       $barcode_array=  explode(",",$barcode_string);
                       $_qty = 1;
                       $_stat = 1;
                       $_return=1;
                       
                       foreach ($barcode_array as $_b_v) {
                        _barcode_insert_update('BarcodeDetail', $product_price_id,$_item_ids[$i],$_master_id,$_sales_details_id,$_qty,$_b_v,$_stat,1,1);
                        _barcode_insert_update('SalesBarcode', $product_price_id,$_item_ids[$i],$_master_id,$_sales_details_id,$_qty,$_b_v,$_stat,0,0);
                         
                       }
                    }
             }
                 

                
/*
    Barcode insert into database section
*/

                $ItemInventory = new ItemInventory();
                $ItemInventory->_item_id =  $_item_ids[$i];
                $ItemInventory->_item_name =  $item_info->_item ?? '';
                 $ItemInventory->_unit_id =  $item_info->_unit_id ?? '';
                $ItemInventory->_category_id = _item_category($_item_ids[$i]);
                $ItemInventory->_date = change_date_format($request->_date);
                $ItemInventory->_time = date('H:i:s');
                $ItemInventory->_transection = "Sales";
                $ItemInventory->_transection_ref = $_master_id;
                $ItemInventory->_transection_detail_ref_id = $_sales_details_id;

                $ItemInventory->_qty = -($_qtys[$i] * $conversion_qtys[$i] ?? 1);
                $ItemInventory->_rate =( $_sales_rates[$i]/$conversion_qtys[$i] ?? 1);
                $ItemInventory->_cost_rate = $_rates[$i] ?? 0;
                $ItemInventory->_cost_value = (($_qtys[$i]*$conversion_qtys[$i] ?? 1)*$_rates[$i]);

                // $ItemInventory->_qty = -($_qtys[$i]*$conversion_qtys[$i] ?? 1);
                // $ItemInventory->_rate = $_sales_rates[$i];
                // $ItemInventory->_cost_rate = ($_rates[$i] / $conversion_qtys[$i] ?? 1);
                // $ItemInventory->_cost_value = (($_qtys[$i]*$conversion_qtys[$i] ?? 1)*$_rates[$i]);
                  //Unit Conversion section
                $ItemInventory->_transection_unit = $_transection_units[$i] ?? 1;
                $ItemInventory->_unit_conversion = $conversion_qtys[$i] ?? 1;
                $ItemInventory->_base_unit = $item_info->_unit_id ?? 1;
                $ItemInventory->_unit_id = $item_info->_unit_id ?? 1;

                $ItemInventory->_manufacture_date = $_manufacture_dates[$i];
                $ItemInventory->_expire_date = $_expire_dates[$i];
                $ItemInventory->_value = $_values[$i] ?? 0;
                $ItemInventory->organization_id = $organization_id;
                $ItemInventory->_branch_id = $_main_branch_id_detail[$i] ?? 1;
                $ItemInventory->_store_id = $_store_ids[$i] ?? 1;
                $ItemInventory->_cost_center_id = $_main_cost_center[$i] ?? 1;
                $ItemInventory->_store_salves_id = $_store_salves_ids[$i] ?? '';
                $ItemInventory->_status = 1;
                $ItemInventory->_created_by = $users->id."-".$users->name;
                $ItemInventory->save(); 
                inventory_stock_update($_item_ids[$i]);
            }
        }

        //###########################
        // Purchase Details information Save End
        //###########################

        //###########################
        // Purchase Account information Save End
        //###########################
        $_total_dr_amount = 0;
        $_total_cr_amount = 0;

        
        $_default_inventory = $SalesFormSetting->_default_inventory;
        $_default_sales = $SalesFormSetting->_default_sales;
        $_default_discount = $SalesFormSetting->_default_discount;
        $_default_vat_account = $SalesFormSetting->_default_vat_account;
        $_default_cost_of_solds = $SalesFormSetting->_default_cost_of_solds;

        $_ref_master_id=$_master_id;
        $_ref_detail_id=$_master_id;
        $_short_narration='N/A';
        $_narration = $request->_note;
        $_reference= $request->_referance;
        $_transaction= 'Sales';
        $_date = change_date_format($request->_date);
        $_table_name = $request->_form_name;
        $_branch_id = $request->_branch_id;
        $_cost_center =  $request->_cost_center_id ?? 1;
        $_name =$users->name;
        
        if($__sub_total > 0){

            //#################
            // Account Receiveable Dr.
            //      Sales Cr
            //#################

            //Default Sales DR.
            account_data_save($_ref_master_id,$_ref_detail_id,_find_ledger($_default_sales),$_narration,$_reference,$_transaction,$_date,$_table_name,$request->_main_ledger_id,$__sub_total,0,$_branch_id,$_cost_center,$_name,1,$organization_id);
           //Default Account Receivable  Cr.
            account_data_save($_ref_master_id,$_ref_detail_id,_find_ledger($request->_main_ledger_id),$_narration,$_reference,$_transaction,$_date,$_table_name,$_default_sales,0,$__sub_total,$_branch_id,$_cost_center,$_name,2,$organization_id);

            //#################
            // Cost of Goods Sold Dr.
            //      Inventory  Cr
            //#################

            //Cost of Goods Sold Dr.
            account_data_save($_ref_master_id,$_ref_detail_id,_find_ledger($_default_inventory),$_narration,$_reference,$_transaction,$_date,$_table_name,$_default_cost_of_solds,$_total_cost_value,0,$_branch_id,$_cost_center,$_name,3,$organization_id);
            //Inventory  Cr
            account_data_save($_ref_master_id,$_ref_detail_id,_find_ledger($_default_cost_of_solds),$_narration,$_reference,$_transaction,$_date,$_table_name,$_default_inventory,0,$_total_cost_value,$_branch_id,$_cost_center,$_name,4,$organization_id);
        }

        if($__total_discount > 0){
             //#################
            // Sales Discount Dr.
            //      Account Receivable  Cr
            //#################
            //Default Discount
            account_data_save($_ref_master_id,$_ref_detail_id,_find_ledger($request->_main_ledger_id),$_narration,$_reference,$_transaction,$_date,$_table_name,$_default_discount,$__total_discount,0,$_branch_id,$_cost_center,$_name,5,$organization_id);
            //  Account Receivable  Cr
            account_data_save($_ref_master_id,$_ref_detail_id,_find_ledger($_default_discount),$_narration,$_reference,$_transaction,$_date,$_table_name,$request->_main_ledger_id,0,$__total_discount,$_branch_id,$_cost_center,$_name,6,$organization_id);
             
        
        }
         $__total_vat = (float) $request->_total_vat ?? 0;
        if($__total_vat > 0){
             //#################
            // Account Receivable Dr.
            //      Vat  Cr
            //#################
            //Default Vat Account
            account_data_save($_ref_master_id,$_ref_detail_id,_find_ledger($_default_vat_account),$_narration,$_reference,$_transaction,$_date,$_table_name,$request->_main_ledger_id,$request->_total_vat,0,$_branch_id,$_cost_center,$_name,7,$organization_id);
            //Account Receivable
            account_data_save($_ref_master_id,$_ref_detail_id,_find_ledger($request->_main_ledger_id),$_narration,$_reference,$_transaction,$_date,$_table_name,$_default_vat_account,0,$request->_total_vat,$_branch_id,$_cost_center,$_name,8,$organization_id);
        
        }

       

        $_ledger_id = (array) $request->_ledger_id;
        $_short_narr = (array) $request->_short_narr;
        $_dr_amount = (array) $request->_dr_amount;
         $_cr_amount = (array) $request->_cr_amount;
        $_branch_id_detail = (array) $request->_branch_id_detail;
        $_cost_center = (array) $request->_cost_center;
       
        if(sizeof($_ledger_id) > 0){

                foreach($_ledger_id as $i=>$ledger) {
                    if($ledger !=""){
                       
                     // echo  $_cr_amount[$i];
                        $_account_type_id =  ledger_to_group_type($ledger)->_account_head_id;
                        $_account_group_id =  ledger_to_group_type($ledger)->_account_group_id;

                        $_total_dr_amount += $_dr_amount[$i] ?? 0;
                        $_total_cr_amount += $_cr_amount[$i] ?? 0;

                        $SalesAccount = new SalesAccount();
                        $SalesAccount->_no = $_master_id;
                        $SalesAccount->_account_type_id = $_account_type_id;
                        $SalesAccount->_account_group_id = $_account_group_id;
                        $SalesAccount->_ledger_id = $ledger;
                        $SalesAccount->_cost_center = $_cost_center[$i] ?? 0;
                        $SalesAccount->organization_id = $organization_id;
                        $SalesAccount->_branch_id = $_branch_id_detail[$i] ?? 0;
                        $SalesAccount->_short_narr = $_short_narr[$i] ?? 'N/A';
                        $SalesAccount->_dr_amount = $_dr_amount[$i];
                        $SalesAccount->_cr_amount = $_cr_amount[$i];
                        $SalesAccount->_status = 1;
                        $SalesAccount->_created_by = $users->id."-".$users->name;
                        $SalesAccount->save();

                        $_sales_account_id = $SalesAccount->id;

                        //Reporting Account Table Data Insert
                        $_ref_master_id=$_master_id;
                        $_ref_detail_id=$_sales_account_id;
                        $_short_narration=$_short_narr[$i] ?? 'N/A';
                        $_narration = $request->_note;
                        $_reference= $request->_referance;
                        $_transaction= 'Sales';
                        $_date = change_date_format($request->_date);
                        $_table_name ='sales_accounts';
                        $_account_ledger = $_ledger_id[$i];
                        $_dr_amount_a = $_dr_amount[$i] ?? 0;
                        $_cr_amount_a = $_cr_amount[$i] ?? 0;
                        $_branch_id_a = $_branch_id_detail[$i] ?? 0;
                        $_cost_center_a = $_cost_center[$i] ?? 0;
                        $_name =$users->name;
                        account_data_save($_ref_master_id,$_ref_detail_id,$_short_narration,$_narration,$_reference,$_transaction,$_date,$_table_name,$_account_ledger,$_dr_amount_a,$_cr_amount_a,$_branch_id_a,$_cost_center_a,$_name,(9+$i),$organization_id);
                          
                    }


                }
            
                //Only Cash and Bank receive in account detail. This entry set automatically by program.
                if($_total_dr_amount > 0 && $users->_ac_type==1){
                         $_account_type_id =  ledger_to_group_type($request->_main_ledger_id)->_account_head_id;
                        $_account_group_id =  ledger_to_group_type($request->_main_ledger_id)->_account_group_id;
                        $SalesAccount = new SalesAccount();
                        $SalesAccount->_no = $_master_id;
                        $SalesAccount->_account_type_id = $_account_type_id;
                        $SalesAccount->_account_group_id = $_account_group_id;
                        $SalesAccount->_ledger_id = $request->_main_ledger_id;
                        $SalesAccount->_cost_center = $users->cost_center_ids;
                        $SalesAccount->organization_id = $organization_id;
                        $SalesAccount->_branch_id = $users->branch_ids;
                        $SalesAccount->_short_narr = 'Sales Payment';
                        $SalesAccount->_dr_amount = 0;
                        $SalesAccount->_cr_amount = $_total_dr_amount;
                        $SalesAccount->_status = 1;
                        $SalesAccount->_created_by = $users->id."-".$users->name;
                        $SalesAccount->save();

 
                        $_sales_account_id = $SalesAccount->id;

                        //Reporting Account Table Data Insert
                        $_ref_master_id=$_master_id;
                        $_ref_detail_id=$_sales_account_id;
                        $_short_narration='Sales Payment';
                        $_narration = $request->_note;
                        $_reference= $request->_referance;
                        $_transaction= 'Sales';
                        $_date = change_date_format($request->_date);
                        $_table_name ='sales_accounts';
                        $_account_ledger = $request->_main_ledger_id;
                        $_dr_amount_a = 0;
                        $_cr_amount_a = $_total_dr_amount ?? 0;
                        $_branch_id_a = $users->branch_ids;
                        $_cost_center_a = $users->cost_center_ids;
                        $_name =$users->name;
                        account_data_save($_ref_master_id,$_ref_detail_id,$_short_narration,$_narration,$_reference,$_transaction,$_date,$_table_name,$_account_ledger,$_dr_amount_a,$_cr_amount_a,$_branch_id_a,$_cost_center_a,$_name,(20),$organization_id);
                }
            }

          $_l_balance = _l_balance_update($request->_main_ledger_id);
            //$_pfix = _sales_pfix().$_master_id;

            $_main_branch_id = $request->_branch_id;
            $__table="sales";
            $_pfix = _sales_pfix().make_order_number($__table,$organization_id,$_main_branch_id);

             \DB::table('sales')
             ->where('id',$_master_id)
             ->update(['_p_balance'=>$_p_balance,'_l_balance'=>$_l_balance,'_order_number'=>$_pfix]);

               //SMS SEND to Customer and Supplier
             $_send_sms = $request->_send_sms ?? '';
             if($_send_sms=='yes'){
                $_name = _ledger_name($request->_main_ledger_id);
                $_phones = $request->_phone;
                $messages = "Dear ".$_name.", Date: ".$request->_date." Invoice N0.".$_pfix." Invoice Amount: ".prefix_taka()."."._report_amount($request->_total).". Payment Amount. ".prefix_taka()."."._report_amount($_total_dr_amount).". Previous Balance ".prefix_taka()."."._report_amount($_p_balance).". Current Balance:".prefix_taka()."."._report_amount($_l_balance);
                  sms_send($messages, $_phones);
             }
             //End Sms Send to customer and Supplier

             $print_url=url('sales/print')."/".$_master_id;
             $success_message= "Information Save successfully. <a target='__blank' style='color:red;' href='".$print_url."'><i class='fas fa-print'></i></a>";

           DB::commit();
            return redirect()->back()
                ->with('success',$success_message)
                ->with('_master_id',$_master_id)
                ->with('_print_value',$_print_value)
                ->with('_sales_man_id',$_sales_man_id)
                ->with('sales_man_name_leder',$sales_man_name_leder)
                ->with('_delivery_man_id',$_delivery_man_id)
                ->with('delivery_man_name_leder',$delivery_man_name_leder);
       } catch (\Exception $e) {
           DB::rollback();
           return redirect()->back()
           ->with('request',$request->all())
           ->with('danger','There is Something Wrong !');
        }

       
    }


  


public function Print($id){
        $users = Auth::user();
        $page_name = $this->page_name;
        $permited_branch = permited_branch(explode(',',$users->branch_ids));
        $permited_costcenters = permited_costcenters(explode(',',$users->cost_center_ids));
       
        $data =  Sales::with(['_master_branch','_master_details','s_account','_ledger','_terms_con','_organization'])->find($id);
        $form_settings = SalesFormSetting::first();
           $permited_branch = permited_branch(explode(',',$users->branch_ids));
        $permited_costcenters = permited_costcenters(explode(',',$users->cost_center_ids));
         
$store_houses = permited_stores(explode(',',$users->store_ids));
         $_master_details_lot_wise = $data->_master_details ?? [];
         $_master_detail_reassign = [];
         $old_item_id_price = [];
         foreach ($_master_details_lot_wise as $key => $value) {
            $id_prince = $value->_item_id."__".$value->_sales_rate;
             if(in_array($id_prince, $old_item_id_price)){
                $_master_detail_reassign[$id_prince][]=$value;
             }else{
                $_master_detail_reassign[$id_prince][]=$value;
                array_push($old_item_id_price, $id_prince);
             }
         }

         //return $_master_detail_reassign;



         $_l_balance_update = _l_balance_update($data->_ledger_id);

         $total_sales = Sales::where('_ledger_id', $data->_ledger_id)
                                                        ->where('_status',1)
                                                        ->sum('_total');
 
        $history_sales_invoices = [];
        $row_conter=0;
                if($total_sales >= $_l_balance_update ){
                    //return $_l_balance_update;
                //if($_l_balance_update > 0 ){
                 //if last balance gretter then 0 then go ahead
                        $_avoid_sales_ids =[];
                        $available_quantity =  0;
                         $_qty_less = $_l_balance_update;
                        do {

                            
                            if ($available_quantity < $_l_balance_update) {
                               // return $available_quantity;
                                 $due_sales_info = Sales::select('id','_date','_order_number','_total')
                                                    ->where('_ledger_id', $data->_ledger_id)
                                                    ->where('_total','>',0)
                                                    ->where('_status',1)
                                                    ->whereNotIn('id', $_avoid_sales_ids)
                                                    ->orderBy('id','DESC')
                                                    ->first();
                                if($due_sales_info){
                                      array_push($_avoid_sales_ids, $due_sales_info->id);

                                       $available_quantity +=$due_sales_info->_total ?? 0;

                                      if($available_quantity  >= $_l_balance_update  ){
                                        $_less_qty = ($due_sales_info->_total -( $available_quantity-$_l_balance_update )); //Last Need this qty
                                         $new_qty = $_less_qty;
                                         $due_sales_info->_due_amount = $new_qty;
                                         array_push($history_sales_invoices, $due_sales_info);
                                         
                                        }else{
                                            $due_sales_info->_due_amount = $due_sales_info->_total ?? 0;
                                            array_push($history_sales_invoices, $due_sales_info);
                                        }    
                                }
                                                            
                            }
                        } while ($available_quantity < $_l_balance_update);
                }
         
   
          
         //return $history_sales_invoices;     
           



Sales::where('id',$id)->update(['_print_counter'=>($data->_print_counter+1)]);



         if($form_settings->_invoice_template==1){
            return view('backend.sales.print',compact('page_name','permited_branch','permited_costcenters','data','form_settings','permited_branch','permited_costcenters','store_houses','history_sales_invoices','_master_detail_reassign'));
         }elseif($form_settings->_invoice_template==2){
            return view('backend.sales.print_1',compact('page_name','permited_branch','permited_costcenters','data','form_settings','permited_branch','permited_costcenters','store_houses','history_sales_invoices','_master_detail_reassign'));
         }elseif($form_settings->_invoice_template==3){
            return view('backend.sales.print_2',compact('page_name','permited_branch','permited_costcenters','data','form_settings','permited_branch','permited_costcenters','store_houses','history_sales_invoices','_master_detail_reassign'));
         }elseif($form_settings->_invoice_template==4){
            return view('backend.sales.print_3',compact('page_name','permited_branch','permited_costcenters','data','form_settings','permited_branch','permited_costcenters','store_houses','history_sales_invoices','_master_detail_reassign'));
         }elseif($form_settings->_invoice_template==6){
            return view('backend.sales.print_4',compact('page_name','permited_branch','permited_costcenters','data','form_settings','permited_branch','permited_costcenters','store_houses','history_sales_invoices','_master_detail_reassign'));
         }elseif($form_settings->_invoice_template==5){
           // return $data;
            return view('backend.sales.pos_template',compact('page_name','permited_branch','permited_costcenters','data','form_settings','permited_branch','permited_costcenters','store_houses','history_sales_invoices','_master_detail_reassign'));
         }else{
            return view('backend.sales.print',compact('page_name','permited_branch','permited_costcenters','data','form_settings','permited_branch','permited_costcenters','store_houses','history_sales_invoices','_master_detail_reassign'));
         }
       
    }

public function mushakSixThree($id){
        $users = Auth::user();
        $page_name = $this->page_name;
        $permited_branch = permited_branch(explode(',',$users->branch_ids));
        $permited_costcenters = permited_costcenters(explode(',',$users->cost_center_ids));
       
        $data =  Sales::with(['_master_branch','_master_details','s_account','_ledger','_terms_con'])->find($id);

         $organization_info = Company::find($data->organization_id);

        $form_settings = SalesFormSetting::first();
        $permited_branch = permited_branch(explode(',',$users->branch_ids));
        $permited_costcenters = permited_costcenters(explode(',',$users->cost_center_ids));
         
$store_houses = permited_stores(explode(',',$users->store_ids));
         $_master_details_lot_wise = $data->_master_details ?? [];
         $_master_detail_reassign = [];
         $old_item_id_price = [];
         foreach ($_master_details_lot_wise as $key => $value) {
            $id_prince = $value->_item_id."__".$value->_sales_rate;
             if(in_array($id_prince, $old_item_id_price)){
                $_master_detail_reassign[$id_prince][]=$value;
             }else{
                $_master_detail_reassign[$id_prince][]=$value;
                array_push($old_item_id_price, $id_prince);
             }
         }

         //return $_master_detail_reassign;



         $_l_balance_update = _l_balance_update($data->_ledger_id);

         $total_sales = Sales::where('_ledger_id', $data->_ledger_id)
                                                        ->where('_status',1)
                                                        ->sum('_total');
 
        $history_sales_invoices = [];
        $row_conter=0;
                if($total_sales >= $_l_balance_update ){
                    //return $_l_balance_update;
                //if($_l_balance_update > 0 ){
                 //if last balance gretter then 0 then go ahead
                        $_avoid_sales_ids =[];
                        $available_quantity =  0;
                         $_qty_less = $_l_balance_update;
                        do {

                            
                            if ($available_quantity < $_l_balance_update) {
                               // return $available_quantity;
                                 $due_sales_info = Sales::select('id','_date','_order_number','_total')
                                                    ->where('_ledger_id', $data->_ledger_id)
                                                    ->where('_total','>',0)
                                                    ->where('_status',1)
                                                    ->whereNotIn('id', $_avoid_sales_ids)
                                                    ->orderBy('id','DESC')
                                                    ->first();
                                if($due_sales_info){
                                      array_push($_avoid_sales_ids, $due_sales_info->id);

                                       $available_quantity +=$due_sales_info->_total ?? 0;

                                      if($available_quantity  >= $_l_balance_update  ){
                                        $_less_qty = ($due_sales_info->_total -( $available_quantity-$_l_balance_update )); //Last Need this qty
                                         $new_qty = $_less_qty;
                                         $due_sales_info->_due_amount = $new_qty;
                                         array_push($history_sales_invoices, $due_sales_info);
                                         
                                        }else{
                                            $due_sales_info->_due_amount = $due_sales_info->_total ?? 0;
                                            array_push($history_sales_invoices, $due_sales_info);
                                        }    
                                }
                                                            
                            }
                        } while ($available_quantity < $_l_balance_update);
                }
         
   
          
         //return $history_sales_invoices;     
           







        
            return view('backend.sales.mushak_6_3',compact('page_name','permited_branch','permited_costcenters','data','form_settings','permited_branch','permited_costcenters','store_houses','history_sales_invoices','_master_detail_reassign'));
         
       
    }

    public function challanPrint($id){
        $users = Auth::user();
        $page_name = $this->page_name;
        $permited_branch = permited_branch(explode(',',$users->branch_ids));
        $permited_costcenters = permited_costcenters(explode(',',$users->cost_center_ids));
       
         $data =  Sales::with(['_organization','_master_branch','_master_details','s_account','_ledger'])->find($id);
        $form_settings = SalesFormSetting::first();
           $permited_branch = permited_branch(explode(',',$users->branch_ids));
        $permited_costcenters = permited_costcenters(explode(',',$users->cost_center_ids));

        Sales::where('id',$id)->update(['_challan_counter'=>($data->_challan_counter+1)]);
        
        $store_houses = permited_stores(explode(',',$users->store_ids));
            return view('backend.sales.challan2',compact('page_name','permited_branch','permited_costcenters','data','form_settings','permited_branch','permited_costcenters','store_houses'));
        
       
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function show(Purchase $purchase)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
     

     public function edit($id)
    {
        $users = Auth::user();
        $page_name = $this->page_name;
        $account_types = AccountHead::select('id','_name')->orderBy('_name','asc')->get();
        $account_groups = [];
        $branchs = Branch::orderBy('_name','asc')->get();
         $permited_branch = permited_branch(explode(',',$users->branch_ids));
         $permited_costcenters = permited_costcenters(explode(',',$users->cost_center_ids));
       $store_houses = permited_stores(explode(',',$users->store_ids));
       
        $form_settings = SalesFormSetting::first();
        $inv_accounts = [];
        $p_accounts = [];
        $dis_accounts = [];
        $vat_accounts =[];
        $categories = ItemCategory::with(['_parents'])->select('id','_name','_parent_id')->orderBy('_name','asc')->get();
        $units = Units::orderBy('_name','asc')->get();
         $data =  Sales::with(['_master_branch','_master_details','s_account','_ledger'])->where('_lock',0)->find($id);
         if(!$data){ return redirect()->back()->with('danger','You have no permission to edit or update !'); }
          $sales_number = SalesDetail::where('_no',$id)->count();
           $_warranties = Warranty::select('id','_name')->orderBy('_name','asc')->where('_status',1)->get();
            $payment_terms = TransectionTerms::where('_status',1)->get();
       return view('backend.sales.edit',compact('account_types','page_name','account_groups','branchs','permited_branch','permited_costcenters','store_houses','form_settings','inv_accounts','p_accounts','dis_accounts','vat_accounts','categories','units','data','sales_number','_warranties','payment_terms'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
   
    public function update(Request $request)
    {
         // dump($request->all());
         // exit();
        $all_req= $request->all();
         $this->validate($request, [
            '_date' => 'required',
            '_branch_id' => 'required',
            '_main_ledger_id' => 'required',
            '_form_name' => 'required',
            '_sales_id' => 'required'
        ]);
    //###########################
    // Sales Master information Save Start
    //###########################

          $SalesFormSetting = SalesFormSetting::first();
    //_cash_customer_check($_cutomer_id,$_selected_customers,$_bill_amount,$_total)
            $check_cash_customers=  _cash_customer_check($request->_main_ledger_id,$SalesFormSetting->_cash_customer,$request->_total,$request->_total_dr_amount);
            if($check_cash_customers=='no'){
            return redirect()->back()->with('error','Cash Customer Must Paid Full Amount!');
            }
            $_sales_id = $request->_sales_id;
            $_lock_check =  Sales::where('_lock',0)->find($_sales_id); 
            if(!$_lock_check){ return redirect()->back()->with('danger','You have no permission to edit or update !'); }


        $_item_ids = $request->_item_id;
        //$_barcodes = $request->_barcode;
        $_qtys = $request->_qty;
        $_rates = $request->_rate;
        $_sales_rates = $request->_sales_rate;
        $_vats = $request->_vat;
        $_vat_amounts = $request->_vat_amount;
        $_values = $request->_value;
        $_main_branch_id_detail = $request->_main_branch_id_detail;
        $_main_cost_center = $request->_main_cost_center;
        $_store_ids = $request->_main_store_id;
        $_store_salves_ids = $request->_store_salves_id;
        $_p_p_l_ids = $request->_p_p_l_id;
        $_purchase_invoice_nos = $request->_purchase_invoice_no;
        $_purchase_detail_ids = $request->_purchase_detail_id;
        $_discounts = $request->_discount;
        $_discount_amounts = $request->_discount_amount;
        $_sales_detail_row_ids = $request->_sales_detail_row_id;
         $_manufacture_dates = $request->_manufacture_date;
        $_expire_dates = $request->_expire_date;
        $_ref_counters = $request->_ref_counter;
        $_warrantys = $request->_warranty;
        $organization_id = $request->organization_id ?? 1;

        $_base_unit_ids = $request->_base_unit_id ?? [];
        $conversion_qtys = $request->conversion_qty ?? [];
        $_transection_units = $request->_transection_unit ?? [];
        $_expected_qtys = $request->_expected_qty ?? [];


 //====
        // Product Price list table update with previous sales details item
        // 
        //======
$checkqty = array();
$over_qtys = array();
      

//Prevoius Return information
     $previous_sales_details = SalesDetail::where('_no',$_sales_id)->where('_status',1)->get();
    foreach ($previous_sales_details as $value) {
         $product_prices = ProductPriceList::where('_purchase_detail_id',$value->_purchase_detail_id)->first();
         $new_qty = (($value->_qty*$value->_unit_conversion)+$product_prices->_qty);

         $_unique_barcode = $product_prices->_unique_barcode;
         $_old_new_barcode = $value->_barcode.",".$product_prices->_barcode;
         array_push($checkqty, ['id'=>$product_prices->_purchase_detail_id,'_qty'=>$new_qty,'_barcode'=>$_old_new_barcode,'_unique_barcode'=>$_unique_barcode]);
    }



    foreach ($_purchase_detail_ids as $item_key=> $_item) {
        foreach ($checkqty as $check_item) {
              $barcode_string=$all_req[$_ref_counters[$item_key]."__barcode__".$_item_ids[$item_key]] ?? '';
          if($_unique_barcode ==1){
            if(strlen($barcode_string) > 0){ //Check Barcode Available
                if($_item==$check_item["id"]){ //Check Previous item id and Current Item id Same
                    $_req_barcode_array = explode(",",$barcode_string); // Barcode make array from string
                    foreach ($_req_barcode_array as $_bar_value) {
                        $_barcode_yes = str_contains($check_item["_barcode"], $_bar_value); //Check available barcode number with product price list table and previous purchase return details table
                        if(!$_barcode_yes){
                           // if Return false then its wrong barcode and send message
                            $msg = "You Input Wrong Barcode. Wrong Barcode Numer is- ". $_bar_value;
                            return redirect()->back()->with('danger',$msg);
                        }else{
                            //this section come after check available 
                          $check_model_or_unique =   explode(",",$check_item["_barcode"]); //Barcode string to array to check model barcode or unique barcode
                          if(sizeof(array_unique($check_model_or_unique)) ==1){ // if sizeof($check_model_or_unique)==1 then we desied that its used a model barcode 
                           
                              //Model Barcode and now check quantity
                                if($_item==$check_item["id"] && ($_qtys[$item_key]*$conversion_qtys[$item_key]) > $check_item["_qty"] ){
                                    array_push($over_qtys, $_item); //if use model barcode and want to return more then purchase quantity then show a messge
                                 }
                          }
                           //Unique Barcode Olready Check as wrong Barcode number
                        }
                       
                    }
                }
            }


          }else{

                 //This section come when no barcode used for item purchase and purchase return
                //Here we need to check item id and item available qty
                if($_item==$check_item["id"] && ($_qtys[$item_key]*$conversion_qtys[$item_key]) > $check_item["_qty"] ){
                    
                    array_push($over_qtys, $_item); //If input Extra qty then shw a messge
                }
            }
            
        }
    }

    if(sizeof($over_qtys) > 0){
        return redirect()->back()
        ->with('request',$request->all())
        ->with('danger','You Can not Return More then available Qty !');
    }



           DB::beginTransaction();
           try {
        
       
        foreach ($previous_sales_details as $value) {

            $product_prices =ProductPriceList::where('id',$value->_p_p_l_id)->first();
             $new_qty = (($value->_qty*$value->_unit_conversion)+$product_prices->_qty);
             $_unique_barcode = $product_prices->_unique_barcode;
             $_up_status = (($new_qty) > 0) ? 1 : 0;
             if($_unique_barcode ==1){
                $_old_new_barcode = $value->_barcode.",".$product_prices->_barcode;
                if($_old_new_barcode !=""){
                    $_unique_old_newbarcode_array =   explode(",",$_old_new_barcode);
                    foreach ($_unique_old_newbarcode_array as $_arraY_val) {
                      $_unique_old_newbarcode_array[]=trim($_arraY_val);
                    }
                    $_unique_old_newbarcode_array = array_unique($_unique_old_newbarcode_array);
                     $_last_barcode_string = implode(",",$_unique_old_newbarcode_array);
                     $product_prices->_barcode =$_last_barcode_string;
                    if(sizeof($_unique_old_newbarcode_array) > 0){
                        foreach ($_unique_old_newbarcode_array as $_bar_value) {
                                BarcodeDetail::where('_p_p_id',$product_prices->id)
                                            ->where('_item_id',$product_prices->_item_id)
                                            ->where('_barcode',$_bar_value)
                                            ->update(['_qty'=>1,'_status'=>1]);
                            }
                    }
                }
                
             }
             $product_prices->_qty = $new_qty;
             
             $product_prices->save();

        }

     

    SalesDetail::where('_no', $_sales_id)
            ->update(['_status'=>0]);
    ItemInventory::where('_transection',"Sales")
        ->where('_transection_ref',$_sales_id)
        ->update(['_status'=>0]);
    SalesAccount::where('_no',$_sales_id)                               
            ->update(['_status'=>0]);
    Accounts::where('_ref_master_id',$_sales_id)
                    ->where('_table_name',$request->_form_name)
                     ->update(['_status'=>0]);  
    Accounts::where('_ref_master_id',$_sales_id)
                    ->where('_table_name','sales_accounts')
                     ->update(['_status'=>0]);  

    SalesBarcode::where('_no_id',$_sales_id)
                  ->update(['_status'=>0,'_qty'=>0]);

     $_p_balance = _l_balance_update($request->_main_ledger_id);
        
         $__sub_total = (float) $request->_sub_total;
         $__total = (float) $request->_total;
         $__discount_input = (float) $request->_discount_input;
         $__total_discount = (float) $request->_total_discount;

       $_print_value = $request->_print ?? 0;
         $users = Auth::user();
        $Sales = Sales::find($_sales_id);
        $Sales->_date = change_date_format($request->_date);
        $Sales->_time = date('H:i:s');
        $Sales->_payment_terms = $request->_payment_terms ?? 1;
        $Sales->_order_ref_id = $request->_order_ref_id;
        $Sales->_order_number = $request->_order_number ?? '';
        $Sales->_referance = $request->_referance;
        $Sales->_ledger_id = $request->_main_ledger_id;
        $Sales->_user_id = $request->_main_ledger_id;
        $Sales->_created_by = $users->id."-".$users->name;
        $Sales->_user_id = $users->id;
        $Sales->_user_name = $users->name;
        $Sales->_note = $request->_note;
        $Sales->_sub_total = $__sub_total;
        $Sales->_discount_input = $__discount_input;
        $Sales->_total_discount = $__total_discount;
        $Sales->_total_vat = $request->_total_vat;
        $Sales->_total =  $__total;
        $Sales->organization_id = $organization_id;
        $Sales->_branch_id = $request->_branch_id;
        $Sales->_cost_center_id = $request->_cost_center_id ?? 1;
        $Sales->_address = $request->_address;
        $Sales->_phone = $request->_phone;
        $Sales->_delivery_man_id = $request->_delivery_man_id ?? 0;
        $Sales->_sales_man_id = $request->_sales_man_id ?? 0;
        $Sales->_sales_type = $request->_sales_type ?? 'sales';

       
        $Sales->_delivery_details = $request->_delivery_details ?? '';
        $Sales->_direct_purchase_no = $request->_direct_purchase_no ?? '';
        if(isset($request->_direct_purchase_no) && $request->_direct_purchase_no !=''){
            $Sales->_is_direct_sales = $request->_is_direct_sales ?? 1;
        }
        
        
        $Sales->_vessel_no = $request->_vessel_no ?? 0;
        $Sales->_arrival_date_time = $request->_arrival_date_time;
        $Sales->_discharge_date_time = $request->_discharge_date_time;
        $Sales->_sd_input = $request->_sd_input ?? 0;
        $Sales->_total_sd_amount = $request->total_sd_amount ?? 0;
        $Sales->_loding_point = $request->_loding_point ?? '';
        $Sales->_unloading_point = $request->_unloading_point ?? '';


        $Sales->_status = 1;
        $Sales->_lock = $request->_lock ?? 0;
        $Sales->save();
        $_master_id = $Sales->id;
                                           

        //###########################
        // Purchase Master information Save End
        //###########################

        //###########################
        // Purchase Details information Save Start
        //###########################
       



        

       
        $_total_cost_value=0;

        if(sizeof($_item_ids) > 0){
            for ($i = 0; $i <sizeof($_item_ids) ; $i++) {
                $_total_cost_value += (($_rates[$i]*$conversion_qtys[$i] ?? 1)*$_qtys[$i]);
                if($_sales_detail_row_ids[$i] ==0){
                        $SalesDetail = new SalesDetail();
                }else{
                    $SalesDetail = SalesDetail::find($_sales_detail_row_ids[$i]);
                }


                //$_base_rate = ($_values[$i]/($_qtys[$i]*$conversion_qtys[$i] ?? 1));

                 $converted_l=($_qtys[$i]*$conversion_qtys[$i] ?? 1);
                if($converted_l ==0){$converted_l=1;};
                $_base_rate = ($_values[$i]/$converted_l);


                $SalesDetail->_transection_unit = $_transection_units[$i] ?? 1;
                $SalesDetail->_unit_conversion = $conversion_qtys[$i] ?? 1;
                $SalesDetail->_base_unit = $_base_unit_ids[$i] ?? 1;
                $SalesDetail->_base_rate = $_base_rate;

                $barcode_string=$all_req[$_ref_counters[$i]."__barcode__".$_item_ids[$i]] ?? '';
                $SalesDetail->_barcode = $barcode_string;
                $SalesDetail->_warranty = $_warrantys[$i] ?? 0;
                
                $SalesDetail->_item_id = $_item_ids[$i];
                $SalesDetail->_p_p_l_id = $_p_p_l_ids[$i];
                $SalesDetail->_purchase_invoice_no = $_purchase_invoice_nos[$i];
                $SalesDetail->_purchase_detail_id = $_purchase_detail_ids[$i];
                $SalesDetail->_qty = $_qtys[$i];
                $SalesDetail->_expected_qty = $_expected_qtys[$i] ?? 0;




                $SalesDetail->_rate = $_rates[$i];
                $SalesDetail->_sales_rate = $_sales_rates[$i];
                $SalesDetail->_discount = $_discounts[$i] ?? 0;
                $SalesDetail->_discount_amount = $_discount_amounts[$i] ?? 0;
                $SalesDetail->_vat = $_vats[$i] ?? 0;
                $SalesDetail->_vat_amount = $_vat_amounts[$i] ?? 0;
                $SalesDetail->_value = $_values[$i] ?? 0;
                $SalesDetail->_manufacture_date = $_manufacture_dates[$i];
                $SalesDetail->_expire_date = $_expire_dates[$i];
                $SalesDetail->_store_id = $_store_ids[$i] ?? 1;
                $SalesDetail->_cost_center_id = $_main_cost_center[$i] ?? 1;
                $SalesDetail->_store_salves_id = $_store_salves_ids[$i] ?? '';
                $SalesDetail->organization_id = $organization_id ?? 1;
                $SalesDetail->_branch_id = $_main_branch_id_detail[$i] ?? 1;
                $SalesDetail->_no = $_master_id;
                $SalesDetail->_status = 1;
                $SalesDetail->_created_by = $users->id."-".$users->name;
                $SalesDetail->save();
                $_sales_details_id = $SalesDetail->id;

                $item_info = Inventory::where('id',$_item_ids[$i])->first();

               $ProductPriceList = ProductPriceList::find($_p_p_l_ids[$i]);
                $_p_qty = $ProductPriceList->_qty;
                $_unique_barcode = $ProductPriceList->_unique_barcode;
 if($_unique_barcode ==1){

                //Barcode  deduction from old string data
                 $_old_barcode_strings =  $ProductPriceList->_barcode;
                 $_last_barcode_array = array();
                $_new_barcode_array = array();
                if($_old_barcode_strings !=""){
                    $_old_barcode_array = explode(",",$_old_barcode_strings);
                }
                if($barcode_string !=""){
                    $_new_barcode_array = explode(",",$barcode_string);
                }

                foreach ($_old_barcode_array as $_old_value) {
                  if(!in_array($_old_value, $_new_barcode_array)){
                   
                    array_push($_last_barcode_array, $_old_value);
                  }
                }
               
                if(sizeof($_last_barcode_array ) > 0){
                  $_new_last_barcode_string = implode(",",$_last_barcode_array);
                  
                }else{
                  $_new_last_barcode_string = '';
                }

$ProductPriceList->_barcode = $_new_last_barcode_string;
              
}
                //Barcode  deduction from old string data




                // $_status = (($_p_qty - $_qtys[$i]) > 0) ? 1 : 0;
                // $ProductPriceList->_qty = ($_p_qty - $_qtys[$i]);
                // $ProductPriceList->_status = $_status;
                // $ProductPriceList->save();

                $_status = (($_p_qty - ($_qtys[$i]* $conversion_qtys[$i] ?? 1)) > 0) ? 1 : 0;
                $ProductPriceList->_qty = ($_p_qty - ($_qtys[$i]* $conversion_qtys[$i] ?? 1));
                $ProductPriceList->_status = $_status;
                $ProductPriceList->save();


/***************************************
   Barcode insert into database section
   _barcode_insert_update($modelName, $_p_p_id,$_item_id,$_no_id,$_no_detail_id,$_qty,$_barcode,$_status,$_return=0,$p=0)
   IF RETURN ACTION THEN $_return = 1; and BarcodeDetail avoid 
   [  $data->_no_id = $_no_id; $data->_no_detail_id = $_no_detail_id; ] use  $p=1;
**************************************************/
                 $product_price_id =  $ProductPriceList->id;
                 $_unique_barcode =  $ProductPriceList->_unique_barcode;
                  if($_unique_barcode ==1){
                 if($barcode_string !=""){
                       $barcode_array=  explode(",",$barcode_string);
                       $_qty = 1;
                       $_stat = 1;
                       $_return=1;
                       
                       foreach ($barcode_array as $_b_v) {
                        _barcode_insert_update('BarcodeDetail', $product_price_id,$_item_ids[$i],$_master_id,$_sales_details_id,$_qty,$_b_v,$_stat,1,1);
                        _barcode_insert_update('SalesBarcode', $product_price_id,$_item_ids[$i],$_master_id,$_sales_details_id,$_qty,$_b_v,$_stat,0,0);
                         
                       }
                    }
              }
                
/*
    Barcode insert into database section


*/




                $ItemInventory = ItemInventory::where('_transection',"Sales")
                                    ->where('_transection_ref',$_sales_id)
                                    ->where('_transection_detail_ref_id',$_sales_details_id)
                                    ->first();
                if(empty($ItemInventory)){
                    $ItemInventory = new ItemInventory();
                    $ItemInventory->_created_by = $users->id."-".$users->name;
                } 
                $ItemInventory->_item_id =  $_item_ids[$i];
                $ItemInventory->_item_name =  $item_info->_item ?? '';
                 $ItemInventory->_unit_id =  $item_info->_unit_id ?? '';
                $ItemInventory->_category_id = _item_category($_item_ids[$i]);
                $ItemInventory->_date = change_date_format($request->_date);
                $ItemInventory->_time = date('H:i:s');
                $ItemInventory->_transection = "Sales";
                $ItemInventory->_transection_ref = $_master_id;
                $ItemInventory->_transection_detail_ref_id = $_sales_details_id;

                $ItemInventory->_qty = -($_qtys[$i] * $conversion_qtys[$i] ?? 1);
                $ItemInventory->_rate =( $_sales_rates[$i]/$conversion_qtys[$i] ?? 1);
                $ItemInventory->_cost_rate = $_rates[$i];
                $ItemInventory->_cost_value = (($_qtys[$i]*$conversion_qtys[$i] ?? 1)*$_rates[$i]);

                //Unit Conversion section
                $ItemInventory->_transection_unit = $_transection_units[$i] ?? 1;
                $ItemInventory->_unit_conversion = $conversion_qtys[$i] ?? 1;
                $ItemInventory->_base_unit = $item_info->_unit_id ?? 1;
                $ItemInventory->_unit_id = $item_info->_unit_id ?? 1;

                
                $ItemInventory->_value = $_values[$i] ?? 0;

                $ItemInventory->_manufacture_date = $_manufacture_dates[$i];
                $ItemInventory->_expire_date = $_expire_dates[$i];
                $ItemInventory->organization_id = $organization_id;
                $ItemInventory->_branch_id = $_main_branch_id_detail[$i] ?? 1;
                $ItemInventory->_store_id = $_store_ids[$i] ?? 1;
                $ItemInventory->_cost_center_id = $_main_cost_center[$i] ?? 1;
                $ItemInventory->_store_salves_id = $_store_salves_ids[$i] ?? '';
                $ItemInventory->_status = 1;
                $ItemInventory->_updated_by = $users->id."-".$users->name;
                $ItemInventory->save(); 

                inventory_stock_update($_item_ids[$i]);
            }
        }

        //###########################
        // Purchase Details information Save End
        //###########################

        //###########################
        // Purchase Account information Save End
        //###########################
        $_total_dr_amount = 0;
        $_total_cr_amount = 0;

        
        $_default_inventory = $SalesFormSetting->_default_inventory;
        $_default_sales = $SalesFormSetting->_default_sales;
        $_default_discount = $SalesFormSetting->_default_discount;
        $_default_vat_account = $SalesFormSetting->_default_vat_account;
        $_default_cost_of_solds = $SalesFormSetting->_default_cost_of_solds;

        $_ref_master_id=$_master_id;
        $_ref_detail_id=$_master_id;
        $_short_narration='N/A';
        $_narration = $request->_note;
        $_reference= $request->_referance;
        $_transaction= 'Sales';
        $_date = change_date_format($request->_date);
        $_table_name = $request->_form_name;
        $_branch_id = $request->_branch_id;
        $_cost_center =  $request->_cost_center_id ?? 1;
        $_name =$users->name;

      
        
         if($__sub_total > 0){

            //#################
            // Account Receiveable Dr.
            //      Sales Cr
            //#################

            //Default Sales DR.
            account_data_save($_ref_master_id,$_ref_detail_id,_find_ledger($_default_sales),$_narration,$_reference,$_transaction,$_date,$_table_name,$request->_main_ledger_id,$__sub_total,0,$_branch_id,$_cost_center,$_name,1,$organization_id);
           //Default Account Receivable  Cr.
            account_data_save($_ref_master_id,$_ref_detail_id,_find_ledger($request->_main_ledger_id),$_narration,$_reference,$_transaction,$_date,$_table_name,$_default_sales,0,$__sub_total,$_branch_id,$_cost_center,$_name,2,$organization_id);

            //#################
            // Cost of Goods Sold Dr.
            //      Inventory  Cr
            //#################

            //Cost of Goods Sold Dr.
            account_data_save($_ref_master_id,$_ref_detail_id,_find_ledger($_default_inventory),$_narration,$_reference,$_transaction,$_date,$_table_name,$_default_cost_of_solds,$_total_cost_value,0,$_branch_id,$_cost_center,$_name,3,$organization_id);
            //Inventory  Cr
            account_data_save($_ref_master_id,$_ref_detail_id,_find_ledger($_default_cost_of_solds),$_narration,$_reference,$_transaction,$_date,$_table_name,$_default_inventory,0,$_total_cost_value,$_branch_id,$_cost_center,$_name,4,$organization_id);
        }

        if($__total_discount > 0){
             //#################
            // Sales Discount Dr.
            //      Account Receivable  Cr
            //#################
            //Default Discount
            account_data_save($_ref_master_id,$_ref_detail_id,_find_ledger($request->_main_ledger_id),$_narration,$_reference,$_transaction,$_date,$_table_name,$_default_discount,$__total_discount,0,$_branch_id,$_cost_center,$_name,5,$organization_id);
            //  Account Receivable  Cr
            account_data_save($_ref_master_id,$_ref_detail_id,_find_ledger($_default_discount),$_narration,$_reference,$_transaction,$_date,$_table_name,$request->_main_ledger_id,0,$__total_discount,$_branch_id,$_cost_center,$_name,6,$organization_id);
             
        
        }
         $__total_vat = (float) $request->_total_vat ?? 0;
        if($__total_vat > 0){
             //#################
            // Account Receivable Dr.
            //      Vat  Cr
            //#################
            //Default Vat Account
            account_data_save($_ref_master_id,$_ref_detail_id,_find_ledger($_default_vat_account),$_narration,$_reference,$_transaction,$_date,$_table_name,$request->_main_ledger_id,$request->_total_vat,0,$_branch_id,$_cost_center,$_name,7,$organization_id);
            //Account Receivable
            account_data_save($_ref_master_id,$_ref_detail_id,_find_ledger($request->_main_ledger_id),$_narration,$_reference,$_transaction,$_date,$_table_name,$_default_vat_account,0,$request->_total_vat,$_branch_id,$_cost_center,$_name,8,$organization_id);
        
        }

       

        $_ledger_id = (array) $request->_ledger_id;
        $_short_narr = (array) $request->_short_narr;
        $_dr_amount = (array) $request->_dr_amount;
         $_cr_amount = (array) $request->_cr_amount;
        $_branch_id_detail = (array) $request->_branch_id_detail;
        $_cost_center = (array) $request->_cost_center;
        $purchase_account_ids =  $request->purchase_account_id;
       
        if(sizeof($_ledger_id) > 0){
                foreach($_ledger_id as $i=>$ledger) {
                    if($ledger !=""){
                       
                     // echo  $_cr_amount[$i];
                        $_account_type_id =  ledger_to_group_type($ledger)->_account_head_id;
                        $_account_group_id =  ledger_to_group_type($ledger)->_account_group_id;

                        $_total_dr_amount += $_dr_amount[$i] ?? 0;
                        $_total_cr_amount += $_cr_amount[$i] ?? 0;
                        $SalesAccount = SalesAccount::where('id',$purchase_account_ids[$i] ?? 0)
                                                            ->where('_ledger_id',$ledger)
                                                            ->first();
                        if(empty($PurchaseAccount)){
                             $SalesAccount = new SalesAccount();
                        }
                       
                        $SalesAccount->_no = $_master_id;
                        $SalesAccount->_account_type_id = $_account_type_id;
                        $SalesAccount->_account_group_id = $_account_group_id;
                        $SalesAccount->_ledger_id = $ledger;
                        $SalesAccount->_cost_center = $_cost_center[$i] ?? 0;
                        $SalesAccount->organization_id = $organization_id;
                        $SalesAccount->_branch_id = $_branch_id_detail[$i] ?? 0;
                        $SalesAccount->_short_narr = $_short_narr[$i] ?? 'N/A';
                        $SalesAccount->_dr_amount = $_dr_amount[$i];
                        $SalesAccount->_cr_amount = $_cr_amount[$i];
                        $SalesAccount->_status = 1;
                        $SalesAccount->_created_by = $users->id."-".$users->name;
                        $SalesAccount->save();

                        $_sales_account_id = $SalesAccount->id;

                        //Reporting Account Table Data Insert
                        $_ref_master_id=$_master_id;
                        $_ref_detail_id=$_sales_account_id;
                        $_short_narration=$_short_narr[$i] ?? 'N/A';
                        $_narration = $request->_note;
                        $_reference= $request->_referance;
                        $_transaction= 'Sales';
                        $_date = change_date_format($request->_date);
                        $_table_name ='sales_accounts';
                        $_account_ledger = $_ledger_id[$i];
                        $_dr_amount_a = $_dr_amount[$i] ?? 0;
                        $_cr_amount_a = $_cr_amount[$i] ?? 0;
                        $_branch_id_a = $_branch_id_detail[$i] ?? 0;
                        $_cost_center_a = $_cost_center[$i] ?? 0;
                        $_name =$users->name;
                        account_data_save($_ref_master_id,$_ref_detail_id,$_short_narration,$_narration,$_reference,$_transaction,$_date,$_table_name,$_account_ledger,$_dr_amount_a,$_cr_amount_a,$_branch_id_a,$_cost_center_a,$_name,(9+$i),$organization_id);
                          
                    }
                }

                 
                //Only Cash and Bank receive in account detail. This entry set automatically by program.
                if($_total_dr_amount > 0 && $users->_ac_type==1){
                         $_account_type_id =  ledger_to_group_type($request->_main_ledger_id)->_account_head_id;
                        $_account_group_id =  ledger_to_group_type($request->_main_ledger_id)->_account_group_id;
                        $SalesAccount = new SalesAccount();
                        $SalesAccount->_no = $_master_id;
                        $SalesAccount->_account_type_id = $_account_type_id;
                        $SalesAccount->_account_group_id = $_account_group_id;
                        $SalesAccount->_ledger_id = $request->_main_ledger_id;
                        $SalesAccount->_cost_center = $users->cost_center_ids;
                        $SalesAccount->organization_id = $organization_id;
                        $SalesAccount->_branch_id = $users->branch_ids;
                        $SalesAccount->_short_narr = 'N/A';
                        $SalesAccount->_dr_amount = 0;
                        $SalesAccount->_cr_amount = $_total_dr_amount;
                        $SalesAccount->_status = 1;
                        $SalesAccount->_created_by = $users->id."-".$users->name;
                        $SalesAccount->save();

                        $_sales_account_id = $SalesAccount->id;

                        //Reporting Account Table Data Insert
                        $_ref_master_id=$_master_id;
                        $_ref_detail_id=$_sales_account_id;
                        $_short_narration='N/A';
                        $_narration = $request->_note;
                        $_reference= $request->_referance;
                        $_transaction= 'Sales';
                        $_date = change_date_format($request->_date);
                        $_table_name ='sales_accounts';
                        $_account_ledger = $request->_main_ledger_id;
                        $_dr_amount_a = 0;
                        $_cr_amount_a = $_total_dr_amount ?? 0;
                        $_branch_id_a = $users->branch_ids;
                        $_cost_center_a = $users->cost_center_ids;
                        $_name =$users->name;
                        account_data_save($_ref_master_id,$_ref_detail_id,$_short_narration,$_narration,$_reference,$_transaction,$_date,$_table_name,$_account_ledger,$_dr_amount_a,$_cr_amount_a,$_branch_id_a,$_cost_center_a,$_name,(20),$organization_id);
                }
            }

 
             $_l_balance = _l_balance_update($request->_main_ledger_id);
              $_pfix = _sales_pfix().$_master_id;

            
             \DB::table('sales')
             ->where('id',$_master_id)
             ->update(['_p_balance'=>$_p_balance,'_l_balance'=>$_l_balance]);

              //SMS SEND to Customer and Supplier
              //SMS SEND to Customer and Supplier
             $_send_sms = $request->_send_sms ?? '';
             if($_send_sms=='yes'){
                $_name = _ledger_name($request->_main_ledger_id);
                $_phones = $request->_phone;
                $messages = "Dear ".$_name.", Date: ".$request->_date." Invoice N0.".$_pfix." Updated Invoice Amount: ".prefix_taka()."."._report_amount($request->_total).". Payment Amount. ".prefix_taka()."."._report_amount($_total_dr_amount).". Previous Balance ".prefix_taka()."."._report_amount($_p_balance).". Current Balance:".prefix_taka()."."._report_amount($_l_balance);
                  sms_send($messages, $_phones);
             }
             //End Sms Send to customer and Supplier
             //End Sms Send to customer and Supplier

          DB::commit();
          if(($request->_print_value ?? 0) ==1){
                return redirect('sales/print/'.$_master_id);
          }else{
            return redirect()
                ->back()
                ->with('success','Information save successfully')
                ->with('_master_id',$_master_id)
                ->with('_print_value',$_print_value);
          }
            
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('danger','There is Something Wrong !');
        }

       
       
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
     public function destroy($id)
    {
        return redirect()->back()->with('danger','You Can not delete this Information');

    }
}
