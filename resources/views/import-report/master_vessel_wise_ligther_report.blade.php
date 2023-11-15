@extends('backend.layouts.app')
@section('title',$page_name)

@section('content')
<style type="text/css">
  .invoice {
    background-color: #fff;
    border: none;
    position: relative;
}
</style>

  <div class="content ">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
          <div class="card">
            <div class="card-header">
                 <h4 class="text-center">{{ $page_name ?? '' }}</h4>
            </div>
 
         
            <div class="card-body filter_body" >
               <form  action="{{url('master_vessel_wise_ligther_report')}}" method="GET">
                @csrf
                

                    <div class="row">
                      <select class="form-control " name="import_invoice_no" required>
                        <option value="">{{__('label.select')}}</option>
                        @forelse($importInvoices as $key=>$val)
                        <option value="{{$val->id}}" @if(isset($request->import_invoice_no) && $request->import_invoice_no==$val->id) selected @endif >{!! $val->_order_number !!} || {!! $val->_mother_vessel->_name ?? '' !!}</option>
                        @empty
                        @endforelse
                      </select>
                    </div>
                     <div class="row mt-3">
                         <div class="col-xs-6 col-sm-6 col-md-6 ">
                            <button type="submit" class="btn btn-success submit-button form-control"><i class="fa fa-credit-card mr-2" aria-hidden="true"></i> Report</button>
                        </div>
                         <div class="col-xs-6 col-sm-6 col-md-6 ">
                                     <a href="{{url('master_vessel_wise_ligther_report')}}" class="btn btn-danger form-control" title="Search Reset"><i class="fa fa-retweet mr-2"></i> </a>
                        </div>
                        <br><br>
                     </div>
                    {!! Form::close() !!}
                
              </div>
          
          </div>


          @if(isset($request->import_invoice_no))

          <div class="row">
            <div class="_report_button_header" style="width:100%;">
                <a class="nav-link"  href="{{url('import-report-dashboard')}}" role="button">
                      <i class="fa fa-arrow-left"></i>
                    </a>
             <a style="cursor: pointer;" class="nav-link"  title="Print" onclick="javascript:printDiv('printablediv')"><i class="fas fa-print"></i></a>
                  <a style="cursor: pointer;" onclick="fnExcelReport();" class="nav-link"  title="Excel Download" ><i class="fa fa-file-excel" aria-hidden="true"></i></a>
              </div>
      </div>
            <section class="invoice" id="printablediv">
              <div style="text-align: center;">
                <h3 class="mb-1">{!! $single_data->_organization->_name ?? '' !!}</h3>
                <address class="mb-1">{!! $single_data->_organization->_address ?? '' !!}</address>
                <h4 class="mb-1">{{ $report_title ?? '' }}</h4>
                <p class="mb-1">Master Vessel: <b>{{ $single_data->_mother_vessel->_name ?? '' }}</b></p>
              </div>

              <table class="cewReportTable">
                  <thead>
                  <tr>
                   <th style="border:1px solid silver;width: 10%;" class="text-left" >Lighter<br>Sl.</th>
                   <th style="border:1px solid silver;width: 10%;" class="text-left" >Name of <br>Vessel</th>
                    <th style="border:1px solid silver;width: 10%;" class="text-left" >Capacity </th>
                    <th style="border:1px solid silver;width: 10%;min-width: 168px;" class="text-left" >Loading Point</th>
                    <th style="border:1px solid silver;width: 10%;min-width: 168px;" class="text-left" >Destination </th>
                    <th style="border:1px solid silver;width: 10%;min-width: 187px;" class="text-left" >Loading Date & Time </th>
                    <th style="border:1px solid silver;width: 10%;min-width: 187px;" class="text-left" >Arrival Date & Time </th>
                    <th style="border:1px solid silver;width: 10%;min-width: 187px;" class="text-left" >Discharge Date & Time </th>
                    <th style="border:1px solid silver;width: 10%;" class="text-left" >Approx. QTY as per<br>draft survey at CTG<br>(MT) </th>
                    <th style="border:1px solid silver;width: 10%;" class="text-left" >Discharge point<br>Weight Scale Weight<br>(MT)</th>
                    <th style="border:1px solid silver;width: 10%;" class="text-left" >Diffrence</th>
                  </tr>
                  
                  
                  </thead>
                  <tbody>

                    @php
                    $_capacity_qty      =0;
                    $_total_sending_qty =0;
                    $_total_actual_qty  =0;
                    $_diff_qty          =0;
                    @endphp

                    @forelse($datas as $key=>$data)

                    @php
                    $_capacity_qty      +=$data->_vessel_detail->_capacity ?? 0;
                    $_total_sending_qty +=$data->_total_expected_qty ?? 0;
                    $_total_actual_qty  +=$data->_total_qty ?? 0;
                    $_diff_qty          +=(($data->_total_expected_qty ?? 0) - ($data->_total_qty ?? 0));
                    @endphp


                    <tr>
                    <td style="border:1px solid silver;white-space: nowrap;" class="text-left" >{{($key+1)}}</td>
                    <td style="border:1px solid silver;white-space: nowrap;" class="text-left" >{!! $data->_vessel_detail->_lighter_info->_name ?? '' !!}</td>
                    <td style="border:1px solid silver;white-space: nowrap;" class="text-left" >{!! $data->_vessel_detail->_capacity ?? '' !!} </td>
