<?php

namespace App\Http\Controllers;

use App\Models\ItemCategory;
use Illuminate\Http\Request;

class ItemCategoryController extends Controller
{

      function __construct()
    {
         $this->middleware('permission:item-category-list|item-category-create|item-category-edit|item-category-delete', ['only' => ['index','store']]);
         $this->middleware('permission:item-category-create', ['only' => ['create','store']]);
         $this->middleware('permission:item-category-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:item-category-delete', ['only' => ['destroy']]);
         $this->page_name = "Item Category";
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
   public function index(Request $request)
    {
        $limit = $request->limit ?? 10;
        $datas = ItemCategory::with(['_parents']);
        if($request->has('_name') && $request->_name !=''){
            $datas = $datas->where('_name','like',"%$request->_name%");
        }
        if($request->has('_parent_id') && $request->_parent_id !=''){
            $datas = $datas->where('_parent_id','=',$request->_parent_id);
        }
        $datas = $datas->orderBy('id','ASC')->paginate($limit);

        $page_name = $this->page_name;
         
        return view('backend.item-category.index',compact('datas','request','page_name'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       $page_name = $this->page_name;
       $parents_categories = ItemCategory::with(['_parents'])->orderBy('_name','asc')->get();
        return view('backend.item-category.create',compact('page_name','parents_categories'));
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
                '_name' => 'required|max:255|unique:item_categories,_name',
                '_parent_id' => 'required',
        ]);

        $this->validate($request, [
            '_name' => 'required|unique:item_categories,_name,'.$request->id,
        ]);

         $data = new ItemCategory();
        $data->_name       = $request->_name ?? '';
        $data->_parent_id = $request->_parent_id ?? '';
        if($request->hasFile('_image')){ 
                $_image = UserImageUpload($request->_image); 
                $data->_image = $_image;
        }
        $data->save();

        return redirect()->back()->with('success','Information Save successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ItemCategory  $itemCategory
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $page_name = $this->page_name;
        $data = ItemCategory::with(['_parents'])->find($id);
        return view('backend.item-category.show',compact('data','page_name'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ItemCategory  $itemCategory
     * @return \Illuminate\Http\Response
     */
     public function edit($id)
    {
        $page_name = $this->page_name;
        $data = ItemCategory::find($id);
        $parents_categories = ItemCategory::where('id','!=',$id)->orderBy('_name','asc')->get();
        return view('backend.item-category.edit',compact('data','page_name','parents_categories'));   
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ItemCategory  $itemCategory
     * @return \Illuminate\Http\Response
     */
     public function update(Request $request)
    {
        
        $request->validate([
                '_name' => 'required|max:255|unique:item_categories,_name,'.$request->id,
                '_parent_id' => 'required',
            ]);

        
        $data = ItemCategory::find($request->id);
        $data->_name       = $request->_name ?? '';
        $data->_parent_id = $request->_parent_id ?? '';
        if($request->hasFile('_image')){ 
                $_image = UserImageUpload($request->_image); 
                $data->_image = $_image;
        }
        $data->save();

        return redirect('item-category')->with('success','Information Save successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ItemCategory  $itemCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy(ItemCategory $ItemCategory)
    {
        return "You Can not delete this Information";
    }
}
