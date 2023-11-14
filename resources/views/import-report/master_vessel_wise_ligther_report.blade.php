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

              <table class="cewReportTable">
                  <thead>
                  <tr>
                   <th style="border:1px solid silver;" class="text-left" >Lighter<br>Sl.</th>
                   <th style="border:1px solid silver;" class="text-left" >Name of <br>Vessel</th>
                    <th style="border:1px solid silver;" class="text-left" >Capacity </th>
                    <th style="border:1px solid silver;" class="text-left" >Loading Point</th>
                    <th style="border:1px solid silver;" class="text-left" >Destination </th>
                    <th style="border:1px solid silver;" class="text-left" >Loading Date & Time </th>
                    <th style="border:1px solid silver;" class="text-left" >Arrival Date & Time </th>
                    <th style="border:1px solid silver;" class="text-left" >Discharge Date & Time </th>
                    <th style="border:1px solid silver;" class="text-left" >Approx. QTY as per<br>draft survey at CTG<br>(MT) </th>
                    <th style="border:1px solid silver;" class="text-left" >Discharge point<br>Weight Scale Weight<br>(MT)</th>
                    <th style="border:1px solid silver;" class="text-left" >Diffrence</th>
                  </tr>
                  
                  
                  </thead>
                  <tbody>

                    @forelse($datas as $key=>$data)
                    <tr>
                    <td style="border:1px solid silver;" class="text-left" >{{($key+1)}}</td>
                    <td style="border:1px solid silver;" class="text-left" >{!! $data->_lighter_info->_name ?? '' !!}</td>
                    <td style="border:1px solid silver;" class="text-left" >{!!$data->_capacity ??  $data->_lighter_info->_capacity ?? '' !!} </td>
                    <td style="border:1px solid silver;" class="text-left" >{{_store_name($data->_loding_point)}}</td>
                    <td style="border:1px solid silver;" class="text-left" >{{_store_name($data->_unloading_point)}} </td>
                    <td style="border:1px solid silver;" class="text-left" >{{_view_date_formate($data->_loading_date_time ?? '')}} </td>
                    <td style="border:1px solid silver;" class="text-left" >{{_view_date_formate($data->_arrival_date_time ?? '')}} </td>
                    <td style="border:1px solid silver;" class="text-left" >{{_view_date_formate($data->_discharge_date_time ?? '')}} </td>
                    <td style="border:1px solid silver;" class="text-right" >{!! _report_amount($data->_total_expected_qty ?? 0) !!}</td>
                    <td style="border:1px solid silver;" class="text-right" >{!! _report_amount($data->_total_qty ?? 0) !!}</td>
                    <td style="border:1px solid silver;" class="text-right" >{!! _report_amount(($data->_total_expected_qty ?? 0) - ($data->_total_qty ?? 0)) !!}</td>
                  </tr>

                    @empty
                    @endforelse
                    </tbody>
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

