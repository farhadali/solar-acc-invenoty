<?php

namespace App\Http\Controllers;

use App\Models\MaterialIssueReturn;
use App\Models\MaterialIssue;
use App\Models\MaterialIssueDetail;
use App\Models\AccountLedger;
use App\Models\AccountHead;
use App\Models\AccountGroup;
use App\Models\MaterialIssueSetting;
use App\Models\Branch;
use App\Models\VoucherType;
use App\Models\ItemCategory;
use App\Models\Units;
use App\Models\Warranty;
use App\Models\TransectionTerms;
use App\Models\Inventory;
use App\Models\ProductPriceList;
use App\Models\ItemInventory;
use App\Models\MaterialIssueBarcode;
use App\Models\Accounts;
use DB;


use Illuminate\Http\Request;
use Auth;
use Session;

class MaterialIssueReturnController extends Controller
{

          function __construct()
    {
         $this->middleware('permission:material-issue-return-list|material-issue-return-create|material-issue-return-edit|material-issue-return-delete|material-issue-return-print', ['only' => ['index','store']]);
         $this->middleware('permission:material-issue-return-print', ['only' => ['material-issue-returnPrint']]);
         $this->middleware('permission:material-issue-return-create', ['only' => ['create','store']]);
         $this->middleware('permission:material-issue-return-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:material-issue-return-delete', ['only' => ['destroy']]);
         $this->middleware('permission:material-issue-return-print', ['only' => ['Print']]);
         $this->middleware('permission:material-issue-return-settings', ['only' => ['Settings']]);
         $this->page_name = __('label.material_issue_return');
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
            session()->put('_sales_return_limit', $request->limit);
        }else{
             $limit= \Session::get('_sales_return_limit') ??  default_pagination();
            
        }
        
        $_asc_desc = $request->_asc_desc ?? 'DESC';
        $asc_cloumn =  $request->asc_cloumn ?? 'id';


        $datas = MaterialIssueReturn::with(['_organization','_master_branch','_ledger','_master_details']);
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
         if($request->has('_lock') && $request->_lock !=''){
            $datas = $datas->where('_lock','=',$request->_lock);
        }
        
        if($request->has('_order_ref_id') && $request->_order_ref_id !=''){
            $datas = $datas->where('_order_ref_id','like',"%trim($request->_order_ref_id)%");
        }
        if($request->has('organization_id') && $request->organization_id !=''){
            $datas = $datas->where('organization_id','=',$request->organization_id);
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
         $account_groups = [];
          $current_date = date('Y-m-d');
          $current_time = date('H:i:s');
          $users = Auth::user();
           $form_settings = MaterialIssueSetting::first();
           $permited_branch = permited_branch(explode(',',$users->branch_ids));
          $permited_costcenters = permited_costcenters(explode(',',$users->cost_center_ids));
          $store_houses = permited_stores(explode(',',$users->store_ids));
        $permited_organizations = permited_organization(explode(',',$users->organization_ids));
        //return $datas;
         if($request->has('print')){
            if($request->print =="single"){
                return view('backend.material-issue-return.master_print',compact('datas','page_name','account_types','request','account_groups','current_date','current_time','limit','form_settings','permited_branch','permited_costcenters','store_houses','permited_organizations'));
            }

            if($request->print =="detail"){
                return view('backend.material-issue-return.details_print',compact('datas','page_name','account_types','request','account_groups','current_date','current_time','limit','form_settings','permited_branch','permited_costcenters','store_houses','permited_organizations'));
            }
         }

        return view('backend.material-issue-return.index',compact('datas','page_name','account_types','request','account_groups','current_date','limit','form_settings','permited_branch','permited_costcenters','store_houses','permited_organizations'));
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
        $voucher_types = VoucherType::select('id','_name','_code')->orderBy('_code','asc')->get();
         $store_houses = permited_stores(explode(',',$users->store_ids));
       $permited_organizations = permited_organization(explode(',',$users->organization_ids));
        $form_settings = MaterialIssueSetting::first();
        $inv_accounts = [];
        $p_accounts = [];
        $dis_accounts = [];
        $vat_accounts =[];

        $categories = ItemCategory::orderBy('_name','asc')->get();
        $units = Units::orderBy('_name','asc')->get();
        $_warranties = Warranty::select('id','_name')->orderBy('_name','asc')->get();
       return view('backend.material-issue-return.create',compact('account_types','page_name','account_groups','branchs','permited_branch','permited_costcenters','voucher_types','store_houses','form_settings','inv_accounts','p_accounts','dis_accounts','vat_accounts','categories','units','_warranties','permited_organizations'));
    }


