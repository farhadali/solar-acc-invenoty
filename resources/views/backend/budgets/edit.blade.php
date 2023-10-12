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
          <div class="col-sm-6">
           <a class="m-0 _page_name" href="{{ route('budgets.index') }}"> {!! $page_name ?? '' !!} </a>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <!-- <li class="breadcrumb-item"><a href="{{url('home')}}">Home</a></li> -->
              <li class="breadcrumb-item active">
                 <a class="btn btn-primary" href="{{ route('budgets.index') }}"> {{ $page_name ?? '' }} </a>
               </li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <div class="message-area">
    @if (count($errors) > 0)
           <div class="alert alert-danger">
                
                <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
                </ul>
            </div>
        @endif
    </div>
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-12">
            <div class="card">
             
              <div class="card-body">
                {!! Form::model($data, ['method' => 'PATCH','route' => ['budgets.update', $data->id]]) !!}
               
                    <div class="row">
                       <input type="hidden" id="{!! $data->id !!}" >
                        <div class="col-xs-12 col-sm-12 col-md-2 @if(sizeof($permited_organizations)==1) display_none @endif">
                     <div class="form-group ">
                         <label>{!! __('label.organization') !!}:<span class="_required">*</span></label>
                        <select class="form-control _master_organization_id" name="organization_id" required >

                           
                           @forelse($permited_organizations as $val )
                           <option value="{{$val->id}}" @if(isset($data->organization_id)) @if($data->organization_id == $val->id) selected @endif   @endif>{{ $val->id ?? '' }} - {{ $val->_name ?? '' }}</option>
                           @empty
                           @endforelse
                         </select>
                     </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-2 @if(sizeof($permited_branch)==1) display_none @endif">
                     <div class="form-group ">
                         <label>Branch:<span class="_required">*</span></label>
                        <select class="form-control _master_branch_id" name="_branch_id" required >
                           
                           @forelse($permited_branch as $branch )
                           <option value="{{$branch->id}}" @if(isset($data->_branch_id)) @if($data->_branch_id == $branch->id) selected @endif   @endif>{{ $branch->id ?? '' }} - {{ $branch->_name ?? '' }}</option>
                           @empty
                           @endforelse
                         </select>
                     </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-2 @if(sizeof($permited_costcenters)==1) display_none @endif">
                     <div class="form-group ">
                         <label>{{__('label.Cost center')}}:<span class="_required">*</span></label>
                        <select class="form-control _cost_center_id" name="_cost_center_id" required >
                           
                           @forelse($permited_costcenters as $cost_center )
                           <option value="{{$cost_center->id}}" @if(isset($data->_cost_center_id)) @if($data->_cost_center_id == $cost_center->id) selected @endif   @endif>{{ $cost_center->id ?? '' }} - {{ $cost_center->_name ?? '' }}</option>
                           @empty
                           @endforelse
                         </select>
                     </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-2">
                            <div class="form-group">
                                <label>{{__('label.start_date')}}:</label>
                                {!! Form::date('_start_date', $data->_start_date, array('placeholder' => __('label.start_date'),'class' => 'form-control')) !!}
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-2">
                            <div class="form-group">
                                <label>{{__('label.end_date')}}:</label>
                                {!! Form::date('_end_date', $data->_end_date, array('placeholder' => __('label.end_date'),'class' => 'form-control')) !!}
                            </div>
                        </div>
                         <div class="col-xs-12 col-sm-12 col-md-2">
                            <div class="form-group">
                                <label>{{__('label._project_value')}}:<span class="_required">*</span></label>
                                {!! Form::number('_project_value', null, array('placeholder' => __('label._project_value'),'class' => 'form-control','step'=>'any','required'=>'required')) !!}
                            </div>
                        </div>

                   </div>
                   <div class="row">
                    <div class="col-md-12  ">
                             <div class="card">
                              <div class="card-header">
                                <strong>{{__('label.budget_income_head')}}</strong>
                              </div>

                              @php
                              $_budget_details_income = $data->_budget_details_income ?? [];
                              @endphp
                              <div class="card-body">
                                <div class="table-responsive">
                                      <table class="table table-bordered" >
                                          <thead>
                                            <th>&nbsp;</th>
                                            <th>Ledger</th>
                                            <th>Short Narr.</th>
                                            <th>Amount</th>
                                          </thead>
                                          @php
                                            $_total_income=0;
                                          @endphp
                                          <tbody class="area__voucher_details form_body" id="area__voucher_details">
                                            @forelse($_budget_details_income as $in_val)
                                            @php
                                            $_total_income +=$in_val->_budget_amount ?? 0;
                                          @endphp
                                            <tr class="_voucher_row">
                                              <td>
                                                <a  href="#none" class="btn btn-sm btn-default _voucher_row_remove" ><i class="fa fa-trash"></i></a>
                                                <input type="hidden" name="_income_row_id[]" value="{{$in_val->id ?? 0}}">
                                              </td>
                                              <td>
                                                <input type="text" name="_search_ledger_id[]" class="form-control _search_ledger_id width_280_px" placeholder="Ledger" value="{!! $in_val->_ledger->_name ?? '' !!}">
                                                <input type="hidden" name="_ledger_id[]" class="form-control _ledger_id"  value="{!! $in_val->_ledger_id !!}">
                                                <div class="search_box"></div>
                                              </td>
                                              <td>
                                                <input type="text" name="_short_narr[]" class="form-control width_250_px _short_narr" placeholder="Short Narr" value="{!! $in_val->_short_narr ?? '' !!}">
                                              </td>
                                              
                                              <td>
                                                <input type="number" name="_cr_amount[]" class="form-control  _cr_amount" placeholder="Amount" value="{{old('_cr_amount',$in_val->_budget_amount)}}">
                                              </td>
                                            </tr>
                                            @empty
                                            @endforelse
                                          </tbody>
                                          <tfoot>
                                            <tr>
                                              <td>
                                                <a href="#none"  class="btn btn-default btn-sm" onclick="voucher_row_add(event)"><i class="fa fa-plus"></i></a>
                                              </td>
                                              <td  class="text-right"><b>Total</b></td>
                                              <td></td>
                                              
                                              <td>
                                                <input type="number" step="any" min="0" name="_total_cr_amount" class="form-control _total_cr_amount" value="{{ $_total_income ?? 0}}" readonly required>
                                              </td>
                                            </tr>
                                          </tfoot>
                                      </table>
                                </div>
                            </div>
                          </div>
                        </div>
                     <div class="col-md-12  ">
                             <div class="card">
                              <div class="card-header">
                                <strong>{{__('label.budget_material_detail')}}</strong>

                              </div>
                             @php
                             $total_qty=0;
                             $_total_item_values=0;
                             $_budget_item_details = $data->_budget_item_details ?? [];
                             @endphp
                              <div class="card-body">
                                <div class="table-responsive">
                                      <table class="table table-bordered" >
                                          <thead >
                                            <th class="text-left" >&nbsp;</th>
                                            <th class="text-left" >Item</th>
                                            <th class="text-left" >Unit</th>
                                            <th class="text-left" >Qty</th>
                                            <th class="text-left" >Rate</th>
                                            <th class="text-left" >Value</th>
                                          </thead>
                                          <tbody class="area__purchase_details" id="area__purchase_details">
                                            @forelse($_budget_item_details as $i_val)

                             @php
                             $total_qty          +=$i_val->_item_qty ?? 0;
                             $_total_item_values +=$i_val->_item_budget_amount ?? 0;
                             @endphp
                                            <tr class="_purchase_row">
                                              <td>
                                                <a  href="#none" class="btn btn-default _purchase_row_remove" ><i class="fa fa-trash"></i></a>
                                                <input type="hidden" name="_item_row_id[]" value="{{$i_val->id ?? 0}}">
                                              </td>
                                              <td>
                                                <input type="text" name="_search_item_id[]" class="form-control _search_item_id width_280_px" placeholder="Item" value="{!! $i_val->_item->_item ?? '' !!}">
                                                <input type="hidden" name="_item_id[]" class="form-control _item_id width_200_px" value="{!! $i_val->_item_id ?? '' !!}" >
                                                <div class="search_box_item">
                                                  
                                                </div>
                                              </td>
                                              <td >
                                                <select class="form-control _transection_unit" name="_transection_unit[]">
                                                   <option value="{{$i_val->_item_unit_id}}">{{$i_val->_item->_units->_name ?? ''}}</option>
                                                </select>
                                              </td>
                                              <td>
                                                <input type="number" name="_qty[]" class="form-control _qty _common_keyup" value="{!! $i_val->_item_qty ?? 0 !!}" >
                                              </td>
                                              <td>
                                                <input type="number" name="_rate[]" class="form-control _rate _common_keyup" value="{!! $i_val->_item_unit_price ?? 0 !!}" >
                                                
                                              </td>
                                             
                                              <td>
                                                <input type="number" name="_value[]" class="form-control _value " readonly value="{!! $i_val->_item_budget_amount ?? 0 !!}"  >
                                              </td>
                                            </tr>
                                            @empty
                                            @endforelse
                                          </tbody>
                                          <tfoot>
                                            <tr>
                                              <td>
                                                <a href="#none"  class="btn btn-default btn-sm" onclick="purchase_row_add(event)"><i class="fa fa-plus"></i></a>
                                              </td>
                                              <td  class="text-right"><b>Total</b></td>
                                             <td></td>
                                              <td>
                                                <input type="number" step="any" min="0" name="_total_qty_amount" class="form-control _total_qty_amount" value="{{$total_qty ?? 0}}" readonly required>
                                              </td>
                                              <td></td>
                                              <td>
                                                <input type="number" step="any" min="0" name="_total_value_amount" class="form-control _total_value_amount" value="{{$_total_item_values ?? 0}}" readonly required>
                                              </td>
                                            </tr>
                                          </tfoot>
                                      </table>
                                </div>
                            </div>
                          </div>
                        </div>  

                        <div class="col-md-12  ">
                             <div class="card ">
                              <div class="card-header">
                                <strong>{{__('label.budget_expenses_head')}}</strong>
                              </div>
                              @php
                              $exp_total_amount=0;
                              $_budget_details_expense = $data->_budget_details_expense_deduction ?? [];
                              @endphp
                              <div class="card-body">
                                <div class="table-responsive">
                                      <table class="table table-bordered" >
                                          <thead>
                                            <th>&nbsp;</th>
                                            <th>Ledger</th>
                                            <th>{{__('label._type')}}</th>
                                            <th>Short Narr.</th>
                                            <th>Amount</th>
                                          </thead>
                                          <tbody class="area__voucher_details_expenses form_body" id="area__voucher_details_expenses">
                                            @forelse($_budget_details_expense as $ex_val)

                                            @php
                              $exp_total_amount +=$ex_val->_budget_amount ?? 0;
                              @endphp
                                            <tr class="_voucher_row">
                                              <td>
                                                <a  href="#none" class="btn btn-sm btn-default _voucher_row_remove" ><i class="fa fa-trash"></i></a>
                                                <input type="hidden" name="_exp_row_id[]" value="{{$ex_val->id ?? 0}}">
                                              </td>
                                              <td>
                                                <input type="text" name="_search_ledger_id[]" class="form-control _search_ledger_id width_280_px" placeholder="Ledger" value="{!! $ex_val->_ledger->_name ?? '' !!}">
                                                <input type="hidden" name="_exp_ledger_id[]" class="form-control _ledger_id" value="{{$ex_val->_ledger_id }}">
                                                <div class="search_box"> </div>
                                              </td>
                                              <td>
                                                <select class="form-control" name='_ledger_type[]'>
                                                  <option value="expense" @if($ex_val->_ledger_type=='expense') selected @endif>Expenses</option>
                                                  <option value="deduction" @if($ex_val->_ledger_type=='deduction') selected @endif>Deduction</option>
                                                </select>
                                              </td>
                                              <td>
                                                <input type="text" name="_exp_short_narr[]" class="form-control width_250_px _short_narr" placeholder="Short Narr" value="{!! $ex_val->_short_narr ?? '' !!}">
                                              </td>
                                              <td>
                                                <input type="number" name="_exp_dr_amount[]" class="form-control  _dr_amount" placeholder="Dr. Amount" value="{{old('_dr_amount',$ex_val->_budget_amount)}}">
                                              </td>
                                              
                                            </tr>
                                            @empty
                                            @endforelse
                                          </tbody>
                                          <tfoot>
                                            <tr>
                                              <td>
                                                <a href="#none"  class="btn btn-default btn-sm" onclick="voucher_row_add_expense(event)"><i class="fa fa-plus"></i></a>
                                              </td>
                                               <td  class="text-right"><b>Total</b></td>
                                              <td></td>
                                              <td></td>
                                              <td>
                                                <input type="number" step="any" min="0" name="_total_dr_amount" class="form-control _total_dr_amount" value="{{$exp_total_amount ?? 0}}" readonly required>
                                              </td>
                                              
                                            </tr>
                                          </tfoot>
                                      </table>
                                </div>
                            </div>
                          </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-12 mb-10">
                          <table class="table" style="border-collapse: collapse;">
                            <tr>
                              <td style="width: 10%;border:0px;"><label for="_remarks">Note<span class="_required">*</span></label></td>
                              <td style="width: 70%;border:0px;">
                                
                                    <textarea required name="_remarks" class="form-control _remarks">{{old('_remarks',$data->_remarks ?? '' )}}</textarea>
                                    
                              </td>
                            </tr>

                            <tr>
                              <td style="width: 20%;border:0px;"><label for="expected_income">{{__('label.expected_income')}}</label></td>
                              <td style="width: 70%;border:0px;">
                                <input type="text" name="expected_income" class="form-control width_200_px" id="expected_income" readonly value="{!! $data->_income_amount ?? 0 !!}">
                              </td>
                            </tr>
                            <tr>
                              <td style="width: 20%;border:0px;"><label for="expected_material_expense">{{__('label.expected_material_expense')}}</label></td>
                              <td style="width: 70%;border:0px;">
                                <input type="text" name="expected_material_expense" class="form-control width_200_px" id="expected_material_expense" readonly value="{!! $data->_material_amount ?? 0 !!}">
                              </td>
                            </tr>
                            <tr>
                              <td style="width: 20%;border:0px;"><label for="expected_other_expenses">{{__('label.expected_other_expenses')}}</label></td>
                              <td style="width: 70%;border:0px;">
                                <input type="text" name="expected_other_expenses" class="form-control width_200_px" id="expected_other_expenses" readonly value="{!! $data->_expense_amount ?? 0 !!}">
                              </td>
                            </tr>
                            <tr>
                              <td style="width: 20%;border:0px;"><label for="expected_profit">{{__('label.expected_profit')}}</label></td>
                              <td style="width: 70%;border:0px;">
                                <input type="text" name="expected_profit" class="form-control width_200_px" id="expected_profit" readonly value="{!! $data->_income_amount-($data->_material_amount+$data->_expense_amount) !!}">
                              </td>
                            </tr>

                            <tr class="display_none">
                              <td style="width: 20%;border:0px;"><label for="_sub_total">{{__('label.expected_income')}}</label></td>
                              <td style="width: 70%;border:0px;">
                                <input type="text" name="_sub_total" class="form-control width_200_px" id="_purchase_sub_total" readonly value="0">
                              </td>
                            </tr>
                            <tr class="display_none">
                              <td style="width: 20%;border:0px;"><label for="_total">NET Total </label></td>
                              <td style="width: 70%;border:0px;">
                          <input type="text" name="_total" class="form-control width_200_px" id="_purchase_total" readonly value="{{0}}">
                              </td>
                            </tr>
                             
                          </table>
                        </div>
                       
                       
                        <div class="col-xs-12 col-sm-12 col-md-12 bottom_save_section text-middle">
                            <button type="submit" class="btn btn-success  ml-5"><i class="fa fa-credit-card mr-2" aria-hidden="true"></i> Save</button>
                           
                        </div>
                        <br><br>
                    
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

