@extends('backend.layouts.app')
@section('title',$settings->name ?? '')
@section('content')
@php
$users = Auth::user();
@endphp
<!-- Content Header (Page header) -->


    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 _page_name">Dashboard </h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a class="_page_name" href="{{url('home')}}">Home</a></li>
              <li class="breadcrumb-item _page_name ">Dashboard </li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <style type="text/css">
      .card-title {
        float: none;
    text-align: center !important;

    font-size: 1.1rem;
    font-weight: 400;
    margin: 0;
    padding: 5px;
}
.card-body{
  background: #fff;
  padding:10px;
}
    </style>
    <!-- /.content-header -->
<div class="content" >
      <div class="container-fluid">
        <div class="row">
          @can('quick-link')
          <div class="col-md-6">
            <div class="card ">
              <div class="card-header border-0">
                <h3 class="card-title">Quick Link</h3>
                <div class="card-tools"></div>
              </div>
              <div class="card-body table-responsive p-0 info-box">
                  <table class="table table-striped table-valign-middle">
                    @can('voucher-list')
                    <tr>
                      <th>
                         
                          <div style="display: flex;">
                           <a href="{{url('voucher')}}" class="dropdown-item">
                              <i class="fa fa-fax mr-2" aria-hidden="true"></i> Voucher
                            </a>
                             <a  href="{{route('voucher.create')}}" class="dropdown-item text-right">
                              <i class="nav-icon fas fa-plus"></i>
                            </a>
                          </div>
                          
                      </th>
                  </tr>
                   @endcan 
                   @can('purchase-list')
                    <tr>
                      <th>
                        
                           <div style="display: flex;">
                           <a href="{{url('purchase')}}" class="dropdown-item">
                            <i class="fa fa-list-alt mr-2" aria-hidden="true"></i> {{__('label.material_receive')}}
                          </a>

                             <a  href="{{route('purchase.create')}}" class="dropdown-item text-right " > 
            <i class="nav-icon fas fa-plus"></i> </a>
                        </div>
                         
                      </th>
                  </tr>
                  @endcan
                   @can('purchase-return-list')
                    <tr>
                      <th>
                        
                            <div style="display: flex;">
                               <a href="{{url('purchase-return')}}" class="dropdown-item">
                                <i class="fa fa-list-alt mr-2" aria-hidden="true"></i>  {{__('label.material_return')}}
                              </a>
                              <a  href="{{route('purchase-return.create')}}" 
          class="dropdown-item text-right  " > 
            <i class="nav-icon fas fa-plus"></i> </a>
                               
                            </div>
                            
                      </th>
                  </tr>
                   @endcan
                  @can('sales-list')
                    <tr>
                      <th>
                         
                        <div style="display: flex;">
                           <a href="{{url('sales')}}" class="dropdown-item">
                            <i class="fa fa-shopping-cart mr-2" aria-hidden="true"></i> {{__('label.material_issue')}}
                          </a>
                           <a  href="{{route('sales.create')}}" class="dropdown-item text-right">
                            <i class="nav-icon fas fa-plus"></i>
                          </a>
                        </div>
                        
                      </th>
                  </tr>
                   @endcan 
                  @can('restaurant-sales-list')
                    <tr>
                      <th>
                         
                        <div style="display: flex;">
                           <a href="{{url('restaurant-sales')}}" class="dropdown-item">
                            <i class="fa fa-shopping-cart mr-2" aria-hidden="true"></i> Restaurant Sales
                          </a>
                           <a  href="{{route('restaurant-sales.create')}}" class="dropdown-item text-right">
                            <i class="nav-icon fas fa-plus"></i>
                          </a>
                        </div>
                        
                      </th>
                  </tr>
                   @endcan 
                    <tr>
                      <th>
                        @can('sales-return-list')
          
                        <div style="display: flex;">
                           <a href="{{url('sales-return')}}" class="dropdown-item">
                            <i class="fa fa-shopping-cart mr-2" aria-hidden="true"></i> {{__('label.issued_material_return')}}
                          </a>
                           <a  href="{{route('sales-return.create')}}" class="dropdown-item text-right">
                            <i class="nav-icon fas fa-plus"></i>
                          </a>
                        </div>
                          
                         @endcan  
                      </th>
                  </tr>
                  </table>
              </div>
            </div>
          </div>
          @endcan


          <div class="col-md-6">

            <div class="row">
