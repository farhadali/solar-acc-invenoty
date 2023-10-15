@extends('backend.layouts.app')
@section('title',$page_name)

@section('content')
<style type="text/css">
 
  @media print {
   .table th {
    vertical-align: top;
    color: #000;
    background-color: #fff; 
}
}
  </style>
<div class="_report_button_header">
 <a class="nav-link"  href="{{url('budgets')}}" role="button"><i class="fa fa-arrow-left"></i></a>

    
    <a style="cursor: pointer;" class="nav-link"  title="Print" onclick="javascript:printDiv('printablediv')"><i class="fas fa-print"></i></a>
      <a style="cursor: pointer;" onclick="fnExcelReport();" class="nav-link"  title="Excel Download" ><i class="fa fa-file-excel" aria-hidden="true"></i></a>
       @include('backend.message.message')
  </div>

<section class="invoice" id="printablediv">
   <div class="container-fluid">
    <table  class="table table-borderd">
      <tr>
        <td colspan="2" style="border:1px solid #000;">
          <h3>PROJECT BUDGET</h3>
        </td>
        <td colspan="2" class="text-right" style="border:1px solid #000;">
          <p>TIME : {{_view_date_formate($data->_start_date)}} TO {{ _view_date_formate($data->_end_date) }}</p>
        </td>
      </tr>
      <tr>
        <td colspan="4" style="border:1px solid #000;"><b>PROJECT NAME:{!! $data->_master_cost_center->_name ?? '' !!}</b></td>
      </tr>
      <tr>
        <td colspan="2" style="border:1px solid #000;"><b>PO NUMBER:</b></td>
        <td colspan="2" style="border:1px solid #000;"><b>DATE:</b></td>
      </tr>
      
      <tr>
        <td style="border:1px solid #000;"><b>PROJECT VALUE: {!! _report_amount($data->_project_value ?? 0) !!}</b></td>
        <td style="border:1px solid #000;"><b>NET PROJECT VALUE: {!! _report_amount($data->_income_amount ?? 0) !!}</b></td>
        @php
        $profit = (($data->_income_amount)-($data->_material_amount+$data->_expense_amount));
        @endphp
        <td style="border:1px solid #000;"><b>PROFIT: {!! _report_amount($profit) !!}</b></td>
        <td style="border:1px solid #000;"><b>PROFIT RATE:{!! _report_amount(($profit/$data->_income_amount)*100) !!}%</b></td>
      </tr>
<tr>
  <td colspan="4" style="height: 50px;"></td>
</tr>
      <tr>
        <td colspan="4" style="border:1px solid #000;"><b>REPORT SUMMARY:</b></td>
      </tr>
      <tr>
        <td colspan="2" style="background:#8991d6;border:1px solid #000;"><b>TOTAL BUDGET</b></td>
        <td style="background:#dacdf0;border:1px solid #000;"><b>TOTAL EXPENDITURE</b></td>
        <td style="background:#ebecb4;border:1px solid #000;"><b>REMAINING TO SPEND</b></td>
      </tr>
           @php
          $total_cur_exp = array_sum($total_expenses);
          $total_budgeted_exp= ($data->_material_amount+$data->_expense_amount);
          $remain_exp = ($total_budgeted_exp-$total_cur_exp);
          @endphp
      <tr>
        <td colspan="2" style="background:#8991d6;border:1px solid #000;"><b>{!! _report_amount($data->_material_amount+$data->_expense_amount) !!}</b></td>
        <td style="background:#dacdf0;border:1px solid #000;">{!! _report_amount(array_sum($total_expenses)) !!}</td>
        <td style="background:#ebecb4;border:1px solid #000;">{!! _report_amount(($data->_material_amount+$data->_expense_amount)-array_sum($total_expenses)) !!}</</td>
      </tr>

      <tr>
        <td colspan="2" style="background:#8991d6;border:1px solid #000;"><b>RATE-100%</b></td>
        <td style="background:#dacdf0;border:1px solid #000;">{!! _report_amount($total_cur_exp/$total_budgeted_exp)*100 !!} %</td>
        <td style="background:#ebecb4;border:1px solid #000;">{!! _report_amount(($remain_exp/$total_budgeted_exp)*100) !!} %</td>
      </tr>
