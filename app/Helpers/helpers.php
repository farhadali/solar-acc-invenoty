<?php
use Carbon\Carbon;
use App\Models\Branch;
use App\Models\CostCenter;
use App\Models\AccountGroup;
use App\Models\AccountHead;
use App\Models\AccountLedger;
use App\Models\Accounts;
use App\Models\VoucherType;
use App\Models\Inventory;
use App\Models\StoreHouse;
use App\Models\ItemCategory;
use App\Models\Units;
use App\Models\UnitConversion;
use App\Models\InvoicePrefix;

function convert_number($number)
{
    if ($number < 0 || $number > 999999999999999) {
        throw new Exception("Number is out of range");
    }
    $decimalPart = ($number * 100) % 100; // Extract the decimal part (up to two decimal places)
    $number = floor($number); // Remove the decimal part for further conversion
    $Kt = floor($number / 10000000); /* Core */
    $number -= $Kt * 10000000;
    $Gn = floor($number / 100000);  /* Lakh */
    $number -= $Gn * 100000;
    $kn = floor($number / 1000);     /* Thousands (kilo) */
    $number -= $kn * 1000;
    $Hn = floor($number / 100);      /* Hundreds (hecto) */
    $number -= $Hn * 100;
    $Dn = floor($number / 10);       /* Tens (deca) */
    $n = $number % 10;               /* Ones */
    $res = "";
    if ($Kt) {$res .= convert_number($Kt) . " Core ";}
    if ($Gn) {$res .= convert_number($Gn) . " Lac";}
    if ($kn) {$res .= (empty($res) ? "" : " ") . convert_number($kn) . " Thousand";}
    if ($Hn) {$res .= (empty($res) ? "" : " ") . convert_number($Hn) . " Hundred";}

    $ones = array("", "One", "Two", "Three", "Four", "Five", "Six",
        "Seven", "Eight", "Nine", "Ten", "Eleven", "Twelve", "Thirteen",
        "Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eighteen",
        "Nineteen");
    $tens = array("", "", "Twenty", "Thirty", "Forty", "Fifty", "Sixty",
        "Seventy", "Eighty", "Ninety");

    if ($Dn || $n) {
        if (!empty($res)) {$res .= " ";}
        if ($Dn < 2) {
            $res .= $ones[$Dn * 10 + $n];
        } else {
            $res .= $tens[$Dn];
            if ($n) {$res .= "-" . $ones[$n];}
        }
    }
    if (empty($res)) {$res = "zero";}

    // Add the decimal part if it exists
    if ($decimalPart > 0) {
        $res .= " Point ";
        $decimalPartWords = convert_number($decimalPart);
        $res .= $decimalPartWords;
    }

    return $res;
} 

//RLP Database Connection

if (! function_exists('access_chain_types')) {
    function access_chain_types()
    {
      return ['1'=>'RLP','2'=>'NOTESHEET','3'=>'WORKORDER'];
    }
}

if (! function_exists('selected_access_chain_types')) {
    function selected_access_chain_types($id)
    {
      foreach(access_chain_types() as $key=>$val){
        if($id == $key){
            return $val;
        } 
      } 
      return 'Empty';
    }
}

if (!function_exists('UserImageUpload')) {

  function UserImageUpload($query) // Taking input image as parameter
    {
        $image_name = date('mdYHis') . uniqid();
        $ext = strtolower($query->getClientOriginalExtension()); // You can use also getClientOriginalName()
        
        $image_full_name = $image_name.'.'.$ext;
        $upload_path = 'images/';    //Creating Sub directory in Public folder to put image
        $image_url = $upload_path.$image_full_name;
        $success = $query->move($upload_path,$image_full_name);
 
        return $image_url; // Just return image
    }
}

if (!function_exists('office_days_cat')) {
    function office_days_cat() 
    {
        return ['on','off','half day'];
    }
}
if (!function_exists('full_half')) {
    function full_half() 
    {
        return ['full day','half day'];
    }
}

if (!function_exists('pay_head_types')) {
    function pay_head_types() 
    {
        return ['Payable','Deduction'];
    }
}

if (!function_exists('_gender_list')) {
    function _gender_list() 
    {
        return ['Male','Female','Others'];
    }
}




