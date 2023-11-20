@extends('backend.layouts.app')
@section('title',$page_name)

@section('css')
<link rel="stylesheet" href="{{asset('backend/new_style.css')}}">
@endsection

@section('content')
@php
$__user= Auth::user();
@endphp
<div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class=" col-sm-6 ">
           <a class="m-0 _page_name" href="{{ route('import-material-receive.index') }}">{!! $page_name ?? '' !!} </a>
           
          </div><!-- /.col -->
          <div class=" col-sm-6 ">
            <ol class="breadcrumb float-sm-right">

               @can('item-information-create')
             <li class="breadcrumb-item ">
                 <a target="__blank" href="{{url('import-material-receive/print')}}/{{$data->id}}" class="btn btn-sm btn-warning"> <i class="nav-icon fas fa-print"></i> </a>
                  
                
               </li>
               @endcan
               @can('item-information-create')
             <li class="breadcrumb-item ">
                 <button type="button" class="btn btn-sm btn-default" data-toggle="modal" data-target="#exampleModalLong_item" title="Create New Item (Inventory) ">
                   <i class="nav-icon fas fa-ship"></i> 
                </button>
               </li>
               @endcan
              
               @can('account-ledger-create')
             <li class="breadcrumb-item ">
                 <button type="button" class="btn btn-sm btn-default" data-toggle="modal" data-target="#exampleModalLong" title="Create Ledger">
                   <i class="nav-icon fas fa-users"></i> 
                </button>
               </li>
               @endcan
                @can('purchase-form-settings')
             <li class="breadcrumb-item ">
                 <button type="button" id="form_settings" class="btn btn-sm btn-default" data-toggle="modal" data-target="#exampleModal">
                   <i class="nav-icon fas fa-cog"></i> 
                </button>
               </li>
              @endcan
              
              <li class="breadcrumb-item ">
                 <a class="btn btn-sm btn-success" title="List" href="{{ route('import-material-receive.index') }}"> <i class="nav-icon fas fa-list"></i> </a>
               </li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
   
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-12">
            <div class="card">
              <div class="card-header">
                 
                   @include('backend.message.message')
    @php
    $default_image = $settings->logo;
    $_show_barcode = $form_settings->_show_barcode ?? 0;
    $_show_short_note = $form_settings->_show_short_note ?? 0;
    $_show_cost_rate =  $form_settings->_show_cost_rate ?? 0;
    $_show_vat =  $form_settings->_show_vat ?? 0;
    $_inline_discount = $form_settings->_inline_discount ?? 0;
    $_show_self = $form_settings->_show_self ?? 0;
    $_show_unit = $form_settings->_show_unit ?? 0;
    $_show_sd = $form_settings->_show_sd ?? 0;
    $_show_cd = $form_settings->_show_cd ?? 0;
    $_show_ait = $form_settings->_show_ait ?? 0;
    $_show_rd  = $form_settings->_show_rd  ?? 0;
    $_show_at  = $form_settings->_show_at  ?? 0;
    $_show_tti  = $form_settings->_show_tti  ?? 0;
    $_show_expected_qty  = $form_settings->_show_expected_qty  ?? 0;
    $_show_sales_rate  = $form_settings->_show_sales_rate  ?? 0;
    $_show_po  = $form_settings->_show_po  ?? 0;
    $_show_rlp  = $form_settings->_show_rlp  ?? 0;
    $_show_note_sheet  = $form_settings->_show_note_sheet  ?? 0;
    $_show_wo  = $form_settings->_show_wo  ?? 0;
    $_show_lc  = $form_settings->_show_lc  ?? 0;
    $_show_vn  = $form_settings->_show_vn  ?? 0;


    @endphp
                    
              </div>
             
              <div class="card-body">
              
                {!! Form::model($data, ['method' => 'PATCH','route' => ['import-material-receive.update', $data->id],'class'=>'purchase_form']) !!}                  
                @csrf
                      <div class="row">
                  
                        <div class="col-xs-12 col-sm-12 col-md-2  ">
                            <div class="form-group">
                              <label class="mr-2" for="import_invoice_no">{{__('label.import_invoice_no')}}:</label>
                              <select class="form-control import_invoice_no " name="import_invoice_no">
                                <option value="">{{__('label.select')}}</option>
                                @forelse($import_purchases as $key=>$val)
                                <option value="{{$val->id}}" @if($val->id==$import_purchase_single->id) selected @endif >{{ $val->_order_number ?? '' }}</option>
                                @empty
                                @endforelse
                              </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-2  ">
                            <div class="form-group">
                              <label class="mr-2" for="_mother_vessel_no">{{__('label._mother_vessel_no')}}:</label>
                              <input type="text" name="_mother_vessel_no_text" class="form-control _mother_vessel_no_text" readonly value="{{$import_purchase_single->_mother_vessel->_name ?? ''}}">
                              <input type="hidden" name="_mother_vessel_no" class="form-control _mother_vessel_no" readonly value="{{$import_purchase_single->_mother_vessel->id ?? ''}}">
                            </div>
                        </div>
                        
                        <div class="col-xs-12 col-sm-12 col-md-2 ">
                            <div class="form-group">
                              <label class="mr-2" for="_main_ledger_id">Supplier:<span class="_required">*</span></label>
                            <input type="text" id="_search_main_ledger_id" name="_search_main_ledger_id" class="form-control _search_main_ledger_id" value="{{old('_search_main_ledger_id',$data->_ledger->_name ?? '')}}" placeholder="Supplier" required readonly>

                            <input type="hidden" id="_main_ledger_id" name="_main_ledger_id" class="form-control _main_ledger_id" value="{{old('_main_ledger_id',$data->_ledger_id)}}" placeholder="Supplier" required>
                            <div class="search_box_main_ledger"> </div>

                                
                            </div>
                        </div>
                          <div class="col-xs-12 col-sm-12 col-md-2 ">
                            <div class="form-group">
                              <label class="mr-2" for="_phone">{{__('label._phone')}}:</label>
                              <input type="text" id="_phone" name="_phone" class="form-control _phone" value="{{old('_phone',$data->_phone)}}" placeholder="{{__('label._phone')}}" >
                            </div>
                        </div>
                        
                        <div class="col-xs-12 col-sm-12 col-md-2 ">
                            <div class="form-group">
                              <label class="mr-2" for="_address">{{__('label._address')}}:</label>
                              <input type="text" id="_address" name="_address" class="form-control _address" value="{{old('_address',$data->_address)}}" placeholder="{{__('label._address')}}" >
                                
                            </div>
                        </div>
                         <div class="col-xs-12 col-sm-12 col-md-2 ">
                            <div class="form-group">
                              <label class="mr-2" for="_referance">{{__('label._referance')}}:</label>
                              <input type="text" id="_referance" name="_referance" class="form-control _referance" value="{{old('_referance',$data->_referance)}}" placeholder="{{__('label._referance')}}" >
                                
                            </div>
                        </div>
                </div>

                   <div class="row">

                       <div class="col-xs-12 col-sm-12 col-md-2">
                        <input type="hidden" name="_form_name" class="_form_name" value="import_purchase">
                            <div class="form-group">
                                <label>{{__('label.mrr_date')}}:</label>
                                  <div class="input-group date" id="reservationdate" data-target-input="nearest">
                                      <input type="text" name="_date" class="form-control datetimepicker-input" data-target="#reservationdate" value="{{_view_date_formate($data->_date)}}" />
                                      <div class="input-group-append" data-target="#reservationdate" data-toggle="datetimepicker">
                                          <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                      </div>
                                  </div>
                              </div>
                              
                              <input type="hidden" name="_item_row_count" value="1" class="_item_row_count">
                              <input type="hidden" name="_purchase_id" value="{{$data->id}}" >
                        </div>

                        
                        <div class="col-xs-12 col-sm-12 col-md-2 ">
                            <div class="form-group">
                              <label class="mr-2" for="_order_number">{{__('label.mrr_number')}}:</label>
                              <input type="text" id="_order_number" name="_order_number" class="form-control _order_number" value="{{old('_order_number',$data->_order_number)}}" placeholder="Invoice No" readonly>
                              <input type="hidden" name="_search_form_value" class="_search_form_value" value="2">
                                
                            </div>
                        </div>
                        
                        <div class="col-xs-12 col-sm-12 col-md-2 display_none">
                            <div class="form-group">
                              <label class="mr-2" for="_purchase_type">{{__('label._purchase_type')}}:</label>
                              <select class="form-control" name="_purchase_type" >
                                <option value="2">Import</option>
                              </select>
                                
                            </div>
                        </div>
                        
                        @include('basic.org_edit')
                        <div class="col-xs-12 col-sm-12 col-md-2 ">
                            <div class="form-group">
                              <label class="mr-2" for="_store_id">{{__('label._store_id')}}:</label>
                             <select class="form-control" name="_store_id">
                                <option value="">{{__('label.select')}}</option>
                                @forelse($store_houses as $key=>$val)
                                  <option value="{{$val->id}}" @if($val->id==$data->_store_id) selected @endif >{{ $val->_name ?? '' }}</option>
                                @empty
                                @endforelse
                              </select>
                            </div>
                        </div>
                         

                        
                        
                         <div class="col-md-12 mt-2">
                          <div class="card ">

                            <div class="row route_info_box" >
                              <table class="table" >
                                <thead>
                                  <tr>
                                    <th  style="width:5%;"></th>
                                    <th  style="width:15%;">{{__('label._loding_point')}}</th>
                                    <th  style="width:15%;">{{__('label._unloading_point')}}</th>
                                    <th  style="width:15%;">{{__('label._loading_date_time')}}</th>
                                    <th  style="width:15%;">{{__('label._arrival_date_time')}}</th>
                                    <th  style="width:15%;">{{__('label._discharge_date_time')}}</th>
                                    <th  style="width:15%;">{{__('label._note')}}</th>
                                    <th  style="width:15%;">{{__('label.final_route')}}</th>
                                  </tr>
                                </thead>
                                @php
                                $_route_infos = $data->_route_info ?? [];
                                @endphp
                                <tbody class="route_display_box">
                                  @forelse($_route_infos as $r_key=>$r_val)
                                <tr>
                                  <td>
                                    
                                    <a href="#none" class="btn btn-default btn-sm remove_route" ><i class="fa fa-trash"></i></a>
                                    <input type="hidden" name="_route_info_id[]" value="{{$r_val->id}}">
                                  </td>
                                  
                                  <td>
                                        
                                        <select class="form-control" name="_loading_point[]">
                                          <option value="">{{__('label.select')}}</option>
                                          @forelse($all_store_houses as $key=>$val)
                                            <option value="{{$val->id}}" @if($val->id==$r_val->_loading_point) selected @endif >{{ $val->_name ?? '' }}</option>
                                          @empty
                                          @endforelse
                                        </select>
                                      
                                  </td>
                                  <td>
                                        <select class="form-control" name="_unloading_point[]">
                                          <option value="">{{__('label.select')}}</option>
                                          @forelse($all_store_houses as $key=>$val)
                                            <option value="{{$val->id}}" @if($val->id==$r_val->_unloading_point) selected @endif>{{ $val->_name ?? '' }}</option>
                                          @empty
                                          @endforelse
                                        </select>
                                  </td>
                                  <td>
                                        <input type="datetime-local" name="_loading_date_time[]" class="form-control _loading_date_time" value="{{old('_loading_date_time',$r_val->_loading_date_time)}}" placeholder="{{__('label._loading_date_time')}}" >
                                     
                                  </td>
                                  <td>
                                        <input type="datetime-local"  name="_arrival_date_time[]" class="form-control _arrival_date_time" value="{{old('_arrival_date_time',$r_val->_arrival_date_time)}}" placeholder="{{__('label._arrival_date_time')}}" >
                                     
                                  </td>
                                  <td>
                                  <input type="datetime-local"  name="_discharge_date_time[]" class="form-control _discharge_date_time" value="{{old('_discharge_date_time',$r_val->_discharge_date_time)}}" placeholder="{{__('label._discharge_date_time')}}" >
                                  </td>
                                  <td>
                                  <input type="text"  name="_route_note[]" class="form-control _route_note" value="{{old('_route_note',$r_val->_route_note)}}" placeholder="{{__('label._note')}}" >
                                  </td>
                                  <td>
                                  <input type="checkbox"  name="_final_route_chekbox[]" class="form-control _final_route_chekbox" value="{{old('_final_route_chekbox')}}" @if($r_val->_final_route==1) checked @endif >
                                  <input type="hidden" class="_final_route" value="{{$r_val->_final_route}}"  name="_final_route[]"/>
                                  </td>

                                </tr>
                                @empty
                                
                                @endforelse
                                
                                </tbody>
                                <tfoot>
                                  <tr>
                                  <td>
                                    
                                    <a href="#none" class="btn btn-default btn-sm" onclick="add_new_route_row(event)"><i class="fa fa-plus"></i></a>
                                  </td>
                                  <td colspan="6"></td>
                                </tr>
                                </tfoot>
                              </table>
                            </div>
                           
                          </div>
                        </div>

                         
                        @php
                        $_vessel_detail = $data->_vessel_detail ?? '';
                        $_vessel_no= $_vessel_detail->_vessel_no ?? 0;
                        @endphp
                       
                <div class="col-md-12">
                  <div class="card vessel_info_box" >
                    <div class="row">
                      @php
                        $vessels = \DB::table('vessel_infos')->orderBy('_name','ASC')->get();
                        @endphp
                        <div class="col-xs-12 col-sm-12 col-md-4  @if($_show_vn==0) display_none @endif">
                            <div class="form-group">
                              <label class="mr-2" for="_vessel_no">{{__('label._vessel_no')}}:</label>
                              <select class="form-control select2" name="_vessel_no">
                                <option value="">{{__('label.select')}}</option>
                                @forelse($vessels as $key=>$val)
                                <option value="{{$val->id}}" @if($_vessel_no==$val->id) selected @endif >{{ $val->_name ?? '' }} || Capacity:{!! $val->_capacity ?? '' !!}</option>
                                @empty
                                @endforelse
                              </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-2  @if($_show_vn==0) display_none @endif">
                            <div class="form-group">
                              <label class="mr-2" for="_capacity">{{__('label._capacity')}}:</label>
                              <input type="text" name="_capacity" class="form-control" placeholder="{{__('label._capacity')}}" value="{{old('_capacity',$_vessel_detail->_capacity ?? 0)}}">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-2  @if($_show_vn==0) display_none @endif">
                            <div class="form-group">
                              <label class="mr-2" for="_name_of_master">{{__('label._name_of_master')}}:</label>
                              <input type="text" name="_vessel_res_person" class="form-control" placeholder="{{__('label._name_of_master')}}" value="{{old('_name_of_master',$_vessel_detail->_vessel_res_person ?? '')}}">
                            </div>
                        </div>
                        
                        <div class="col-xs-12 col-sm-12 col-md-2  @if($_show_vn==0) display_none @endif">
                            <div class="form-group">
                              <label class="mr-2" for="_mobile_of_master">{{__('label._mobile_of_master')}}:</label>
                              <input type="text" name="_vessel_res_mobile" class="form-control" placeholder="{{__('label._mobile_of_master')}}" value="{{old('_mobile_of_master',$_vessel_detail->_vessel_res_mobile ?? '')}}">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-2  @if($_show_vn==0) display_none @endif">
                            <div class="form-group">
                              <label class="mr-2" for="_extra_instruction">{{__('label._extra_instruction')}}:</label>
                              <input type="text" name="_extra_instruction" class="form-control" placeholder="{{__('label._extra_instruction')}}" value="{{old('_extra_instruction',$_vessel_detail->_extra_instruction ?? '')}}">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-2  @if($_show_vn==0) display_none @endif">
                            <div class="form-group">
                              <label class="mr-2" for="scott_name">{{__('label.scott_name')}}:</label>
                              <input type="text" name="scott_name" class="form-control" placeholder="{{__('label.scott_name')}}"  value="{{old('scott_name',$data->scott_name)}}">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-2  @if($_show_vn==0) display_none @endif">
                            <div class="form-group">
                              <label class="mr-2" for="scott_number">{{__('label.scott_number')}}:</label>
                              <input type="text" name="scott_number" class="form-control" placeholder="{{__('label.scott_number')}}"  value="{{old('scott_number',$data->scott_number)}}">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-2  @if($_show_vn==0) display_none @endif">
                            <div class="form-group">
                              <label class="mr-2" for="servey_name">{{__('label.servey_name')}}:</label>
                              <input type="text" name="servey_name" class="form-control" placeholder="{{__('label.servey_name')}}"  value="{{old('servey_name',$data->servey_name)}}">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-2  @if($_show_vn==0) display_none @endif">
                            <div class="form-group">
                              <label class="mr-2" for="servey_number">{{__('label.servey_number')}}:</label>
                              <input type="text" name="servey_number" class="form-control" placeholder="{{__('label.servey_number')}}"  value="{{old('servey_number',$data->servey_number)}}">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-3  @if($_show_vn==0) display_none @endif">
                            <div class="form-group">
                              <label class="mr-2" for="boat_no">{{__('label.boat_no')}}:</label>
                              <input type="text" name="boat_no" class="form-control" placeholder="{{__('label.boat_no')}}"  value="{{old('boat_no',$data->boat_no)}}">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-3  @if($_show_vn==0) display_none @endif">
                            <div class="form-group">
                              <label class="mr-2" for="boat_file">{{__('label.boat_file')}}:</label>
                              <input type="file" name="boat_file" class="form-control myImage" placeholder="{{__('label.boat_file')}}" onchange="loadFile(event,1 )" > 
                            </div>
                             
                            <img id="output_1" class="myImage banner_image_create" src="{{asset($data->boat_file ?? $default_image)}}"  title="attachment" data-toggle="modal" data-target="#imageModal" style="max-height:50px;max-width: 150px; " >
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-3  @if($_show_vn==0) display_none @endif">
                            <div class="form-group">
                              <label class="mr-2" for="servey_file">{{__('label.servey_file')}}:</label>
                              <input type="file" name="servey_file" class="form-control myImage" placeholder="{{__('label.servey_file')}}"  onchange="loadFile(event,2 )">
                              
                            </div>
                            <img id="output_2" class="myImage banner_image_create" src="{{asset($data->servey_file ?? $default_image )}}"  title="attachment" data-toggle="modal" data-target="#imageModal" style="max-height:50px;max-width: 150px; " >
                        </div>
                    </div>
                  </div>
                </div>


                        <div class="col-md-12  ">
                             <div class="card">
                              <div class="card-header">
                                <strong>Details</strong>

                              </div>
                             
                              <div class="card-body">
                                <div class="table-responsive">
                                      <table class="table table-bordered" >
                                         <thead >
                                            <th class="text-left" >&nbsp;</th>
                                            <th class="text-left" >{{__('label._item_id')}}</th>
                                            <th class="text-left display_none" >{{__('label._base_unit')}}</th>
                                            <th class="text-left display_none" >{{__('label.conversion_qty')}}</th>
                                            <th class="text-left @if(isset($_show_unit)) @if($_show_unit==0) display_none    @endif @endif" >{{__('label._transection_unit')}}</th>
                                           
                                            <th class="text-left @if($_show_barcode==0) display_none    @endif " >{{__('label._barcode')}}</th>
                                            <th class="text-left @if($_show_short_note==0) display_none    @endif " >{{__('label._note')}}</th>
                                         
                                            <th class="text-left @if($_show_expected_qty==0) display_none @endif" >{{__('label._expected_qty')}}</th>
                                            <th class="text-left" >{{__('label._qty')}}</th>
                                            <th class="text-left" >{{__('label._rate')}}</th>
                                            <th class="text-left @if($_show_sales_rate==0) display_none @endif" >{{__('label._sales_rate')}}</th>

                                           
                                            
                                            <th class="text-left" >{{__('label._value')}}</th>
                                           
                                             <th class="text-left @if(isset($_show_self)) @if($_show_self==0) display_none @endif
                                            @endif" >{{__('label._shelf')}}</th>
                                             <th class="text-left @if(isset($form_settings->_show_manufacture_date)) @if($form_settings->_show_manufacture_date==0) display_none @endif
                                            @endif" >{{__('label._manufacture_date')}}</th>
                                             <th class="text-left @if(isset($form_settings->_show_expire_date)) @if($form_settings->_show_expire_date==0) display_none @endif
                                            @endif"> {{__('label._expire_date')}} </th>
                                            
                                           
                                          </thead>
                                          @php
                                          $_total_qty_amount = 0;
                                          $_total_vat_amount =0;
                                          $_total_value_amount =0;
                                          $_total_discount_amount=0;
                                          $_total_expected_amount=0;
                                          $__master_details = $data->_master_details;
                                          @endphp
                                          <tbody class="area__purchase_details" id="area__purchase_details">
                                            @if(sizeof($__master_details) > 0)
                                            @forelse($data->_master_details as $m_key=> $detail)
                                             @php
                                              $_total_qty_amount += $detail->_qty ??  0;
                                              $_total_vat_amount += $detail->_vat_amount ??  0;
                                              $_total_value_amount += $detail->_value ??  0;
                                              $_total_discount_amount=$detail->_discount_amount ??  0;
                                              $_total_expected_amount +=$detail->_expected_qty ??  0;
                                              @endphp
                                            <tr class="_purchase_row">
                                              <td>
                                                <a  href="#none" class="btn btn-default _purchase_row_remove" ><i class="fa fa-trash"></i></a>
                                              </td>
                                              <td>
                                                
                                                <input type="hidden" name="purchase_detail_id[]" value="{{ $detail->id }}" class="form-control purchase_detail_id" >
                                                
                                              
                                                <input type="text" name="_search_item_id[]" class="form-control _search_item_id width_280_px" placeholder="Item" value="{{$detail->_items->_name ?? '' }}">
                                                <input type="hidden" name="_item_id[]" class="form-control _item_id width_200_px" value="{{$detail->_item_id}}">
                                                <div class="search_box_item">
                                                  
                                                </div>
                                              </td>
                                              <td class="display_none">
                                                <input type="hidden" class="form-control _base_unit_id width_100_px" name="_base_unit_id[]" value="{!! $detail->_base_unit !!}" />
                                                <input type="text" class="form-control _main_unit_val width_100_px" readonly name="_main_unit_val[]" value="{!! $detail->_items->_units->_name ?? '' !!}" />
                                              </td>
                                              <td class="display_none">
                                                <input type="text" name="conversion_qty[]"  class="form-control conversion_qty " value="{!! $detail->_unit_conversion ?? 1 !!}" readonly>
                                                <input type="hidden" name="_base_rate[]" class="form-control _base_rate _common_keyup" value="{!! $detail->_items->_pur_rate ?? 0 !!}" >
                                              </td>
                                              <td class="@if($_show_unit==0) display_none @endif">
                                                <select class="form-control _transection_unit" name="_transection_unit[]">
                                                  @forelse($detail->_items->unit_conversion as $conversion_units )
                                                    <option 
                                                    value="{{$conversion_units->_conversion_unit}}" 
                                                    attr_base_unit_id="{{$conversion_units->_base_unit_id}}" 
        attr_conversion_qty="{{$conversion_units->_conversion_qty}}" 
        attr_conversion_unit="{{$conversion_units->_conversion_unit}}" 
        attr_item_id="{{$conversion_units->_item_id}}"

                                                    @if($conversion_units->_conversion_unit==$detail->_transection_unit) selected @endif >{!! $conversion_units->_conversion_unit_name ?? '' !!}</option>
                                                  @empty
                                                  @endforelse
                                                </select>
                                              </td>

                                              @if(isset($_show_barcode)) @if($_show_barcode==1)
                                              <td>
                                                <input type="text" name="{{($m_key+1)}}__barcode__{{$detail->_item_id}}" class="form-control _barcode {{($m_key+1)}}__barcode"  value="{{$detail->_barcode ?? '' }} " id="{{($m_key+1)}}__barcode" >
                                               

                                                <input type="hidden" name="_ref_counter[]" value="{{($m_key+1)}}" class="_ref_counter" id="{{($m_key+1)}}__ref_counter">
                                              </td>
                                              @else
                                              <td class="display_none">
                                                <input type="text" name="{{($m_key+1)}}__barcode__{{$detail->_item_id}}" class="form-control _barcode {{($m_key+1)}}__barcode"  value="{{$detail->_barcode ?? '' }} " id="{{($m_key+1)}}__barcode" >
                                               

                                                <input type="hidden" name="_ref_counter[]" value="{{($m_key+1)}}" class="_ref_counter" id="{{($m_key+1)}}__ref_counter">


                                              </td>
                                              @endif
                                            @endif

                                             <td class="@if(isset($_show_short_note)) @if($_show_short_note==0) display_none   @endif @endif">
                                                <input type="text" name="_short_note[]" class="form-control _short_note {{($m_key+1)}}__short_note "  value="{{$detail->_short_note ?? '' }}">
                                              </td>
                                              <td class="@if($_show_expected_qty==0) display_none @endif">
                                                <input type="number" name="_expected_qty[]" class="form-control _expected_qty _common_keyup" value="{{ $detail->_expected_qty ?? 0 }}">
                                              </td>

                                              <td>
                                                 @if($detail->_items->_unique_barcode==1)
 <script type="text/javascript">
  $('#<?php echo ($m_key+1);?>__barcode').amsifySuggestags({
      trimValue: true,
      dashspaces: true,
      showPlusAfter: 1,
      });
                                            </script>
                                            @endif
                                                <input type="number" name="_qty[]" class="form-control _qty _common_keyup"  value="{{$detail->_qty ?? 0 }}" @if($detail->_items->_unique_barcode==1) readonly @endif >
                                              </td>
                                              <td>
                                                <input type="number" name="_rate[]" class="form-control _rate _common_keyup" value="{{$detail->_rate ?? 0 }}" >
                                              </td>
                                              <td class="@if($_show_sales_rate==0) display_none @endif">
                                                <input type="number" name="_sales_rate[]" class="form-control _sales_rate " value="{{$detail->_sales_rate ?? 0 }}" >
                                              </td>

                                               
                                              <td>
                                                <input type="number" name="_value[]" class="form-control _value "  value="{{ $detail->_value ?? 0 }}" >
                                              </td>
                                            
                                              <td class=" @if(isset($_show_self)) @if($_show_self==0) display_none @endif  @endif">
                                                <input type="text" name="_store_salves_id[]" class="form-control _store_salves_id " value="{{$detail->_store_salves_id ?? '' }}" >
                                              </td>
                                             
                                              

                                              <td class="@if(isset($form_settings->_show_manufacture_date)) @if($form_settings->_show_manufacture_date==0) display_none  @endif @endif">
                                                <input type="date" name="_manufacture_date[]" class="form-control _manufacture_date "  value="{{$detail->_manufacture_date ?? '' }}">
                                              </td>
                                              <td class="@if(isset($form_settings->_show_expire_date)) @if($form_settings->_show_expire_date==0) display_none  @endif @endif">
                                                <input type="date" name="_expire_date[]" class="form-control _expire_date " value="{{$detail->_expire_date ?? '' }}" >
                                              </td>
                                              
                                            </tr>
                                           
                                            @empty
                                            @endforelse
                                            @endif
                                          </tbody>
                                          <tfoot>
                                            <tr>
                                              <td>
                                                <a href="#none"  class="btn btn-default btn-sm" onclick="purchase_row_add(event)"><i class="fa fa-plus"></i></a>
                                              </td>
                                              <td  class="text-right"><b>Total</b></td>
                                              <td class="display_none"></td>
                                              <td class="display_none"></td>
                                              <td class="@if($_show_unit==0) display_none @endif"></td>
                                              
                                                <td  class="text-right @if($_show_barcode==0) display_none @endif"></td>
                                             
                                              
                                                <td  class="text-right @if($_show_short_note==0) display_none @endif"></td>
                                             
                                              <td class="@if($_show_expected_qty==0) display_none @endif">
                                                <input type="number" step="any" min="0" name="_total_expected_qty_amount" class="form-control _total_expected_qty_amount" value="{{$_total_expected_amount}}" readonly required>
                                              </td>
                                              <td>
                                                <input type="number" step="any" min="0" name="_total_qty_amount" class="form-control _total_qty_amount" value="{{$_total_qty_amount}}" readonly required>
                                              </td>
                                              <td></td>
                                              <td class="@if($_show_sales_rate==0) display_none @endif"></td>
                                              <td>
                                                <input type="number" step="any" min="0" name="_total_value_amount" class="form-control _total_value_amount" value="{{$_total_value_amount}}" readonly required>
                                              </td>
                                              
                                              
                                              <td class="@if(isset($form_settings->_show_manufacture_date)) @if($form_settings->_show_manufacture_date==0) display_none  @endif  @endif"></td>

                                              <td class="@if(isset($form_settings->_show_expire_date)) @if($form_settings->_show_expire_date==0) display_none  @endif  @endif"></td>
                                              
                                            </tr>
                                          </tfoot>
                                      </table>
                                </div>
                            </div>
                          </div>
                        </div>
                        
                          @if($__user->_ac_type==1)
                      @include('backend.purchase.edit_ac_cb')
                         
                      @else
                       @include('backend.purchase.edit_ac_detail')
                      @endif
                          

                        <div class="col-xs-12 col-sm-12 col-md-12 mb-10">
                          <table class="table" style="border-collapse: collapse;">
                            <tr>
                              <td style="width: 10%;border:0px;"><label for="_note">Note</label></td>
                              <td style="width: 70%;border:0px;">
                                @if ($_print = Session::get('_print_value'))
                                     <input type="hidden" name="_after_print" value="{{$_print}}" class="_after_print" >
                                    @else
                                    <input type="hidden" name="_after_print" value="0" class="_after_print" >
                                    @endif
                                    @if ($_master_id = Session::get('_master_id'))
                                     <input type="hidden" name="_master_id" value="{{url('purchase/print')}}/{{$_master_id}}" class="_master_id">
                                    
                                    @endif
                                   
                                       <input type="hidden" name="_print" value="0" class="_save_and_print_value">

                                    <input type="text" id="_note"  name="_note" class="form-control _note" value="{{old('_note',$data->_note ?? '' )}}" placeholder="Note" required >
                              </td>
                            </tr>
                            <tr>
                              <td style="width: 10%;border:0px;"><label for="_sub_total">Sub Total</label></td>
                              <td style="width: 70%;border:0px;">
                                <input type="text" name="_sub_total" class="form-control width_200_px" id="_purchase_sub_total" readonly value="{{ _php_round($data->_sub_total ?? 0) }}">
                              </td>
                            </tr>
                            <tr class="display_none">
                              <td style="width: 10%;border:0px;"><label for="_discount_input">Invoice Discount</label></td>
                              <td style="width: 70%;border:0px;">
                                <input type="text" name="_discount_input" class="form-control width_200_px" id="_purchase_discount_input" value="{{$data->_discount_input ?? 0}}" >
                              </td>
                            </tr>
                            <tr class="display_none">
                              <td style="width: 10%;border:0px;"><label for="_total_discount">Total Discount</label></td>
                              <td style="width: 70%;border:0px;">
                                <input type="text" name="_total_discount" class="form-control width_200_px" id="_purchase_total_discount" readonly value="{{$data->_total_discount ?? 0}}">
                              </td>
                            </tr>
                            
                            <tr class=" display_none @if($_show_vat==0) display_none @endif">
                              <td style="width: 10%;border:0px;"><label for="_total_vat">Total VAT</label></td>
                              <td style="width: 70%;border:0px;">
                                <input type="text" name="_total_vat" class="form-control width_200_px" id="_purchase_total_vat" readonly value="{{$data->_total_vat ?? 0}}">
                              </td>
                            </tr>
                            
                            <tr class=" ">
                              <td style="width: 10%;border:0px;"><label for="_total">NET Total </label></td>
                              <td style="width: 70%;border:0px;">
                          <input type="text" name="_total" class="form-control width_200_px" id="_total" readonly value="{{ _php_round($data->_total ?? 0)}}">
                          <input type="hidden" name="_item_row_count" value="{{sizeof($__master_details)}}" class="_item_row_count">
                              </td>
                            </tr>
                             @include('backend.message.send_sms')
                          </table>
                        </div>
                         <div class="col-xs-12 col-sm-12 col-md-12 " style="height: 50px;">
                         </div>

                        <div class="col-xs-12 col-sm-12 col-md-12 bottom_save_section text-middle">
                          @if($sales_number > 0 )
                          <p class="text-center _required">This invoice Item already sold. Please don't Change any item information.
                            </p>
                            <p>
                            <button type="submit" class="btn btn-success submit-button ml-5"><i class="fa fa-credit-card mr-2" aria-hidden="true"></i> Save</button></p>
                          @else
                            <button type="submit" class="btn btn-success submit-button ml-5"><i class="fa fa-credit-card mr-2" aria-hidden="true"></i> Save</button>
                            <button type="submit" class="btn btn-warning submit-button _save_and_print"><i class="fa fa-print mr-2" aria-hidden="true"></i> Save & Print</button>
                          @endif
                            
                        </div>
                        <br><br>
                        
                    </div>
                    {!! Form::close() !!}
                
              </div>
            </div>
            <!-- /.card -->

            
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </div>
</div>