@endsection
@section('script')

<script type="text/javascript">

  var default_date_formate = `{{default_date_formate()}}`;
  var _after_print = $(document).find("._after_print").val();
  var _master_id = $(document).find("._master_id").val();
  var _item_row_count =1;
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


var duplicate_barcode_status=0;
  

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
                          var   _manufacture_company = data[i]?. _manufacture_company;
                          var _balance = data[i]?._balance
                         search_html += `<tr class="search_row_item" >
                                        <td>${data[i].id}
                                        <input type="hidden" name="_id_item" class="_id_item" value="${data[i].id}">
                                        </td><td>${data[i]._name}
                                        <input type="hidden" name="_name_item" class="_name_item" value="${data[i]._name}">
                                  <input type="hidden" name="_item_barcode" class="_item_barcode" value="${data[i]._barcode}">
                                  <input type="hidden" name="_item_rate" class="_item_rate" value="${data[i]._pur_rate}">
                                  <input type="hidden" name="_unique_barcode" class="_unique_barcode" value="${data[i]._unique_barcode}">
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
        search_html +=`<div class="card"><table style="width: 400px;"> 
        <thead><th colspan="4"><button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target="#exampleModalLong_item" title="Create New Item (Inventory) ">
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
  var _unique_barcode = parseFloat($(this).find('._unique_barcode').val());

  var _main_unit_id = $(this).children('td').find('._main_unit_id').val();
  var _main_unit_val = $(this).children('td').find('._main_unit_text').val();

 
  
  if(isNaN(_item_vat)){ _item_vat=0 }
  _vat_amount = ((_item_rate*_item_vat)/100);

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
  
 
  $(this).parent().parent().parent().parent().parent().parent().find('._qty').val(1);

  $(this).parent().parent().parent().parent().parent().parent().find('._value').val(_item_rate);

 
  $(this).parent().parent().parent().parent().parent().parent().find('._base_unit_id').val(_main_unit_id);
  $(this).parent().parent().parent().parent().parent().parent().find('._main_unit_val').val(_main_unit_val);

  _purchase_total_calculation();
  $(document).find('.search_box_item').hide();
  $(document).find('.search_box_item').removeClass('search_box_show').hide();
  
})


