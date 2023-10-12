<?php

namespace App\Http\Controllers;

use App\Models\Budgets;
use App\Models\BudgetDetail;
use App\Models\BudgetItemDetail;
use App\Models\BudgetRevision;
use App\Models\BudgetRevisionDetail;
use App\Models\BudgetRevisionItemDetail;
use Illuminate\Http\Request;
use Auth;
use DB;

class BudgetsController extends Controller
{


     function __construct()
    {
         $this->middleware('permission:budgets-list|budgets-create|budgets-edit|budgets-delete', ['only' => ['index','store']]);
         $this->middleware('permission:budgets-create', ['only' => ['create','store']]);
         $this->middleware('permission:budgets-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:budgets-delete', ['only' => ['destroy']]);
         $this->page_name = __('label.budgets');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {




        $_asc_desc = $request->_asc_desc ?? 'DESC';
        $asc_cloumn =  $request->asc_cloumn ?? 'id';
        $limit = $request->limit ?? default_pagination();
        $datas = Budgets::with(['_master_branch','_master_cost_center','_organization','_budget_details_income','_budget_details_expense','_budget_item_details'])->where('_status',1);
        if($request->has('_status') && $request->_status !=''){
            $datas = $datas->where('_status',$request->_status);
        }
        if($request->has('organization_id') && $request->organization_id !=''){
            $datas = $datas->where('organization_id',$request->organization_id);
        }
        if($request->has('_cost_center_id') && $request->_cost_center_id !=''){
            $datas = $datas->where('_cost_center_id',$request->_cost_center_id);
        }
        if($request->has('_branch_id') && $request->_branch_id !=''){
            $datas = $datas->where('_branch_id',$request->_branch_id);
        }
        if($request->has('_start_date') && $request->_start_date !=''){
            $datas = $datas->where('_start_date','like',"%$request->_start_date%");
        }
        if($request->has('_end_date') && $request->_end_date !=''){
            $datas = $datas->where('_end_date','like',"%$request->_end_date%");
        }
        if($request->has('budget_amount') && $request->budget_amount !=''){
            $datas = $datas->where('budget_amount','like',"%$request->budget_amount%");
        }
        if($request->has('_remarks') && $request->_remarks !=''){
            $datas = $datas->where('_remarks','like',"%$request->_remarks%");
        }

          $datas = $datas->orderBy($asc_cloumn,$_asc_desc)->paginate($limit);
         // return $datas;
         $page_name = $this->page_name;
        return view('backend.budgets.index',compact('datas','request','page_name'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = Auth::user();
        $permited_branch = permited_branch(explode(',',$users->branch_ids));
        $permited_costcenters = permited_costcenters(explode(',',$users->cost_center_ids));
        $permited_organizations = permited_organization(explode(',',$users->organization_ids));
        $page_name = $this->page_name;
        return view('backend.Budgets.create',compact('page_name','permited_branch','permited_costcenters','permited_organizations'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //return $request->all();
        $request->validate([
            'organization_id' => 'required',
            '_branch_id' => 'required',
            '_cost_center_id' => 'required',
        ]);

        
          DB::beginTransaction();
           try {
            $users = \Auth::user();
            $_date = $request->_date;
            $_print_value=$request->_print ?? 0;

            $sum_of_income_amount=$request->_total_cr_amount ?? 0;
            $sum_of_expense_amount=$request->_total_dr_amount ?? 0;
            $sum_of_material_amount= $request->_total_value_amount ?? 0;
            
            $Budgets = new Budgets();
            $Budgets->organization_id = $request->organization_id;
            $Budgets->_branch_id = $request->_branch_id;
            $Budgets->_cost_center_id = $request->_cost_center_id;
            $Budgets->_start_date = $request->_start_date;
            $Budgets->_end_date = $request->_end_date;
            $Budgets->_remarks = $request->_remarks ?? '';
            $Budgets->_status = $request->_status ?? 1;
            $Budgets->_material_amount = $sum_of_material_amount;
            $Budgets->_income_amount = $sum_of_income_amount;
            $Budgets->_expense_amount = $sum_of_material_amount;
            $Budgets->_project_value = $request->_project_value ?? 0;
            $Budgets->_created_by = $users->id;
            $Budgets->save();

            $budget_id = $Budgets->id;

            //Income Section for Budgets
            $income_ledgers = $request->_ledger_id ?? [];
            $income_short_narr = $request->_short_narr ?? [];
            $income_cr_amounts = $request->_cr_amount ?? [];
             for ($i=0; $i <sizeof($income_ledgers) ; $i++) { 
                $income_data = new BudgetDetail();
                $income_data->_budget_id = $budget_id;
                $income_data->_ledger_id = $income_ledgers[$i] ?? 0;
                $income_data->_short_narr = $income_short_narr[$i] ?? '';
                $income_data->_ledger_type = 'income';
                $income_data->_budget_amount = $income_cr_amounts[$i] ?? 0;
                $income_data->_status = 1;
                $income_data->save();
             }

            //Expense Section for Budgets
            $_exp_ledger_ids = $request->_exp_ledger_id ?? [];
            $_exp_short_narrs = $request->_exp_short_narr ?? [];
            $_exp_dr_amounts = $request->_exp_dr_amount ?? [];
            $_exp_ledger_types = $request->_ledger_type ?? [];
             for ($i=0; $i <sizeof($_exp_ledger_ids) ; $i++) { 
                $income_data = new BudgetDetail();
                $income_data->_budget_id = $budget_id;
                $income_data->_ledger_id = $_exp_ledger_ids[$i] ?? 0;
                $income_data->_short_narr = $_exp_short_narrs[$i] ?? '';
                $income_data->_ledger_type = $_exp_ledger_types[$i];
                $income_data->_budget_amount = $_exp_dr_amounts[$i] ?? 0;
                $income_data->_status = 1;
                $income_data->save();
             }

             //Materials Expense Heads
             $_item_ids = $request->_item_id ?? [];
             $_transection_units = $request->_transection_unit ?? [];
             $_qtys = $request->_qty ?? [];
             $_rates = $request->_rate ?? [];
             $_values = $request->_value ?? [];
              for ($i=0; $i <sizeof($_item_ids) ; $i++) {
                $material_data = new BudgetItemDetail();
                $material_data->_budget_id = $budget_id;
                $material_data->_item_id = $_item_ids[$i];
                $material_data->_item_unit_id = $_transection_units[$i];
                $material_data->_item_type = findItemBaseCat($_item_ids[$i]);
                $material_data->_item_qty = $_qtys[$i];
                $material_data->_item_unit_price = $_rates[$i];
                $material_data->_item_budget_amount = $_values[$i];
                $material_data->_status = 1;
                $material_data->save();

              }
            
               DB::commit();
                return redirect()->back()
                ->with('success','Information save successfully')
                ->with('_master_id',$_master_id)
                ->with('_print_value',$_print_value);
           } catch (\Exception $e) {
               DB::rollback();
               return redirect()->back()->with('error','Somthing Went Wrong');
            }


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Budgets  $budgets
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //return $id;
         $data = Budgets::with(['_master_branch','_master_cost_center','_organization','_budget_details_income','_budget_details_expense','_budget_item_details','_budget_details_deduction','budget_authorised_order'])->find($id);



       return view('backend.budgets.show',compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Budgets  $budgets
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
         $data = Budgets::with(['_master_branch','_master_cost_center','_organization','_budget_details_income','_budget_details_expense_deduction','_budget_item_details'])->find($id);
        $users = Auth::user();
        $permited_branch = permited_branch(explode(',',$users->branch_ids));
        $permited_costcenters = permited_costcenters(explode(',',$users->cost_center_ids));
        $permited_organizations = permited_organization(explode(',',$users->organization_ids));
        $page_name = $this->page_name;
        return view('backend.Budgets.edit',compact('page_name','permited_branch','permited_costcenters','permited_organizations','data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Budgets  $budgets
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
         $request->validate([
            'organization_id' => 'required',
            '_branch_id' => 'required',
            '_cost_center_id' => 'required',
        ]);
//return $id;
//return $request->all();
        
          DB::beginTransaction();
           try {
             $users = \Auth::user();
            $_date = $request->_date;
            $_print_value=$request->_print ?? 0;

            $sum_of_income_amount=$request->expected_income ?? 0;
            $sum_of_expense_amount=$request->expected_other_expenses ?? 0;
            $sum_of_material_amount= $request->expected_material_expense ?? 0;

            BudgetDetail::where('_budget_id',$id)->update(['_status'=>0]);
            BudgetDetail::where('_budget_id',$id)->update(['_status'=>0]);
            BudgetItemDetail::where('_budget_id',$id)->update(['_status'=>0]);
            
            $Budgets = Budgets::find($id);
            $Budgets->organization_id = $request->organization_id;
            $Budgets->_branch_id = $request->_branch_id;
            $Budgets->_cost_center_id = $request->_cost_center_id;
            $Budgets->_start_date = $request->_start_date;
            $Budgets->_end_date = $request->_end_date;
            $Budgets->_remarks = $request->_remarks ?? '';
            $Budgets->_status = $request->_status ?? 1;
            $Budgets->_material_amount = $sum_of_material_amount;
            $Budgets->_income_amount = $sum_of_income_amount;
            $Budgets->_expense_amount = $sum_of_expense_amount;
            $Budgets->_project_value = $request->_project_value ?? 0;
            $Budgets->_updated_by = $users->id;
            
            $Budgets->save();

            $budget_id = $Budgets->id;

            //Income Section for Budgets
            $income_row_ids = $request->_income_row_id ?? [];
            $income_ledgers = $request->_ledger_id ?? [];
            $income_short_narr = $request->_short_narr ?? [];
            $income_cr_amounts = $request->_cr_amount ?? [];
             for ($i=0; $i <sizeof($income_ledgers) ; $i++) { 
                if($income_row_ids[$i]==0){
                   $income_data = new BudgetDetail();
                }else{
                    $income_data = BudgetDetail::find($income_row_ids[$i]);
                }
                $income_data->_budget_id = $budget_id;
                $income_data->_ledger_id = $income_ledgers[$i] ?? 0;
                $income_data->_short_narr = $income_short_narr[$i] ?? '';
                $income_data->_ledger_type = 'income';
                $income_data->_budget_amount = $income_cr_amounts[$i] ?? 0;
                $income_data->_status = 1;
                $income_data->save();
             }

            //Expense Section for Budgets
            $_exp_row_ids = $request->_exp_row_id ?? [];
            $_exp_ledger_ids = $request->_exp_ledger_id ?? [];
            $_exp_short_narrs = $request->_exp_short_narr ?? [];
            $_exp_dr_amounts = $request->_exp_dr_amount ?? [];
             $_exp_ledger_types = $request->_ledger_type ?? [];

             for ($i=0; $i <sizeof($_exp_row_ids) ; $i++) { 
                if($_exp_row_ids[$i]==0){
                    $exp_deduction_data = new BudgetDetail();
                }else{
                    $exp_deduction_data = BudgetDetail::find($_exp_row_ids[$i]);
                }
                
                $exp_deduction_data->_budget_id = $budget_id;
                $exp_deduction_data->_ledger_id = $_exp_ledger_ids[$i] ?? 0;
                $exp_deduction_data->_short_narr = $_exp_short_narrs[$i] ?? '';
                $exp_deduction_data->_ledger_type = $_exp_ledger_types[$i];
                $exp_deduction_data->_budget_amount = $_exp_dr_amounts[$i] ?? 0;
                $exp_deduction_data->_status = 1;
                $exp_deduction_data->save();
             }

             //Materials Expense Heads
             $_item_row_ids = $request->_item_row_id ?? [];
             $_item_ids = $request->_item_id ?? [];
             $_transection_units = $request->_transection_unit ?? [];
             $_qtys = $request->_qty ?? [];
             $_rates = $request->_rate ?? [];
             $_values = $request->_value ?? [];
              for ($i=0; $i <sizeof($_item_ids) ; $i++) {
                if($_item_row_ids[$i]==0){
                    $material_data = new BudgetItemDetail();
                }else{
                    $material_data = BudgetItemDetail::find($_item_row_ids[$i]);
                }
                
                $material_data->_budget_id = $budget_id;
                $material_data->_item_id = $_item_ids[$i];
                $material_data->_item_unit_id = $_transection_units[$i];
                $material_data->_item_type = findItemBaseCat($_item_ids[$i]);
                $material_data->_item_qty = $_qtys[$i];
                $material_data->_item_unit_price = $_rates[$i];
                $material_data->_item_budget_amount = $_values[$i];
                $material_data->_status = 1;
                $material_data->save();

              }
            
               DB::commit();
                return redirect()->back()
                ->with('success','Information save successfully')
                ->with('_master_id',$_master_id)
                ->with('_print_value',$_print_value);
           } catch (\Exception $e) {
               DB::rollback();
               return redirect()->back()->with('error','Somthing Went Wrong');
            }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Budgets  $budgets
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        BudgetDetail::where('_budget_id',$id)->update(['_status'=>0]);
            BudgetDetail::where('_budget_id',$id)->update(['_status'=>0]);
            BudgetItemDetail::where('_budget_id',$id)->update(['_status'=>0]);
            Budgets::where('id',$id)->update(['_status'=>0]);
            return redirect()->back()
                ->with('success','Information Deleted successfully');
    }
}