<div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <img class="img-fluid" id="modalImage" src="">
                </div>
            </div>
        </div>
    </div>
<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form action="{{ url('import-purchase-settings')}}" method="POST">
        @csrf
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Purchase Form Settings</h5>
        <button type="button" class="close exampleModalClose"  aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body display_form_setting_info">
       
         
      
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary exampleModalClose" >Close</button>
        <button type="submit" class="btn btn-primary">Save changes</button>
      </div>
       </form>
    </div>
  </div>



</div>
@include('backend.common-modal.item_ledger_modal')

@endsection

@section('script')

<script type="text/javascript">
  @if(empty($form_settings))
    $(document).find("#form_settings").click();
  @endif
  var default_date_formate = `{{default_date_formate()}}`;
  var _after_print = $(document).find("._after_print").val();
  var _master_id = $(document).find("._master_id").val();
  var _item_row_count = parseFloat($(document).find('._item_row_count').val());
  

  if(_after_print ==1){
      var open_new = window.open(_master_id, '_blank');
      if (open_new) {
          //Browser has allowed it to be opened
          open_new.focus();
      } else {
          //Browser has blocked it
          alert('Please allow popups for this website');
      }
  }

  $('.myImage').on('click', function() {
        var imgSrc = $(this).attr('src');
        $('#modalImage').attr('src', imgSrc);
    });