$(document).on('keyup','._dr_amount',function(){
  all_total_calculations();
})
$(document).on('keyup','._cr_amount',function(){
  all_total_calculations();
})
$(document).on('keyup','._dr_amount',function(){
  all_total_calculations();
})

function all_total_calculations(){
  var _total_cr_amount = $(document).find("._total_cr_amount").val();
    _total_cr_amount = parseFloat(_total_cr_amount);
    if(isNaN(_total_cr_amount)){_total_cr_amount=0}
  var _total_value_amount = $(document).find("._total_value_amount").val();
    _total_value_amount = parseFloat(_total_value_amount);
    if(isNaN(_total_value_amount)){_total_value_amount=0}
  var _total_dr_amount = $(document).find("._total_dr_amount").val();
    _total_dr_amount = parseFloat(_total_dr_amount);
    if(isNaN(_total_dr_amount)){_total_dr_amount=0}

  var expected_profit = _total_cr_amount-(_total_value_amount+_total_dr_amount);

  $(document).find("#expected_income").val(_total_cr_amount);
  $(document).find("#expected_material_expense").val(_total_value_amount);
  $(document).find("#expected_other_expenses").val(_total_dr_amount);
  $(document).find("#expected_profit").val(expected_profit);

}


$(document).on('click',function(){
    var searach_show= $(document).find('.search_box_item').hasClass('search_box_show');
    var search_box_main_ledger= $(document).find('.search_box_main_ledger').hasClass('search_box_show');
    var search_box_purchase_order= $(document).find('.search_box_purchase_order').hasClass('search_box_show');
    if(searach_show ==true){
      $(document).find('.search_box_item').removeClass('search_box_show').hide();
    }

    if(search_box_main_ledger ==true){
      $(document).find('.search_box_main_ledger').removeClass('search_box_show').hide();
    }

    if(search_box_purchase_order ==true){
      $(document).find('.search_box_purchase_order').removeClass('search_box_show').hide();
    }

    all_total_calculations()
})

