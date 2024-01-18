<?php

namespace App\Http\Controllers\Notesheet;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notesheet\NoteSheetAccountDetail;
use App\Models\Notesheet\NoteSheetAcknoledgement;
use App\Models\Notesheet\NoteSheetDetail;
use App\Models\Notesheet\NoteSheetMaster;
use App\Models\Notesheet\NoteSheetRemark;
use App\Models\RLP\AccessChainUser;
use App\Models\RLP\RlpAccountDetail;
use App\Models\RLP\RlpAccessChain;
use App\Models\RLP\RlpAcknowledgement;
use App\Models\RLP\RlpDeleteHistory;
use App\Models\RLP\RlpDetail;
use App\Models\RLP\RlpMaster;
use App\Models\RLP\RlpRemarks;
use App\Models\RLP\RlpUserGroup;
use Auth;
use DB;
use Session;


class NoteSheetMasterController extends Controller
{

      function __construct()
    {
        
         $this->middleware('permission:notesheet-create', ['only' => ['create','store']]);
         $this->middleware('permission:notesheet-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:rlp-to-notesheet', ['only' => ['create','rlpToNotesheetUpdate']]);
         $this->middleware('permission:notesheet-list', ['only' => ['index']]);
         $this->middleware('permission:notesheet-delete', ['only' => ['destroy']]);
         $this->page_name = __('label.notesheet-info');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
       // return $request->all();
        $auth_user = Auth::user();
       if($request->has('limit')){
            $limit = $request->limit ??  default_pagination();
            session()->put('_rlp_list_limit', $request->limit);
        }else{
             $limit= \Session::get('_rlp_list_limit') ??  default_pagination();
            
        }
        
        $_asc_desc = $request->_asc_desc ?? 'DESC';
        $asc_cloumn =  $request->asc_cloumn ?? 'id';

        $page_name=$this->page_name;
        $rlp_ids = [];

        $RlpAcknowledgements = NoteSheetAcknoledgement::select('id','ns_id')->where('user_office_id',$auth_user->user_name)
        ->where('is_visible',1)
        ->where('_is_approve',0)
        ->get();

        foreach($RlpAcknowledgements as $val){
           $rlp_ids[]=$val->rlp_info_id;
        }
         

        $datas = NoteSheetMaster::with(['_emp_department','_emp_designation','_branch','_cost_center','_organization','_entry_by','_item_detail','_account_detail'])
         ->where('is_delete',0);
        if($auth_user->user_type !='admin'){
                $datas = $datas->whereIn('id',$rlp_ids);  
        }
         if($request->has('_user_date') && $request->_user_date=="yes" && $request->_datex !="" && $request->_datex !=""){
            $_datex =  change_date_format($request->_datex);
            $_datey=  change_date_format($request->_datey);

             $datas = $datas->whereDate('request_date','>=', $_datex);
            $datas = $datas->whereDate('request_date','<=', $_datey);
        }

        if($request->has('id') && $request->id !=""){
             $ids =  array_map('intval', explode(',', $request->id ));
            $datas = $datas->where('id', $ids); 
        }
        if($request->has('_lock') && $request->_lock !=''){
            $datas = $datas->where('_lock','=',$request->_lock);
        }
        if($request->has('rlp_no') && $request->rlp_no !=''){
            $datas = $datas->where('rlp_no','like',"%$request->rlp_no%");
        }
        if($request->has('rlp_user_office_id') && $request->rlp_user_office_id !=''){
            $datas = $datas->where('rlp_user_office_id','like',"%$request->rlp_user_office_id%");
        }
        if($request->has('priority') && $request->priority !=''){
            $datas = $datas->where('priority','like',"%$request->priority%");
        }
        if($request->has('request_department') && $request->request_department !=''){
            $datas = $datas->where('request_department','=',$request->request_department);
        }
        if($request->has('organization_id') && $request->organization_id !=''){
            $datas = $datas->where('organization_id','=',$request->organization_id);
        }
        if($request->has('_branch_id') && $request->_branch_id !=''){
            $datas = $datas->where('_branch_id','=',$request->_branch_id);
        }
        if($request->has('_cost_center_id') && $request->_cost_center_id !=''){
            $datas = $datas->where('_cost_center_id','=',$request->_cost_center_id);
        }
        $datas = $datas->orderBy($asc_cloumn,$_asc_desc)
                        ->paginate($limit);

         $status_details =\DB::table('status_details')->get();

        return view('rlp-module.notesheet.index',compact('page_name','datas','limit','request','status_details'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request){
    
        $page_name=$this->page_name;
        $users = \Auth::user();
        $permited_branch = permited_branch(explode(',',$users->branch_ids));
        $permited_costcenters = permited_costcenters(explode(',',$users->cost_center_ids));
        $permited_organizations = permited_organization(explode(',',$users->organization_ids));
        
        $emp_id = $users->user_name;

        $user_assign_rlp_chain = DB::table("rlp_access_chains as t1")
                                ->select('t1.id')
                                ->join('rlp_access_chain_users as t2','t2.chain_id','t1.id')
                                ->where('t2.user_id',$emp_id)
                                ->where('t1.chain_type','NOT')
                                ->where('t2.user_group',1) // user group 1=Rlp Creator
                                ->groupBy('t1.id')
                                ->get();

 
    $rlp_ids = array();
    foreach($user_assign_rlp_chain as $key=>$val){
        array_push($rlp_ids,$val->id);
    }

   

    $rlp_chains = RlpAccessChain::whereIn('id',$rlp_ids)->get();

        return view('rlp-module.notesheet.create',compact('page_name','permited_branch','permited_costcenters','permited_organizations','rlp_chains'));
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