$(document).on("click","#form_settings",function(){
         setting_data_fetch();
  })

  function setting_data_fetch(){
      var request = $.ajax({
            url: "{{url('import-purchase-setting-modal')}}",
            method: "GET",
            dataType: "html"
          });
         request.done(function( result ) {
              $(document).find(".display_form_setting_info").html(result);
         })
  }



  $(document).on('click','.remove_route',function(){
$(this).closest('tr').remove();
});

function add_new_route_row(event){
  var single_route=`<tr>
                                  <td>
                                    
                                    <a href="#none" class="btn btn-default btn-sm remove_route" ><i class="fa fa-trash"></i></a>
                                    <input type="hidden" name="_route_info_id[]" value="0">
                                  </td>
                                  <td>
                                        
                                        <select class="form-control _loding_point" name="_loding_point[]">
                                          <option value="">{{__('label.select')}}</option>
                                          @forelse($all_store_houses as $key=>$val)
                                            <option value="{{$val->id}}">{{ $val->_name ?? '' }}</option>
                                          @empty
                                          @endforelse
                                        </select>
                                      
                                  </td>
                                  <td>
                                        <select class="form-control _unloading_point" name="_unloading_point[]">
                                          <option value="">{{__('label.select')}}</option>
                                          @forelse($all_store_houses as $key=>$val)
                                            <option value="{{$val->id}}">{{ $val->_name ?? '' }}</option>
                                          @empty
                                          @endforelse
                                        </select>
                                  </td>
                                  <td>
                                        <input type="datetime-local" name="_loading_date_time[]" class="form-control _loading_date_time" value="{{old('_loading_date_time')}}" placeholder="{{__('label._loading_date_time')}}" >
                                     
                                  </td>
                                  <td>
                                        <input type="datetime-local" name="_arrival_date_time[]" class="form-control _arrival_date_time" value="{{old('_arrival_date_time')}}" placeholder="{{__('label._arrival_date_time')}}" >
                                     
                                  </td>
                                  <td>
                                  <input type="datetime-local"  name="_discharge_date_time[]" class="form-control _discharge_date_time" value="{{old('_discharge_date_time')}}" placeholder="{{__('label._discharge_date_time')}}" >
                                  </td>
                                  <td>
                                  <input type="text"  name="_route_note[]" class="form-control _route_note" value="{{old('_route_note')}}" placeholder="{{__('label._note')}}" >
                                  </td>
                                  <td>
                                  <input type="checkbox"  name="_final_route_chekbox[]" class="form-control _final_route_chekbox" value="{{old('_final_route_chekbox')}}"  >
                                  <input type="hidden" class="_final_route" value="0"  name="_final_route[]"/>
                                  </td>

                                </tr>`;


  $(document).find(".route_display_box").append(single_route);
}

