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
.table td{
  border:1px solid silver;
}
}
  </style>
<div class="_report_button_header">
 <a class="nav-link"  href="{{url('individual-replacement')}}" role="button"><i class="fa fa-arrow-left"></i></a>
 
    
    <a style="cursor: pointer;" class="nav-link"  title="Print" onclick="javascript:printDiv('printablediv')"><i class="fas fa-print"></i></a>
      <a style="cursor: pointer;" onclick="fnExcelReport();" class="nav-link"  title="Excel Download" ><i class="fa fa-file-excel" aria-hidden="true"></i></a>
     @include('backend.message.message')
  </div>

<section class="invoice" id="printablediv">

     <div class="row">
      <div class="col-3">
       <h3 class="page-header">
        <img src="{{url('/')}}/{{$settings->logo}}" alt="{{$settings->name ?? '' }}" style="height: 60px;width: 60px"  > {{$settings->title ?? '' }}
       
       </h3>
      </div>
      <div class="col-6 text-center">
         {{ $settings->_top_title ?? '' }}
       <h4 class="page-header text-center">
        {{$page_name}}
       </h4>
      </div>
      <div class="col-3">
       <h3 class="page-header">
        <small class="float-right">Date: {!! _view_date_formate($data->_date ?? '') !!}</small>
       </h3>
      </div>

     </div>
      
     <div class="row invoice-info">
      <div class="col-sm-4 invoice-col">
       
       <address>
        <strong>{{$settings->name ?? '' }}</strong><br>
        Address: {{$settings->_address ?? '' }}<br>
        Phone: {{$settings->_phone ?? '' }}<br>
        Email: {{$settings->_email ?? '' }}
       </address>
      </div>

      <div class="col-sm-4 invoice-col">
       Customer:
       <address>
        <strong>{{$data->_ledger->_name ?? '' }}</strong><br>
        Phone: {{$data->_ledger->_phone ?? '' }}<br>
        Email: {{$data->_ledger->_email ?? '' }}
       </address>
      </div>
      

      <div class="col-sm-4 invoice-col">
       
        {{ invoice_barcode($data->_order_number ?? '') }}
                    
       <b>Invoice No: {{ $data->_order_number ?? '' }}</b><br>
       <b>Referance:</b> {!! $data->_referance ?? '' !!}<br>
       <b>Status: </b> @if($data->_status==1) Delivered @else <span class="_required">Delivery Not Done</span> @endif <br>
      
       <b>Created By:</b> {!! $data->_user_name ?? '' !!}<br>
       <b>Branch:</b> {{$data->_master_branch->_name ?? ''}}
      </div>

     </div>


     <div class="row">
      <div class="col-12 table-responsive">
       <table class="table table-striped _grid_table">
        <thead>
         <tr>
          <th class="text-left" >Complain No</th>
          <th class="text-left" >Invoice No</th>
          <th class="text-left" >Old Product</th>
          <th class="text-left" >Old Serial No</th>
          <th class="text-left" >New Product</th>
          <th class="text-left" >New Serial No</th>
          <th class="text-left" >Adjustment</th>
         </tr>
        </thead>
        <tbody>
          <tr>
            <td>{{ $data->_warranty_detail->_order_number ?? '' }}</td>
            <td>{{ $data->_warranty_detail->sales_info->_order_number ?? '' }}</td>
            @forelse($data->_master_in_details as $in_key=>$in_detail)
            <td>{{ $in_detail->_items->_name ?? '' }}</td>
            <td>{{ $in_detail->_barcode ?? '' }}</td>
            @empty
            @endforelse
            @forelse($data->_master_details as $out_key=>$out_del)
            <td>{{ $out_del->_items->_name ?? '' }}</td>
            <td>{{ $out_del->_barcode ?? '' }}</td>
            @empty
            @endforelse
            <td>{{ _report_amount($data->total ?? 0) }}</td>
          </tr>
        </tbody>
        <tfoot>
          <tr>
            <td colspan="7"><b>Remarks:</b> {{$data->_note ?? '' }} </td>
          </tr>

               <tr>
                 <td colspan="7">
                    @include('backend.message.invoice_footer')
                 </td>
               </tr> 
        </tfoot>
       </table>
      </div>

     </div>

   

   @endsection

@section('script')


@endsection