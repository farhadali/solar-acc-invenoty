<?php

namespace App\Http\Controllers;

use App\Models\HRM\HrmEmployees;
use App\Models\HRM\HrmEmpCategory;
use App\Models\HRM\HrmDepartment;
use App\Models\HRM\Designation;
use App\Models\HRM\HrmGrade;
use App\Models\HRM\HrmEmpLocation;
use App\Models\AccountLedger;
use App\Models\GeneralSettings;
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


     public function employeeDataUpdate()
    {
     // \DB::statement(" UPDATE hrm_employees
     //        JOIN saif_employee_db ON saif_employee_db.EMP_ID= hrm_employees._code
     //        JOIN hrm_departments ON hrm_departments._department = saif_employee_db.DEPARTMENT
     //        SET hrm_employees._department_id=hrm_departments.id ");
//      \DB::statement(" UPDATE hrm_employees
// JOIN saif_employee_db ON saif_employee_db.EMP_ID= hrm_employees._code
// JOIN designations ON designations._name = saif_employee_db.DESIGNATION
// SET hrm_employees._jobtitle_id=designations.id ");

     // $old_data = \DB::select(" SELECT hrm_emp_categories.id as _id,saif_employee_db.CATEGORY,saif_employee_db.EMP_ID as _code FROM saif_employee_db INNER JOIN hrm_emp_categories ON hrm_emp_categories._name=saif_employee_db.CATEGORY  ");

   // $old_data = \DB::select(" SELECT designations.id as _id,saif_employee_db.DESIGNATION,saif_employee_db.EMP_ID as _code FROM saif_employee_db INNER JOIN designations ON designations._name=saif_employee_db.DESIGNATION  ");

  
//    $old_data = \DB::select(" SELECT hrm_grades.id as _id,saif_employee_db.EMP_GRADE,saif_employee_db.EMP_ID as _code
// FROM saif_employee_db 
// INNER JOIN hrm_grades ON hrm_grades._grade=saif_employee_db.EMP_GRADE  ");

//      foreach($old_data as $key=>$val){
//         $_code= $val->_code;
//         $_category_id= $val->_id;

//         //\DB::table("hrm_employees")->where('_code',$_code)->update(['_category_id'=>$_category_id]);
//         \DB::table("hrm_employees")->where('_code',$_code)->update(['_grade_id'=>$_category_id]);
//      }

     
           
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
   public function index(Request $request)
    {
       // return $request->all();
       if($request->has('limit')){
            $limit = $request->limit ??  default_pagination();
            session()->put('_employee_list', $request->limit);
        }else{
             $limit= Session::get('_employee_list') ??  default_pagination();
            
        }
       
        $_asc_desc = $request->_asc_desc ?? 'DESC';
        $asc_cloumn =  $request->asc_cloumn ?? 'id';
                $page_name = $this->page_name;

         $datas = HrmEmployees::with(['_employee_cat','_emp_department','_emp_designation','_emp_grade','_emp_location','_branch','_cost_center','_organization'])->where('_status',1);

         $all_row = $datas->count();
         
        if($request->has('id') && $request->id !=""){
            $ids =  array_map('intval', explode(',', $request->id ));
            $datas = $datas->whereIn('id', $ids); 
        }
        if($request->has('_code') && $request->_code !=''){
            $datas = $datas->where('_code','like',"%$request->_code%");
        }
        if($request->has('_name') && $request->_name !=''){
            $datas = $datas->where('_name','like',"%$request->_name%");
        }
        if($request->has('_mobile1') && $request->_mobile1 !=''){
            $datas = $datas->where('_mobile1','like',"%$request->_mobile1%");
        }
        if($request->has('_email') && $request->_email !=''){
            $datas = $datas->where('_email','like',"%$request->_email%");
        }
        if($request->has('_category_id') && $request->_category_id !=''){
            $datas = $datas->where('_category_id',$request->_category_id);
        }
        if($request->has('_department_id') && $request->_department_id !=''){
            $datas = $datas->where('_department_id',$request->_department_id);
        }
        if($request->has('_jobtitle_id') && $request->_jobtitle_id !=''){
            $datas = $datas->where('_jobtitle_id',$request->_jobtitle_id);
        }
        if($request->has('_grade_id') && $request->_grade_id !=''){
            $datas = $datas->where('_grade_id',$request->_grade_id);
        }

         $datas = $datas->orderBy($asc_cloumn,$_asc_desc);
         if($limit =='all'){
            $datas = $datas->paginate($all_row);
         }else{
            $datas = $datas->paginate($limit);
         }
         

        if($request->has('print')){
            if($request->print =="detail"){
                return view('hrm.hrm-employee.print',compact('datas','page_name','request'));
            }
         }

         $employee_catogories = HrmEmpCategory::where('_status',1)->orderBy('_name','ASC')->get();
         $departments = HrmDepartment::where('_status',1)->orderBy('_department','ASC')->get();
         $designations = Designation::where('_status',1)->orderBy('_name','ASC')->get();
         $grades = HrmGrade::where('_status',1)->orderBy('_grade','ASC')->get();
         $job_locations = HrmEmpLocation::where('_status',1)->orderBy('_name','ASC')->get();


        return view('hrm.hrm-employee.index',compact('datas','page_name','employee_catogories','departments','designations','grades','job_locations','limit','request'));
    }


    public function employeeSearch(Request $request){
       $limit = $request->limit ?? default_pagination();
        $_asc_desc = $request->_asc_desc ?? 'ASC';
        $asc_cloumn =  $request->asc_cloumn ?? '_code';
        $text_val = trim($request->_text_val);
        if($text_val =='%'){ $text_val=''; }








         $datas = HrmEmployees::with(['_organization','_branch','_cost_center','_employee_cat','_emp_department','_emp_designation','_emp_grade','_emp_location'])->where('_status',1)
        ->where(function ($query) use ($text_val) {
                $query->orWhere('_code','like',"%$text_val%")
                      ->orWhere('_name','like',"%$text_val%");
            });
        
        $datas = $datas->orderBy($asc_cloumn,$_asc_desc)->paginate($limit);
        return json_encode( $datas);
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
         $grades = HrmGrade::where('_status',1)->orderBy('_grade','ASC')->get();
         $job_locations = HrmEmpLocation::where('_status',1)->orderBy('_name','ASC')->get();
       
        return view('hrm.hrm-employee.create',compact('page_name','employee_catogories','departments','designations','grades','job_locations'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

         $this->validate($request, [
            '_name' => 'required',
            '_category_id' => 'required',
            '_department_id' => 'required',
            '_jobtitle_id' => 'required',
            '_grade_id' => 'required',
            
        ]);

        try {

            $_user = Auth::user();
            $_account_groups = GeneralSettings::select('_employee_group')->first();
            $_employee_group = $_account_groups->_employee_group;
            $_account_head_id = _find_group_to_head($_employee_group);

             $data = new AccountLedger();
            $data->_account_head_id = $_account_groups;
            $data->_account_group_id = $_account_head_id;
            $data->_branch_id = $request->_branch_id;
            $data->_name = $request->_name;
            $data->_address = $request->_officedes ?? 'N/A';
            $data->_code = $request->_code ?? '';
            $data->_nid = $request->_nid ?? 'N/A';
            $data->_email = $request->_email;
            $data->_phone = $request->_mobile1;
            $data->_credit_limit = $request->_ledger_credit_limit ?? 0;
            $data->_short = $request->_ledger_short ?? 1;
            $data->_is_user = $request->_ledger_is_user ?? 1;
            $data->_is_sales_form = 1;
            $data->_is_purchase_form = 1;
            $data->_is_all_branch = 1;
            $data->_status = 1;
            $data->_show =1;
            $data->_created_by = $_user->id."-".$_user->name;
            $data->save();
            $_ledger_id = $data->id;

            $data = new HrmEmployees();
            $data->_name =$request->_name ?? '';
            $data->_code =$request->_code ?? '';
            $data->_father =$request->_father ?? '';
            $data->_mother =$request->_mother ?? '';
            $data->_spouse =$request->_spouse ?? '';
            $data->_mobile1 =$request->_mobile1 ?? '';
            $data->_mobile2 =$request->_mobile2 ?? '';
            $data->_spousesmobile =$request->_spousesmobile ?? '';
            $data->_nid =$request->_nid ?? '';
            $data->_gender =$request->_gender ?? '';
            $data->_bloodgroup =$request->_bloodgroup ?? '';
            $data->_religion =$request->_religion ?? '';
            $data->_dob =$request->_dob ?? '';
            $data->_education =$request->_education ?? '';
            $data->_email =$request->_email ?? '';
            $data->_jobtitle_id =$request->_jobtitle_id ?? 1;
            $data->_department_id =$request->_department_id ?? 1;
            $data->_category_id =$request->_category_id ?? 1;
            $data->_grade_id =$request->_grade_id ?? 1;
            $data->_location =$request->_location ?? 1;
            $data->_officedes =$request->_officedes ?? 1;
            $data->_bank =$request->_bank ?? 1;
            $data->_bankac =$request->_bankac ?? 1;
            $data->_cost_center_id =$request->_cost_center_id ?? 1;
            $data->_branch_id =$request->_branch_id ?? 1;
            $data->organization_id =$request->organization_id ?? 1;
            $data->_active =$request->_active ?? 1;
            $data->_doj =$request->_doj ?? '';
            $data->_tin =$request->_tin ?? '';
            $data->_ledger_id =$_ledger_id;
            if($request->hasFile('_photo')){ 
                $_photo = UserImageUpload($request->_photo); 
                $data->_photo = $_photo;
            }
            if($request->hasFile('_signature')){ 
                $_signature = UserImageUpload($request->_signature); 
                $data->_signature = $_signature;
            }

            $data->_status =$request->_status ?? 0;
            $data->_user = $_user->id;
            $data->_created_by = $_user->id."-".$_user->_name;
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
     * @param  \App\Models\HRM\HrmEmployees  $HrmEmployees
     * @return \Illuminate\Http\Response
     */
   public function show($id)
    {
        $page_name = $this->page_name;
        $data = HrmEmployees::with(['_employee_cat','_emp_department','_emp_designation','_emp_grade','_emp_location','_branch','_cost_center','_organization'])->find($id);

        return view('hrm.hrm-employee.show',compact('data','page_name'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\HRM\HrmEmployees  $HrmEmployees
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        
         $data = HrmEmployees::find($id);
         $page_name = $this->page_name;
         $employee_catogories = HrmEmpCategory::where('_status',1)->orderBy('_name','ASC')->get();
         $departments = HrmDepartment::where('_status',1)->orderBy('_department','ASC')->get();
         $designations = Designation::where('_status',1)->orderBy('_name','ASC')->get();
         $grades = HrmGrade::where('_status',1)->orderBy('_grade','ASC')->get();
         $job_locations = HrmEmpLocation::where('_status',1)->orderBy('_name','ASC')->get();
       
        return view('hrm.hrm-employee.edit',compact('page_name','employee_catogories','departments','designations','grades','job_locations','data'));
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\HRM\HrmEmployees  $HrmEmployees
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            '_name' => 'required',
            '_category_id' => 'required',
            '_department_id' => 'required',
            '_jobtitle_id' => 'required',
            '_grade_id' => 'required',
            '_ledger_id' => 'required',
            
        ]);
        
        //return $request->_ledger_id;

        try {

            $_user = Auth::user();
            $_account_groups = GeneralSettings::select('_employee_group')->first();
            $_employee_group = $_account_groups->_employee_group;
             $_account_head_id = _find_group_to_head($_employee_group);
            if($request->_ledger_id ==0){
                $data = new AccountLedger();
            }else{
                $data = AccountLedger::find($request->_ledger_id);
                if(empty($data)){
                    $data = new AccountLedger();
                }
            }
            
            $data->_account_head_id = $_account_head_id;
            $data->_account_group_id = $_employee_group;
            $data->_branch_id = $request->_branch_id;
            $data->_name = $request->_name;
            $data->_address = $request->_officedes ?? 'N/A';
            $data->_code = $request->_code ?? '';
            $data->_nid = $request->_nid ?? 'N/A';
            $data->_email = $request->_email;
            $data->_phone = $request->_mobile1;
            $data->_credit_limit = $request->_ledger_credit_limit ?? 0;
            $data->_short = $request->_ledger_short ?? 1;
            $data->_is_user = $request->_ledger_is_user ?? 1;
            $data->_is_sales_form = 1;
            $data->_is_purchase_form = 1;
            $data->_is_all_branch = 1;
            $data->_status = 1;
            $data->_show =1;
            $data->_created_by = $_user->id."-".$_user->name;
            $data->save();
            $_ledger_id = $data->id;
            $request->_ledger_id=$_ledger_id;

            if($request->_ledger_is_user ==1){ 
                $user_id = create_update_user($request);
            }
        

            $data = HrmEmployees::find($id);
            $data->_name =$request->_name ?? '';
            $data->_code =$request->_code ?? '';
            $data->_father =$request->_father ?? '';
            $data->_mother =$request->_mother ?? '';
            $data->_spouse =$request->_spouse ?? '';
            $data->_mobile1 =$request->_mobile1 ?? '';
            $data->_mobile2 =$request->_mobile2 ?? '';
            $data->_spousesmobile =$request->_spousesmobile ?? '';
            $data->_nid =$request->_nid ?? '';
            $data->_gender =$request->_gender ?? '';
            $data->_bloodgroup =$request->_bloodgroup ?? '';
            $data->_religion =$request->_religion ?? '';
            $data->_dob =$request->_dob ?? '';
            $data->_education =$request->_education ?? '';
            $data->_email =$request->_email ?? '';
            $data->_jobtitle_id =$request->_jobtitle_id ?? 1;
            $data->_department_id =$request->_department_id ?? 1;
            $data->_category_id =$request->_category_id ?? 1;
            $data->_grade_id =$request->_grade_id ?? 1;
            $data->_location =$request->_location ?? 1;
            $data->_officedes =$request->_officedes ?? 1;
            $data->_bank =$request->_bank ?? 1;
            $data->_bankac =$request->_bankac ?? 1;
            $data->_cost_center_id =$request->_cost_center_id ?? 1;
            $data->_branch_id =$request->_branch_id ?? 1;
            $data->organization_id =$request->organization_id ?? 1;
            $data->_active =$request->_active ?? 1;
            $data->_doj =$request->_doj ?? '';
            $data->_tin =$request->_tin ?? '';
            $data->_ledger_id =$_ledger_id;
            if($request->_ledger_is_user ==1){
                $data->user_id =$user_id;
            }

            if($request->hasFile('_photo')){ 
                $_photo = UserImageUpload($request->_photo); 
                $data->_photo = $_photo;
            }
            if($request->hasFile('_signature')){ 
                $_signature = UserImageUpload($request->_signature); 
                $data->_signature = $_signature;
            }

            $data->_status =$request->_status ?? 0;
            $data->_user = $_user->id;
            $data->_created_by = $_user->id."-".$_user->_name;
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
     * @param  \App\Models\HRM\HrmEmployees  $HrmEmployees
     * @return \Illuminate\Http\Response
     */
     public function destroy($id)
    {
        HrmEmployees::where('id',$id)->update(['_status'=>0]);
        return redirect('hrm-employee')->with('success','Information deleted successfully');
    }
}