$(document).on('click','._final_route_chekbox',function(){
  $('._final_route_chekbox').prop('checked',false);
  $('._final_route').val(0);

  $(this).closest('tr').find('._final_route_chekbox').prop('checked',true);
  $(this).closest('tr').find('._final_route').val(1);
})
  


  $(document).on('keyup','._search_item_id',delay(function(e){
    $(document).find('._search_item_id').removeClass('required_border');
    var _gloabal_this = $(this);
    var _text_val = $(this).val().trim();


  var request = $.ajax({
      url: "{{url('item-purchase-search')}}",
      method: "GET",
      data: { _text_val : _text_val },
      dataType: "JSON"
    });
     
    request.done(function( result ) {

      var search_html =``;
      var data = result.data; 
      if(data.length > 0 ){
            search_html +=`<div class="card"><table style="width: 500px;">
                            <tbody>`;
                        for (var i = 0; i < data.length; i++) {
                          var _balance = data[i]?._balance

                         search_html += `<tr class="search_row_item" >
                                        <td>${data[i].id}
                                        <input type="hidden" name="_id_item" class="_id_item" value="${data[i].id}">
                                        </td><td>${data[i]._name}
                                        <input type="hidden" name="_name_item" class="_name_item" value="${data[i]._name}">
                                        <input type="hidden" name="_unique_barcode" class="_unique_barcode" value="${data[i]._unique_barcode}">
                                  <input type="hidden" name="_item_barcode" class="_item_barcode" value="${data[i]._barcode}">
                                  <input type="hidden" name="_item_rate" class="_item_rate" value="${data[i]._pur_rate}">
                                  <input type="hidden" name="_item_sales_rate" class="_item_sales_rate" value="${data[i]._sale_rate}">
                                  <input type="hidden" name="_item_vat" class="_item_vat" value="${data[i]._vat}">
                                   <input type="hidden" name="_main_unit_id" class="_main_unit_id" value="${data[i]._unit_id}">
                                  <input type="hidden" name="_main_unit_text" class="_main_unit_text" value="${data[i]._units?._name}">
                                   </td>
                                   <td>${_balance} ${data[i]._units?._name}</td>
                                   </tr>`;
                        }                         
            search_html += ` </tbody> </table></div>`;
      }else{
        search_html +=`<div class="card"><table style="width: 300px;"> 
        <thead><th colspan="3"><button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target="#exampleModalLong_item" title="Create New Item (Inventory) ">
                   <i class="nav-icon fas fa-plus"></i> New Item
                </button></th></thead><tbody></tbody></table></div>`;
      }     
      _gloabal_this.parent('td').find('.search_box_item').html(search_html);
      _gloabal_this.parent('td').find('.search_box_item').addClass('search_box_show').show();
      
    });
     
    request.fail(function( jqXHR, textStatus ) {
      alert( "Request failed: " + textStatus );
    });

  

}, 500));