if (!function_exists('item_code_generate')) {

  function item_code_generate($cat_id=0) // Taking input image as parameter
    {
             $category_wise_last_row =\DB::table('inventories')->where('_category_id',$cat_id)->count();

             $last_serial =\DB::table('inventories')->where('_category_id',$cat_id)->orderBy('id','Desc')->first();
             
                $_serial = (($last_serial->_serial ?? 0)+1);
            

             $category_serial = ($_serial+1);

            if(strlen($cat_id)==1){
                $base_cat_id = "0".$cat_id;
            }else{
                $base_cat_id = $cat_id;
            }

             if(strlen($category_serial)==1){
                $product_code = "0000".$category_serial;
              }elseif(strlen($category_serial)==2){
                $product_code = "000".$category_serial;
              }elseif(strlen($category_serial)==3){
                $product_code = "00".$category_serial;
              }elseif(strlen($category_serial)==4){
                $product_code = "0".$category_serial;
              }else{
                $product_code = $category_serial;
              }

              $full_product_code = $base_cat_id."-".$product_code;
              //CheckDuplicate Item Code

              $data["full_product_code"] =$full_product_code;
              $data["_serial"] =$_serial;

            return  $data;
    }
}

function make_po_number($organization_id,$branch_id){
    $row_counts = \App\Models\PurchaseOrder::where('organization_id',$organization_id)
                                            ->where('_branch_id',$branch_id)
                                            ->count();
     $row_counts = ($row_counts+1);                                   
    $org_code = \App\Models\hrm\Company::find($organization_id)->_code ?? '';
    $branch_code = \App\Models\Branch::find($branch_id)->_code ?? '';
    if(strlen($row_counts)==1){
        $last_row_number = "0".$row_counts;
      }else{
        $last_row_number = $row_counts;
      }
      return $org_code."-".$branch_code."-".$last_row_number;
}


function make_order_number($table,$organization_id,$branch_id){
    $row_counts = \DB::table($table)->where('organization_id',$organization_id)
                                            ->where('_branch_id',$branch_id)
                                            ->count();
     $row_counts = ($row_counts+1);                                   
    $org_code = \App\Models\hrm\Company::find($organization_id)->_code ?? '';
    $branch_code = \App\Models\Branch::find($branch_id)->_code ?? '';
    if(strlen($row_counts)==1){
        $last_row_number = "0".$row_counts;
      }else{
        $last_row_number = $row_counts;
      }
      return $org_code."-".$branch_code."-".$last_row_number;
}


if (!function_exists('item_code_Regenerate')) {

  function item_code_Regenerate() 
    {
             //Regenerate of item Code

        //Fetch all data
        $all_items = Inventory::orderBy('_item','ASC')->get();
            $rearrage_items = [];
            foreach ($all_items as $key => $value) {
               $rearrage_items[$value->_category_id][]=$value;
            }
            $category_serial=0;
            foreach ($rearrage_items as $key => $val) {
                if(strlen($key)==1){
                $base_cat_id = "0".$key;
                }else{
                  $base_cat_id = $key;
                }
                $category_serial=1;
                foreach ($val as $product_key => $product_value) {
                  

                  if(strlen($category_serial)==1){
                    $product_code = "0000".$category_serial;
                  }elseif(strlen($category_serial)==2){
                    $product_code = "000".$category_serial;
                  }elseif(strlen($category_serial)==3){
                    $product_code = "00".$category_serial;
                  }elseif(strlen($category_serial)==4){
                    $product_code = "0".$category_serial;
                  }else{
                    $product_code = $category_serial;
                  }

                  $full_product_code = $base_cat_id."-".$product_code;
                  $product_row_id = $product_value["id"];
                Inventory::where('id',$product_row_id)->update(['_code'=>$full_product_code,'_serial'=>$category_serial]);
                  $category_serial++;
            }
        }
    }
}






if (! function_exists('_itemUnit_update')) {
    function _itemUnit_update($_item_id,$_item_name,$_unit_id)
    {
        \DB::table("item_inventories")->where("_item_id",$_item_id)
                                    ->update(['_unit_id'=>$_unit_id,'_item_name'=>$_item_name]);
        \DB::table("product_price_lists")->where("_item_id",$_item_id)
                                    ->update(['_unit_id'=>$_unit_id,'_item'=>$_item_name]);
        \DB::table("inventories")->where("id",$_item_id)
                                    ->update(['_unit_id'=>$_unit_id,'_item'=>$_item_name]);                            
    }
}
if (! function_exists('_find_category_id')) {
    function _find_category_id($_category)
    {
        $data = ItemCategory::where('_name',$_category)->first();
        return $data->id ?? 1;                            
    }
}
if (! function_exists('findItemBaseCat')) {
    function findItemBaseCat($id)
    {
        $data = Inventory::where('id',$id)->first();
        return $data->_item_category ?? 1;                            
    }
}

if (! function_exists('_find_group_to_head')) {
    function _find_group_to_head($id)
    {
        $data = \App\Models\AccountGroup::find($id);
        return $data->_account_head_id ?? 1;                            
    }
}



