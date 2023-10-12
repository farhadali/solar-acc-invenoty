@extends('backend.layouts.app')
@section('title',$page_name)
@section('css')
<link rel="stylesheet" href="{{asset('backend/new_style.css')}}">
@endsection
@section('content')
<div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class=" col-sm-6 ">
            <h1 class="m-0 _page_name"><a  href="{{ route('voucher.index') }}"> {!! $page_name ?? '' !!} </a>  
              @include('backend.message.voucher-header')
              </h1>

          </div><!-- /.col -->
          <div class=" col-sm-6 ">
            <ol class="breadcrumb float-sm-right">
                @can('account-ledger-create')
             <li class="breadcrumb-item active">
                 <button type="button" class="btn btn-sm btn-default" data-toggle="modal" data-target="#exampleModalLong" title="Create Ledger"> New Ledger</button>
               </li>
               @endcan
              <li class="breadcrumb-item active">
                 <a class="btn btn-sm btn-default" title="List" href="{{ route('inter-project-voucher.index') }}">Inner Project Voucher List</a>
               </li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>

    <div class="message-area">
     @include('backend.message.message')
    </div>
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-12">
            <div class="card">
             
              <div class="card-body">
                {!! Form::open(array('route' => 'inter-project-voucher.store','method'=>'POST','class'=>'voucher-form')) !!}
                    <div class="row">

                       <div class="col-xs-12 col-sm-12 col-md-2">
                        <input type="hidden" name="_form_name" value="voucher_masters">
                            <div class="form-group">
                                <label>Date:</label>
                                  <div class="input-group date" id="reservationdate" data-target-input="nearest">
                                      <input type="text" name="_date" class="form-control datetimepicker-input" data-target="#reservationdate"/>
                                      <div class="input-group-append" data-target="#reservationdate" data-toggle="datetimepicker">
                                          <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                      </div>
                                  </div>
                              </div>
                        </div>

                       <div class="col-xs-12 col-sm-12 col-md-2">
                            <div class="form-group">
                                <label>Voucher Type: <span class="_required">*</span></label>
                               <select class="form-control _voucher_type" name="_voucher_type" required="true">
                                  <option value="">--Voucher Type--</option>
                                  @forelse($voucher_types as $voucher_type )
                                  <option value="{{$voucher_type->_code}}" @if(isset($request->_voucher_type)) @if($request->_voucher_type == $voucher_type->_code) selected @endif   @endif>
                                    {{ $voucher_type->_name ?? '' }}</option>
                                  @empty
                                  @endforelse
                                </select>
                            </div>
                        </div>
@php
$users = \Auth::user();
$permited_organizations = permited_organization(explode(',',$users->organization_ids));
@endphp 


<div class="col-xs-12 col-sm-12 col-md-2 @if(sizeof($permited_organizations)==1) display_none @endif   ">
 <div class="form-group ">
     <label>{!! __('label.organization') !!}:<span class="_required">*</span></label>
    <select class="form-control _master_organization_id" name="organization_id" required >
       @forelse($permited_organizations as $val )
       <option value="{{$val->id}}" @if(isset($request->organization_id)) @if($request->organization_id == $val->id) selected @endif   @endif>{{ $val->id ?? '' }} - {{ $val->_name ?? '' }}</option>
       @empty
       @endforelse
     </select>
 </div>
