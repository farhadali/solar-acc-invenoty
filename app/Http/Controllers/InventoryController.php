<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\ItemCategory;
use App\Models\ItemInventory;
use App\Models\ProductPriceList;
use App\Models\Units;
use App\Models\Warranty;
use App\Models\UnitConversion;
use App\Models\PurchaseFormSettings;
use App\Models\GeneralSettings;
use App\Models\StoreHouse;
use App\Models\Purchase;
use App\Models\PurchaseDetail;
use Illuminate\Http\Request;
use Session;
use DB;



class InventoryController extends Controller
{


    function __construct()
    {
         $this->middleware('permission:item-information-list|item-information-create|item-information-edit|item-information-delete', ['only' => ['index','store']]);
         $this->middleware('permission:item-information-create', ['only' => ['create','store']]);
         $this->middleware('permission:item-information-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:item-information-delete', ['only' => ['destroy']]);
         $this->middleware('permission:item-sales-price-update', ['only' => ['salesPriceUpdate']]);
         $this->page_name = "Item Information";
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {


       // item_code_Regenerate(); 



         if($request->has('limit')){
            $limit = $request->limit ??  default_pagination();
            session()->put('_i_limit', $request->limit);
        }else{
             $limit= \Session::get('_i_limit') ??  default_pagination();
            
        }
        
        $_asc_desc = $request->_asc_desc ?? 'DESC';
        $asc_cloumn =  $request->asc_cloumn ?? 'id';
        $datas = Inventory::with(['_category','_units','_warranty_name']);
        if($request->has('_item') && $request->_item !=''){
            $datas = $datas->where('_item','like',"%$request->_item%");
        }
        if($request->has('_code') && $request->_code !=''){
            $datas = $datas->where('_code','like',"%$request->_code%");
        }
        if($request->has('_barcode') && $request->_barcode !=''){
            $datas = $datas->where('_barcode','like',"%$request->_barcode%");
        }
        if($request->has('_discount') && $request->_discount !=''){
            $datas = $datas->where('_discount','like',"%$request->_discount%");
        }
        if($request->has('_vat') && $request->_vat !=''){
            $datas = $datas->where('_vat','like',"%$request->_vat%");
        }
        if($request->has('_pur_rate') && $request->_pur_rate !=''){
            $datas = $datas->where('_pur_rate','like',"%$request->_pur_rate%");
        }
        if($request->has('_sale_rate') && $request->_sale_rate !=''){
            $datas = $datas->where('_sale_rate','like',"%$request->_sale_rate%");
        }
        if($request->has('_manufacture_company') && $request->_manufacture_company !=''){
            $datas = $datas->where('_manufacture_company','like',"%$request->_manufacture_company%");
        }
        if($request->has('_status') && $request->_status !=''){
            $datas = $datas->where('_status','=',$request->_status);
        }
        if($request->has('_unique_barcode') && $request->_unique_barcode !=''){
            $datas = $datas->where('_unique_barcode','=',$request->_unique_barcode);
        }
        if($request->has('_kitchen_item') && $request->_kitchen_item !=''){
            $datas = $datas->where('_kitchen_item','=',$request->_kitchen_item);
        }
        if($request->has('_category_id') && $request->_category_id !=''){
            $datas = $datas->where('_category_id','=',$request->_category_id);
        }
        if($request->has('_warranty') && $request->_warranty !=''){
            $datas = $datas->where('_warranty','=',$request->_warranty);
        }
        if($request->has('_reorder') && $request->_reorder !=''){
            $datas = $datas->where('_reorder','=',$request->_reorder);
        }
        if($request->has('_order_qty') && $request->_order_qty !=''){
            $datas = $datas->where('_order_qty','=',$request->_order_qty);
        }


        $datas = $datas->orderBy($asc_cloumn,$_asc_desc)->paginate($limit);
        $page_name = $this->page_name;

        $categories = ItemCategory::with(['_parents'])->orderBy('_name','asc')->get();
        $_warranties = Warranty::select('id','_name')->orderBy('_name','asc')->where('_status',1)->get();
        if($request->has('print')){
            if($request->print =="single"){
                return view('backend.item-information.master_print',compact('datas','page_name','request','limit','_warranties'));
            }
         }
          $units = Units::orderBy('_name','asc')->get();
        return view('backend.item-information.index',compact('datas','request','page_name','limit','categories','units','_warranties'));

    }


    public function fileUpload(Request $request){
                $file_full = $_FILES['file'];

                // Get file properties
                 $fileName = $file_full['name'];
                $fileTmpName = $file_full['tmp_name'];
                $fileSize = $file_full['size'];
                $fileError = $file_full['error'];
                $fileType = $file_full['type'];

                // Get file extension
                $fileExt = explode('.', $fileName);
                 $fileActualExt = strtolower(end($fileExt));

                // Allowed file extensions
                $allowedCSV = array('csv');
                $allowedExcel = array('xls', 'xlsx');
                 // Check if the file extension is a CSV or Excel file
                if (in_array($fileActualExt, $allowedCSV) || in_array($fileActualExt, $allowedExcel)) {

                    // Check for errors
                    if ($fileError === 0) {
                            // Generate a unique file name
                             $fileNameNew = uniqid('', true) . "." . $fileActualExt;
                             $fileDestination = 'uploads/' . $fileNameNew;

                            // Move the file to the destination
                            move_uploaded_file($fileTmpName, $fileDestination);

                            // Set the file destination
                            $log_file = $_FILES['file']['tmp_name'];
                            $extension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
                            if ($extension == "csv") {
                              $handle = fopen($fileDestination, "r");
                            } elseif ($extension == "xls" || $extension == "xlsx") {
                              require 'PHPExcel/IOFactory.php';
                              $objPHPExcel = PHPExcel_IOFactory::load($log_file);
                              $sheet = $objPHPExcel->getSheet(0);
                              $highestRow = $sheet->getHighestRow();
                              $highestColumn = $sheet->getHighestColumn();
                            }


                            if ($extension == "csv") {
                                $i =0;
                                $rowCount=0;
                              while (($data = fgetcsv($handle, 100000, ",")) !== FALSE) {
                                if($i !=0){
                                    $name = $data[0];
                                    $_generic_name = $data[1];
                                    $_strength = $data[2];
                                    $_item_category = $data[3];
                                    $_manufacture_company = $data[4];

                                    $Inventory = new Inventory();
                                    $Inventory->_item  = $name;
                                    $Inventory->_generic_name  = $_generic_name." ".$_strength;
                                    $Inventory->_strength  = $_strength;
                                    $Inventory->_category_id  = _find_category_id($_item_category);
                                    $Inventory->_item_category  = $_item_category;
                                    $Inventory->_manufacture_company  = $_manufacture_company;
                                    $Inventory->_unit_id  = 1;
                                    $Inventory->_status  = 1;
                                    $Inventory->save();
 

                                }
                                    $i++;
                                    $rowCount++;
                              }
                              fclose($handle);
                            }

                           return redirect('item-information')->with('success','Data Uploaded Successfully'); 
                    if ($extension == "xls" || $extension == "xlsx") {
                              for ($row = 1; $row <= $highestRow; $row++) {
                                    $date_time = $rowData[0][2];
                                    exit($rowData[0]);
                                    
                                    $row_number++;

                              }
                            }
                
            }
        }
    }

    public function itemWiseUnits(Request $request){
        $_item_id = $request["item_id"];
        $units = Units::orderBy('_name','asc')->get();
        $conversionUnits = UnitConversion::where('_item_id',$_item_id)
                        ->where('_status',1)->orderBy('id','asc')->get();
        return view('backend.item-information.unit_option',compact('conversionUnits','units'));
    }
    public function itemWiseUnitConversion(Request $request){
        $_item_id = $request["item_id"];
        $base_unit_name = $request["base_unit_name"];
       // $_item_id = $request->_item_id;
        $units = Units::orderBy('_name','asc')->get();
        $conversionUnits = UnitConversion::where('_item_id',$_item_id)->where('_status',1)->get();
        return view('backend.item-information.unit_ajax',compact('conversionUnits','units','base_unit_name'));
       
    }

    public function itemWiseUnitConversionSave(Request $request){
        $conversion_id = $request->conversion_id ?? [];
        $conversion_item_id = $request->conversion_item_id ?? [];
        $_base_unit_id = $request->_base_unit_id ?? [];
        $_conversion_qty = $request->_conversion_qty ?? [];
        $_conversion_unit = $request->_conversion_unit ?? [];
        $_converted_unit_names = $request->_converted_unit_names ?? [];
        
        $item_id = $request->item_id;
        UnitConversion::where('_item_id',$item_id)->update(['_status'=>0]);
        for ($i=0; $i <sizeof($conversion_id) ; $i++) { 
            if($conversion_id[$i]==0){
                $UnitConversion = new UnitConversion();
            }else{
                $UnitConversion = UnitConversion::find($conversion_id[$i]);
            }
            $UnitConversion->_item_id = $item_id;
            $UnitConversion->_base_unit_id = $_base_unit_id[$i];
            $UnitConversion->_conversion_qty = $_conversion_qty[$i];
            $UnitConversion->_conversion_unit = $_conversion_unit[$i];
            $UnitConversion->_conversion_unit_name = _find_unit($_conversion_unit[$i]);
            $UnitConversion->_status = 1;
            $UnitConversion->save();
        }
        $data["message"]="success";
        return json_encode($data);

    }

    public function labelPrint(Request $request){
        $page_name="Label Print";
         $_id = $request->_id ?? 0;
         $_type = $request->_type;
        $datas = [];
        if($_id !=0 && $_type =='purchase'){
              $datas = \App\Models\Purchase::with(['_master_details'])->find($_id);
        }
        if($_id !=0 && $_type =='production'){
              $datas = \App\Models\Production::with(['_master_details'])->find($_id);
        }
        
        return view('backend.item-information.label-print',compact('page_name','datas'));
    }

    public function barcodePrintStore(Request $request){
         $data =  $request->all();
         $barcode_setting = $request->barcode_setting ?? 1;
         if($barcode_setting ==1){
            return view('backend.item-information.final_print_1',compact('data'));
         }
         if($barcode_setting ==2){
            return view('backend.item-information.final_print_2',compact('data'));
         }
         if($barcode_setting ==3){
            return view('backend.item-information.final_print_3',compact('data'));
         }
         if($barcode_setting ==4){
            return view('backend.item-information.final_print_4',compact('data'));
         }
         if($barcode_setting ==5){
            return view('backend.item-information.final_print_5',compact('data'));
         }
         if($barcode_setting ==6){
            return view('backend.item-information.final_print_6',compact('data'));
         }

        
    }

     public function reset(){
        Session::flash('_i_limit');
       return  \Redirect::to('item-information?limit='.default_pagination());
    }

     public function lotReset(){
        Session::flash('_i_limit');
       return  \Redirect::to('lot-item-information?limit='.default_pagination());
    }


    public function lotItemInformation(Request $request){
        if($request->has('limit')){
            $limit = $request->limit ??  default_pagination();
            session()->put('_i_limit', $request->limit);
        }else{
             $limit= \Session::get('_i_limit') ??  default_pagination();
            
        }
        
        $_asc_desc = $request->_asc_desc ?? 'DESC';
        $asc_cloumn =  $request->asc_cloumn ?? 'id';
        
        $datas = ProductPriceList::with(['_units','_warranty_name'])->where('_qty','!=',0);
        if($request->has('_item') && $request->_item !=''){
            $datas = $datas->where('_item','like',"%$request->_item%");
        }
        if($request->has('_input_type') && $request->_input_type !=''){
            $datas = $datas->where('_input_type','LIKE',"%$request->_input_type%");
        }
        if($request->has('_code') && $request->_code !=''){
            $datas = $datas->where('_code','like',"%$request->_code%");
        }
        if($request->has('_barcode') && $request->_barcode !=''){
            $datas = $datas->where('_barcode','like',"%$request->_barcode%");
        }
        if($request->has('_discount') && $request->_discount !=''){
            $datas = $datas->where('_discount','like',"%$request->_discount%");
        }
        if($request->has('_vat') && $request->_vat !=''){
            $datas = $datas->where('_vat','like',"%$request->_vat%");
        }
        if($request->has('_pur_rate') && $request->_pur_rate !=''){
            $datas = $datas->where('_pur_rate','like',"%$request->_pur_rate%");
        }
        if($request->has('_sale_rate') && $request->_sale_rate !=''){
            $datas = $datas->where('_sale_rate','like',"%$request->_sale_rate%");
        }
        if($request->has('_manufacture_company') && $request->_manufacture_company !=''){
            $datas = $datas->where('_manufacture_company','like',"%$request->_manufacture_company%");
        }
        if($request->has('_status') && $request->_status !=''){
            $datas = $datas->where('_status','=',$request->_status);
        }
        if($request->has('_unique_barcode') && $request->_unique_barcode !=''){
            $datas = $datas->where('_unique_barcode','=',$request->_unique_barcode);
        }
        if($request->has('_category_id') && $request->_category_id !=''){
            $datas = $datas->where('_category_id','=',$request->_category_id);
        }
        if($request->has('_warranty') && $request->_warranty !=''){
            $datas = $datas->where('_warranty','=',$request->_warranty);
        }
        if($request->has('_unit_id') && $request->_unit_id !=''){
            $datas = $datas->where('_unit_id','=',$request->_unit_id);
        }
        if($request->has('_branch_id') && $request->_branch_id !=''){
            $datas = $datas->where('_branch_id','=',$request->_branch_id);
        }
        if($request->has('_cost_center_id') && $request->_cost_center_id !=''){
            $datas = $datas->where('_cost_center_id','=',$request->_cost_center_id);
        }
        if($request->has('_store_id') && $request->_store_id !=''){
            $datas = $datas->where('_store_id','=',$request->_store_id);
        }
        $datas = $datas->orderBy($asc_cloumn,$_asc_desc)->paginate($limit);
        $page_name ='Lot Wise Item Information';
         $_warranties = Warranty::select('id','_name')->orderBy('_name','asc')->get();

        $categories = ItemCategory::with('_parents')->orderBy('_name','asc')->get();
        if($request->has('print')){
            if($request->print =="single"){
                return view('backend.item-information.lot_print',compact('datas','page_name','request','limit','_warranties'));
            }
         }
          $units = Units::orderBy('_name','asc')->get();
         return view('backend.item-information.lot_item',compact('datas','request','page_name','limit','categories','units','_warranties'));
    }


    public function itemPurchaseSearch(Request $request){
        $limit = $request->limit ?? default_pagination();
        $_asc_desc = $request->_asc_desc ?? 'ASC';
        $asc_cloumn =  $request->asc_cloumn ?? '_item';
        $text_val = $request->_text_val;
        $datas = Inventory::with(['unit_conversion','_units'])->select('id','_item as _name','_code','_unit_id','_barcode','_discount','_vat','_pur_rate','_sale_rate','_manufacture_company','_unique_barcode','_warranty','_balance','_sd','_cd','_ait','_rd','_at','_tti')
            ->where('_status',1);
         if($request->has('_text_val') && $request->_text_val !=''){
            $datas = $datas->where('_item','like',"%$request->_text_val%")
            ->orWhere('_code','like',"%$request->_text_val%")
            ->orWhere('_barcode','like',"%$request->_text_val%")
            ->orWhere('id','like',"%$request->_text_val%");
        }
        
        
        
        $datas = $datas->orderBy($asc_cloumn,$_asc_desc)->paginate($limit);
        return json_encode( $datas);
    }


    public function salesPriceEdit($id){
        $data = ProductPriceList::where('_qty','>',0)->find($id);

         $page_name = " Lot Wise Price Update -".$data->_item ?? '';
         
        $categories = ItemCategory::orderBy('_name','asc')->get();
         $units = Units::orderBy('_name','asc')->get();
         $_warranties = Warranty::select('id','_name')->orderBy('_name','asc')->get();
       return view('backend.item-information.lot_edit',compact('page_name','categories','data','units','_warranties'));
    }

    public function salesPriceUpdate(Request $request){

        

        $this->validate($request, [
            'id' => 'required',
            '_item' => 'required',
            '_unit_id' => 'required',
            '_sales_rate' => 'required',
            '_status' => 'required'
        ]);
        
        $data = ProductPriceList::find($request->id);
        $data->_item = $request->_item;
        $data->_unit_id = $request->_unit_id;
        $data->_barcode = $request->_barcode ?? '';
        $data->_p_discount_input = $request->_p_discount_input ?? 0;
        $data->_p_vat = $request->_p_vat ?? 0;
        $data->_sales_rate = $request->_sales_rate ?? 0;
        $data->_status = $request->_status ?? 0;
        $data->_unique_barcode = $request->_unique_barcode ?? 0;
        $data->_warranty = $request->_warranty ?? 0;
        $data->save();
        return redirect()->back()->with('success','Information save successfully');
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
     public function create()
    {
        $users =\Auth::user();
        $page_name = $this->page_name;
        $categories = ItemCategory::with(['_parents'])->orderBy('_name','asc')->get();
        $units = Units::orderBy('_name','asc')->get();
        $_warranties = Warranty::select('id','_name')->orderBy('_name','asc')->get();
        $permited_branch = permited_branch(explode(',',$users->branch_ids));
        $permited_costcenters = permited_costcenters(explode(',',$users->cost_center_ids));
        $store_houses = permited_stores(explode(',',$users->store_ids));

        

       return view('backend.item-information.create',compact('page_name','categories','units','_warranties','permited_branch','permited_costcenters','store_houses'));
    }

    public function showManufactureCompanys(Request $request){
         $limit = $request->limit ?? default_pagination();
        $_asc_desc = $request->_asc_desc ?? 'ASC';
        $asc_cloumn =  $request->asc_cloumn ?? '_manufacture_company';
        $text_val = $request->_text_val;

         $datas = Inventory::select('_manufacture_company');
         if($request->has('_text_val') && $request->_text_val !=''){
            $datas = $datas->where('_manufacture_company','like',"%$request->_text_val%");
        }
        
        $datas = $datas->distinct()->orderBy($asc_cloumn,$_asc_desc)->paginate($limit);

        return response($datas);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

      //  return dump($request->all());
         $this->validate($request, [
            '_category_id' => 'required',
            '_item' => 'required|unique:inventories,_item',
            '_unit_id' => 'required',
            '_status' => 'required'
        ]);

         DB::beginTransaction();
        try {

            $_category_id = $request->_category_id;
            $_item = $request->_item;
            $_unit_id = $request->_unit_id;
            $_warranty = $request->_warranty;
            $_manufacture_company = $request->_manufacture_company;
            $_code = $request->_code;
            $_barcode = $request->_barcode;
            $_discount = $request->_discount;
            $_vat = $request->_vat;
            $_branch_id = $request->_branch_id;
            $_cost_center_id = $request->_cost_center_id;
            $_store_id = $request->_store_id;
            $_opening_qty = $request->_opening_qty;
            $_pur_rate = $request->_pur_rate;
            $_sale_rate = $request->_sale_rate;
            $_reorder = $request->_reorder ?? 0;
            $_order_qty = $request->_order_qty ?? 0;
            $_kitchen_item = $request->_kitchen_item ?? 0;
            $_unique_barcode = $request->_unique_barcode ?? 0;
            $_status = $request->_status ?? 0;



        $item_codes =  item_code_generate($request->_category_id);
        $_serial =     $item_codes["_serial"];
        $full_product_code = $item_codes["full_product_code"];

        $users =\Auth::user();

        $data = new Inventory();
        $data->_item = trim($request->_item);
        $data->_unit_id = $request->_unit_id;
        $data->_code = $request->_code ?? $full_product_code;
        $data->_serial = $_serial;
        if($request->_unique_barcode ==0){
            $data->_barcode = $request->_barcode ?? $full_product_code;
        }
        
        $data->_category_id = $request->_category_id;
        $data->_discount = $request->_discount ?? 0;
        $data->_vat = $request->_vat ?? 0;
        $data->_pur_rate = $request->_pur_rate ?? 0;
        $data->_sale_rate = $request->_sale_rate ?? 0;
        $data->_manufacture_company = $request->_manufacture_company;
        $data->_status = $request->_status ?? 0;
        $data->_unique_barcode = $request->_unique_barcode ?? 0;
        $data->_warranty = $request->_warranty ?? 0;
        $data->_reorder = $request->_reorder ?? 0;
        $data->_order_qty = $request->_order_qty ?? 0;
        $data->_kitchen_item = $request->_kitchen_item ?? 0;
        $data->_opening_qty = $request->_opening_qty ?? 0;
        $data->_balance = $request->_opening_qty ?? 0;
        $data->_created_by = $users->id."-".$users->name;
        if($request->hasFile('_image')){ 
                $_image = UserImageUpload($request->_image); 
                $data->_image = $_image;
        }
        $data->save();


        $item_info = $data;

       

        $_item_id = $data->id;
        $_unit_id = $request->_unit_id;
        $_conversion_qty = 1;
        $_conversion_unit= $request->_unit_id;
        $unit_status= 1;
        unitConversation_save($_item_id,$_unit_id,$_conversion_qty,$_conversion_unit,$unit_status);

         $_opening_qty = $request->_opening_qty ?? 0;

         if($_opening_qty > 0){

            $PurchaseFormSettings = PurchaseFormSettings::first();
            $_default_inventory = $PurchaseFormSettings->_default_inventory;
            $_default_purchase = $PurchaseFormSettings->_default_purchase;
            $general_settings =GeneralSettings::first();
            $_opening_ledger = $general_settings->_opening_ledger ?? 49;

            $_pur_rate = $request->_pur_rate ?? 0;
            $__sub_total = ($_pur_rate*$_opening_qty);
            $__discount_input = 0;
            $__total_discount = 0;
            $_total_vat = 0;
            $__total = ($_pur_rate*$_opening_qty);

            $Purchase = new Purchase();
            $Purchase->_date = change_date_format(date('Y-m-d'));
            $Purchase->_time = date('H:i:s');
            $Purchase->_order_ref_id = '';
            $Purchase->_referance = 'Opening Inventory';
            $Purchase->_ledger_id = $_opening_ledger;
            $Purchase->_created_by = $users->id."-".$users->name;
            $Purchase->_user_id = $users->id;
            $Purchase->_user_name = $users->name;
            $Purchase->_note = 'Opening Inventory';;
            $Purchase->_sub_total = $__sub_total;
            $Purchase->_discount_input = $__discount_input;
            $Purchase->_total_discount = $__total_discount;
            $Purchase->_total_vat = $_total_vat;
            $Purchase->_total =  $__total;
            $Purchase->_branch_id = $request->_branch_id;
            $Purchase->_cost_center_id = $request->_cost_center_id;
            $Purchase->_address = 'N/A';
            $Purchase->_phone = 'N/A';
            $Purchase->_status = 1;
            $Purchase->_lock = $request->_lock ?? 0;
            $Purchase->save();
            $purchase_id = $Purchase->id;
            
            $_pfix = _purchase_pfix().$purchase_id;
             \DB::table('purchases')
             ->where('id',$purchase_id)
             ->update(['_order_number'=>$_pfix]);


                $PurchaseDetail = new PurchaseDetail();
                $PurchaseDetail->_item_id = $_item_id;
                $PurchaseDetail->_qty = $_opening_qty;

                $PurchaseDetail->_transection_unit = 1;
                $PurchaseDetail->_unit_conversion = 1;
                $PurchaseDetail->_base_unit = $_unit_id;

                $PurchaseDetail->_barcode = '';
                $PurchaseDetail->_rate = $_pur_rate;
                $PurchaseDetail->_short_note = '';
                $PurchaseDetail->_sales_rate = $request->_sale_rate ?? 0;
                $PurchaseDetail->_discount = 0;
                $PurchaseDetail->_discount_amount = 0;
                $PurchaseDetail->_vat =0 ;
                $PurchaseDetail->_vat_amount = 0;
                $PurchaseDetail->_value = $__total ?? 0;
                $PurchaseDetail->_branch_id = $request->_branch_id ?? 1;
                $PurchaseDetail->_cost_center_id = $request->_cost_center_id ?? 1;
                $PurchaseDetail->_store_id = $request->_store_id ?? 1;
                $PurchaseDetail->_store_salves_id = '';
                $PurchaseDetail->_no = $purchase_id;
                $PurchaseDetail->_status = 1;
                $PurchaseDetail->_created_by = $users->id."-".$users->name;
                $PurchaseDetail->save();
                $_purchase_detail_id = $PurchaseDetail->id;




                $ProductPriceList = new ProductPriceList();
                $ProductPriceList->_item_id = $item_info->id;
                $ProductPriceList->_item = $item_info->_item ?? '';
                $ProductPriceList->_manufacture_date =null;
                $ProductPriceList->_expire_date = null;
                $ProductPriceList->_qty = $_opening_qty;
                $ProductPriceList->_pur_rate = $_pur_rate ;
                $ProductPriceList->_sales_rate =$request->_sale_rate ?? 0;
                //Unit Conversion section
                $ProductPriceList->_transection_unit = $item_info->_unit_id ?? 1;
                $ProductPriceList->_unit_conversion = 1;
                $ProductPriceList->_base_unit = $item_info->_unit_id ?? 1;
                $ProductPriceList->_unit_id = $item_info->_unit_id ?? 1;
                $ProductPriceList->_unique_barcode = $item_info->_unique_barcode ?? 0;
                $ProductPriceList->_warranty = $item_info->_warranty ?? 0;
                $ProductPriceList->_sales_discount = $item_info->_discount ?? 0;
                $ProductPriceList->_p_discount_input =  0;
                $ProductPriceList->_p_discount_amount =  0;
                $ProductPriceList->_p_vat =  0;
                $ProductPriceList->_p_vat_amount =  0;
                $ProductPriceList->_sales_vat = $item_info->_vat ?? 0;;
                $ProductPriceList->_value =$__total ?? 0;
                $ProductPriceList->_purchase_detail_id =$_purchase_detail_id;
                $ProductPriceList->_master_id = $purchase_id;
                $ProductPriceList->_branch_id = $request->_branch_id ?? 1;
                $ProductPriceList->_cost_center_id = $request->_cost_center_id ?? 1;
                $ProductPriceList->_store_salves_id = '';
                $ProductPriceList->_store_id = $request->_store_id ?? 1;
                $ProductPriceList->_status =1;
                $ProductPriceList->_created_by = $users->id."-".$users->name;
                $ProductPriceList->save();
                $product_price_id =  $ProductPriceList->id;
                

                $ItemInventory = new ItemInventory();
                $ItemInventory->_item_id =  $item_info->id ?? '';
                $ItemInventory->_item_name =  $item_info->_item ?? '';
                $ItemInventory->_unit_id =  $item_info->_unit_id ?? '';
                $ItemInventory->_category_id = $request->_category_id;
                $ItemInventory->_date = change_date_format(date('Y-m-d'));
                $ItemInventory->_time = date('H:i:s');
                $ItemInventory->_transection = "Purchase";
                $ItemInventory->_transection_ref = $purchase_id;
                $ItemInventory->_transection_detail_ref_id = $_purchase_detail_id;

                $ItemInventory->_qty = $_opening_qty;
                $ItemInventory->_rate = $_pur_rate;
                $ItemInventory->_cost_rate =  $_pur_rate;
                 //Unit Conversion section
                $ItemInventory->_transection_unit = $item_info->_unit_id ?? 1;
                $ItemInventory->_unit_conversion =  1;
                $ItemInventory->_base_unit = $item_info->_unit_id ?? 1;
                $ItemInventory->_unit_id = $item_info->_unit_id ?? 1;

                $ItemInventory->_cost_value = $__total;
                $ItemInventory->_value = $__total ?? 0;
                $ItemInventory->_branch_id = $request->_branch_id ?? 1;
                $ItemInventory->_store_id = $request->_store_id ?? 1;
                $ItemInventory->_cost_center_id = $request->_cost_center_id ?? 1;
                $ItemInventory->_store_salves_id =  '';
                $ItemInventory->_status = 1;
                $ItemInventory->_created_by = $users->id."-".$users->name;
                $ItemInventory->save(); 


        $_ref_master_id=$purchase_id;
        $_ref_detail_id=$purchase_id;
        $_short_narration='N/A';
        $_narration = 'Opening Inventory';
        $_reference= 'Opening Inventory';
        $_transaction= 'Purchase';
        $_date = change_date_format(date('Y-m-d'));
        $_table_name = 'purchases';
        $_branch_id = $request->_branch_id;
        $_cost_center =   $request->_cost_center_id;
        $_name =$users->name;

        $__sub_total = $__total ?? 0;
        
        if($__sub_total > 0){

            //Default Purchase
            account_data_save($_ref_master_id,$_ref_detail_id,_find_ledger($_opening_ledger),$_narration,$_reference,$_transaction,$_date,$_table_name,$_default_purchase,$__sub_total,0,$_branch_id,$_cost_center,$_name,1);
            //Default Supplier
            account_data_save($_ref_master_id,$_ref_detail_id,_find_ledger($_default_purchase),$_narration,$_reference,$_transaction,$_date,$_table_name,$_opening_ledger,0,$__sub_total,$_branch_id,$_cost_center,$_name,2);

            //Default Inventory
            account_data_save($_ref_master_id,$_ref_detail_id,_find_ledger($_default_purchase),$_narration,$_reference,$_transaction,$_date,$_table_name,$_default_inventory,$__sub_total,0,$_branch_id,$_cost_center,$_name,3);
            //Default Purchase 
            account_data_save($_ref_master_id,$_ref_detail_id,_find_ledger($_default_inventory),$_narration,$_reference,$_transaction,$_date,$_table_name,$_default_purchase,0,$__sub_total,$_branch_id,$_cost_center,$_name,4);
        }

}


         DB::commit();
            return redirect()->back()->with('success','Information save successfully');
       } catch (\Exception $e) {
           DB::rollback();
           return redirect()->back()->with('danger','There is Something Wrong !');
        }

        
        
                                
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function ajaxItemSave(Request $request)
    {

        //return dump($request->all());

        

        

        

         DB::beginTransaction();
        try {

        $users = \Auth::user();

            $_item_item = trim($request->_item_item ?? '');
        $old_item_check = Inventory::where('_item',$_item_item)->first();
        if($old_item_check){
            $id = 0;
            return $id;
        }

         $item_codes =  item_code_generate($request->_category_id);
         $_serial =     $item_codes["_serial"];
         $full_product_code = $item_codes["full_product_code"];
         $_opening_qty = $request->_item_opening_qty ?? 0;
         $_sale_rate = $request->_item_sale_rate ?? 0;

         
        $data = new Inventory();
        $data->_item = trim($request->_item_item);
        $data->_code = $request->_code ?? $full_product_code;
        $data->_serial = $_serial;
        $data->_unit_id = $request->_item_unit_id;
        $data->_barcode = $request->_item_barcode;
        $data->_category_id = $request->_category_id;
        $data->_discount = $request->_item_discount ?? 0;
        $data->_balance = $_opening_qty ?? 0;
        $data->_vat = $request->_item_vat ?? 0;
        $data->_pur_rate = $request->_item_pur_rate ?? 0;
        $data->_sale_rate = $request->_item_sale_rate ?? 0;
        $data->_manufacture_company = $request->_item_manufacture_company;
        $data->_status = $request->_item_status ?? 0;
        $data->_unique_barcode = $request->_item_unique_barcode ?? 0;
        $data->_kitchen_item = $request->_item_kitchen_item ?? 0;
        $data->_created_by = \Auth::user()->id."-".\Auth::user()->name;
        $data->save();
        $id = $data->id;

        $_item_id = $data->id;
        $_unit_id = $data->_unit_id;
        $_conversion_qty = 1;
        $_conversion_unit= $data->_unit_id;
        $_item_name = $data->_item;

        $unit_status= 1;
        $action= 0;
        unitConversation_save($_item_id,$_unit_id,$_conversion_qty,$_conversion_unit,$unit_status,$action);


        $item_info = $data;

        $_item_branch_id = $request->_item_branch_id ?? 1;
        $_item_store_id = $request->_item_store_id ?? 1;
        $_item_cost_center_id = $request->_item_cost_center_id ?? 1;

       

       

         

         if($_opening_qty > 0){

            $PurchaseFormSettings = PurchaseFormSettings::first();
            $_default_inventory = $PurchaseFormSettings->_default_inventory;
            $_default_purchase = $PurchaseFormSettings->_default_purchase;
            $general_settings =GeneralSettings::first();
            $_opening_ledger = $general_settings->_opening_ledger ?? 49;

            $_pur_rate = $request->_item_pur_rate ?? 0;
            $__sub_total = ($_pur_rate*$_opening_qty);
            $__discount_input = 0;
            $__total_discount = 0;
            $_total_vat = 0;
            $__total = ($_pur_rate*$_opening_qty);

            $Purchase = new Purchase();
            $Purchase->_date = change_date_format(date('Y-m-d'));
            $Purchase->_time = date('H:i:s');
            $Purchase->_order_ref_id = '';
            $Purchase->_referance = 'Opening Inventory';
            $Purchase->_ledger_id = $_opening_ledger;
            $Purchase->_created_by = $users->id."-".$users->name;
            $Purchase->_user_id = $users->id;
            $Purchase->_user_name = $users->name;
            $Purchase->_note = 'Opening Inventory';;
            $Purchase->_sub_total = $__sub_total;
            $Purchase->_discount_input = $__discount_input;
            $Purchase->_total_discount = $__total_discount;
            $Purchase->_total_vat = $_total_vat;
            $Purchase->_total =  $__total;
            $Purchase->_branch_id = $_item_branch_id;
            $Purchase->_cost_center_id = $_item_cost_center_id;
            $Purchase->_address = 'N/A';
            $Purchase->_phone = 'N/A';
            $Purchase->_status = 1;
            $Purchase->_lock = $request->_lock ?? 0;
            $Purchase->save();
            $purchase_id = $Purchase->id;




                $PurchaseDetail = new PurchaseDetail();
                $PurchaseDetail->_item_id = $_item_id;
                $PurchaseDetail->_qty = $_opening_qty;

                $PurchaseDetail->_transection_unit = 1;
                $PurchaseDetail->_unit_conversion = 1;
                $PurchaseDetail->_base_unit = $_unit_id;

                $PurchaseDetail->_barcode = '';
                $PurchaseDetail->_rate = $_pur_rate;
                $PurchaseDetail->_short_note = '';
                $PurchaseDetail->_sales_rate = $_sale_rate ?? 0;
                $PurchaseDetail->_discount = 0;
                $PurchaseDetail->_discount_amount = 0;
                $PurchaseDetail->_vat =0 ;
                $PurchaseDetail->_vat_amount = 0;
                $PurchaseDetail->_value = $__total ?? 0;
                $PurchaseDetail->_branch_id = $_item_branch_id ?? 1;
                $PurchaseDetail->_cost_center_id = $_item_cost_center_id ?? 1;
                $PurchaseDetail->_store_id = $_item_store_id ?? 1;
                $PurchaseDetail->_store_salves_id = '';
                $PurchaseDetail->_no = $purchase_id;
                $PurchaseDetail->_status = 1;
                $PurchaseDetail->_created_by = $users->id."-".$users->name;
                $PurchaseDetail->save();
                $_purchase_detail_id = $PurchaseDetail->id;




                $ProductPriceList = new ProductPriceList();
                $ProductPriceList->_item_id = $item_info->id;
                $ProductPriceList->_item = $item_info->_item ?? '';
                $ProductPriceList->_manufacture_date =null;
                $ProductPriceList->_expire_date = null;
                $ProductPriceList->_qty = $_opening_qty;
                $ProductPriceList->_pur_rate = $_pur_rate ;
                $ProductPriceList->_sales_rate =$_sale_rate ?? 0;
                //Unit Conversion section
                $ProductPriceList->_transection_unit = $item_info->_unit_id ?? 1;
                $ProductPriceList->_unit_conversion = 1;
                $ProductPriceList->_base_unit = $item_info->_unit_id ?? 1;
                $ProductPriceList->_unit_id = $item_info->_unit_id ?? 1;
                $ProductPriceList->_unique_barcode = $item_info->_unique_barcode ?? 0;
                $ProductPriceList->_warranty = $item_info->_warranty ?? 0;
                $ProductPriceList->_sales_discount = $item_info->_discount ?? 0;
                $ProductPriceList->_p_discount_input =  0;
                $ProductPriceList->_p_discount_amount =  0;
                $ProductPriceList->_p_vat =  0;
                $ProductPriceList->_p_vat_amount =  0;
                $ProductPriceList->_sales_vat = $item_info->_vat ?? 0;;
                $ProductPriceList->_value =$__total ?? 0;
                $ProductPriceList->_purchase_detail_id =$_purchase_detail_id;
                $ProductPriceList->_master_id = $purchase_id;
                $ProductPriceList->_branch_id = $_item_branch_id ?? 1;
                $ProductPriceList->_cost_center_id = $_item_cost_center_id ?? 1;
                $ProductPriceList->_store_salves_id = '';
                $ProductPriceList->_store_id = $_item_store_id ?? 1;
                $ProductPriceList->_status =1;
                $ProductPriceList->_created_by = $users->id."-".$users->name;
                $ProductPriceList->save();
                $product_price_id =  $ProductPriceList->id;
                

                $ItemInventory = new ItemInventory();
                $ItemInventory->_item_id =  $item_info->id ?? '';
                $ItemInventory->_item_name =  $item_info->_item ?? '';
                $ItemInventory->_unit_id =  $item_info->_unit_id ?? '';
                $ItemInventory->_category_id = $request->_category_id;
                $ItemInventory->_date = change_date_format(date('Y-m-d'));
                $ItemInventory->_time = date('H:i:s');
                $ItemInventory->_transection = "Purchase";
                $ItemInventory->_transection_ref = $purchase_id;
                $ItemInventory->_transection_detail_ref_id = $_purchase_detail_id;

                $ItemInventory->_qty = $_opening_qty;
                $ItemInventory->_rate = $_pur_rate;
                $ItemInventory->_cost_rate =  $_pur_rate;
                 //Unit Conversion section
                $ItemInventory->_transection_unit = $item_info->_unit_id ?? 1;
                $ItemInventory->_unit_conversion =  1;
                $ItemInventory->_base_unit = $item_info->_unit_id ?? 1;
                $ItemInventory->_unit_id = $item_info->_unit_id ?? 1;

                $ItemInventory->_cost_value = $__total;
                $ItemInventory->_value = $__total ?? 0;
                $ItemInventory->_branch_id = $_item_branch_id ?? 1;
                $ItemInventory->_store_id = $_item_store_id ?? 1;
                $ItemInventory->_cost_center_id = $_item_cost_center_id ?? 1;
                $ItemInventory->_store_salves_id =  '';
                $ItemInventory->_status = 1;
                $ItemInventory->_created_by = $users->id."-".$users->name;
                $ItemInventory->save(); 


        $_ref_master_id=$purchase_id;
        $_ref_detail_id=$purchase_id;
        $_short_narration='N/A';
        $_narration = 'Opening Inventory';
        $_reference= 'Opening Inventory';
        $_transaction= 'Purchase';
        $_date = change_date_format(date('Y-m-d'));
        $_table_name = 'purchases';
        $_branch_id = $_item_branch_id;
        $_cost_center =   $_item_cost_center_id;
        $_name =$users->name;

        $__sub_total = $__total ?? 0;
        
        if($__sub_total > 0){

            //Default Purchase
            account_data_save($_ref_master_id,$_ref_detail_id,_find_ledger($_opening_ledger),$_narration,$_reference,$_transaction,$_date,$_table_name,$_default_purchase,$__sub_total,0,$_branch_id,$_cost_center,$_name,1);
            //Default Supplier
            account_data_save($_ref_master_id,$_ref_detail_id,_find_ledger($_default_purchase),$_narration,$_reference,$_transaction,$_date,$_table_name,$_opening_ledger,0,$__sub_total,$_branch_id,$_cost_center,$_name,2);

            //Default Inventory
            account_data_save($_ref_master_id,$_ref_detail_id,_find_ledger($_default_purchase),$_narration,$_reference,$_transaction,$_date,$_table_name,$_default_inventory,$__sub_total,0,$_branch_id,$_cost_center,$_name,3);
            //Default Purchase 
            account_data_save($_ref_master_id,$_ref_detail_id,_find_ledger($_default_inventory),$_narration,$_reference,$_transaction,$_date,$_table_name,$_default_purchase,0,$__sub_total,$_branch_id,$_cost_center,$_name,4);
        }

}


         DB::commit();
            return $id;
       } catch (\Exception $e) {
           DB::rollback();
           $id=0;
           return $id;
        }

                                        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Inventory  $inventory
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $page_name = $this->page_name;
         $data= Inventory::with(['_category','_units'])->find($id);
        $categories = ItemCategory::with(['_parents'])->orderBy('_name','asc')->get();
         $_warranties = Warranty::select('id','_name')->orderBy('_name','asc')->get();
       return view('backend.item-information.show',compact('page_name','categories','data','_warranties'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Inventory  $inventory
     * @return \Illuminate\Http\Response
     */
    public function edit( $id)
    {
         $page_name = $this->page_name;
         $data= Inventory::find($id);
         $categories = ItemCategory::with(['_parents'])->orderBy('_name','asc')->get();
         $units = Units::orderBy('_name','asc')->get();
        $_warranties = Warranty::select('id','_name')->orderBy('_name','asc')->get();
       return view('backend.item-information.edit',compact('page_name','categories','data','units','_warranties'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Inventory  $inventory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
       $this->validate($request, [
            '_category_id' => 'required',
            '_item' => 'required|unique:inventories,_item,'.$request->id,
            '_unit_id' => 'required',
            'id' => 'required',
            '_status' => 'required'
        ]);

        


        $data = Inventory::find($request->id);
        $__item_id = $request->id;
        $__unit_id = $data->_unit_id;

        UnitConversion::where('_item_id',$__item_id)
                        ->where('_base_unit_id',$__unit_id)
                        ->where('_conversion_unit',$__unit_id)
                        ->update(['_status'=>0]);

        $data->_item = trim($request->_item);
        $data->_code = $request->_code;
        $data->_unit_id = $request->_unit_id;
        $data->_barcode = $request->_barcode;
        $data->_category_id = $request->_category_id;
        $data->_discount = $request->_discount ?? 0;
        $data->_vat = $request->_vat ?? 0;
        $data->_pur_rate = $request->_pur_rate ?? 0;
        $data->_sale_rate = $request->_sale_rate ?? 0;
        $data->_manufacture_company = $request->_manufacture_company;
        $data->_status = $request->_status ?? 0;
        $data->_unique_barcode = $request->_unique_barcode ?? 0;
        $data->_kitchen_item = $request->_kitchen_item ?? 0;
        $data->_warranty = $request->_warranty ?? 0;
        $data->_reorder = $request->_reorder ?? 0;
        $data->_order_qty = $request->_order_qty ?? 0;
        $data->_updated_by = \Auth::user()->id."-".\Auth::user()->name;
        if($request->hasFile('_image')){ 
                $_image = UserImageUpload($request->_image); 
                $data->_image = $_image;
        }
        $data->save();

        
            $UnitConversion = UnitConversion::where('_item_id',$request->id)
                                            ->where('_base_unit_id',$request->_unit_id)
                                            ->where('_conversion_unit',$request->_unit_id)
                                            ->first();
            if(!$UnitConversion){
                $UnitConversion  =new UnitConversion();
            }
            
            $UnitConversion->_item_id = $request->id;
            $UnitConversion->_base_unit_id =$request->_unit_id ;
            $UnitConversion->_conversion_qty = 1;
            $UnitConversion->_conversion_unit = $request->_unit_id;
            $UnitConversion->_status = 1;
            $UnitConversion->save();

            if($request->has('_update_all_item_name') && $request->_update_all_item_name==1){
                ProductPriceList::where('_item_id',$request->id)->update(['_item'=>$request->_item]);
            }

        
        return redirect('item-information')->with('success','Information save successfully');
       
    }


    

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Inventory  $inventory
     * @return \Illuminate\Http\Response
     */
      public function destroy($id)
    {
        
      
        $numOfAccount = ItemInventory::where('_item_id',$id)->count();
        if($numOfAccount ==0){
            Inventory::find($id)->delete();
            return redirect('item-information')->with('success','Information deleted successfully');
        }else{
             return "You Can not delete this Information";
        }
        
    }
}
