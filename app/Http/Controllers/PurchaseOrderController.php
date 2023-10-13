<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\Purchase;
use App\Models\VoucherMaster;
use App\Models\AccountLedger;
use App\Models\AccountGroup;
use App\Models\AccountHead;
use App\Models\Accounts;
use App\Models\Branch;
use App\Models\hrm\Company;
use App\Models\VoucherType;
use App\Models\VoucherMasterDetail;
use App\Models\StoreHouse;
use App\Models\PurchaseFormSettings;
use App\Models\PurchaseOrderDetail;
use App\Models\PurchaseAccount;
use App\Models\ProductPriceList;
use App\Models\ItemInventory;
use App\Models\Inventory;
use App\Models\ItemCategory;
use App\Models\Units;
use App\Models\SalesDetail;
use Auth;
use DB;
use Illuminate\Http\Request;
use Session;

class PurchaseOrderController extends Controller
{
   function __construct()
    {
         $this->middleware('permission:purchase-order-list|purchase-order-create|purchase-order-edit|purchase-order-delete|purchase-order-print', ['only' => ['index','store']]);
         $this->middleware('permission:purchase-order-print', ['only' => ['purchase-orderPrint']]);
         $this->middleware('permission:purchase-order-create', ['only' => ['create','store']]);
         $this->middleware('permission:purchase-order-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:purchase-order-delete', ['only' => ['destroy']]);
         $this->page_name = "Purchase Order";
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

        $datas = PurchaseOrder::with(['_organization','_master_branch','_ledger','_master_details']);
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
        
        if($request->has('_order_number') && $request->_order_number !=''){
            $datas = $datas->where('_order_number','like',"$request->_order_number%");
        }

        if($request->has('_referance') && $request->_referance !=''){
            $datas = $datas->where('_referance','like',"$request->_referance%");
        }
        if($request->has('_note') && $request->_note !=''){
            $datas = $datas->where('_note','like',"%$request->_note%");
        }
        if($request->has('_user_name') && $request->_user_name !=''){
            $datas = $datas->where('_user_name','like',"$request->_user_name%");
        }
        
        if($request->has('_sub_total') && $request->_sub_total !=''){
            $datas = $datas->where('_sub_total','=',trim($request->_sub_total));
        }
       
       
        if($request->has('_total') && $request->_total !=''){
            $datas = $datas->where('_total','=',$request->_total);
        }
        if($request->has('_ledger_id') && $request->_ledger_id !='' && $request->has('_search_main_ledger_id') && $request->_search_main_ledger_id ){
            $datas = $datas->where('_ledger_id','=',$request->_ledger_id);
        }
        
        $datas = $datas->orderBy($asc_cloumn,$_asc_desc)
                        ->paginate($limit);

         $page_name = $this->page_name;
         $account_types = AccountHead::select('id','_name')->orderBy('_name','asc')->get();
         $account_groups = AccountGroup::select('id','_name')->orderBy('_name','asc')->get();
          $current_date = date('Y-m-d');
          $current_time = date('H:i:s');
          $users = Auth::user();
           $form_settings = PurchaseFormSettings::first();
           $permited_branch = permited_branch(explode(',',$users->branch_ids));
        $permited_costcenters = permited_costcenters(explode(',',$users->cost_center_ids));
         $store_houses = permited_stores(explode(',',$users->store_ids));
        //return $datas;
         if($request->has('print')){
            if($request->print =="single"){
                return view('backend.purchase-order.master_print',compact('datas','page_name','account_types','request','account_groups','current_date','current_time','limit','form_settings','permited_branch','permited_costcenters','store_houses'));
            }

            if($request->print =="detail"){
                return view('backend.purchase-order.details_print',compact('datas','page_name','account_types','request','account_groups','current_date','current_time','limit','form_settings','permited_branch','permited_costcenters','store_houses'));
            }
         }

        return view('backend.purchase-order.index',compact('datas','page_name','account_types','request','account_groups','current_date','limit','form_settings','permited_branch','permited_costcenters','store_houses'));
    }

