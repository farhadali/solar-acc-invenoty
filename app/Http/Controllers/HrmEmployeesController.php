<?php

namespace App\Http\Controllers;

use App\Models\hrm\HrmEmployees;
use App\Models\hrm\HrmEmpCategory;
use App\Models\hrm\HrmDepartment;
use App\Models\hrm\Designation;
use Illuminate\Http\Request;
use Auth;
use Session;

class HrmEmployeesController extends Controller
{

     function __construct()
    {
        
         $this->middleware('permission:hrm-employee-create', ['only' => ['create','store']]);
         $this->middleware('permission:hrm-employee-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:hrm-employee-list', ['only' => ['index']]);
         $this->middleware('permission:hrm-employee-delete', ['only' => ['destroy']]);
         $this->page_name = __('label.hrm-employee');
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
            session()->put('_u_limit', $request->limit);
        }else{
             $limit= Session::get('_u_limit') ??  default_pagination();
            
        }
       
        $_asc_desc = $request->_asc_desc ?? 'DESC';
        $asc_cloumn =  $request->asc_cloumn ?? 'id';
        
        $page_name = $this->page_name;
         $datas = HrmEmployees::orderBy($asc_cloumn,$_asc_desc)->paginate($limit);

        if($request->has('print')){
            if($request->print =="detail"){
                return view('hrm.hrm-employee.print',compact('datas','page_name','request'));
            }
         }

        return view('hrm.hrm-employee.index',compact('datas','page_name'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
         $page_name = $this->page_name;
         $employee_catogories = HrmEmpCategory::where('_status',1)->orderBy('_name','ASC')->get();
         $departments = HrmDepartment::where('_status',1)->orderBy('_department','ASC')->get();
         $designations = Designation::where('_status',1)->orderBy('_name','ASC')->get();
       
        return view('hrm.hrm-employee.create',compact('page_name','employee_catogories','departments','designations'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        // dump($request->all());
        // die();
         $this->validate($request, [
            '_name' => 'required',
            
        ]);

        try {
            $_user = Auth::user();
            $data = new HrmEmployees();
            $data->_name =$request->_name ?? '';
            $data->_status =$request->_status ?? 0;
            $data->_user = $_user->id;
            $data->save();
            return redirect('hrm-employee')->with('success','Information Save successfully');
        }

        //catch exception
        catch(Exception $e) {
          echo 'Message: ' .$e->getMessage();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\hrm\HrmEmployees  $HrmEmployees
     * @return \Illuminate\Http\Response
     */
   public function show($id)
    {
        $page_name = $this->page_name;
        $data = HrmEmployees::find($id);

        return view('hrm.hrm-employee.show',compact('data','page_name'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\hrm\HrmEmployees  $HrmEmployees
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $page_name = $this->page_name;
         $data = HrmEmployees::find($id);
        

        return view('hrm.hrm-employee.edit',compact('data','page_name'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\hrm\HrmEmployees  $HrmEmployees
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            '_name' => 'required',
           
        ]);

        try {
            $_user = Auth::user();
            $data =  HrmEmployees::find($id);
             $data->_name =$request->_name ?? '';
            $data->_status =$request->_status ?? 0;
            $data->_user = $_user->id;
            $data->save();
            return redirect('hrm-employee')->with('success','Information Save successfully');
        }

        //catch exception
        catch(Exception $e) {
          echo 'Message: ' .$e->getMessage();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\hrm\HrmEmployees  $HrmEmployees
     * @return \Illuminate\Http\Response
     */
     public function destroy($id)
    {
        HrmEmployees::find($id)->delete();
        return redirect('hrm-employee')->with('success','Information deleted successfully');
    }
}