      public function orderSearch(Request $request){
      //  return $request->all();
        $limit = $request->limit ?? default_pagination();
        $_asc_desc = $request->_asc_desc ?? 'ASC';
        $asc_cloumn =  $request->asc_cloumn ?? '_date';
        $text_val = trim($request->_text_val);
        if($text_val =='%'){ $text_val=''; }
        $_branch_id = $request->_branch_id;

        $users = Auth::user();
        $permited_branch = explode(',',$users->branch_ids);
        $permited_costcenters = explode(',',$users->cost_center_ids);
        $permited_organizations = explode(',',$users->organization_ids);
        $store_houses = permited_stores(explode(',',$users->store_ids));


        $datas = MaterialIssue::with(['_ledger','_delivery_man','_sales_man'])
        ->where('_status',1)
        ->whereIn('organization_id',$permited_organizations)
        ->whereIn('_branch_id',$permited_branch)
        ->whereIn('_cost_center_id',$permited_costcenters)
        ->where(function ($query) use ($text_val) {
                $query->orWhere('id','like',"%$text_val%")
                      ->orWhere('_order_number','like',"%$text_val%")
                      ->orWhere('_date','like',"%$text_val%")
                      ->orWhere('id', '=', $text_val);
            });
        
        $datas = $datas->orderBy($asc_cloumn,$_asc_desc)->paginate($limit);
        return json_encode( $datas);
    }

    public function issueDetail(Request $request){
        $this->validate($request, [
            '_purchase_main_id' => 'required',
        ]);
        $_purchase_main_id = $request->_purchase_main_id;
       $datas = SalesDetail::with(['_detail_branch','_detail_cost_center','_store','_items','_units','_trans_unit','unit_conversion'])
                                ->where('_no',$_purchase_main_id)
                                ->get();
        $previous_retuns = \DB::select(" SELECT t1._sales_detail_ref_id,SUM(t1._qty*t1._unit_conversion) as _qty 
                                        FROM  sales_return_details AS t1 
                                            WHERE t1._sales_ref_id=".$_purchase_main_id."
                                        GROUP BY t1._sales_detail_ref_id ");
        if(sizeof($previous_retuns) > 0){
            foreach ($previous_retuns as $value) {
                foreach ($datas as $key=> $_data_val) {
                    if($_data_val->id ==$value->_sales_detail_ref_id){
                        $datas[$key]->_qty = ($datas[$key]->_qty -$value->_qty);
                    }
                }
            }
        }

        
        return json_encode( $datas);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MaterialIssueReturn  $materialIssueReturn
     * @return \Illuminate\Http\Response
     */
    public function show(MaterialIssueReturn $materialIssueReturn)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MaterialIssueReturn  $materialIssueReturn
     * @return \Illuminate\Http\Response
     */
    public function edit(MaterialIssueReturn $materialIssueReturn)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MaterialIssueReturn  $materialIssueReturn
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MaterialIssueReturn $materialIssueReturn)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MaterialIssueReturn  $materialIssueReturn
     * @return \Illuminate\Http\Response
     */
    public function destroy(MaterialIssueReturn $materialIssueReturn)
    {
        //
    }
}