if (! function_exists('invoice_barcode')) {
    function invoice_barcode($_order_number)
    {
       $generator = new Picqer\Barcode\BarcodeGeneratorPNG();  
       // echo  '<img style="max-width:90% !important;height: 0.24in !important; display: block;"  src="data:image/png;base64,' . base64_encode($generator->getBarcode('"'.$_order_number.'"', $generator::TYPE_CODE_128)) . '">';    



       echo  '<img style="max-width:90% !important;height: 0.24in !important; display: block;"  src="data:image/png;base64,' . base64_encode($generator->getBarcode($_order_number, $generator::TYPE_CODE_128)) . '">';            
    }
}

if (! function_exists('delivery_company')) {
    function delivery_company()
    {
       return [0=>"Own Delivery",1=>"Food Panda",2=>"Pathao"];                           
    }
}
if (! function_exists('_table_name')) {
    function _table_name($table_ids)
    {
        
        $tables = \DB::select( "SELECT _name FROM table_infos where id IN(".$table_ids.")" );   
        $_table_array = [];
        foreach ($tables as $key => $value) {
           array_push($_table_array, $value->_name);
        } 

        $_table_stings  =implode(",",$_table_array);  
        return $_table_stings;                   
    }
}

if (! function_exists('unitConversation_save')) {
    function unitConversation_save($_item_id,$_unit_id,$_conversion_qty,$_conversion_unit,$unit_status,$action=0)
    {
        if($action==0){
            $UnitConversion = new UnitConversion();
        }else{
           $UnitConversion = UnitConversion::find($action); 
        }
        
        $UnitConversion->_item_id = $_item_id;
        $UnitConversion->_base_unit_id =  $_unit_id;
        $UnitConversion->_conversion_qty =  $_conversion_qty;
        $UnitConversion->_conversion_unit =  $_conversion_unit;
        $UnitConversion->_status =  $unit_status;
        $UnitConversion->_conversion_unit_name =  _find_unit($_conversion_unit);
        $UnitConversion->save();                            
    }
}




if (!function_exists('sms_send')) {
    function sms_send($messages, $to){
        $sending_phone_numbers = array();
        $phone_numbers = "";
        if($to !=""){
            $phone_array=explode(",",$to);
            foreach ($phone_array as $_phone_num) {
                $newstring = "88".substr($_phone_num, -11);
                if(strlen($newstring)==13){
                    array_push($sending_phone_numbers, $newstring);
                } 
            }
        }
        if(sizeof($sending_phone_numbers) > 0){
           $phone_numbers = implode(",",$sending_phone_numbers);
        }

        if($phone_numbers !=""){
            $api_key ="F54d7hem0z1h8SrN9HAt4hvhJZzd0gB1vgUW935O"; 
            $url="https://api.sms.net.bd/sendsms?api_key=".$api_key."&msg=".$messages."&to=".$phone_numbers."";  
            $curl = curl_init();
            
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_CONNECTTIMEOUT , 30);
            curl_setopt($curl, CURLOPT_USERAGENT , "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1)");
            curl_setopt($curl, CURLOPT_HEADER, 0);
            $result = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);

            if($err)
                return $err;
            else
                return $result;
        }else{
            return "no";
        }

        
    }
 }

if (!function_exists('_barcode_insert_update')) {
    function _barcode_insert_update($modelName, $_p_p_id,$_item_id,$_no_id,$_no_detail_id,$_qty,$_barcode,$_status,$_return=0,$p=0){
        
        $modelName = "\\App\\Models\\".$modelName;
           
         $data = $modelName::where('_p_p_id',$_p_p_id)
                            ->where('_item_id',$_item_id)
                            //->where('_no_id',$_no_id)
                            //->where('_no_detail_id',$_no_detail_id)
                            ->where('_barcode',$_barcode)
                            ->first();
        if(empty($data)){
            $data = new $modelName();
        }
       $data->_p_p_id = $_p_p_id;
       $data->_item_id = $_item_id;
       if($p==0){
            $data->_no_id = $_no_id;
            $data->_no_detail_id = $_no_detail_id;
       }
       
       if($_return ==1){
            if(($data->_qty-$_qty) >=0){
                $data->_qty = ($data->_qty-$_qty);
            }
         
         }else{
            $data->_qty = $_qty;
         }
      
       $data->_barcode = $_barcode;
       $data->_status = $_status;
       $data->save();

       if($data->_qty < 1){
          $id = $data->id;
          $modelName::where('id',$id)->update(['_status'=>0]);
       }
       
        
    }
 }