$(document).on('click','.search_row_item',function(){
  var _vat_amount =0;
  var _id = $(this).children('td').find('._id_item').val();
  var _name = $(this).find('._name_item').val();
  var _item_barcode = $(this).find('._item_barcode').val();
  if(_item_barcode=='null'){ _item_barcode='' } 
  var _item_rate = $(this).find('._item_rate').val();
  var _item_sales_rate = $(this).find('._item_sales_rate').val();
  var _item_vat = parseFloat($(this).find('._item_vat').val());

  var _main_unit_id = $(this).children('td').find('._main_unit_id').val();
  var _main_unit_val = $(this).children('td').find('._main_unit_text').val();

  var _unique_barcode = parseFloat($(this).find('._unique_barcode').val());
 var _item_row_count = $(document).find('._item_row_count').val();
 console.log(" _unique_barcode "+_unique_barcode)
  if(_unique_barcode ==1){
    _new_barcode_function(_item_row_count);
  }
  
  if(isNaN(_item_vat)){ _item_vat=0 }
  _vat_amount = ((_item_rate*_item_vat)/100)

var self = $(this);

    var request = $.ajax({
      url: "{{url('item-wise-units')}}",
      method: "GET",
      data: { item_id:_id },
       dataType: "html"
    });
     
    request.done(function( response ) {
      self.parent().parent().parent().parent().parent().parent().find('._transection_unit').html("")
      self.parent().parent().parent().parent().parent().parent().find("._transection_unit").html(response);
    });
     
    request.fail(function( jqXHR, textStatus ) {
      alert( "Request failed: " + textStatus );
    });
  

  $(this).parent().parent().parent().parent().parent().parent().find('._item_id').val(_id);
  var _id_name = `${_name} `;
  $(this).parent().parent().parent().parent().parent().parent().find('._search_item_id').val(_id_name);
  $(this).parent().parent().parent().parent().parent().parent().find('._barcode').val(_item_barcode);
  $(this).parent().parent().parent().parent().parent().parent().find('._rate').val(_item_rate);
  $(this).parent().parent().parent().parent().parent().parent().find('._sales_rate').val(_item_sales_rate);
  $(this).parent().parent().parent().parent().parent().parent().find('._vat').val(_item_vat);
  $(this).parent().parent().parent().parent().parent().parent().find('._vat_amount').val(_vat_amount);
  $(this).parent().parent().parent().parent().parent().parent().find('._qty').val(1);
  if(_unique_barcode ==1){
    $(this).parent().parent().parent().parent().parent().parent().find('._qty').val(0);
    $(this).parent().parent().parent().parent().parent().parent().find('._qty').attr('readonly',true);
  }
  $(this).parent().parent().parent().parent().parent().parent().find('._value').val(_item_rate);
  var _ref_counter = $(this).parent().parent().parent().parent().parent().parent().find('._ref_counter').val();
  $(this).parent().parent().parent().parent().parent().parent().find('._barcode').attr('name',_ref_counter+'__barcode__'+_id);
$(this).parent().parent().parent().parent().parent().parent().find('._base_rate').val(_item_rate);
   $(this).parent().parent().parent().parent().parent().parent().find('._base_unit_id').val(_main_unit_id);
  $(this).parent().parent().parent().parent().parent().parent().find('._main_unit_val').val(_main_unit_val);

  _purchase_total_calculation();
  $('.search_box_item').hide();
  $('.search_box_item').removeClass('search_box_show').hide();
})

$(document).on('change','._transection_unit',function(){
  var __this = $(this);
  var conversion_qty = $('option:selected', this).attr('attr_conversion_qty');
  console.log(conversion_qty)
 
  __this.closest('tr').find(".conversion_qty").val(conversion_qty);

  converted_qty_value(__this);
})

function converted_qty_value(__this){

  var _vat_amount =0;
  var _qty = __this.closest('tr').find('._qty').val();
  var _rate = __this.closest('tr').find('._rate').val();
  var _base_rate = __this.closest('tr').find('._base_rate').val();
  var _sales_rate =parseFloat( __this.closest('tr').find('._sales_rate').val());
  var _item_vat = __this.closest('tr').find('._vat').val();
  var conversion_qty = parseFloat(__this.closest('tr').find('.conversion_qty').val());
  var _item_discount = parseFloat(__this.closest('tr').find('._discount').val());




   if(isNaN(_item_vat)){ _item_vat   = 0 }

  if(isNaN(conversion_qty)){ conversion_qty   = 1 }
  var converted_price_rate = (( conversion_qty/1)*_base_rate);

   if(isNaN(_qty)){ _qty   = 0 }
   if(isNaN(_rate)){ _rate =0 }
   if(isNaN(_base_rate)){ _base_rate =0 }

  if(converted_price_rate ==0){
    converted_price_rate = _rate;
  }

   if(isNaN(_item_vat)){ _item_vat   = 0 }
   if(isNaN(_qty)){ _qty   = 0 }
   if(isNaN(_rate)){ _rate =0 }
   if(isNaN(_item_discount)){ _item_discount =0 }
   _vat_amount = Math.ceil(((_qty*converted_price_rate)*_item_vat)/100)
   _discount_amount = Math.ceil(((_qty*converted_price_rate)*_item_discount)/100)


   var _value = parseFloat(converted_price_rate*_qty).toFixed(2);
 __this.closest('tr').find('._rate').val(converted_price_rate);
 __this.closest('tr').find('._value').val(_value);
 __this.closest('tr').find('._vat_amount').val(_vat_amount);
 __this.closest('tr').find('._discount_amount').val(_discount_amount);
 _purchase_total_calculation();


}

