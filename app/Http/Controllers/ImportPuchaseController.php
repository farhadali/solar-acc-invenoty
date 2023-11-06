<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\VoucherMaster;
use App\Models\AccountLedger;
use App\Models\AccountGroup;
use App\Models\AccountHead;
use App\Models\Accounts;
use App\Models\Branch;
use App\Models\VoucherType;
use App\Models\VoucherMasterDetail;
use App\Models\StoreHouse;
use App\Models\PurchaseFormSettings;
use App\Models\PurchaseDetail;
use App\Models\PurchaseAccount;
use App\Models\ProductPriceList;
use App\Models\ItemInventory;
use App\Models\Inventory;
use App\Models\ItemCategory;
use App\Models\Units;
use App\Models\SalesDetail;
use App\Models\BarcodeDetail;
use App\Models\PurchaseBarcode;
use App\Models\GeneralSettings;
use App\Models\ImportPuchaseFormSetting;
use Auth;
use DB;
use Illuminate\Http\Request;
use Session;

class ImportPuchaseController extends Controller
{

     function __construct()
    {
         // $this->middleware('permission:import-purchase-list|import-purchase-create|import-purchase-edit|import-purchase-delete|import-purchase-print', ['only' => ['index','store']]);
         // $this->middleware('permission:import-purchase-print', ['only' => ['importPurchasePrint']]);
         // $this->middleware('permission:import-purchase-create', ['only' => ['create','store']]);
         // $this->middleware('permission:import-purchase-edit', ['only' => ['edit','update']]);
         // $this->middleware('permission:import-purchase-delete', ['only' => ['destroy']]);
         $this->page_name = __('label.import-purchase');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        
        $auth_user = Auth::user();
       if($request->has('limit')){
            $limit = $request->limit ??  default_pagination();
            session()->put('_pur_limit', $request->limit);
        }else{
             $limit= \Session::get('_pur_limit') ??  default_pagination();
            
        }
        
        $_asc_desc = $request->_asc_desc ?? 'DESC';
        $asc_cloumn =  $request->asc_cloumn ?? 'id';

        $datas = Purchase::with(['_organization','_master_branch','_ledger'])->where('_purchase_type',2);
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
         if($request->has('_lock') && $request->_lock !=''){
            $datas = $datas->where('_lock','=',$request->_lock);
        }
        if($request->has('_sub_total') && $request->_sub_total !=''){
            $datas = $datas->where('_sub_total','=',trim($request->_sub_total));
        }
        if($request->has('_order_number') && $request->_order_number !=''){
            $datas = $datas->where('_order_number','=',trim($request->_order_number));
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
        $form_settings = PurchaseFormSettings::first();
        $permited_branch = permited_branch(explode(',',$users->branch_ids));
        $permited_costcenters = permited_costcenters(explode(',',$users->cost_center_ids));
        $store_houses = permited_stores(explode(',',$users->store_ids));
        // $store_houses = StoreHouse::whereIn('_branch_id',explode(',',$users->cost_center_ids))->get();
        //return $datas;
         if($request->has('print')){
            if($request->print =="single"){
                return view('backend.import-purchase.master_print',compact('datas','page_name','account_types','request','account_groups','current_date','current_time','limit','form_settings','permited_branch','permited_costcenters','store_houses'));
            }

            if($request->print =="detail"){
                return view('backend.import-purchase.details_print',compact('datas','page_name','account_types','request','account_groups','current_date','current_time','limit','form_settings','permited_branch','permited_costcenters','store_houses'));
            }
         }

        return view('backend.import-purchase.index',compact('datas','page_name','account_types','request','account_groups','current_date','limit','form_settings','permited_branch','permited_costcenters','store_houses'));
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
        //$store_houses = StoreHouse::whereIn('_branch_id',explode(',',$users->cost_center_ids))->get();
        $form_settings = ImportPuchaseFormSetting::first();
        $inv_accounts = [];
        $p_accounts = $inv_accounts;
        $dis_accounts =$p_accounts;
        $capital_accounts = $inv_accounts;

      
       

       
        $categories = ItemCategory::orderBy('_name','asc')->get();
        $units = Units::orderBy('_name','asc')->get();

       return view('backend.import-purchase.create',compact('account_types','page_name','account_groups','branchs','permited_branch','permited_costcenters','store_houses','form_settings','inv_accounts','p_accounts','dis_accounts','categories','units','capital_accounts'));
    }


   public function formSettingAjax(){
        $form_settings = ImportPuchaseFormSetting::first();
        $inv_accounts = AccountLedger::orderBy('_name','asc')->get();
        $p_accounts = $inv_accounts;
        $dis_accounts = $inv_accounts;
        $cost_of_solds = $inv_accounts;
        $_cash_customers = $inv_accounts;
        $capital_accounts = $inv_accounts;
        return view('backend.import-purchase.form_setting_modal',compact('form_settings','inv_accounts','p_accounts','dis_accounts','cost_of_solds','_cash_customers','capital_accounts'));
    }

    public function Settings(Request $request){
        $data = ImportPuchaseFormSetting::first();
        if(empty($data)){
            $data = new ImportPuchaseFormSetting();
        }

        $data->organization_id = $request->organization_id ?? 0;
        $data->_default_inventory = $request->_default_inventory ?? 0;
        $data->_default_purchase = $request->_default_purchase ?? 0;
        $data->_default_discount = $request->_default_discount ?? 0;
        $data->_default_vat_account = $request->_default_vat_account ?? 0;
        $data->_default_sd_account = $request->_default_sd_account ?? 0;
        $data->_default_cd_account = $request->_default_cd_account ?? 0;
        $data->_default_ait_account = $request->_default_ait_account ?? 0;
        $data->_default_rd_account = $request->_default_rd_account ?? 0;
        $data->_default_tti_account = $request->_default_tti_account ?? 0;
        $data->_opening_inventory = $request->_opening_inventory ?? 0;
        $data->_default_capital = $request->_default_capital ?? 0;
        $data->_show_barcode = $request->_show_barcode ?? 0;
        $data->_inline_discount = $request->_inline_discount ?? 0;
        $data->_show_vat = $request->_show_vat ?? 0;
        $data->_show_store = $request->_show_store ?? 0;
        $data->_show_self = $request->_show_self ?? 0;
        $data->_show_sd = $request->_show_sd ?? 0;
        $data->_show_cd = $request->_show_cd ?? 0;
        $data->_show_ait = $request->_show_ait ?? 0;
        $data->_show_expected_qty = $request->_show_expected_qty ?? 0;
        $data->_show_rd = $request->_show_rd ?? 0;
        $data->_show_at = $request->_show_at ?? 0;
        $data->_show_tti = $request->_show_tti ?? 0;
        $data->_show_manufacture_date = $request->_show_manufacture_date ?? 0;
        $data->_show_expire_date = $request->_show_expire_date ?? 0;
        $data->_show_sales_rate = $request->_show_sales_rate ?? 0;
        $data->_show_unit = $request->_show_unit ?? 0;
        $data->_show_p_balance = $request->_show_p_balance ?? 0;
        $data->_invoice_template = $request->_invoice_template ?? 0;
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ImportPuchase  $importPuchase
     * @return \Illuminate\Http\Response
     */
    public function show(ImportPuchase $importPuchase)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ImportPuchase  $importPuchase
     * @return \Illuminate\Http\Response
     */
    public function edit(ImportPuchase $importPuchase)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ImportPuchase  $importPuchase
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ImportPuchase $importPuchase)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ImportPuchase  $importPuchase
     * @return \Illuminate\Http\Response
     */
    public function destroy(ImportPuchase $importPuchase)
    {
        //
    }
}