if (! function_exists('_oposite_account')) {
    function _oposite_account($_master_id,$_table_name,$_ledger_id)
    {
        $_ledgers = \DB::select(" SELECT DISTINCT t2._name FROM `accounts` AS t1 
            INNER JOIN account_ledgers AS t2 ON t2.id=t1._account_ledger
            WHERE t1.`_table_name`='".$_table_name."' 
            AND t1.`_ref_master_id`=".$_master_id." AND t1.`_account_ledger` !=$_ledger_id ");
       
        return $_ledgers;
    }
}

if (! function_exists('_devloped_by')) {
    function _devloped_by()
    {
        //return "sohoz-hisab.com call:01677-023131";
    }
}



if (! function_exists('_barcode_status')) {
    function _barcode_status($modelName,$_no_id)
    {
        $modelName = "\\App\\Models\\".$modelName; 
        $data = $modelName::where('_no_id',$_no_id)
                            ->update(['_status'=>0,'_qty'=>0]);
    }
}

if (! function_exists('convertLocalToUTC')) {
    function convertLocalToUTC($time)
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', $time, 'Europe/Paris')->setTimezone('UTC');
    }
}

if (! function_exists('convertUTCToLocal')) {
    function convertUTCToLocal($time)
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', $time, 'UTC')->setTimezone('Europe/Paris');
    }
}

if (! function_exists('default_pagination')) {
    function default_pagination()
    {
        return 30;
    }
}

if (! function_exists('_php_round')) {
    function _php_round($_amount,$_param=1)
    {
        return round($_amount);
    }
}


if (! function_exists('_cash_customer_check')) {
    function _cash_customer_check($_cutomer_id,$_selected_customers,$_bill_amount,$_total)
    {
            if($_selected_customers !=0){
                $selected_customer_array = explode(",",$_selected_customers);
                if(in_array($_cutomer_id, $selected_customer_array)){
                    if(intval($_bill_amount) !=intval($_total)){
                        return "no";
                    }
                }
              }
    }
}









if (! function_exists('inventory_stock_update')) {
    function inventory_stock_update($item_id)
    {
        $balance=\DB::select("SELECT SUM(IFNULL(_qty,0) * IFNULL(_unit_conversion,0) ) as _balance FROM item_inventories WHERE _item_id=$item_id ");
        \DB::table('inventories')->where('id',$item_id)->update(['_balance'=>$balance[0]->_balance,'_is_used'=>1]);
    }
}



if (! function_exists('_inventory_last_price')) {
    function _inventory_last_price($item_id,$_pur_rate,$_sale_rate)
    {
        
        \DB::table('inventories')->where('id',$item_id)
                                ->update(['_pur_rate'=>$_pur_rate,'_sale_rate'=>$_sale_rate]);
    }
}



if (! function_exists('_l_balance_update')) {
    function _l_balance_update($ledger)
    {

       
        $balance=\DB::select("SELECT SUM(IFNULL(_dr_amount,0)-IFNULL(_cr_amount,0)) as _balance FROM `accounts` WHERE _account_ledger=$ledger  AND _status=1 ");

      
      return $balance[0]->_balance ?? 0;
    }
}


if (! function_exists('ledger_balance_update')) {
    function ledger_balance_update($ledger)
    {

        $balance=\DB::select("SELECT SUM(IFNULL(_dr_amount,0)-IFNULL(_cr_amount,0)) as _balance FROM `accounts` WHERE _account_ledger=$ledger AND _status=1 ");
        \DB::table('account_ledgers')->where('id',$ledger)->update(['_balance'=>$balance[0]->_balance,'_is_used'=>1]);
    }
}

if (! function_exists('account_data_save')) {
       function account_data_save($_ref_master_id,$_ref_detail_id,$_short_narration,$_narration,$_reference,$_transaction,$_date,$_table_name,$_account_ledger,$_dr_amount,$_cr_amount,$_branch_id,$_cost_center,$_name,$_serial=0,$organization_id=1){
       
        
        $_account_head =  ledger_to_group_type($_account_ledger)->_account_head_id;
        $_account_group =  ledger_to_group_type($_account_ledger)->_account_group_id;
            $Accounts =  Accounts::where('_ref_master_id',$_ref_master_id)
                                    ->where('_ref_detail_id',$_ref_detail_id)
                                    ->where('_table_name',$_table_name)
                                    ->where('_account_ledger',$_account_ledger)
                                    ->where('_serial',$_serial)
                                    ->first();
            if(empty($Accounts)){
                $Accounts = new Accounts();
            }
            
            $Accounts->_ref_master_id = $_ref_master_id;
            $Accounts->_ref_detail_id = $_ref_detail_id;
            $Accounts->_short_narration = $_short_narration;
            $Accounts->_narration = $_narration;
            $Accounts->_reference = $_reference;
            $Accounts->_transaction = $_transaction;
            $Accounts->_date = $_date;
            $Accounts->_table_name = $_table_name;
            $Accounts->_account_head = $_account_head;
            $Accounts->_account_group = $_account_group;
            $Accounts->_account_ledger = $_account_ledger;
            $Accounts->_dr_amount = $_dr_amount;
            $Accounts->_cr_amount = $_cr_amount;
            $Accounts->_branch_id = $_branch_id;
            $Accounts->organization_id = $organization_id;
            $Accounts->_cost_center = $_cost_center;
            $Accounts->_name =$_name;
            $Accounts->_status =1;
            $Accounts->_serial =$_serial;
            $Accounts->save(); 

            $id= $Accounts->id;

            ledger_balance_update($_account_ledger);
        

    }
}