@if(sizeof($_budget_item_details) > 0)
<tr>
  <td colspan="4" style="height: 50px;"></td>
</tr>
<tr>
  <td colspan="4" style="border:1px solid #000;"><b>MATERIAL EXPENSES</b></td>
</tr>


<tr>
  <td colspan="4">
     <table  class="table table-borderd">
      <thead>
        <tr>
          <td colspan="4" class="text-center" style="border:1px solid #000;">BUDGETED MATERIAL</td>
          <td colspan="3" class="text-center" style="border:1px solid #000;">USED MATERIAL</td>
          <td colspan="2" class="text-center" style="border:1px solid #000;">REAMINING BALANCE</td>
        </tr>
        <tr>
          <td style="width:20%;text-align: left;border:1px solid #000;">ITEM NAME</td>
          <td style="width:10%;text-align: left;border:1px solid #000;">QTY</td>
          <td style="width:10%;text-align: left;border:1px solid #000;">UNIT</td>
          <td style="width:10%;text-align: left;border:1px solid #000;">AMOUNT</td>

          <td style="width:10%;text-align: left;background: yellowgreen;border:1px solid #000;">QTY</td>
          <td style="width:10%;text-align: left;background: yellowgreen;border:1px solid #000;">UNIT</td>
          <td style="width:10%;text-align: left;background: yellowgreen;border:1px solid #000;">AMOUNT</td>

          <td style="width:10%;text-align: left;background: lightyellow;border:1px solid #000;">REMAINING QTY</td>
          <td style="width:10%;text-align: left;background: lightyellow;border:1px solid #000;">REMAINING BALANCE</td>
        </tr>
      </thead>

      @php
          $total_bud_mat_exp_amount = 0;
          $total_used_exp_material_amount= 0;
          $total_remain_exp_amount = 0;
          @endphp

      @forelse($_budget_item_details as $key=>$val)
      <tr>
        <td style="border:1px solid #000;">{!! $val->_item ?? '' !!}</td>
        <td style="border:1px solid #000;">{!! _report_amount($val->total_qty ?? 0) !!}</td>
        <td style="border:1px solid #000;">{!! $val->_unit_name ?? '' !!}</td>
        <td style="border:1px solid #000;">{!! _report_amount($val->_total_value ?? 0) !!}</td>

        <td style="background: yellowgreen;border:1px solid #000;">{!! _report_amount(find_bud_item_column($val->_item_id,'total_qty',$cost_center_wise_items)) !!}</td>
        <td style="background: yellowgreen;border:1px solid #000;">{!! find_bud_item_column($val->_item_id,'unit_name',$cost_center_wise_items) !!}</td>
        <td style="background: yellowgreen;border:1px solid #000;">{!! find_bud_item_column($val->_item_id,'_total_value',$cost_center_wise_items) !!}</td>


        <td style="background: lightyellow;border:1px solid #000;">{!! _report_amount($val->total_qty+find_bud_item_column($val->_item_id,'total_qty',$cost_center_wise_items)) !!}</td>
        <td style="background: lightyellow;border:1px solid #000;">{!! _report_amount($val->_total_value+find_bud_item_column($val->_item_id,'_total_value',$cost_center_wise_items)) !!}</td>
      </tr>

      @php
          $total_bud_mat_exp_amount += $val->_total_value ?? 0;
          $total_used_exp_material_amount += find_bud_item_column($val->_item_id,'_total_value',$cost_center_wise_items);
          $total_remain_exp_amount += ($val->_total_value+find_bud_item_column($val->_item_id,'_total_value',$cost_center_wise_items));
          @endphp


      @empty
      @endforelse
      <tr>
        <td style="border:1px solid #000;"><b>TOTAL</b></td>
        <td style="border:1px solid #000;"><b></b></td>
        <td style="border:1px solid #000;"><b></b></td>
        <td style="border:1px solid #000;"><b>{!! _report_amount($total_bud_mat_exp_amount) !!}</b></td>
        <td style="border:1px solid #000;"><b></b></td>
        <td style="border:1px solid #000;"><b></b></td>
        <td style="border:1px solid #000;"><b>{!! _report_amount($total_used_exp_material_amount) !!}</b></td>
        <td style="border:1px solid #000;"><b></b></td>
        <td style="border:1px solid #000;"><b>{!! _report_amount($total_remain_exp_amount) !!}</b></td>

      </tr>
    </table>
  </td>
