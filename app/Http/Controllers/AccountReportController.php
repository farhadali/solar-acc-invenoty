<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AccountGroup;
use App\Models\Accounts;
use App\Models\AccountLedger;
use App\Models\MainAccountHead;
use App\Models\VoucherType;
use App\Models\Sales;
use App\Models\ResturantSales;
use App\Models\ResturantFormSetting;
use App\Models\SalesFormSetting;
use App\Models\GeneralSettings;
use Illuminate\Support\Facades\DB;
use Session;
use Auth;

class AccountReportController extends Controller
{


function __construct()
    {
         
         $this->middleware('permission:ledger-report', ['only' => ['ledgerReprt','ledgerReprtShow']]);
         $this->middleware('permission:trail-balance', ['only' => ['trailBalance','trailBalanceReport']]);
         $this->middleware('permission:income-statement', ['only' => ['incomeStatement','incomeStatementReport']]);
         $this->middleware('permission:balance-sheet', ['only' => ['balanceSheet','balanceSheetReport']]);
         $this->middleware('permission:work-sheet', ['only' => ['workSheet','workSheetReport']]);
         $this->middleware('permission:group-ledger', ['only' => ['groupLedger','groupBaseLedgerReport']]);
         $this->middleware('permission:income-statement-settings', ['only' => ['incomeStatementSettings']]);
         $this->middleware('permission:ledger-summary-report', ['only' => ['ledgerSummaryReport','filterLedgerSummarray']]);
         $this->middleware('permission:cash-book', ['only' => ['cashBook','cashBookReport']]);
         $this->middleware('permission:bank-book', ['only' => ['bankBook','bankBookReport']]);
         $this->middleware('permission:day-book', ['only' => ['dayBook','dayBookReport']]);
         $this->middleware('permission:user-wise-collection-payment', ['only' => ['userReceiptPayment','userReceiptPaymentReport']]);
         $this->middleware('permission:date-wise-invoice-print', ['only' => ['dateWiseInvoice','dateWiseInvoiceReport']]);














    }

	//###################################
	//
	//
	//####################################

    public function ledgerReprt(Request $request){
         $previous_filter= Session::get('ledgerReprtFilter');
    	$page_name = "Ledger Report";
        $users = Auth::user();
        $permited_branch = permited_branch(explode(',',$users->branch_ids));
        $permited_costcenters = permited_costcenters(explode(',',$users->cost_center_ids));
    	return view('backend.account-report.ledger',compact('request','page_name','permited_branch','permited_costcenters','previous_filter'));

    }

  //###################################
  //
  //
  //####################################

    public function chartOfAccount(Request $request){
       
        $page_name = "Chart of Accounts";
         $data = MainAccountHead::with(['_account_type'])->get();
        
      return view('backend.account-report.chart-of-account',compact('request','page_name','data'));

    }


    public function ledgerReprtShow(Request $request){
        //return $request->all();
    	 $this->validate($request, [
            '_datex' => 'required',
            '_datey' => 'required',
            '_ledger_id' => 'required'
        ]);
         $_datex =  change_date_format($request->_datex);
         $_datey=  change_date_format($request->_datey);
     //    $users = Auth::user();
     //    $permited_branch = permited_branch(explode(',',$users->branch_ids));
     //    $permited_costcenters = permited_costcenters(explode(',',$users->cost_center_ids));

       
     //      $request_branchs = $request->_branch_id ?? [];
     //      $request_cost_centers = $request->_cost_center ?? [];

     //      $_branch_ids = filterableBranch($request_branchs,$permited_branch);
     //      $_cost_center_ids = filterableCostCenter($request_cost_centers,$permited_costcenters);

     //      $_branch_ids_rows = implode(',', $_branch_ids);
     //      $_cost_center_id_rows = implode(',', $_cost_center_ids);
                


       session()->put('ledgerReprtFilter', $request->all());
       $previous_filter= Session::get('ledgerReprtFilter');

     
                  
       $ledger_info = AccountLedger::with(['account_type','account_group','_entry_branch'])->find($request->_ledger_id);
    	
        $users = Auth::user();
        $permited_branch = permited_branch(explode(',',$users->branch_ids));
        $permited_costcenters = permited_costcenters(explode(',',$users->cost_center_ids));
        $datas=[];
         $_datex =  change_date_format($request->_datex);
        $_datey=  change_date_format($request->_datey);

       

        
$page_name = "Ledger Report";
       

      $request_branchs = $request->_branch_id ?? [];
      $request_cost_centers = $request->_cost_center ?? [];

      $request_organizations = $request->organization_id ?? [];
      $permited_organizations = permited_organization(explode(',',$users->organization_ids));
      $_organization_ids = filterableOrganization($request_organizations,$permited_organizations);
      $_organization_id_rows = implode(',', $_organization_ids);


      $_branch_ids = filterableBranch($request_branchs,$permited_branch);
      $_cost_center_ids = filterableCostCenter($request_cost_centers,$permited_costcenters);

      $ledger_id_rows = (int) $request->_ledger_id;
      $_branch_ids_rows = implode(',', $_branch_ids);
      $_cost_center_id_rows = implode(',', $_cost_center_ids);
      
     if($ledger_id_rows){
     $string_query = " 
     SELECT s1._account_group,s1._group_name, s1._account_ledger,s1._l_name,s1._branch_id,s1._cost_center, s1._branch_name, s1._id,s1._table_name, s1._date, s1._short_narration, s1._narration, s1._dr_amount, s1._cr_amount, s1._balance,s1._serial FROM(

     SELECT t1._account_group AS _account_group,t2._name as _group_name, t1._account_ledger AS _account_ledger,t3._name as _l_name,t1._branch_id AS _branch_id,t1._cost_center as _cost_center, t4._name as _branch_name, null as _id,null as _table_name, null as _date, null as _short_narration, 'Opening Balance' as _narration, 0 AS _dr_amount, 0  AS _cr_amount, SUM(t1._dr_amount-t1._cr_amount) AS _balance ,0 as _serial 
            FROM accounts as t1
            INNER JOIN account_groups as t2 ON t2.id=t1._account_group
            INNER JOIN account_ledgers as t3 ON t3.id=t1._account_ledger
            INNER JOIN branches as t4 ON t4.id = t1._branch_id
               WHERE t1._status=1 AND t1._date < '".$_datex."' AND t1._account_ledger IN(".$ledger_id_rows.") AND t1.organization_id IN(".$_organization_id_rows.")
               AND  t1._branch_id IN(".$_branch_ids_rows.") AND  t1._cost_center IN(".$_cost_center_id_rows.")
                 GROUP BY t1._account_ledger
      UNION ALL
            SELECT t1._account_group AS _account_group,t2._name as _group_name, t1._account_ledger AS _account_ledger,t3._name as _l_name,t1._branch_id AS _branch_id,t1._cost_center as _cost_center, t4._name as _branch_name, t1._ref_master_id as _id, t1._table_name AS _table_name, t1._date as _date, t1._short_narration as _short_narration, t1._narration as _narration, t1._dr_amount AS _dr_amount, t1._cr_amount  AS _cr_amount, 0 AS _balance ,t1._serial as _serial
            FROM accounts AS t1
            INNER JOIN account_groups as t2 ON t2.id=t1._account_group
            INNER JOIN account_ledgers as t3 ON t3.id=t1._account_ledger
            INNER JOIN branches as t4 ON t4.id = t1._branch_id
              WHERE  t1._status=1 AND t1._date  >= '".$_datex."'  AND t1._date <= '".$_datey."' 
              AND t1._account_ledger IN(".$ledger_id_rows.") AND t1.organization_id IN(".$_organization_id_rows.") AND  t1._branch_id IN(".$_branch_ids_rows.") AND  t1._cost_center IN(".$_cost_center_id_rows.") ) as s1 order by s1._date,s1._id,s1._serial ASC  ";

       $datas = DB::select($string_query);
       $group_array_values = array();
       foreach ($datas as $value) {
           $group_array_values[$value->_group_name][$value->_l_name][]=$value;
       }

}else{
   $group_array_values = array();
}
$ledger_details =[];

      //return $group_array_values;
    	return view('backend.account-report.ledger_show',compact('request','page_name','previous_filter','permited_branch','permited_costcenters','group_array_values','_datex','_datey','ledger_id_rows','ledger_info'));

    }

//Day book filter
    public function dayBook(Request $request){
        $previous_filter= Session::get('filter_day_book');
        $page_name = "Day Book";
        $voucher_types = VoucherType::select('_name','_code')->get();
        $transactions=DB::select('SELECT DISTINCT `_transaction` FROM `accounts`'); 
        $users = Auth::user();
        $permited_branch = permited_branch(explode(',',$users->branch_ids));
        $permited_costcenters = permited_costcenters(explode(',',$users->cost_center_ids));
        return view('backend.account-report.filter_day_book',compact('request','page_name','voucher_types','previous_filter','permited_branch','permited_costcenters','transactions'));
    }

    public function dayBookReport(Request $request){

      $this->validate($request, [
            '_datex' => 'required',
            '_datey' => 'required',
            '_transaction' => 'required',
        ]);

        session()->put('filter_day_book', $request->all());
        $previous_filter= Session::get('filter_day_book');
        $page_name = "Day Book";
         $users = Auth::user();
        $permited_branch = permited_branch(explode(',',$users->branch_ids));
        $permited_costcenters = permited_costcenters(explode(',',$users->cost_center_ids));
        $datas=[];
        $_datex =  change_date_format($request->_datex);
        $_datey=  change_date_format($request->_datey);
        $request_branchs = $request->_branch_id ?? [];
        $request_cost_centers = $request->_cost_center ?? [];

        $_branch_ids = filterableBranch($request_branchs,$permited_branch);
        $_cost_center_ids = filterableCostCenter($request_cost_centers,$permited_costcenters);
        $_voucher_types = $request->_voucher_type ?? [];
        $_transaction = $request->_transaction ?? 'all';
        $_voucher_types_codes='';
         if(sizeof($_voucher_types) > 0){
          foreach ($_voucher_types as $value) {
            $_voucher_types_codes .="'".$value."',";
          }
          $_voucher_types_codes = rtrim($_voucher_types_codes,",");
        }
        $_branch_ids_rows = implode(',', $_branch_ids);
        $_cost_center_id_rows = implode(',', $_cost_center_ids);


        $request_organizations = $request->organization_id ?? [];
      $permited_organizations = permited_organization(explode(',',$users->organization_ids));
      $_organization_ids = filterableOrganization($request_organizations,$permited_organizations);
      $_organization_id_rows = implode(',', $_organization_ids);


        $query = " SELECT `_ref_master_id` as _id,`_short_narration`,`_narration`,`_reference`,`_transaction`,`_voucher_type`,`_date`,`_account_group`,`_account_ledger`,`_dr_amount`,`_cr_amount`,`_table_name` FROM
         `accounts` WHERE  _status=1 AND _date  >= '".$_datex."'  AND _date <= '".$_datey."' 
               AND  _branch_id IN(".$_branch_ids_rows.") AND organization_id IN(".$_organization_id_rows.") AND  _cost_center IN(".$_cost_center_id_rows.")  ";
          if(sizeof($_voucher_types) > 0){
            $query .= " AND _voucher_type IN(".$_voucher_types_codes.") ";
          }
          if($_transaction !='all'){
             $query .= " AND _transaction ='".$_transaction."' ";
          }
          $query .= " ORDER BY _date, _ref_master_id ASC";
           $query_result = DB::select($query);
           $_result_group = array();
           foreach ($query_result as $value) {
             $_result_group["ID:".$value->_id." | Date:"._view_date_formate($value->_date)." | Transaction: ".$value->_transaction." | Type: ".$value->_voucher_type." | Reference".$value->_reference ??'N/A'][]=$value;
           }

         
        return view('backend.account-report.report_day_book',compact('request','page_name','previous_filter','permited_branch','permited_costcenters','_result_group'));

    }

public function dayBookFilterReset(){
  Session::flash('filter_day_book');
  return redirect()->back();
}


//Day Wise Summary Report For Restaurant Part