$(document).on('keyup','._common_keyup',function(){
  var _vat_amount =0;
  var _qty = parseFloat($(this).closest('tr').find('._qty').val());
  var _rate =parseFloat( $(this).closest('tr').find('._rate').val());

   if(isNaN(_qty)){ _qty   = 0 }
   if(isNaN(_rate)){ _rate =0 }

  $(this).closest('tr').find('._value').val((_qty*_rate));
    _purchase_total_calculation();
})















 function _purchase_total_calculation(){
  console.log('calculation here')
    var _total_qty = 0;
    var _total__value = 0;
    var _total__vat =0;
    var _total_discount  = 0;
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
    
      $(document).find("._total_qty_amount").val(_total_qty);
      $(document).find("._total_value_amount").val(_total__value);

      var _total = _math_round((parseFloat(_total__value)+parseFloat(_total__vat))-parseFloat(_total_discount));
      $(document).find("#_purchase_total").val(_total);
  }


 var single_row =  `<tr class="_voucher_row">
                      <td><a  href="" class="btn btn-sm btn-default _voucher_row_remove" ><i class="fa fa-trash"></i></a></td>
                      <td><input type="text" name="_search_ledger_id[]" class="form-control _search_ledger_id width_280_px" placeholder="Ledger"  >
                      <input type="hidden" name="_ledger_id[]" class="form-control _ledger_id" >
                      <div class="search_box">
                      </div>
                      </td>
                      
                            <td><input type="text" name="_short_narr[]" class="form-control width_250_px" placeholder="Short Narr"></td>
                            
                            <td>
                              <input type="number" step="any" name="_cr_amount[]" class="form-control  _cr_amount" placeholder="Cr. Amount" value="{{old('_cr_amount',0)}}">
                              </td>
                            </tr>`;

  function voucher_row_add(event) {
      event.preventDefault();
      $(document).find("#area__voucher_details").append(single_row);
  }

  var expense_single_row = `<tr class="_voucher_row">
                                              <td>
                                                <a  href="#none" class="btn btn-sm btn-default _voucher_row_remove" ><i class="fa fa-trash"></i></a>
                                                <input type="hidden" name="_exp_row_id[]" value="0">
                                              </td>
                                              <td>
                                                <input type="text" name="_search_ledger_id[]" class="form-control _search_ledger_id width_280_px" placeholder="Ledger">
                                                <input type="hidden" name="_exp_ledger_id[]" class="form-control _ledger_id" >
                                                <div class="search_box"> </div>
                                              </td>
                                              <td>
                                                <select class="form-control" name='_ledger_type[]'>
                                                  <option value="expense">Expenses</option>
                                                  <option value="deduction">Deduction</option>
                                                </select>
                                              </td>
                                              <td>
                                                <input type="text" name="_exp_short_narr[]" class="form-control width_250_px _short_narr" placeholder="Short Narr">
                                              </td>
                                              <td>
                                                <input type="number" step="any" name="_exp_dr_amount[]" class="form-control  _dr_amount" placeholder="Dr. Amount" value="{{old('_exp_dr_amount',0)}}">
                                              </td>
                                              
                                            </tr>`

  function voucher_row_add_expense(event) {
      event.preventDefault();
      $(document).find("#area__voucher_details_expenses").append(expense_single_row);
  }


