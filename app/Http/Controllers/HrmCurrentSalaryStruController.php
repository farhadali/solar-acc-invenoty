<?php

namespace App\Http\Controllers;
use App\Models\hrm\CurrentSalaryStructure;
use App\Models\hrm\HrmPayheads;

use Illuminate\Http\Request;
use Auth;
use Session;

class HrmCurrentSalaryStruController extends Controller
{

    function __construct()
    {
         $this->middleware('permission:initial-salary-structure-list|initial-salary-structure-create|initial-salary-structure-edit|initial-salary-structure-delete', ['only' => ['index','store']]);
         $this->middleware('permission:initial-salary-structure-create', ['only' => ['create','store']]);
         $this->middleware('permission:initial-salary-structure-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:initial-salary-structure-delete', ['only' => ['destroy']]);
         $this->page_name = __('label.initial-salary-structure');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $page_name = $this->page_name;
        $payheads_data = HrmPayheads::with(['_payhead_type'])->where('_status',1)->get();
        $payheads =array();
        $payhead_types=array();
        foreach($payheads_data as $key=>$val){
            if(!in_array($val->_payhead_type->_name ?? '', $payhead_types)){
                array_push($payhead_types,$val->_payhead_type->_name ?? '');
            }
            $payheads[$val->_payhead_type->_name ?? ''][]=$val;
        }
//return $payheads;
        return view('hrm.initial-salary-structure.create',compact('page_name','payheads','payhead_types'));

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