 public function dayWiseSummaryReportFilter(Request $request){
        $previous_filter= Session::get('day_wise_summary_report_filter');
        $page_name = "Work Period Sales Summary Report";
         
        $users = Auth::user();
        $permited_branch = permited_branch(explode(',',$users->branch_ids));
        $permited_costcenters = permited_costcenters(explode(',',$users->cost_center_ids));
        return view('backend.account-report.filter_day_wise_summary_report',compact('request','page_name','previous_filter','permited_branch','permited_costcenters'));
    }


public function dayWiseSummaryReport(Request $request){

  $this->validate($request, [
            '_datex' => 'required',
            '_datey' => 'required',
        ]);

        session()->put('day_wise_summary_report_filter', $request->all());
        $previous_filter= Session::get('day_wise_summary_report_filter');
        $page_name = "Work Period Sales Summary Report";
         $users = Auth::user();
        $permited_branch = permited_branch(explode(',',$users->branch_ids));
        $permited_costcenters = permited_costcenters(explode(',',$users->cost_center_ids));
        $datas=[];
        $_datex =  change_date_format($request->_datex);
        $_datey=  change_date_format($request->_datey);
        $request_branchs = $request->_branch_id ?? [];
        $request_cost_centers = $request->_cost_center ?? [];

        $_branch_ids = filterableBranch($request_branchs,$permited_branch);
        $_cost_center_ids = filterableCostCenter($request_cost_centers,$permited_costcenters);
        $_branch_ids_rows = implode(',', $_branch_ids);
        $_cost_center_id_rows = implode(',', $_cost_center_ids);


    $request_organizations = $request->organization_id ?? [];
    $permited_organizations = permited_organization(explode(',',$users->organization_ids));
    $_organization_ids = filterableOrganization($request_organizations,$permited_organizations);
    $_organization_id_rows = implode(',', $_organization_ids);


  $ResturantFormSetting = ResturantFormSetting::first();
  $_default_sales = $ResturantFormSetting->_default_sales;
  $_default_discount = $ResturantFormSetting->_default_discount;
  $_default_vat_account = $ResturantFormSetting->_default_vat_account;
  $_default_service_charge = $ResturantFormSetting->_default_service_charge;
  $_default_other_charge = $ResturantFormSetting->_default_other_charge;
  $_default_delivery_charge = $ResturantFormSetting->_default_delivery_charge;

  $_sales =" SELECT  t1._account_ledger AS _account_ledger,t3._name as _l_name,SUM(t1._cr_amount-t1._dr_amount) AS _balance
            FROM accounts AS t1
            INNER JOIN account_groups as t2 ON t2.id=t1._account_group
            INNER JOIN account_ledgers as t3 ON t3.id=t1._account_ledger
            INNER JOIN branches as t4 ON t4.id = t1._branch_id
              WHERE  t1._status=1 AND t1._date  >= '".$_datex."'  AND t1._date <= '".$_datey."' 
              AND t1._account_ledger IN(".$_default_sales.") AND  t1._branch_id IN(".$_branch_ids_rows.")  AND t1.organization_id IN(".$_organization_id_rows.") AND  t1._cost_center IN(".$_cost_center_id_rows.") GROUP BY t1._account_ledger ";
  $_default_sales_result = DB::select($_sales);

  $_discount =" SELECT  t1._account_ledger AS _account_ledger,t3._name as _l_name,SUM(t1._cr_amount-t1._dr_amount) AS _balance
            FROM accounts AS t1
            INNER JOIN account_groups as t2 ON t2.id=t1._account_group
            INNER JOIN account_ledgers as t3 ON t3.id=t1._account_ledger
            INNER JOIN branches as t4 ON t4.id = t1._branch_id
              WHERE  t1._status=1 AND t1._date  >= '".$_datex."'  AND t1._date <= '".$_datey."' 
              AND t1._account_ledger IN(".$_default_discount.") AND  t1._branch_id IN(".$_branch_ids_rows.") AND t1.organization_id IN(".$_organization_id_rows.") AND  t1._cost_center IN(".$_cost_center_id_rows.") GROUP BY t1._account_ledger ";
  $_default_discount_result = DB::select($_discount);
  
  $_vat_account =" SELECT  t1._account_ledger AS _account_ledger,t3._name as _l_name,SUM(t1._cr_amount-t1._dr_amount) AS _balance
            FROM accounts AS t1
            INNER JOIN account_groups as t2 ON t2.id=t1._account_group
            INNER JOIN account_ledgers as t3 ON t3.id=t1._account_ledger
            INNER JOIN branches as t4 ON t4.id = t1._branch_id
              WHERE  t1._status=1 AND t1._date  >= '".$_datex."'  AND t1._date <= '".$_datey."' 
              AND t1._account_ledger IN(".$_default_vat_account.") AND  t1._branch_id IN(".$_branch_ids_rows.") AND t1.organization_id IN(".$_organization_id_rows.") AND  t1._cost_center IN(".$_cost_center_id_rows.") GROUP BY t1._account_ledger ";
  $_default_vat_account_result = DB::select($_vat_account);

  $_service_charge =" SELECT  t1._account_ledger AS _account_ledger,t3._name as _l_name,SUM(t1._cr_amount-t1._dr_amount) AS _balance
            FROM accounts AS t1
            INNER JOIN account_groups as t2 ON t2.id=t1._account_group
            INNER JOIN account_ledgers as t3 ON t3.id=t1._account_ledger
            INNER JOIN branches as t4 ON t4.id = t1._branch_id
              WHERE  t1._status=1 AND t1._date  >= '".$_datex."'  AND t1._date <= '".$_datey."' 
              AND t1._account_ledger IN(".$_default_service_charge.") AND  t1._branch_id IN(".$_branch_ids_rows.") AND t1.organization_id IN(".$_organization_id_rows.") AND  t1._cost_center IN(".$_cost_center_id_rows.") GROUP BY t1._account_ledger ";
  $_default_service_charge_result = DB::select($_service_charge);

  $_other_charge =" SELECT  t1._account_ledger AS _account_ledger,t3._name as _l_name,SUM(t1._cr_amount-t1._dr_amount) AS _balance
            FROM accounts AS t1
            INNER JOIN account_groups as t2 ON t2.id=t1._account_group
            INNER JOIN account_ledgers as t3 ON t3.id=t1._account_ledger
            INNER JOIN branches as t4 ON t4.id = t1._branch_id
              WHERE  t1._status=1 AND t1._date  >= '".$_datex."'  AND t1._date <= '".$_datey."' 
              AND t1._account_ledger IN(".$_default_other_charge.") AND  t1._branch_id IN(".$_branch_ids_rows.") AND t1.organization_id IN(".$_organization_id_rows.") AND  t1._cost_center IN(".$_cost_center_id_rows.") GROUP BY t1._account_ledger ";
  $_default_other_charge_result = DB::select($_other_charge);
  
  $_delivery_charge =" SELECT  t1._account_ledger AS _account_ledger,t3._name as _l_name,SUM(t1._cr_amount-t1._dr_amount) AS _balance
            FROM accounts AS t1
            INNER JOIN account_groups as t2 ON t2.id=t1._account_group
            INNER JOIN account_ledgers as t3 ON t3.id=t1._account_ledger
            INNER JOIN branches as t4 ON t4.id = t1._branch_id
              WHERE  t1._status=1 AND t1._date  >= '".$_datex."'  AND t1._date <= '".$_datey."' 
              AND t1._account_ledger IN(".$_default_delivery_charge.") AND  t1._branch_id IN(".$_branch_ids_rows.") AND t1.organization_id IN(".$_organization_id_rows.") AND  t1._cost_center IN(".$_cost_center_id_rows.") GROUP BY t1._account_ledger ";
  $_default_delivery_charge_result = DB::select($_delivery_charge);


   $_cash_group_details = GeneralSettings::select("_cash_group")->first();
   $_cash_group = $_cash_group_details->_cash_group ?? 0;

   $all_cash_group =" SELECT  t2._name as _l_name,SUM(t1._dr_amount-t1._cr_amount) AS _balance
            FROM resturant_sales_accounts AS t1
             INNER JOIN resturant_sales as t5 ON t5.id=t1._no
            INNER JOIN account_groups as t2 ON t2.id=t1._account_group_id
            INNER JOIN account_ledgers as t3 ON t3.id=t1._ledger_id
            INNER JOIN branches as t4 ON t4.id = t1._branch_id
              WHERE  t1._status=1 AND t5._date  >= '".$_datex."'  AND t5._date <= '".$_datey."' 
              AND t1._account_group_id IN(".$_cash_group.") AND  t1._branch_id IN(".$_branch_ids_rows.") AND t1.organization_id IN(".$_organization_id_rows.") AND  t1._cost_center IN(".$_cost_center_id_rows.") GROUP BY t1._account_group_id ";
    $all_cash_group_result = DB::select($all_cash_group);
     $total_cashin_hand = $all_cash_group_result[0]->_balance ?? 0;


   $ledger_group_query =" SELECT  t3._name as _l_name,SUM(t1._dr_amount) as _balance
            FROM resturant_sales_accounts AS t1
            INNER JOIN resturant_sales as t5 ON t5.id=t1._no
            INNER JOIN account_groups as t2 ON t2.id=t1._account_group_id
            INNER JOIN account_ledgers as t3 ON t3.id=t1._ledger_id
            INNER JOIN branches as t4 ON t4.id = t1._branch_id
              WHERE  t1._status=1 AND t5._date  >= '".$_datex."'  AND t5._date <= '".$_datey."' 
              AND t1._account_group_id IN(".$_cash_group.") AND  t1._branch_id IN(".$_branch_ids_rows.") AND t1.organization_id IN(".$_organization_id_rows.") AND  t1._cost_center IN(".$_cost_center_id_rows.") 
              GROUP BY t1._ledger_id   ";
    $ledger_groupd_result = DB::select($ledger_group_query);



  return view('backend.account-report.report_day_wise_summary_res',compact('request','page_name','previous_filter','permited_branch','permited_costcenters','_default_sales_result','_default_discount_result','_default_vat_account_result','_default_service_charge_result','_default_other_charge_result','_default_delivery_charge_result','ledger_groupd_result','total_cashin_hand'));
  
}

public function dayWiseSummaryReportFilterReset(){
  Session::flash('day_wise_summary_report_filter');
  return redirect()->back();
}


public function itemSalesReportFilter(Request $request){
   $previous_filter= Session::get('filter_item_sales_report');
    $page_name = "Item Sales Report";
     
    $users = Auth::user();
    $permited_branch = permited_branch(explode(',',$users->branch_ids));
    $permited_costcenters = permited_costcenters(explode(',',$users->cost_center_ids));
    return view('backend.account-report.filter_item_sales_report',compact('request','page_name','previous_filter','permited_branch','permited_costcenters'));
}

public function itemSalesReport(Request $request){
    $this->validate($request, [
            '_datex' => 'required',
            '_datey' => 'required',
        ]);

        session()->put('filter_item_sales_report', $request->all());
        $previous_filter= Session::get('filter_item_sales_report');
        $page_name = "Item Sales Report";
         $users = Auth::user();
        $permited_branch = permited_branch(explode(',',$users->branch_ids));
        $permited_costcenters = permited_costcenters(explode(',',$users->cost_center_ids));
        $datas=[];
        $_datex =  change_date_format($request->_datex);
        $_datey=  change_date_format($request->_datey);
        $request_branchs = $request->_branch_id ?? [];
        $request_cost_centers = $request->_cost_center ?? [];

        $_branch_ids = filterableBranch($request_branchs,$permited_branch);
        $_cost_center_ids = filterableCostCenter($request_cost_centers,$permited_costcenters);
        $_branch_ids_rows = implode(',', $_branch_ids);
        $_cost_center_id_rows = implode(',', $_cost_center_ids);

  $ResturantFormSetting = ResturantFormSetting::first();
  $_default_sales = $ResturantFormSetting->_default_sales;
  $_default_discount = $ResturantFormSetting->_default_discount;
  $_default_vat_account = $ResturantFormSetting->_default_vat_account;
  $_default_service_charge = $ResturantFormSetting->_default_service_charge;
  $_default_other_charge = $ResturantFormSetting->_default_other_charge;
  $_default_delivery_charge = $ResturantFormSetting->_default_delivery_charge;


  $request_organizations = $request->organization_id ?? [];
  $permited_organizations = permited_organization(explode(',',$users->organization_ids));
  $_organization_ids = filterableOrganization($request_organizations,$permited_organizations);
  $_organization_id_rows = implode(',', $_organization_ids);



  $_sales =" SELECT  t1._account_ledger AS _account_ledger,t3._name as _l_name,SUM(t1._cr_amount-t1._dr_amount) AS _balance
            FROM accounts AS t1
            INNER JOIN account_groups as t2 ON t2.id=t1._account_group
            INNER JOIN account_ledgers as t3 ON t3.id=t1._account_ledger
            INNER JOIN branches as t4 ON t4.id = t1._branch_id
              WHERE  t1._status=1 AND t1._date  >= '".$_datex."'  AND t1._date <= '".$_datey."' 
              AND t1._account_ledger IN(".$_default_sales.") AND  t1._branch_id IN(".$_branch_ids_rows.") AND t1.organization_id IN(".$_organization_id_rows.") AND  t1._cost_center IN(".$_cost_center_id_rows.") GROUP BY t1._account_ledger ";
  $_default_sales_result = DB::select($_sales);

  $_discount =" SELECT  t1._account_ledger AS _account_ledger,t3._name as _l_name,SUM(t1._cr_amount-t1._dr_amount) AS _balance
            FROM accounts AS t1
            INNER JOIN account_groups as t2 ON t2.id=t1._account_group
            INNER JOIN account_ledgers as t3 ON t3.id=t1._account_ledger
            INNER JOIN branches as t4 ON t4.id = t1._branch_id
              WHERE  t1._status=1 AND t1._date  >= '".$_datex."'  AND t1._date <= '".$_datey."' 
              AND t1._account_ledger IN(".$_default_discount.") AND  t1._branch_id IN(".$_branch_ids_rows.") AND t1.organization_id IN(".$_organization_id_rows.") AND  t1._cost_center IN(".$_cost_center_id_rows.") GROUP BY t1._account_ledger ";
  $_default_discount_result = DB::select($_discount);
  
  $_vat_account =" SELECT  t1._account_ledger AS _account_ledger,t3._name as _l_name,SUM(t1._cr_amount-t1._dr_amount) AS _balance
            FROM accounts AS t1
            INNER JOIN account_groups as t2 ON t2.id=t1._account_group
            INNER JOIN account_ledgers as t3 ON t3.id=t1._account_ledger
            INNER JOIN branches as t4 ON t4.id = t1._branch_id
              WHERE  t1._status=1 AND t1._date  >= '".$_datex."'  AND t1._date <= '".$_datey."' 
              AND t1._account_ledger IN(".$_default_vat_account.") AND  t1._branch_id IN(".$_branch_ids_rows.") AND t1.organization_id IN(".$_organization_id_rows.") AND  t1._cost_center IN(".$_cost_center_id_rows.") GROUP BY t1._account_ledger ";
  $_default_vat_account_result = DB::select($_vat_account);

  $_service_charge =" SELECT  t1._account_ledger AS _account_ledger,t3._name as _l_name,SUM(t1._cr_amount-t1._dr_amount) AS _balance
            FROM accounts AS t1
            INNER JOIN account_groups as t2 ON t2.id=t1._account_group
            INNER JOIN account_ledgers as t3 ON t3.id=t1._account_ledger
            INNER JOIN branches as t4 ON t4.id = t1._branch_id
              WHERE  t1._status=1 AND t1._date  >= '".$_datex."'  AND t1._date <= '".$_datey."' 
              AND t1._account_ledger IN(".$_default_service_charge.") AND  t1._branch_id IN(".$_branch_ids_rows.") AND t1.organization_id IN(".$_organization_id_rows.") AND  t1._cost_center IN(".$_cost_center_id_rows.") GROUP BY t1._account_ledger ";
  $_default_service_charge_result = DB::select($_service_charge);

  $_other_charge =" SELECT  t1._account_ledger AS _account_ledger,t3._name as _l_name,SUM(t1._cr_amount-t1._dr_amount) AS _balance
            FROM accounts AS t1
            INNER JOIN account_groups as t2 ON t2.id=t1._account_group
            INNER JOIN account_ledgers as t3 ON t3.id=t1._account_ledger
            INNER JOIN branches as t4 ON t4.id = t1._branch_id
              WHERE  t1._status=1 AND t1._date  >= '".$_datex."'  AND t1._date <= '".$_datey."' 
              AND t1._account_ledger IN(".$_default_other_charge.") AND  t1._branch_id IN(".$_branch_ids_rows.") AND t1.organization_id IN(".$_organization_id_rows.") AND  t1._cost_center IN(".$_cost_center_id_rows.") GROUP BY t1._account_ledger ";
  $_default_other_charge_result = DB::select($_other_charge);
  
  $_delivery_charge =" SELECT  t1._account_ledger AS _account_ledger,t3._name as _l_name,SUM(t1._cr_amount-t1._dr_amount) AS _balance
            FROM accounts AS t1
            INNER JOIN account_groups as t2 ON t2.id=t1._account_group
            INNER JOIN account_ledgers as t3 ON t3.id=t1._account_ledger
            INNER JOIN branches as t4 ON t4.id = t1._branch_id
              WHERE  t1._status=1 AND t1._date  >= '".$_datex."'  AND t1._date <= '".$_datey."' 
              AND t1._account_ledger IN(".$_default_delivery_charge.") AND  t1._branch_id IN(".$_branch_ids_rows.") AND t1.organization_id IN(".$_organization_id_rows.") AND  t1._cost_center IN(".$_cost_center_id_rows.") GROUP BY t1._account_ledger ";
  $_default_delivery_charge_result = DB::select($_delivery_charge);


   $_cash_group_details = GeneralSettings::select("_cash_group")->first();
   $_cash_group = $_cash_group_details->_cash_group ?? 0;

   $all_cash_group =" SELECT  t2._name as _l_name,SUM(t1._dr_amount-t1._cr_amount) AS _balance
            FROM resturant_sales_accounts AS t1
             INNER JOIN resturant_sales as t5 ON t5.id=t1._no
            INNER JOIN account_groups as t2 ON t2.id=t1._account_group_id
            INNER JOIN account_ledgers as t3 ON t3.id=t1._ledger_id
            INNER JOIN branches as t4 ON t4.id = t1._branch_id
              WHERE  t1._status=1 AND t5._date  >= '".$_datex."'  AND t5._date <= '".$_datey."' 
              AND t1._account_group_id IN(".$_cash_group.") AND  t1._branch_id IN(".$_branch_ids_rows.") AND t1.organization_id IN(".$_organization_id_rows.") AND  t1._cost_center IN(".$_cost_center_id_rows.") GROUP BY t1._account_group_id ";
    $all_cash_group_result = DB::select($all_cash_group);
     $total_cashin_hand = $all_cash_group_result[0]->_balance ?? 0;


   $ledger_group_query =" SELECT  t3._name as _l_name,SUM(t1._dr_amount) as _balance
            FROM resturant_sales_accounts AS t1
            INNER JOIN resturant_sales as t5 ON t5.id=t1._no
            INNER JOIN account_groups as t2 ON t2.id=t1._account_group_id
            INNER JOIN account_ledgers as t3 ON t3.id=t1._ledger_id
            INNER JOIN branches as t4 ON t4.id = t1._branch_id
              WHERE  t1._status=1 AND t5._date  >= '".$_datex."'  AND t5._date <= '".$_datey."' 
              AND t1._account_group_id IN(".$_cash_group.") AND  t1._branch_id IN(".$_branch_ids_rows.") AND t1.organization_id IN(".$_organization_id_rows.") AND  t1._cost_center IN(".$_cost_center_id_rows.") 
              GROUP BY t1._ledger_id   ";
    $ledger_groupd_result = DB::select($ledger_group_query);



    //Restaurant Sales Item
     $item_sales_query =" SELECT t3._item AS _item_name,SUM(t2._qty) AS _total_qty, SUM(t2._qty*t2._sales_rate) AS _total_value FROM resturant_sales AS t1 
INNER JOIN resturant_details AS t2 ON t1.id=t2._no
INNER JOIN inventories AS t3 ON t2._item_id=t3.id
WHERE 1=1 AND t2._status=1 AND t1._date  >= '".$_datex."'  AND t1._date <= '".$_datey."' 
GROUP BY t2._item_id
ORDER BY t3._item ASC";
    $item_sales_res = DB::select($item_sales_query);



  return view('backend.account-report.report_item_sales_report',compact('request','page_name','previous_filter','permited_branch','permited_costcenters','_default_sales_result','_default_discount_result','_default_vat_account_result','_default_service_charge_result','_default_other_charge_result','_default_delivery_charge_result','ledger_groupd_result','total_cashin_hand','item_sales_res'));
}

public function itemSalesReportFilterReset(){
  Session::flash('filter_item_sales_report');
  return redirect()->back();
}



public function detailItemSalesReportFilter(Request $request){
   $previous_filter= Session::get('filter_deail_item_sales_report');
    $page_name = "Item Details Sales Report";
     
    $users = Auth::user();
    $permited_branch = permited_branch(explode(',',$users->branch_ids));
    $permited_costcenters = permited_costcenters(explode(',',$users->cost_center_ids));
    return view('backend.account-report.filter_deail_item_sales_report',compact('request','page_name','previous_filter','permited_branch','permited_costcenters'));
}

public function detailItemSalesReportFilterReset(){
  Session::flash('filter_deail_item_sales_report');
  return redirect()->back();
}

public function detailItemSalesReport(Request $request){
    $this->validate($request, [
            '_datex' => 'required',
            '_datey' => 'required',
        ]);

        session()->put('filter_deail_item_sales_report', $request->all());
        $previous_filter= Session::get('filter_deail_item_sales_report');
        $page_name = "Item Sales Report";
         $users = Auth::user();
        $permited_branch = permited_branch(explode(',',$users->branch_ids));
        $permited_costcenters = permited_costcenters(explode(',',$users->cost_center_ids));
        $datas=[];
        $_datex =  change_date_format($request->_datex);
        $_datey=  change_date_format($request->_datey);
        $request_branchs = $request->_branch_id ?? [];
        $request_cost_centers = $request->_cost_center ?? [];

        $_branch_ids = filterableBranch($request_branchs,$permited_branch);
        $_cost_center_ids = filterableCostCenter($request_cost_centers,$permited_costcenters);
        $_branch_ids_rows = implode(',', $_branch_ids);
        $_cost_center_id_rows = implode(',', $_cost_center_ids);


        

   $item_sales_query =" SELECT SUM(t2._qty) AS _total_qty, SUM(t2._value) AS _total_value,t3._unit_id FROM resturant_sales AS t1 
INNER JOIN resturant_details AS t2 ON t1.id=t2._no
INNER JOIN inventories AS t3 ON t2._item_id=t3.id

WHERE 1=1 AND t2._status=1 AND t1._date  >= '".$_datex."'  AND t1._date <= '".$_datey."' ";
     $item_sales_res = DB::select($item_sales_query);

     $total_qty = $item_sales_res[0]->_total_qty ?? 0;
     $_total_value = $item_sales_res[0]->_total_value ?? 0;

//Sales By Group

$item_group_query =" SELECT t3._category_id, t4._name as _name,
      SUM(t2._qty) AS _total_qty, SUM(t2._value) AS _value ,t3._unit_id
      FROM resturant_sales AS t1 
      INNER JOIN resturant_details AS t2 ON t1.id=t2._no
      INNER JOIN inventories AS t3 ON t2._item_id=t3.id
      INNER JOIN item_categories AS t4 ON t4.id=t3._category_id
      WHERE 1=1 AND t2._status=1 AND t1._date  >= '".$_datex."'  AND t1._date <= '".$_datey."'
      GROUP BY t3._category_id
      ORDER BY t4._name ASC ";
  $item_group_res = DB::select($item_group_query);

$item_detail_query =" SELECT t3._category_id, t4._name as _cat_name, t3._item as _name,t2._qty AS _qty,
t2._sales_rate AS _sales_rate, (t2._qty*t2._sales_rate) AS _value ,t3._unit_id
FROM resturant_sales AS t1 
INNER JOIN resturant_details AS t2 ON t1.id=t2._no
INNER JOIN inventories AS t3 ON t2._item_id=t3.id
INNER JOIN item_categories AS t4 ON t4.id=t3._category_id
WHERE 1=1 AND t2._status=1 AND t1._date  >= '".$_datex."'  AND t1._date <= '".$_datey."'
ORDER BY t3._item ASC ";
 $item_detail_res = DB::select($item_detail_query);



  return view('backend.account-report.report_detail_item_sales_report',compact('request','page_name','previous_filter','permited_branch','permited_costcenters','total_qty','_total_value','item_group_res','item_detail_res'));
}

//Cash book filter
    public function cashBook(Request $request){
        $previous_filter= Session::get('filter_cash_book');
        $page_name = "Cash Book";
        $account_ledgers = \DB::select( " SELECT t1.id,t1._account_head_id,t1._account_group_id,t1._name FROM account_ledgers AS t1 WHERE t1._account_group_id=(SELECT _cash_group FROM general_settings ) " );
        $users = Auth::user();
        $permited_branch = permited_branch(explode(',',$users->branch_ids));
        $permited_costcenters = permited_costcenters(explode(',',$users->cost_center_ids));
        return view('backend.account-report.filter_cash_book',compact('request','page_name','account_ledgers','previous_filter','permited_branch','permited_costcenters'));
    }

