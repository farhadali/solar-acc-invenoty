<?php

namespace App\Http\Controllers;

use App\Models\ItemType;
use Illuminate\Http\Request;

class ItemTypeController extends Controller
{

      function __construct()
    {
         $this->middleware('permission:item-information-list|item-information-create|item-information-edit|item-information-delete|item-information-print', ['only' => ['index','store']]);
         $this->middleware('permission:item-information-print', ['only' => ['purchasePrint']]);
         $this->middleware('permission:item-information-create', ['only' => ['create','store']]);
         $this->middleware('permission:item-information-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:item-information-delete', ['only' => ['destroy']]);
         $this->page_name = __('label.item_type');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $limit = $request->limit ?? 10;
        $datas = ItemType::where('_is_delete',0);
        if($request->has('_name') && $request->_name !=''){
            $datas = $datas->where('_name','like',"%$request->_name%");
        }
        if($request->has('_code') && $request->_code !=''){
            $datas = $datas->where('_code','like',"%$request->_code%");
        }
        if($request->has('_detail') && $request->_detail !=''){
            $datas = $datas->where('_detail','like',"%$request->_detail%");
        }
        if($request->has('_status') && $request->_status !=''){
            $datas = $datas->where('_status','=',$request->_status);
        }
        $datas = $datas->orderBy('id','ASC')->paginate($limit);

        $page_name = $this->page_name;
         
        return view('backend.item-type.index',compact('datas','request','page_name'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       $page_name = $this->page_name;
        return view('backend.item-type.create',compact('page_name'));
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
                '_name' => 'required|max:255|unique:item_types,_name',
                
        ]);

        $this->validate($request, [
            '_name' => 'required|unique:item_types,_name,'.$request->id,
        ]);

         $data = new ItemType();
        $data->_name    = $request->_name ?? '';
        $data->_code    = $request->_code ?? '';
        $data->_detail  = $request->_detail ?? '';
        $data->_status  = $request->_status ?? 0;
       
        $data->save();

        return redirect()->back()->with('success','Information Save successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ItemType  $ItemType
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $page_name = $this->page_name;
        $data = ItemType::find($id);
        return view('backend.item-type.show',compact('data','page_name'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ItemType  $ItemType
     * @return \Illuminate\Http\Response
     */
     public function edit($id)
    {
        $page_name = $this->page_name;
        $data = ItemType::find($id);
        return view('backend.item-type.edit',compact('data','page_name'));   
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ItemType  $ItemType
     * @return \Illuminate\Http\Response
     */
     public function update(Request $request)
    {
        
        $request->validate([
                '_name' => 'required|max:255|unique:item_types,_name,'.$request->id,
                
            ]);

        
        $data = ItemType::find($request->id);
        $data->_name    = $request->_name ?? '';
        $data->_code    = $request->_code ?? '';
        $data->_detail  = $request->_detail ?? '';
        $data->_status  = $request->_status ?? 0;
        
        $data->save();

        return redirect('item-type')->with('success','Information Save successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ItemType  $ItemType
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        ItemType::where('id',$id)->update(['_is_delete'=>1]);
        return redirect('item-type')->with('danger','Information deleted successfully');
    }
}
