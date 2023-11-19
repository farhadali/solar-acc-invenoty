@extends('backend.layouts.app')
@section('title',$page_name ?? '')
@section('content')
<div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <a class="m-0 _page_name" href="{{ route('item-information.index') }}">{!! $page_name ?? '' !!} </a>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
             @can('item-information-list')
              <li class="breadcrumb-item active">
                 <a class="btn btn-info" href="{{ route('item-information.index') }}"> <i class="fa fa-th-list" aria-hidden="true"></i></a>
               </li>
               @endcan
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-12">
            <div class="col-md-12">
<div class="card">
<div class="card-header p-2">
<ul class="nav nav-pills">
<li class="nav-item"><a class="nav-link active" href="#tab1" data-toggle="tab">{{__('Basic')}}</a></li>
<li class="nav-item"><a class="nav-link" href="#tab2" data-toggle="tab">{{__('Pricing Information')}}</a></li>
<li class="nav-item"><a class="nav-link" href="#tab3" data-toggle="tab">{{__('Other Information')}}</a></li>
<li class="nav-item display_none"><a class="nav-link" href="#tab4" data-toggle="tab">{{__('Opening Balance')}}</a></li>
</ul>
</div>
<div class="card-body">
  @include('backend.message.message')
                {!! Form::open(array('route' => 'item-information.store','method'=>'POST','enctype'=>'multipart/form-data')) !!}
<div class="tab-content">
    
<div class="tab-pane active" id="tab1">
    <div class="form-group row">
        <label class="col-sm-2 col-form-label">{{__('label._category_id')}}: <span class="_required">*</span></label>
         <div class="col-sm-6">
           <select  class="form-control _category_id " name="_category_id" required>
              <option value="">--Select Category--</option>
              @forelse($categories as $category )
              <option value="{{$category->id}}"  @if(old('_category_id') == $category->id) selected @endif  >{{ $category->_parents->_name ?? 'C' }}->{{ $category->_name ?? '' }}</option>
              @empty
              @endforelse
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label" for="_item">{{__('label._item')}}:<span class="_required">*</span></label>
         <div class="col-sm-6">
          <input type="text" id="_item" name="_item" class="form-control" value="{{old('_item')}}" placeholder="Item" required>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label" for="_unit">{{__('label._unit')}}:<span class="_required">*</span></label>
         <div class="col-sm-2">
            <select class="form-control _unit_id " id="_unit_id" name="_unit_id" required>
              <option value="" >--Units--</option>
              @foreach($units as $unit)
               <option value="{{$unit->id}}" @if(old('_unit_id')==$unit->id) selected @endif >{{$unit->_name ?? ''}}</option>
              @endforeach
            </select>
        </div>
    </div>
     
    <div class="form-group row">
        <label class="col-sm-2 col-form-label"  for="_code">Code:</label>
        <div class="col-sm-6">
            <input type="text" id="_code" name="_code" class="form-control" value="{{old('_code')}}" placeholder="Code" >
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label" for="_barcode">Model:</label>
        <div class="col-sm-6">
            <input type="text" id="_barcode" name="_barcode" class="form-control" value="{{old('_barcode')}}" placeholder="Model" >
        </div>
    </div>
    
    <div class="form-group row">
            <label class="col-sm-2 col-form-label" for="_manufacture_company">Manufacture Company:</label>
            <div class="col-sm-6">
                <input type="text" id="_manufacture_company" name="_manufacture_company" class="form-control _manufacture_company" value="{{old('_manufacture_company')}}" placeholder="Manufacture Company" >
                <div class="search_boxManufacCompany"></div>
            </div>
    </div>

    <div class="form-group row">
            <label class="col-sm-2 col-form-label"  for="_unique_barcode" class="_required">Use Unique Barcode ?:</label>
            <div class="col-sm-2 ">
                <select class="form-control" name="_unique_barcode" id="_unique_barcode">
                  <option value="0">NO</option>
                  <option value="1">Yes</option>
                </select>
            </div>
    </div>
     @can('restaurant-module') 
        <div class="form-group row">
            <label class="col-sm-2 col-form-label" for="_kitchen_item" class="_required" title="if Yes then this item will send to kitchen to cook/production for sales and store deduct as per item ingredient wise automaticaly">Kitchen/Production Item ?:</label>
            <div class="col-sm-2 ">
            <select class="form-control" name="_kitchen_item" id="_kitchen_item">
              <option value="0">No</option>
              <option value="1">Yes</option>
            </select>
        </div>
    </div>
    @endcan
     <div class="form-group row">
        <label class="col-sm-2 col-form-label" for="_status">Status:</label>
        <div class="col-sm-2">
        <select class="form-control" name="_status" id="_status">
          <option value="1">Active</option>
          <option value="0">In Active</option>
        </select>
    </div>