    public function cashBookReport(Request $request){
      $this->validate($request, [
            '_datex' => 'required',
            '_datey' => 'required',
            '_account_ledger_id' => 'required',
        ]);

        session()->put('filter_cash_book', $request->all());
        $previous_filter= Session::get('filter_cash_book');
        $page_name = "Cash Book";
         $users = Auth::user();
        $permited_branch = permited_branch(explode(',',$users->branch_ids));
        $permited_costcenters = permited_costcenters(explode(',',$users->cost_center_ids));
        $datas=[];
        $_datex =  change_date_format($request->_datex);
        $_datey=  change_date_format($request->_datey);
        $request_branchs = $request->_branch_id ?? [];
        $request_cost_centers = $request->_cost_center ?? [];

        $_branch_ids = filterableBranch($request_branchs,$permited_branch);
        $_cost_center_ids = filterableCostCenter($request_cost_centers,$permited_costcenters);
        $ledger_ids = $request->_account_ledger_id ?? [];

        $ledger_id_rows = implode(',', $ledger_ids);
        $_branch_ids_rows = implode(',', $_branch_ids);
        $_cost_center_id_rows = implode(',', $_cost_center_ids);

        $request_organizations = $request->organization_id ?? [];
        $permited_organizations = permited_organization(explode(',',$users->organization_ids));
        $_organization_ids = filterableOrganization($request_organizations,$permited_organizations);
        $_organization_id_rows = implode(',', $_organization_ids);
        



        $opening_query_sting = "

        SELECT l1._type as _type,l1._date as _date,l1._account_ledger as _account_ledger,l1._l_name as _l_name, l1._short_narration as _short_narration,l1._reference,l1._table_name,l1._id,l1._dr_amount,l1._cr_amount,l1._serial FROM (
         SELECT 'A. Opening Cash' as _type,null as _date,  t1._account_ledger AS _account_ledger,t3._name as _l_name,null as _short_narration,null as _reference,null as _table_name, null as _id,SUM(t1._dr_amount-t1._cr_amount) as _dr_amount,0 as _cr_amount,0 as _serial
            FROM accounts as t1
            INNER JOIN account_ledgers as t3 ON t3.id=t1._account_ledger
               WHERE t1._status=1 AND t1._date < '".$_datex."' AND t1._account_ledger IN(".$ledger_id_rows.") AND t1.organization_id IN(".$_organization_id_rows.")
               AND  t1._branch_id IN(".$_branch_ids_rows.") AND  t1._cost_center IN(".$_cost_center_id_rows.")
                 GROUP BY t1._account_ledger  
UNION ALL
SELECT 'B. Receipt & Payment' as _type,t1._date, t1._account_ledger AS _account_ledger,t3._name as _l_name,t1._short_narration as _short_narration,t1._reference as _reference,t1._table_name, t1._ref_master_id as _id,t1._dr_amount as _dr_amount,t1._cr_amount as _cr_amount,t1._serial
            FROM accounts as t1
             INNER JOIN account_ledgers as t3 ON t3.id=t1._account_ledger
              WHERE  t1._status=1 AND t1._date  >= '".$_datex."'  AND t1._date <= '".$_datey."' 
               AND t1._account_ledger IN(".$ledger_id_rows.") AND t1.organization_id IN(".$_organization_id_rows.")
               AND  t1._branch_id IN(".$_branch_ids_rows.") AND  t1._cost_center IN(".$_cost_center_id_rows.") 

    UNION ALL
    SELECT 'C. Closing Cash' as _type,null as _date,  t1._account_ledger AS _account_ledger,t3._name as _l_name,null as _short_narration,null as _reference,null as _table_name, null as _id,SUM(t1._dr_amount-t1._cr_amount) as _dr_amount,0 as _cr_amount,0 as _serial
            FROM accounts as t1
            INNER JOIN account_ledgers as t3 ON t3.id=t1._account_ledger
               WHERE t1._status=1 AND t1._date <= '".$_datey."'  AND t1._account_ledger IN(".$ledger_id_rows.") AND t1.organization_id IN(".$_organization_id_rows.")
               AND  t1._branch_id IN(".$_branch_ids_rows.") AND  t1._cost_center IN(".$_cost_center_id_rows.")
                 GROUP BY t1._account_ledger ) AS l1 ORDER By l1._type,l1._date ASC ";

         $_opening_balance =  DB::select($opening_query_sting);
         $_result_group =array();
         foreach ($_opening_balance as $value) {
           $_result_group[$value->_type][]=$value;
         }
        // return $_result_group;
        return view('backend.account-report.report_cash_book',compact('request','page_name','previous_filter','permited_branch','permited_costcenters','_result_group'));

    }

public function cashBookFilterReset(){
  Session::flash('filter_cash_book');
  return redirect()->back();
}


//Bank book filter
    public function bankBook(Request $request){
        $previous_filter= Session::get('filter_bank_book');
        $page_name = "Bank Book";
        $account_ledgers = \DB::select( " SELECT t1.id,t1._account_head_id,t1._account_group_id,t1._name FROM account_ledgers AS t1 WHERE t1._account_group_id=(SELECT _bank_group FROM general_settings ) " );
        $users = Auth::user();
        $permited_branch = permited_branch(explode(',',$users->branch_ids));
        $permited_costcenters = permited_costcenters(explode(',',$users->cost_center_ids));
        return view('backend.account-report.filter_bank_book',compact('request','page_name','account_ledgers','previous_filter','permited_branch','permited_costcenters'));
    }

