<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AccountGroup;
use App\Models\Accounts;
use App\Models\AccountLedger;
use Illuminate\Support\Facades\DB;
use Session;
use Auth;
use App\Models\Sales;
use App\Models\SalesAccount;
use App\Models\SalesDetail;
use App\Models\VoucherMaster;
use App\Models\AccountHead;
use App\Models\Branch;
use App\Models\VoucherType;
use App\Models\VoucherMasterDetail;
use App\Models\StoreHouse;
use App\Models\PurchaseDetail;
use App\Models\PurchaseReturnAccount;
use App\Models\PurchaseReturnDetail;
use App\Models\ProductPriceList;
use App\Models\ItemInventory;
use App\Models\Inventory;
use App\Models\ItemCategory;
use App\Models\Units;
use App\Models\SalesReturn;
use App\Models\SalesReturnDetail;
use App\Models\SalesReturnAccount;
use App\Models\PurchaseReturn;
use App\Models\Purchase;
use App\Models\ResturantSales;

class InventoryReportController extends Controller
{

     function __construct()
    {
         
         $this->middleware('permission:actual-sales-report', ['only' => ['reportActualSales','filterActualSales','resetActualSales']]);

         $this->middleware('permission:bill-party-statement', ['only' => ['reportBillOfPartyStatement','filterBillOfPartyStatement','resetBillOfPartyStatement']]);

         $this->middleware('permission:date-wise-purchase', ['only' => ['reportDateWisePurchaseStatement','filterDateWisePurchaseStatement','resetDateWisePurchaseStatement']]);

         $this->middleware('permission:purchase-return-detail', ['only' => ['filterDateWisePurchaseReturnStatement','resetDateWisePurchaseReturnStatement','reportDateWisePurchaseReturnStatement']]);
         
         $this->middleware('permission:date-wise-sales', ['only' => ['reportDateWiseSalesStatement','filterDateWiseSalesStatement','resetDateWiseSalesStatement']]);
         
         $this->middleware('permission:sales-return-detail', ['only' => ['reportDateWiseSalesReturnStatement','filterDateWiseSalesReturnStatement','resetDateWiseSalesReturnStatement']]);
         
         $this->middleware('permission:stock-possition', ['only' => ['reportStockPossition','filterStockPossition','resetStockPossition']]);
         
         $this->middleware('permission:stock-ledger', ['only' => ['reportStockLedger','filterStockLedger','resetStockLedger']]);

         
         $this->middleware('permission:stock-value', ['only' => ['reportStockValue','filterStockValue','resetStockValue']]);

         
         $this->middleware('permission:stock-value-register', ['only' => ['reportStockValueRegister','filterStockValueRegister','resetStockValueRegister']]);

         $this->middleware('permission:gross-profit', ['only' => ['reportGrossProfit','filterGrossProfit','resetGrossProfit']]);
         
         $this->middleware('permission:expired-item', ['only' => ['reportExpiredItem','filterExpiredItem','resetExpiredItem']]);
         
         $this->middleware('permission:shortage-item', ['only' => ['filterShortageItem','reportShortageItem','resetShortageItem']]);
         
         $this->middleware('permission:date-wise-restaurant-sales', ['only' => ['filterDateWiseRestaurantSalesStatement','reportDateWiseRestaurantSalesStatement','resetDateWiseRestaurantSalesStatement']]);

         $this->middleware('permission:date-wise-restaurant-invoice-print', ['only' => ['dateWiseRestaurantInvoice','dateWiseRestaurantInvoiceFilterReset','dateWiseRestaurantInvoiceReport']]);

    }




     public function filterItemHistory(Request $request){

        $previous_filter= Session::get('item_history');
        $page_name = "Item History";
         
        return view('backend.inventory-report.filter_item_history',compact('request','page_name','previous_filter'));
    }