$(document).on('change','._value',function(e){

  var _vat_amount =0;
  var _value = parseFloat($(this).closest('tr').find('._value').val());
  var _qty = parseFloat($(this).closest('tr').find('._qty').val());
  var _rate =parseFloat( $(this).closest('tr').find('._rate').val());

  if(isNaN(_value)){_value=0}
  if(isNaN(_qty)){_qty=0}
  if(isNaN(_rate)){ _rate   = 0 }

  if(_value > 0 && _qty > 0){
      var _rate = parseFloat(_value)/parseFloat(_qty);
      if(isNaN(_rate)){_rate=0}
      $(this).closest('tr').find('._rate').val(_rate);
  }

  if(_value > 0 && _rate > 0){
      var _qty = parseFloat(_value)/parseFloat(_rate);
      if(isNaN(_qty)){_qty=0}
      $(this).closest('tr').find('._qty').val(_qty);
  }

  // var _sales_rate =parseFloat( $(this).closest('tr').find('._sales_rate').val());
  // var _item_vat = parseFloat($(this).closest('tr').find('._vat').val());
  // var _item_discount = parseFloat($(this).closest('tr').find('._discount').val());
  // var _rate =parseFloat( $(this).closest('tr').find('._rate').val());


  //  if(isNaN(_item_vat)){ _item_vat   = 0 }
  
  //  if(isNaN(_rate)){ _rate =0 }
  //  if(isNaN(_sales_rate)){ _sales_rate =0 }
  //  if(isNaN(_item_discount)){ _item_discount =0 }
  //  _vat_amount = Math.ceil(((_qty*_rate)*_item_vat)/100)
  //  _discount_amount = Math.ceil(((_qty*_rate)*_item_discount)/100)
  //  _value = parseFloat((_qty*_rate)).toFixed(2);

  // $(this).closest('tr').find('._qty').val(_qty);
  // $(this).closest('tr').find('._value').val(_value);
  // $(this).closest('tr').find('._vat_amount').val(_vat_amount);
  // $(this).closest('tr').find('._discount_amount').val(_discount_amount);
    _purchase_total_calculation();

});

$(document).on('click',function(){
    var searach_show= $('.search_box_item').hasClass('search_box_show');
    var search_box_main_ledger= $('.search_box_main_ledger').hasClass('search_box_show');
    var search_box_purchase_order= $('.search_box_purchase_order').hasClass('search_box_show');
    if(searach_show ==true){
      $('.search_box_item').removeClass('search_box_show').hide();
    }

    if(search_box_main_ledger ==true){
      $('.search_box_main_ledger').removeClass('search_box_show').hide();
    }

    if(search_box_purchase_order ==true){
      $('.search_box_purchase_order').removeClass('search_box_show').hide();
    }
})

$(document).on('keyup','._common_keyup',function(){
  var _vat_amount =0;
  var _qty = parseFloat($(this).closest('tr').find('._qty').val());
  var _rate =parseFloat( $(this).closest('tr').find('._rate').val());
  var _sales_rate =parseFloat( $(this).closest('tr').find('._sales_rate').val());
  var _item_vat = parseFloat($(this).closest('tr').find('._vat').val());
  var _item_discount = parseFloat($(this).closest('tr').find('._discount').val());

   if(isNaN(_item_vat)){ _item_vat   = 0 }
   if(isNaN(_qty)){ _qty   = 0 }
   if(isNaN(_rate)){ _rate =0 }
   if(isNaN(_sales_rate)){ _sales_rate =0 }
   if(isNaN(_item_discount)){ _item_discount =0 }
   _vat_amount = Math.ceil(((_qty*_rate)*_item_vat)/100)
   _discount_amount = Math.ceil(((_qty*_rate)*_item_discount)/100)

  $(this).closest('tr').find('._value').val((_qty*_rate));
  $(this).closest('tr').find('._vat_amount').val(_vat_amount);
  $(this).closest('tr').find('._discount_amount').val(_discount_amount);
    _purchase_total_calculation();
})


// $(document).on('keyup','._expected_qty',function(){

// })

$(document).on('keyup','._vat_amount',function(){
 var _item_vat =0;
  var _qty = $(this).closest('tr').find('._qty').val();
  var _rate = $(this).closest('tr').find('._rate').val();
  var _vat_amount =  $(this).closest('tr').find('._vat_amount').val();
  
   if(isNaN(_vat_amount)){ _vat_amount = 0 }
   if(isNaN(_qty)){ _qty   = 0 }
   if(isNaN(_rate)){ _rate =0 }
   var _vat = parseFloat((_vat_amount/(_rate*_qty))*100).toFixed(2);
    $(this).closest('tr').find('._vat').val(_vat);

    $(this).closest('tr').find('._value').val((_qty*_rate));
 
    _purchase_total_calculation();
})

$(document).on('keyup','._discount_amount',function(){
 var _item_vat =0;
  var _qty = $(this).closest('tr').find('._qty').val();
  var _rate = $(this).closest('tr').find('._rate').val();
  var _sales_rate = $(this).closest('tr').find('._sales_rate').val();
  var _discount_amount =  $(this).closest('tr').find('._discount_amount').val();
  
   if(isNaN(_discount_amount)){ _discount_amount = 0 }
   if(isNaN(_qty)){ _qty   = 0 }
   if(isNaN(_rate)){ _rate =0 }
   if(isNaN(_sales_rate)){ _sales_rate =0 }
   var _discount = parseFloat((_discount_amount/(_rate*_qty))*100).toFixed(2);
    $(this).closest('tr').find('._discount').val(_discount);

    $(this).closest('tr').find('._value').val((_qty*_rate));
 
    _purchase_total_calculation();
})

$(document).on("change","#_purchase_discount_input",function(){
  var _discount_input = $(this).val();
  var res = _discount_input.match(/%/gi);
  if(res){
     res = _discount_input.split("%");
    res= parseFloat(res);
    on_invoice_discount = ($(document).find("#_purchase_sub_total").val()*res)/100
    $(document).find("#_purchase_discount_input").val(on_invoice_discount)

  }else{
    on_invoice_discount = _discount_input;
  }

   $(document).find("#_purchase_total_discount").val(on_invoice_discount);
    _purchase_total_calculation()
})



 function _purchase_total_calculation(){
  console.log('calculation here')
    var _total_qty = 0;
    var _total__value = 0;
    var _total__vat =0;
    var _total_discount_amount = 0;
    var _total__expected_qty = 0;
      $(document).find("._value").each(function() {
            var _s_value =parseFloat($(this).val());
            if(isNaN(_s_value)){_s_value = 0}
          _total__value +=parseFloat(_s_value);
      });

      $(document).find("._qty").each(function() {
            var _s_qty =parseFloat($(this).val());
            if(isNaN(_s_qty)){_s_qty = 0}
          _total_qty +=parseFloat(_s_qty);
      });

      $(document).find("._vat_amount").each(function() {
            var _s_vat =parseFloat($(this).val());
            if(isNaN(_s_vat)){_s_vat = 0}
          _total__vat +=parseFloat(_s_vat);
      });

      $(document).find("._discount_amount").each(function() {
            var _s_discount_amount =parseFloat($(this).val());
            if(isNaN(_s_discount_amount)){_s_discount_amount = 0}
          _total_discount_amount +=parseFloat(_s_discount_amount);
      });


      $(document).find("._expected_qty").each(function() {
            var _expected_qty =parseFloat($(this).val());
            if(isNaN(_expected_qty)){_expected_qty = 0}
          _total__expected_qty +=parseFloat(_expected_qty);
      });



      $(document).find("._total_expected_qty_amount").val(_total__expected_qty);
      $(document).find("._total_qty_amount").val(_total_qty);
      $(document).find("._total_value_amount").val(_total__value);
      $(document).find("._total_vat_amount").val(_total__vat);
      $(document).find("._total_discount_amount").val(_total_discount_amount);

      var _discount_input = parseFloat($(document).find("#_purchase_discount_input").val());
      if(isNaN(_discount_input)){ _discount_input =0 }
      var _total_discount = parseFloat(_discount_input)+parseFloat(_total_discount_amount);
      $(document).find("#_purchase_sub_total").val(_math_round(_total__value));
      $(document).find("#_purchase_total_vat").val(_total__vat);
      $(document).find("#_purchase_total_discount").val(parseFloat(_discount_input)+parseFloat(_total_discount_amount));
      var _total = _math_round((parseFloat(_total__value)+parseFloat(_total__vat))-parseFloat(_total_discount));
      $(document).find("#_total").val(_total);
  }


 var single_row =  `<tr class="_voucher_row">
                      <td><a  href="" class="btn btn-sm btn-default _voucher_row_remove" ><i class="fa fa-trash"></i></a></td>
                      <td>
                      <input type="hidden" name="purchase_account_id[]" class="form-control purchase_account_id" value="0"  />
                      </td>
                      <td><input type="text" name="_search_ledger_id[]" class="form-control _search_ledger_id width_280_px" placeholder="Ledger" @if($__user->_ac_type==1) attr_account_head_no="1" @endif >
                      <input type="hidden" name="_ledger_id[]" class="form-control _ledger_id" >
                      <div class="search_box">
                      </div>
                      </td>

                       @if(sizeof($permited_branch)>1)
                      <td>
                      <select class="form-control width_150_px _branch_id_detail" name="_branch_id_detail[]"  required >
                        @forelse($permited_branch as $branch )
                            <option value="{{$branch->id}}" @if(isset($request->_branch_id)) @if($request->_branch_id == $branch->id) selected @endif   @endif>{{ $branch->_name ?? '' }}</option>
                        @empty
                        @endforelse
                        </select>
                        </td>
                        @else
                          <td class="display_none">
                      <select class="form-control width_150_px _branch_id_detail" name="_branch_id_detail[]"  required >
                        @forelse($permited_branch as $branch )
                            <option value="{{$branch->id}}" @if(isset($request->_branch_id)) @if($request->_branch_id == $branch->id) selected @endif   @endif>{{ $branch->_name ?? '' }}</option>
                        @empty
                        @endforelse
                        </select>
                        </td>
                        @endif

                         @if(sizeof($permited_costcenters)>1)
                        <td>
                          <select class="form-control width_150_px _cost_center" name="_cost_center[]" required >
                            @forelse($permited_costcenters as $costcenter )
                              <option value="{{$costcenter->id}}" @if(isset($request->_cost_center)) @if($request->_cost_center == $costcenter->id) selected @endif   @endif> {{ $costcenter->_name ?? '' }}</option>
                            @empty
                            @endforelse
                            </select>
                            </td>
                        @else
                        <td class="display_none">
                          <select class="form-control width_150_px _cost_center" name="_cost_center[]" required >
                            @forelse($permited_costcenters as $costcenter )
                              <option value="{{$costcenter->id}}" @if(isset($request->_cost_center)) @if($request->_cost_center == $costcenter->id) selected @endif   @endif> {{ $costcenter->_name ?? '' }}</option>
                            @empty
                            @endforelse
                            </select>
                            </td>
                        @endif
                            <td><input type="text" name="_short_narr[]" class="form-control width_250_px" placeholder="Short Narr"></td>
                            <td>
                              <input type="number" name="_dr_amount[]" class="form-control  _dr_amount" placeholder="Dr. Amount" value="{{old('_dr_amount',0)}}">
                            </td>
                            <td class=" @if($__user->_ac_type==1) display_none @endif ">
                              <input type="number" name="_cr_amount[]" class="form-control  _cr_amount" placeholder="Cr. Amount" value="{{old('_cr_amount',0)}}">
                              </td>
                            </tr>`;

  function voucher_row_add(event) {
      event.preventDefault();
      $(document).find("#area__voucher_details").append(single_row);
      change_branch_cost_strore();
  }