</div>
                        <div class="col-xs-12 col-sm-12 col-md-2 @if(sizeof($permited_branch)==1) display_none @endif">
                            <div class="form-group ">
                                <label>Branch:<span class="_required">*</span></label>
                               <select class="form-control" name="_branch_id" required >
                                  
                                  @forelse($permited_branch as $branch )
                                  <option value="{{$branch->id}}" @if(isset($request->_branch_id)) @if($request->_branch_id == $branch->id) selected @endif   @endif>{{ $branch->id ?? '' }} - {{ $branch->_name ?? '' }}</option>
                                  @empty
                                  @endforelse
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-2 @if(sizeof($permited_costcenters)==1) display_none @endif">
                            <div class="form-group ">
                                <label>Cost Center:<span class="_required">*</span></label>
                               <select class="form-control" name="_cost_center_id" required >
                                   @forelse($permited_costcenters as $costcenter )
                                                  <option value="{{$costcenter->id}}" @if(isset($request->_cost_center_id)) @if($request->_cost_center_id == $costcenter->id) selected @endif   @endif> {{ $costcenter->_name ?? '' }}</option>
                                                  @empty
                                                  @endforelse
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-6 ">
                            <div class="form-group">
                              <label class="mr-2" for="_transection_ref">Reference:</label>
                              <input type="text" id="_transection_ref" name="_transection_ref" class="form-control _transection_ref" value="{{old('_transection_ref')}}" placeholder="Reference" >
                                
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
                                          <thead>
                                            <th>&nbsp;</th>
                                            <th class="dr_account_title">DR. Ledger</th>
                                            <th class="dr_particular_title">DR. Particular</th>
                                            <th class="dr_organization_title">DR. {{__('label.organization')}}</th>
                                            <th class="dr_branch_title">DR. Branch</th>
                                            <th class="dr_cost_center_title">DR. Cost Center</th>
                                            <th class="cr_account_title">CR. Ledger</th>
                                            <th class="cr_particular_title">CR. Particular</th>
                                            <th class="cr_organization_title">CR. {{__('label.organization')}}</th>
                                            <th class="cr_branch_title">CR. Branch</th>
                                            <th class="cr_cost_center_title">CR. Cost Center</th>
                                            <th>Amount</th>
                                          </thead>
                                          <tbody class="area__voucher_details" id="area__voucher_details">
                                            <tr class="_voucher_row">
                                              <td>
                                                <a  href="#none" class="btn btn-default easy_voucher_row_remove" ><i class="fa fa-trash"></i></a>
                                              </td>
                                              <td>
                                                <input type="hidden" name="_dr_row_id[]" value="0">
                                                <input type="text" name="_dr_search_ledger_id[]" class="form-control _dr_search_ledger_id width_150_px" >

                                                <input type="hidden" name="_dr_ledger_id[]" class="form-control _dr_ledger_id" >
                                                <div class="_dr_search_box">
                                                  
                                                </div>
                                              </td>
                                              <td>
                                                <input type="text" name="_dr_short_narr[]" class="form-control width_150_px _dr_short_narr" >
                                              </td>
                                              <td>
                                                <select class="form-control " name="_dr_organization_id_detail[]" required >
                                                   @forelse($permited_organizations as $val )
                                                   <option value="{{$val->id}}" >{{ $val->id ?? '' }} - {{ $val->_name ?? '' }}</option>
                                                   @empty
                                                   @endforelse
                                                 </select>
                                              </td>
                                              <td>
                                                <select class="form-control width_150_px _dr_branch_id_detail" name="_dr_branch_id_detail[]"  required>
                                                  @forelse($permited_branch as $branch )
                                                  <option value="{{$branch->id}}" >{{ $branch->_name ?? '' }}</option>
                                                  @empty
                                                  @endforelse
                                                </select>
                                              </td>
                                             
                                                <td>
                                                 <select class="form-control width_150_px _dr_cost_center" name="_dr_cost_center[]" required >
                                            
                                                  @forelse($permited_costcenters as $costcenter )
                                                  <option value="{{$costcenter->id}}" > {{ $costcenter->_name ?? '' }}</option>
                                                  @empty
                                                  @endforelse
                                                </select>
                                              </td>


                                              <td>
                                                 <input type="hidden" name="_cr_row_id[]" value="0">

                                                <input type="text" name="_cr_search_ledger_id[]" class="form-control _cr_search_ledger_id width_150_px" >
                                                <input type="hidden" name="_cr_ledger_id[]" class="form-control _cr_ledger_id" >
                                                <div class="_cr_search_box">
                                                  
                                                </div>
                                              </td>
                                              <td>
                                                <input type="text" name="_cr_short_narr[]" class="form-control width_150_px _cr_short_narr" >
                                              </td>
                                              <td>
                                                <select class="form-control " name="_cr_organization_id_detail[]" required >
                                                   @forelse($permited_organizations as $val )
                                                   <option value="{{$val->id}}" >{{ $val->id ?? '' }} - {{ $val->_name ?? '' }}</option>
                                                   @empty
                                                   @endforelse
                                                 </select>
                                              </td>
                                              <td>
                                                <select class="form-control width_150_px _cr_branch_id_detail" name="_cr_branch_id_detail[]"  required>
                                                  @forelse($permited_branch as $branch )
                                                  <option value="{{$branch->id}}" >{{ $branch->_name ?? '' }}</option>
                                                  @empty
                                                  @endforelse
                                                </select>
                                              </td>
                                             
                                                <td>
                                                 <select class="form-control width_150_px _cr_cost_center" name="_cr_cost_center[]" required >
                                            
                                                  @forelse($permited_costcenters as $costcenter )
                                                  <option value="{{$costcenter->id}}" > {{ $costcenter->_name ?? '' }}</option>
                                                  @empty
                                                  @endforelse
                                                </select>
                                              </td>
                                              
                                              
                                              <td>
                                                <input type="number" name="_amount[]" class="form-control  _amount"  value="{{old('_amount',0)}}">

                                              </td>
                                             
                                            </tr>
                                          </tbody>
                                          <tfoot>
                                            <tr>
                                              <td>
                                                <a href="#none"  class="btn btn-info" onclick="easy_voucherAddRow(event)"><i class="fa fa-plus"></i></a>
                                              </td>
                                              <td colspan="10" class="text-right"><b>Total</b></td>
                                              <td>
                                                <input type="number" step="any" min="0" name="_total_amount" class="form-control _total_amount" value="0" readonly required>
                                              </td>
                                              
                                            </tr>
                                          </tfoot>
                                      </table>
                                </div>
                            </div>
                          </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 mb-10">
                            <div class="form-group">
                               
                                
                                <div class="row">
                                  <div class="col-md-1">
                                     <label class="mr-2" for="_note">Note:<span class="_required">*</span></label>
                                  </div>
                                  <div class="col-md-11">
                                    @if ($_print = Session::get('_print_value'))
                                     <input type="hidden" name="_after_print" value="{{$_print}}" class="_after_print" >
                                    @else
                                    <input type="hidden" name="_after_print" value="0" class="_after_print" >
                                    @endif
                                    @if ($_master_id = Session::get('_master_id'))
                                     <input type="hidden" name="_master_id" value="{{url('voucher/print')}}/{{$_master_id}}" class="_master_id">
                                    
                                    @endif
                                   
                                       <input type="hidden" name="_print" value="0" class="_save_and_print_value">

                                    <input type="text" id="_note"  name="_note" class="form-control _note" value="{{old('_note')}}" placeholder="Note" required >
                                  </div>
                                </div>
                                 @include('backend.message.send_sms')
                            </div>
                        </div>
                        
                        
                        <div class="col-xs-12 col-sm-12 col-md-12 bottom_save_section text-middle">
                            <button type="submit" class="btn btn-success submit-button ml-5"><i class="fa fa-credit-card mr-2" aria-hidden="true"></i> Save</button>
                            <button type="submit" class="btn btn-warning submit-button _save_and_print"><i class="fa fa-print mr-2" aria-hidden="true"></i> Save & Print</button>
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