if (! function_exists('_item_category')) {
    function _item_category($item)
    {
        $cates = Inventory::where('id',$item)->select('_category_id')->first();
        return $cates->_category_id ?? 0;
    }
}

if (! function_exists('_p_t_status')) {
    function _p_t_status($_p_status)
    {
        if($_p_status ==1){
            return "Transit";
        }elseif($_p_status ==2){
            return "Work-in-progress";
        }elseif($_p_status ==3){
            return "Complete";
        }else{
            return "N/A";
        }
    }
}






if (! function_exists('filterableBranch')) {
    function filterableBranch($request_branchs,$permited_branch)
    {
        $_branch_ids = array();
         
         if(sizeof($request_branchs) > 0){
            foreach ($request_branchs as $value) {
                array_push($_branch_ids, (int) $value);
            }
        }else{
                foreach ($permited_branch as $branch) {
                    array_push($_branch_ids, $branch->id);
                }
            
        }
        return $_branch_ids;
    }
}

if (! function_exists('filterableOrganization')) {
    function filterableOrganization($request_organizations,$permited_organizations)
    {
        $_organization_ids = array();
         
         if(sizeof($request_organizations) > 0){
            foreach ($request_organizations as $value) {
                array_push($_organization_ids, (int) $value);
            }
        }else{
                foreach ($permited_organizations as $org) {
                    array_push($_organization_ids, $org->id);
                }
            
        }
        return $_organization_ids;
    }
}



if (! function_exists('filterableCostCenter')) {
    function filterableCostCenter($request_cost_centers,$permited_costcenters)
    {
        
         $_cost_center_ids=array();
        if(sizeof($request_cost_centers) > 0){
            foreach ($request_cost_centers as $value) {
                array_push($_cost_center_ids, (int) $value);
            }
        }else{
            foreach ($permited_costcenters as $cost_center) {
                array_push($_cost_center_ids, $cost_center->id);
            }
            
        }
        return $_cost_center_ids;
    }
}



if (! function_exists('permited_branch')) {
    function permited_branch($branch_ids)
    {
        return Branch::whereIn('id',$branch_ids)->select('id','_name')->get();
    }
}


if (! function_exists('_branch_name')) {
    function _branch_name($branch_ids)
    {
        $branch= Branch::where('id',$branch_ids)->select('_name')->first();
        return $branch->_name ?? '';
    }
}

if (! function_exists('_unit_name')) {
    function _unit_name($id)
    {
        $data= Units::where('id',$id)->select('_name')->first();
        return $data->_name ?? '';
    }
}

if (! function_exists('_store_name')) {
    function _store_name($id)
    {
        $store= StoreHouse::where('id',$id)->select('_name')->first();
        return $store->_name ?? '';
    }
}

if (! function_exists('_cost_center_name')) {
    function _cost_center_name($id)
    {
        $data= CostCenter::where('id',$id)->select('_name')->first();
        return $data->_name ?? '';
    }
}

if (! function_exists('_category_name')) {
    function _category_name($id)
    {
        $data= ItemCategory::where('id',$id)->select('_name')->first();
        return $data->_name ?? '';
    }
}

if (! function_exists('_item_name')) {
    function _item_name($id)
    {
        $data= Inventory::where('id',$id)->select('_item as _name')->first();
        return $data->_name ?? '';
    }
}


if (! function_exists('_ledger_name')) {
    function _ledger_name($id)
    {
        $data= AccountLedger::where('id',$id)->select('_name')->first();
        return $data->_name ?? '';
    }
}

if (! function_exists('_group_name')) {
    function _group_name($id)
    {
        $data= AccountGroup::where('id',$id)->select('_name')->first();
        return $data->_name ?? '';
    }
}






