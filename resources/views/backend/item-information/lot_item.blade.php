@extends('backend.layouts.app')
@section('title',$page_name)

@section('content')
<div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 _page_name">{!! $page_name ?? '' !!} </h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <!-- <li class="breadcrumb-item"><a href="{{url('home')}}">Home</a></li> -->
              <li class="breadcrumb-item active">
              @can('item-information-create')
                <a  
               class="btn btn-sm btn-info active " 
               
               href="{{ route('item-information.create') }}">
                   <i class="nav-icon fas fa-plus"></i> Create New
                </a>
              
              @endcan
               </li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    @if ($message = Session::get('success'))
    <div class="alert alert-success">
      <p>{{ $message }}</p>
    </div>
    @endif
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-12">
            <div class="card">
              <div class="card-header border-0 mt-1">
                 

                  <div class="row">
                   @php

 $currentURL = URL::full();
 $current = URL::current();
if($currentURL === $current){
   $print_url = $current."?print=single";
   $print_url_detal = $current."?print=detail";
}else{
     $print_url = $currentURL."&print=single";
     $print_url_detal = $currentURL."&print=detail";
}
    

                   @endphp
                    <div class="col-md-4">
                      @include('backend.item-information.lot_search')
                    </div>
                    <div class="col-md-8">
                      <div class="d-flex flex-row justify-content-end">
                         @can('voucher-print')
                        <li class="nav-item dropdown remove_from_header">
                              <a class="nav-link" data-toggle="dropdown" href="#">
                                
                                <i class="fa fa-print " aria-hidden="true"></i> <i class="right fas fa-angle-down "></i>
                              </a>
                              <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                               
                                <div class="dropdown-divider"></div>
                                
                                <a target="__blank" href="{{$print_url}}" class="dropdown-item">
                                  <i class="fa fa-print mr-2" aria-hidden="true"></i> Print
                                </a>  
                            </li>
                             @endcan   
                         {!! $datas->render() !!}
                          </div>
                    </div>
                  </div>
              </div>
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table table-bordered _list_table">
                      <thead>
                        <tr>
                        <th>##</th>
                         <th>SL</th>
                         <th>ID</th>
                         <th>Ref</th>
                         <th>IN Type</th>
                         <th>Item</th>
                         <th>Unit</th>
                         <th>Code</th>
                         <th>Barcode</th>
                         <th>Warranty</th>
                         <th>QTY</th>
                         
                         <th>Discount</th>
                         <th>Vat</th>
                         <th>Purchase Rate</th>
                         <th>Sales Rate</th>
                         <th>Total Value</th>
                         <th>Manu. Date</th>
                         <th>Exp. Date</th>
                         <th>Status</th>            
                      </tr>
                      </thead>
                      <tbody>
                      @php
                        $total_qty=0;
                        $total_value=0;
                      @endphp
                        @foreach ($datas as $key => $data)
                         @php
                        $total_qty +=$data->_qty;
                        $total_value +=($data->_qty*$data->_pur_rate);
                      @endphp
                        <tr>
                           
                           <td class="_list_table_td">
                             @can('item-sales-price-update')
                            <a class="nav-link"  href="{{url('item-sales-price-edit')}}/{{$data->id}}" role="button"><i class="nav-icon fas fa-edit"></i></a>
                            @endcan


                          </td>
                            <td class="_list_table_td">{{ ($key+1) }}</td>
                            <td class="_list_table_td">{{ $data->id ?? '' }}</td>
                            
                            <td class="_list_table_td" style="display:flex;">
                              @if($data->_input_type=='purchase')
                              <a class="" href="{{ url('purchase/print') }}/{{$data->_master_id}}">
                                     {{ _purchase_pfix()}} {{$data->_master_id}}
                                    </a>
                                @endif
                                @if($data->_input_type=='replacement')
                           <a class="" 
                              href="{{url('item-replace/print')}}/{{$data->_master_id}}">
                                      RP-{{$data->_master_id}}
                                    </a>
                            @endif
                            @can('labels-print')
                                    <a title="Model Barcode Print" target="__blank" class="btn btn-default" href="{{url('labels-print')}}?_id={{$data->_master_id}}&_type=purchase&_item_id={{$data->_item_id}}"><i class=" fas fa-barcode"></i></a>
                                  @endcan

                              </td>
                            
                            
                            <td class="_list_table_td">{{ $data->_input_type ?? '' }}</td>
                            <td class="_list_table_td">{{ $data->_item ?? '' }}</td>
                            <td class="_list_table_td">{{ $data->_units->_name ?? '' }}</td>
                            <td class="_list_table_td">{{ $data->_code ?? '' }}</td>
                            <td class="_list_barcode">
                              @php
                                $barcode_arrays = explode(',', $data->_barcode ?? '');
                                @endphp
                                @forelse($barcode_arrays as $barcode)
                              <span style="width: 100%;">{{$barcode}}</span><br>
                                @empty
                                @endforelse
                            </td>
                            <td class="_list_table_td">{{ $data->_warranty_name->_name ?? '' }}</td>
                            <td class="text-right _list_table_td">{{ _report_amount($data->_qty ?? 0) }}</td>
                            <td class="text-right _list_table_td">{{ _report_amount( $data->_discount ?? 0 ) }}</td>
                            <td class="text-right _list_table_td">{{ _report_amount( $data->_vat ?? 0 ) }}</td>
                            <td class="text-right _list_table_td">{{ _report_amount($data->_pur_rate ?? 0 ) }}</td>
                            <td class="text-right _list_table_td">{{ _report_amount($data->_sales_rate ?? 0 ) }}</td>
                            <td class="text-right _list_table_td">{{ _report_amount(($data->_qty*$data->_pur_rate) ) }}</td>
                            <td class="_list_table_td">{{ _view_date_formate($data->_manufacture_date ?? '') }}</td>
                            <td class="_list_table_td">{{ _view_date_formate($data->_expire_date ?? '') }}</td>
                           <td class="_list_table_td">{{ selected_status($data->_status) }}</td>
                           
                        </tr>
                        @endforeach
                        <tr>
                          <th colspan="10" class="text-right">Total</th>
                          <th class="text-right">{{_report_amount($total_qty)}}</th>
                          <th colspan="4"></th>
                          <th class="text-right">{{_report_amount($total_value)}}</th>
                          <th colspan="3"></th>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <!-- /.d-flex -->

                

                <div class="d-flex flex-row justify-content-end">
                 {!! $datas->render() !!}
                </div>
              </div>
            </div>
            <!-- /.card -->

            
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </div>
</div>

@endsection