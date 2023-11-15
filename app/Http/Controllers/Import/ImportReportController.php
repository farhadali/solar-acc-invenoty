<?php

namespace App\Http\Controllers\Import;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ImportPuchase;
use App\Models\Purchase;
use Auth;
use DB;
use Session;

class ImportReportController extends Controller
{
    //

    public function importReportDashboard(){
        return view('import-report.dashboard');
    }

    public function masterVesselWiseLigtherReport(Request $request){
        $page_name = __('label.master_vessel_wise_ligther_report');


        $users = Auth::user();
        $request_branchs = $request->_branch_id ?? [];
        $request_cost_centers = $request->_cost_center ?? [];
        $request_organizations = $request->organization_id ?? [];

        $permited_organizations = permited_organization(explode(',',$users->organization_ids));
        $permited_branch = permited_branch(explode(',',$users->branch_ids));
        $permited_costcenters = permited_costcenters(explode(',',$users->cost_center_ids));

       $_organization_ids = filterableOrganization($request_organizations,$permited_organizations);
       $_branch_ids = filterableBranch($request_branchs,$permited_branch);
       $_cost_center_ids = filterableCostCenter($request_cost_centers,$permited_costcenters);

       $_organization_id_rows = implode(',', $_organization_ids);
       $_branch_ids_rows = implode(',', $_branch_ids);
       $_cost_center_id_rows = implode(',', $_cost_center_ids);


        $importInvoices = ImportPuchase::with(['_mother_vessel'])
                                        ->whereIn('organization_id',$_organization_ids)
                                        ->whereIn('_branch_id',$_branch_ids)
                                        ->whereIn('_cost_center_id',$_cost_center_ids)
                                        ->get();

        $report_title='Daily Servey Report Coal in Bulk';


        $datas=[];
        $single_data='';
        if($request->has('import_invoice_no') && $request->import_invoice_no !=''){
             $single_data = ImportPuchase::with(['_mother_vessel','_organization'])->find($request->import_invoice_no);
               $datas = Purchase::with(['_master_branch','_vessel_detail','_route_info'])->where('import_invoice_no',$request->import_invoice_no)->get();
        }





        return view('import-report.master_vessel_wise_ligther_report',compact('page_name','importInvoices','request','datas','single_data','report_title'));
    }
}
