@extends('backend.layouts.app')
@section('title',$page_name)

@section('content')
<div class="content-header">
      <div class="container-fluid">

        <div class="col-sm-12" style="display: flex;">
             <a class="m-0 _page_name" href="{{ route('rlp-chain.index') }}"> {!! $page_name ?? '' !!} </a>
            
          </div>

        
      </div><!-- /.container-fluid -->
    </div>
    <div class="message-area">
    @if (count($errors) > 0)
           <div class="alert alert-danger">
                <strong>Whoops!</strong> There were some problems with your input.<br><br>
                <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
                </ul>
            </div>
        @endif
        @if ($message = Session::get('success'))
    <div class="alert alert-success">
      <p>{{ $message }}</p>
    </div>
    @endif
    </div>
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-12">
            <div class="card">
              
              <div class="card-body">
 {!! Form::open(array('route' => 'rlp-chain.store','method'=>'POST')) !!}                    @csrf
                    <div class="row">
                 

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
          <option value="{{$cost_center->id}}" @if(isset($data->id)) @if($data->id == $cost_center->id) selected @endif   @endif>{{ $cost_center->id ?? '' }} - {{ $cost_center->_name ?? '' }}</option>
          @empty
          @endforelse
        </select>
    </div>
</div>
<div class="col-xs-12 col-sm-12 col-md-2 ">
    <div class="form-group ">
        <label>{{__('label.chain_type')}}:<span class="_required">*</span></label>
       <select class="form-control chain_type" name="chain_type" required >
          
          @forelse(access_chain_types() as $key=> $val )
          <option value="{{$key}}" > {{ $val }}</option>
          @empty
          @endforelse
        </select>
    </div>
</div>

<div class="col-xs-12 col-sm-12 col-md-12 ">
    <div class="form-group ">
        <label>{{__('label.chain_name')}}:<span class="_required">*</span></label>
      <input type="text" name="chain_name" class="form-control" required value="{!! old('chain_name') !!}" placeholder="{!! __('label.chain_name') !!}" />
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
                                            <th class="text-left" >User</th>
                                            <th class="text-left" >Order</th>
                                            
                                          </thead>
                                          <tbody class="area__user_chain" id="area__user_chain">
                            @php

                            $chain_datas= $data->chain ?? [];
                            @endphp
                        @forelse($chain_datas as $key=>$val)
                        <tr class="_purchase_row">
                          <td>
                            <a  href="#none" class="btn btn-default _purchase_row_remove" ><i class="fa fa-trash"></i></a>
                            <input type="hidden" name="_row_id[]" value="{{$val->id}}">
                          </td>
                          <td>
                              <select class="form-control select2 employee_change" name="user_id[]">
                                @forelse($_list_data as $uval)
                                <option value="">Select Employee</option>
                                <option attr_id="{!! $uval->id  !!}" value="{!! $uval->_code  !!}" @if($val->erp_user_id==$uval->_code) selected @endif >{!! $uval->_code ?? '' !!}-{!! $uval->_name ?? '' !!}</option>
                                @empty
                                @endforelse
                              </select>
                               <input type="hidden" class="user_auto_id" name="user_row_id[]" value="0"  />
                          </td>
                          <td>
                              <input type="text" name="ack_order[]" class="form-control" value="{{$val->ack_order}}">
                          </td>
                         
                        </tr>
                                @empty
                                @endforelse
                                          </tbody>
                                          <tfoot>
                                            <tr>
                                              <td>
                                                <a href="#none"  class="btn btn-default btn-sm" onclick="add_new_user(event)"><i class="fa fa-plus"></i></a>
                                              </td>
                                              <td></td>
                                              <td></td>
                                              
                                              
                                              
                                            </tr>
                                          </tfoot>
                                      </table>
                                </div>
                            </div>
                          </div>
                        </div>
                        
                        
                        
                       
                        <div class="col-xs-12 col-sm-12 col-md-12 bottom_save_section text-middle">
                            <button type="submit" class="btn btn-success  ml-5"><i class="fa fa-credit-card mr-2" aria-hidden="true"></i> Save</button>
                           
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
 $(document).on('click','._purchase_row_remove',function(event){
      event.preventDefault();
      $(this).parent().parent('tr').remove();
      
  })

   var single_row_user= `<tr class="_purchase_row">
              <td>
                <a  href="#none" class="btn btn-default _purchase_row_remove" ><i class="fa fa-trash"></i></a>
                <input type="hidden" name="_row_id[]" value="0">
              </td>
              <td>
                  <select class="form-control select2 employee_change" name="user_id[]">
                  <option value="">Select Employee</option>
                    @forelse($_list_data as $uval)
<option
data-id="{!! $uval->id  !!}"
 value="{!! $uval->_code  !!}">{!! $uval->_code ?? '' !!}-{!! $uval->_name ?? '' !!}</option>
                    @empty
                    @endforelse
                  </select>
                  <input type="hidden" class="user_auto_id" name="user_row_id[]" value="0"  />
              </td>
              <td>
                  <input type="text" name="ack_order[]" class="form-control">
              </td>
            </tr>`;

            function add_new_user(event){
                $(document).find("#area__user_chain").append(single_row_user);
                $(document).find('.select2').select2();
            }

            $(document).on('change','.employee_change',function(){
              var user_row_id = $(this).find(":selected").data("id");
              $(this).closest('td').find('.user_auto_id').val(user_row_id);
            })
</script>

@endsection