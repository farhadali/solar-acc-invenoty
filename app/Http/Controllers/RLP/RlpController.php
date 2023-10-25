<?php

namespace App\Http\Controllers\RLP;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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
use Session;
use DB;

class RlpController extends Controller
{

     function __construct()
    {
        
         $this->middleware('permission:rlp-create', ['only' => ['create','store']]);
         $this->middleware('permission:rlp-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:rlp-list', ['only' => ['index']]);
         $this->middleware('permission:rlp-delete', ['only' => ['destroy']]);
         $this->page_name = __('label.rlp-info');
    }





    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $page_name=$this->page_name;
        $datas =[];

        return view('rlp-module.rlp.index',compact('page_name','datas'));
    }


    public function chainWiseDetail(Request $request)
    {
        $rlp_id = $request->chain_id;
        $datas = RlpAccessChain::with(['_chain_user'])->find($rlp_id);
        $data['data']=$datas;
        return $data;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $page_name=$this->page_name;
        $users = \Auth::user();
        $permited_branch = permited_branch(explode(',',$users->branch_ids));
        $permited_costcenters = permited_costcenters(explode(',',$users->cost_center_ids));
        $permited_organizations = permited_organization(explode(',',$users->organization_ids));
        
        $emp_id = $users->user_name;

        $user_assign_rlp_chain = \DB::select("SELECT DISTINCT t1.id FROM `rlp_access_chains` as t1
INNER JOIN rlp_access_chain_users as t2 ON t1.id=t2.chain_id
WHERE t2.user_id='".$emp_id."'   AND t2.user_group=1 "); // user group 1=Rlp Creator
    $rlp_ids = array();
    foreach($user_assign_rlp_chain as $key=>$val){
        array_push($rlp_ids,$val->id);
    }

   // return $rlp_ids;

    //      $datas = RlpAccessChain::with(['_chain_user' => function ($query) use ($emp_id) {
    //     $query->where('user_id', $emp_id);
    // }])->get();

    $rlp_chains = RlpAccessChain::whereIn('id',$rlp_ids)->get();

        return view('rlp-module.rlp.create',compact('page_name','permited_branch','permited_costcenters','permited_organizations','rlp_chains'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       // return dump($request->all());
        $this->validate($request, [
            'rlp_prefix' => 'required',
            'priority' => 'required',
            'chain_id' => 'required',
            'organization_id' => 'required',
            '_branch_id' => 'required',
            '_cost_center_id' => 'required'
        ]);
        DB::beginTransaction();
       try {

        $users = Auth::user();
        $totalamount = (($request->_total_value_amount ?? 0) + ($request->_total_ledger_amount ?? 0));

        $organization_id = $request->organization_id;
        $_main_branch_id  = $request->_branch_id;

        $RlpMaster = new RlpMaster();
        $RlpMaster->organization_id = $organization_id;
        $RlpMaster->_branch_id = $_main_branch_id;
        $RlpMaster->_cost_center_id = $request->_cost_center_id;
        $RlpMaster->priority = $request->priority;
        $RlpMaster->request_date = $request->request_date;
        $RlpMaster->chain_id = $request->chain_id;

        $RlpMaster->rlp_user_id = $users->id; //Taken From Auth user
        $RlpMaster->rlp_user_office_id = $users->user_name ?? ''; //Taken From Auth user

        $RlpMaster->request_department = $request->request_department;
        $RlpMaster->request_person = $request->request_person;
        $RlpMaster->designation = $request->designation;
        $RlpMaster->email = $request->email;
        $RlpMaster->contact_number = $request->contact_number;

        $RlpMaster->user_remarks = $request->user_remarks;
        $RlpMaster->totalamount = $totalamount ?? 0;
        $RlpMaster->is_viewed = $request->is_viewed ?? 0; //
        $RlpMaster->rlp_status = $request->rlp_status ?? 0;//
        $RlpMaster->is_ns = $request->is_ns ?? 0;//
        $RlpMaster->save();

        $rlp_inserted_id = $RlpMaster->id;

        $__table="rlp_masters";
        $_pfix = $request->rlp_prefix."-".make_order_number($__table,$organization_id,$_main_branch_id);
        \DB::table('rlp_masters')
             ->where('id',$rlp_inserted_id)
             ->update(['rlp_no'=>$_pfix]);


        DB::commit();
        return redirect()->back()->with('success','Information save successfully');
       } catch (\Exception $e) {
           DB::rollback();
           return redirect()->back()->with('danger','Information not Save');
        }
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
