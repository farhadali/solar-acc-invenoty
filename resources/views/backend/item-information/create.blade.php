
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
            <div class="card">
             <div class="card-header">
                 
                   @include('backend.message.message')
                    
              </div>
              <div class="card-body">
                {!! Form::open(array('route' => 'item-information.store','method'=>'POST','enctype'=>'multipart/form-data')) !!}
                    <div class="row">
                       <div class="col-xs-12 col-sm-12 col-md-3">
                            <div class="form-group">
                                <label>Category: <span class="_required">*</span></label>
                               <select  class="form-control _category_id select2" name="_category_id" required>
                                  <option value="">--Select Category--</option>
                                  @forelse($categories as $category )
                                  <option value="{{$category->id}}"  @if(old('_category_id') == $category->id) selected @endif  >{{ $category->_parents->_name ?? 'C' }}->{{ $category->_name ?? '' }}</option>
                                  @empty
                                  @endforelse
                                </select>
                            </div>
                        </div>
                      
                       
                        <div class="col-xs-12 col-sm-12 col-md-3">
                            <div class="form-group">
                                <label for="_item">Item:<span class="_required">*</span></label>
                                <input type="text" id="_item" name="_item" class="form-control" value="{{old('_item')}}" placeholder="Item" required>
                            </div>
                        </div>
                       
                       
                       
                       
                        <div class="col-xs-12 col-sm-12 col-md-3">
                            <div class="form-group">
                                <label for="_unit">Unit:<span class="_required">*</span></label>

                                <select class="form-control _unit_id " id="_unit_id" name="_unit_id" required>
                                  <option value="" >--Units--</option>
                                  @foreach($units as $unit)
                                   <option value="{{$unit->id}}" @if(old('_unit_id')==$unit->id) selected @endif >{{$unit->_name ?? ''}}</option>
                                  @endforeach
                                </select>
                            </div>
                        </div>
                       <div class="col-xs-12 col-sm-12 col-md-3">
                            <div class="form-group">
                                <label>Warranty: </label>
                               <select  class="form-control _warranty " name="_warranty" >
                                  <option value="">--Select Warranty--</option>
                                  @forelse($_warranties as $_warranty )
                                  <option value="{{$_warranty->id}}" @if(isset($request->_warranty)) @if($request->_warranty == $_warranty->id) selected @endif   @endif>{{ $_warranty->_name ?? '' }}</option>
                                  @empty
                                  @endforelse
                                </select>
                            </div>
                        </div>
                         <div class="col-xs-12 col-sm-12 col-md-3">
                            <div class="form-group">
                                <label for="_manufacture_company">Manufacture Company:</label>
                                <input type="text" id="_manufacture_company" name="_manufacture_company" class="form-control" value="{{old('_manufacture_company')}}" placeholder="Manufacture Company" >
                            </div>
                        </div>
                         <div class="col-xs-12 col-sm-12 col-md-3">
                            <div class="form-group">
                                <label for="_code">Code:</label>
                                <input type="text" id="_code" name="_code" class="form-control" value="{{old('_code')}}" placeholder="Code" >
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-3">
                            <div class="form-group">
                                <label for="_barcode">Model:</label>
                                <input type="text" id="_barcode" name="_barcode" class="form-control" value="{{old('_barcode')}}" placeholder="Model" >
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-3">
                            <div class="form-group">
                                <label for="_discount">Discount Rate:</label>
                                <input type="text" id="_discount" name="_discount" class="form-control" value="{{old('_discount')}}" placeholder="Discount Rate" >
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-3">
                            <div class="form-group">
                                <label for="_vat">Vat Rate:</label>
                                <input type="text" id="_vat" name="_vat" class="form-control" value="{{old('_vat')}}" placeholder="Vat Rate" >
                            </div>
                        </div>
                        
                        <div class="col-xs-12 col-sm-12 col-md-3 @if(sizeof($permited_branch)==1) display_none @endif ">
                            <div class="form-group ">
                                <label>Branch:<span class="_required">*</span></label>
                               <select class="form-control _master_branch_id" name="_branch_id" required >
                                  
                                  @forelse($permited_branch as $branch )
                                  <option value="{{$branch->id}}" @if(isset($request->_branch_id)) @if($request->_branch_id == $branch->id) selected @endif   @endif>{{ $branch->id ?? '' }} - {{ $branch->_name ?? '' }}</option>
                                  @empty
                                  @endforelse
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-3 @if(sizeof($permited_costcenters)==1) display_none @endif ">
                            <div class="form-group ">
                                <label>Cost Center:<span class="_required">*</span></label>
                               <select class="form-control _cost_center_id" name="_cost_center_id" required >
                                  
                                  @forelse($permited_costcenters as $cost_center )
                                  <option value="{{$cost_center->id}}" @if(isset($request->_cost_center_id)) @if($request->_cost_center_id == $cost_center->id) selected @endif   @endif>{{ $cost_center->id ?? '' }} - {{ $cost_center->_name ?? '' }}</option>
                                  @empty
                                  @endforelse
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-3 @if(sizeof($store_houses)==1) display_none @endif">
                            <div class="form-group ">
                                <label>Store House:<span class="_required">*</span></label>
                                <select class="form-control  _store_id" name="_store_id">
                                      @forelse($store_houses as $store)
                                      <option value="{{$store->id}}">{{$store->_name ?? '' }}</option>
                                      @empty
                                      @endforelse
                                    </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-3">
                            <div class="form-group">
                                <label for="_opening_qty">Opening QTY:</label>
                                <input type="number" step="any" min="0" id="_item_opening_qty" name="_opening_qty" class="form-control" value="{{old('_opening_qty',0)}}" placeholder="Opening QTY" >
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-3">
                            <div class="form-group">
                                <label for="_pur_rate">Purchase Rate:</label>
                                <input type="number" step="any" min="0" id="_item_pur_rate" name="_pur_rate" class="form-control" value="{{old('_pur_rate',0)}}" placeholder="Purchase Rate" >
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-3">
                            <div class="form-group">
                                <label for="_sale_rate">Sales Rate:</label>
                                <input type="number" step="any" min="0" id="_item_sale_rate" name="_sale_rate" class="form-control" value="{{old('_sale_rate',0)}}" placeholder="Sales Rate" >
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-3">
                            <div class="form-group">
                                <label for="_reorder">Reorder Level:</label>
                                <input type="text" id="_reorder" name="_reorder" class="form-control" value="{{old('_reorder')}}" placeholder="Reorder Level" >
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-3">
                            <div class="form-group">
                                <label for="_order_qty">Order Qty:</label>
                                <input type="text" id="_order_qty" name="_order_qty" class="form-control" value="{{old('_order_qty')}}" placeholder="Order Qty" >
                            </div>
                        </div>
                       @can('restaurant-module') 
                         <div class="col-xs-12 col-sm-12 col-md-3">
                            <div class="form-group">
                                <label for="_kitchen_item" class="_required" title="if Yes then this item will send to kitchen to cook/production for sales and store deduct as per item ingredient wise automaticaly">Kitchen/Production Item ?:</label>
                                <select class="form-control" name="_kitchen_item" id="_kitchen_item">
                                  <option value="0">No</option>
                                  <option value="1">Yes</option>
                                </select>
                            </div>
                        </div>
                        @endcan
                         <div class="col-xs-12 col-sm-12 col-md-3">
                            <div class="form-group">
                                <label for="_unique_barcode" class="_required">Use Unique Barcode ?:</label>
                                <select class="form-control" name="_unique_barcode" id="_unique_barcode">
                                  <option value="0">NO</option>
                                  <option value="1">Yes</option>
                                </select>
                            </div>
                        </div>

                         <div class="col-xs-12 col-sm-12 col-md-3">
                            <div class="form-group">
                                <label for="_status">Status:</label>
                                <select class="form-control" name="_status" id="_status">
                                  <option value="1">Active</option>
                                  <option value="0">In Active</option>
                                </select>
                            </div>
                        </div>
                        
                         <div class="col-xs-12 col-sm-12 col-md-3 mb-10">
                            <div class="form-group">
                                <label>Image:</label>
                               <input type="file" accept="image/*" onchange="loadFile(event,1 )"  name="_image" class="form-control">
                               <img id="output_1" class="banner_image_create" src="{{asset('/')}}{{$settings->logo ?? ''}}"  style="max-height:100px;max-width: 100px; " />
                            </div>
                        </div>
                       
                       
                        <div class="col-xs-12 col-sm-12 col-md-12 bottom_save_section text-middle">
                            <button type="submit" class="btn btn-success item_save  ml-5"><i class="fa fa-credit-card mr-2" aria-hidden="true"></i> Save</button>
                           
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



<script type="text/javascript">
  
  // $(document).on('click','.item_save',function(event){
  //   event.preventDefault();
  //   var _item_opening_qty = $(document).find('#_item_opening_qty').val();
  //   var _item_pur_rate = $(document).find('#_item_pur_rate').val();
  //   var _item_sale_rate = $(document).find('#_item_sale_rate').val();

  //   if(_item_opening_qty > 0){

  //   }





  // })
</script>