var _purchase_row_single =``;
function purchase_row_add(event){
   event.preventDefault();
     _item_row_count= $(document).find("._item_row_count").val();
      $(document).find("._item_row_count").val((parseFloat(_item_row_count)+1));
     var  _item_row_count = (parseFloat(_item_row_count)+1);
      $(document).find("#area__purchase_details").append(`<tr class="_purchase_row">
                                              <td>
                                                <a  href="#none" class="btn btn-default _purchase_row_remove" ><i class="fa fa-trash"></i></a>
                                            
                                              <input type="hidden" name="purchase_detail_id[]" class="form-control purchase_detail_id" value="0" />
                                              </td>
                                              <td>
                                                <input type="text" name="_search_item_id[]" class="form-control _search_item_id width_280_px" placeholder="Item">
                                                <input type="hidden" name="_item_id[]" class="form-control _item_id width_200_px" >
                                                <div class="search_box_item">
                                                  
                                                </div>
                                              </td>
                                              <td class="display_none">
                                                <input type="text" name="conversion_qty[]"  class="form-control conversion_qty " value="1" readonly>
                                                <input type="hidden" name="_base_rate[]" class="form-control _base_rate _common_keyup" value="0" >
                                              </td>
                                              <td class="@if($_show_unit==0) display_none @endif">
                                                <select class="form-control _transection_unit" name="_transection_unit[]">
                                                </select>
                                              </td>
                                             @if(isset($_show_barcode)) @if($_show_barcode==1)
                                              <td>
                                                <input type="text" name="_barcode[]" class="form-control _barcode ${_item_row_count}__barcode " id="${_item_row_count}__barcode" >
                                              </td>
                                              @else
                                              <td class="display_none">
                                                <input type="text" name="_barcode[]" class="form-control _barcode  ${_item_row_count}__barcode " id="${_item_row_count}__barcode"  >
                                              </td>
                                              @endif
                                              @endif
                                              @if(isset($_show_short_note)) @if($_show_short_note==1)
                                              <td>
                                                <input type="text" name="_short_note[]" class="form-control _short_note ${_item_row_count}__short_note " id="${_item_row_count}__short_note" >
                                              </td>
                                              @else
                                              <td class="display_none">
                                                <input type="text" name="_short_note[]" class="form-control _short_note  ${_item_row_count}__short_note " id="${_item_row_count}__short_note"  >
                                              </td>
                                              @endif
                                              @endif
                                              <td class="@if($_show_expected_qty==0) display_none @endif">
                                                <input type="number" name="_expected_qty[]" class="form-control _expected_qty _common_keyup" value="0">
                                              </td>


                                              <td>
                                                <input type="number" name="_qty[]" class="form-control _qty _common_keyup" >
                                                <input type="hidden" name="_ref_counter[]" value="${_item_row_count}" class="_ref_counter" id="${_item_row_count}__ref_counter">
                                              </td>
                                              <td>
                                                <input type="number" name="_rate[]" class="form-control _rate _common_keyup" >
                                              </td>
                                              <td class="@if($_show_sales_rate==0) display_none @endif">
                                                <input type="number" name="_sales_rate[]" class="form-control _sales_rate " >
                                              </td>
                                              
                                              <td>
                                                <input type="number" name="_value[]" class="form-control _value "  >
                                              </td>
                                              
                                             
                                              
                                              @if(isset($_show_self)) @if($_show_self==1)
                                              <td>
                                                <input type="text" name="_store_salves_id[]" class="form-control _store_salves_id " >
                                              </td>
                                              @else
                                              <td class="display_none">
                                                <input type="text" name="_store_salves_id[]" class="form-control _store_salves_id " >
                                              </td>
                                              @endif

                                              @endif
                                                <td class="@if(isset($form_settings->_show_manufacture_date)) @if($form_settings->_show_manufacture_date==0) display_none  @endif @endif">
                                                <input type="date" name="_manufacture_date[]" class="form-control _manufacture_date " >
                                              </td>
                                              <td class="@if(isset($form_settings->_show_expire_date)) @if($form_settings->_show_expire_date==0) display_none  @endif @endif">
                                                <input type="date" name="_expire_date[]" class="form-control _expire_date " >
                                              </td>
                                              
                                            </tr>`);


change_branch_cost_strore();
       
}
 $(document).on('click','._purchase_row_remove',function(event){
      event.preventDefault();
      var ledger_id = $(this).parent().parent('tr').find('._item_id').val();
      if(ledger_id ==""){
          $(this).parent().parent('tr').remove();
          $(this).parent().parent('tr').find('._ref_counter').remove();
      }else{
        if(confirm('Are you sure your want to delete?')){
          $(this).parent().parent('tr').remove();
          $(this).parent().parent('tr').find('._ref_counter').remove();
        } 
      }
      _purchase_total_calculation();
  })

 

  



  


  $(document).on('click','.submit-button',function(event){
    event.preventDefault();
    var _total_dr_amount = $(document).find("._total_dr_amount").val();
    var _total_cr_amount = $(document).find("._total_cr_amount").val();
    var _voucher_type = $(document).find('._voucher_type').val();
    var _note = $(document).find('._note').val();
    var _main_ledger_id = $(document).find('._main_ledger_id').val();
    if(_main_ledger_id  ==""){
       alert(" Please Add Ledger  ");
        $(document).find('._search_main_ledger_id').addClass('required_border').focus();
        return false;
    }


    var empty_ledger = [];
    $(document).find("._search_item_id").each(function(){
        if($(this).val() ==""){
          console.log($(this))
          alert(" Please Add Item  ");
          $(this).addClass('required_border');
          empty_ledger.push(1);
        }  
    })

    if(empty_ledger.length > 0){
      return false;
    }

    //Empty or Zero Qty Check
    var empty_or_zero_qty=[];
    $(document).find("._qty").each(function(){
      var zero_qty = parseFloat($(this).val());
      if(isNaN(zero_qty) || zero_qty==0 ){
         empty_or_zero_qty.push(1);
      }
    })

    if(empty_or_zero_qty.length > 0){
      alert("Please input QTY");
      return false;
    }
    

@if($__user->_ac_type==0)
    if( parseFloat(_total_dr_amount) !=parseFloat(_total_cr_amount)){
      $(document).find("._total_dr_amount").addClass('required_border').focus();
      $(document).find("._total_cr_amount").addClass('required_border').focus();
       alert("Account Details Dr. And Cr. Amount Not Equal");
      return false;

    }
@endif

     if(_note ==""){
       
       $(document).find('._note').focus().addClass('required_border');
      return false;
    }else if(_main_ledger_id ==""){
       
      $(document).find('._search_main_ledger_id').focus().addClass('required_border');
      return false;
    }else{
       $('.submit-button').attr('disabled','true');
      $(document).find('.purchase_form').submit();
    }
  })