    public function bankBookReport(Request $request){
      $this->validate($request, [
            '_datex' => 'required',
            '_datey' => 'required',
            '_account_ledger_id' => 'required',
        ]);

        session()->put('filter_bank_book', $request->all());
        $previous_filter= Session::get('filter_bank_book');
        $page_name = "Bank Book";
         $users = Auth::user();
        $permited_branch = permited_branch(explode(',',$users->branch_ids));
        $permited_costcenters = permited_costcenters(explode(',',$users->cost_center_ids));
        $datas=[];
        $_datex =  change_date_format($request->_datex);
        $_datey=  change_date_format($request->_datey);
        $request_branchs = $request->_branch_id ?? [];
        $request_cost_centers = $request->_cost_center ?? [];

        $_branch_ids = filterableBranch($request_branchs,$permited_branch);
        $_cost_center_ids = filterableCostCenter($request_cost_centers,$permited_costcenters);
        $ledger_ids = $request->_account_ledger_id ?? [];

        $ledger_id_rows = implode(',', $ledger_ids);
        $_branch_ids_rows = implode(',', $_branch_ids);
        $_cost_center_id_rows = implode(',', $_cost_center_ids);


        $request_organizations = $request->organization_id ?? [];
        $permited_organizations = permited_organization(explode(',',$users->organization_ids));
        $_organization_ids = filterableOrganization($request_organizations,$permited_organizations);
        $_organization_id_rows = implode(',', $_organization_ids);
        





        $opening_query_sting = " SELECT 'A. Opening Bank' as _type,null as _date,  t1._account_ledger AS _account_ledger,t3._name as _l_name,null as _short_narration,null as _reference,null as _table_name, null as _id,SUM(t1._dr_amount-t1._cr_amount) as _dr_amount,0 as _cr_amount,0 as _serial
            FROM accounts as t1
            INNER JOIN account_ledgers as t3 ON t3.id=t1._account_ledger
               WHERE t1._status=1 AND t1._date < '".$_datex."' AND t1._account_ledger IN(".$ledger_id_rows.") AND t1.organization_id IN(".$_organization_id_rows.")
               AND  t1._branch_id IN(".$_branch_ids_rows.") AND  t1._cost_center IN(".$_cost_center_id_rows.")
                 GROUP BY t1._account_ledger 
UNION ALL
SELECT 'B. Receipt & Payment' as _type,t1._date, t1._account_ledger AS _account_ledger,t3._name as _l_name,t1._short_narration as _short_narration,t1._reference as _reference,t1._table_name, t1._ref_master_id as _id,t1._dr_amount as _dr_amount,t1._cr_amount as _cr_amount,t1._serial
            FROM accounts as t1
             INNER JOIN account_ledgers as t3 ON t3.id=t1._account_ledger
              WHERE  t1._status=1 AND t1._date  >= '".$_datex."'  AND t1._date <= '".$_datey."' 
               AND t1._account_ledger IN(".$ledger_id_rows.") AND t1.organization_id IN(".$_organization_id_rows.")
               AND  t1._branch_id IN(".$_branch_ids_rows.") AND  t1._cost_center IN(".$_cost_center_id_rows.")

    UNION ALL
    SELECT 'C. Closing Bank' as _type,null as _date,  t1._account_ledger AS _account_ledger,t3._name as _l_name,null as _short_narration,null as _reference,null as _table_name, null as _id,SUM(t1._dr_amount-t1._cr_amount) as _dr_amount,0 as _cr_amount,0 as _serial
            FROM accounts as t1
            INNER JOIN account_ledgers as t3 ON t3.id=t1._account_ledger
               WHERE t1._status=1 AND t1._date <= '".$_datey."'  AND t1._account_ledger IN(".$ledger_id_rows.") AND t1.organization_id IN(".$_organization_id_rows.")
               AND  t1._branch_id IN(".$_branch_ids_rows.") AND  t1._cost_center IN(".$_cost_center_id_rows.")
                 GROUP BY t1._account_ledger ";

         $_opening_balance =  DB::select($opening_query_sting);
         $_result_group =array();
         foreach ($_opening_balance as $value) {
           $_result_group[$value->_type][]=$value;
         }
        return view('backend.account-report.report_bank_book',compact('request','page_name','previous_filter','permited_branch','permited_costcenters','_result_group'));

    }

public function bankBookFilterReset(){
  Session::flash('filter_bank_book');
  return redirect()->back();
}



public function dateWiseInvoice(Request $request){
        $users = Auth::user();
        $previous_filter= Session::get('filter_date_wise_invoice_print');
        $page_name = "Date Wise Invoice Print";
        $sales_mans = DB::select(" SELECT id,_name FROM account_ledgers WHERE id IN(SELECT DISTINCT t1._sales_man_id FROM sales AS t1  WHERE t1._sales_man_id !=0) ");
        $delivery_mans = DB::select(" SELECT id,_name FROM account_ledgers WHERE id IN(SELECT DISTINCT t1._delivery_man_id FROM sales AS t1  WHERE t1._delivery_man_id !=0) ");
        $users_info = DB::select(" SELECT DISTINCT _user_name as name FROM sales  ");
        $permited_branch = permited_branch(explode(',',$users->branch_ids));
        $permited_costcenters = permited_costcenters(explode(',',$users->cost_center_ids));
        return view('backend.account-report.filter_date_wise_invoice_print',compact('request','page_name','previous_filter','permited_branch','permited_costcenters','sales_mans','delivery_mans','users_info'));
    }





     public function dateWiseInvoiceReport(Request $request){
   //   return $request->all();
      $this->validate($request, [
            '_datex' => 'required',
            '_datey' => 'required',
            '_branch_id' => 'required',
            '_cost_center' => 'required',
        ]);
        session()->put('filter_date_wise_invoice_print', $request->all());
        $previous_filter= Session::get('filter_date_wise_invoice_print');
        $page_name = "Date Wise Invoice Print";
         $users = Auth::user();
        $permited_branch = permited_branch(explode(',',$users->branch_ids));
        $permited_costcenters = permited_costcenters(explode(',',$users->cost_center_ids));
        $datas=[];
        $_datex =  change_date_format($request->_datex);
        $_datey=  change_date_format($request->_datey);
        $request_branchs = $request->_branch_id ?? [];
        $request_cost_centers = $request->_cost_center ?? [];
        $_delivery_man_ids = $request->_delivery_man_id ?? [];
        $user_names = $request->_name ?? [];
        $organization_ids = $request->organization_id ?? [];

        
      $_branch_ids = filterableBranch($request_branchs,$permited_branch);
      $_cost_center_ids = filterableCostCenter($request_cost_centers,$permited_costcenters);
       $datas = Sales::with(['_master_branch','_master_details','s_account','_ledger','_delivery_man','_sales_man'])->where('_status',1);
        $datas = $datas->whereDate('_date','>=', $_datex);
        $datas = $datas->whereDate('_date','<=', $_datey);
        if($request->has('organization_id')){
                    $datas = $datas->whereIn('organization_id',$organization_ids);
        } 

        if($request->has('_cost_center_id')){
                    $datas = $datas->whereIn('_cost_center_id',$_cost_center_ids);
        }       
        if($request->has('_branch_id')){
                    $datas = $datas->whereIn('_branch_id',$_branch_ids);
        }
        if($request->has('_delivery_man_id')){
                    $datas = $datas->whereIn('_delivery_man_id',$_delivery_man_ids);
        }

        if($request->has('_sales_man_id')){
            $datas = $datas->whereIn('_sales_man_id',$request->_sales_man_id);
        }
        if($request->has('_name')){
            $datas = $datas->whereIn('_user_name',$user_names);
        }
        $datas = $datas->orderBy('_date','ASC')
                                ->get();
         $form_settings = SalesFormSetting::first();
      

return view('backend.account-report.report_date_wise_invoice_print',compact('request','page_name','previous_filter','permited_branch','permited_costcenters','datas','form_settings'));


    }


public function dateWiseInvoiceFilterReset(){
  Session::flash('filter_date_wise_invoice_print');
  return redirect()->back();
}


public function dateWiseRestaurantInvoice(Request $request){
        $users = Auth::user();
        $previous_filter= Session::get('filter_date_wise_invoice_print');
        $page_name = "Date Wise Invoice Print";
        $sales_mans = DB::select(" SELECT id,_name FROM account_ledgers WHERE id IN(SELECT DISTINCT t1._sales_man_id FROM resturant_sales AS t1  WHERE t1._sales_man_id !=0) ");
        $delivery_mans = DB::select(" SELECT id,_name FROM account_ledgers WHERE id IN(SELECT DISTINCT t1._delivery_man_id FROM resturant_sales AS t1  WHERE t1._delivery_man_id !=0) ");
        $users_info = DB::select(" SELECT DISTINCT _user_name as name FROM resturant_sales  ");
        $permited_branch = permited_branch(explode(',',$users->branch_ids));
        $permited_costcenters = permited_costcenters(explode(',',$users->cost_center_ids));
        return view('backend.account-report.filter_date_wise_restaurant_invoice_print',compact('request','page_name','previous_filter','permited_branch','permited_costcenters','sales_mans','delivery_mans','users_info'));
    }



    

