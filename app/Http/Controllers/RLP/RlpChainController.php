<?php

namespace App\Http\Controllers\RLP;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RLP\RlpAccessChain;
use App\Models\RLP\AccessChainUser;
use Auth;
use DB;

class RlpChainController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       $page_name="RLP Information";
        $datas =RlpAccessChain::with(['_branch','_cost_center','_organization','_chain_user'])->get() ;


        return view('rlp-module.rlp-chain.index',compact('page_name','datas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       

       $page_name="RLP Information";
        $users = \Auth::user();
        $permited_branch = permited_branch(explode(',',$users->branch_ids));
        $permited_costcenters = permited_costcenters(explode(',',$users->cost_center_ids));
        $permited_organizations = permited_organization(explode(',',$users->organization_ids));
        $_list_data =\DB::table("hrm_employees")->get();

        return view('rlp-module.rlp-chain.create',compact('page_name','permited_branch','permited_costcenters','permited_organizations','_list_data'));
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
        $request->validate([
                'chain_type' => 'required',
                'chain_name' => 'required',
                'organization_id' => 'required',
                '_branch_id' => 'required',
                '_cost_center_id' => 'required',
                'user_id' => 'required',
            ]);

      DB::beginTransaction();
        try {

        $RlpAccessChain = new RlpAccessChain();
        $RlpAccessChain->chain_type = $request->chain_type;
        $RlpAccessChain->chain_name = $request->chain_name;
        $RlpAccessChain->organization_id = $request->organization_id;
        $RlpAccessChain->_branch_id = $request->_branch_id;
        $RlpAccessChain->_cost_center_id = $request->_cost_center_id;
        $RlpAccessChain->department_id = $request->department_id ?? 1;
        $RlpAccessChain->_status = 1;
        $RlpAccessChain->save();
       $rlp_access_id = $RlpAccessChain->id;

       $user_ids = $request->user_id ?? [];
        $user_row_ids = $request->user_row_id ?? [];
       $ack_orders = $request->ack_order ?? [];

       if(sizeof($user_row_ids) > 0){
        for ($i=0; $i < sizeof($user_ids) ; $i++) { 
            $AccessChainUser = new AccessChainUser();
            $AccessChainUser->chain_id = $rlp_access_id;
            $AccessChainUser->user_row_id = $user_row_ids[$i];
            $AccessChainUser->user_id = $user_ids[$i];
            $AccessChainUser->_order = $ack_orders[$i];
            $AccessChainUser->_status = 1;
            $AccessChainUser->save();
        }
       }
            DB::commit();
           return redirect()->back()->with('success','Information Save successfully');
       } catch (\Exception $e) {
           DB::rollback();
         return redirect()->back()->with('error','Something Went Wrong');
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