$(document).on('keyup','._search_order_ref_id',delay(function(e){
    $(document).find('._search_order_ref_id').removeClass('required_border');
    var _gloabal_this = $(this);
    var _text_val = $(this).val().trim();
    var _branch_id = $(document).find('._master_branch_id').val();

  var request = $.ajax({
      url: "{{url('purchase-pre-order-search')}}",
      method: "GET",
      data: { _text_val,_branch_id },
      dataType: "JSON"
    });
    request.done(function( result ) {

      var search_html =``;
      var data = result.data; 
       console.log(data)
      if(data.length > 0 ){
            search_html +=`<div class="card"><table table-bordered style="width: 100%;">
                            <thead>
                              <th style="border:1px solid #ccc;text-align:center;">ID</th>
                              <th style="border:1px solid #ccc;text-align:center;">Supplier</th>
                              <th style="border:1px solid #ccc;text-align:center;">Date</th>
                            </thead>
                            <tbody>`;
                        for (var i = 0; i < data.length; i++) {
                         search_html += `<tr class="search_row_purchase_order" >
                                        <td style="border:1px solid #ccc;">${data[i].id}
                                        <input type="hidden" name="_id_main_ledger" class="_id_main_ledger" value="${data[i]._ledger_id}">
                                        <input type="hidden" name="_purchase_main_id" class="_purchase_main_id" value="${data[i].id}">
                                        <input type="hidden" name="_purchase_main_date" class="_purchase_main_date" value="${after_request_date__today(data[i]._date)}">
                                        </td><td style="border:1px solid #ccc;">${data[i]._ledger._name}
                                        <input type="hidden" name="_name_main_ledger" class="_name_main_ledger" value="${data[i]._ledger._name}">
                                        <input type="hidden" name="_address_main_ledger" class="_address_main_ledger" value="${data[i]._address}">
                                        <input type="hidden" name="_phone_main_ledger" class="_phone_main_ledger" value="${data[i]._phone}">
                                        
                                        <input type="hidden" name="_date_main_ledger" class="_date_main_ledger" value="${after_request_date__today(data[i]._date)}">
                                   </td>
                                   <td style="border:1px solid #ccc;">${after_request_date__today(data[i]._date)}
                                   </td></tr>`;
                        }                         
            search_html += ` </tbody> </table></div>`;
      }else{
        search_html +=`<div class="card"><table style="width: 300px;"> 
        <thead><th colspan="3">No Data Found</th></thead><tbody></tbody></table></div>`;
      }     
      _gloabal_this.parent('div').find('.search_box_purchase_order').html(search_html);
      _gloabal_this.parent('div').find('.search_box_purchase_order').addClass('search_box_show').show();
      
    });
     
    request.fail(function( jqXHR, textStatus ) {
      alert( "Request failed: " + textStatus );
    });
}, 500));

$(document).on("click",'.search_row_purchase_order',function(){
    var _id = $(this).children('td').find('._id_main_ledger').val();
    var _name = $(this).find('._name_main_ledger').val();
    var _purchase_main_id = $(this).find('._purchase_main_id').val();
    var _purchase_main_date = $(this).find('._purchase_main_date').val();
    var _main_branch_id = $(this).find('._main_branch_id').val();
    var _date_main_ledger = $(this).find('._date_main_ledger').val();
    var _address_main_ledger = $(this).find('._address_main_ledger').val();


    var _phone_main_ledger = $(this).find('._phone_main_ledger').val();
   
    if(_address_main_ledger =='null' ){ _address_main_ledger =""; } 
    if(_phone_main_ledger =='null' ){ _phone_main_ledger =""; } 

    $(document).find("._main_ledger_id").val(_id);
    $(document).find("._search_main_ledger_id").val(_name);
    $(document).find("._order_ref_id").val(_purchase_main_id);
    $(document).find("._phone").val(_phone_main_ledger);
    $(document).find("._address").val(_address_main_ledger);



    $(document).find("._search_order_ref_id").val(_purchase_main_id+","+_date_main_ledger);

    $.ajaxSetup({ headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

    var request = $.ajax({
      url: "{{url('purchase-pre-order-details')}}",
      method: "POST",
      data: { _purchase_main_id,_main_branch_id },
      dataType: "JSON"
    });
    request.done(function( result ) {
      console.log(result)
      var data = result;
      var _purchase_row_single = ``;
      $(document).find("#area__purchase_details").empty();
     
if(data.length > 0 ){
  for (var i = 0; i < data.length; i++) {
    var _item_row_count = (parseFloat(i)+1);
    var _item_name = (data[i]._items._name) ? data[i]._items._name : '' ;
    var _item_id = (data[i]._item_id) ? data[i]._item_id : '' ;
    var _qty   = (data[i]._qty  ) ? data[i]._qty   : 0 ;
    var _rate    = (data[i]._rate) ? data[i]._rate    : 0 ;
    var _sales_rate =  0 ;
    var _value = ( (data[i]._qty*data[i]._rate) ) ? (data[i]._qty*data[i]._rate) : 0 ;
   
$(document).find("._item_row_count").val(_item_row_count)
       
            $(document).find("#area__purchase_details").append(`<tr class="_purchase_row">
                                              <td>
                                                <a  href="#none" class="btn btn-default _purchase_row_remove" ><i class="fa fa-trash"></i></a>
                                              
                                              <input type="hidden" name="purchase_detail_id[]" class="form-control purchase_detail_id" value="0" />
                                              </td>
                                              <td>
                                                <input type="text" name="_search_item_id[]" class="form-control _search_item_id width_280_px" placeholder="Item" value="${_item_name}">
                                                <input type="hidden" name="_item_id[]" class="form-control _item_id width_200_px" value="${_item_id}">
                                                <div class="search_box_item">
                                                  
                                                </div>
                                              </td>
                                              <td class="display_none">
                                                <input type="text" name="conversion_qty[]"  class="form-control conversion_qty " value="1" readonly>
                                                <input type="hidden" name="_base_rate[]" class="form-control _base_rate _common_keyup" value="0" >
                                              </td>
                                              <td class="@if($_show_unit==0) display_none @endif">
                                                <select class="form-control _transection_unit" name="_transection_unit[]">
                                                </select>
                                              </td>
                                             @if(isset($_show_barcode)) @if($_show_barcode==1)
                                              <td>
                                                <input type="text" name="_barcode[]" class="form-control _barcode ${_item_row_count}__barcode " id="${_item_row_count}__barcode" >
                                              </td>
                                              @else
                                              <td class="display_none">
                                                <input type="text" name="_barcode[]" class="form-control _barcode ${_item_row_count}__barcode " id="${_item_row_count}__barcode"  >
                                              </td>
                                              @endif
                                              @endif
                                              <td class="@if($_show_expected_qty==0) display_none @endif">
                                                <input type="number" name="_expected_qty[]" class="form-control _expected_qty _common_keyup" value="0">
                                              </td>


                                              <td>
                                                <input type="number" name="_qty[]" class="form-control _qty _common_keyup" value="${_qty}">
                                                <input type="hidden" name="_ref_counter[]" value="${_item_row_count}" class="_ref_counter" id="${_item_row_count}__ref_counter">
                                              </td>
                                              <td>
                                                <input type="number" name="_rate[]" class="form-control _rate _common_keyup" value="${_rate}">
                                              </td>
                                              <td>
                                                <input type="number" name="_sales_rate[]" class="form-control _sales_rate " value="${_sales_rate}">
                                              </td>
                                               @if(isset($_show_vat)) @if($_show_vat==1)
                                              <td>
                                                <input type="text" name="_vat[]" class="form-control  _vat _common_keyup" >
                                              </td>
                                              <td>
                                                <input type="text" name="_vat_amount[]" class="form-control  _vat_amount" >
                                              </td>
                                              @else
                                                <td class="display_none">
                                                <input type="text" name="_vat[]" class="form-control  _vat _common_keyup" >
                                              </td>
                                              <td class="display_none">
                                                <input type="text" name="_vat_amount[]" class="form-control  _vat_amount" >
                                              </td>
                                              @endif
                                              @endif
                                              <td>
                                                <input type="number" name="_value[]" class="form-control _value "  value="${_value}">
                                              </td>
                                              
                                              
                                              <td class="@if(sizeof($store_houses) == 0) display_none @endif">
                                                <select class="form-control  _main_store_id" name="_main_store_id[]">
                                                  @forelse($store_houses as $store)
                                                  <option value="{{$store->id}}">{{$store->_name ?? '' }}</option>
                                                  @empty
                                                  @endforelse
                                                </select>
                                                
                                              </td>
                                              
                                              @if(isset($_show_self)) @if($_show_self==1)
                                              <td>
                                                <input type="text" name="_store_salves_id[]" class="form-control _store_salves_id " >
                                              </td>
                                              @else
                                              <td class="display_none">
                                                <input type="text" name="_store_salves_id[]" class="form-control _store_salves_id " >
                                              </td>
                                              @endif

                                              @endif
                                               <td class="@if(isset($form_settings->_show_manufacture_date)) @if($form_settings->_show_manufacture_date==0) display_none  @endif @endif">
                                                <input type="date" name="_manufacture_date[]" class="form-control _manufacture_date " >
                                              </td>
                                              <td class="@if(isset($form_settings->_show_expire_date)) @if($form_settings->_show_expire_date==0) display_none  @endif @endif">
                                                <input type="date" name="_expire_date[]" class="form-control _expire_date " >
                                              </td>
                                              
                                            </tr>`);
_new_barcode_function(_item_row_count);
                                          }
                                        }else{
                                          _purchase_row_single += `Returnable Item Not Found !`;
                                        }

              _purchase_total_calculation();
    })



  })
 function _new_barcode_function(_item_row_count){
      $('#'+_item_row_count+'__barcode').amsifySuggestags({
      trimValue: true,
      dashspaces: true,
      showPlusAfter: 1,
      });
  }
 



</script>


@endsection