     public function dateWiseRestaurantInvoiceReport(Request $request){
   //   return $request->all();
      $this->validate($request, [
            '_datex' => 'required',
            '_datey' => 'required',
            '_branch_id' => 'required',
            '_cost_center' => 'required',
        ]);
        session()->put('filter_date_wise_restaurnt_invoice_print', $request->all());
        $previous_filter= Session::get('filter_date_wise_restaurnt_invoice_print');
        $page_name = "Date Wise Invoice Print";
         $users = Auth::user();
        $permited_branch = permited_branch(explode(',',$users->branch_ids));
        $permited_costcenters = permited_costcenters(explode(',',$users->cost_center_ids));
        $datas=[];
        $_datex =  change_date_format($request->_datex);
        $_datey=  change_date_format($request->_datey);
        $request_branchs = $request->_branch_id ?? [];
        $request_cost_centers = $request->_cost_center ?? [];
        $_delivery_man_ids = $request->_delivery_man_id ?? [];
        $user_names = $request->_name ?? [];
        
      $_branch_ids = filterableBranch($request_branchs,$permited_branch);
      $_cost_center_ids = filterableCostCenter($request_cost_centers,$permited_costcenters);
       $datas = ResturantSales::with(['_master_branch','_master_details','s_account','_ledger','_delivery_man','_sales_man'])->where('_status',1);
        $datas = $datas->whereDate('_date','>=', $_datex);
        $datas = $datas->whereDate('_date','<=', $_datey);
        if($request->has('_cost_center_id')){
                    $datas = $datas->whereIn('_cost_center_id',$_cost_center_ids);
        }       
        if($request->has('_branch_id')){
                    $datas = $datas->whereIn('_branch_id',$_branch_ids);
        }
        if($request->has('_delivery_man_id')){
                    $datas = $datas->whereIn('_delivery_man_id',$_delivery_man_ids);
        }

        if($request->has('_sales_man_id')){
            $datas = $datas->whereIn('_sales_man_id',$request->_sales_man_id);
        }
        if($request->has('_name')){
            $datas = $datas->whereIn('_user_name',$user_names);
        }
        $datas = $datas->orderBy('_date','ASC')
                                ->get();
         $form_settings = ResturantFormSetting::first();
      

return view('backend.account-report.report_date_wise_restaurant_invoice_print',compact('request','page_name','previous_filter','permited_branch','permited_costcenters','datas','form_settings'));


    }


public function dateWiseRestaurantInvoiceFilterReset(){
  Session::flash('filter_date_wise_restaurant_invoice_print');
  return redirect()->back();
}



   //User Receipt Payment
    public function userReceiptPayment(Request $request){
        $previous_filter= Session::get('filter_user_receipt_payment');
        $page_name = "User Wise Receipt & Payment";
        $_defalut_groups = \DB::select("SELECT _bank_group,_cash_group FROM general_settings");
        $_defalut_groups_array = array();
        foreach ($_defalut_groups as $value) {
          array_push($_defalut_groups_array ,intval($value->_bank_group));
          array_push($_defalut_groups_array ,intval($value->_cash_group));
        }
        $_defalut_groups_ids=implode(',', $_defalut_groups_array);

        $account_ledgers = \DB::select( " SELECT t1.id,t1._account_head_id,t1._account_group_id,t1._name FROM account_ledgers AS t1 WHERE t1._account_group_id IN(".$_defalut_groups_ids." ) " );
        $users = Auth::user();
        if($users->user_type=="admin"){
          $users_info =  DB::select(" SELECT DISTINCT _name as name FROM accounts  ");
        }else{
          $users_info =[];
        }
        $permited_branch = permited_branch(explode(',',$users->branch_ids));
        $permited_costcenters = permited_costcenters(explode(',',$users->cost_center_ids));
        return view('backend.account-report.filter_user_receipt_payment',compact('request','page_name','account_ledgers','previous_filter','permited_branch','permited_costcenters','users_info'));
    }



    public function userReceiptPaymentReport(Request $request){
      $this->validate($request, [
            '_datex' => 'required',
            '_datey' => 'required',
            '_account_ledger_id' => 'required',
            '_name' => 'required',
        ]);

        session()->put('filter_user_receipt_payment', $request->all());
        $previous_filter= Session::get('filter_user_receipt_payment');
        $page_name = "User Wise Receipt & Payment";
         $users = Auth::user();
        $permited_branch = permited_branch(explode(',',$users->branch_ids));
        $permited_costcenters = permited_costcenters(explode(',',$users->cost_center_ids));
        $datas=[];
        $_datex =  change_date_format($request->_datex);
        $_datey=  change_date_format($request->_datey);
        $request_branchs = $request->_branch_id ?? [];
        $request_cost_centers = $request->_cost_center ?? [];
        $user_names = $request->_name ?? [];
         $_user_name_codes='';
         if(sizeof($user_names) > 0){
          foreach ($user_names as $value) {
            $_user_name_codes .="'".$value."',";
          }
          $_user_name_codes = rtrim($_user_name_codes,",");
        }

        $_branch_ids = filterableBranch($request_branchs,$permited_branch);
        $_cost_center_ids = filterableCostCenter($request_cost_centers,$permited_costcenters);
        $ledger_ids = $request->_account_ledger_id ?? [];

        $ledger_id_rows = implode(',', $ledger_ids);
        $_branch_ids_rows = implode(',', $_branch_ids);
        $_cost_center_id_rows = implode(',', $_cost_center_ids);

         $request_organizations = $request->organization_id ?? [];
        $permited_organizations = permited_organization(explode(',',$users->organization_ids));
        $_organization_ids = filterableOrganization($request_organizations,$permited_organizations);
        $_organization_id_rows = implode(',', $_organization_ids);
        //AND t1.organization_id IN(".$_organization_id_rows.")


        $opening_query_sting = " 
SELECT l1._type,l1._date,l1._account_ledger,l1._l_name,l1._short_narration,l1._reference,l1._table_name,l1._id,l1._dr_amount,l1._cr_amount,l1._serial,l1._name FROM(
        SELECT 'A. Opening' as _type,'".$_datex."' as _date,  t1._account_ledger AS _account_ledger,t3._name as _l_name,null as _short_narration,null as _reference,null as _table_name, null as _id,SUM(t1._dr_amount-t1._cr_amount) as _dr_amount,0 as _cr_amount,0 as _serial,t1._name
            FROM accounts as t1
            INNER JOIN account_ledgers as t3 ON t3.id=t1._account_ledger
               WHERE t1._status=1 AND t1._date < '".$_datex."' AND t1._account_ledger IN(".$ledger_id_rows.") AND t1.organization_id IN(".$_organization_id_rows.")
               AND  t1._branch_id IN(".$_branch_ids_rows.") AND  t1._cost_center IN(".$_cost_center_id_rows.") AND t1._name IN(".$_user_name_codes.")
                 GROUP BY t1._account_ledger,t1._name 
UNION ALL
SELECT 'B. Receipt & Payment' as _type,t1._date, t1._account_ledger AS _account_ledger,t3._name as _l_name,t1._short_narration as _short_narration,t1._reference as _reference,t1._table_name, t1._ref_master_id as _id,t1._dr_amount as _dr_amount,t1._cr_amount as _cr_amount,t1._serial,t1._name
            FROM accounts as t1
             INNER JOIN account_ledgers as t3 ON t3.id=t1._account_ledger
              WHERE  t1._status=1 AND t1._date  >= '".$_datex."'  AND t1._date <= '".$_datey."' 
               AND t1._account_ledger IN(".$ledger_id_rows.") AND t1.organization_id IN(".$_organization_id_rows.")
               AND  t1._branch_id IN(".$_branch_ids_rows.") AND  t1._cost_center IN(".$_cost_center_id_rows.")
               AND t1._name IN(".$_user_name_codes.")

    UNION ALL
    SELECT 'C. Closing' as _type,null as _date,  t1._account_ledger AS _account_ledger,t3._name as _l_name,null as _short_narration,null as _reference,null as _table_name, null as _id,SUM(t1._dr_amount-t1._cr_amount) as _dr_amount,0 as _cr_amount,0 as _serial,t1._name
            FROM accounts as t1
            INNER JOIN account_ledgers as t3 ON t3.id=t1._account_ledger
               WHERE t1._status=1 AND t1._date <= '".$_datey."'  AND t1._account_ledger IN(".$ledger_id_rows.") AND t1.organization_id IN(".$_organization_id_rows.")
               AND  t1._branch_id IN(".$_branch_ids_rows.") AND  t1._cost_center IN(".$_cost_center_id_rows.")
               AND t1._name IN(".$_user_name_codes.")
                 GROUP BY t1._account_ledger,t1._name 

                 ) as l1 ORDER BY l1._type,l1._date,l1._id ASC ";

         $_opening_balance =  DB::select($opening_query_sting);
         $_result_group =array();
         foreach ($_opening_balance as $value) {
           $_result_group[$value->_type."-".$value->_name][]=$value;
         }
        return view('backend.account-report.report_user_receipt_payment',compact('request','page_name','previous_filter','permited_branch','permited_costcenters','_result_group'));

    }

public function userReceiptPaymentFilterReset(){
  Session::flash('filter_user_receipt_payment');
  return redirect()->back();
}


//Bank book filter
    public function receiptPayment(Request $request){
        $previous_filter= Session::get('filter_receipt_payment');
        $page_name = "Receipt Payment";
        $_defalut_groups = \DB::select("SELECT _bank_group,_cash_group FROM general_settings");
        $_defalut_groups_array = array();
        foreach ($_defalut_groups as $value) {
          array_push($_defalut_groups_array ,intval($value->_bank_group));
          array_push($_defalut_groups_array ,intval($value->_cash_group));
        }
        $_defalut_groups_ids=implode(',', $_defalut_groups_array);

        $account_ledgers = \DB::select( " SELECT t1.id,t1._account_head_id,t1._account_group_id,t1._name FROM account_ledgers AS t1 WHERE t1._account_group_id IN(".$_defalut_groups_ids." ) " );
        $users = Auth::user();
        $permited_branch = permited_branch(explode(',',$users->branch_ids));
        $permited_costcenters = permited_costcenters(explode(',',$users->cost_center_ids));
        return view('backend.account-report.filter_receipt_payment',compact('request','page_name','account_ledgers','previous_filter','permited_branch','permited_costcenters'));
    }