if (! function_exists('permited_costcenters')) {
    function permited_costcenters($ids)
    {
        return CostCenter::whereIn('id',$ids)->select('id','_name')->get();
    }
}

if (! function_exists('permited_stores')) {
    function permited_stores($ids)
    {
        return StoreHouse::whereIn('id',$ids)->select('id','_name')->get();
    }
}

if (! function_exists('permited_organization')) {
    function permited_organization($ids)
    {
        return \App\Models\hrm\Company::whereIn('id',$ids)->select('id','_name')->get();
    }
}


if (! function_exists('filter_page_numbers')) {
    function filter_page_numbers()
    {
        return  [5,10,20,30,40,50,100,200,300,400,500];
    }
}

if (! function_exists('db_name')) {
    function db_name()
    {
        return  "branch_wise_account_soft";
    }
}

if (! function_exists('common_status')) {
    function common_status()
    {
        return  ['1'=>'Active','0'=>'In Active'];
    }
}

if (! function_exists('yes_nos')) {
    function yes_nos()
    {
        return  [1=>'YES',0=>'NO'];
    }
}
if (! function_exists('_sales_spot')) {
    function _sales_spot()
    {
        return  [1=>'Spot Sales',2=>'Online Sales'];
    }
}
if (! function_exists('_delivery_status')) {
    function _delivery_status()
    {
        return  [1=>'Order',2=>'Processing',3=>'Transit',4=>'Deliverd'];
    }
}
if (! function_exists('_warranty_status')) {
    function _warranty_status()
    {
        return  [1=>'Receive',2=>'Processing',3=>'Complete',4=>'Deliverd',5=>'Cancel',6=>'Replace'];
    }
}
if (! function_exists('_service_status')) {
    function _service_status()
    {
        return  [1=>'Receive',2=>'Processing',3=>'Complete',4=>'Deliverd',5=>'Cancel'];
    }
}
if (! function_exists('_selected_warranty_status')) {
    function _selected_warranty_status($id)
    {
        foreach (_warranty_status() as $key => $value) {
            if($id ===$key){
                return $value;
            }
        }
    }
}
if (! function_exists('selected_ser_status')) {
    function selected_ser_status($id)
    {
        foreach (_service_status() as $key => $value) {
            if($id ===$key){
                return $value;
            }
        }
    }
}

if (! function_exists('asc_desc')) {
    function asc_desc()
    {
        return  ['DESC','ASC'];
    }
}

if (! function_exists('selected_status')) {
    function selected_status($value)
    {
        foreach(common_status() as $key=>$val){
            if($key == $value){
                return $val;
            }
        }
    }
}

if (! function_exists('selected_status')) {
    function selected_status($value)
    {
        foreach(common_status() as $key=>$val){
            if($key == $value){
                return $val;
            }
        }
    }
}


if (! function_exists('selected_yes_no')) {
    function selected_yes_no($value)
    {
        foreach(yes_nos() as $key=>$val){
            if($key == $value){
                return $val;
            }
        }
    }
}


if (! function_exists('default_date_formate')) {
    function default_date_formate($value='DD-MM-YYYY')
    {
        return $value;
    }
}

if (! function_exists('voucher_prefix')) {
    function voucher_prefix()
    {
        $data= InvoicePrefix::where('_table_name','voucher_masters')->select('_prefix')->first();
        return $data->_prefix ?? '';
    }
}
if (! function_exists('resturant_prefix')) {
    function resturant_prefix()
    {
        $data= InvoicePrefix::where('_table_name','resturant_sales')->select('_prefix')->first();
        return $data->_prefix ?? '';
    }
}
if (! function_exists('service_prefix')) {
    function service_prefix()
    {
        $data= InvoicePrefix::where('_table_name','service_masters')->select('_prefix')->first();
        return $data->_prefix ?? '';
    }
}
if (! function_exists('production_prefix')) {
    function production_prefix()
    {
         $data= InvoicePrefix::where('_table_name','productions')->select('_prefix')->first();
        return $data->_prefix ?? '';
    }
}
if (! function_exists('warranty_prefix')) {
    function warranty_prefix()
    {
        $data= InvoicePrefix::where('_table_name','warranty_masters')->select('_prefix')->first();
        return $data->_prefix ?? '';
    }
}

if (! function_exists('_sales_pfix')) {
    function _sales_pfix()
    {
        $data= InvoicePrefix::where('_table_name','sales')->select('_prefix')->first();
        return $data->_prefix ?? '';
    }
}
if (! function_exists('_issue_pfix')) {
    function _issue_pfix()
    {
        $data= InvoicePrefix::where('_table_name','material_issues')->select('_prefix')->first();
        return $data->_prefix ?? '';
    }
}