    public function reportItemHistory(Request $request){
      $this->validate($request, [
            '_search_item_id' => 'required',
            '_item_id' => 'required',
        ]);

        session()->put('item_history', $request->all());
        $previous_filter= Session::get('item_history');
        $page_name = "Item History";
        $_item_id = $request->_item_id;


        
         $purchase_details = DB::select("  SELECT 'purchase_details' as _table_name,t1.*
FROM purchase_details AS t1  WHERE t1._item_id=".$_item_id." ");

    $purchase_return_details = DB::select("  SELECT 'purchase_return_details' as _table_name,t1.*
FROM purchase_return_details AS t1  WHERE t1._item_id=".$_item_id." ");

    $sales_details = DB::select("  SELECT 'sales_details' as _table_name,t1.*
FROM sales_details AS t1  WHERE t1._item_id=".$_item_id." ");

    $sales_return_details = DB::select("  SELECT 'sales_return_details' as _table_name,t1.*
FROM sales_return_details AS t1  WHERE t1._item_id=".$_item_id." ");

    $damage_adjustment_details = DB::select("  SELECT 'damage_adjustment_details' as _table_name,t1.*
FROM damage_adjustment_details AS t1  WHERE t1._item_id=".$_item_id." ");

        return view('backend.inventory-report.report_item_history_row',compact('request','page_name','previous_filter','purchase_details','purchase_return_details','sales_details','sales_return_details','damage_adjustment_details'));
    }


    public function itemHistoryUpdate(Request $request){
      $row_id= $request->row_id;
      $table_name= $request->table_name;
      $column_name= $request->column_name;
      $column_value= $request->column_value;
      $string_query= " UPDATE ".$table_name." SET ".$column_name." = '".$column_value."' WHERE id = ".$row_id." ";
    return  \DB::statement($string_query);
      
    }


    public function resetItemHistory(){
        Session::flash('item_history');
        return redirect()->back();
    }


     public function filterBarcodeHistory(Request $request){

        $previous_filter= Session::get('barcode_history');
        $page_name = "Barcode History";
        return view('backend.inventory-report.filter_barcode_history',compact('request','page_name','previous_filter'));
    }


    public function reportBarcodeHistory(Request $request){
      $this->validate($request, [
            '_barcode' => 'required'
        ]);

        session()->put('barcode_history', $request->all());
        $previous_filter= Session::get('barcode_history');
        $page_name = "Barcode History";
        $_barcode = $request->_barcode ?? '';
        $datas = DB::select(" 
SELECT s1._type,s1._table_name,s4._name as _w_name,s1._ledger_id,s1.id as _id, s1._barcode,s1._warranty,s1._date,s1._item_id,s1._qty,s1._rate,s1._sales_rate,s1._store_id,s1._branch_id,s1._no  FROM(
          SELECT '1.Purchase' AS _type,'purchases' as _table_name,t2._ledger_id,t2.id, t1._barcode,t3._warranty,t2._date,t1._item_id,t1._qty,t1._rate,t1._sales_rate,t1._store_id,t1._branch_id,t1._no 
FROM purchase_details AS t1 
INNER JOIN purchases AS t2 ON t1._no=t2.id
LEFT JOIN inventories AS t3 ON t3.id=t1._item_id where t1._barcode LIKE '%$_barcode%'
UNION ALL
SELECT  '2.Purchase Return' AS _type,'purchase_return' as _table_name,t2._ledger_id,t2.id,t1._barcode,t3._warranty,t2._date,t1._item_id,t1._qty,t1._rate,t1._sales_rate,t1._store_id,t1._branch_id,t1._no 
FROM purchase_return_details AS t1 
INNER JOIN purchase_returns AS t2 ON t1._no=t2.id
LEFT JOIN inventories AS t3 ON t3.id=t1._item_id where t1._barcode LIKE '%$_barcode%'
UNION ALL
SELECT '3.Sales' AS _type,'sales' as _table_name,t2._ledger_id,t2.id,t1._barcode,t1._warranty,t2._date,t1._item_id,t1._qty,t1._rate,t1._sales_rate,t1._store_id,t1._branch_id,t1._no 
FROM sales_details AS t1
INNER JOIN sales AS t2 ON t1._no=t2.id where t1._barcode LIKE '%$_barcode%'
UNION ALL
SELECT '4.Sales Return' AS _type,'sales_return' as _table_name,t2._ledger_id,t2.id,t1._barcode,t1._warranty,t2._date,t1._item_id,t1._qty,t1._rate,t1._sales_rate,t1._store_id,t1._branch_id,t1._no 
FROM sales_return_details AS t1
INNER JOIN sales_returns AS t2 ON t1._no=t2.id where t1._barcode LIKE '%$_barcode%'
UNION ALL
SELECT '5.Damage' AS _type,'damage' as _table_name,t2._ledger_id,t2.id,t1._barcode,t1._warranty,t2._date,t1._item_id,t1._qty,t1._rate,t1._sales_rate,t1._store_id,t1._branch_id,t1._no 
FROM damage_adjustment_details AS t1
INNER JOIN damage_adjustments AS t2 ON t1._no=t2.id 
 where t1._barcode LIKE '%$_barcode%'
UNION ALL
SELECT '6.Rep In' AS _type,'replacement_masters' as _table_name,t2._ledger_id,t2.id,t1._barcode,inv._warranty,t2._date,t1._item_id,t1._qty,t1._rate,t1._sales_rate,t1._store_id,t1._branch_id,t1._no 
FROM replacement_item_ins AS t1
INNER JOIN inventories as inv ON inv.id=t1._item_id
INNER JOIN replacement_masters AS t2 ON t1._no=t2.id 
 where t1._barcode LIKE '%$_barcode%'
UNION ALL
SELECT '7.Rep Out' AS _type,'replacement_masters' as _table_name,t2._ledger_id,t2.id,t1._barcode,inv._warranty,t2._date,t1._item_id,t1._qty,t1._rate,t1._sales_rate,t1._store_id,t1._branch_id,t1._no 
FROM replacement_item_outs AS t1
INNER JOIN inventories as inv ON inv.id=t1._item_id
INNER JOIN replacement_masters AS t2 ON t1._no=t2.id 
 where t1._barcode LIKE '%$_barcode%'
UNION ALL
SELECT '8.Rep To Supplier' AS _type,'individual_replace_masters' as _table_name,t2._supplier_id as _ledger_id,t2.id,t1._barcode,inv._warranty,t2._date,t1._item_id,t1._qty,t1._rate,t1._sales_rate,t1._store_id,t1._branch_id,t1._no 
FROM individual_replace_old_items AS t1
INNER JOIN inventories as inv ON inv.id=t1._item_id
INNER JOIN individual_replace_masters AS t2 ON t1._no=t2.id 
 where t1._barcode LIKE '%$_barcode%'
UNION ALL
SELECT '9.In For Rep.' AS _type,'individual_replace_masters' as _table_name,t2._supplier_id as _ledger_id,t2.id,t1._barcode,inv._warranty,t2._date,t1._item_id,t1._qty,t1._rate,t1._sales_rate,t1._store_id,t1._branch_id,t1._no 
FROM individual_replace_in_items AS t1
INNER JOIN inventories as inv ON inv.id=t1._item_id
INNER JOIN individual_replace_masters AS t2 ON t1._no=t2.id 
 where t1._barcode LIKE '%$_barcode%'
UNION ALL
SELECT '10.Rep Delivery' AS _type,'individual_replace_masters' as _table_name,t2._supplier_id as _ledger_id,t2.id,t1._barcode,inv._warranty,t2._date,t1._item_id,t1._qty,t1._rate,t1._sales_rate,t1._store_id,t1._branch_id,t1._no 
FROM individual_replace_out_items AS t1
INNER JOIN inventories as inv ON inv.id=t1._item_id
INNER JOIN individual_replace_masters AS t2 ON t1._no=t2.id 
 where t1._barcode LIKE '%$_barcode%'

  ) as s1
 LEFT JOIN warranties as s4 on s4.id=s1._warranty
  ORDER BY s1._type ASC
  ");
  //return $datas;
        return view('backend.inventory-report.report_barcode_history',compact('request','page_name','previous_filter','datas'));
    }


    public function resetBarcodeHistory(){
        Session::flash('barcode_history');
        return redirect()->back();
    }


     public function filterBillOfPartyStatement(Request $request){

        $previous_filter= Session::get('bill_of_party_statement');
        $page_name = "Bill of Party Statement";

        $exixting_groups = \DB::select("SELECT DISTINCT `_account_group` FROM `accounts` WHERE 1");
        $group_ids = [];
        foreach ($exixting_groups as $key => $value) {
         array_push($group_ids, $value->_account_group);
        }
        $account_groups = AccountGroup::select('id','_name')
                                        ->where('_show_filter',1)
                                        ->whereIn('id',$group_ids)
                                        ->orderBy('_name','ASC')
                                        ->get();
        $users = Auth::user();
        $permited_branch = permited_branch(explode(',',$users->branch_ids));
        $permited_costcenters = permited_costcenters(explode(',',$users->cost_center_ids));


         
        return view('backend.inventory-report.filter_bill_of_party_statement',compact('request','page_name','account_groups','previous_filter','permited_branch','permited_costcenters'));
    }


    public function reportBillOfPartyStatement(Request $request){
      $this->validate($request, [
            '_datex' => 'required',
            '_account_ledger_id' => 'required',
            '_datey' => 'required'
        ]);

        session()->put('bill_of_party_statement', $request->all());
        $previous_filter= Session::get('bill_of_party_statement');
        $page_name = "Bill of Party Statement";
        
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
SELECT s1._sl, s1._account_group,s1._group_name, s1._account_ledger,s1._l_name,s1._branch_id,s1._cost_center, s1._branch_name, s1._id,s1._table_name, s1._date, s1._short_narration, s1._narration, s1._dr_amount, s1._cr_amount, s1._balance  FROM (
      SELECT t1._status,null as _sl, t1._account_group AS _account_group,t2._name as _group_name, t1._account_ledger AS _account_ledger,t3._name as _l_name,t1._branch_id AS _branch_id,t1._cost_center as _cost_center, t4._name as _branch_name, null as _id,null as _table_name, '".$_datex."' as _date, null as _short_narration, 'Opening Balance' as _narration, 0 AS _dr_amount, 0  AS _cr_amount, SUM(t1._dr_amount-t1._cr_amount) AS _balance  
            FROM accounts as t1
            INNER JOIN account_groups as t2 ON t2.id=t1._account_group
            INNER JOIN account_ledgers as t3 ON t3.id=t1._account_ledger
            INNER JOIN branches as t4 ON t4.id = t1._branch_id
               WHERE t1._status=1 AND t1._date < '".$_datex."' AND t1._account_ledger IN(".$ledger_id_rows.") AND t1.organization_id IN(".$_organization_id_rows.")
               AND  t1._branch_id IN(".$_branch_ids_rows.") AND  t1._cost_center IN(".$_cost_center_id_rows.")
                 GROUP BY t1._account_ledger
      UNION ALL
            SELECT t1._status, t1.id as _sl, t1._account_group AS _account_group,t2._name as _group_name, t1._account_ledger AS _account_ledger,t3._name as _l_name,t1._branch_id AS _branch_id,t1._cost_center as _cost_center, t4._name as _branch_name, t1._ref_master_id as _id, t1._table_name AS _table_name, t1._date as _date, t1._short_narration as _short_narration, t1._narration as _narration, t1._dr_amount AS _dr_amount, t1._cr_amount  AS _cr_amount, 0 AS _balance 
            FROM accounts AS t1
            INNER JOIN account_groups as t2 ON t2.id=t1._account_group
            INNER JOIN account_ledgers as t3 ON t3.id=t1._account_ledger
            INNER JOIN branches as t4 ON t4.id = t1._branch_id
              WHERE  t1._status=1 AND t1._date  >= '".$_datex."'  AND t1._date <= '".$_datey."' 
              AND t1._account_ledger IN(".$ledger_id_rows.") AND t1.organization_id IN(".$_organization_id_rows.") AND  t1._branch_id IN(".$_branch_ids_rows.") AND  t1._cost_center IN(".$_cost_center_id_rows.") 

              ) as s1 WHERE s1._status=1 order by s1._date,s1._sl ASC  ";

       $datas = DB::select($string_query);
       $group_array_values = array();
       foreach ($datas as $value) {
           $group_array_values[$value->_group_name][$value->_l_name][]=$value;
       }

}else{
   $group_array_values = array();
}
        //return $group_array_values;
        return view('backend.inventory-report.report_bill_of_party_statement',compact('request','page_name','group_array_values','basic_information','_datex','_datey','previous_filter','permited_branch','permited_costcenters'));
    }


    public function resetBillOfPartyStatement(){
        Session::flash('bill_of_party_statement');
        return redirect()->back();
    }


     public function filterDateWisePurchaseStatement(Request $request){

        $previous_filter= Session::get('date_wise_purchase_statement');
        $page_name = "Date Wise Purchase Statement";
        $account_groups = DB::select(" SELECT DISTINCT t2._account_group_id as _ledger_id,t3.id,t3._name AS _name
                                      FROM purchases AS t1
                                      INNER JOIN account_ledgers AS t2 ON t2.id=t1._ledger_id
                                      INNER JOIN account_groups AS t3 ON t3.id=t2._account_group_id
                                      ORDER BY t3._name ASC ");
        $users = Auth::user();
        $permited_branch = permited_branch(explode(',',$users->branch_ids));
        $permited_costcenters = permited_costcenters(explode(',',$users->cost_center_ids));


         
        return view('backend.inventory-report.filter_date_wise_purchase_statement',compact('request','page_name','account_groups','previous_filter','permited_branch','permited_costcenters'));
    }


    public function reportDateWisePurchaseStatement(Request $request){
      $this->validate($request, [
            '_datex' => 'required',
            '_account_ledger_id' => 'required',
            '_datey' => 'required'
        ]);

        session()->put('date_wise_purchase_statement', $request->all());
        $previous_filter= Session::get('date_wise_purchase_statement');
        $page_name = "Date Wise Purchase Statement";
        
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
     // return $request->all();

       $datas = Purchase::with(['_ledger','_master_details'])
                ->whereIn('_branch_id', $_branch_ids)
                ->whereIn('organization_id', $_organization_ids)
                ->whereIn('_cost_center_id', $_cost_center_ids)
                ->whereIn('_ledger_id', $ledger_ids)
                ->whereDate('_date','>=',$_datex)
                ->whereDate('_date','<=',$_datey)
                ->get();
       $group_array_values = array();
       foreach ($datas as $value) {
           $group_array_values[$value->_ledger->account_group->_name][$value->_ledger->_name][]=$value;
       }
  //return $group_array_values;

}else{
   $group_array_values = array();
}
        //return $group_array_values;
        return view('backend.inventory-report.report_date_wise_purchase_statement',compact('request','page_name','group_array_values','basic_information','_datex','_datey','previous_filter','permited_branch','permited_costcenters'));
    }


    public function resetDateWisePurchaseStatement(){
        Session::flash('date_wise_purchase_statement');
        return redirect()->back();
    }



     public function filterDateWisePurchaseReturnStatement(Request $request){

        $previous_filter= Session::get('date_wise_purchase_return_statement');
        $page_name = "Date Wise Purchase Return Statement";
        $account_groups = DB::select(" SELECT DISTINCT t2._account_group_id as _ledger_id ,t3.id,t3._name AS _name
                                      FROM purchase_returns AS t1
                                      INNER JOIN account_ledgers AS t2 ON t2.id=t1._ledger_id
                                      INNER JOIN account_groups AS t3 ON t3.id=t2._account_group_id
                                      ORDER BY t3._name ASC ");
        $users = Auth::user();
        $permited_branch = permited_branch(explode(',',$users->branch_ids));
        $permited_costcenters = permited_costcenters(explode(',',$users->cost_center_ids));


         
        return view('backend.inventory-report.filter_date_wise_purchase_return_statement',compact('request','page_name','account_groups','previous_filter','permited_branch','permited_costcenters'));
    }


    public function reportDateWisePurchaseReturnStatement(Request $request){
      $this->validate($request, [
            '_datex' => 'required',
            '_account_ledger_id' => 'required',
            '_datey' => 'required'
        ]);

        session()->put('date_wise_purchase_return_statement', $request->all());
        $previous_filter= Session::get('date_wise_purchase_return_statement');
        $page_name = "Date Wise Purchase Return Statement";
        
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
     // return $request->all();

       $datas = PurchaseReturn::with(['_ledger','_master_details'])
               ->whereIn('organization_id', $_organization_ids)
                ->whereIn('_branch_id', $_branch_ids)
                ->whereIn('_cost_center_id', $_cost_center_ids)
                ->whereIn('_ledger_id', $ledger_ids)
                ->whereDate('_date','>=',$_datex)
                ->whereDate('_date','<=',$_datey)
                ->get();
       $group_array_values = array();
       foreach ($datas as $value) {
           $group_array_values[$value->_ledger->account_group->_name][$value->_ledger->_name][]=$value;
       }
  //return $group_array_values;

}else{
   $group_array_values = array();
}
        //return $group_array_values;
        return view('backend.inventory-report.report_date_wise_purchase_return_statement',compact('request','page_name','group_array_values','basic_information','_datex','_datey','previous_filter','permited_branch','permited_costcenters'));
    }


    public function resetDateWisePurchaseReturnStatement(){
        Session::flash('date_wise_purchase_return_statement');
        return redirect()->back();
    }

     public function filterDateWiseSalesStatement(Request $request){

        $previous_filter= Session::get('date_wise_sales_statement');
        $page_name = "Date Wise Sales Statement";
        $account_groups = DB::select(" SELECT DISTINCT t2._account_group_id as _ledger_id,t3.id,t3._name AS _name
                                      FROM sales AS t1
                                      INNER JOIN account_ledgers AS t2 ON t2.id=t1._ledger_id
                                      INNER JOIN account_groups AS t3 ON t3.id=t2._account_group_id
                                      ORDER BY t3._name ASC ");
        $users = Auth::user();
        $permited_branch = permited_branch(explode(',',$users->branch_ids));
        $permited_costcenters = permited_costcenters(explode(',',$users->cost_center_ids));


         
        return view('backend.inventory-report.filter_date_wise_sales_statement',compact('request','page_name','account_groups','previous_filter','permited_branch','permited_costcenters'));
    }


    public function reportDateWiseSalesStatement(Request $request){
      $this->validate($request, [
            '_datex' => 'required',
            '_account_ledger_id' => 'required',
            '_datey' => 'required'
        ]);

        session()->put('date_wise_sales_statement', $request->all());
        $previous_filter= Session::get('date_wise_sales_statement');
        $page_name = "Date Wise Sales Statement";
        
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
     // return $request->all();

       $datas = Sales::with(['_ledger','_master_details'])
                ->whereIn('organization_id', $_organization_ids)
                ->whereIn('_branch_id', $_branch_ids)
                ->whereIn('_cost_center_id', $_cost_center_ids)
                ->whereIn('_ledger_id', $ledger_ids)
                ->whereDate('_date','>=',$_datex)
                ->whereDate('_date','<=',$_datey)
                ->get();
       $group_array_values = array();
       foreach ($datas as $value) {
           $group_array_values[$value->_ledger->account_group->_name][$value->_ledger->_name][]=$value;
       }
  //return $group_array_values;

}else{
   $group_array_values = array();
}
        //return $group_array_values;
        return view('backend.inventory-report.report_date_wise_sales_statement',compact('request','page_name','group_array_values','basic_information','_datex','_datey','previous_filter','permited_branch','permited_costcenters'));
    }


    public function resetDateWiseSalesStatement(){
        Session::flash('date_wise_sales_statement');
        return redirect()->back();
    }

    public function filterActualSales(Request $request){

        $previous_filter= Session::get('actual_sales');
        $page_name = "Date Wise Sales Statement";
        $account_groups = DB::select(" SELECT DISTINCT t2._account_group_id as _ledger_id,t3.id,t3._name AS _name
                                      FROM sales AS t1
                                      INNER JOIN account_ledgers AS t2 ON t2.id=t1._ledger_id
                                      INNER JOIN account_groups AS t3 ON t3.id=t2._account_group_id
                                      ORDER BY t3._name ASC ");
        $users = Auth::user();
        $permited_branch = permited_branch(explode(',',$users->branch_ids));
        $permited_costcenters = permited_costcenters(explode(',',$users->cost_center_ids));


         
        return view('backend.inventory-report.filter_actual_sales',compact('request','page_name','account_groups','previous_filter','permited_branch','permited_costcenters'));
    }

    public function reportActualSales(Request $request){
      $this->validate($request, [
            '_datex' => 'required',
            '_account_ledger_id' => 'required',
            '_datey' => 'required'
        ]);

        session()->put('actual_sales', $request->all());
        $previous_filter= Session::get('actual_sales');
        $page_name = "Actual Sales Report";
        
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
     // return $request->all();

       $datas = Sales::with(['_ledger','_master_details','_sales_return'])
                ->whereIn('organization_id', $_organization_ids)
                ->whereIn('_branch_id', $_branch_ids)
                ->whereIn('_cost_center_id', $_cost_center_ids)
                ->whereIn('_ledger_id', $ledger_ids)
                ->whereDate('_date','>=',$_datex)
                ->whereDate('_date','<=',$_datey)
                ->get();
       $group_array_values = array();
       foreach ($datas as $value) {
           $group_array_values[$value->_ledger->account_group->_name][$value->_ledger->_name][]=$value;
       }
  //return $group_array_values;

}else{
   $group_array_values = array();
}
        //return $group_array_values;
        return view('backend.inventory-report.report_actual_sales',compact('request','page_name','group_array_values','basic_information','_datex','_datey','previous_filter','permited_branch','permited_costcenters'));
    }

     public function resetActualSales(){
        Session::flash('actual_sales');
        return redirect()->back();
    }





    //Filter Date wise Restaurant Sals Statement
     public function filterDateWiseRestaurantSalesStatement(Request $request){

        $previous_filter= Session::get('date_wise_restaurant_sales_statement');
        $page_name = "Date Wise Sales Restaurant Statement";
        $account_groups = DB::select(" SELECT DISTINCT t2._account_group_id as _ledger_id,t3.id,t3._name AS _name
                                      FROM resturant_sales  AS t1
                                      INNER JOIN account_ledgers AS t2 ON t2.id=t1._ledger_id
                                      INNER JOIN account_groups AS t3 ON t3.id=t2._account_group_id
                                      ORDER BY t3._name ASC ");
        $users = Auth::user();
        $permited_branch = permited_branch(explode(',',$users->branch_ids));
        $permited_costcenters = permited_costcenters(explode(',',$users->cost_center_ids));


         
        return view('backend.inventory-report.filter_date_wise_restaurant_sales_statement',compact('request','page_name','account_groups','previous_filter','permited_branch','permited_costcenters'));
    }


    public function reportDateWiseRestaurantSalesStatement(Request $request){
      $this->validate($request, [
            '_datex' => 'required',
            '_account_ledger_id' => 'required',
            '_datey' => 'required'
        ]);

        session()->put('date_wise_restaurant_sales_statement', $request->all());
        $previous_filter= Session::get('date_wise_restaurant_sales_statement');
        $page_name = "Date Wise Sales Restaurant Statement";
        
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
     // return $request->all();

       $datas = ResturantSales::with(['_ledger','_master_details'])
                ->whereIn('organization_id', $_organization_ids)
                ->whereIn('_branch_id', $_branch_ids)
                ->whereIn('_cost_center_id', $_cost_center_ids)
                ->whereIn('_ledger_id', $ledger_ids)
                ->whereDate('_date','>=',$_datex)
                ->whereDate('_date','<=',$_datey)
                ->get();
       $group_array_values = array();
       foreach ($datas as $value) {
           $group_array_values[$value->_ledger->account_group->_name][$value->_ledger->_name][]=$value;
       }
  //return $group_array_values;

}else{
   $group_array_values = array();
}
        //return $group_array_values;
        return view('backend.inventory-report.report_date_wise_restaurant_sales_statement',compact('request','page_name','group_array_values','basic_information','_datex','_datey','previous_filter','permited_branch','permited_costcenters'));
    }


    public function resetDateWiseRestaurantSalesStatement(){
        Session::flash('date_wise_restaurant_sales_statement');
        return redirect()->back();
    }


     public function filterDateWiseSalesReturnStatement(Request $request){

        $previous_filter= Session::get('date_wise_sales_return_statement');
        $page_name = "Date Wise Sales Return Statement";
        $account_groups = DB::select(" SELECT DISTINCT t2._account_group_id as _ledger_id,t3.id,t3._name AS _name
                                      FROM sales_returns AS t1
                                      INNER JOIN account_ledgers AS t2 ON t2.id=t1._ledger_id
                                      INNER JOIN account_groups AS t3 ON t3.id=t2._account_group_id
                                      ORDER BY t3._name ASC ");
        $users = Auth::user();
        $permited_branch = permited_branch(explode(',',$users->branch_ids));
        $permited_costcenters = permited_costcenters(explode(',',$users->cost_center_ids));


         
        return view('backend.inventory-report.filter_date_wise_sales_return_statement',compact('request','page_name','account_groups','previous_filter','permited_branch','permited_costcenters'));
    }


    public function reportDateWiseSalesReturnStatement(Request $request){
      $this->validate($request, [
            '_datex' => 'required',
            '_account_ledger_id' => 'required',
            '_datey' => 'required'
        ]);

        session()->put('date_wise_sales_return_statement', $request->all());
        $previous_filter= Session::get('date_wise_sales_return_statement');
        $page_name = "Date Wise Sales Return Statement";
        
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
       $datas = SalesReturn::with(['_ledger','_master_details'])
                ->whereIn('organization_id', $_organization_ids)
                ->whereIn('_branch_id', $_branch_ids)
                ->whereIn('_cost_center_id', $_cost_center_ids)
                ->whereIn('_ledger_id', $ledger_ids)
                ->whereDate('_date','>=',$_datex)
                ->whereDate('_date','<=',$_datey)
                ->get();
       $group_array_values = array();
       foreach ($datas as $value) {
           $group_array_values[$value->_ledger->account_group->_name][$value->_ledger->_name][]=$value;
       }
  //return $group_array_values;

}else{
   $group_array_values = array();
}
        //return $group_array_values;
        return view('backend.inventory-report.report_date_wise_sales_return_statement',compact('request','page_name','group_array_values','basic_information','_datex','_datey','previous_filter','permited_branch','permited_costcenters'));
    }


    public function resetDateWiseSalesReturnStatement(){
      
        Session::flash('date_wise_sales_return_statement');
        return redirect()->back();
    }


public function filterStockPossition(Request $request){
      $previous_filter= Session::get('filter_stock_possition');
      $page_name = "Stock Position";
      $users = Auth::user();
      $permited_branch = permited_branch(explode(',',$users->branch_ids));
      $permited_costcenters = permited_costcenters(explode(',',$users->cost_center_ids));
      $datas=[];
      $_datex =  change_date_format($request->_datex);
      $_datey=  change_date_format($request->_datey);
      $stores = StoreHouse::get();
      $categories = DB::select( " SELECT DISTINCT t1._category_id FROM item_inventories AS t1" );
      $_categories_ids = [];
      foreach ($categories as $value) {
        array_push($_categories_ids, intval($value->_category_id));
      }
     $_item_categories = ItemCategory::with(['_parents'])->whereIn('id',$_categories_ids)->get();
      
        return view('backend.inventory-report.filter_stock_possition',compact('page_name','previous_filter','permited_branch','permited_costcenters','_datex','_datey','request','stores','_item_categories'));
}


public function reportStockPossition(Request $request){
      $this->validate($request, [
            '_datex' => 'required',
            '_item_category' => 'required',
            '_datey' => 'required'
        ]);

        session()->put('filter_stock_possition', $request->all());
        $previous_filter= Session::get('filter_stock_possition');
        $page_name = "Stock Position";
        
        $users = Auth::user();
        $permited_branch = permited_branch(explode(',',$users->branch_ids));
        $permited_costcenters = permited_costcenters(explode(',',$users->cost_center_ids));
        $datas=[];
        $_datex =  change_date_format($request->_datex);
        $_datey=  change_date_format($request->_datey);

        $category_ids = array();
        $_item_categorys = $request->_item_category ?? [];
        if(sizeof($_item_categorys) > 0){
            foreach ($_item_categorys as $value) {
                array_push($category_ids, (int) $value);
            }
        }

        $_items_ids = array();
        $_items = (array) $request->_item_id;
        if(sizeof($_items) > 0){
            foreach ($_items as $value) {
                array_push($_items_ids, (int) $value);
            }

        }else{
            $basic_information = Inventory::select('id')->whereIn('_category_id',$_item_categorys)->get();
            foreach ($basic_information as $value) {
                array_push($_items_ids, (int) $value->id);
            }
        }

     

      $request_branchs = $request->_branch_id ?? [];
      $request_cost_centers = $request->_cost_center ?? [];
      $_stores = $request->_store ?? [];
      if(sizeof($_stores) ==0){
        $stores_all = StoreHouse::get();
        foreach ($stores_all as $value) {
          array_push($_stores, (int) $value->id);
        }
      }


      $_branch_ids = filterableBranch($request_branchs,$permited_branch);
      $_cost_center_ids = filterableCostCenter($request_cost_centers,$permited_costcenters);

      $_items_ids_rows = implode(',', $_items_ids);
      $_branch_ids_rows = implode(',', $_branch_ids);
      $_cost_center_id_rows = implode(',', $_cost_center_ids);
      $_stores_id_rows = implode(',', $_stores);
      $category_ids_rows = implode(',', $category_ids);
      $_with_zero_qty = $request->_with_zero ?? 0;

      $request_organizations = $request->organization_id ?? [];
        $permited_organizations = permited_organization(explode(',',$users->organization_ids));
        $_organization_ids = filterableOrganization($request_organizations,$permited_organizations);
        $_organization_id_rows = implode(',', $_organization_ids);
        //AND t1.organization_id IN(".$_organization_id_rows.")
      
     if($_items_ids){

      $_string_query = "  SELECT  s1._item_id,s1._name,s1._category_id,t3._name as _unit,s1._unit_id,s1._store_id,s1._branch_id, s1._cost_center_id, SUM(s1._opening) AS _opening,SUM(s1._stockin) as _stockin,SUM(s1._stockout) AS _stockout
      FROM (
      SELECT t1._item_id,t1._item_name as _name,t1._category_id,t1._unit_id,t1._store_id,t1._branch_id, t1._cost_center_id, SUM(IFNULL(t1._qty,0)) AS _opening,0 as _stockin,0 AS _stockout 
        FROM item_inventories as t1 
        WHERE  t1._status=1 AND t1._date < '".$_datex."' AND t1.organization_id IN(".$_organization_id_rows.") AND  t1._branch_id IN(".$_branch_ids_rows.") AND  t1._cost_center_id IN(".$_cost_center_id_rows.") AND t1._item_id IN(".$_items_ids_rows.")
        AND t1._store_id IN(".$_stores_id_rows.") AND t1._category_id IN(".$category_ids_rows.")
        GROUP BY t1._branch_id,t1._cost_center_id,t1._store_id,t1._category_id,t1._item_id 
      UNION ALL
      SELECT t1._item_id,t1._item_name as _name,t1._category_id,t1._unit_id,t1._store_id,t1._branch_id, t1._cost_center_id, 0 AS _opening, SUM(IF((t1._qty > 0), t1._qty,0  )) as _stockin,SUM(IF((t1._qty < 0), t1._qty,0  )) AS _stockout 
        FROM item_inventories as t1 
        WHERE  t1._status=1 AND t1._date  >= '".$_datex."'  AND t1._date <= '".$_datey."' 
           AND t1.organization_id IN(".$_organization_id_rows.")   AND  t1._branch_id IN(".$_branch_ids_rows.") AND  t1._cost_center_id IN(".$_cost_center_id_rows.") AND t1._item_id IN(".$_items_ids_rows.")
        AND t1._store_id IN(".$_stores_id_rows.") AND t1._category_id IN(".$category_ids_rows.")
        GROUP BY t1._branch_id,t1._cost_center_id,t1._store_id,t1._category_id,t1._item_id 
    ) as s1
    inner join units as t3 ON t3.id=s1._unit_id
     GROUP BY s1._branch_id,s1._cost_center_id,s1._store_id,s1._category_id,s1._item_id  ";
     if($_with_zero_qty ==1){
       $_string_query .= " HAVING (SUM(s1._stockin+s1._stockout+s1._opening) > 0) ";
     }

      $datas = DB::select($_string_query);

       
       $group_array_values =array();
      // foreach ($datas as $value) {
      //   $group_array_values[$value->_branch_id."__".$value->_cost_center_id."__".$value->_store_id."__".$value->_category_id][]=$value;
      // }

      foreach ($datas as $value) {
        $group_array_values[$value->_branch_id][$value->_cost_center_id][$value->_store_id][$value->_category_id][]=$value;
      }

}else{
   $group_array_values = array();
}
      // return $group_array_values;
        return view('backend.inventory-report.report_stock_possition',compact('request','page_name','group_array_values','_datex','_datey','previous_filter','permited_branch','permited_costcenters','_branch_ids','_cost_center_ids','_stores','category_ids'));
    }

public function stockPossitionCatItem(Request $request){
  $category_id = $request->_category_id;
  $data = Inventory::whereIn('_category_id',$category_id)->select('id','_item')->get();
  return view('backend.item-category.stock_possition_cat_base_item',compact('data'));
}

public function resetStockPossition(){
  Session::flash('filter_stock_possition');
  return redirect()->back();
}



public function filterStockLedger(Request $request){
      $previous_filter= Session::get('filter_stock_ledger');
      $page_name = "Stock Ledger";
      $users = Auth::user();
      $permited_branch = permited_branch(explode(',',$users->branch_ids));
      $permited_costcenters = permited_costcenters(explode(',',$users->cost_center_ids));
      $datas=[];
      $_datex =  change_date_format($request->_datex);
      $_datey=  change_date_format($request->_datey);
      $stores = StoreHouse::get();
       $categories = DB::select( " SELECT DISTINCT t1._category_id FROM item_inventories AS t1" );
      $_categories_ids = [];
      foreach ($categories as $value) {
        array_push($_categories_ids, intval($value->_category_id));
      }
     $_item_categories = ItemCategory::with(['_parents'])->whereIn('id',$_categories_ids)->get();
      
        return view('backend.inventory-report.filter_stock_ledger',compact('page_name','previous_filter','permited_branch','permited_costcenters','_datex','_datey','request','stores','_item_categories'));
}

public function reportStockLedger(Request $request){
      $this->validate($request, [
            '_datex' => 'required',
            '_item_category' => 'required',
            '_datey' => 'required'
        ]);

        session()->put('filter_stock_ledger', $request->all());
        $previous_filter= Session::get('filter_stock_ledger');
        $page_name = "Stock Ledger";
        
        $users = Auth::user();
        $permited_branch = permited_branch(explode(',',$users->branch_ids));
        $permited_costcenters = permited_costcenters(explode(',',$users->cost_center_ids));
        $datas=[];
        $_datex =  change_date_format($request->_datex);
        $_datey=  change_date_format($request->_datey);

        $category_ids = array();
        $_item_categorys = $request->_item_category ?? [];
        if(sizeof($_item_categorys) > 0){
            foreach ($_item_categorys as $value) {
                array_push($category_ids, (int) $value);
            }
        }

        $_items_ids = array();
        $_items = (array) $request->_item_id;
        if(sizeof($_items) > 0){
            foreach ($_items as $value) {
                array_push($_items_ids, (int) $value);
            }

        }else{
            $basic_information = Inventory::select('id')->whereIn('_category_id',$_item_categorys)->get();
            foreach ($basic_information as $value) {
                array_push($_items_ids, (int) $value->id);
            }
        }

     

      $request_branchs = $request->_branch_id ?? [];
      $request_cost_centers = $request->_cost_center ?? [];
      $_stores = $request->_store ?? [];
      if(sizeof($_stores) ==0){
        $stores_all = StoreHouse::get();
        foreach ($stores_all as $value) {
          array_push($_stores, (int) $value->id);
        }
      }


      $_branch_ids = filterableBranch($request_branchs,$permited_branch);
      $_cost_center_ids = filterableCostCenter($request_cost_centers,$permited_costcenters);

      $_items_ids_rows = implode(',', $_items_ids);
      $_branch_ids_rows = implode(',', $_branch_ids);
      $_cost_center_id_rows = implode(',', $_cost_center_ids);
      $_stores_id_rows = implode(',', $_stores);
      $category_ids_rows = implode(',', $category_ids);


      $request_organizations = $request->organization_id ?? [];
        $permited_organizations = permited_organization(explode(',',$users->organization_ids));
        $_organization_ids = filterableOrganization($request_organizations,$permited_organizations);
        $_organization_id_rows = implode(',', $_organization_ids);
        //AND t1.organization_id IN(".$_organization_id_rows.")
      
     if($_items_ids){

      
      $datas = DB::select("  

      SELECT '' as id, ".$_datex." as _date,'' as _transection_ref ,'Opening' as _transection,t1._item_id,t1._item_name as _name,t1._category_id,t1._unit_id,t1._store_id,t1._branch_id, t1._cost_center_id, 0 as _stockin,0 AS _stockout,SUM(IFNULL(t1._qty,0)) as _balance 
        FROM item_inventories as t1 
        WHERE  t1._status=1 AND  t1._date < '".$_datex."' AND t1.organization_id IN(".$_organization_id_rows.") AND  t1._branch_id IN(".$_branch_ids_rows.") 
        AND t1._store_id IN(".$_stores_id_rows.") AND t1._category_id IN(".$category_ids_rows.")
        AND t1._item_id IN(".$_items_ids_rows.")
        GROUP BY t1._branch_id,t1._cost_center_id,t1._store_id,t1._category_id,t1._item_id
        
 UNION ALL
 SELECT t1.id, t1._date, t1._transection_ref as _transection_ref  ,t1._transection as _transection,t1._item_id,t1._item_name as _name,t1._category_id,t1._unit_id,t1._store_id,t1._branch_id, t1._cost_center_id, SUM(IF((t1._qty > 0), t1._qty,0  )) as _stockin, SUM(IF((t1._qty < 0), t1._qty,0  )) AS _stockout,SUM(IFNULL(t1._qty,0)) as _balance 
        FROM item_inventories as t1 
        WHERE  t1._status=1 AND   t1._date  >= '".$_datex."'  AND t1._date <= '".$_datey."' AND t1.organization_id IN(".$_organization_id_rows.")
        AND  t1._branch_id IN(".$_branch_ids_rows.") 
        AND t1._store_id IN(".$_stores_id_rows.") AND t1._category_id IN(".$category_ids_rows.")
        AND t1._item_id IN(".$_items_ids_rows.")
        GROUP BY t1.id  


      ");
    $group_array_values =array();
      foreach ($datas as $value) {
        $group_array_values[$value->_branch_id."__".$value->_cost_center_id."__".$value->_store_id."__".$value->_category_id."__".$value->_item_id][]=$value;
      }

      //   foreach ($datas as $value) {
      //   $group_array_values[$value->_branch_id][$value->_cost_center_id][$value->_store_id][$value->_category_id][$value->_item_id][]=$value;
      // }
       

}else{
   $group_array_values = array();
}
      // return $group_array_values;
        return view('backend.inventory-report.report_stock_ledger',compact('request','page_name','group_array_values','_datex','_datey','previous_filter','permited_branch','permited_costcenters','_branch_ids','_cost_center_ids','_stores','category_ids'));
    }
public function resetStockLedger(){
  Session::flash('filter_stock_ledger');
  return redirect()->back();
}


public function filterStockLedgerHistory(Request $request){
      $previous_filter= Session::get('filter_stock_ledger_history');
      $page_name = "Stock Ledger History";
      $users = Auth::user();
      $permited_branch = permited_branch(explode(',',$users->branch_ids));
      $permited_costcenters = permited_costcenters(explode(',',$users->cost_center_ids));
      $datas=[];
      $_datex =  change_date_format($request->_datex);
      $_datey=  change_date_format($request->_datey);
      $stores = StoreHouse::get();
       $categories = DB::select( " SELECT DISTINCT t1._category_id FROM item_inventories AS t1" );
      $_categories_ids = [];
      foreach ($categories as $value) {
        array_push($_categories_ids, intval($value->_category_id));
      }
     $_item_categories = ItemCategory::with(['_parents'])->whereIn('id',$_categories_ids)->get();
      
        return view('backend.inventory-report.filter_stock_ledger_history',compact('page_name','previous_filter','permited_branch','permited_costcenters','_datex','_datey','request','stores','_item_categories'));
}




public function reportStockLedgerHistory(Request $request){

    

      $this->validate($request, [
            '_item_id' => 'required',
        ]);

        session()->put('filter_stock_ledger_history', $request->all());
        $previous_filter= Session::get('filter_stock_ledger_history');
        $page_name = "Stock Ledger History";
        
        $users = Auth::user();
        $permited_branch = permited_branch(explode(',',$users->branch_ids));
        $permited_costcenters = permited_costcenters(explode(',',$users->cost_center_ids));
        $permited_organizations = permited_organization(explode(',',$users->cost_center_ids));

       
       

   
    $_items = $request->_item_id;
    $request_branchs = $request->_branch_id ?? [];
    $request_cost_centers = $request->_cost_center ?? [];
    $request_organization_ids = $request->organization_id ?? [];

      $_stores = $request->_store ?? [];
      if(sizeof($_stores) ==0){
        $stores_all = permited_stores(explode(',',$users->store_ids));
        foreach ($stores_all as $value) {
          array_push($_stores, (int) $value->id);
        }
      }


      $_branch_ids = filterableBranch($request_branchs,$permited_branch);
      $_cost_center_ids = filterableCostCenter($request_cost_centers,$permited_costcenters);
      

      
      $_branch_ids_rows = implode(',', $_branch_ids);
      $_cost_center_id_rows = implode(',', $_cost_center_ids);
      $_stores_id_rows = implode(',', $_stores);


      $request_organizations = $request->organization_id ?? [];
      $permited_organizations = permited_organization(explode(',',$users->organization_ids));
      $_organization_ids = filterableOrganization($request_organizations,$permited_organizations);
      $_organization_id_rows = implode(',', $_organization_ids);

       // $datas = PurchaseDetail::with(['_purchase_master','_purchase_barcode','_purchase_master','_lot_product_history','_purchase_return_details'])->where('_item_id',$request->_item_id)->get();
      $purchase_type = 0;
      $datas = ProductPriceList::with(['_purchase_detail','_lot_wise_sales_details','_lot_wise_sales_return_details','_purchase_detail' => function($q) use ($purchase_type){
        $q->where('_status', $purchase_type);
    }])->where('_item_id',$request->_item_id)->get();
      
      
return $datas;
die();

     
        return view('backend.inventory-report.report_stock_ledger_history',compact('request','page_name','previous_filter','datas'));
    }



public function resetStockLedgerHistory(){
  Session::flash('filter_stock_ledger_history');
  return redirect()->back();
}




public function filterSingleStockLedger(Request $request){
      $previous_filter= Session::get('filter_single_stock_ledger');
      $page_name = "Single Item Stock Ledger";
      $users = Auth::user();
      $permited_branch = permited_branch(explode(',',$users->branch_ids));
      $permited_costcenters = permited_costcenters(explode(',',$users->cost_center_ids));
      $datas=[];
      $_datex =  change_date_format($request->_datex);
      $_datey=  change_date_format($request->_datey);
      $stores = StoreHouse::get();
       
       $items = DB::select( " SELECT DISTINCT t1._item_id,t1._item_name FROM item_inventories AS t1" );
        return view('backend.inventory-report.filter_single_stock_ledger',compact('page_name','previous_filter','permited_branch','permited_costcenters','_datex','_datey','request','stores','items'));
}

public function reportSingleStockLedger(Request $request){
      $this->validate($request, [
            '_datex' => 'required',
            '_item_id' => 'required',
            '_datey' => 'required'
        ]);

        session()->put('filter_single_stock_ledger', $request->all());
        $previous_filter= Session::get('filter_single_stock_ledger');
        $page_name = "Single Item Stock Ledger";
        
        $users = Auth::user();
        $permited_branch = permited_branch(explode(',',$users->branch_ids));
        $permited_costcenters = permited_costcenters(explode(',',$users->cost_center_ids));
        $datas=[];
        $_datex =  change_date_format($request->_datex);
        $_datey=  change_date_format($request->_datey);

        
        $category_ids=[];
        $_items_ids = array();
        $_items = (array) $request->_item_id;
        if(sizeof($_items) > 0){
            foreach ($_items as $value) {
                array_push($_items_ids, (int) $value);
            }

        }

     

      $request_branchs = $request->_branch_id ?? [];
      $request_cost_centers = $request->_cost_center ?? [];
      $_stores = $request->_store ?? [];
      if(sizeof($_stores) ==0){
        $stores_all = StoreHouse::get();
        foreach ($stores_all as $value) {
          array_push($_stores, (int) $value->id);
        }
      }


      $_branch_ids = filterableBranch($request_branchs,$permited_branch);
      $_cost_center_ids = filterableCostCenter($request_cost_centers,$permited_costcenters);

      $_items_ids_rows = implode(',', $_items_ids);
      $_branch_ids_rows = implode(',', $_branch_ids);
      $_cost_center_id_rows = implode(',', $_cost_center_ids);
      $_stores_id_rows = implode(',', $_stores);
      $category_ids_rows = implode(',', $category_ids);


        $request_organizations = $request->organization_id ?? [];
        $permited_organizations = permited_organization(explode(',',$users->organization_ids));
        $_organization_ids = filterableOrganization($request_organizations,$permited_organizations);
        $_organization_id_rows = implode(',', $_organization_ids);
        //AND t1.organization_id IN(".$_organization_id_rows.")
      
     if($_items_ids){

      
      $datas = DB::select("  

      SELECT '' as id, ".$_datex." as _date,'' as _transection_ref ,'Opening' as _transection,t1._item_id,t1._item_name as _name,t1._category_id,t1._unit_id,t1._store_id,t1._branch_id, t1._cost_center_id, 0 as _stockin,0 AS _stockout,SUM(IFNULL(t1._qty,0)) as _balance 
        FROM item_inventories as t1 
        WHERE  t1._status=1 AND  t1._date < '".$_datex."' AND t1.organization_id IN(".$_organization_id_rows.") AND  t1._branch_id IN(".$_branch_ids_rows.") 
        AND t1._store_id IN(".$_stores_id_rows.") 
        AND t1._item_id IN(".$_items_ids_rows.")
        GROUP BY t1._branch_id,t1._cost_center_id,t1._store_id,t1._category_id,t1._item_id
        
 UNION ALL
 SELECT t1.id, t1._date, t1._transection_ref as _transection_ref  ,t1._transection as _transection,t1._item_id,t1._item_name as _name,t1._category_id,t1._unit_id,t1._store_id,t1._branch_id, t1._cost_center_id, SUM(IF((t1._qty > 0), t1._qty,0  )) as _stockin, SUM(IF((t1._qty < 0), t1._qty,0  )) AS _stockout,SUM(IFNULL(t1._qty,0)) as _balance 
        FROM item_inventories as t1 
        WHERE  t1._status=1 AND   t1._date  >= '".$_datex."'  AND t1._date <= '".$_datey."' AND t1.organization_id IN(".$_organization_id_rows.")
        AND  t1._branch_id IN(".$_branch_ids_rows.") 
        AND t1._store_id IN(".$_stores_id_rows.") 
        AND t1._item_id IN(".$_items_ids_rows.")
        GROUP BY t1.id  


      ");
    $group_array_values =array();
      foreach ($datas as $value) {
        $group_array_values[$value->_branch_id."__".$value->_cost_center_id."__".$value->_store_id."__".$value->_category_id."__".$value->_item_id][]=$value;
      }

      //   foreach ($datas as $value) {
      //   $group_array_values[$value->_branch_id][$value->_cost_center_id][$value->_store_id][$value->_category_id][$value->_item_id][]=$value;
      // }
       

}else{
   $group_array_values = array();
}
      // return $group_array_values;
        return view('backend.inventory-report.report_single_stock_ledger',compact('request','page_name','group_array_values','_datex','_datey','previous_filter','permited_branch','permited_costcenters','_branch_ids','_cost_center_ids','_stores','category_ids'));
    }
public function resetSingleStockLedger(){
  Session::flash('filter_single_stock_ledger');
  return redirect()->back();
}

public function stockLedgerCatItem(Request $request){
  $category_id = $request->_category_id;
  $data = Inventory::whereIn('_category_id',$category_id)->select('id','_item')->get();
  return view('backend.item-category.stock_ledger_cat_base_item',compact('data'));
}




public function filterStockValueRegister(Request $request){
      $previous_filter= Session::get('filter_stock_value_register');
      $page_name = "Stock Value Register";
      $users = Auth::user();
      $permited_branch = permited_branch(explode(',',$users->branch_ids));
      $permited_costcenters = permited_costcenters(explode(',',$users->cost_center_ids));
      $datas=[];
      $_datex =  change_date_format($request->_datex);
      $_datey=  change_date_format($request->_datey);
      $stores = StoreHouse::get();
       $categories = DB::select( " SELECT DISTINCT t1._category_id FROM item_inventories AS t1" );
      $_categories_ids = [];
      foreach ($categories as $value) {
        array_push($_categories_ids, intval($value->_category_id));
      }
     $_item_categories = ItemCategory::with(['_parents'])->whereIn('id',$_categories_ids)->get();
      
        return view('backend.inventory-report.filter_stock_value_register',compact('page_name','previous_filter','permited_branch','permited_costcenters','_datex','_datey','request','stores','_item_categories'));
}

public function reportStockValueRegister(Request $request){
      $this->validate($request, [
            '_datex' => 'required',
            '_item_category' => 'required'
        ]);

        session()->put('filter_stock_value_register', $request->all());
        $previous_filter= Session::get('filter_stock_value_register');
        $page_name = "Stock Value Register";
        
        $users = Auth::user();
        $permited_branch = permited_branch(explode(',',$users->branch_ids));
        $permited_costcenters = permited_costcenters(explode(',',$users->cost_center_ids));
        $datas=[];
        $_datex =  change_date_format($request->_datex);
        $_datey=  change_date_format($request->_datey);

        $category_ids = array();
        $_item_categorys = $request->_item_category ?? [];
        if(sizeof($_item_categorys) > 0){
            foreach ($_item_categorys as $value) {
                array_push($category_ids, (int) $value);
            }
        }

        $_items_ids = array();
        $_items = (array) $request->_item_id;
        if(sizeof($_items) > 0){
            foreach ($_items as $value) {
                array_push($_items_ids, (int) $value);
            }

        }else{
            $basic_information = Inventory::select('id')->whereIn('_category_id',$_item_categorys)->get();
            foreach ($basic_information as $value) {
                array_push($_items_ids, (int) $value->id);
            }
        }

     

      $request_branchs = $request->_branch_id ?? [];
      $request_cost_centers = $request->_cost_center ?? [];
      $_stores = $request->_store ?? [];
      if(sizeof($_stores) ==0){
        $stores_all = StoreHouse::get();
        foreach ($stores_all as $value) {
          array_push($_stores, (int) $value->id);
        }
      }


      $_branch_ids = filterableBranch($request_branchs,$permited_branch);
      $_cost_center_ids = filterableCostCenter($request_cost_centers,$permited_costcenters);

      $_items_ids_rows = implode(',', $_items_ids);
      $_branch_ids_rows = implode(',', $_branch_ids);
      $_cost_center_id_rows = implode(',', $_cost_center_ids);
      $_stores_id_rows = implode(',', $_stores);
      $category_ids_rows = implode(',', $category_ids);

      $request_organizations = $request->organization_id ?? [];
        $permited_organizations = permited_organization(explode(',',$users->organization_ids));
        $_organization_ids = filterableOrganization($request_organizations,$permited_organizations);
        $_organization_id_rows = implode(',', $_organization_ids);
        //AND t1.organization_id IN(".$_organization_id_rows.")
      
     if($_items_ids){

    
      $datas = DB::select("  SELECT t1._date, t1._transection_ref as _transection_ref  ,t1._transection as _transection,t1._item_id,t1._item_name as _name,t1._category_id,t1._unit_id,t1._store_id,t1._branch_id, t1._cost_center_id, SUM(IF((t1._qty > 0), t1._qty,0  )) as _stockin, SUM(IF((t1._qty < 0), t1._qty,0  )) AS _stockout,SUM(IFNULL(t1._qty,0)) as _balance 
        FROM item_inventories as t1 
        WHERE  t1._status=1 AND    t1._date <= '".$_datex."' AND t1.organization_id IN(".$_organization_id_rows.")
        AND  t1._branch_id IN(".$_branch_ids_rows.") 
        AND t1._store_id IN(".$_stores_id_rows.") AND t1._category_id IN(".$category_ids_rows.")
        AND t1._item_id IN(".$_items_ids_rows.") AND t1._transection IN('Purchase','Purchase Return')
        GROUP BY t1.id ORDER by t1.id ASC
      ");
    $group_array_values =array();
      foreach ($datas as $value) {
        $group_array_values[$value->_branch_id."__".$value->_cost_center_id."__".$value->_store_id."__".$value->_category_id."__".$value->_item_id][]=$value;
      }

       
       

}else{
   $group_array_values = array();
}
       //return $group_array_values;
        return view('backend.inventory-report.report_stock_value_register',compact('request','page_name','group_array_values','_datex','_datey','previous_filter','permited_branch','permited_costcenters','_branch_ids','_cost_center_ids','_stores','category_ids'));
    }

public function stockValueRegisterCatItem(Request $request){
  $category_id = $request->_category_id;
  $data = Inventory::whereIn('_category_id',$category_id)->select('id','_item')->get();
  return view('backend.item-category.stock_value_register_cat_base_item',compact('data'));
}

public function resetStockValueRegister(){
  Session::flash('filter_stock_value_register');
  return redirect()->back();
}

public function filterStockValue(Request $request){
      $previous_filter= Session::get('filter_stock_value');
      $page_name = "Stock Value";
      $users = Auth::user();
      $permited_branch = permited_branch(explode(',',$users->branch_ids));
      $permited_costcenters = permited_costcenters(explode(',',$users->cost_center_ids));
      $datas=[];
      $_datex =  change_date_format($request->_datex);
      $_datey=  change_date_format($request->_datey);
      $stores = StoreHouse::get();
      $categories = DB::select( " SELECT DISTINCT t1._category_id FROM item_inventories AS t1" );
      $_categories_ids = [];
      foreach ($categories as $value) {
        array_push($_categories_ids, intval($value->_category_id));
      }
     $_item_categories = ItemCategory::with(['_parents'])->whereIn('id',$_categories_ids)->get();
      
        return view('backend.inventory-report.filter_stock_value',compact('page_name','previous_filter','permited_branch','permited_costcenters','_datex','_datey','request','stores','_item_categories'));
}

public function reportStockValue(Request $request){
      $this->validate($request, [
            '_datex' => 'required',
            '_item_category' => 'required'
        ]);

        session()->put('filter_stock_value', $request->all());
        $previous_filter= Session::get('filter_stock_value');
        $page_name = "Stock Value";
        
        $users = Auth::user();
        $permited_branch = permited_branch(explode(',',$users->branch_ids));
        $permited_costcenters = permited_costcenters(explode(',',$users->cost_center_ids));
        $datas=[];
        $_datex =  change_date_format($request->_datex);
        $_datey=  change_date_format($request->_datey);

        $category_ids = array();
        $_item_categorys = $request->_item_category ?? [];
        if(sizeof($_item_categorys) > 0){
            foreach ($_item_categorys as $value) {
                array_push($category_ids, (int) $value);
            }
        }

        $_items_ids = array();
        $_items = (array) $request->_item_id;
        if(sizeof($_items) > 0){
            foreach ($_items as $value) {
                array_push($_items_ids, (int) $value);
            }

        }else{
            $basic_information = Inventory::select('id')->whereIn('_category_id',$_item_categorys)->get();
            foreach ($basic_information as $value) {
                array_push($_items_ids, (int) $value->id);
            }
        }

     

      $request_branchs = $request->_branch_id ?? [];
      $request_cost_centers = $request->_cost_center ?? [];
      $_stores = $request->_store ?? [];
      if(sizeof($_stores) ==0){
        $stores_all = StoreHouse::get();
        foreach ($stores_all as $value) {
          array_push($_stores, (int) $value->id);
        }
      }


      $_branch_ids = filterableBranch($request_branchs,$permited_branch);
      $_cost_center_ids = filterableCostCenter($request_cost_centers,$permited_costcenters);

      $_items_ids_rows = implode(',', $_items_ids);
      $_branch_ids_rows = implode(',', $_branch_ids);
      $_cost_center_id_rows = implode(',', $_cost_center_ids);
      $_stores_id_rows = implode(',', $_stores);
      $category_ids_rows = implode(',', $category_ids);


      $request_organizations = $request->organization_id ?? [];
        $permited_organizations = permited_organization(explode(',',$users->organization_ids));
        $_organization_ids = filterableOrganization($request_organizations,$permited_organizations);
        $_organization_id_rows = implode(',', $_organization_ids);
        //AND t1.organization_id IN(".$_organization_id_rows.")
      
     if($_items_ids){

      
      $datas = DB::select("  SELECT t1._item_id,t1._item_name as _name,t1._category_id,t1._unit_id,t1._store_id,t1._branch_id, t1._cost_center_id,SUM(IFNULL(t1._qty,0)) as _qty , avg( IF((t1._qty > 0), t1._cost_rate,0  ) ) as _cost_rate,SUM( t1._qty*t1._cost_rate ) as _cost_value
        FROM item_inventories as t1 
        WHERE  t1._status=1 AND  t1._date <= '".$_datex."' AND t1.organization_id IN(".$_organization_id_rows.") AND  t1._branch_id IN(".$_branch_ids_rows.") 
        AND t1._store_id IN(".$_stores_id_rows.") AND t1._category_id IN(".$category_ids_rows.")
        AND t1._item_id IN(".$_items_ids_rows.") AND t1._qty !=0

        GROUP BY t1._branch_id,t1._cost_center_id,t1._store_id,t1._category_id,t1._item_id
         HAVING(SUM(IFNULL(t1._qty,0)) > 0)

      ");
    $group_array_values =array();
      foreach ($datas as $value) {
        $group_array_values[$value->_branch_id."__".$value->_cost_center_id."__".$value->_store_id."__".$value->_category_id][]=$value;
      }

       
       

}else{
   $group_array_values = array();
}
       //return $group_array_values;
        return view('backend.inventory-report.report_stock_value',compact('request','page_name','group_array_values','_datex','_datey','previous_filter','permited_branch','permited_costcenters','_branch_ids','_cost_center_ids','_stores','category_ids'));
    }

public function stockValueCatItem(Request $request){
  $category_id = $request->_category_id;
  $data = Inventory::whereIn('_category_id',$category_id)->select('id','_item')->get();
  return view('backend.item-category.stock_value_cat_base_item',compact('data'));
}

public function resetStockValue(){
  Session::flash('filter_stock_value');
  return redirect()->back();
}

public function filterGrossProfit(Request $request){
      $previous_filter= Session::get('filter_gross_profit');
      $page_name = "Gross Profit";
      $users = Auth::user();
      $permited_branch = permited_branch(explode(',',$users->branch_ids));
      $permited_costcenters = permited_costcenters(explode(',',$users->cost_center_ids));
      $datas=[];
      $_datex =  change_date_format($request->_datex);
      $_datey=  change_date_format($request->_datey);
      $stores = StoreHouse::get();
       $categories = DB::select( " SELECT DISTINCT t1._category_id FROM item_inventories AS t1" );
      $_categories_ids = [];
      foreach ($categories as $value) {
        array_push($_categories_ids, intval($value->_category_id));
      }
     $_item_categories = ItemCategory::with(['_parents'])->whereIn('id',$_categories_ids)->get();
      
        return view('backend.inventory-report.filter_gross_profit',compact('page_name','previous_filter','permited_branch','permited_costcenters','_datex','_datey','request','stores','_item_categories'));
}

public function reportGrossProfit(Request $request){
      $this->validate($request, [
            '_datex' => 'required',
            '_item_category' => 'required',
            '_datey' => 'required',
        ]);

        session()->put('filter_gross_profit', $request->all());
        $previous_filter= Session::get('filter_gross_profit');
        $page_name = "Gross Profit";
        
        $users = Auth::user();
        $permited_branch = permited_branch(explode(',',$users->branch_ids));
        $permited_costcenters = permited_costcenters(explode(',',$users->cost_center_ids));
        $datas=[];
        $_datex =  change_date_format($request->_datex);
        $_datey=  change_date_format($request->_datey);

        $category_ids = array();
        $_item_categorys = $request->_item_category ?? [];
        if(sizeof($_item_categorys) > 0){
            foreach ($_item_categorys as $value) {
                array_push($category_ids, (int) $value);
            }
        }

        $_items_ids = array();
        $_items = (array) $request->_item_id;
        if(sizeof($_items) > 0){
            foreach ($_items as $value) {
                array_push($_items_ids, (int) $value);
            }

        }else{
            $basic_information = Inventory::select('id')->whereIn('_category_id',$_item_categorys)->get();
            foreach ($basic_information as $value) {
                array_push($_items_ids, (int) $value->id);
            }
        }

     

      $request_branchs = $request->_branch_id ?? [];
      $request_cost_centers = $request->_cost_center ?? [];
      $_stores = $request->_store ?? [];
      if(sizeof($_stores) ==0){
        $stores_all = StoreHouse::get();
        foreach ($stores_all as $value) {
          array_push($_stores, (int) $value->id);
        }
      }


      $_branch_ids = filterableBranch($request_branchs,$permited_branch);
      $_cost_center_ids = filterableCostCenter($request_cost_centers,$permited_costcenters);

      $_items_ids_rows = implode(',', $_items_ids);
      $_branch_ids_rows = implode(',', $_branch_ids);
      $_cost_center_id_rows = implode(',', $_cost_center_ids);
      $_stores_id_rows = implode(',', $_stores);
      $category_ids_rows = implode(',', $category_ids);

      $request_organizations = $request->organization_id ?? [];
        $permited_organizations = permited_organization(explode(',',$users->organization_ids));
        $_organization_ids = filterableOrganization($request_organizations,$permited_organizations);
        $_organization_id_rows = implode(',', $_organization_ids);
        //AND t1.organization_id IN(".$_organization_id_rows.")
      
     if($_items_ids){

      
      $datas = DB::select("  
 SELECT t1._item_id,t1._item_name as _name,t1._category_id,t2._name as _unit_name,t1._store_id,t1._branch_id, t1._cost_center_id,  SUM(IFNULL( -(t1._qty),0  )) AS _qty,SUM(-(t1._qty)*t1._rate) as _value,
  SUM(-(t1._qty)*t1._cost_rate) as _cost_value 
        FROM item_inventories as t1 
        inner JOIN units as t2 on t1._unit_id=t2.id
        WHERE  t1._status=1 AND   t1._date  >= '".$_datex."'  AND t1._date <= '".$_datey."' 
        AND  t1._branch_id IN(".$_branch_ids_rows.")  AND t1.organization_id IN(".$_organization_id_rows.")
        AND t1._store_id IN(".$_stores_id_rows.") AND t1._category_id IN(".$category_ids_rows.")
        AND t1._item_id IN(".$_items_ids_rows.") AND t1._transection IN('Sales','Sales Return')
        GROUP BY t1._branch_id,t1._cost_center_id,t1._store_id,t1._category_id,t1._item_id


      ");
    $group_array_values =array();
      foreach ($datas as $value) {
        $group_array_values[$value->_branch_id."__".$value->_cost_center_id."__".$value->_store_id."__".$value->_category_id][]=$value;
      }

       
       

}else{
   $group_array_values = array();
}
       //return $group_array_values;
        return view('backend.inventory-report.report_gross_profit',compact('request','page_name','group_array_values','_datex','_datey','previous_filter','permited_branch','permited_costcenters','_branch_ids','_cost_center_ids','_stores','category_ids'));
    }

public function grossProfitCatItem(Request $request){
  $category_id = $request->_category_id;
  $data = Inventory::whereIn('_category_id',$category_id)->select('id','_item')->get();
  return view('backend.item-category.gross_profit_cat_base_item',compact('data'));
}

public function resetGrossProfit(){
  Session::flash('filter_gross_profit');
  return redirect()->back();
}



public function filterExpiredItem(Request $request){
      $previous_filter= Session::get('filter_expired_item');
      $page_name = "Expired Item";
      $users = Auth::user();
      $permited_branch = permited_branch(explode(',',$users->branch_ids));
      $permited_costcenters = permited_costcenters(explode(',',$users->cost_center_ids));
      $datas=[];
      $_datex =  change_date_format($request->_datex);
      $_datey=  change_date_format($request->_datey);
      $stores = StoreHouse::get();
       $categories = DB::select( " SELECT DISTINCT t1._category_id FROM item_inventories AS t1" );
      $_categories_ids = [];
      foreach ($categories as $value) {
        array_push($_categories_ids, intval($value->_category_id));
      }
     $_item_categories = ItemCategory::with(['_parents'])->whereIn('id',$_categories_ids)->get();
      
        return view('backend.inventory-report.filter_expired_item',compact('page_name','previous_filter','permited_branch','permited_costcenters','_datex','_datey','request','stores','_item_categories'));
}

public function reportExpiredItem(Request $request){
      $this->validate($request, [
            '_datex' => 'required',
            '_item_category' => 'required',
            '_datey' => 'required',
        ]);

        session()->put('filter_expired_item', $request->all());
        $previous_filter= Session::get('filter_expired_item');
        $page_name = "Expired Item";
        
        $users = Auth::user();
        $permited_branch = permited_branch(explode(',',$users->branch_ids));
        $permited_costcenters = permited_costcenters(explode(',',$users->cost_center_ids));
        $datas=[];
        $_datex =  change_date_format($request->_datex);
        $_datey=  change_date_format($request->_datey);

        $category_ids = array();
        $_item_categorys = $request->_item_category ?? [];
        if(sizeof($_item_categorys) > 0){
            foreach ($_item_categorys as $value) {
                array_push($category_ids, (int) $value);
            }
        }

        $_items_ids = array();
        $_items = (array) $request->_item_id;
        if(sizeof($_items) > 0){
            foreach ($_items as $value) {
                array_push($_items_ids, (int) $value);
            }

        }else{
            $basic_information = Inventory::select('id')->whereIn('_category_id',$_item_categorys)->get();
            foreach ($basic_information as $value) {
                array_push($_items_ids, (int) $value->id);
            }
        }

     

      $request_branchs = $request->_branch_id ?? [];
      $request_cost_centers = $request->_cost_center ?? [];
      $_stores = $request->_store ?? [];
      if(sizeof($_stores) ==0){
        $stores_all = StoreHouse::get();
        foreach ($stores_all as $value) {
          array_push($_stores, (int) $value->id);
        }
      }


      $_branch_ids = filterableBranch($request_branchs,$permited_branch);
      $_cost_center_ids = filterableCostCenter($request_cost_centers,$permited_costcenters);

      $_items_ids_rows = implode(',', $_items_ids);
      $_branch_ids_rows = implode(',', $_branch_ids);
      $_cost_center_id_rows = implode(',', $_cost_center_ids);
      $_stores_id_rows = implode(',', $_stores);
      $category_ids_rows = implode(',', $category_ids);

      $request_organizations = $request->organization_id ?? [];
        $permited_organizations = permited_organization(explode(',',$users->organization_ids));
        $_organization_ids = filterableOrganization($request_organizations,$permited_organizations);
        $_organization_id_rows = implode(',', $_organization_ids);
        //AND t1.organization_id IN(".$_organization_id_rows.")
      
     if($_items_ids){

      
      $datas = DB::select("  
 SELECT t1.`id`, t1.`_item_id`,t2._category_id, t1.`_item`, t1.`_unit_id`,t3._name as _unit_name, t1.`_barcode`, t1.`_manufacture_date`, t1.`_expire_date`, t1.`_qty`, t1.`_sales_rate`, t1.`_pur_rate`, t1.`_sales_discount`, t1.`_sales_vat`, t1.`_value`, t1.`_master_id`, t1.`_branch_id`, t1.`_cost_center_id`, t1.`_store_id`, t1.`_store_salves_id`, t1.`_status` 
 FROM `product_price_lists` as t1
 INNER JOIN inventories as t2 ON t1._item_id=t2.id
 INNER JOIN units as t3 on t3.id=t1._unit_id
  WHERE  t1._status=1 AND   t1._expire_date  >= '".$_datex."'  AND t1._expire_date <= '".$_datey."' 
        AND t1.organization_id IN(".$_organization_id_rows.") AND  t1._branch_id IN(".$_branch_ids_rows.") AND t1._qty !=0
        AND t1._store_id IN(".$_stores_id_rows.") AND t2._category_id IN(".$category_ids_rows.")
        AND t1._item_id IN(".$_items_ids_rows.")  AND t1._cost_center_id IN(".$_cost_center_id_rows.")
        ORDER BY t1.`_item` ASC


      ");
    $group_array_values =array();
      foreach ($datas as $value) {
        $group_array_values[$value->_branch_id."__".$value->_cost_center_id."__".$value->_store_id."__".$value->_category_id][]=$value;
      }

      // foreach ($datas as $value) {
      //   $group_array_values[$value->_branch_id][$value->_cost_center_id][$value->_store_id][$value->_category_id][]=$value;
      // }

       
       

}else{
   $group_array_values = array();
}
       //return $group_array_values;
        return view('backend.inventory-report.report_expired_item',compact('request','page_name','group_array_values','_datex','_datey','previous_filter','permited_branch','permited_costcenters','_branch_ids','_cost_center_ids','_stores','category_ids'));
    }

public function expiredItemCatItem(Request $request){
  $category_id = $request->_category_id;
  $data = Inventory::whereIn('_category_id',$category_id)->select('id','_item')->get();
  return view('backend.item-category.expired_item_cat_base_item',compact('data'));
}

public function resetExpiredItem(){
  Session::flash('filter_expired_item');
  return redirect()->back();
}

public function filterShortageItem(Request $request){
      $previous_filter= Session::get('filter_shortage_item');
      $page_name = "Shortage Item";
      $users = Auth::user();
      $permited_branch = permited_branch(explode(',',$users->branch_ids));
      $permited_costcenters = permited_costcenters(explode(',',$users->cost_center_ids));
      $datas=[];
      $_datex =  change_date_format($request->_datex);
      $_datey=  change_date_format($request->_datey);
      $stores = StoreHouse::get();
      $categories = DB::select( " SELECT DISTINCT t1._category_id FROM item_inventories AS t1" );
      $_categories_ids = [];
      foreach ($categories as $value) {
        array_push($_categories_ids, intval($value->_category_id));
      }
     $_item_categories = ItemCategory::with(['_parents'])->whereIn('id',$_categories_ids)->get();
      
        return view('backend.inventory-report.filter_shortage_item',compact('page_name','previous_filter','permited_branch','permited_costcenters','_datex','_datey','request','stores','_item_categories'));
}

public function reportShortageItem(Request $request){
      $this->validate($request, [
            '_item_category' => 'required'
        ]);

        session()->put('filter_shortage_item', $request->all());
        $previous_filter= Session::get('filter_shortage_item');
        $page_name = "Shortage Item";
        
        $users = Auth::user();
        $permited_branch = permited_branch(explode(',',$users->branch_ids));
        $permited_costcenters = permited_costcenters(explode(',',$users->cost_center_ids));
        $datas=[];
        $_datex =  change_date_format($request->_datex);
        $_datey=  change_date_format($request->_datey);

        $category_ids = array();
        $_item_categorys = $request->_item_category ?? [];
        if(sizeof($_item_categorys) > 0){
            foreach ($_item_categorys as $value) {
                array_push($category_ids, (int) $value);
            }
        }

        $_items_ids = array();
        $_items = (array) $request->_item_id;
        if(sizeof($_items) > 0){
            foreach ($_items as $value) {
                array_push($_items_ids, (int) $value);
            }

        }else{
            $basic_information = Inventory::select('id')->whereIn('_category_id',$_item_categorys)->get();
            foreach ($basic_information as $value) {
                array_push($_items_ids, (int) $value->id);
            }
        }

     

      $request_branchs = $request->_branch_id ?? [];
      $request_cost_centers = $request->_cost_center ?? [];
      $_stores = $request->_store ?? [];
      if(sizeof($_stores) ==0){
        $stores_all = StoreHouse::get();
        foreach ($stores_all as $value) {
          array_push($_stores, (int) $value->id);
        }
      }


      $_branch_ids = filterableBranch($request_branchs,$permited_branch);
      $_cost_center_ids = filterableCostCenter($request_cost_centers,$permited_costcenters);

      $_items_ids_rows = implode(',', $_items_ids);
      $_branch_ids_rows = implode(',', $_branch_ids);
      $_cost_center_id_rows = implode(',', $_cost_center_ids);
      $_stores_id_rows = implode(',', $_stores);
      $category_ids_rows = implode(',', $category_ids);


      $request_organizations = $request->organization_id ?? [];
        $permited_organizations = permited_organization(explode(',',$users->organization_ids));
        $_organization_ids = filterableOrganization($request_organizations,$permited_organizations);
        $_organization_id_rows = implode(',', $_organization_ids);
        //AND t1.organization_id IN(".$_organization_id_rows.")
      
     if($_items_ids){

      
      $datas = DB::select("  
SELECT t1._branch_id,t1._store_id,t1._cost_center_id,t2._category_id,t1._item_id,t1._item_name,SUM(t1._qty) as _qty,t3._name AS _unit_name,t2._reorder,t2._order_qty ,t2._manufacture_company
FROM item_inventories as t1 
INNER JOIN inventories AS t2 ON t1._item_id=t2.id
INNER JOIN units AS t3 ON t2._unit_id=t3.id
WHERE t1._status=1 AND t1.organization_id IN(".$_organization_id_rows.")  AND  t1._branch_id IN(".$_branch_ids_rows.") 
        AND t1._store_id IN(".$_stores_id_rows.") AND t2._category_id IN(".$category_ids_rows.")
        AND t1._item_id IN(".$_items_ids_rows.")  AND t1._cost_center_id IN(".$_cost_center_id_rows.") GROUP BY t1._item_id HAVING (SUM(t1._qty) < t2._reorder)
       
        ORDER BY t2.`_item` ASC


      ");
    $group_array_values =array();
      foreach ($datas as $value) {
        $group_array_values[$value->_branch_id."__".$value->_cost_center_id."__".$value->_store_id."__".$value->_category_id][]=$value;
      }

      // foreach ($datas as $value) {
      //   $group_array_values[$value->_branch_id][$value->_cost_center_id][$value->_store_id][$value->_category_id][]=$value;
      // }

       
       

}else{
   $group_array_values = array();
}
       //return $group_array_values;
        return view('backend.inventory-report.report_shortage_item',compact('request','page_name','group_array_values','_datex','_datey','previous_filter','permited_branch','permited_costcenters','_branch_ids','_cost_center_ids','_stores','category_ids'));
    }

public function shortageItemCatItem(Request $request){
  $category_id = $request->_category_id;
  $data = Inventory::whereIn('_category_id',$category_id)->select('id','_item')->get();
  return view('backend.item-category.shortage_item_cat_base_item',compact('data'));
}

public function resetShortageItem(){
  Session::flash('filter_shortage_item');
  return redirect()->back();
}


}