@php
$_route_infos = $data->_route_info ?? [];
@endphp
                    <td colspan="5" style="width:50%;">
                      <table style="width:100%;">
                        @forelse($_route_infos as $rKey=>$rVal)
                          <tr>
                            <td style="border:1px solid silver;width: 20%;white-space: nowrap;min-width: 150px;" class="text-left" >{{_store_name($rVal->_loading_point)}}</td>
                          <td style="border:1px solid silver;width: 20%;white-space: nowrap;min-width: 150px;" class="text-left" >{{_store_name($rVal->_unloading_point)}} </td>
                          <td style="border:1px solid silver;width: 20%;white-space: nowrap;min-width: 187px;" class="text-left" >{{_view_date_formate($rVal->_loading_date_time ?? '')}} </td>
                          <td style="border:1px solid silver;width: 20%;white-space: nowrap;min-width: 187px;" class="text-left" >{{_view_date_formate($rVal->_arrival_date_time ?? '')}} </td>
                          <td style="border:1px solid silver;width: 20%;white-space: nowrap;min-width: 187px;" class="text-left" >{{_view_date_formate($rVal->_discharge_date_time ?? '')}} </td>
                          </tr>
                        @empty
                        @endforelse
                        
                      </table>
                      
                    </td>

                    

                    <td style="border:1px solid silver;" class="text-right" >{!! _report_amount($data->_total_expected_qty ?? 0) !!}</td>
                    <td style="border:1px solid silver;" class="text-right" >{!! _report_amount($data->_total_qty ?? 0) !!}</td>
                    <td style="border:1px solid silver;" class="text-right" >{!! _report_amount((($data->_total_expected_qty ?? 0) - ($data->_total_qty ?? 0))) !!}</td>
                  </tr>

                    @empty
                    @endforelse
                    </tbody>
                    <tfoot>
                      <tr>
                        <th colspan="2" style="border:1px solid silver;">Total</th>
                        <th class="text-right"  style="border:1px solid silver;"> {{_report_amount($_capacity_qty)}}</th>
                        <th colspan="5"  style="border:1px solid silver;"></th>
                        <th class="text-right" style="border:1px solid silver;">{{_report_amount($_total_sending_qty)}}</th>
                        <th class="text-right" style="border:1px solid silver;">{{_report_amount($_total_actual_qty)}}</th>
                        <th class="text-right" style="border:1px solid silver;">{{_report_amount($_diff_qty)}}</th>


                      </tr>
                    </tfoot>
                  </table>
            </section>
          </div>
          @endif
        </div>
        <!-- /.row -->
      </div>
    </div>  
</div>



@endsection

@section('script')

<script type="text/javascript">

</script>
@endsection

