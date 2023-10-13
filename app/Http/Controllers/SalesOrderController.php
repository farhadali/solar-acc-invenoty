<?php

namespace App\Http\Controllers;

use App\Models\SalesOrder;
use Illuminate\Http\Request;
use App\Models\VoucherMaster;
use App\Models\AccountLedger;
use App\Models\AccountGroup;
use App\Models\AccountHead;
use App\Models\Accounts;
use App\Models\Branch;
use App\Models\VoucherType;
use App\Models\StoreHouse;
use App\Models\SalesFormSetting;
use App\Models\SalesOrderDetail;
use App\Models\Inventory;
use App\Models\ItemCategory;
use App\Models\Units;
use Auth;
use DB;
use Session;

class SalesOrderController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:sales-order-list|sales-order-create|sales-order-edit|sales-order-delete|sales-order-print', ['only' => ['index','store']]);
         $this->middleware('permission:sales-order-print', ['only' => ['sales-orderPrint']]);
         $this->middleware('permission:sales-order-create', ['only' => ['create','store']]);
         $this->middleware('permission:sales-order-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:sales-order-delete', ['only' => ['destroy']]);
         $this->page_name = "Sales Order/Quotation";
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
            session()->put('_pur_limit', $request->limit);
        }else{
             $limit= \Session::get('_pur_limit') ??  default_pagination();
            
        }
        
        $_asc_desc = $request->_asc_desc ?? 'DESC';
        $asc_cloumn =  $request->asc_cloumn ?? 'id';

        $datas = SalesOrder::with(['_master_branch','_ledger','_master_details']);
        $datas = $datas->whereIn('_branch_id',explode(',',\Auth::user()->branch_ids));
        if($request->has('_branch_id') && $request->_branch_id !=""){
            $datas = $datas->where('_branch_id',$request->_branch_id);  
        }else{
           if($auth_user->user_type !='admin'){
                $datas = $datas->where('_user_id',$auth_user->id);   
            } 
        }
        

        if($request->has('_user_date') && $request->_user_date=="yes" && $request->_datex !="" && $request->_datex !=""){
            $_datex =  change_date_format($request->_datex);
            $_datey=  change_date_format($request->_datey);

             $datas = $datas->whereDate('_date','>=', $_datex);
            $datas = $datas->whereDate('_date','<=', $_datey);
        }

        if($request->has('id') && $request->id !=""){
            $ids =  array_map('intval', explode(',', $request->id ));
            $datas = $datas->whereIn('id', $ids); 
        }
        
        if($request->has('_order_ref_id') && $request->_order_ref_id !=''){
            $datas = $datas->where('_order_ref_id','like',"%trim($request->_order_ref_id)%");
        }

        if($request->has('_referance') && $request->_referance !=''){
            $datas = $datas->where('_referance','like',"%trim($request->_referance)%");
        }
        if($request->has('_note') && $request->_note !=''){
            $datas = $datas->where('_note','like',"%trim($request->_note)%");
        }
        if($request->has('_user_name') && $request->_user_name !=''){
            $datas = $datas->where('_user_name','like',"%trim($request->_user_name)%");
        }
        
        if($request->has('_sub_total') && $request->_sub_total !=''){
            $datas = $datas->where('_sub_total','=',trim($request->_sub_total));
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
                return view('backend.sales-order.master_print',compact('datas','page_name','account_types','request','account_groups','current_date','current_time','limit','form_settings','permited_branch','permited_costcenters','store_houses'));
            }

            if($request->print =="detail"){
                return view('backend.sales-order.details_print',compact('datas','page_name','account_types','request','account_groups','current_date','current_time','limit','form_settings','permited_branch','permited_costcenters','store_houses'));
            }
         }

        return view('backend.sales-order.index',compact('datas','page_name','account_types','request','account_groups','current_date','limit','form_settings','permited_branch','permited_costcenters','store_houses'));
    }

     public function reset(){
        Session::flash('_pur_limit');
       return  \Redirect::to('sales-order?limit='.default_pagination());
    }


   

    public function SalesOrderDetails(Request $request){
        $this->validate($request, [
            '_sales_main_id' => 'required',
        ]);
        $_sales_main_id = $request->_sales_main_id;

       $datas = SalesOrderDetail::with(['_detail_branch','_detail_cost_center','_store','_items'])
                                ->where('_no',$_sales_main_id)
                                ->get();
      
        
        return json_encode( $datas);
    }

    public function orderSearch(Request $request){
      
        $limit = $request->limit ?? default_pagination();
        $_asc_desc = $request->_asc_desc ?? 'ASC';
        $asc_cloumn =  $request->asc_cloumn ?? '_date';
        $text_val = $request->_text_val;
        $_branch_id = $request->_branch_id;
        $datas = SalesOrder::with(['_ledger'])->where('_status',1)->where('_branch_id',$_branch_id);
         if($request->has('_text_val') && $request->_text_val !=''){
            $datas = $datas->where('_date','like',"%$request->_text_val%")
            ->orWhere('id','like',"%$request->_text_val%");
        }
        $datas = $datas->orderBy($asc_cloumn,$_asc_desc)->paginate($limit);
        return json_encode( $datas);
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
        $branchs = Branch::orderBy('_name','asc')->get();
        $permited_branch = permited_branch(explode(',',$users->branch_ids));
        $permited_costcenters = permited_costcenters(explode(',',$users->cost_center_ids));
        
        $store_houses = permited_stores(explode(',',$users->store_ids));
        $form_settings = SalesFormSetting::first();
        $inv_accounts = AccountLedger::where('_account_head_id',2)->get();
        $p_accounts = AccountLedger::where('_account_head_id',10)->get();
        $dis_accounts = AccountLedger::where('_account_head_id',11)->get();
       
        $categories = ItemCategory::orderBy('_name','asc')->get();
        $units = Units::orderBy('_name','asc')->get();

       return view('backend.sales-order.create',compact('account_types','page_name','account_groups','branchs','permited_branch','permited_costcenters','store_houses','form_settings','inv_accounts','p_accounts','dis_accounts','categories','units'));
    }


   

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
         
      // return $request->all();
         $this->validate($request, [
            '_date' => 'required',
            '_branch_id' => 'required',
            '_main_ledger_id' => 'required',
            '_form_name' => 'required'
        ]);
    //###########################
    // sales Master information Save Start
    //###########################
      DB::beginTransaction();
       try {
        
         $__sub_total = (float) $request->_sub_total;
         $__total = (float) $request->_total;
         $organization_id = $request->organization_id;

       $_print_value = $request->_print ?? 0;
         $users = Auth::user();
        $sales = new SalesOrder();
        $sales->_date = change_date_format($request->_date);
        $sales->_time = date('H:i:s');
        $sales->_referance = $request->_referance;
        $sales->_ledger_id = $request->_main_ledger_id;
        $sales->_user_id = $request->_main_ledger_id;
        $sales->_created_by = $users->id."-".$users->name;
        $sales->_user_id = $users->id;
        $sales->_user_name = $users->name;
        $sales->_note = $request->_note;
        $sales->_sub_total = $__sub_total;
        $sales->_total =  $__total;
        $sales->organization_id = $organization_id;
        $sales->_branch_id = $request->_branch_id;
        $sales->_cost_center_id = $request->_cost_center_id ?? 1;
        $sales->_address = $request->_address;
        $sales->_phone = $request->_phone;
        $sales->_status = 1;
        $sales->save();
        $sales_id = $sales->id;

        //###########################
        // sales Master information Save End
        //###########################

        //###########################
        // sales Details information Save Start
        //###########################
        $_item_ids = $request->_item_id;
        $_qtys = $request->_qty;
        $_rates = $request->_rate;
        $_values = $request->_value;
        $_main_branch_id_detail = $request->_main_branch_id_detail;
        $_main_cost_center = $request->_main_cost_center;

        $_base_unit_ids = $request->_base_unit_id ?? [];
        $conversion_qtys = $request->conversion_qty ?? [];
        $_transection_units = $request->_transection_unit ?? [];

        if(sizeof($_item_ids) > 0){
            for ($i = 0; $i <sizeof($_item_ids) ; $i++) {
                $salesDetail = new SalesOrderDetail();
                $salesDetail->_item_id = $_item_ids[$i];
                $salesDetail->_qty = $_qtys[$i];
                $salesDetail->_unit_conversion = $conversion_qtys[$i];
                $salesDetail->_transection_unit = $_transection_units[$i];
                $salesDetail->_base_unit = $_base_unit_ids[$i];

                $salesDetail->_rate = $_rates[$i];
                $salesDetail->_value = $_values[$i] ?? 0;
                $salesDetail->_cost_center_id = $_main_cost_center[$i] ?? 1;
                $salesDetail->organization_id = $organization_id;
                $salesDetail->_branch_id = $_main_branch_id_detail[$i] ?? 1;
                $salesDetail->_no = $sales_id;
                $salesDetail->_status = 1;
                $salesDetail->_created_by = $users->id."-".$users->name;
                $salesDetail->save();
                $_sales_detail_id = $salesDetail->id;

               
            }
        }

        

            DB::commit();
            return redirect()->back()->with('success','Information save successfully')->with('_master_id',$sales_id)->with('_print_value',$_print_value);
       } catch (\Exception $e) {
           DB::rollback();
           return redirect()->back()->with('danger','There is Something Wrong !');
        }

       
    }


  


    public function SalesOrderPrint($id){
        $users = Auth::user();
        $page_name = $this->page_name;
        $permited_branch = permited_branch(explode(',',$users->branch_ids));
        $permited_costcenters = permited_costcenters(explode(',',$users->cost_center_ids));
        $voucher_types = VoucherType::select('id','_name','_code')->orderBy('_code','asc')->get();
         $data =  SalesOrder::with(['_master_branch','_master_details','_ledger'])->find($id);
        $form_settings = SalesFormSetting::first();
        $permited_branch = permited_branch(explode(',',$users->branch_ids));
        $permited_costcenters = permited_costcenters(explode(',',$users->cost_center_ids));
         $store_houses = permited_stores(explode(',',$users->store_ids));
       return view('backend.sales-order.print',compact('page_name','permited_branch','permited_costcenters','data','form_settings','permited_branch','permited_costcenters','store_houses'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\sales  $sales
     * @return \Illuminate\Http\Response
     */
    public function show(sales $sales)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\sales  $sales
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
        $inv_accounts = AccountLedger::where('_account_head_id',2)->get();
        $p_accounts = AccountLedger::where('_account_head_id',10)->get();
        $dis_accounts = AccountLedger::where('_account_head_id',11)->get();
        
        $categories = ItemCategory::orderBy('_name','asc')->get();
        $units = Units::orderBy('_name','asc')->get();
        $data =  SalesOrder::with(['_master_branch','_master_details','_ledger'])->find($id);
         
       return view('backend.sales-order.edit',compact('account_types','page_name','account_groups','branchs','permited_branch','permited_costcenters','store_houses','form_settings','inv_accounts','p_accounts','dis_accounts','categories','units','data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\sales  $sales
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
         
       //return $request->all();

        $this->validate($request, [
            '_order_number' => 'required',
            '_form_name' => 'required',
            '_date' => 'required',
            '_main_ledger_id' => 'required',
        ]);

       //######################
       // Previous information need to make zero for every thing.
       //#####################
     

    //###########################
    // sales Master information Save Start
    //###########################
     // DB::beginTransaction();
     //   try {

   $sales_id = $request->_order_number;
    SalesOrderDetail::where('_no', $sales_id)
            ->update(['_status'=>0]);            

    //###########################
    // sales Master information Save Start
    //###########################
       $_print_value = $request->_print ?? 0;
       $users = Auth::user();
        $sales = SalesOrder::where('id',$sales_id)->first();
        if(empty($sales)){
            return redirect()->back()->with('danger','Something Went Wrong !');
        }
        $sales->_date = change_date_format($request->_date);
        $sales->_time = date('H:i:s');
        $sales->_referance = $request->_referance;
        $sales->_ledger_id = $request->_main_ledger_id;
        $sales->_user_id = $users->id;
        $sales->_created_by = $users->id."-".$users->name;
        $sales->_updated_by = $users->id."-".$users->name;
        $sales->_user_id = $users->id;
        $sales->_user_name = $users->name;
        $sales->_note = $request->_note;
        $sales->_sub_total = $request->_sub_total;
        $sales->_total = $request->_total;
        $sales->_branch_id = $request->_branch_id;
        $sales->_cost_center_id = $request->_cost_center_id ?? 1;
        
        $sales->_address = $request->_address;
        $sales->_phone = $request->_phone;
        $sales->_status = 1;
        $sales->save();
        $sales_id = $sales->id;

        //###########################
        // sales Master information Save End
        //###########################

        //###########################
        // sales Details information Save Start
        //###########################
        $_item_ids = $request->_item_id;
        $_qtys = $request->_qty;
        $_rates = $request->_rate;
        $_values = $request->_value;
        $_main_branch_id_detail = $request->_main_branch_id_detail;
        $_main_cost_center = $request->_main_cost_center;
        $sales_detail_ids = $request->purchase_detail_id;

         $_base_unit_ids = $request->_base_unit_id ?? [];
        $conversion_qtys = $request->conversion_qty ?? [];
        $_transection_units = $request->_transection_unit ?? [];




        if(sizeof($_item_ids) > 0){
            for ($i = 0; $i <sizeof($_item_ids) ; $i++) {
                if($sales_detail_ids[$i] ==0){
                    $SalesOrderDetail = new SalesOrderDetail();
                    $SalesOrderDetail->_created_by = $users->id."-".$users->name;
                }else{
                    $SalesOrderDetail = SalesOrderDetail::where('id',$sales_detail_ids[$i] ?? 0)->first();
                }

                $SalesOrderDetail->_item_id = $_item_ids[$i];
                $SalesOrderDetail->_qty = $_qtys[$i];
                
                $SalesOrderDetail->_unit_conversion = $conversion_qtys[$i];
                $SalesOrderDetail->_transection_unit = $_transection_units[$i];
                $SalesOrderDetail->_base_unit = $_base_unit_ids[$i];

                $SalesOrderDetail->_rate = $_rates[$i];
                $SalesOrderDetail->_value = $_values[$i] ?? 0;
                $SalesOrderDetail->_cost_center_id = $_main_cost_center[$i] ?? 1;
                $SalesOrderDetail->_branch_id = $_main_branch_id_detail[$i] ?? 1;
                $SalesOrderDetail->_no = $sales_id;
                $SalesOrderDetail->_status = 1;
                $SalesOrderDetail->_updated_by = $users->id."-".$users->name;
                $SalesOrderDetail->save();
                $_sales_detail_id = $SalesOrderDetail->id;

                
            }
        }

        

        


           //     DB::commit();
        return redirect()->back()
            ->with('success','Information save successfully')
            ->with('_master_id',$sales_id)->with('_print_value',$_print_value);
       // } catch (\Exception $e) {
        //    DB::rollback();
         //   return redirect()->back()->with('danger','There is Something Wrong !');
         //}

       
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\sales  $sales
     * @return \Illuminate\Http\Response
     */
     public function destroy($id)
    {
        DB::table('sales_order_details')->where('_no', $id)->delete();
        SalesOrder::find($id)->delete();
         return redirect()->back()->with('danger','Information Deleted successfully');

    }
}