    public function receiptPaymentReport(Request $request){
      $this->validate($request, [
            '_datex' => 'required',
            '_datey' => 'required',
            '_account_ledger_id' => 'required',
        ]);

        session()->put('filter_receipt_payment', $request->all());
        $previous_filter= Session::get('filter_receipt_payment');
        $page_name = "Receipt Payment";
         $users = Auth::user();
        $permited_branch = permited_branch(explode(',',$users->branch_ids));
        $permited_costcenters = permited_costcenters(explode(',',$users->cost_center_ids));
        $datas=[];
        $_datex =  change_date_format($request->_datex);
        $_datey=  change_date_format($request->_datey);
        $request_branchs = $request->_branch_id ?? [];
        $request_cost_centers = $request->_cost_center ?? [];

        $_branch_ids = filterableBranch($request_branchs,$permited_branch);
        $_cost_center_ids = filterableCostCenter($request_cost_centers,$permited_costcenters);
        $ledger_ids = $request->_account_ledger_id ?? [];

        $ledger_id_rows = implode(',', $ledger_ids);
        $_branch_ids_rows = implode(',', $_branch_ids);
        $_cost_center_id_rows = implode(',', $_cost_center_ids);

         $request_organizations = $request->organization_id ?? [];
        $permited_organizations = permited_organization(explode(',',$users->organization_ids));
        $_organization_ids = filterableOrganization($request_organizations,$permited_organizations);
        $_organization_id_rows = implode(',', $_organization_ids);
        //AND t1.organization_id IN(".$_organization_id_rows.")




        $opening_query_sting = " 
SELECT l1._type,l1._date,l1._account_ledger,l1._l_name,l1._short_narration,l1._reference,l1._table_name,l1._id,l1._dr_amount,l1._cr_amount,l1._serial FROM(
        SELECT 'A. Opening' as _type,null as _date,  t1._account_ledger AS _account_ledger,t3._name as _l_name,null as _short_narration,null as _reference,null as _table_name, null as _id,SUM(t1._dr_amount-t1._cr_amount) as _dr_amount,0 as _cr_amount,0 as _serial
            FROM accounts as t1
            INNER JOIN account_ledgers as t3 ON t3.id=t1._account_ledger
               WHERE t1._status=1 AND t1._date < '".$_datex."' AND t1._account_ledger IN(".$ledger_id_rows.") AND t1.organization_id IN(".$_organization_id_rows.")
               AND  t1._branch_id IN(".$_branch_ids_rows.") AND  t1._cost_center IN(".$_cost_center_id_rows.")
                 GROUP BY t1._account_ledger 
UNION ALL
SELECT 'B. Receipt & Payment' as _type,t1._date, t1._account_ledger AS _account_ledger,t3._name as _l_name,t1._short_narration as _short_narration,t1._reference as _reference,t1._table_name, t1._ref_master_id as _id,t1._dr_amount as _dr_amount,t1._cr_amount as _cr_amount,t1._serial
            FROM accounts as t1
             INNER JOIN account_ledgers as t3 ON t3.id=t1._account_ledger
              WHERE  t1._status=1 AND t1._date  >= '".$_datex."'  AND t1._date <= '".$_datey."' 
               AND t1._account_ledger IN(".$ledger_id_rows.") AND t1.organization_id IN(".$_organization_id_rows.")
               AND  t1._branch_id IN(".$_branch_ids_rows.") AND  t1._cost_center IN(".$_cost_center_id_rows.")

    UNION ALL
    SELECT 'C. Closing' as _type,null as _date,  t1._account_ledger AS _account_ledger,t3._name as _l_name,null as _short_narration,null as _reference,null as _table_name, null as _id,SUM(t1._dr_amount-t1._cr_amount) as _dr_amount,0 as _cr_amount,0 as _serial
            FROM accounts as t1
            INNER JOIN account_ledgers as t3 ON t3.id=t1._account_ledger
               WHERE t1._status=1 AND t1._date <= '".$_datey."'  AND t1._account_ledger IN(".$ledger_id_rows.") AND t1.organization_id IN(".$_organization_id_rows.")
               AND  t1._branch_id IN(".$_branch_ids_rows.") AND  t1._cost_center IN(".$_cost_center_id_rows.")
                 GROUP BY t1._account_ledger  ) as l1 ORDER BY l1._type,l1._date,l1._id ASC ";

         $_opening_balance =  DB::select($opening_query_sting);
         $_result_group =array();
         foreach ($_opening_balance as $value) {
           $_result_group[$value->_type][]=$value;
         }
        return view('backend.account-report.report_receipt_payment',compact('request','page_name','previous_filter','permited_branch','permited_costcenters','_result_group'));

    }

public function receiptPaymentFilterReset(){
  Session::flash('filter_receipt_payment');
  return redirect()->back();
}