</div>

</div><!-- End fo Tab One -->

<div class="tab-pane" id="tab2"><!-- Starting Point Two -->
    
    <div class="form-group row">
        <label class="col-sm-2 col-form-label" for="_pur_rate">Purchase Rate:</label>
        <div class=" col-sm-6">
            <input type="number" step="any" min="0" id="_item_pur_rate" name="_pur_rate" class="form-control" value="{{old('_pur_rate',0)}}" placeholder="Purchase Rate" >
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label" for="_sale_rate">Sales Rate:</label>
         <div class=" col-sm-6">
        
            <input type="number" step="any" min="0" id="_item_sale_rate" name="_sale_rate" class="form-control" value="{{old('_sale_rate',0)}}" placeholder="Sales Rate" >
        </div>
    </div>

        <div class="form-group row">
            
            <label class="col-sm-2 col-form-label" for="_discount">Discount Rate:</label>
            <div class="col-sm-6">
            <input type="text" id="_discount" name="_discount" class="form-control" value="{{old('_discount')}}" placeholder="Discount Rate" >
        </div>
    </div>
    
        <div class="form-group row">
            
            <label class="col-sm-2 col-form-label" for="_vat">Vat Rate:</label>
            <div class="col-sm-6">
            <input type="text" id="_vat" name="_vat" class="form-control" value="{{old('_vat')}}" placeholder="Vat Rate" >
        </div>
    </div>
    
        <div class="form-group row">
            
            <label class="col-sm-2 col-form-label" for="_sd">{{__('label._sd')}}:</label>
            <div class="col-sm-6">
            <input type="text" id="_sd" name="_sd" class="form-control" value="{{old('_sd')}}" placeholder="{{__('label._sd')}}" >
        </div>
    </div>
    
        <div class="form-group row">
            
            <label class="col-sm-2 col-form-label" for="_cd">{{__('label._cd')}}:</label>
            <div class="col-sm-6">
            <input type="text" id="_cd" name="_cd" class="form-control" value="{{old('_cd')}}" placeholder="{{__('label._cd')}}" >
        </div>
    </div>
    
        <div class="form-group row">
            
            <label class="col-sm-2 col-form-label" for="_ait">{{__('label._ait')}}:</label>
            <div class="col-sm-6">
            <input type="text" id="_ait" name="_ait" class="form-control" value="{{old('_ait')}}" placeholder="{{__('label._ait')}}" >
        </div>
    </div>
    
        <div class="form-group row">
            
            <label class="col-sm-2 col-form-label" for="_rd">{{__('label._rd')}}:</label>
            <div class="col-sm-6">
            <input type="text" id="_rd" name="_rd" class="form-control" value="{{old('_rd')}}" placeholder="{{__('label._rd')}}" >
        </div>
    </div>
    
        <div class="form-group row">
            
            <label class="col-sm-2 col-form-label" for="_at">{{__('label._at')}}:</label>
            <div class="col-sm-6">
            <input type="text" id="_at" name="_at" class="form-control" value="{{old('_at')}}" placeholder="{{__('label._at')}}" >
        </div>
    </div>
    
        <div class="form-group row">
            
            <label class="col-sm-2 col-form-label" for="_tti">{{__('label._tti')}}:</label>
            <div class="col-sm-6">
            <input type="text" id="_tti" name="_tti" class="form-control" value="{{old('_tti')}}" placeholder="{{__('label._tti')}}" >
        </div>
    </div>
</div><!-- End of Second Tab -->

