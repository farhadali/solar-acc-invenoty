<?php

namespace App\Http\Controllers;

use App\Models\AccountHead;
use Illuminate\Http\Request;
use Session;

class AccountHeadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     function __construct()
    {
         $this->middleware('permission:account-type-list|account-type-create|account-type-edit|account-type-delete', ['only' => ['index','store']]);
         $this->middleware('permission:account-type-create', ['only' => ['create','store']]);
         $this->middleware('permission:account-type-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:account-type-delete', ['only' => ['destroy']]);
         $this->page_name = "Account Type";
    }
    

    public function index(Request $request)
    {
       if($request->has('limit')){
            $limit = $request->limit ??  default_pagination();
            session()->put('_at_limit', $request->limit);
        }else{
             $limit= Session::get('_at_limit') ??  default_pagination();
            
        }
       
        $_asc_desc = $request->_asc_desc ?? 'DESC';
        $asc_cloumn =  $request->asc_cloumn ?? 'id';

        $datas = AccountHead::with(['_main_account_head']);
        if($request->has('_code') && $request->_code !=''){
            $datas = $datas->where('_code','like',"%$request->_code%");
        }
        if($request->has('id') && $request->id !=""){
            $ids =  array_map('intval', explode(',', $request->id ));
            $datas = $datas->whereIn('id', $ids); 
        }
        if($request->has('_name') && $request->_name !=''){
            $datas = $datas->where('_name','like',"%$request->_name%");
        }
        if($request->has('_account_id') && $request->_account_id !=''){
            $datas = $datas->where('_account_id',$request->_account_id);
        }

         $datas = $datas->orderBy($asc_cloumn,$_asc_desc)->paginate($limit);
         $page_name = $this->page_name;
        if($request->has('print')){
            if($request->print =="single"){
                return view('backend.account-type.master_print',compact('datas','page_name','request','limit'));
            }
         }

         $base_accounts =\DB::table('main_account_head')->select('id','_name')->get();
         

        return view('backend.account-type.index',compact('datas','request','page_name','limit','base_accounts'));

    }



    public function reset(){
        Session::flash('_at_limit');
       return  \Redirect::to('account-type?limit='.default_pagination());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $page_name = $this->page_name;
        return view('backend.account-type.create',compact('page_name'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $request->validate([
                '_name' => 'required|max:255|unique:account_heads,_name',
            ]);
        $data = new AccountHead();
        $data->_name       = $request->_name ?? '';
        $data->_account_id = $request->_account_id ?? '';
        $data->_code       = $request->_code ?? '';
        $data->_status     = $request->_status ?? '';
        $data->save();

        return redirect()->back()->with('success','Information Save successfully');



    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AccountHead  $accountHead
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $page_name = $this->page_name;
         $data = AccountHead::with(['_main_account_head'])->find($id);
        return view('backend.account-type.show',compact('data','page_name'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\AccountHead  $accountHead
     * @return \Illuminate\Http\Response
     */
    public function edit(AccountHead $accountHead,$id)
    {
        $page_name = $this->page_name;
        $data = AccountHead::find($id);
        return view('backend.account-type.edit',compact('data','page_name'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AccountHead  $accountHead
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {

        $request->validate([
                '_name' => 'required|max:255|unique:account_heads,_name,'.$request->id,
            ]);
        
        $data = AccountHead::find($request->id);
        $data->_name       = $request->_name ?? '';
        $data->_account_id = $request->_account_id ?? '';
        $data->_code       = $request->_code ?? '';
        $data->_status     = $request->_status ?? '';
        $data->save();

        return redirect('account-type')->with('success','Information Save successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AccountHead  $accountHead
     * @return \Illuminate\Http\Response
     */
    public function destroy(AccountHead $accountHead)
    {
        $__message ="You Can not delete this Information";
        $page_name ="Permission Denied";
        return view('backend.message.permission_message',compact('__message','page_name'));
    }
}