@endsection

@section('script')

<script type="text/javascript">
  var default_date_formate = `{{default_date_formate()}}`;
  var _after_print = $(document).find("._after_print").val();
  var _master_id = $(document).find("._master_id").val();
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


  $(document).on("change","._voucher_type",function(){
    var voucher_type = $(this).val();
    title_change_voucher(voucher_type)
  })

  function title_change_voucher(voucher_type){
      if(voucher_type=="BP"){
          $(".dr_account_title").text('Receiver Ledger');
          $(".dr_particular_title").text('Receiver Ledger Note');
          $(".cr_account_title").text('Bank Ledger');
          $(".cr_particular_title").text('Bank Ledger Note');
      }else if(voucher_type=="BR"){
          $(".dr_account_title").text('Bank Ledger');
          $(".dr_particular_title").text('Bank Ledger Note');
          $(".cr_account_title").text('Payee Ledger');
          $(".cr_particular_title").text('Payee Ledger Note');
      }else if(voucher_type=="CR"){
          $(".dr_account_title").text('Cash Ledger');
          $(".dr_particular_title").text('Cash Ledger Note');
          $(".cr_account_title").text('Payee Ledger');
          $(".cr_particular_title").text('Payee Ledger Note');
      }else if(voucher_type=="CP"){
          $(".dr_account_title").text('Receiver Ledger');
          $(".dr_particular_title").text('Receiver Ledger Note');
          $(".cr_account_title").text('Cash Ledger');
          $(".cr_particular_title").text('Cash Ledger Note');
      }
      else{
          $(".dr_account_title").text('DR. Ledger');
          $(".dr_particular_title").text('DR. Particular');
          $(".cr_account_title").text('CR. Ledger');
          $(".cr_particular_title").text('CR. Particular');
      }
  }


 var single_row =  `<tr class="_voucher_row">
                        <td>
                          <a  href="#none" class="btn btn-default easy_voucher_row_remove" ><i class="fa fa-trash"></i></a>
                        </td>
                        <td>
                          <input type="hidden" name="_dr_row_id[]" value="0">
                          <input type="text" name="_dr_search_ledger_id[]" class="form-control _dr_search_ledger_id width_150_px" >

                          <input type="hidden" name="_dr_ledger_id[]" class="form-control _dr_ledger_id" >
                          <div class="_dr_search_box">
                            
                          </div>
                        </td>
                        <td>
                          <input type="text" name="_dr_short_narr[]" class="form-control width_150_px _dr_short_narr" >
                        </td>
                        <td>
                          <select class="form-control " name="_dr_organization_id_detail[]" required >
                             @forelse($permited_organizations as $val )
                             <option value="{{$val->id}}" >{{ $val->id ?? '' }} - {{ $val->_name ?? '' }}</option>
                             @empty
                             @endforelse
                           </select>
                        </td>
                        <td>
                          <select class="form-control width_150_px _dr_branch_id_detail" name="_dr_branch_id_detail[]"  required>
                            @forelse($permited_branch as $branch )
                            <option value="{{$branch->id}}" @if(isset($request->_dr_branch_id_detail)) @if($request->_dr_branch_id_detail == $branch->id) selected @endif   @endif>{{ $branch->_name ?? '' }}</option>
                            @empty
                            @endforelse
                          </select>
                        </td>
                       
                          <td>
                           <select class="form-control width_150_px _dr_cost_center" name="_dr_cost_center[]" required >
                      
                            @forelse($permited_costcenters as $costcenter )
                            <option value="{{$costcenter->id}}" @if(isset($request->_dr_cost_center)) @if($request->_dr_cost_center == $costcenter->id) selected @endif   @endif> {{ $costcenter->_name ?? '' }}</option>
                            @empty
                            @endforelse
                          </select>
                        </td>


                        <td>
                           <input type="hidden" name="_cr_row_id[]" value="0">

                          <input type="text" name="_cr_search_ledger_id[]" class="form-control _cr_search_ledger_id width_150_px" >
                          <input type="hidden" name="_cr_ledger_id[]" class="form-control _cr_ledger_id" >
                          <div class="_cr_search_box">
                            
                          </div>
                        </td>
                        <td>
                          <input type="text" name="_cr_short_narr[]" class="form-control width_150_px _cr_short_narr" >
                        </td>
                         <td>
                          <select class="form-control " name="_cr_organization_id_detail[]" required >
                             @forelse($permited_organizations as $val )
                             <option value="{{$val->id}}" >{{ $val->id ?? '' }} - {{ $val->_name ?? '' }}</option>
                             @empty
                             @endforelse
                           </select>
                        </td>
                        <td>
                          <select class="form-control width_150_px _cr_branch_id_detail" name="_cr_branch_id_detail[]"  required>
                            @forelse($permited_branch as $branch )
                            <option value="{{$branch->id}}" >{{ $branch->_name ?? '' }}</option>
                            @empty
                            @endforelse
                          </select>
                        </td>
                       
                          <td>
                           <select class="form-control width_150_px _cr_cost_center" name="_cr_cost_center[]" required >
                      
                            @forelse($permited_costcenters as $costcenter )
                            <option value="{{$costcenter->id}}" > {{ $costcenter->_name ?? '' }}</option>
                            @empty
                            @endforelse
                          </select>
                        </td>
                        
                        
                        <td>
                          <input type="number" name="_amount[]" class="form-control  _amount"  value="{{old('_amount',0)}}">

                        </td>
                       
                      </tr>`;

  function easy_voucherAddRow(event) {
      event.preventDefault();
      $("#area__voucher_details").append(single_row);
  }

  

  $(document).on('click','.easy_voucher_row_remove',function(event){
      event.preventDefault();
      var ledger_id = $(this).parent().parent('tr').find('._ledger_id').val();
      if(ledger_id ==""){
          $(this).parent().parent('tr').remove();
      }else{
        if(confirm('Are you sure your want to delete?')){
          $(this).parent().parent('tr').remove();
        } 
      }
      _easy_voucher_calculation();
  })

  function _easy_voucher_calculation(){
    var _total_amount = 0;
      $(document).find("._amount").each(function() {
          _total_amount +=parseFloat($(this).val());
      });
      
      $("._total_amount").val(Math.round(_total_amount));
  }


  $(document).on('keyup','._amount',function(){
    _easy_voucher_calculation();
  })




  $(document).on('change','._voucher_type',function(){
    $(document).find('._voucher_type').removeClass('required_border');
  })

  $(document).on('keyup','._note',function(){
    $(document).find('._note').removeClass('required_border');
  })

  $(document).on('click','._save_and_print',function(){
    $(document).find('._save_and_print_value').val(1);
  })


  $(document).on('click','.submit-button',function(event){
    event.preventDefault();
    var _total_amount = $(document).find("._total_amount").val();
    var _voucher_type = $(document).find('._voucher_type').val();
    var _note = $(document).find('._note').val();
    var _search_ledger_id = $(document).find('._search_ledger_id').val();


    var empty_ledger = [];
    $(document).find("._dr_ledger_id").each(function(){
        if($(this).val() ==""){
         
          $(document).find('._dr_search_ledger_id').focus().addClass('required_border');
          empty_ledger.push(1);
        }  
    })
    $(document).find("._cr_ledger_id").each(function(){
        if($(this).val() ==""){
         
          $(document).find('._cr_search_ledger_id').focus().addClass('required_border');
          empty_ledger.push(1);
        }  
    })

    if(empty_ledger.length > 0){
      return false;
    }


    if(_voucher_type ==""){
       $(document).find('._voucher_type').focus().addClass('required_border');
     
      return false;
    }else if(_note ==""){
       
       $(document).find('._note').focus().addClass('required_border');
      return false;
    }else{
      $(document).find('.voucher-form').submit();
    }
  })



