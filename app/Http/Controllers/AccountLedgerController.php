<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AccountLedger;
use App\Models\AccountGroup;
use App\Models\AccountHead;
use App\Models\Accounts;
use App\Models\Branch;
use App\Models\GeneralSettings;
use App\Models\VoucherMaster;
use App\Models\VoucherMasterDetail;
use Auth;
use Session;
use DB;

class AccountLedgerController extends Controller
{


    function __construct()
    {
         $this->middleware('permission:account-ledger-list|account-ledger-create|account-ledger-edit|account-ledger-delete', ['only' => ['index','store']]);
         $this->middleware('permission:account-ledger-create', ['only' => ['create','store']]);
         $this->middleware('permission:account-ledger-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:account-ledger-delete', ['only' => ['destroy']]);
         $this->page_name = "Account Ledger";
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->has('limit')){
            $limit = $request->limit ??  default_pagination();
            session()->put('_al_limit', $request->limit);
        }else{
             $limit= \Session::get('_al_limit') ??  default_pagination();
            
        }
        
        $_asc_desc = $request->_asc_desc ?? 'DESC';
        $asc_cloumn =  $request->asc_cloumn ?? 'id';

        $datas = AccountLedger::with(['account_type','account_group']);
        if($request->has('id') && $request->id !=""){
            $ids =  array_map('intval', explode(',', $request->id ));
            $datas = $datas->whereIn('id', $ids); 
        }
        if($request->has('_name') && $request->_name !=''){
            $datas = $datas->where('_name','like',"%$request->_name%");
        }
        if($request->has('_address') && $request->_address !=''){
            $datas = $datas->where('_address','like',"%$request->_address%");
        }
        if($request->has('_code') && $request->_code !=''){
            $datas = $datas->where('_code','like',"%$request->_code%");
        }
        if($request->has('_nid') && $request->_nid !=''){
            $datas = $datas->where('_nid','like',"%$request->_nid%");
        }
        if($request->has('_note') && $request->_note !=''){
            $datas = $datas->where('_note','like',"%$request->_note%");
        }
        if($request->has('_alious') && $request->_alious !=''){
            $datas = $datas->where('_alious','like',"%$request->_alious%");
        }
        if($request->has('_email') && $request->_email !=''){
            $datas = $datas->where('_email','like',"%$request->_email%");
        }
        if($request->has('_phone') && $request->_phone !=''){
            $datas = $datas->where('_phone','like',"%$request->_phone%");
        }
        if ($request->has('_account_group_id') && $request->_account_group_id !="") {
            $datas = $datas->where('_account_group_id','=',$request->_account_group_id);
        }
        if ($request->has('_account_head_id') && $request->_account_head_id !="") {
            $datas = $datas->where('_account_head_id','=',$request->_account_head_id);
        }
        $datas = $datas->orderBy($asc_cloumn,$_asc_desc)->paginate($limit);
       

         $page_name = $this->page_name;
         if($request->has('print')){
            if($request->print =="single"){
                return view('backend.account-ledger.master_print',compact('datas','page_name','request','limit'));
            }
         }
         $account_types = AccountHead::select('id','_name')->orderBy('_name','asc')->get();
         $account_groups = [];
         //$account_groups = AccountGroup::select('id','_name')->orderBy('_name','asc')->get();