<div class="tab-pane" id="tab3"><!-- Starting point tab 3 -->


    <div class="form-group row">
         <label class="col-sm-2 col-form-label">Warranty: </label>
         <div class="col-sm-6">
           <select  class="form-control _warranty " name="_warranty" >
              <option value="">--Select Warranty--</option>
              @forelse($_warranties as $_warranty )
              <option value="{{$_warranty->id}}" @if(isset($request->_warranty)) @if($request->_warranty == $_warranty->id) selected @endif   @endif>{{ $_warranty->_name ?? '' }}</option>
              @empty
              @endforelse
            </select>
        </div>
    </div>

    <div class="form-group row">
        <label class="col-sm-2 col-form-label" for="_reorder">Reorder Level:</label>
        <div class="col-sm-6">
        <input type="text" id="_reorder" name="_reorder" class="form-control" value="{{old('_reorder')}}" placeholder="Reorder Level" >
    </div>
</div>

    <div class="form-group row">
        <label class="col-sm-2 col-form-label" for="_order_qty">Order Qty:</label>
        <div class="col-sm-6">
        <input type="text" id="_order_qty" name="_order_qty" class="form-control" value="{{old('_order_qty')}}" placeholder="Order Qty" >
    </div>
</div>

 

 
   

 
    <div class="form-group">
        <label class="col-sm-2 col-form-label">Image:</label>
        <div class="col-sm-6">
       <input type="file" accept="image/*" onchange="loadFile(event,1 )"  name="_image" class="form-control">
       <img id="output_1" class="banner_image_create" src="{{asset('/')}}{{$settings->logo ?? ''}}"  style="max-height:100px;max-width: 100px; " />
    </div>
</div>


</div><!-- End of Tab Three -->
<div class="tab-pane display_none" id="tab4">
<table class="table">
    <thead>
        <tr>
            <th>#</th>
            <th>{{__('label.organization_id')}}</th>
            <th>{{__('label._branch_id')}}</th>
            <th>{{__('label._cost_center_id')}}</th>
            <th>{{__('label._store_id')}}</th>
            <th>{{__('label._qty')}}</th>
            <th>{{__('label._cost_rate')}}</th>
            <th>{{__('label._sales_rate')}}</th>
            <th>{{__('label._amount')}}</th>
        </tr>
    </thead>
    <tbody class="opeing_body">
        <tr>
            <td>
            <a href="#none" class="btn btn-default _opening_row_remove"><i class="fa fa-trash"></i></a>
          </td>
            <td>
                <select class="form-control _master_branch_id" name="_branch_id" required >
                  @forelse($permited_organizations as $val )
                  <option value="{{$val->id}}" >{{ $val->id ?? '' }} - {{ $val->_name ?? '' }}</option>
                  @empty
                  @endforelse
                </select>
            </td>
            <td>
                <select class="form-control _master_branch_id" name="_branch_id" required >
                  @forelse($permited_branch as $branch )
                  <option value="{{$branch->id}}" @if(isset($request->_branch_id)) @if($request->_branch_id == $branch->id) selected @endif   @endif>{{ $branch->id ?? '' }} - {{ $branch->_name ?? '' }}</option>
                  @empty
                  @endforelse
                </select>
            </td>
            <td>
                <select class="form-control _cost_center_id" name="_cost_center_id" required >    
                  @forelse($permited_costcenters as $cost_center )
                  <option value="{{$cost_center->id}}" @if(isset($request->_cost_center_id)) @if($request->_cost_center_id == $cost_center->id) selected @endif   @endif>{{ $cost_center->id ?? '' }} - {{ $cost_center->_name ?? '' }}</option>
                  @empty
                  @endforelse
                </select>
            </td>
            <td>
                <select class="form-control  _store_id" name="_store_id">
                  @forelse($store_houses as $store)
                  <option value="{{$store->id}}">{{$store->_name ?? '' }}</option>
                  @empty
                  @endforelse
                </select>
            </td>
            
            <td>
                 <input type="number" step="any" min="0" id="_item_opening_qty" name="_opening_qty" class="form-control" value="{{old('_opening_qty',0)}}" placeholder="Opening QTY" >
            </td>
            <td>
                 <input type="number" step="any" min="0"  name="_cost_rate" class="form-control" value="{{old('_cost_rate',0)}}" placeholder="Opening Cost Rate" >
            </td>
            <td>
                 <input type="number" step="any" min="0"  name="_opening_sales_rate" class="form-control" value="{{old('_opening_sales_rate',0)}}" placeholder="Opening Sales Rate" >
            </td>
            <td>
                <input type="number" step="any" min="0"  name="_opening_amount" class="form-control" value="{{old('_opening_amount',0)}}"  >
            </td>
        </tr>
    </tbody>
    <tfoot>
        <tr>
                <th colspan="2">
                    <a href="#none" class="btn btn-default" onclick="addNewRowForOpenig(event)"><i class="fa fa-plus"></i></a>
                  </th>
           
            <th colspan="3">{{__('label._total')}}</th>
            <th>
                <input type="text" name="_total_opening_qty" class="form-control _total_opening_qty" value="0" readonly>
            </th>
            <th></th>
            <th></th>
            <th>
                 <input type="text" name="_total_opening_amount" class="form-control _total_opening_amount" value="0" readonly>
            </th>
        </tr>
    </tfoot>