function purchase_row_add(event){
   event.preventDefault();
      

       _item_row_count= $(document).find("._item_row_count").val();
      $(document).find("._item_row_count").val((parseFloat(_item_row_count)+1));
     var  _item_row_count = (parseFloat(_item_row_count)+1);
     $(document).find("#area__purchase_details").append(`<tr class="_purchase_row">
                                              <td>
                                                <a  href="#none" class="btn btn-default _purchase_row_remove" ><i class="fa fa-trash"></i></a>
                                              </td>
                                              <td>
                                                <input type="text" name="_search_item_id[]" class="form-control _search_item_id width_280_px" placeholder="Item">
                                                <input type="hidden" name="_item_id[]" class="form-control _item_id width_200_px" >
                                                <div class="search_box_item">
                                                  
                                                </div>
                                              </td>
                                              <td >
                                                <select class="form-control _transection_unit" name="_transection_unit[]">
                                                </select>
                                              </td>
                                              <td>
                                                <input type="number" name="_qty[]" class="form-control _qty _common_keyup" >
                                              </td>
                                              <td>
                                                <input type="number" name="_rate[]" class="form-control _rate _common_keyup" >
                                                <input type="hidden" name="_base_rate[]" class="form-control _base_rate _common_keyup" >
                                              </td>
                                             
                                              <td>
                                                <input type="number" name="_value[]" class="form-control _value " readonly >
                                              </td>
                                            </tr>`);
     
      

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

 var _purchase_row_single_new =``;

 
 






 

$(document).find(".datetimepicker-input").val(date__today())

          function date__today(){
              var d = new Date();
            var yyyy = d.getFullYear().toString();
            var mm = (d.getMonth()+1).toString(); // getMonth() is zero-based
            var dd  = d.getDate().toString();
            if(default_date_formate=='DD-MM-YYYY'){
              return (dd[1]?dd:"0"+dd[0]) +"-"+ (mm[1]?mm:"0"+mm[0])+"-"+ yyyy ;
            }
            if(default_date_formate=='MM-DD-YYYY'){
              return (mm[1]?mm:"0"+mm[0])+"-" + (dd[1]?dd:"0"+dd[0]) +"-"+  yyyy ;
            }
            

            
          }

</script>

@endsection