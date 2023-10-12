@extends('backend.layouts.app')
@section('title',$page_name)

@section('content')
<div class="wrapper print_content">
  <style type="text/css">
  .table td, .table th {
    padding: 0.10rem;
    vertical-align: top;
    border-top: 1px solid #dee2e6;
}
  </style>
<div class="_report_button_header">
    <a class="nav-link"  href="{{url('stock-possition')}}" role="button">
          <i class="fas fa-search"></i>
        </a>
    <a style="cursor: pointer;" class="nav-link"  title="Print" onclick="javascript:printDiv('printablediv')"><i class="fas fa-print"></i></a>
      <a style="cursor: pointer;" onclick="fnExcelReport();" class="nav-link"  title="Excel Download" ><i class="fa fa-file-excel" aria-hidden="true"></i></a>
  </div>

<section class="invoice" id="printablediv">
    

        <table class="table" style="border:none;width: 100%;">
          <tr>
            
            <td style="border:none;width: 100%;text-align: center;">
              <table class="table" style="border:none;">
                <tr style="line-height: 16px;" > <td class="text-center" style="border:none;font-size: 24px;"><b>{{$settings->name ?? '' }}</b></td> </tr>
                <tr style="line-height: 16px;" > <td class="text-center" style="border:none;">{{$settings->_address ?? '' }}</td></tr>
                <tr style="line-height: 16px;" > <td class="text-center" style="border:none;">{{$settings->_phone ?? '' }},{{$settings->_email ?? '' }}</td></tr>
                 <tr style="line-height: 16px;" > <td class="text-center" style="border:none;"><b>{{$page_name}} </b></td> </tr>
                 <tr style="line-height: 16px;" > <td class="text-center" style="border:none;"><strong>Date:{{ $previous_filter["_datex"] ?? '' }} To {{ $previous_filter["_datey"] ?? '' }}</strong></td> </tr>
                 <tr style="line-height: 16px;" > <td class="text-center" style="border:none;">
                  <br/><b>@foreach($permited_branch as $p_branch)
                      @if(isset($previous_filter["_branch_id"]))
                        @if(in_array($p_branch->id,$previous_filter["_branch_id"])) 
                       <span style="background: #f4f6f9;margin-right: 2px;padding: 5px;"><b>{{ $p_branch["_name"] }}</b></span>    
                        @endif
                      @endif
                      @endforeach </b></td> </tr>
              </table>
            </td>
            
          </tr>
        </table>


    <!-- Table row -->
   <table class="cewReportTable">
          <thead>
          <tr>
             

            <th >Item Name </th>
            <th style="width: 10%;">Unit</th>
            <th style="width: 10%;" class="text-right">Opening</th>
            <th style="width: 10%;" class="text-right">Stock In</th>
            <th style="width: 10%;" class="text-right">Stock Out</th>
            <th style="width: 10%;" class="text-right">Closing</th>
          </tr>
          
          
          </thead>
          <tbody>
            @php
              $_total_opening = 0;
              $_total_stockin = 0;
              $_total_stockout = 0;
              $_total_balance = 0;
               $remove_duplicate_branch=array();
            @endphp
            @forelse($group_array_values as $branch_key=> $branch_data)
             @if(sizeof($_branch_ids) > 1 )
            <tr>
              <th colspan="7">
                Branch: {{ _branch_name($branch_key) }}
              </th>
            </tr>
            @endif

            @forelse($branch_data as $cost_key=> $cost_data)
             @if(sizeof($_cost_center_ids) > 1 )
            <tr>
              <th colspan="7">
                Cost Center: {{ _cost_center_name($cost_key) }}
              </th>
            </tr>
            @endif

             @forelse($cost_data as $store_key=> $store_data)
              @if(sizeof($_stores) > 1 )
             <tr>
              <th colspan="7">
                Store: {{ _store_name($store_key) }}
              </th>
            </tr>
            @endif

             @forelse($store_data as $category_key=> $category_data)
             <tr>
              <th colspan="7">
               Category: {{ _category_name($category_key) }}
              </th>
            </tr>


             @php
              $_sub_total_opening =0;
              $_sub_total_stockin =0;
              $_sub_total_stockout =0;
              $_sub_total_balance =0;
            @endphp

             @forelse($category_data as $g_value)

            @php
             $_sub_total_opening += $g_value->_opening;
              $_sub_total_stockin += $g_value->_stockin;
              $_sub_total_stockout += $g_value->_stockout;
              $_sub_total_balance += ($g_value->_opening+$g_value->_stockin+$g_value->_stockout);

              $_total_opening += $g_value->_opening;
              $_total_stockin += $g_value->_stockin;
              $_total_stockout += $g_value->_stockout;
              $_total_balance += ($g_value->_opening+$g_value->_stockin+$g_value->_stockout);
            @endphp
            <tr>
             

            <td>{!! $g_value->_name ?? '' !!} </td>
            <td style="width: 10%;">{!! $g_value->_unit ?? '' !!}</td>
            <td style="width: 10%;" class="text-right">{!! _report_amount($g_value->_opening) !!}</td>
            <td style="width: 10%;" class="text-right">{!! _report_amount($g_value->_stockin) !!}</td>
            <td style="width: 10%;" class="text-right">{!! _report_amount($g_value->_stockout) !!}</td>
            <td style="width: 10%;" class="text-right">{{ _report_amount($g_value->_opening+$g_value->_stockin+$g_value->_stockout) }}</td>
          </tr>
          @empty
          @endforelse

          <tr>
            

            <th colspan="2" class="text-left" >Sub Total </th>
            <th style="width: 10%;" class="text-right">{!! _report_amount($_sub_total_opening) !!}</th>
            <th style="width: 10%;" class="text-right">{!! _report_amount($_sub_total_stockin) !!}</th>
            <th style="width: 10%;" class="text-right">{!! _report_amount($_sub_total_stockout) !!}</th>
            <th style="width: 10%;" class="text-right">{!! _report_amount($_sub_total_balance) !!}</th>
          </tr>

          @empty
          @endforelse

          @empty
          @endforelse

          @empty
          @endforelse
           

           @empty
          @endforelse
          <tr>
            

            <th colspan="2" class="text-left" >Grand Total </th>
            <th style="width: 10%;" class="text-right">{!! _report_amount($_total_opening) !!}</th>
            <th style="width: 10%;" class="text-right">{!! _report_amount($_total_stockin) !!}</th>
            <th style="width: 10%;" class="text-right">{!! _report_amount($_total_stockout) !!}</th>
            <th style="width: 10%;" class="text-right">{!! _report_amount($_total_balance) !!}</th>
          </tr>
            
            
          </tbody>
          <tfoot>
            <tr>
              <td colspan="9">
                 @include('backend.message.invoice_footer')
              </td>
            </tr>
          </tfoot>
        </table>


    
    <!-- /.row -->
  </section>

</div>
@endsection

@section('script')


@endsection