        return view('backend.account-ledger.index',compact('datas','page_name','account_types','request','account_groups','limit'));
    }


     public function reset(){
        Session::flash('_al_limit');
       return  \Redirect::to('account-ledger?limit='.default_pagination());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $page_name = $this->page_name;
        $account_types = AccountHead::select('id','_name')->orderBy('_name','asc')->get();
        $account_groups = AccountGroup::select('id','_name')->orderBy('_name','asc')->get();
        $branchs = Branch::orderBy('_name','asc')->get();
       return view('backend.account-ledger.create',compact('account_types','page_name','account_groups','branchs'));
    }

    public function ledger_search(Request $request){
        $limit = $request->limit ?? default_pagination();
        $_asc_desc = $request->_asc_desc ?? 'ASC';
        $asc_cloumn =  $request->asc_cloumn ?? '_name';
        $text_val = trim($request->_text_val);
        if($text_val =='%'){ $text_val=''; }
        $_head_no = $request->_head_no ?? 0;
        $datas = AccountLedger::select('id','_name','_code','_address','_balance','_phone')->where('_status',1);
         if($_head_no !=0){
            $datas = $datas->where('_account_head_id','=',$_head_no);
        }
         if($request->has('_text_val') && $text_val !=''){
            $datas = $datas->where('_name','like',"%$text_val%");
            $datas = $datas->orWhere('_code','like',"%$text_val%")
            ->orWhere('id','like',"%text_val%");
        }
       
        $datas = $datas->orderBy($asc_cloumn,$_asc_desc)->paginate($limit);
        return json_encode( $datas);
    }
    public function rlpLedgerSearch(Request $request){
        $limit = $request->limit ?? default_pagination();
        $_asc_desc = $request->_asc_desc ?? 'ASC';
        $asc_cloumn =  $request->asc_cloumn ?? '_name';
        $text_val = trim($request->_text_val);
        if($text_val =='%'){ $text_val=''; }
        $_head_no = $request->_head_no ?? 0;
        $datas = AccountLedger::select('id','_name','_code','_address','_balance','_phone')->where('_status',1);
         if($_head_no !=0){
            $datas = $datas->where('_account_head_id','=',$_head_no);
        }
         if($request->has('_text_val') && $text_val !=''){
            $datas = $datas->where('_name','like',"%$text_val%");
            $datas = $datas->orWhere('_code','like',"%$text_val%")
            ->orWhere('id','like',"%text_val%");
        }
       
        $datas = $datas->orderBy($asc_cloumn,$_asc_desc)->paginate($limit);
        return json_encode( $datas);
    }

    public function mainLedgerSearch(Request $request){
        $limit = $request->limit ?? default_pagination();
        $_asc_desc = $request->_asc_desc ?? 'ASC';
        $asc_cloumn =  $request->asc_cloumn ?? '_name';
        $text_val = $request->_text_val;
        $_form = $request->_form ?? 1;
        $datas = AccountLedger::select('id','_name','_code','_address','_balance','_phone');
        if($_form ==2){
            $datas = $datas->where('_is_sales_form','=',1);
        }
        if($_form ==1){
           $datas = $datas->where('_is_purchase_form','=',1);
        }
           $datas = $datas->where('_status',1);
         if($request->has('_text_val') && $request->_text_val !=''){
            $datas = $datas->where('_name','like',"%$request->_text_val%")
            ->orWhere('id','like',"%$request->_text_val%")
            ->orWhere('_phone','like',"%$request->_text_val%");
        }
        $datas = $datas->orderBy($asc_cloumn,$_asc_desc)->paginate($limit);
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
      //  dump($request->all());
      //  die();
        
        $this->validate($request, [
            '_account_head_id' => 'required',
            '_account_group_id' => 'required',
            '_branch_id' => 'required',
            '_name' => 'required|unique:account_ledgers,_name',
            '_status' => 'required'
        ]);
DB::beginTransaction();
       try {
        $data = new AccountLedger();
        $data->_account_head_id = $request->_account_head_id;
        $data->_account_group_id = $request->_account_group_id;
        $data->_branch_id = $request->_branch_id;
        $data->_name = $request->_name ?? '';
        $data->_address = $request->_address ?? '';
        $data->_code = $request->_code ?? '';
        $data->_nid = $request->_nid ?? '';
        $data->_note = $request->_note ?? '';
        $data->_alious = $request->_alious ?? '';
        $data->_email = $request->_email;
        $data->_phone = $request->_phone;
        $data->_credit_limit = $request->_credit_limit ?? 0;
        $data->_short = $request->_short ?? 5;
        $data->_is_user = $request->_is_user;
        $data->_is_sales_form = $request->_is_sales_form;
        $data->_is_purchase_form = $request->_is_purchase_form;
        $data->_is_all_branch = $request->_is_all_branch;
        $data->opening_dr_amount = $request->opening_dr_amount ?? 0;
        $data->opening_cr_amount = $request->opening_cr_amount ?? 0;
        $data->_status = $request->_status;
        $data->_show = 1;
        $data->_is_used = 1;
        $data->_created_by = Auth::user()->id."-".Auth::user()->name;
        $data->save();

        $ledger_id = $data->id;

        $settings = GeneralSettings::select('_opening_ledger')->first();
        $_opening_ledger = $settings->_opening_ledger ?? 0;
        $opening_dr_amount =$request->opening_dr_amount ?? 0;
        $opening_cr_amount =$request->opening_cr_amount ?? 0;
        $_total_dr_amount = 0;
        if($opening_dr_amount > 0){
            $_total_dr_amount = $opening_dr_amount;
        }
        if($opening_cr_amount > 0){
            $_total_dr_amount = $opening_cr_amount;
        }
        
        if($_opening_ledger !=0 && intval($_total_dr_amount) > 0  ){

            $users = Auth::user();
            // Voucher Master Data Insert
            $VoucherMaster = new VoucherMaster();
            $VoucherMaster->_date =date('Y-m-d');
            $VoucherMaster->_voucher_type = 'JV';
            $VoucherMaster->_branch_id = $request->_branch_id;
            $VoucherMaster->_transection_ref = $request->_transection_ref ?? '';
            $VoucherMaster->_amount = $_total_dr_amount;
            $VoucherMaster->_note = 'Opening Balance';
            $VoucherMaster->_form_name = 'voucher_masters';
            $VoucherMaster->_lock = $request->_lock ?? 0;
            $VoucherMaster->_cost_center_id = $request->_ledger_cost_center_id ?? 1;
            $VoucherMaster->_status =1;
            $VoucherMaster->_created_by = $users->id."-".$users->name;
            $VoucherMaster->_user_id = $users->id;
            $VoucherMaster->_user_name = $users->name;
            $VoucherMaster->_time = date('H:i:s');
            $VoucherMaster->save();
            $master_id = $VoucherMaster->id;
            VoucherMaster::where('id',$master_id )->update(['_code'=>voucher_prefix().$master_id]);

           

            if($opening_dr_amount > 0 || $opening_cr_amount > 0){
               
                    $_account_type_id =  ledger_to_group_type($ledger_id)->_account_head_id;
                    $_account_group_id =  ledger_to_group_type($ledger_id)->_account_group_id;

                    $VoucherMasterDetail = new VoucherMasterDetail();
                    $VoucherMasterDetail->_no = $master_id;
                    $VoucherMasterDetail->_account_type_id = $_account_type_id;
                    $VoucherMasterDetail->_account_group_id = $_account_group_id;
                    $VoucherMasterDetail->_cost_center = $request->_ledger_cost_center_id ?? 1;
                    $VoucherMasterDetail->_branch_id = $request->_branch_id;
                    $VoucherMasterDetail->_short_narr = 'Opening Balance';

                    $VoucherMasterDetail->_ledger_id = $ledger_id;
                     if($opening_dr_amount > 0){
                        $VoucherMasterDetail->_dr_amount = $opening_dr_amount ?? 0;
                        $VoucherMasterDetail->_cr_amount =  0;
                    }
                    if($opening_cr_amount > 0){
                        $VoucherMasterDetail->_dr_amount = 0;
                        $VoucherMasterDetail->_cr_amount =  $opening_cr_amount ?? 0;
                    }

                    $VoucherMasterDetail->_dr_amount = $opening_dr_amount ?? 0;
                    $VoucherMasterDetail->_cr_amount = $opening_cr_amount ?? 0;

                    $VoucherMasterDetail->_status = 1;
                    $VoucherMasterDetail->_created_by = $users->id."-".$users->name;
                    $VoucherMasterDetail->save();
                    $master_detail_id = $VoucherMasterDetail->id;



                    //Reporting Account Table Data Insert

                    $Accounts = new Accounts();
                    $Accounts->_ref_master_id = $master_id;
                    $Accounts->_ref_detail_id = $master_detail_id;
                    $Accounts->_short_narration = 'Opening Balance';
                    $Accounts->_narration = 'Opening Balance';
                    $Accounts->_reference = $request->_transection_ref;
                    $Accounts->_voucher_type = $request->_voucher_type ?? 'JV';
                    $Accounts->_transaction = 'Account';
                    $Accounts->_date = date('Y-m-d');
                    $Accounts->_table_name = 'voucher_masters';
                    $Accounts->_account_head = $_account_type_id;
                    $Accounts->_account_group = $_account_group_id;

                    $Accounts->_account_ledger = $ledger_id;
                    if($opening_dr_amount > 0){
                        $Accounts->_dr_amount = $opening_dr_amount ?? 0;
                        $Accounts->_cr_amount = 0;
                    }
                    if($opening_cr_amount > 0){
                        $Accounts->_dr_amount = 0;
                        $Accounts->_cr_amount = $opening_cr_amount ?? 0;
                    }

                    $Accounts->_branch_id = $request->_branch_id;
                    $Accounts->_cost_center = $request->_ledger_cost_center_id ?? 1;
                    $Accounts->_name =$users->name;
                    $Accounts->save();
               
                    $_account_type_id =  ledger_to_group_type($_opening_ledger)->_account_head_id;
                    $_account_group_id =  ledger_to_group_type($_opening_ledger)->_account_group_id;

                    $VoucherMasterDetail = new VoucherMasterDetail();
                    $VoucherMasterDetail->_no = $master_id;
                    $VoucherMasterDetail->_account_type_id = $_account_type_id;
                    $VoucherMasterDetail->_account_group_id = $_account_group_id;
                    $VoucherMasterDetail->_cost_center = $request->_ledger_cost_center_id ?? 1;
                    $VoucherMasterDetail->_branch_id = $request->_branch_id;
                    $VoucherMasterDetail->_short_narr = 'Opening Balance';

                    $VoucherMasterDetail->_ledger_id = $_opening_ledger;
                    if($opening_dr_amount > 0){
                        $VoucherMasterDetail->_dr_amount = 0;
                        $VoucherMasterDetail->_cr_amount =  $opening_dr_amount ?? 0;
                    }
                    if($opening_cr_amount > 0){
                        $VoucherMasterDetail->_dr_amount = $opening_cr_amount ?? 0;
                        $VoucherMasterDetail->_cr_amount =  0;
                    }
                    

                    $VoucherMasterDetail->_status = 1;
                    $VoucherMasterDetail->_created_by = $users->id."-".$users->name;
                    $VoucherMasterDetail->save();
                    $master_detail_id = $VoucherMasterDetail->id;
                    //Reporting Account Table Data Insert

                    $Accounts = new Accounts();
                    $Accounts->_ref_master_id = $master_id;
                    $Accounts->_ref_detail_id = $master_detail_id;
                    $Accounts->_short_narration = 'Opening Balance';
                    $Accounts->_narration = 'Opening Balance';
                    $Accounts->_reference = $request->_transection_ref;
                    $Accounts->_voucher_type = $request->_voucher_type ?? 'JV';
                    $Accounts->_transaction = 'Account';
                    $Accounts->_date = date('Y-m-d');
                    $Accounts->_table_name = 'voucher_masters';
                    $Accounts->_account_head = $_account_type_id;
                    $Accounts->_account_group = $_account_group_id;

                    $Accounts->_account_ledger = $_opening_ledger;
                    

                    if($opening_dr_amount > 0){
                        $Accounts->_dr_amount = 0;
                        $Accounts->_cr_amount = $opening_dr_amount ?? 0;
                    }
                    if($opening_cr_amount > 0){
                        $Accounts->_dr_amount = $opening_cr_amount ?? 0;
                        $Accounts->_cr_amount = 0;
                    }

                    $Accounts->_branch_id = $request->_branch_id;
                    $Accounts->_cost_center = $request->_ledger_cost_center_id ?? 1;
                    $Accounts->_name =$users->name;
                    $Accounts->save();
            }

        }


           DB::commit();
        return redirect()->back()->with('success','Information save successfully');
       } catch (\Exception $e) {
           DB::rollback();
           return redirect()->back()->with('danger','Information not Save');
        }





        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function ajaxLedgerSave(Request $request)
    {
        
        $ledger_name = trim($request->_ledger_name ?? '');
        $old_ledger_check = AccountLedger::where('_name',$ledger_name)->first();
        if($old_ledger_check){
            $id = 0;
            return $id;
        }



        
        $data = new AccountLedger();
        $data->_account_head_id = $request->_account_head_id;
        $data->_account_group_id = $request->_account_groups;
        $data->_branch_id = $request->_ledger_branch_id;
        $data->_name = $request->_ledger_name;
        $data->_address = $request->_ledger_address;
        $data->_code = $request->_ledger_code;
        $data->_nid = $request->_ledger_nid;
        
        $data->_email = $request->_ledger_email;
        $data->_phone = $request->_ledger_phone;
        $data->_credit_limit = $request->_ledger_credit_limit ?? 0;
        $data->_short = $request->_ledger_short ?? 5;
        $data->_is_user = $request->_ledger_is_user;
        $data->_is_sales_form = $request->_ledger_is_sales_form;
        $data->_is_purchase_form = $request->_ledger_is_purchase_form;
        $data->_is_all_branch = $request->_ledger_is_all_branch;
        $data->_status = $request->_ledger_status;
        $data->_show =1;
        $data->_created_by = Auth::user()->id."-".Auth::user()->name;
        $data->save();
        $id = $data->id;


        $ledger_id = $data->id;

        $settings = GeneralSettings::select('_opening_ledger')->first();
        $_opening_ledger = $settings->_opening_ledger ?? 0;
        $opening_dr_amount =$request->opening_dr_amount ?? 0;
        $opening_cr_amount =$request->opening_cr_amount ?? 0;
        $_total_dr_amount =0;
        if($opening_dr_amount > 0){
            $_total_dr_amount = $opening_dr_amount;
        }
        if($opening_cr_amount > 0){
            $_total_dr_amount = $opening_cr_amount;
        }
        
        if($_opening_ledger !=0  && $_total_dr_amount > 0){

            $users = Auth::user();
            // Voucher Master Data Insert
            $VoucherMaster = new VoucherMaster();
            $VoucherMaster->_date =date('Y-m-d');
            $VoucherMaster->_voucher_type = 'JV';
            $VoucherMaster->_branch_id = $request->_ledger_branch_id;
            $VoucherMaster->_transection_ref = $request->_transection_ref ?? '';
            $VoucherMaster->_amount = $_total_dr_amount;
            $VoucherMaster->_note = 'Opening Balance';
            $VoucherMaster->_form_name = 'voucher_masters';
            $VoucherMaster->_lock = $request->_lock ?? 0;
            $VoucherMaster->organization_id = $request->_ledger_organization_id ?? 1;
            $VoucherMaster->_cost_center_id = $request->_ledger_cost_center_id ?? 1;
            $VoucherMaster->_status =1;
            $VoucherMaster->_created_by = $users->id."-".$users->name;
            $VoucherMaster->_user_id = $users->id;
            $VoucherMaster->_user_name = $users->name;
            $VoucherMaster->_time = date('H:i:s');
            $VoucherMaster->save();
            $master_id = $VoucherMaster->id;
            VoucherMaster::where('id',$master_id )->update(['_code'=>voucher_prefix().$master_id]);

           

            if($opening_dr_amount > 0 || $opening_cr_amount > 0){
               

                   $_account_type_id =  ledger_to_group_type($ledger_id)->_account_head_id;
                    $_account_group_id =  ledger_to_group_type($ledger_id)->_account_group_id;

                    $VoucherMasterDetail = new VoucherMasterDetail();
                    $VoucherMasterDetail->_no = $master_id;
                    $VoucherMasterDetail->_account_type_id = $_account_type_id;
                    $VoucherMasterDetail->_account_group_id = $_account_group_id;
                    $VoucherMasterDetail->_cost_center = $request->_ledger_cost_center_id ?? 1;
                    $VoucherMasterDetail->organization_id = $request->_ledger_organization_id ?? 1;
                    $VoucherMasterDetail->_branch_id = $request->_ledger_branch_id ?? 1;
                    $VoucherMasterDetail->_short_narr = 'Opening Balance';

                    $VoucherMasterDetail->_ledger_id = $ledger_id;
                     if($opening_dr_amount > 0){
                        $VoucherMasterDetail->_dr_amount = $opening_dr_amount ?? 0;
                        $VoucherMasterDetail->_cr_amount =  0;
                    }
                    if($opening_cr_amount > 0){
                        $VoucherMasterDetail->_dr_amount = 0;
                        $VoucherMasterDetail->_cr_amount =  $opening_cr_amount ?? 0;
                    }

                    $VoucherMasterDetail->_dr_amount = $opening_dr_amount ?? 0;
                    $VoucherMasterDetail->_cr_amount = $opening_cr_amount ?? 0;

                    $VoucherMasterDetail->_status = 1;
                    $VoucherMasterDetail->_created_by = $users->id."-".$users->name;
                    $VoucherMasterDetail->save();
                    $master_detail_id = $VoucherMasterDetail->id;



                    //Reporting Account Table Data Insert

                    $Accounts = new Accounts();
                    $Accounts->_ref_master_id = $master_id;
                    $Accounts->_ref_detail_id = $master_detail_id;
                    $Accounts->_short_narration = 'Opening Balance';
                    $Accounts->_narration = 'Opening Balance';
                    $Accounts->_reference = $request->_transection_ref;
                    $Accounts->_voucher_type = $request->_voucher_type ?? 'JV';
                    $Accounts->_transaction = 'Account';
                    $Accounts->_date = date('Y-m-d');
                    $Accounts->_table_name = 'voucher_masters';
                    $Accounts->_account_head = $_account_type_id;
                    $Accounts->_account_group = $_account_group_id;

                    $Accounts->_account_ledger = $ledger_id;
                    if($opening_dr_amount > 0){
                        $Accounts->_dr_amount = $opening_dr_amount ?? 0;
                        $Accounts->_cr_amount = 0;
                    }
                    if($opening_cr_amount > 0){
                        $Accounts->_dr_amount = 0;
                        $Accounts->_cr_amount = $opening_cr_amount ?? 0;
                    }

                    $Accounts->organization_id = $request->_ledger_organization_id ?? 1;
                    $Accounts->_branch_id = $request->_ledger_branch_id ?? 1;
                    $Accounts->_cost_center = $request->_ledger_cost_center_id ?? 1;
                    $Accounts->_name =$users->name;
                    $Accounts->save();
               
                    $_account_type_id =  ledger_to_group_type($_opening_ledger)->_account_head_id;
                    $_account_group_id =  ledger_to_group_type($_opening_ledger)->_account_group_id;

                    $VoucherMasterDetail = new VoucherMasterDetail();
                    $VoucherMasterDetail->_no = $master_id;
                    $VoucherMasterDetail->_account_type_id = $_account_type_id;
                    $VoucherMasterDetail->_account_group_id = $_account_group_id;
                    $VoucherMasterDetail->_cost_center = $request->_ledger_cost_center_id ?? 1;
                    $VoucherMasterDetail->organization_id = $request->_ledger_organization_id ?? 1;
                    $VoucherMasterDetail->_branch_id = $request->_ledger_branch_id ?? 1;
                    $VoucherMasterDetail->_short_narr = 'Opening Balance';

                    $VoucherMasterDetail->_ledger_id = $_opening_ledger;
                    if($opening_dr_amount > 0){
                        $VoucherMasterDetail->_dr_amount = 0;
                        $VoucherMasterDetail->_cr_amount =  $opening_dr_amount ?? 0;
                    }
                    if($opening_cr_amount > 0){
                        $VoucherMasterDetail->_dr_amount = $opening_cr_amount ?? 0;
                        $VoucherMasterDetail->_cr_amount =  0;
                    }
                    

                    $VoucherMasterDetail->_status = 1;
                    $VoucherMasterDetail->_created_by = $users->id."-".$users->name;
                    $VoucherMasterDetail->save();
                    $master_detail_id = $VoucherMasterDetail->id;
                    //Reporting Account Table Data Insert

                    $Accounts = new Accounts();
                    $Accounts->_ref_master_id = $master_id;
                    $Accounts->_ref_detail_id = $master_detail_id;
                    $Accounts->_short_narration = 'Opening Balance';
                    $Accounts->_narration = 'Opening Balance';
                    $Accounts->_reference = $request->_transection_ref;
                    $Accounts->_voucher_type = $request->_voucher_type ?? 'JV';
                    $Accounts->_transaction = 'Account';
                    $Accounts->_date = date('Y-m-d');
                    $Accounts->_table_name = 'voucher_masters';
                    $Accounts->_account_head = $_account_type_id;
                    $Accounts->_account_group = $_account_group_id;

                    $Accounts->_account_ledger = $_opening_ledger;
                    

                    if($opening_dr_amount > 0){
                        $Accounts->_dr_amount = 0;
                        $Accounts->_cr_amount = $opening_dr_amount ?? 0;
                    }
                    if($opening_cr_amount > 0){
                        $Accounts->_dr_amount = $opening_cr_amount ?? 0;
                        $Accounts->_cr_amount = 0;
                    }

                    $Accounts->organization_id = $request->_ledger_organization_id ?? 1;
                    $Accounts->_branch_id = $request->_ledger_branch_id ?? 1;
                    $Accounts->_cost_center = $request->_ledger_cost_center_id ?? 1;
                    $Accounts->_name =$users->name;
                    $Accounts->save();
            }
        }
        return $id;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AccountLedger  $accountLedger
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = AccountLedger::with(['account_type','account_group','last_balance'])->find($id);
        $page_name = $this->page_name;
        return view('backend.account-ledger.show',compact('data','page_name'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\AccountLedger  $accountLedger
     * @return \Illuminate\Http\Response
     */
    public function edit(AccountLedger $accountLedger)
    {
        //return $accountLedger;
        $data = $accountLedger;
        $page_name = $this->page_name;
        $account_types = AccountHead::select('id','_name')
                            ->orderBy('_name','asc')
                            ->get();
        $account_groups = AccountGroup::select('id','_name')
                        ->where('_account_head_id',$data->_account_head_id)
                        ->orderBy('_name','asc')
                        ->get();
        $branchs = Branch::orderBy('_name','asc')->get();
        
       return view('backend.account-ledger.edit',compact('account_types','page_name','account_groups','branchs','data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AccountLedger  $accountLedger
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {

        $this->validate($request, [
            'id' => 'required',
            '_account_head_id' => 'required',
            '_account_group_id' => 'required',
            '_branch_id' => 'required',
            '_name' => 'required|unique:account_ledgers,_name,'.$request->id,
            '_status' => 'required'
        ]);
        $data = AccountLedger::find($request->id);
        $data->_account_head_id = $request->_account_head_id;
        $data->_account_group_id = $request->_account_group_id;
        $data->_branch_id = $request->_branch_id;
        $data->_name = $request->_name;
        $data->_address = $request->_address;
         $data->_alious = $request->_alious ?? '';
        $data->_code = $request->_code;
        $data->_nid = $request->_nid;
        $data->_note = $request->_note;
        $data->_email = $request->_email;
        $data->_phone = $request->_phone;
        $data->_credit_limit = $request->_credit_limit ?? 0;
        $data->_short = $request->_short ?? 5;
        $data->_is_user = $request->_is_user;
        $data->_is_sales_form = $request->_is_sales_form;
        $data->_is_purchase_form = $request->_is_purchase_form;
        $data->_is_all_branch = $request->_is_all_branch;
        $data->_status = $request->_status;
        $data->_updated_by = Auth::user()->id."-".Auth::user()->name;
        $data->save();
    //return $data->id;
        //if Update Account Ledger Then Update All Transection Table

        \DB::table("accounts")->where('_account_ledger',$data->id)->update(['_account_head'=>$request->_account_head_id,'_account_group'=>$request->_account_group_id]);

        \DB::table("purchase_accounts")->where('_ledger_id',$data->id)->update(['_account_type_id'=>$request->_account_head_id,'_account_group_id'=>$request->_account_group_id]);

        \DB::table("purchase_return_accounts")->where('_ledger_id',$data->id)->update(['_account_type_id'=>$request->_account_head_id,'_account_group_id'=>$request->_account_group_id]);

        \DB::table("replacement_item_accounts")->where('_ledger_id',$data->id)->update(['_account_type_id'=>$request->_account_head_id,'_account_group_id'=>$request->_account_group_id]);
        
        \DB::table("resturant_sales_accounts")->where('_ledger_id',$data->id)->update(['_account_type_id'=>$request->_account_head_id,'_account_group_id'=>$request->_account_group_id]);

        \DB::table("sales_accounts")->where('_ledger_id',$data->id)->update(['_account_type_id'=>$request->_account_head_id,'_account_group_id'=>$request->_account_group_id]);
        
        \DB::table("sales_return_accounts")->where('_ledger_id',$data->id)->update(['_account_type_id'=>$request->_account_head_id,'_account_group_id'=>$request->_account_group_id]);
        
        \DB::table("warranty_accounts")->where('_ledger_id',$data->id)->update(['_account_type_id'=>$request->_account_head_id,'_account_group_id'=>$request->_account_group_id]);
        
        \DB::table("import_puchase_accounts")->where('_ledger_id',$data->id)->update(['_account_type_id'=>$request->_account_head_id,'_account_group_id'=>$request->_account_group_id]);

        return redirect()->back()->with('success','Information save successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AccountLedger  $accountLedger
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
      
        $numOfAccount = Accounts::where('_account_ledger',$id)->count();
        if($numOfAccount ==0){
            AccountLedger::find($id)->delete();
            return redirect('account-ledger')->with('success','Information deleted successfully');
        }else{
            $__message ="You Can not delete this Information";
            $page_name ="Permission Denied";
            return view('backend.message.permission_message',compact('__message','page_name'));
        }
        
    }



public function type_base_group(Request $request){
        $account_groups = AccountGroup::where('_account_head_id',$request->id)
                                        ->orderBy('_name','asc')
                                        ->get();
        return view('backend.account-ledger.type_base_group',compact('account_groups'));
}

public function groupBaseLedger(Request $request){
        
         $data = AccountLedger::whereIn('_account_group_id',$request->_account_group_id)
                                 ->where('_is_used',1)
                                ->select('id','_name')
                                ->get();
        return view('backend.account-ledger.group_base_ledger',compact('data'));
}
public function groupBaseSmsLedger(Request $request){
        
         $data = AccountLedger::whereIn('_account_group_id',$request->_account_group_id)
                                 
                                ->select('id','_name','_phone')
                                ->get();
        return view('backend.account-ledger.group_sms_base_ledger',compact('data'));
}

public function groupBaseLedgerPurchaseStatement(Request $request){
        
         $data = AccountLedger::whereIn('_account_group_id',$request->_account_group_id)
                                ->where('_is_used',1)
                                ->select('id','_name')
                                ->get();
        return view('backend.account-ledger.group_base_ledger_pur_statement',compact('data'));
}

public function groupBaseBillParty(Request $request){
        
         $data = AccountLedger::whereIn('_account_group_id',$request->_account_group_id)
                                ->where('_is_used',1)
                                ->select('id','_name')
                                ->get();
        return view('backend.account-ledger.group-base-bill-party-ledger',compact('data'));
}


public function groupBaseLedgerPurchaseReturnStatement(Request $request){
        
         $data = AccountLedger::whereIn('_account_group_id',$request->_account_group_id)
                                ->where('_is_used',1)
                                ->select('id','_name')
                                ->get();
        return view('backend.account-ledger.group_base_ledger_pur_return',compact('data'));
}



public function groupBaseLedgerSalesStatement(Request $request){
        
         $data = AccountLedger::whereIn('_account_group_id',$request->_account_group_id)
                                ->where('_is_used',1)
                                ->select('id','_name')
                                ->get();
        return view('backend.account-ledger.group_base_ledger_sales',compact('data'));
}


public function groupBaseLedgerSalesReturnStatement(Request $request){
        
         $data = AccountLedger::whereIn('_account_group_id',$request->_account_group_id)
                                        ->where('_is_used',1)
                                        ->select('id','_name')
                                        ->get();
        return view('backend.account-ledger.group_base_ledger_sales_return',compact('data'));
}



}
