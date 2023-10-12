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
    <a class="nav-link"  href="{{url('trail-balance')}}" role="button">
          <i class="fas fa-search"></i>
        </a>
         <a style="cursor: pointer;" class="nav-link"  title="" data-caption="Print"  onclick="javascript:printDiv('printablediv')"
    data-original-title="Print"><i class="fas fa-print"></i></a>
    <a style="cursor: pointer;" onclick="fnExcelReport();" class="nav-link"  title="Excel Download" ><i class="fa fa-file-excel" aria-hidden="true"></i></a>
  </div>

<section class="invoice" id="printablediv">
    
  <table width="100%">
<tr><td colspan="8" align="center" style="font-size:24px; font-weight:bold">{{$settings->name ?? '' }}</td></tr>
<tr><td colspan="8" align="center" style="font-size:16px;">{{$settings->_address ?? '' }}</td></tr>
<tr><td colspan="8" align="center" style="font-size:16px;">{{$settings->_phone ?? '' }},{{$settings->_email ?? '' }}</td></tr>
<tr><td colspan="8" align="center" style="font-size:16px; font-weight:bold">{{$page_name}} </td></tr>
<tr><td colspan="8" align="center" style="font-size:12px; font-weight:bold">As on Date :&nbsp;{{ $previous_filter["_datex"] ?? '' }}</td></tr>
<tr><td colspan="8">@foreach($permited_branch as $p_branch)
                      @if(isset($previous_filter["_branch_id"]))
                        @if(in_array($p_branch->id,$previous_filter["_branch_id"])) 
                       <span style="background: #f4f6f9;margin-right: 2px;padding: 5px;"><b>{{ $p_branch["_name"] }}</b></span>    
                        @endif
                      @endif
                      @endforeach</td></tr>