if (! function_exists('_issue_return_pfix')) {
    function _issue_return_pfix()
    {
        $data= InvoicePrefix::where('_table_name','material_issue_returns')->select('_prefix')->first();
        return $data->_prefix ?? '';
    }
}


if (! function_exists('_replace_prefix')) {
    function _replace_prefix()
    {
         $data= InvoicePrefix::where('_table_name','replacement_masters')->select('_prefix')->first();
        return $data->_prefix ?? '';
    }
}


if (! function_exists('ind_rep_prefix')) {
    function ind_rep_prefix()
    {
         $data= InvoicePrefix::where('_table_name','individual_replace_masters')->select('_prefix')->first();
        return $data->_prefix ?? '';
    }
}



if (! function_exists('_transfer_prefix')) {
    function _transfer_prefix()
    {
        $data= InvoicePrefix::where('_table_name','transfer')->select('_prefix')->first();
        return $data->_prefix ?? '';
    }
}

if (! function_exists('_sales_return_pfix')) {
    function _sales_return_pfix()
    {
        $data= InvoicePrefix::where('_table_name','sales_returns')->select('_prefix')->first();
        return $data->_prefix ?? '';
    }
}


if (! function_exists('_purchase_pfix')) {
    function _purchase_pfix()
    {
        $data= InvoicePrefix::where('_table_name','purchases')->select('_prefix')->first();
        return $data->_prefix ?? '';
    }
}

if (! function_exists('_purchase_return_pfix')) {
    function _purchase_return_pfix()
    {
        $data= InvoicePrefix::where('_table_name','purchase_returns')->select('_prefix')->first();
        return $data->_prefix ?? '';
    }
}
if (! function_exists('_damage_pfix')) {
    function _damage_pfix()
    {
       $data= InvoicePrefix::where('_table_name','damage_adjustments')->select('_prefix')->first();
        return $data->_prefix ?? '';
    }
}
if (! function_exists('_damage_pfix')) {
    function _damage_pfix()
    {
       $data= InvoicePrefix::where('_table_name','damage_adjustments')->select('_prefix')->first();
        return $data->_prefix ?? '';
    }
}



if (! function_exists('report_date_formate')) {
    function report_date_formate()
    {
        return 'd-m-Y';
    }
}

if (! function_exists('_view_date_formate')) {
    function _view_date_formate($_date)
    {
        if($_date ==''){
            return "";
        } 
       return date('d-m-Y', strtotime($_date));
    }
}



if (! function_exists('voucher_code_to_name')) {
    function voucher_code_to_name($value)
    {
        $types = VoucherType::where('_code',$value)->select('_name')->first();
        return $types->_name ?? '';
    }
}


if (! function_exists('prefix_taka')) {
    function prefix_taka($value="Tk")
    {
        
        return $value;
    }
}



//Database insert formate Date

if (! function_exists('change_date_format')) {
    function change_date_format($_date)
    {
      return   date('Y-m-d', strtotime($_date));
    }
}



if (! function_exists('_report_amount')) {
    function _report_amount($_amount)
    {
      return   number_format((float) $_amount ?? 0, default_des(), '.', ',');
    }
}


function find_bud_amount($ledger_id,$array_data=[]){
    if(sizeof($array_data) > 0){
        foreach($array_data as $key=>$val){
            if($ledger_id==$val->_ledger_id){
                return $val->_total_amount ?? 0;
            }
        }
    }else{
        return 0;
    }
    
}

function find_bud_column($ledger_id,$column_name,$array_data=[]){
    if(sizeof($array_data) > 0){
        foreach($array_data as $key=>$val){
            if($ledger_id==$val->_ledger_id){
                return $val->$column_name ?? '';
            }
        }
    }else{
        return '';
    }
    
}
function find_bud_item_column($item_id,$column_name,$array_data=[]){
    if(sizeof($array_data) > 0){
        foreach($array_data as $key=>$val){
            if($item_id==$val->_item_id){
                return $val->$column_name ?? '';
            }
        }
    }else{
        return '';
    }
    
}





if (! function_exists('default_des')) {
    function default_des()
    {
      return 3;
    }
}
if (! function_exists('_date_diff')) {
    function _date_diff($date1,$date2)
    {
        $date1 = date_create($date1);
        $date2 = date_create($date2);
        $diff1 = date_diff($date1,$date2);
        $daysdiff = $diff1->format("%R%a");
        return $daysdiff = abs($daysdiff)." Days";
    }
}