    public function groupLedger(Request $request){

        $previous_filter= Session::get('groupBaseLedgerReportFilter');
        $page_name = "Group Ledger Statement";
        $account_groups = \DB::select(" SELECT DISTINCT t2.id as id,t2._name as _name FROM accounts AS t1
                                        INNER JOIN account_groups AS t2 ON t1._account_group=t2.id WHERE t2._show_filter=1 ORDER BY t2._name ASC ");
        $users = Auth::user();
        $permited_branch = permited_branch(explode(',',$users->branch_ids));
        $permited_costcenters = permited_costcenters(explode(',',$users->cost_center_ids));


         
        return view('backend.account-report.group_ledger',compact('request','page_name','account_groups','previous_filter','permited_branch','permited_costcenters'));
    }


    public function groupBaseLedgerReport(Request $request){
     // return $request->all();
      $this->validate($request, [
            '_datex' => 'required',
            '_datey' => 'required'
        ]);

        $_status = $request->_status ?? '';
        if($_status ==''){
           $_status = "1,0";
        }

        session()->put('groupBaseLedgerReportFilter', $request->all());
        $previous_filter= Session::get('groupBaseLedgerReportFilter');
        $page_name = "Group Ledger Statement";
        
        $users = Auth::user();
        $permited_branch = permited_branch(explode(',',$users->branch_ids));
        $permited_costcenters = permited_costcenters(explode(',',$users->cost_center_ids));
        $datas=[];
        $_datex =  change_date_format($request->_datex);
        $_datey=  change_date_format($request->_datey);

        $group_ids = array();
        $_account_groups = $request->_account_group_id ?? [];
        if(sizeof($_account_groups) > 0){
            foreach ($_account_groups as $value) {
                array_push($group_ids, (int) $value);
            }
        }

        $ledger_ids = array();
        $_account_ledgers = (array) $request->_account_ledger_id ?? [];
        if(sizeof($_account_ledgers) > 0){
            foreach ($_account_ledgers as $value) {
                array_push($ledger_ids, (int) $value);
            }
            $basic_information = AccountLedger::with(['account_group'])
                    ->select('_account_group_id','id as _ledger_id','_name')
                         ->whereIn('id',$_account_ledgers)->get();
        }else{
            $basic_information = AccountLedger::with(['account_group'])->select('_account_group_id','id as _ledger_id','_name')
            ->whereIn('_account_group_id',$group_ids)->get();
            foreach ($basic_information as $value) {
                array_push($ledger_ids, (int) $value->_ledger_id);
            }
        }

       

      $request_branchs = $request->_branch_id ?? [];
      $request_cost_centers = $request->_cost_center ?? [];

      $_branch_ids = filterableBranch($request_branchs,$permited_branch);
      $_cost_center_ids = filterableCostCenter($request_cost_centers,$permited_costcenters);

      $ledger_id_rows = implode(',', $ledger_ids);
      $_branch_ids_rows = implode(',', $_branch_ids);
      $_cost_center_id_rows = implode(',', $_cost_center_ids);

       $request_organizations = $request->organization_id ?? [];
        $permited_organizations = permited_organization(explode(',',$users->organization_ids));
        $_organization_ids = filterableOrganization($request_organizations,$permited_organizations);
        $_organization_id_rows = implode(',', $_organization_ids);
        //AND t1.organization_id IN(".$_organization_id_rows.")


      
     if($ledger_id_rows){
     $string_query = " 
      SELECT s1._account_group,s1._group_name, s1._account_ledger,s1._l_name,s1._branch_id,s1._cost_center, s1._branch_name, s1._id,s1._table_name, s1._date, s1._short_narration, s1._narration, s1._dr_amount, s1._cr_amount, s1._balance,s1._serial FROM(

     SELECT t1._account_group AS _account_group,t2._name as _group_name, t1._account_ledger AS _account_ledger,t3._name as _l_name,t1._branch_id AS _branch_id,t1._cost_center as _cost_center, t4._name as _branch_name, null as _id,null as _table_name, null as _date, null as _short_narration, 'Opening Balance' as _narration, 0 AS _dr_amount, 0  AS _cr_amount, SUM(t1._dr_amount-t1._cr_amount) AS _balance,0 as _serial  
            FROM accounts as t1
            INNER JOIN account_groups as t2 ON t2.id=t1._account_group
            INNER JOIN account_ledgers as t3 ON t3.id=t1._account_ledger
            INNER JOIN branches as t4 ON t4.id = t1._branch_id
               WHERE t1._status=1 AND t1._date < '".$_datex."' AND t1._account_ledger IN(".$ledger_id_rows.") AND t1.organization_id IN(".$_organization_id_rows.")
               AND  t1._branch_id IN(".$_branch_ids_rows.") AND  t1._cost_center IN(".$_cost_center_id_rows.") AND  t3._status IN(".$_status.")
                 GROUP BY t1._account_ledger
      UNION ALL
            SELECT t1._account_group AS _account_group,t2._name as _group_name, t1._account_ledger AS _account_ledger,t3._name as _l_name,t1._branch_id AS _branch_id,t1._cost_center as _cost_center, t4._name as _branch_name, t1._ref_master_id as _id, t1._table_name AS _table_name, t1._date as _date, t1._short_narration as _short_narration, t1._narration as _narration, t1._dr_amount AS _dr_amount, t1._cr_amount  AS _cr_amount, 0 AS _balance ,t1._serial as _serial
            FROM accounts AS t1
            INNER JOIN account_groups as t2 ON t2.id=t1._account_group
            INNER JOIN account_ledgers as t3 ON t3.id=t1._account_ledger
            INNER JOIN branches as t4 ON t4.id = t1._branch_id
              WHERE  t1._status=1 AND t1._date  >= '".$_datex."'  AND t1._date <= '".$_datey."' 
              AND t1._account_ledger IN(".$ledger_id_rows.") AND t1.organization_id IN(".$_organization_id_rows.") AND  t1._branch_id IN(".$_branch_ids_rows.") AND  t1._cost_center IN(".$_cost_center_id_rows.") AND  t3._status IN(".$_status.") ) as s1 order by s1._date,s1._id,s1._serial ASC ";

       $datas = DB::select($string_query);
       $group_array_values = array();
       foreach ($datas as $value) {
           $group_array_values[$value->_group_name][$value->_l_name][]=$value;
       }

}else{
   $group_array_values = array();
}
        //return $group_array_values;
        return view('backend.account-report.group_ledger_report',compact('request','page_name','group_array_values','basic_information','_datex','_datey','previous_filter','permited_branch','permited_costcenters'));
    }


    

    public function groupBaseLedgerFilterReset(){
        Session::flash('groupBaseLedgerReportFilter');

        return redirect()->back();
    }
 public function filterLedgerSummarray(Request $request){

        $previous_filter= Session::get('ledgerSummaryFilter');
        $page_name = "Ledger Summary Report";
        $account_groups = \DB::select(" SELECT DISTINCT t2.id as id,t2._name as _name FROM accounts AS t1
                                        INNER JOIN account_groups AS t2 ON t1._account_group=t2.id WHERE t2._show_filter=1 ORDER BY t2._name ASC ");
        $users = Auth::user();
        $permited_branch = permited_branch(explode(',',$users->branch_ids));
        $permited_costcenters = permited_costcenters(explode(',',$users->cost_center_ids));


         
        return view('backend.account-report.filter_ledger_summary',compact('request','page_name','account_groups','previous_filter','permited_branch','permited_costcenters'));
    }


    public function ledgerSummaryReport(Request $request){
   //  return $request->all();
      $this->validate($request, [
            '_branch_id' => 'required',
            '_account_group_id' => 'required'
        ]);

      $_status = $request->_status ?? '';
        if($_status ==''){
           $_status = "1,0";
        }

        session()->put('ledgerSummaryFilter', $request->all());
        $previous_filter= Session::get('ledgerSummaryFilter');
        $page_name = "Ledger Summary Report";
        $_order_by= $request->_order_by ?? 'DESC';
        $_with_zero= $request->_with_zero ?? 1;
        
       $users = Auth::user();
        $permited_branch = permited_branch(explode(',',$users->branch_ids));
        $permited_costcenters = permited_costcenters(explode(',',$users->cost_center_ids));
        $datas=[];

        $group_ids = array();
        $_account_groups = $request->_account_group_id ?? [];
        if(sizeof($_account_groups) > 0){
            foreach ($_account_groups as $value) {
                array_push($group_ids, (int) $value);
            }
        }

        $ledger_ids = array();
        $_account_ledgers = (array) $request->_account_ledger_id ?? [];
        if(sizeof($_account_ledgers) > 0){
            foreach ($_account_ledgers as $value) {
                array_push($ledger_ids, (int) $value);
            }
            $basic_information = AccountLedger::with(['account_group'])
                    ->select('_account_group_id','id as _ledger_id','_name')
                         ->whereIn('id',$_account_ledgers)->get();
        }else{
            $basic_information = AccountLedger::with(['account_group'])->select('_account_group_id','id as _ledger_id','_name')
            ->whereIn('_account_group_id',$group_ids)->get();
            foreach ($basic_information as $value) {
                array_push($ledger_ids, (int) $value->_ledger_id);
            }
        }

       

      $request_branchs = $request->_branch_id ?? [];
      $request_cost_centers = $request->_cost_center ?? [];

      $_branch_ids = filterableBranch($request_branchs,$permited_branch);
      $_cost_center_ids = filterableCostCenter($request_cost_centers,$permited_costcenters);

      $ledger_id_rows = implode(',', $ledger_ids);
      $_branch_ids_rows = implode(',', $_branch_ids);
      $_cost_center_id_rows = implode(',', $_cost_center_ids);

      $request_organizations = $request->organization_id ?? [];
        $permited_organizations = permited_organization(explode(',',$users->organization_ids));
        $_organization_ids = filterableOrganization($request_organizations,$permited_organizations);
        $_organization_id_rows = implode(',', $_organization_ids);
        //AND t1.organization_id IN(".$_organization_id_rows.")


      
     if($ledger_id_rows){
     $string_query = "  
            SELECT t1._account_group AS _account_group,t2._name as _group_name,t3._address,t3._phone, t1._account_ledger AS _account_ledger,t3._name as _l_name,t1._branch_id AS _branch_id,t1._cost_center as _cost_center, t4._name as _branch_name, SUM(t1._dr_amount-t1._cr_amount) AS _balance
            FROM accounts AS t1
            INNER JOIN account_groups as t2 ON t2.id=t1._account_group
            INNER JOIN account_ledgers as t3 ON t3.id=t1._account_ledger
            INNER JOIN branches as t4 ON t4.id = t1._branch_id
              WHERE  t1._status=1 AND  t3._status IN(".$_status.")
              AND t1._account_ledger IN(".$ledger_id_rows.") AND t1.organization_id IN(".$_organization_id_rows.") AND  t1._branch_id IN(".$_branch_ids_rows.") AND  t1._cost_center IN(".$_cost_center_id_rows.")    GROUP BY t1._account_ledger ";
              if($_with_zero ==1){
                $string_query .= " HAVING (abs(SUM(t1._dr_amount-t1._cr_amount)) > 0 )  ";
              }

              $string_query .= "   ORDER BY  abs(SUM(t1._dr_amount-t1._cr_amount))  $_order_by ";
           

        $datas = DB::select($string_query);
       $group_array_values = array();
       foreach ($datas as $value) {
           $group_array_values[$value->_group_name][]=$value;
       }

}else{
   $group_array_values = array();
}
        //return $group_array_values;
        return view('backend.account-report.report_ledger_summary',compact('request','page_name','group_array_values','basic_information','previous_filter','permited_branch','permited_costcenters'));
    }


    public function ledgerSummaryFilterReset(){
        Session::flash('ledgerSummaryFilter');

        return redirect()->back();
    }

    public function LedgerReportFilterReset(){
        Session::flash('ledgerReprtFilter');

        return redirect()->back();
    }



    public function trailBalance(Request $request){
        $previous_filter= Session::get('trailBalanceReportFilter');
        $page_name = "Trail Balance";
       $account_groups = \DB::select(" SELECT DISTINCT t2.id as id,t2._name as _name FROM accounts AS t1
                                        INNER JOIN account_groups AS t2 ON t1._account_group=t2.id WHERE t2._show_filter=1 ORDER BY t2._name ASC ");
        $users = Auth::user();
        $permited_branch = permited_branch(explode(',',$users->branch_ids));
        $permited_costcenters = permited_costcenters(explode(',',$users->cost_center_ids));


         
        return view('backend.account-report.trail-balance',compact('request','page_name','account_groups','previous_filter','permited_branch','permited_costcenters'));
    }




    public function trailBalanceReport(Request $request){
      $this->validate($request, [
            '_datex' => 'required',
            '_datey' => 'required'
        ]);

        session()->put('trailBalanceReportFilter', $request->all());
        $previous_filter= Session::get('trailBalanceReportFilter');
        $page_name = "Trail Balance";
       
        $datas=[];
         $_datex =  change_date_format($request->_datex);
        $_datey=  change_date_format($request->_datey);
        $users = Auth::user();
        $permited_branch = permited_branch(explode(',',$users->branch_ids));
        $permited_costcenters = permited_costcenters(explode(',',$users->cost_center_ids));

        $group_ids = array();
        $_account_groups = $request->_account_group_id ?? [];
        if(sizeof($_account_groups) > 0){
            foreach ($_account_groups as $value) {
                array_push($group_ids, (int) $value);
            }
        }

        $ledger_ids = array();
        $_account_ledgers = (array) $request->_account_ledger_id ?? [];
        if(sizeof($_account_ledgers) > 0){
            foreach ($_account_ledgers as $value) {
                array_push($ledger_ids, (int) $value);
            }
            $basic_information = AccountLedger::with(['account_group'])
                    ->select('_account_group_id','id as _ledger_id','_name')
                         ->whereIn('id',$_account_ledgers)->get();
        }else{
            $basic_information = AccountLedger::with(['account_group'])->select('_account_group_id','id as _ledger_id','_name')
            ->whereIn('_account_group_id',$group_ids)->get();
            foreach ($basic_information as $value) {
                array_push($ledger_ids, (int) $value->_ledger_id);
            }
        }

      $request_branchs = $request->_branch_id ?? [];
      $request_cost_centers = $request->_cost_center ?? [];

      $_branch_ids = filterableBranch($request_branchs,$permited_branch);
      $_cost_center_ids = filterableCostCenter($request_cost_centers,$permited_costcenters);

      $ledger_id_rows = implode(',', $ledger_ids);
      $_branch_ids_rows = implode(',', $_branch_ids);
      $_cost_center_id_rows = implode(',', $_cost_center_ids);

      $request_organizations = $request->organization_id ?? [];
        $permited_organizations = permited_organization(explode(',',$users->organization_ids));
        $_organization_ids = filterableOrganization($request_organizations,$permited_organizations);
        $_organization_id_rows = implode(',', $_organization_ids);
        //AND t1.organization_id IN(".$_organization_id_rows.")


      
      if($ledger_id_rows){
     $string_query = " 
 SELECT t5._account_group,t5._group_name, t5._account_ledger,t5._l_name,t5._branch_id,t5._cost_center, t5._branch_name,  SUM(t5._o_dr_amount)  AS _o_dr_amount, SUM(t5._o_cr_amount)  AS _o_cr_amount ,SUM(t5._c_dr_amount) as _c_dr_amount,SUM(t5._c_cr_amount) as _c_cr_amount FROM (
     SELECT t1._account_group AS _account_group,t2._name as _group_name, t1._account_ledger AS _account_ledger,t3._name as _l_name,t1._branch_id AS _branch_id,t1._cost_center as _cost_center, t4._name as _branch_name,  SUM(t1._dr_amount)  AS _o_dr_amount, SUM(t1._cr_amount)  AS _o_cr_amount ,0 as _c_dr_amount,0 as _c_cr_amount 
            FROM accounts as t1
            INNER JOIN account_groups as t2 ON t2.id=t1._account_group
            INNER JOIN account_ledgers as t3 ON t3.id=t1._account_ledger
            INNER JOIN branches as t4 ON t4.id = t1._branch_id
               WHERE  t1._status=1 AND t1._date < '".$_datex."' AND t1._account_ledger IN(".$ledger_id_rows.") AND t1.organization_id IN(".$_organization_id_rows.")
               AND  t1._branch_id IN(".$_branch_ids_rows.") AND  t1._cost_center IN(".$_cost_center_id_rows.")
                 GROUP BY t1._account_ledger
      UNION ALL
            SELECT t1._account_group AS _account_group,t2._name as _group_name, t1._account_ledger AS _account_ledger,t3._name as _l_name,t1._branch_id AS _branch_id,t1._cost_center as _cost_center, t4._name as _branch_name,0 as _cr_amount, 0 as _o_cr_amount, SUM(t1._dr_amount) AS _c_dr_amount, SUM(t1._cr_amount)  AS _c_cr_amount
            FROM accounts AS t1
            INNER JOIN account_groups as t2 ON t2.id=t1._account_group
            INNER JOIN account_ledgers as t3 ON t3.id=t1._account_ledger
            INNER JOIN branches as t4 ON t4.id = t1._branch_id
              WHERE  t1._status=1 AND t1._date  >= '".$_datex."'  AND t1._date <= '".$_datey."' 
              AND t1._account_ledger IN(".$ledger_id_rows.") AND t1.organization_id IN(".$_organization_id_rows.") AND  t1._branch_id IN(".$_branch_ids_rows.") AND  t1._cost_center IN(".$_cost_center_id_rows.")
              GROUP BY t1._account_ledger
              ) as t5 GROUP BY t5._account_ledger  ";

       $datas = DB::select($string_query);
       $group_array_values = array();
       foreach ($datas as $value) {
           $group_array_values[$value->_group_name][$value->_l_name][]=$value;
       }
}else{
   $group_array_values = array();
}
     //  return $group_array_values;

       

        //return $group_array_values;
        return view('backend.account-report.trail-balance-report',compact('request','page_name','group_array_values','basic_information','_datex','_datey','previous_filter','permited_branch','permited_costcenters'));
    }

    public function trailBalanceReportFilterReset(){
        Session::flash('trailBalanceReportFilter');

        return redirect()->back();
    }


    public function incomeStatement(Request $request){
        $previous_filter= Session::get('incomeStatementFillter');
        $page_name = "Income Statement";
        $users = Auth::user();
        $permited_branch = permited_branch(explode(',',$users->branch_ids));
        $permited_costcenters = permited_costcenters(explode(',',$users->cost_center_ids));
        $incomeStatementLedgers = DB::table('account_ledgers')
                                    ->select('account_ledgers.id','account_ledgers._name','account_ledgers._show','account_heads._name as _head_name')
                                    ->join('account_heads','account_heads.id','account_ledgers._account_head_id')
                                    ->whereIn('account_heads._account_id',[3,4])
                                    ->orderBy('account_heads.id','ASC')
                                    ->orderBy('account_ledgers.id','ASC')
                                    ->get();
        $_filter_ledgers = array();
        foreach ($incomeStatementLedgers as $value) {
          $_filter_ledgers[$value->_head_name][] = $value;
        }
       

         
        return view('backend.account-report.income-statement',compact('request','page_name','previous_filter','permited_branch','permited_costcenters','_filter_ledgers'));
    }


    public function incomeStatementReport(Request $request){
        $this->validate($request, [
            '_datex' => 'required',
            '_datey' => 'required'
        ]);
         session()->put('incomeStatementFillter', $request->all());
        $previous_filter= Session::get('incomeStatementFillter');
        $page_name = "Income Statement";
        $users = Auth::user();
        $permited_branch = permited_branch(explode(',',$users->branch_ids));
        $permited_costcenters = permited_costcenters(explode(',',$users->cost_center_ids));

      $_datex =  change_date_format($request->_datex);
      $_datey=  change_date_format($request->_datey);
      $request_branchs = $request->_branch_id ?? [];
      $request_cost_centers = $request->_cost_center ?? [];

      $_branch_ids = filterableBranch($request_branchs,$permited_branch);
      $_cost_center_ids = filterableCostCenter($request_cost_centers,$permited_costcenters);

       $_branch_ids_rows = implode(',', $_branch_ids);
       $_cost_center_id_rows = implode(',', $_cost_center_ids);


       $request_organizations = $request->organization_id ?? [];
        $permited_organizations = permited_organization(explode(',',$users->organization_ids));
        $_organization_ids = filterableOrganization($request_organizations,$permited_organizations);
        $_organization_id_rows = implode(',', $_organization_ids);
        //AND t1.organization_id IN(".$_organization_id_rows.")


      $income_query = " SELECT t5._account_group,t5._group_name, t5._account_ledger,t5._l_name,t5._branch_id,t5._cost_center, t5._branch_name,  SUM(t5._previous_balance)  AS _previous_balance, SUM(t5._current_balance)  AS _current_balance, SUM(t5._previous_balance+t5._current_balance) as _last_amount FROM (

      SELECT t1._account_group AS _account_group,t2._name as _group_name, t1._account_ledger AS _account_ledger,t3._name as _l_name,t1._branch_id AS _branch_id,t1._cost_center as _cost_center, t4._name as _branch_name,  SUM(t1._cr_amount-t1._dr_amount)  AS _previous_balance, 0  AS _current_balance
            FROM accounts as t1
            INNER JOIN account_groups as t2 ON t2.id=t1._account_group
            INNER JOIN account_ledgers as t3 ON t3.id=t1._account_ledger
            INNER JOIN branches as t4 ON t4.id = t1._branch_id
               WHERE  t1._status=1 AND t1._date < '".$_datex."' AND t3._show=1 AND t1._account_head IN (8,9) AND t1.organization_id IN(".$_organization_id_rows.")
               AND  t1._branch_id IN(".$_branch_ids_rows.") AND  t1._cost_center IN(".$_cost_center_id_rows.")
                 GROUP BY t1._account_ledger
            UNION ALL
            SELECT t1._account_group AS _account_group,t2._name as _group_name, t1._account_ledger AS _account_ledger,t3._name as _l_name,t1._branch_id AS _branch_id,t1._cost_center as _cost_center, t4._name as _branch_name,  0 AS _previous_balance, SUM(t1._cr_amount-t1._dr_amount)   AS _current_balance
            FROM accounts as t1
            INNER JOIN account_groups as t2 ON t2.id=t1._account_group
            INNER JOIN account_ledgers as t3 ON t3.id=t1._account_ledger
            INNER JOIN branches as t4 ON t4.id = t1._branch_id

               WHERE  t1._status=1 AND t1._date  >= '".$_datex."'  AND t1._date <= '".$_datey."'  AND t3._show=1 AND t1._account_head IN (SELECT id FROM `account_heads` WHERE _account_id=3) AND t1.organization_id IN(".$_organization_id_rows.")
               AND  t1._branch_id IN(".$_branch_ids_rows.") AND  t1._cost_center IN(".$_cost_center_id_rows.")
                 GROUP BY t1._account_ledger
                 ) as t5 GROUP BY t5._account_ledger ";
      $income_8_result = DB::select($income_query);
      $income_8 = array();
      foreach ($income_8_result as $value) {
        $income_8[$value->_group_name][]=$value;
      }



      $other_income_expense_query = " SELECT t5._account_group,t5._group_name, t5._account_ledger,t5._l_name,t5._branch_id,t5._cost_center, t5._branch_name,  SUM(t5._previous_balance)  AS _previous_balance, SUM(t5._current_balance)  AS _current_balance, SUM(t5._previous_balance+t5._current_balance) as _last_amount FROM (

      SELECT t1._account_group AS _account_group,t2._name as _group_name, t1._account_ledger AS _account_ledger,t3._name as _l_name,t1._branch_id AS _branch_id,t1._cost_center as _cost_center, t4._name as _branch_name,  SUM(t1._cr_amount-t1._dr_amount)  AS _previous_balance, 0  AS _current_balance
            FROM accounts as t1
            INNER JOIN account_groups as t2 ON t2.id=t1._account_group
            INNER JOIN account_ledgers as t3 ON t3.id=t1._account_ledger
            INNER JOIN branches as t4 ON t4.id = t1._branch_id
               WHERE  t1._status=1 AND t1._date < '".$_datex."' AND t3._show=1 AND t1._account_head IN (10,11,15) AND t1.organization_id IN(".$_organization_id_rows.")
               AND  t1._branch_id IN(".$_branch_ids_rows.") AND  t1._cost_center IN(".$_cost_center_id_rows.")
                 GROUP BY t1._account_ledger
            UNION ALL
            SELECT t1._account_group AS _account_group,t2._name as _group_name, t1._account_ledger AS _account_ledger,t3._name as _l_name,t1._branch_id AS _branch_id,t1._cost_center as _cost_center, t4._name as _branch_name,  0 AS _previous_balance, SUM(t1._cr_amount-t1._dr_amount)   AS _current_balance
            FROM accounts as t1
            INNER JOIN account_groups as t2 ON t2.id=t1._account_group
            INNER JOIN account_ledgers as t3 ON t3.id=t1._account_ledger
            INNER JOIN branches as t4 ON t4.id = t1._branch_id
               WHERE  t1._status=1 AND t1._date  >= '".$_datex."'  AND t1._date <= '".$_datey."'  AND t3._show=1 AND t1._account_head IN (SELECT id FROM `account_heads` WHERE _account_id=4) AND t1.organization_id IN(".$_organization_id_rows.")
               AND  t1._branch_id IN(".$_branch_ids_rows.") AND  t1._cost_center IN(".$_cost_center_id_rows.")
                 GROUP BY t1._account_ledger
                 ) as t5 GROUP BY t5._account_ledger ";
            $other_income_expense_result = DB::select($other_income_expense_query);
            $other_income_expenses = array();
            foreach ($other_income_expense_result as $value) {
              $other_income_expenses[$value->_group_name][]=$value;
            }

        // return $income_8;
        return view('backend.account-report.income-statement-report',compact('request','page_name','previous_filter','permited_branch','permited_costcenters','income_8','other_income_expenses'));
    }



    public function balanceSheet(Request $request){
        $previous_filter= Session::get('balanceSheetFilter');
        $page_name = "Balance Sheet";
        
        $users = Auth::user();
        $permited_branch = permited_branch(explode(',',$users->branch_ids));
        $permited_costcenters = permited_costcenters(explode(',',$users->cost_center_ids));
        
       

         
        return view('backend.account-report.balance-sheet',compact('request','page_name','previous_filter','permited_branch','permited_costcenters'));
    }


    public function balanceSheetReport(Request $request){
        $this->validate($request, [
            '_datex' => 'required'
        ]);
         session()->put('balanceSheetFilter', $request->all());
        $previous_filter= Session::get('balanceSheetFilter');
        $page_name = "Balance Sheet";
        $users = Auth::user();
        $permited_branch = permited_branch(explode(',',$users->branch_ids));
        $permited_costcenters = permited_costcenters(explode(',',$users->cost_center_ids));

      $_datex =  change_date_format($request->_datex);
      $_datey=  change_date_format($request->_datey);
      $request_branchs = $request->_branch_id ?? [];
      $request_cost_centers = $request->_cost_center ?? [];

      $_branch_ids = filterableBranch($request_branchs,$permited_branch);
      $_cost_center_ids = filterableCostCenter($request_cost_centers,$permited_costcenters);

      $_branch_ids_rows = implode(',', $_branch_ids);
      $_cost_center_id_rows = implode(',', $_cost_center_ids);
      $_with_zero_qty = $request->_with_zero ?? 0;

      $request_organizations = $request->organization_id ?? [];
        $permited_organizations = permited_organization(explode(',',$users->organization_ids));
        $_organization_ids = filterableOrganization($request_organizations,$permited_organizations);
        $_organization_id_rows = implode(',', $_organization_ids);
        //AND t1.organization_id IN(".$_organization_id_rows.")


      $balance_sheet = "   SELECT t5._name as _main_head,t6._name as _head_name, t1._account_group AS _account_group,t2._name as _group_name, t1._account_ledger AS _account_ledger,t3._name as _l_name,t1._branch_id AS _branch_id,t1._cost_center as _cost_center, t4._name as _branch_name,  SUM(t1._dr_amount-t1._cr_amount)  AS _amount
            FROM accounts as t1
            INNER JOIN account_groups as t2 ON t2.id=t1._account_group
            INNER JOIN account_ledgers as t3 ON t3.id=t1._account_ledger
            INNER JOIN account_heads as t6 ON t6.id=t1._account_head
            INNER JOIN main_account_head as t5 ON t5.id=t6._account_id
            INNER JOIN branches as t4 ON t4.id = t1._branch_id
               WHERE  t1._status=1 AND t1._date <= '".$_datex."' AND t3._show=1 AND t5.id IN (1,2,5)
              AND t1.organization_id IN(".$_organization_id_rows.") AND  t1._branch_id IN (".$_branch_ids_rows.") AND  t1._cost_center IN(".$_cost_center_id_rows.")
                 GROUP BY t1._account_ledger

          UNION ALL
          SELECT 'Capital' as _main_head, 'Owner\'s equity' as _head_name, 'Owner\'s Equity' AS _account_group,'Owner\'s Equity' as _group_name, null  AS _account_ledger,'Income Statement Account' as _l_name,t1._branch_id AS _branch_id,t1._cost_center as _cost_center, t4._name as _branch_name,  SUM(t1._dr_amount-t1._cr_amount)  AS _amount
            FROM accounts as t1
            INNER JOIN account_groups as t2 ON t2.id=t1._account_group
            INNER JOIN account_ledgers as t3 ON t3.id=t1._account_ledger
            INNER JOIN account_heads as t6 ON t6.id=t1._account_head
            INNER JOIN main_account_head as t5 ON t5.id=t6._account_id
            INNER JOIN branches as t4 ON t4.id = t1._branch_id
               WHERE  t1._status=1 AND t1._date <= '".$_datex."' AND t3._show=1 AND t5.id IN (3,4)
             AND t1.organization_id IN(".$_organization_id_rows.")  AND  t1._branch_id IN(".$_branch_ids_rows.") AND  t1._cost_center IN(".$_cost_center_id_rows.") ";

      $balance_sheet_result = DB::select($balance_sheet);
      $balance_sheet_filter = array();
      foreach ($balance_sheet_result as $value) {
        $balance_sheet_filter[$value->_main_head][$value->_head_name][$value->_group_name][]=$value;
      }

     if($request->_level =='Level 1'){
      
       return view('backend.account-report.balance_sheet_level_1',compact('request','page_name','previous_filter','permited_branch','permited_costcenters','balance_sheet_filter','_with_zero_qty'));

     }else{
       return view('backend.account-report.balance-sheet-report',compact('request','page_name','previous_filter','permited_branch','permited_costcenters','balance_sheet_filter','_with_zero_qty'));
     }

       
    }



    public function workSheet(Request $request){
        $previous_filter= Session::get('workSheetFilter');
        $page_name = "Work Sheet";
        
        $users = Auth::user();
        $permited_branch = permited_branch(explode(',',$users->branch_ids));
        $permited_costcenters = permited_costcenters(explode(',',$users->cost_center_ids));
        
       

         
        return view('backend.account-report.work-sheet',compact('request','page_name','previous_filter','permited_branch','permited_costcenters'));

    }

    public function workSheetReport(Request $request){

      $this->validate($request, [
            '_datex' => 'required'
        ]);
         session()->put('balanceSheetFilter', $request->all());
        $previous_filter= Session::get('balanceSheetFilter');
        $page_name = "Work Sheet";
        $users = Auth::user();
        $permited_branch = permited_branch(explode(',',$users->branch_ids));
        $permited_costcenters = permited_costcenters(explode(',',$users->cost_center_ids));

      $_datex =  change_date_format($request->_datex);
      $_datey=  change_date_format($request->_datey);
      $request_branchs = $request->_branch_id ?? [];
      $request_cost_centers = $request->_cost_center ?? [];

      $_branch_ids = filterableBranch($request_branchs,$permited_branch);
      $_cost_center_ids = filterableCostCenter($request_cost_centers,$permited_costcenters);

      $_branch_ids_rows = implode(',', $_branch_ids);
      $_cost_center_id_rows = implode(',', $_cost_center_ids);


      $request_organizations = $request->organization_id ?? [];
        $permited_organizations = permited_organization(explode(',',$users->organization_ids));
        $_organization_ids = filterableOrganization($request_organizations,$permited_organizations);
        $_organization_id_rows = implode(',', $_organization_ids);
        //AND t1.organization_id IN(".$_organization_id_rows.")


      $balance_sheet = "   SELECT t5.id as _main_head,t6._name as _head_name, t1._account_group AS _account_group,t2._name as _group_name, t1._account_ledger AS _account_ledger,t3._name as _l_name,t1._branch_id AS _branch_id,t1._cost_center as _cost_center, t4._name as _branch_name,  SUM(t1._dr_amount-t1._cr_amount)  AS _amount
            FROM accounts as t1
            INNER JOIN account_groups as t2 ON t2.id=t1._account_group
            INNER JOIN account_ledgers as t3 ON t3.id=t1._account_ledger
            INNER JOIN account_heads as t6 ON t6.id=t1._account_head
            INNER JOIN main_account_head as t5 ON t5.id=t6._account_id
            INNER JOIN branches as t4 ON t4.id = t1._branch_id
               WHERE  t1._status=1 AND t1._date < '".$_datex."'  AND t1.organization_id IN(".$_organization_id_rows.")
               AND  t1._branch_id IN (".$_branch_ids_rows.") AND  t1._cost_center IN(".$_cost_center_id_rows.")
                 GROUP BY t1._account_ledger

          UNION ALL
          SELECT 6 as _main_head, 4 as _head_name, 'Owner\'s Equity' AS _account_group,'Owner\'s Equity' as _group_name, null  AS _account_ledger,'Income Statement Account' as _l_name,t1._branch_id AS _branch_id,t1._cost_center as _cost_center, t4._name as _branch_name,  SUM(t1._dr_amount-t1._cr_amount)  AS _amount
            FROM accounts as t1
            INNER JOIN account_groups as t2 ON t2.id=t1._account_group
            INNER JOIN account_ledgers as t3 ON t3.id=t1._account_ledger
            INNER JOIN account_heads as t6 ON t6.id=t1._account_head
            INNER JOIN main_account_head as t5 ON t5.id=t6._account_id
            INNER JOIN branches as t4 ON t4.id = t1._branch_id
               WHERE  t1._status=1 AND t1._date <= '".$_datex."'  AND t3._show=1 AND t5.id IN (3,4)
             AND t1.organization_id IN(".$_organization_id_rows.")  AND  t1._branch_id IN(".$_branch_ids_rows.") AND  t1._cost_center IN(".$_cost_center_id_rows.") 


                   ";
       $work_sheet_result = DB::select($balance_sheet);
      

      

        return view('backend.account-report.work-sheet-report',compact('request','page_name','previous_filter','permited_branch','permited_costcenters','work_sheet_result'));
      
    }

    public function workSheetFilterReset(Request $request){
      Session::flash('workSheetFilter');

        return redirect()->back();
    }






    public function incomeStatementFilterReset(){
        Session::flash('incomeStatementFillter');

        return redirect()->back();
    }

    public function balanceSheetFilterReset(){
        Session::flash('balanceSheetFilter');

        return redirect()->back();
    }




    public function incomeStatementSettings(Request $request){

      $_ledger_ids = $request->_l_id ?? [];
      $_shows = $request->_show ?? [];
        foreach ($_ledger_ids as  $key=>$value) {
          $AccountLedger = AccountLedger::find($value);
          $AccountLedger->_show = $_shows[$key];
          $AccountLedger->save();

          
        }

        return redirect()->back();

    }














}