</table>
    

    <!-- Table row -->
    <table class="cewReportTable">
          <thead>
          <tr>
            <th style="width: 5%;">ID</th>
            <th style="width: 35%;">Ledger</th>
            <th style="width: 10%;" class="text-right">Opening DR</th>
            <th style="width: 10%;" class="text-right">Opening CR</th>
            <th style="width: 10%;" class="text-right" >Current DR</th>
            <th style="width: 10%;" class="text-right" >Current Cr</th>
            <th style="width: 10%;" class="text-right" >Closing DR</th>
            <th style="width: 10%;" class="text-right" >Closing CR</th>
          </tr>
          
          
          </thead>
          <tbody>
            @php
            $_grand_total_opening_dr = 0;
            $_grand_total_opening_cr = 0;
            $_grand_total_current_dr = 0;
            $_grand_total_current_cr = 0;
            $_grand_total_closing_dr = 0;
            $_grand_total_closing_cr = 0;
            @endphp
            @forelse($group_array_values as $key=>$value)
            <tr>
              
                <td colspan="8" >
                  
                     <b> {{ $key ?? '' }} </b>
                    
                
              
              </td>
            </tr>
             @php
                    $_running_sub_opening_group_dr = 0;
                    $_running_sub_opening_group_cr = 0;
                    $_running_sub_current_group_dr = 0;
                    $_running_sub_current_group_cr = 0;
                    $_running_sub_closing_group_dr = 0;
                    $_running_sub_closing_group_cr = 0;
                  @endphp
                @forelse($value as $l_key=>$l_val)

               
                  @forelse($l_val as $_dkey=>$detail)
                  @php
                    

                     $_grand_total_opening_dr +=$detail->_o_dr_amount ?? 0;
                    $_grand_total_opening_cr +=$detail->_o_cr_amount ?? 0;
                    $_grand_total_current_dr +=$detail->_c_dr_amount ?? 0;
                    $_grand_total_current_cr +=$detail->_c_cr_amount ?? 0;
                    $_grand_total_closing_dr += ( $detail->_o_dr_amount +$detail->_c_dr_amount);
                    $_grand_total_closing_cr += ($detail->_o_cr_amount+$detail->_c_cr_amount);

                    $_running_sub_opening_group_dr  +=$detail->_o_dr_amount ?? 0;
                    $_running_sub_opening_group_cr +=$detail->_o_cr_amount ?? 0;
                    $_running_sub_current_group_dr +=$detail->_c_dr_amount ?? 0;
                    $_running_sub_current_group_cr +=$detail->_c_cr_amount ?? 0;
                    $_running_sub_closing_group_dr += ( $detail->_o_dr_amount +$detail->_c_dr_amount);
                    $_running_sub_closing_group_cr += ($detail->_o_cr_amount+$detail->_c_cr_amount);

                  @endphp
                  
                    <tr>
                    
                    <td style="text-align: left;">&nbsp; &nbsp;{{ $detail->_account_ledger ?? '' }} </td>
                    <td style="text-align: left;">{{ $detail->_l_name ?? '' }} </td>
                    <td style="text-align: right;">{{ _report_amount($detail->_o_dr_amount ?? 0) }} </td>
                    <td style="text-align: right;">{{ _report_amount($detail->_o_cr_amount ?? 0) }} </td>
                    <td style="text-align: right;">{{ _report_amount($detail->_c_dr_amount ?? 0) }} </td>
                    <td style="text-align: right;">{{ _report_amount($detail->_c_cr_amount ?? 0) }} </td>
                    <td style="text-align: right;">{{ _report_amount(  $_running_sub_closing_group_dr ) }} </td>
                    <td style="text-align: right;">{{ _report_amount(  $_running_sub_closing_group_cr ) }} </td>

                  </tr>

                  @empty
                  @endforelse

                 
                

                @empty
                @endforelse



              <tr>
              
                <td colspan="2" style="text-align: left;background: #f5f9f9;">&nbsp; &nbsp;Sub Total of {{ $key ?? '' }}:  </td>
                <td style="text-align: right;background: #f5f9f9;"> <b>{{ _report_amount($_running_sub_opening_group_dr ?? 0) }} </b> </td>
                <td style="text-align: right;background: #f5f9f9;"> <b>{{ _report_amount($_running_sub_opening_group_cr ?? 0) }}</b> </td>
                <td style="text-align: right;background: #f5f9f9;"> <b>{{ _report_amount($_running_sub_current_group_dr ?? 0) }}</b> </td>
                <td style="text-align: right;background: #f5f9f9;"> <b>{{ _report_amount($_running_sub_current_group_cr ?? 0) }}</b> </td>
                <td style="text-align: right;background: #f5f9f9;"> <b>{{ _report_amount($_running_sub_closing_group_dr ?? 0) }}</b> </td>
                <td style="text-align: right;background: #f5f9f9;"> <b>{{ _report_amount($_running_sub_closing_group_cr ?? 0) }}</b> </td>

            </tr>
           

            @empty
            @endforelse
          <tr>
              
                <td colspan="2" style="text-align: left;background: #f5f9f9;"> &nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;<b>Grand Total </b> </td>
                <td style="text-align: right;background: #f5f9f9;"> <b>{{_report_amount($_grand_total_opening_dr) }}</b> </td>
                <td style="text-align: right;background: #f5f9f9;"> <b>{{_report_amount($_grand_total_opening_cr) }}</b> </td>
                <td style="text-align: right;background: #f5f9f9;"> <b>{{_report_amount($_grand_total_current_dr) }}</b> </td>
                <td style="text-align: right;background: #f5f9f9;"> <b>{{_report_amount($_grand_total_current_cr) }}</b> </td>
                <td style="text-align: right;background: #f5f9f9;"> <b>{{_report_amount($_grand_total_closing_dr) }}</b> </td>
                <td style="text-align: right;background: #f5f9f9;"> <b>{{_report_amount($_grand_total_closing_cr) }}</b> </td>
                
            </tr>
          </tbody>
          <tfoot>
            <tr>
              <td colspan="8">
                <div class="row">
                   @include('backend.message.invoice_footer')
                </div>
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