$(document).on('keyup','._dr_search_ledger_id',delay(function(e){
    $(document).find('._dr_search_ledger_id').removeClass('required_border');
  var _gloabal_this = $(this);
  var _text_val = $(this).val().trim();
  var _head_no = $(this).attr('attr_account_head_no');
  if(isNaN(_head_no)){ _head_no=0 }
    console.log("_text_val "+_text_val)
    console.log("_head_no "+_head_no)
  var request = $.ajax({
      url: "{{url('ledger-search')}}",
      method: "GET",
      data: { _text_val,_head_no },
      dataType: "JSON"
    });
     
    request.done(function( result ) {
      var search_html =``;
      var data = result.data; 
      if(data.length > 0 ){
            search_html +=`<div class="card"><table style="width: 400px;"> <tbody>`;
                        for (var i = 0; i < data.length; i++) {
                         search_html += `<tr class="_dr_search_row" >
                                        <td>${data[i].id}
                                        <input type="hidden" name="_id_ledger" class="_id_ledger" value="${data[i].id}">
                                        </td><td>${data[i]._name}
                                        <input type="hidden" name="_name_leder" class="_name_leder" value="${data[i]._name}">
                                        <input type="hidden" name="_s_l_address" class="_s_l_address" value="${data[i]._address}">
                                        <input type="hidden" name="_s_l_phone" class="_s_l_phone" value="${data[i]?._phone}">
                                        <input type="hidden" name="_s_l_balance" class="_s_l_balance" value="${data[i]?._balance}">
                                        </td>
                                        <td>${data[i]?._phone}</td>
                                        <td>${data[i]?._balance}</td>
                                        </tr>`;
                        }                         
            search_html += ` </tbody> </table></div>`;
      }else{
        search_html +=`<div class="card"><table style="width: 300px;"> 
        <thead><th colspan="3"><button type="button" class="btn btn-sm btn-default" data-toggle="modal" data-target="#exampleModalLong" title="Create Ledger"> New Ledger</button></th></thead><tbody></tbody></table></div>`;
      }     
      _gloabal_this.parent('td').find('._dr_search_box').html(search_html);
      _gloabal_this.parent('td').find('._dr_search_box').addClass('search_box_show').show();
      
    });
     
    request.fail(function( jqXHR, textStatus ) {
      alert( "Request failed: " + textStatus );
    });

  

}, 500));