</tr>



@endif




<tr>
  <td colspan="4" style="height: 50px;"></td>
</tr>

      <tr>
        <td style="background: #101a1775;border:1px solid #000;"><b>HEAD OF COST</b></td>
        <td style="background: #101a1775;border:1px solid #000;"><b>BUDGET</b></td>
        <td style="background: #101a1775;border:1px solid #000;"><b>EXPENDITURE</b></td>
        <td style="background: #101a1775;border:1px solid #000;"><b>REMAINING TO SPEND</b></td>
      </tr>
@php
$total_budget_amount =0;
$total_budget_exp_amount=0;
$total_remain_exp_amount =0;
@endphp
      @forelse($budget_expenses as $key=>$val)
      <tr>
        <td style="background: #f8f8f8;border:1px solid #000;">{!! $val->_ledger_name ?? '' !!}</td>
        <td style="background: #f8f8f8;border:1px solid #000;">{!! _report_amount($val->_total_amount ?? 0) !!}</td>
        <td style="background: #f8f8f8;border:1px solid #000;">{!! _report_amount(find_bud_amount($val->_ledger_id,$bud_orginal_exp)) !!}</td>
        <td style="background: #ebecb4;border:1px solid #000;">{!! _report_amount(($val->_total_amount ?? 0)-find_bud_amount($val->_ledger_id,$bud_orginal_exp)) !!}</td>
      </tr>

      @php
$total_budget_amount +=$val->_total_amount ?? 0;
$total_budget_exp_amount +=find_bud_amount($val->_ledger_id,$bud_orginal_exp);
$total_remain_exp_amount +=($val->_total_amount ?? 0)-find_bud_amount($val->_ledger_id,$bud_orginal_exp);
@endphp
      @empty
      @endforelse

      @if(sizeof($exces_expnesses) > 0)
      <tr>
        <td colspan="4" style="border:1px solid #000;"><b>EXTRA EXPENSES</b></td>
      </tr>
      @forelse($exces_expnesses as $key=>$val)
      <tr>
        <td style="background: #f8f8f8;border:1px solid #000;">{!! find_bud_column($val,'_ledger_name',$bud_orginal_exp) !!}</td>
        <td style="background: #f8f8f8;border:1px solid #000;"></td>
        <td style="background: #f8f8f8;border:1px solid #000;">{!! _report_amount(find_bud_column($val,'_total_amount',$bud_orginal_exp)) !!}</td>
        <td style="background: #ebecb4;border:1px solid #000;"></td>
      </tr>

       @php

$total_budget_exp_amount +=find_bud_column($val,'_total_amount',$bud_orginal_exp);
@endphp
      @empty
      @endforelse

      @endif
      <tr>
        <td style="background: #101a1775;border:1px solid #000;"><b>TOTAL</b></td>
        <td style="background: #101a1775;border:1px solid #000;"><b>{!! _report_amount($total_budget_amount) !!}</b></td>
        <td style="background: #101a1775;border:1px solid #000;"><b>{!! _report_amount($total_budget_exp_amount) !!}</b></td>
        <td style="background: #101a1775;border:1px solid #000;"><b>{!! _report_amount($total_remain_exp_amount) !!}</b></td>
      </tr>

       


    </table>
  </div>
   </section>

   @endsection

@section('script')


@endsection