</table>

</div>
<div class="form-group row">
<div class="offset-sm-2 col-sm-6">
<button type="submit" class="btn btn-danger">Submit</button>
</div>
</div>

</div> <!-- End of tab content -->
</form> <!-- End of form -->

</div>
</div>

</div>
         
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </div>
</div>




@endsection
@section('script')
<script type="text/javascript">
    function addNewRowForOpenig(event){
        $(document).find(".opeing_body").append(` <tr>
            <td>
            <a href="#none" class="btn btn-default _opening_row_remove"><i class="fa fa-trash"></i></a>
          </td>
            <td>
                <select class="form-control _master_branch_id" name="_branch_id" required >
                  @forelse($permited_organizations as $val )
                  <option value="{{$val->id}}" >{{ $val->id ?? '' }} - {{ $val->_name ?? '' }}</option>
                  @empty
                  @endforelse
                </select>
            </td>
            <td>
                <select class="form-control _master_branch_id" name="_branch_id" required >
                  @forelse($permited_branch as $branch )
                  <option value="{{$branch->id}}" @if(isset($request->_branch_id)) @if($request->_branch_id == $branch->id) selected @endif   @endif>{{ $branch->id ?? '' }} - {{ $branch->_name ?? '' }}</option>
                  @empty
                  @endforelse
                </select>
            </td>
            <td>
                <select class="form-control _cost_center_id" name="_cost_center_id" required >    
                  @forelse($permited_costcenters as $cost_center )
                  <option value="{{$cost_center->id}}" @if(isset($request->_cost_center_id)) @if($request->_cost_center_id == $cost_center->id) selected @endif   @endif>{{ $cost_center->id ?? '' }} - {{ $cost_center->_name ?? '' }}</option>
                  @empty
                  @endforelse
                </select>
            </td>
            <td>
                <select class="form-control  _store_id" name="_store_id">
                  @forelse($store_houses as $store)
                  <option value="{{$store->id}}">{{$store->_name ?? '' }}</option>
                  @empty
                  @endforelse
                </select>
            </td>
            
            <td>
                 <input type="number" step="any" min="0" id="_item_opening_qty" name="_opening_qty" class="form-control" value="{{old('_opening_qty',0)}}" placeholder="Opening QTY" >
            </td>
            <td>
                 <input type="number" step="any" min="0"  name="_cost_rate" class="form-control" value="{{old('_cost_rate',0)}}" placeholder="Opening Cost Rate" >
            </td>
            <td>
                 <input type="number" step="any" min="0"  name="_opening_sales_rate" class="form-control" value="{{old('_opening_sales_rate',0)}}" placeholder="Opening Sales Rate" >
            </td>
            <td>
                <input type="number" step="any" min="0"  name="_opening_amount" class="form-control" value="{{old('_opening_amount',0)}}"  >
            </td>
        </tr>`);
    }

    $(document).on('click','._opening_row_remove',function(){
        $(this).closest('tr').remove();
    })
</script>
@endsection