$(document).on('click','._dr_search_row',function(){
  var _id = $(this).children('td').find('._id_ledger').val();
  var _name = $(this).find('._name_leder').val();
  var _s_l_balance = $(this).find('._s_l_balance').val();
  console.log(_s_l_balance)
  $(this).parent().parent().parent().parent().parent().parent().find('._dr_ledger_id').val(_id);
  var _id_name = `${_name},`+_s_l_balance;
  $(this).parent().parent().parent().parent().parent().parent().find('._dr_search_ledger_id').val(_id_name);


  $('._dr_search_box').hide();
  $('._dr_search_box').removeClass('search_box_show').hide();
})
 



$(document).on('keyup','._cr_search_ledger_id',delay(function(e){
    $(document).find('._cr_search_ledger_id').removeClass('required_border');
  var _gloabal_this = $(this);
  var _text_val = $(this).val().trim();
  var _head_no = $(this).attr('attr_account_head_no');
  if(isNaN(_head_no)){ _head_no=0 }
    console.log("_text_val "+_text_val)
    console.log("_head_no "+_head_no)
  var request = $.ajax({
      url: "{{url('ledger-search')}}",
      method: "GET",
      data: { _text_val,_head_no },
      dataType: "JSON"
    });
     
    request.done(function( result ) {
      var search_html =``;
      var data = result.data; 
      if(data.length > 0 ){
            search_html +=`<div class="card"><table style="width: 400px;"> <tbody>`;
                        for (var i = 0; i < data.length; i++) {
                         search_html += `<tr class="_cr_search_row" >
                                        <td>${data[i].id}
                                        <input type="hidden" name="_id_ledger" class="_id_ledger" value="${data[i].id}">
                                        </td><td>${data[i]._name}
                                        <input type="hidden" name="_name_leder" class="_name_leder" value="${data[i]._name}">
                                        <input type="hidden" name="_s_l_address" class="_s_l_address" value="${data[i]._address}">
                                        <input type="hidden" name="_s_l_phone" class="_s_l_phone" value="${data[i]?._phone}">
                                        <input type="hidden" name="_s_l_balance" class="_s_l_balance" value="${data[i]?._balance}">
                                        </td>
                                        <td>${data[i]?._phone}</td>
                                        <td>${data[i]?._balance}</td>
                                        </tr>`;
                        }                         
            search_html += ` </tbody> </table></div>`;
      }else{
        search_html +=`<div class="card"><table style="width: 300px;"> 
        <thead><th colspan="3"><button type="button" class="btn btn-sm btn-default" data-toggle="modal" data-target="#exampleModalLong" title="Create Ledger"> New Ledger</button></th></thead><tbody></tbody></table></div>`;
      }     
      _gloabal_this.parent('td').find('._cr_search_box').html(search_html);
      _gloabal_this.parent('td').find('._cr_search_box').addClass('search_box_show').show();
      
    });
     
    request.fail(function( jqXHR, textStatus ) {
      alert( "Request failed: " + textStatus );
    });

  

}, 500));

$(document).on('click','._cr_search_row',function(){
  var _id = $(this).children('td').find('._id_ledger').val();
  var _name = $(this).find('._name_leder').val();
  var _s_l_balance = $(this).find('._s_l_balance').val();
  console.log(_s_l_balance)
$(this).parent().parent().parent().parent().parent().parent().find('._cr_ledger_id').val(_id);
  var _id_name = `${_name},`+_s_l_balance;
  $(this).parent().parent().parent().parent().parent().parent().find('._cr_search_ledger_id').val(_id_name);


  $('._cr_search_box').hide();
  $('._cr_search_box').removeClass('search_box_show').hide();
})
 

$(".datetimepicker-input").val(date__today())

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