     public function reset(){
        Session::flash('_pur_limit');
       return  \Redirect::to('purchase-order?limit='.default_pagination());
    }


   

    public function purchaseOrderDetails(Request $request){
        $this->validate($request, [
            '_purchase_main_id' => 'required',
        ]);
        $_purchase_main_id = $request->_purchase_main_id;

       $datas = PurchaseOrderDetail::with(['_detail_branch','_detail_cost_center','_store','_items'])
                                ->where('_no',$_purchase_main_id)
                                 ->where('_status',1)
                                ->get();
      
        
        return json_encode( $datas);
    }

    public function orderSearch(Request $request){
      
        $limit = $request->limit ?? default_pagination();
        $_asc_desc = $request->_asc_desc ?? 'ASC';
        $asc_cloumn =  $request->asc_cloumn ?? '_date';
        $text_val = $request->_text_val;
        $_branch_id = $request->_branch_id;
        $datas = PurchaseOrder::with(['_ledger'])->where('_status',1)->where('_branch_id',$_branch_id);
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
        $permited_organizations = permited_organization(explode(',',$users->organization_ids));
        
        $store_houses = permited_stores(explode(',',$users->store_ids));
        $form_settings = PurchaseFormSettings::first();
        $inv_accounts = AccountLedger::where('_account_head_id',2)->get();
        $p_accounts = AccountLedger::where('_account_head_id',10)->get();
        $dis_accounts = AccountLedger::where('_account_head_id',11)->get();
       
        $categories = ItemCategory::orderBy('_name','asc')->get();
        $units = Units::orderBy('_name','asc')->get();

       return view('backend.purchase-order.create',compact('account_types','page_name','account_groups','branchs','permited_branch','permited_costcenters','store_houses','form_settings','inv_accounts','p_accounts','dis_accounts','categories','units','permited_organizations'));
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
         $this->validate($request, [
            '_date' => 'required',
            '_branch_id' => 'required',
            '_main_ledger_id' => 'required',
            '_form_name' => 'required'
        ]);
    //###########################
    // Purchase Master information Save Start
    //###########################
       DB::beginTransaction();
        try {
        
         $__sub_total = (float) $request->_sub_total;
         $__total = (float) $request->_total;

       $_print_value = $request->_print ?? 0;
         $users = Auth::user();
        $Purchase = new PurchaseOrder();
        $Purchase->_date = change_date_format($request->_date);
        $Purchase->_order_number = make_po_number($request->organization_id,$request->_branch_id);
        $Purchase->_time = date('H:i:s');
        $Purchase->_referance = $request->_referance;
        $Purchase->_ledger_id = $request->_main_ledger_id;
        $Purchase->_user_id = $request->_main_ledger_id;
        $Purchase->_created_by = $users->id."-".$users->name;
        $Purchase->_user_id = $users->id;
        $Purchase->_user_name = $users->name;
        $Purchase->_note = $request->_note;
        $Purchase->_sub_total = $__sub_total;
        $Purchase->_total =  $__total;
        $Purchase->organization_id = $request->organization_id ?? 1;
        $Purchase->_branch_id = $request->_branch_id ?? 1;
        $Purchase->_cost_center_id = $request->_cost_center_id ?? 1;
        $Purchase->_address = $request->_address;
        $Purchase->_phone = $request->_phone;
        $Purchase->_status = 1;
        $Purchase->save();
        $purchase_id = $Purchase->id;

        //###########################
        // Purchase Master information Save End
        //###########################

        //###########################
        // Purchase Details information Save Start
        //###########################
        $_item_ids = $request->_item_id;
        $_qtys = $request->_qty;
        $_rates = $request->_rate;
        $_values = $request->_value;
        $_main_branch_id_detail = $request->_main_branch_id_detail;
        $_main_cost_center = $request->_main_cost_center;
        $organization_id = $request->organization_id ?? 1;

        $_unit_conversions = $request->conversion_qty ?? [];
        $_transection_units = $request->_unit_id ?? [];
        $_base_units = $request->_base_unit_id ?? [];

        if(sizeof($_item_ids) > 0){
            for ($i = 0; $i <sizeof($_item_ids) ; $i++) {
                $PurchaseDetail = new PurchaseOrderDetail();
                $PurchaseDetail->_item_id = $_item_ids[$i];
                $PurchaseDetail->_unit_conversion = $_unit_conversions[$i];
                $PurchaseDetail->_transection_unit = $_transection_units[$i];
                $PurchaseDetail->_base_unit = $_base_units[$i];


                $PurchaseDetail->_qty = $_qtys[$i];
                $PurchaseDetail->_rate = $_rates[$i];
                $PurchaseDetail->_value = $_values[$i] ?? 0;
                $PurchaseDetail->_cost_center_id = $_main_cost_center[$i] ?? 1;
                $PurchaseDetail->_branch_id = $_main_branch_id_detail[$i] ?? 1;
                $PurchaseDetail->organization_id = $organization_id;
                $PurchaseDetail->_no = $purchase_id;
                $PurchaseDetail->_status = 1;
                $PurchaseDetail->_created_by = $users->id."-".$users->name;
                $PurchaseDetail->save();
                $_purchase_detail_id = $PurchaseDetail->id;

               
            }
        }

        

            DB::commit();
            return redirect()->back()->with('success','Information save successfully')->with('_master_id',$purchase_id)->with('_print_value',$_print_value);
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('danger','There is Something Wrong !');
         }

       
    }


  