@can('total-purchase')
@php        
$_purchase = \DB::select( " SELECT  t1._account_ledger,SUM(t1.`_dr_amount`) AS _balance  FROM `accounts` AS t1 
INNER JOIN purchase_form_settings AS t2 ON t1.`_account_ledger`=t2._default_purchase
WHERE t1._status=1 AND t1.`_branch_id` IN(".$users->branch_ids.") AND t1.`_cost_center` IN(".$users->cost_center_ids.")  " );
@endphp
               <div class="col-md-6 col-sm-6 col-xs-12 col-custom">
                 <div class="info-box info-box-new-style">
                      <span class="info-box-icon bg-aqua"><i class="fa fa-shopping-basket  text-white" aria-hidden="true"></i>
</span>
                        @forelse($_purchase as $val)
                      <div class="info-box-content">
                        <span class="info-box-text"><h4>Total  {{ _ledger_name($val->_account_ledger) ?? 'Purchase' }}</h4></span>
                        <span class="info-box-number total_purchase"><h3> {{prefix_taka()}}. {{ _report_amount($val->_balance ?? 0) }}</h3></span>
                      </div>
                      @empty
                      @endforelse
                      <!-- /.info-box-content -->
                 </div>
                 <!-- /.info-box -->
              </div>
@endcan
@can('total-sales')
@php        
$data = \DB::select( " SELECT  t1._account_ledger,SUM(t1.`_cr_amount`-t1._dr_amount) AS _balance  FROM `accounts` AS t1 
INNER JOIN sales_form_settings AS t2 ON t1.`_account_ledger`=t2._default_sales
WHERE t1._status=1 AND t1.`_branch_id` IN(".$users->branch_ids.") AND t1.`_cost_center` IN(".$users->cost_center_ids.")  " );
@endphp
               <div class="col-md-6 col-sm-6 col-xs-12 col-custom">
                 <div class="info-box info-box-new-style">
                      <span class="info-box-icon bg-yellow"><i class="fa fa-shopping-cart text-white" aria-hidden="true"></i></span>
                        @forelse($data as $val)
                      <div class="info-box-content">
                        <span class="info-box-text"><h4>Total  {{ _ledger_name($val->_account_ledger) }}</h4></span>
                        <span class="info-box-number total_purchase"><h3> {{prefix_taka()}}. {{ _report_amount($val->_balance ?? 0) }}</h3></span>
                      </div>
                      @empty
                      @endforelse
                      <!-- /.info-box-content -->
                 </div>
                 <!-- /.info-box -->
              </div>
@endcan
@can('total-purchase-return')
@php        
$data = \DB::select( " SELECT  t1._account_ledger,SUM(t1.`_cr_amount`) AS _balance  FROM `accounts` AS t1 
INNER JOIN purchase_return_form_settings AS t2 ON t1.`_account_ledger`=t2._default_purchase
WHERE t1._status=1 AND t1.`_branch_id` IN(".$users->branch_ids.") AND t1.`_cost_center` IN(".$users->cost_center_ids.")  " );
@endphp
               <div class="col-md-6 col-sm-6 col-xs-12 col-custom">
                 <div class="info-box info-box-new-style">
                      <span class="info-box-icon bg-red"><i class="fa fa-shopping-basket  text-white" aria-hidden="true"></i></span>
                        @forelse($data as $val)
                      <div class="info-box-content">
                        <span class="info-box-text"><h4>Total  {{ _ledger_name($val->_account_ledger) }}</h4></span>
                        <span class="info-box-number total_purchase"><h3> {{prefix_taka()}}. {{ _report_amount($val->_balance ?? 0) }}</h3></span>
                      </div>
                      @empty
                      @endforelse
                      <!-- /.info-box-content -->
                 </div>
                 <!-- /.info-box -->
              </div>
@endcan
@can('total-sales-return')
@php        
$data = \DB::select( " SELECT  t1._account_ledger,-SUM(t1.`_cr_amount`-t1._dr_amount) AS _balance  FROM `accounts` AS t1 
INNER JOIN sales_return_form_settings AS t2 ON t1.`_account_ledger`=t2._default_sales
WHERE t1._status=1 AND t1.`_branch_id` IN(".$users->branch_ids.") AND t1.`_cost_center` IN(".$users->cost_center_ids.")  " );
@endphp
               <div class="col-md-6 col-sm-6 col-xs-12 col-custom ">
                 <div class="info-box info-box-new-style">
                      <span class="info-box-icon bg-red"><i class="fa fa-shopping-cart   text-white" aria-hidden="true"></i></span>
                        @forelse($data as $val)
                      <div class="info-box-content">
                        <span class="info-box-text"><h4>Total  {{ _ledger_name($val->_account_ledger) }}</h4></span>
                        <span class="info-box-number total_purchase"><h3> {{prefix_taka()}}. {{ _report_amount($val->_balance ?? 0) }}</h3></span>
                      </div>
                      @empty
                      @endforelse
                      <!-- /.info-box-content -->
                 </div>
                 <!-- /.info-box -->
              </div>
@endcan




            </div>
          </div>

@can('daily-sales-chart')
<!-- Sales Related Chart Start -->
  <div class="col-lg-6 mt-2">
   <div class="card bg-white">
              <div class="card-header border-0">
                <div class="d-flex justify-content-between">
                  <h3 class="card-title">Last 30 Days Sales Report</h3>
                  
                </div>
              </div>
              <div class="card-body">
                <div class="position-relative mb-4">
                  <canvas id="dailySalesReport" height="200"></canvas>
                </div>
              </div>
            </div>
  </div>
@endcan
  @can('monthly-sales-chart')
  <div class="col-md-6 mt-2">
    <div class="card bg-white">
              <div class="card-header border-0">
                <div class="d-flex justify-content-between">
                  <h3 class="card-title">Last 12 Month Sales  Report</h3>
                </div>
              </div>
              <div class="card-body">
                <div class="position-relative mb-4">
                  <canvas id="monthWiseSalesBarChart" height="200"></canvas>
                </div>
              </div>
            </div>
  </div><!-- Sales realted Chart End -->
@endcan

@can('daily-restaurant-sales-chart')
<!-- Sales Related Chart Start -->
  <div class="col-lg-6 mt-2">
   <div class="card bg-white">
              <div class="card-header border-0">
                <div class="d-flex justify-content-between">
                  <h3 class="card-title">Last 30 Days Resturant Sales Report</h3>
                  
                </div>
              </div>
              <div class="card-body">
                <div class="position-relative mb-4">
                  <canvas id="dailyResturantSalesReport" height="200"></canvas>
                </div>
              </div>
            </div>
  </div>
@endcan
  @can('monthly-restaurant-sales-chart')
  <div class="col-md-6 mt-2">
    <div class="card bg-white">
              <div class="card-header border-0">
                <div class="d-flex justify-content-between">
                  <h3 class="card-title">Last 12 Month Resturant Sales  Report</h3>
                </div>
              </div>
              <div class="card-body">
                <div class="position-relative mb-4">
                  <canvas id="monthWiseResturantSalesBarChart" height="200"></canvas>
                </div>
              </div>
            </div>
  </div><!-- Sales realted Chart End -->
@endcan
@can('daily-purchase-chart')
  <!-- Purchase Related Chart Start -->
  <div class="col-lg-6 mt-2">
   <div class="card bg-white">
              <div class="card-header border-0">
                <div class="d-flex justify-content-between">
                  <h3 class="card-title">Last 30 Days Purchase Report</h3>
                  
                </div>
              </div>
              <div class="card-body">
                <div class="position-relative mb-4">
                  <canvas id="dailyPurchaseReport" height="200"></canvas>
                </div>
              </div>
            </div>
  </div>
  @endcan
  @can('monthly-purchase-chart')
  <div class="col-md-6 mt-2">
    <div class="card bg-white">
              <div class="card-header border-0">
                <div class="d-flex justify-content-between">
                  <h3 class="card-title">Last 12 Month Purchase  Report</h3>
                </div>
              </div>
              <div class="card-body">
                <div class="position-relative mb-4">
                  <canvas id="monthWisePurchaseBarChart" height="200"></canvas>
                </div>
              </div>
            </div>
  </div><!-- Purchase realted Chart End -->
@endcan
@can('monthly-sales-return-chart')
  <!-- SalesReturn Related Chart Start -->
  <div class="col-lg-6 mt-2">
   <div class="card bg-white">
              <div class="card-header border-0">
                <div class="d-flex justify-content-between">
                  <h3 class="card-title">Last 30 Days Sales Return Report</h3>
                  
                </div>
              </div>
              <div class="card-body">
                <div class="position-relative mb-4">
                  <canvas id="dailySalesReturnReport" height="200"></canvas>
                </div>
              </div>
            </div>
  </div>
  @endcan
  @can('daily-sales-return-chart')
  <div class="col-md-6 mt-2">
    <div class="card bg-white">
              <div class="card-header border-0">
                <div class="d-flex justify-content-between">
                  <h3 class="card-title">Last 12 Month Sales Return  Report</h3>
                </div>
              </div>
              <div class="card-body">
                <div class="position-relative mb-4">
                  <canvas id="monthWiseSalesReturnBarChart" height="200"></canvas>
                </div>
              </div>
            </div>
  </div><!-- Purchase realted Chart End -->
@endcan

@can('daily-purchase-return-chart')
  <!-- PurchaseReturn Related Chart Start -->
  <div class="col-lg-6 mt-2">
   <div class="card bg-white">
              <div class="card-header border-0">
                <div class="d-flex justify-content-between">
                  <h3 class="card-title">Last 30 Days Purchase Return Report</h3>
                  
                </div>
              </div>
              <div class="card-body">
                <div class="position-relative mb-4">
                  <canvas id="dailyPurchaseReturnReport" height="200"></canvas>
                </div>
              </div>
            </div>
  </div>
  @endcan
  @can('monthly-purchase-return-chart')
  <div class="col-md-6 mt-2">
    <div class="card bg-white">
              <div class="card-header border-0">
                <div class="d-flex justify-content-between">
                  <h3 class="card-title">Last 12 Month Purchase Return  Report</h3>
                </div>
              </div>
              <div class="card-body">
                <div class="position-relative mb-4">
                  <canvas id="monthWisePurchaseReturnBarChart" height="200"></canvas>
                </div>
              </div>
            </div>
  </div><!-- Purchase realted Chart End -->
  @endcan
  @can('daily-damage-chart')
  <!-- Damage Related Chart Start -->
  <div class="col-lg-6 mt-2">
   <div class="card bg-white">
              <div class="card-header border-0">
                <div class="d-flex justify-content-between">
                  <h3 class="card-title">Last 30 Days Damage Report</h3>
                  
                </div>
              </div>
              <div class="card-body">
                <div class="position-relative mb-4">
                  <canvas id="dailyDamageReport" height="200"></canvas>
                </div>
              </div>
            </div>
  </div>
  @endcan
  @can('monthly-damage-chart')
  <div class="col-md-6 mt-2">
    <div class="card bg-white">
              <div class="card-header border-0">
                <div class="d-flex justify-content-between">
                  <h3 class="card-title">Last 12 Month Damage  Report</h3>
                </div>
              </div>
              <div class="card-body">
                <div class="position-relative mb-4">
                  <canvas id="monthWiseDamageBarChart" height="200"></canvas>
                </div>
              </div>
            </div>
  </div><!-- Damage realted Chart End -->
  @endcan
  
  
  

  


          <!-- /.col-md-6 -->
           @can('top-due-customer')
          <div class="col-md-6 mt-2">
            <div class="card ">
              <div class="card-header border-0">
                <h3 class="card-title">Top Due Customer</h3>
                <div class="card-tools"></div>
              </div>
              <div class="card-body table-responsive p-0 info-box">
                <table class="table table-striped table-valign-middle">
                  <thead>
                  <tr>
                    <th>Ledger</th>
                    <th class="text-right">Amount</th>
                  </tr>
                  </thead>
                  @php
        
        $accounts = \DB::select( " SELECT  t2._name,SUM(t1.`_dr_amount`-t1.`_cr_amount`) AS _balance  
FROM `accounts` AS t1 
INNER JOIN account_ledgers AS t2 ON t1._account_ledger=t2.id
WHERE t1._status=1 AND t1.`_branch_id` IN(".$users->branch_ids.") 
AND t1.`_cost_center` IN(".$users->cost_center_ids.") AND t1._account_head=13
GROUP BY t1._account_ledger ORDER BY ABS(SUM(t1.`_dr_amount`-t1.`_cr_amount`)) DESC
LIMIT 5 " );


                  @endphp
                  <tbody>
                    @forelse($accounts as $val)
                  <tr>
                    <td>
                     {!! $val->_name ?? '' !!}
                    </td>
                    <td class="text-right"> {!! _show_amount_dr_cr(_report_amount($val->_balance ?? 0)) !!}</td>
                    
                  </tr>
                  @empty
                  @endforelse
                  
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          @endcan
          @can('top-payable-supplier')
          <div class="col-md-6 mt-2">
            <div class="card ">
              <div class="card-header border-0">
                <h3 class="card-title">Top Payable Supplier</h3>
                <div class="card-tools"></div>
              </div>
              <div class="card-body table-responsive p-0 info-box">
                <table class="table table-striped table-valign-middle">
                  <thead>
                  <tr>
                    <th>Ledger</th>
                    <th class="text-right">Amount</th>
                  </tr>
                  </thead>
                  @php
        
        $accounts = \DB::select( " SELECT  t2._name,SUM(t1.`_dr_amount`-t1.`_cr_amount`) AS _balance  
FROM `accounts` AS t1 
INNER JOIN account_ledgers AS t2 ON t1._account_ledger=t2.id
WHERE t1._status=1 AND t1.`_branch_id` IN(".$users->branch_ids.") 
AND t1.`_cost_center` IN(".$users->cost_center_ids.") AND t1._account_head=12
GROUP BY t1._account_ledger ORDER BY ABS(SUM(t1.`_dr_amount`-t1.`_cr_amount`)) DESC
LIMIT 5 " );


                  @endphp
                  <tbody>
                    @forelse($accounts as $val)
                  <tr>
                    <td>
                     {!! $val->_name ?? '' !!}
                    </td>
                    <td class="text-right"> {!! _show_amount_dr_cr(_report_amount($val->_balance ?? 0)) !!}</td>
                    
                  </tr>
                  @empty
                  @endforelse
                  
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          @endcan
          <!-- /.col-md-6 -->
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </div>


<?php
  $filtered_month =[];
  $filtered_purchase = [];
  $filtered_purchase_return = [];
  $filtered_sales = [];
  $filtered_sales_return = [];
  $filtered_damage = [];

  
  $qur = " select DATE_FORMAT(_date, '%m-%Y') as _month from accounts GROUP BY YEAR(_date),MONTH(_date)  ";
  $months = \DB::select($qur);
  ?>

 @can('monthly-sales-chart')
<?php

  $sales = " select round(sum(t1._cr_amount)) as _amount,DATE_FORMAT(t1._date, '%m-%y') as _month 
from accounts as t1
INNER JOIN sales_form_settings AS t2 ON t1._account_ledger=t2._default_sales
where (t1._transaction collate utf8mb4_unicode_ci = 'Sales') AND (t1._date > now() - INTERVAL 12 month ) AND t1._status=1 AND t1._branch_id IN(".$users->branch_ids.")  
AND `_cost_center` IN(".$users->cost_center_ids.")  GROUP BY YEAR(t1._date),MONTH(t1._date)  ";
  $sales_amounts = \DB::select($sales);
$_sales_months=array();
$_sales_month_amounts = array();
foreach ($sales_amounts as $value) {
    array_push($_sales_months, $value->_month);
    array_push($_sales_month_amounts, floatval($value->_amount));
  }
  ?>
  @endcan

 @can('monthly-restaurant-sales-chart')
<?php

  $sales_restaurant = " select round(sum(t1._cr_amount)) as _amount,DATE_FORMAT(t1._date, '%m-%y') as _month 
from accounts as t1
INNER JOIN resturant_form_settings AS t2 ON t1._account_ledger=t2._default_sales
where (t1._transaction collate utf8mb4_unicode_ci = 'Restaurant Sales') AND (t1._date > now() - INTERVAL 12 month ) AND t1._status=1 AND t1._branch_id IN(".$users->branch_ids.")  
AND `_cost_center` IN(".$users->cost_center_ids.")  GROUP BY YEAR(t1._date),MONTH(t1._date)  ";
  $sales_amounts_restaurant = \DB::select($sales_restaurant);
$_sales_months_restaurant=array();
$_sales_month_amounts_restaurant = array();
foreach ($sales_amounts_restaurant as $value) {
    array_push($_sales_months_restaurant, $value->_month);
    array_push($_sales_month_amounts_restaurant, floatval($value->_amount));
  }
  ?>
  @endcan


    @can('daily-sales-chart')
  <?php

//Last 30 days sales line chart
  $_daily_sales = " select round(sum(t1._cr_amount)) as _amount,DATE_FORMAT(t1._date, '%d-%m') as _month 
from accounts as t1
INNER JOIN sales_form_settings AS t2 ON t1._account_ledger=t2._default_sales
where (t1._transaction collate utf8mb4_unicode_ci = 'Sales') AND (t1._date BETWEEN CURDATE() - INTERVAL 30 DAY AND CURDATE() ) AND t1._status=1 AND t1._branch_id IN(".$users->branch_ids.")  
AND `_cost_center` IN(".$users->cost_center_ids.")  GROUP BY YEAR(t1._date),MONTH(t1._date),DAY(t1._date) ORDER BY YEAR(t1._date),MONTH(t1._date),DAY(t1._date) ASC ";
  $daily_sales_report = \DB::select($_daily_sales);

  //Last 30 days sales line chart
  $sales_days=array();
  $last_30_days_sales = array();
  foreach ($daily_sales_report as $value) {
    array_push($sales_days, $value->_month);
    array_push($last_30_days_sales, floatval($value->_amount));
  }

?>
@endcan




@can('daily-restaurant-sales-chart')
  <?php

//Last 30 days sales line chart
  $_daily_sales_restaurant = " select round(sum(t1._cr_amount)) as _amount,DATE_FORMAT(t1._date, '%d-%m') as _month 
from accounts as t1
INNER JOIN sales_form_settings AS t2 ON t1._account_ledger=t2._default_sales
where (t1._transaction collate utf8mb4_unicode_ci = 'Restaurant Sales') AND (t1._date BETWEEN CURDATE() - INTERVAL 30 DAY AND CURDATE() ) AND t1._status=1 AND t1._branch_id IN(".$users->branch_ids.")  
AND `_cost_center` IN(".$users->cost_center_ids.")  GROUP BY YEAR(t1._date),MONTH(t1._date),DAY(t1._date) ORDER BY YEAR(t1._date),MONTH(t1._date),DAY(t1._date) ASC ";
  $daily_sales_report_restaurant = \DB::select($_daily_sales_restaurant);

  //Last 30 days sales line chart
  $sales_days_restaurant=array();
  $last_30_days_sales_restaurant = array();
  foreach ($daily_sales_report_restaurant as $value) {
    array_push($sales_days_restaurant, $value->_month);
    array_push($last_30_days_sales_restaurant, floatval($value->_amount));
  }

?>
@endcan



@can('monthly-sales-return-chart')

<?php

  $sales_return = " select round(sum(t1._dr_amount)) as _amount,DATE_FORMAT(t1._date, '%m-%y') as _month 
from accounts as t1
INNER JOIN sales_return_form_settings AS t2 ON t1._account_ledger=t2._default_sales
where (t1._transaction collate utf8mb4_unicode_ci = 'Sales Return') AND (t1._date > now() - INTERVAL 12 month ) AND t1._status=1 AND t1._branch_id IN(".$users->branch_ids.")  
AND `_cost_center` IN(".$users->cost_center_ids.")  GROUP BY YEAR(t1._date),MONTH(t1._date) ORDER BY YEAR(t1._date),MONTH(t1._date) ASC ";
   $sales_return_amounts = \DB::select($sales_return);

$_sales_retun_months=array();
$_sales_return_month_amounts = array();
foreach ($sales_return_amounts as $value) {
    array_push($_sales_retun_months, $value->_month);
    array_push($_sales_return_month_amounts, floatval($value->_amount));
  }

?>
@endcan
 @can('daily-sales-return-chart')
<?php
  //Last 30 days sales_return line chart
  $_daily_sales_return = "  select round(sum(t1._dr_amount)) as _amount,DATE_FORMAT(t1._date, '%d-%m-%y') as _month 
from accounts as t1
INNER JOIN sales_return_form_settings AS t2 ON t1._account_ledger=t2._default_sales
where (t1._transaction collate utf8mb4_unicode_ci = 'Sales Return') AND (t1._date BETWEEN CURDATE() - INTERVAL 30 DAY AND CURDATE() ) AND t1._status=1 AND t1._branch_id IN(".$users->branch_ids.")  
AND `_cost_center` IN(".$users->cost_center_ids.")  GROUP BY YEAR(t1._date),MONTH(t1._date),DATE(t1._date) ORDER BY YEAR(t1._date),MONTH(t1._date),DATE(t1._date) ASC ";
  $_daily_sales_return_report = \DB::select($_daily_sales_return);

    //Last 30 days sales_return line chart
  $sales_return_days=array();
  $last_30_days_sales_return = array();
  foreach ($_daily_sales_return_report as $value) {
    array_push($sales_return_days, $value->_month);
    array_push($last_30_days_sales_return, floatval($value->_amount));
  }

?>
@endcan
@can('monthly-purchase-chart')

<?php
    $purchases = " select round(sum(t1._dr_amount)) as _amount,DATE_FORMAT(t1._date, '%m-%y') as _month 
from accounts as t1
INNER JOIN purchase_form_settings AS t2 ON t1._account_ledger=t2._default_purchase
where (t1._transaction collate utf8mb4_unicode_ci = 'Purchase') AND (t1._date > now() - INTERVAL 12 month ) AND t1._status=1 AND t1._branch_id IN(".$users->branch_ids.")  
AND `_cost_center` IN(".$users->cost_center_ids.")  GROUP BY YEAR(t1._date),MONTH(t1._date) ORDER BY YEAR(t1._date),MONTH(t1._date) ASC ";
  $purchases_amount  = \DB::select($purchases);

$_purchase_months=array();
$_purchase_month_amounts = array();
foreach ($purchases_amount  as $value) {
    array_push($_purchase_months, $value->_month);
    array_push($_purchase_month_amounts, floatval($value->_amount));
  }
?>
@endcan
@can('daily-purchase-chart')
<?php

//Last 30 days Purchase line chart
  $_daily_purchase = " select round(sum(t1._dr_amount)) as _amount,DATE_FORMAT(t1._date, '%d-%m') as _month 
from accounts as t1
INNER JOIN purchase_form_settings AS t2 ON t1._account_ledger=t2._default_purchase
where (t1._transaction collate utf8mb4_unicode_ci = 'Purchase') AND (t1._date BETWEEN CURDATE() - INTERVAL 30 DAY AND CURDATE() ) AND t1._status=1 AND t1._branch_id IN(".$users->branch_ids.")  
AND `_cost_center` IN(".$users->cost_center_ids.")  GROUP BY YEAR(t1._date),MONTH(t1._date),DATE(t1._date) ORDER BY YEAR(t1._date),MONTH(t1._date),DATE(t1._date) ASC ";
  $_daily_purchase_report = \DB::select($_daily_purchase);

    //Last 30 days Purchase line chart
  $purchase_days=array();
  $last_30_days_purchase = array();
  foreach ($_daily_purchase_report as $value) {
    array_push($purchase_days, $value->_month);
    array_push($last_30_days_purchase, floatval($value->_amount));
  }

?>
@endcan
@can('monthly-purchase-return-chart')

<?php
  
  $purchase_return = " select round(sum(t1._cr_amount)) as _amount,DATE_FORMAT(t1._date, '%m-%y') as _month 
from accounts as t1
INNER JOIN purchase_return_form_settings AS t2 ON t1._account_ledger=t2._default_purchase
where (t1._transaction collate utf8mb4_unicode_ci = 'Purchase Return') AND (t1._date > now() - INTERVAL 12 month ) AND t1._status=1 AND t1._branch_id IN(".$users->branch_ids.")  
AND `_cost_center` IN(".$users->cost_center_ids.")  GROUP BY YEAR(t1._date),MONTH(t1._date) ORDER BY YEAR(t1._date),MONTH(t1._date) ASC";
  $purchase_return_amounts = \DB::select($purchase_return);

$_purchase_return_months=array();
$_purchase_return_month_amounts = array();
foreach ($purchase_return_amounts  as $value) {
    array_push($_purchase_return_months, $value->_month);
    array_push($_purchase_return_month_amounts, floatval($value->_amount));
  }

?>
@endcan
@can('daily-purchase-return-chart')
<?php
//Last 30 days purchase_return line chart
  $_daily_purchase_return = " SELECT round(sum(t1._cr_amount)) as _amount,DATE_FORMAT(t1._date, '%d-%m') as _month 
from accounts as t1
INNER JOIN purchase_return_form_settings AS t2 ON t1._account_ledger=t2._default_purchase
where (t1._transaction collate utf8mb4_unicode_ci = 'Purchase Return') AND (t1._date BETWEEN CURDATE() - INTERVAL 30 DAY AND CURDATE() ) AND t1._status=1 AND t1._branch_id IN(".$users->branch_ids.")  
AND `_cost_center` IN(".$users->cost_center_ids.")  GROUP BY YEAR(t1._date),MONTH(t1._date),DATE(t1._date) ORDER BY YEAR(t1._date),MONTH(t1._date),DAY(t1._date) ASC ";
  $_daily_purchase_return_report = \DB::select($_daily_purchase_return);

    //Last 30 days purchase_return line chart
  $purchase_return_days=array();
  $last_30_days_purchase_return = array();
  foreach ($_daily_purchase_return_report as $value) {
    array_push($purchase_return_days, $value->_month);
    array_push($last_30_days_purchase_return, floatval($value->_amount));
  }
?>
 @endcan
 @can('monthly-damage-chart')
 <?php

  $_damage = " SELECT round(sum(_dr_amount)) as _amount,DATE_FORMAT(_date, '%m-%Y') as _month from accounts where (_transaction collate utf8mb4_unicode_ci = 'Damage') AND (_date BETWEEN CURDATE() - INTERVAL 30 DAY AND CURDATE() ) AND _status=1 AND `_branch_id` IN(".$users->branch_ids.") 
AND `_cost_center` IN(".$users->cost_center_ids.") GROUP BY YEAR(_date),MONTH(_date) ORDER BY YEAR(_date),MONTH(_date) ASC ";
  $_damage_amounts = \DB::select($_damage);

  $damage_months=array();
$damage_month_amounts = array();
foreach ($_damage_amounts  as $value) {
    array_push($damage_months, $value->_month);
    array_push($damage_month_amounts, floatval($value->_amount));
  }
?>
@endcan

@can('daily-damage-chart')
<?php
//Last 30 days damage line chart
  $_daily_damage = " select round(sum(_dr_amount)) as _amount,DATE_FORMAT(_date, '%d-%m') as _month from accounts where (_transaction collate utf8mb4_unicode_ci = 'Damage') AND (_date BETWEEN CURDATE() - INTERVAL 30 DAY AND CURDATE() ) AND _status=1 AND `_branch_id` IN(".$users->branch_ids.") 
AND `_cost_center` IN(".$users->cost_center_ids.") GROUP BY YEAR(_date),MONTH(_date),DATE(_date) ORDER BY  YEAR(_date),MONTH(_date),DATE(_date) ASC ";
  $_daily_damage_report = \DB::select($_daily_damage);

    //Last 30 days damage line chart
  $damage_days=array();
  $last_30_days_damage = array();
  foreach ($_daily_damage_report as $value) {
    array_push($damage_days, $value->_month);
    array_push($last_30_days_damage, floatval($value->_amount));
  }
?>
 @endcan

  
  
 



</div>


@endsection
@section('script')
<script src="{{asset('plugins/chart.js/Chart.min.js')}}"></script>
<script type="text/javascript">




/* Damage  Information Start */
 @can('monthly-damage-chart')
  var damage_months =  <?php echo json_encode($damage_months) ?>;
  var damage_month_amounts=  <?php echo json_encode($damage_month_amounts) ?>;
  @endcan

  @can('daily-damage-chart')
  var damage_days =  <?php echo json_encode($damage_days) ?>;
  var last_30_days_damage =  <?php echo json_encode($last_30_days_damage) ?>;
  @endcan

/* Damage  Information End  */



/* Purchase  Information Start */
@can('monthly-purchase-chart')

  var _purchase_months =  <?php echo json_encode($_purchase_months) ?>;
  var _purchase_month_amounts=  <?php echo json_encode($_purchase_month_amounts) ?>;
@endcan
@can('daily-purchase-chart')
  var purchase_days =  <?php echo json_encode($purchase_days) ?>;
  var last_30_days_purchase =  <?php echo json_encode($last_30_days_purchase) ?>;
@endcan  

/* Purchase  Information End  */





/* Purchase Return  Information Start */
@can('monthly-purchase-return-chart')
  var _purchase_return_months =  <?php echo json_encode($_purchase_return_months) ?>;
  var _purchase_return_month_amounts=  <?php echo json_encode($_purchase_return_month_amounts) ?>;
@endcan

@can('daily-purchase-return-chart')
  var purchase_return_days =  <?php echo json_encode($purchase_return_days) ?>;
  var last_30_days_purchase_return =  <?php echo json_encode($last_30_days_purchase_return) ?>;
 @endcan 

/* Purchase Return  Information End  */


/* Sales  Information Start */
@can('monthly-sales-chart')

  var _sales_months =  <?php echo json_encode($_sales_months) ?>;
  var _sales_month_amounts=  <?php echo json_encode($_sales_month_amounts) ?>;
@endcan

/* monthly-restaurant-sales-chart */
@can('monthly-restaurant-sales-chart')

  var _sales_months_restaurant =  <?php echo json_encode($_sales_months_restaurant) ?>;
  var _sales_month_amounts_restaurant=  <?php echo json_encode($_sales_month_amounts_restaurant) ?>;
@endcan


@can('daily-sales-chart')
  var sales_days =  <?php echo json_encode($sales_days) ?>;
  var last_30_days_sales =  <?php echo json_encode($last_30_days_sales) ?>;
@endcan


@can('daily-restaurant-sales-chart')
  var sales_days_restaurant =  <?php echo json_encode($sales_days_restaurant) ?>;
  var last_30_days_sales_restaurant =  <?php echo json_encode($last_30_days_sales_restaurant) ?>;
@endcan

/* Sales  Information End  */

/* Sales Return Information Start */
 @can('daily-sales-return-chart')
  var sales_return_days =  <?php echo json_encode($sales_return_days) ?>;
  var last_30_days_sales_return=  <?php echo json_encode($last_30_days_sales_return) ?>;
@endcan
@can('monthly-sales-return-chart')
  var _sales_retun_months =  <?php echo json_encode($_sales_retun_months) ?>;
  var _sales_return_month_amounts =  <?php echo json_encode($_sales_return_month_amounts) ?>;
@endcan

/* Sales Return Information End  */





  $(function () {
  'use strict'

  var ticksStyle = {
    fontColor: '#495057',
    fontStyle: 'bold'
  }

  var mode = 'index'
  var intersect = true

  




@can('monthly-sales-chart')

  var $salesChart = $('#monthWiseSalesBarChart')
  // eslint-disable-next-line no-unused-vars
  var salesChart = new Chart($salesChart, {
    type: 'bar',
    data: {
      labels: _sales_months,
      datasets: [
        {
          backgroundColor: '#28a745',
          borderColor: '#007bff',
          label: 'Sales',
          data: _sales_month_amounts
        }
      ]
    },
    options: {
      maintainAspectRatio: false,
      tooltips: {
        mode: mode,
        intersect: intersect
      },
      hover: {
        mode: mode,
        intersect: intersect
      },
      legend: {
        display: false
      },
      scales: {
        yAxes: [{
          // display: false,
          gridLines: {
            display: true,
            lineWidth: '4px',
            color: 'rgba(0, 0, 0, .2)',
            zeroLineColor: 'transparent'
          },
          ticks: $.extend({
            beginAtZero: true,

            // Include a dollar sign in the ticks
            callback: function (value) {
              if (value >= 1000) {
                value /= 1000
                value += 'k'
              }

              return 'Tk.' + value
            }
          }, ticksStyle)
        }],
        xAxes: [{
          display: true,
          gridLines: {
            display: false
          },
          ticks: ticksStyle
        }]
      }
    }
  })
@endcan


@can('monthly-restaurant-sales-chart')

  var $salesChart = $('#monthWiseResturantSalesBarChart')
  // eslint-disable-next-line no-unused-vars
  var salesChart = new Chart($salesChart, {
    type: 'bar',
    data: {
      labels: _sales_months_restaurant,
      datasets: [
        {
          backgroundColor: '#28a745',
          borderColor: '#007bff',
          label: 'Sales',
          data: _sales_month_amounts_restaurant
        }
      ]
    },
    options: {
      maintainAspectRatio: false,
      tooltips: {
        mode: mode,
        intersect: intersect
      },
      hover: {
        mode: mode,
        intersect: intersect
      },
      legend: {
        display: false
      },
      scales: {
        yAxes: [{
          // display: false,
          gridLines: {
            display: true,
            lineWidth: '4px',
            color: 'rgba(0, 0, 0, .2)',
            zeroLineColor: 'transparent'
          },
          ticks: $.extend({
            beginAtZero: true,

            // Include a dollar sign in the ticks
            callback: function (value) {
              if (value >= 1000) {
                value /= 1000
                value += 'k'
              }

              return 'Tk.' + value
            }
          }, ticksStyle)
        }],
        xAxes: [{
          display: true,
          gridLines: {
            display: false
          },
          ticks: ticksStyle
        }]
      }
    }
  })
@endcan

@can('daily-sales-chart')
  var $visitorsChart = $('#dailySalesReport')
  // eslint-disable-next-line no-unused-vars
  var visitorsChart = new Chart($visitorsChart, {
    data: {
      labels:sales_days,
      datasets: [{
        type: 'line',
        data: last_30_days_sales,
        backgroundColor: 'transparent',
        borderColor: '#28a745',
        pointBorderColor: '#28a745',
        pointBackgroundColor: '#28a745',
        fill: false
        // pointHoverBackgroundColor: '#007bff',
        // pointHoverBorderColor    : '#007bff'
      }]
    },


    options: {
      maintainAspectRatio: false,
      tooltips: {
        mode: mode,
        intersect: intersect
      },
      hover: {
        mode: mode,
        intersect: intersect
      },
      legend: {
        display: false
      },
      scales: {
        yAxes: [{
          // display: false,
          gridLines: {
            display: true,
            lineWidth: '4px',
            color: 'rgba(0, 0, 0, .2)',
            zeroLineColor: 'transparent'
          },
          ticks: $.extend({
            beginAtZero: true,
            suggestedMax: 200
          }, ticksStyle)
        }],
        xAxes: [{
          display: true,
          gridLines: {
            display: false
          },
          ticks: ticksStyle
        }]
      }
    }
  })

@endcan
@can('daily-restaurant-sales-chart')
  var $visitorsChart = $('#dailyResturantSalesReport')
  // eslint-disable-next-line no-unused-vars
  var visitorsChart = new Chart($visitorsChart, {
    data: {
      labels:sales_days_restaurant,
      datasets: [{
        type: 'line',
        data: last_30_days_sales_restaurant,
        backgroundColor: 'transparent',
        borderColor: '#28a745',
        pointBorderColor: '#28a745',
        pointBackgroundColor: '#28a745',
        fill: false
        // pointHoverBackgroundColor: '#007bff',
        // pointHoverBorderColor    : '#007bff'
      }]
    },


    options: {
      maintainAspectRatio: false,
      tooltips: {
        mode: mode,
        intersect: intersect
      },
      hover: {
        mode: mode,
        intersect: intersect
      },
      legend: {
        display: false
      },
      scales: {
        yAxes: [{
          // display: false,
          gridLines: {
            display: true,
            lineWidth: '4px',
            color: 'rgba(0, 0, 0, .2)',
            zeroLineColor: 'transparent'
          },
          ticks: $.extend({
            beginAtZero: true,
            suggestedMax: 200
          }, ticksStyle)
        }],
        xAxes: [{
          display: true,
          gridLines: {
            display: false
          },
          ticks: ticksStyle
        }]
      }
    }
  })

@endcan



@can('monthly-purchase-chart')
  var $salesChart = $('#monthWisePurchaseBarChart')
  // eslint-disable-next-line no-unused-vars
  var salesChart = new Chart($salesChart, {
    type: 'bar',
    data: {
      labels: _purchase_months,
      datasets: [
        {
          backgroundColor: '#007bff',
          borderColor: '#007bff',
          label: 'Purchase',
          data: _purchase_month_amounts
        }
      ]
    },
    options: {
      maintainAspectRatio: false,
      tooltips: {
        mode: mode,
        intersect: intersect
      },
      hover: {
        mode: mode,
        intersect: intersect
      },
      legend: {
        display: false
      },
      scales: {
        yAxes: [{
          // display: false,
          gridLines: {
            display: true,
            lineWidth: '4px',
            color: 'rgba(0, 0, 0, .2)',
            zeroLineColor: 'transparent'
          },
          ticks: $.extend({
            beginAtZero: true,

            // Include a dollar sign in the ticks
            callback: function (value) {
              if (value >= 1000) {
                value /= 1000
                value += 'k'
              }

              return 'Tk.' + value
            }
          }, ticksStyle)
        }],
        xAxes: [{
          display: true,
          gridLines: {
            display: false
          },
          ticks: ticksStyle
        }]
      }
    }
  })
@endcan

@can('daily-purchase-chart')
  var $visitorsChart = $('#dailyPurchaseReport')
  // eslint-disable-next-line no-unused-vars
  var visitorsChart = new Chart($visitorsChart, {
    data: {
      labels:purchase_days,
      datasets: [{
        type: 'line',
        data: last_30_days_purchase,
        backgroundColor: 'transparent',
        borderColor: '#007bff',
        pointBorderColor: '#007bff',
        pointBackgroundColor: '#007bff',
        fill: false
        // pointHoverBackgroundColor: '#007bff',
        // pointHoverBorderColor    : '#007bff'
      }]
    },
    options: {
      maintainAspectRatio: false,
      tooltips: {
        mode: mode,
        intersect: intersect
      },
      hover: {
        mode: mode,
        intersect: intersect
      },
      legend: {
        display: false
      },
      scales: {
        yAxes: [{
          // display: false,
          gridLines: {
            display: true,
            lineWidth: '4px',
            color: 'rgba(0, 0, 0, .2)',
            zeroLineColor: 'transparent'
          },
          ticks: $.extend({
            beginAtZero: true,
            suggestedMax: 200
          }, ticksStyle)
        }],
        xAxes: [{
          display: true,
          gridLines: {
            display: false
          },
          ticks: ticksStyle
        }]
      }
    }
  })
@endcan



@can('monthly-sales-return-chart')
  var $salesChart = $('#monthWiseSalesReturnBarChart')
  // eslint-disable-next-line no-unused-vars
  var salesChart = new Chart($salesChart, {
    type: 'bar',
    data: {
      labels: _sales_retun_months,
      datasets: [
        {
          backgroundColor: '#e83e8c',
          borderColor: '#007bff',
          label: 'Sales Return',
          data: _sales_return_month_amounts
        }
      ]
    },
    options: {
      maintainAspectRatio: false,
      tooltips: {
        mode: mode,
        intersect: intersect
      },
      hover: {
        mode: mode,
        intersect: intersect
      },
      legend: {
        display: false
      },
      scales: {
        yAxes: [{
          // display: false,
          gridLines: {
            display: true,
            lineWidth: '4px',
            color: 'rgba(0, 0, 0, .2)',
            zeroLineColor: 'transparent'
          },
          ticks: $.extend({
            beginAtZero: true,

            // Include a dollar sign in the ticks
            callback: function (value) {
              if (value >= 1000) {
                value /= 1000
                value += 'k'
              }

              return 'Tk.' + value
            }
          }, ticksStyle)
        }],
        xAxes: [{
          display: true,
          gridLines: {
            display: false
          },
          ticks: ticksStyle
        }]
      }
    }
  })
@endcan
@can('daily-sales-return-chart')
  var $visitorsChart = $('#dailySalesReturnReport')
  // eslint-disable-next-line no-unused-vars
  var visitorsChart = new Chart($visitorsChart, {
    data: {
      labels:sales_return_days,
      datasets: [{
        type: 'line',
        data: last_30_days_sales_return,
        backgroundColor: 'transparent',
        borderColor: '#e83e8c',
        pointBorderColor: '#e83e8c',
        pointBackgroundColor: '#e83e8c',
        fill: false
        // pointHoverBackgroundColor: '#007bff',
        // pointHoverBorderColor    : '#007bff'
      }]
    },
    options: {
      maintainAspectRatio: false,
      tooltips: {
        mode: mode,
        intersect: intersect
      },
      hover: {
        mode: mode,
        intersect: intersect
      },
      legend: {
        display: false
      },
      scales: {
        yAxes: [{
          // display: false,
          gridLines: {
            display: true,
            lineWidth: '4px',
            color: 'rgba(0, 0, 0, .2)',
            zeroLineColor: 'transparent'
          },
          ticks: $.extend({
            beginAtZero: true,
            suggestedMax: 200
          }, ticksStyle)
        }],
        xAxes: [{
          display: true,
          gridLines: {
            display: false
          },
          ticks: ticksStyle
        }]
      }
    }
  })
@endcan





@can('monthly-purchase-return-chart')

  var $salesChart = $('#monthWisePurchaseReturnBarChart')
  // eslint-disable-next-line no-unused-vars
  var salesChart = new Chart($salesChart, {
    type: 'bar',
    data: {
      labels: _purchase_return_months,
      datasets: [
        {
          backgroundColor: '#6610f2',
          borderColor: '#007bff',
          label: 'Purchase Return',
          data: _purchase_return_month_amounts
        }
      ]
    },
    options: {
      maintainAspectRatio: false,
      tooltips: {
        mode: mode,
        intersect: intersect
      },
      hover: {
        mode: mode,
        intersect: intersect
      },
      legend: {
        display: false
      },
      scales: {
        yAxes: [{
          // display: false,
          gridLines: {
            display: true,
            lineWidth: '4px',
            color: 'rgba(0, 0, 0, .2)',
            zeroLineColor: 'transparent'
          },
          ticks: $.extend({
            beginAtZero: true,

            // Include a dollar sign in the ticks
            callback: function (value) {
              if (value >= 1000) {
                value /= 1000
                value += 'k'
              }

              return 'Tk.' + value
            }
          }, ticksStyle)
        }],
        xAxes: [{
          display: true,
          gridLines: {
            display: false
          },
          ticks: ticksStyle
        }]
      }
    }
  })
@endcan
@can('daily-purchase-return-chart')
  var $visitorsChart = $('#dailyPurchaseReturnReport')
  // eslint-disable-next-line no-unused-vars
  var visitorsChart = new Chart($visitorsChart, {
    data: {
      labels:purchase_return_days,
      datasets: [{
        type: 'line',
        data: last_30_days_purchase_return,
        backgroundColor: 'transparent',
        borderColor: '#6610f2',
        pointBorderColor: '#6610f2',
        pointBackgroundColor: '#6610f2',
        fill: false
        // pointHoverBackgroundColor: '#007bff',
        // pointHoverBorderColor    : '#007bff'
      }]
    },
    options: {
      maintainAspectRatio: false,
      tooltips: {
        mode: mode,
        intersect: intersect
      },
      hover: {
        mode: mode,
        intersect: intersect
      },
      legend: {
        display: false
      },
      scales: {
        yAxes: [{
          // display: false,
          gridLines: {
            display: true,
            lineWidth: '4px',
            color: 'rgba(0, 0, 0, .2)',
            zeroLineColor: 'transparent'
          },
          ticks: $.extend({
            beginAtZero: true,
            suggestedMax: 200
          }, ticksStyle)
        }],
        xAxes: [{
          display: true,
          gridLines: {
            display: false
          },
          ticks: ticksStyle
        }]
      }
    }
  })
@endcan





 @can('monthly-damage-chart')

  var $salesChart = $('#monthWiseDamageBarChart')
  // eslint-disable-next-line no-unused-vars
  var salesChart = new Chart($salesChart, {
    type: 'bar',
    data: {
      labels: damage_months,
      datasets: [
        {
          backgroundColor: '#dc3545',
          borderColor: '#007bff',
          label: 'Damge Inventory',
          data: damage_month_amounts
        }
      ]
    },
    options: {
      maintainAspectRatio: false,
      tooltips: {
        mode: mode,
        intersect: intersect
      },
      hover: {
        mode: mode,
        intersect: intersect
      },
      legend: {
        display: false
      },
      scales: {
        yAxes: [{
          // display: false,
          gridLines: {
            display: true,
            lineWidth: '4px',
            color: 'rgba(0, 0, 0, .2)',
            zeroLineColor: 'transparent'
          },
          ticks: $.extend({
            beginAtZero: true,

            // Include a dollar sign in the ticks
            callback: function (value) {
              if (value >= 1000) {
                value /= 1000
                value += 'k'
              }

              return 'Tk.' + value
            }
          }, ticksStyle)
        }],
        xAxes: [{
          display: true,
          gridLines: {
            display: false
          },
          ticks: ticksStyle
        }]
      }
    }
  })
@endcan
 @can('daily-damage-chart')
  var $visitorsChart = $('#dailyDamageReport')
  // eslint-disable-next-line no-unused-vars
  var visitorsChart = new Chart($visitorsChart, {
    data: {
      labels:damage_days,
      datasets: [{
        type: 'line',
        data: last_30_days_damage,
        backgroundColor: 'transparent',
        borderColor: '#dc3545',
        pointBorderColor: '#dc3545',
        pointBackgroundColor: '#dc3545',
        fill: false
        // pointHoverBackgroundColor: '#007bff',
        // pointHoverBorderColor    : '#007bff'
      }]
    },
    options: {
      maintainAspectRatio: false,
      tooltips: {
        mode: mode,
        intersect: intersect
      },
      hover: {
        mode: mode,
        intersect: intersect
      },
      legend: {
        display: false
      },
      scales: {
        yAxes: [{
          // display: false,
          gridLines: {
            display: true,
            lineWidth: '4px',
            color: 'rgba(0, 0, 0, .2)',
            zeroLineColor: 'transparent'
          },
          ticks: $.extend({
            beginAtZero: true,
            suggestedMax: 200
          }, ticksStyle)
        }],
        xAxes: [{
          display: true,
          gridLines: {
            display: false
          },
          ticks: ticksStyle
        }]
      }
    }
  })
@endcan







})
</script>

@endsection