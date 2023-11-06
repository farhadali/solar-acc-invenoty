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
 <a class="nav-link"  href="{{url('purchase')}}" role="button"><i class="fa fa-arrow-left"></i></a>
 @can('purchase-edit')
    <a  href="{{ route('purchase.edit',$data->id) }}" 
    class="nav-link "  title="Edit"  >
    <i class="nav-icon fas fa-edit"></i>
     </a>
  @endcan
    
    <a style="cursor: pointer;" class="nav-link"  title="Print" onclick="javascript:printDiv('printablediv')"><i class="fas fa-print"></i></a>
      <a style="cursor: pointer;" onclick="fnExcelReport();" class="nav-link"  title="Excel Download" ><i class="fa fa-file-excel" aria-hidden="true"></i></a>
     @include('backend.message.message')
  </div>

<section class="invoice" id="printablediv">
<div class="container-fluid">
     <div class="row">
      <div class="col-12">
       <h3 class="page-header">
        <img src="{{url('/')}}/{{$settings->logo}}" alt="{{$settings->name ?? '' }}" style="height: 60px;width: 60px"  > {{$settings->title ?? '' }}
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
       Supplier:
       <address>
        <strong>{{$data->_ledger->_name ?? '' }}</strong><br>
        {{$data->_address ?? '' }}<br>
        Phone: {{$data->_phone ?? '' }}<br>
        Email: {{$data->_email ?? '' }}
       </address>
      </div>

      <div class="col-sm-4 invoice-col">
        
         
       <b>Invoice No: {{ $data->_order_number ?? '' }}</b><br>
       <b>Referance:</b> {!! $data->_referance ?? '' !!}<br>
       <b>Account Balance:</b> {!! _show_amount_dr_cr(_report_amount($data->_l_balance ?? 0)) !!}<br>
       <b>Created By:</b> {!! $data->_user_name ?? '' !!}<br>
       <b>Branch:</b> {{$data->_master_branch->_name ?? ''}}
      </div>
      <h3 class="text-center">{{$page_name ?? ''}}</h3>
     </div>


     <div class="row">
      <div class="col-12 table-responsive">
       <table class="table table-striped _grid_table">
        <thead>
         <tr>
          <th class="text-left" style="width: 10%;">SL</th>
          <th class="text-left" style="width: 60%;">Product Description</th>
          <th class="text-left" style="width: 10%;">Unit</th>
          <th class="text-right" style="width: 10%;">Qty</th>
          <th class="text-right" style="width: 10%;">Rate</th>
          <th class="text-right" style="width: 10%;">Amount</th>
         </tr>
        </thead>
        <tbody>
          @php
          $_master_details = $data->_master_details ?? [];
          @endphp
           @if(sizeof( $_master_details) > 0)
           
         @php
                                    $_value_total = 0;
                                    $_vat_total = 0;
                                    $_qty_total = 0;
                                    $_total_discount_amount = 0;
                                  @endphp
                                  @forelse($data->_master_details AS $item_key=>$_item )
                                  <tr>
                                     <td class="text-left" style="vertical-align: top;" >{{($item_key+1)}}.</td>
                                     @php
                                      $_value_total +=$_item->_value ?? 0;
                                      $_vat_total += $_item->_vat_amount ?? 0;
                                      $_qty_total += $_item->_qty ?? 0;
                                      $_total_discount_amount += $_item->_discount_amount ?? 0;
                                     @endphp
                                            <td class="  " style="word-break: break-all;vertical-align: text-top;border:1px solid silver;padding:2px;" >{!! $_item->_items->_name ?? '' !!}<br>
                                              @php 
                                              
                                                  $_barcodes_string = $_item->_barcode ?? '';
                                                  $_barcodes = explode(",",$_barcodes_string);
                                              @endphp
                                              @if(sizeof($_barcodes) > 0)
                                              <b>SN:</b>
                                                  @forelse($_barcodes as $barcode)
                                                    <span>{{$barcode ?? '' }},</span>
                                                  @empty
                                                  @endforelse
                                              @endif

                                             </td>
                                            <td class="text-left" >{!! $_item->_trans_unit->_name ?? $_item->_items->_units->_name !!}</td>
                                             <td class="text-right  " style="vertical-align: text-top;border:1px solid silver;padding:2px;">{!! _report_amount($_item->_qty ?? 0) !!}</td>
                                            <td class="text-right  " style="vertical-align: text-top;border:1px solid silver;padding:2px;">{!! _report_amount($_item->_rate ?? 0) !!}</td>
                                            
                                            <td class="text-right  " style="vertical-align: text-top;border:1px solid silver;padding:2px;">{!! _report_amount($_item->_value ?? 0) !!}</td>
                                            
                                            
                                           
                                          </thead>
                                  </tr>
                                  @empty
                                  @endforelse
                            <tr>
                              <td colspan="3" class="text-right "><b>Total</b></td>
                              <td class="text-right "> <b>{{ _report_amount($_qty_total ?? 0) }}</b> </td>
                              <td></td>
                              <td class=" text-right"><b> {{ _report_amount($_value_total ?? 0) }}</b>
                              </td>
                            </tr>
                            <tr>
                              <td colspan="3" class="text-left " style="width: 50%;">
                              <table style="width: 100%">
                                <tr>
                                  <td>

                                    {{$settings->_purchse_note ?? '' }}
                                  </td>
                                </tr>
                                <tr>
                                  <td><p class="lead"> In Words:  {{ nv_number_to_text($data->_total ?? 0) }} </p></td>
                                </tr>
                              </table>
                              </td>
                              
                              <td colspan="4" class=" text-right"  style="width: 50%;">
                                  <table style="width: 100%">
                                     <tr>
                                      <th class="text-left" ><b>Sub Total</b></th>
                                      <th class="text-right">{!! _report_amount($data->_sub_total ?? 0) !!}</th>
                                    </tr>
                                   
                                    <tr>
                                      <th class="text-left" ><b>Discount</b></th>
                                      <th class="text-right">{!! _report_amount($data->_total_discount ?? 0) !!}</th>
                                    </tr>
                                   
                                    @if($form_settings->_show_vat==1)
                                    <tr>
                                      <th class="text-left" ><b>VAT</b></th>
                                      <th class="text-right">{!! _report_amount($data->_total_vat ?? 0) !!}</th>
                                    </tr>
                                    @endif
                                    <tr>
                                      <th class="text-left" ><b>Net Total</b></th>
                                      <th class="text-right">{!! _report_amount($data->_total ?? 0) !!}</th>
                                    </tr>
                                    @php
                                    $accounts = $data->purchase_account ?? [];
                                    $_due_amount =$data->_total ?? 0;
                                    @endphp
                                    @if(sizeof($accounts) > 0)
                                    @foreach($accounts as $ac_val)
                                    @if($ac_val->_ledger->id !=$data->_ledger_id)
                                     @if($ac_val->_cr_amount > 0)
                                     @php
                                      $_due_amount -=$ac_val->_cr_amount ?? 0;
                                     @endphp
                                    <tr>
                                      <th class="text-left" ><b> Less:{!! $ac_val->_ledger->_name ?? '' !!}
                                        </b></th>
                                      <th class="text-right">{!! _report_amount( $ac_val->_cr_amount ?? 0 ) !!}</th>
                                    </tr>
                                    @endif
                                    @if($ac_val->_dr_amount > 0)
                                     @php
                                      $_due_amount +=$ac_val->_dr_amount ?? 0;
                                     @endphp
                                    <tr>
                                      <th class="text-left" ><b> Add:{!! $ac_val->_ledger->_name ?? '' !!}
                                        </b></th>
                                      <th class="text-right">{!! _report_amount( $ac_val->_dr_amount ?? 0 ) !!}</th>
                                    </tr>
                                    @endif

                                    @endif
                                    @endforeach
                                    <tr>
                                      <th class="text-left" ><b>Invoice Due </b></th>
                                      <th class="text-right">{!! _report_amount( $_due_amount) !!}</th>
                                    </tr>

                                    @endif
                                    @if($form_settings->_show_p_balance==1)
                                    <tr>
                                      <th class="text-left" ><b>Previous Balance</b></th>
                                      <th class="text-right">{!! _show_amount_dr_cr(_report_amount($data->_p_balance ?? 0)) !!}</th>
                                    </tr>
                                    <tr>
                                      <th class="text-left" ><b>Current Balance</b></th>
                                      <th class="text-right">{!! _show_amount_dr_cr(_report_amount($data->_l_balance ?? 0)) !!}</th>
                                    </tr>
                                    @endif
                                  </table>

                              </td>
                            </tr>
         @endif
        </tbody>
        <tfoot>

               <tr>
                 <td colspan="6">
                    @include('backend.message.invoice_footer')
                 </td>
               </tr> 
        </tfoot>
       </table>
      </div>

     </div>

 </div>
 </section>  

   @endsection

@section('script')


@endsection