    public function purchaseOrderPrint($id){
        $users = Auth::user();
        $page_name = $this->page_name;
        $permited_branch = permited_branch(explode(',',$users->branch_ids));
        $permited_costcenters = permited_costcenters(explode(',',$users->cost_center_ids));
        $voucher_types = VoucherType::select('id','_name','_code')->orderBy('_code','asc')->get();
        $data =  PurchaseOrder::with(['_master_branch','_master_details','_ledger'])->find($id);
        $form_settings = PurchaseFormSettings::first();
           $permited_branch = permited_branch(explode(',',$users->branch_ids));
        $permited_costcenters = permited_costcenters(explode(',',$users->cost_center_ids));
         $store_houses = permited_stores(explode(',',$users->store_ids));
       return view('backend.purchase-order.print',compact('page_name','permited_branch','permited_costcenters','data','form_settings','permited_branch','permited_costcenters','store_houses'));
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
        $permited_organizations = permited_organization(explode(',',$users->organization_ids));
        $store_houses = permited_stores(explode(',',$users->store_ids));
        $form_settings = PurchaseFormSettings::first();
        $inv_accounts = AccountLedger::where('_account_head_id',2)->get();
        $p_accounts = AccountLedger::where('_account_head_id',10)->get();
        $dis_accounts = AccountLedger::where('_account_head_id',11)->get();
        
        $categories = ItemCategory::orderBy('_name','asc')->get();
        $units = Units::orderBy('_name','asc')->get();
         $data =  PurchaseOrder::with(['_master_branch','_master_details','_ledger'])->find($id);
        // dump($data);
       return view('backend.purchase-order.edit',compact('account_types','page_name','account_groups','branchs','permited_branch','permited_costcenters','store_houses','form_settings','inv_accounts','p_accounts','dis_accounts','categories','units','data','permited_organizations'));
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
         
       //return dump($request->all());

        $this->validate($request, [
            '_purchase_id' => 'required',
            '_form_name' => 'required',
            '_date' => 'required',
            '_main_ledger_id' => 'required',
        ]);

       //######################
       // Previous information need to make zero for every thing.
       //#####################
     

    //###########################
    // Purchase Master information Save Start
    //###########################
      DB::beginTransaction();
        try {

   $purchase_id = $request->_purchase_id;
    PurchaseOrderDetail::where('_no', $purchase_id)
            ->update(['_status'=>0]); 

    $organization_id = $request->organization_id ?? 1;           

    //###########################
    // Purchase Master information Save Start
    //###########################
       $_print_value = $request->_print ?? 0;
       $users = Auth::user();
        $Purchase = PurchaseOrder::where('id',$purchase_id)->first();
        if(empty($Purchase)){
            return redirect()->back()->with('danger','Something Went Wrong !');
        }
        $Purchase->_date = change_date_format($request->_date);
        $Purchase->_time = date('H:i:s');
        $Purchase->_referance = $request->_referance;
        $Purchase->_ledger_id = $request->_main_ledger_id;
        $Purchase->_user_id = $users->id;
        $Purchase->_created_by = $users->id."-".$users->name;
        $Purchase->_updated_by = $users->id."-".$users->name;
        $Purchase->_user_id = $users->id;
        $Purchase->_user_name = $users->name;
        $Purchase->_note = $request->_note;
        $Purchase->_sub_total = $request->_sub_total;
        $Purchase->_total = $request->_total;
        $Purchase->organization_id = $organization_id;
        $Purchase->_branch_id = $request->_branch_id;
        $Purchase->_cost_center_id = $request->_cost_center_id ?? 1;
        $Purchase->_address = $request->_address;
        $Purchase->_phone = $request->_phone;
        $Purchase->_status = 1;
        $Purchase->save();
        $purchase_id = $Purchase->id;

        //###########################
        // Purchase Master information Save End
        //###########################

        //###########################
        // Purchase Details information Save Start
        //###########################
        $_item_ids = $request->_item_id;
        $_qtys = $request->_qty;
        $_rates = $request->_rate;
        $_values = $request->_value;
        $_main_branch_id_detail = $request->_main_branch_id_detail;
        $_main_cost_center = $request->_main_cost_center;
        $purchase_detail_ids = $request->purchase_detail_id;

        $_unit_conversions = $request->conversion_qty ?? [];
        $_transection_units = $request->_unit_id ?? [];
        $_base_units = $request->_base_unit_id ?? [];

 

        if(sizeof($_item_ids) > 0){
            for ($i = 0; $i <sizeof($_item_ids) ; $i++) {
                if($purchase_detail_ids[$i] ==0){
                    $PurchaseOrderDetail = new PurchaseOrderDetail();
                    $PurchaseOrderDetail->_created_by = $users->id."-".$users->name;
                }else{
                    $PurchaseOrderDetail = PurchaseOrderDetail::where('id',$purchase_detail_ids[$i] ?? 0)->first();
                }

                $PurchaseOrderDetail->_item_id = $_item_ids[$i];

                $PurchaseOrderDetail->_unit_conversion = $_unit_conversions[$i];
                $PurchaseOrderDetail->_transection_unit = $_transection_units[$i];
                $PurchaseOrderDetail->_base_unit = $_base_units[$i];

                $PurchaseOrderDetail->_qty = $_qtys[$i];
                $PurchaseOrderDetail->_rate = $_rates[$i];
                $PurchaseOrderDetail->_value = $_values[$i] ?? 0;
                $PurchaseOrderDetail->_cost_center_id = $_main_cost_center[$i] ?? 1;
                $PurchaseOrderDetail->_branch_id = $_main_branch_id_detail[$i] ?? 1;
                $PurchaseOrderDetail->organization_id = $organization_id;
                $PurchaseOrderDetail->_no = $purchase_id;
                $PurchaseOrderDetail->_status = 1;
                $PurchaseOrderDetail->_updated_by = $users->id."-".$users->name;
                $PurchaseOrderDetail->save();
                $_purchase_detail_id = $PurchaseOrderDetail->id;

                
            }
        }

        

        


                DB::commit();
        return redirect()->back()->with('success','Information save successfully')->with('_master_id',$purchase_id)->with('_print_value',$_print_value);
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
        PurchaseOrder::find($id)->delete();
        DB::table('purchase_order_details')->where('_no', $id)->delete();
         return redirect()->back()->with('danger','Information Deleted successfully');

    }
}