if (! function_exists('ledger_to_group_type')) {
    function ledger_to_group_type($ledger)
    {
      return AccountLedger::where('id',$ledger)->select('_account_group_id','_account_head_id')->first();
    }
}
if (! function_exists('_find_employee_name')) {
    function _find_employee_name($_office_id)
    {
      $data = \DB::table("hrm_employees")->where("_code",$_office_id)->first();

      return $data->_name ?? '';
    }
}

if (! function_exists('_find_ledger')) {
    function _find_ledger($ledger)
    {
      $ledger_info =  AccountLedger::where('id',$ledger)->select('_name')->first();
      return $ledger_info->_name ?? '';
    }
}

if (! function_exists('_find_unit')) {
    function _find_unit($unit)
    {
      $unit_info =  Units::where('id',$unit)->select('_name')->first();
      return $unit_info->_name ?? '';
    }
}
if (! function_exists('_table_name')) {
    function _table_name($id)
    {
      $table =  \App\Models\TableInfo::where('id',$id)->select('_name')->first();
      return $table->_name ?? '';
    }
}


if (! function_exists('_last_balance')) {
    function _last_balance($ledger)
    {
      return \DB::select(' select SUM(_dr_amount-_cr_amount) as _balance from accounts where _account_ledger="'.$ledger.'" ');
    }
}


if (! function_exists('nv_number_to_text')) {
    function  nv_number_to_text($amount)
    {

        $digit = new NumberFormatter("en", NumberFormatter::SPELLOUT);
        return prefix_taka().".  ".ucfirst($digit->format($amount ?? 0))." Only."; 
        
    }
}

if (! function_exists('_default_amount_dr_cr')) {
    function  _default_amount_dr_cr()
    {

        return 1; 
        
    }
}

if (! function_exists('_show_amount_dr_cr')) {
    function  _show_amount_dr_cr($amount)
    {
        $amount = (string) $amount;
        if($amount[0]==='-'){
            if(_default_amount_dr_cr()==1){
                $amount = substr($amount, 1);
                return $amount." Cr";
            }elseif(_default_amount_dr_cr()==2){
                 $amount = substr($amount, 1);
                 return "(".$amount.")";
            }else{
                return $amount;
            }
        }else{
           if(_default_amount_dr_cr()==1){
                return $amount." Dr";
            }elseif(_default_amount_dr_cr()==2){
                 return $amount;
            }else{
                return $amount;
            } 
        }
        
        
    }
}
if (! function_exists('database_backup_info')) {
     function database_backup_info(){
            $DbName             = env('DB_DATABASE');
            $get_all_table_query = "SHOW TABLES ";
            $result = DB::select(DB::raw($get_all_table_query));

            $prep = "Tables_in_$DbName";
            foreach ($result as $res){
                $tables[] =  $res->$prep;
            }



            $connect = DB::connection()->getPdo();

            $get_all_table_query = "SHOW TABLES";
            $statement = $connect->prepare($get_all_table_query);
            $statement->execute();
            $result = $statement->fetchAll();


            $output = '';
            foreach($tables as $table)
            {
                $show_table_query = "SHOW CREATE TABLE " . $table . "";
                $statement = $connect->prepare($show_table_query);
                $statement->execute();
                $show_table_result = $statement->fetchAll();
                //dump($show_table_result);

                foreach($show_table_result as $show_table_row)
                {
                    $output .= "\n\n" . $show_table_row["Create Table"] . ";\n\n";
                }
                $select_query = "SELECT * FROM " . $table . "";
                $statement = $connect->prepare($select_query);
                $statement->execute();
                $total_row = $statement->rowCount();

                for($count=0; $count<$total_row; $count++)
                {
                    $single_result = $statement->fetch(\PDO::FETCH_ASSOC);

                    $table_column_array = array_keys($single_result);
                    $table_value_array = array_values($single_result);
                    $remvoe_coma_from_array_values = [];
                    foreach ($table_value_array as $key => $value) {
                        $withoue_coma_value= str_replace( array( '\'', '"',
      ',' , ';', '<', '>','"' ), ' ', $value);
      array_push($remvoe_coma_from_array_values, $withoue_coma_value); 
                    }

                   //  dump($remvoe_coma_from_array_values);
                    $output .= "\nINSERT INTO $table (";
                    $output .= "" . implode(", ", $table_column_array) . ") VALUES (";
                    $output .= "'" . implode("','", $remvoe_coma_from_array_values) . "');\n";
                }
            }
            $file_name = 'database_backup_on_' . date('y-m-d') . '.sql';
            $file_handle = fopen($file_name, 'w+');
            fwrite($file_handle, $output);
            fclose($file_handle);
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename=' . basename($file_name));
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file_name));
            ob_clean();
            flush();
            readfile($file_name);
            unlink($file_name);


        }
}










