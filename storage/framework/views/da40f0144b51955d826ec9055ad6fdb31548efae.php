
<?php $__env->startSection('title',$page_name); ?>

<?php $__env->startSection('content'); ?>
<div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-12" style="display: flex;">
            <a class="m-0 _page_name" href="<?php echo e(route('item-information.index')); ?>"><?php echo $page_name ?? ''; ?> </a>
            <ol class="breadcrumb float-sm-right ml-2">
               <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('item-information-create')): ?>
                <a 
               class="btn btn-sm btn-info active " 
               
               href="<?php echo e(route('item-information.create')); ?>">
                   <i class="nav-icon fas fa-plus"></i> Create New
                </a>
              
              <?php endif; ?>
            </ol>
          </div>
          
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <?php if($message = Session::get('success')): ?>
    <div class="alert alert-success">
      <p><?php echo e($message); ?></p>
    </div>
    <?php endif; ?>
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-12">
            <div class="card">
              <div class="card-header border-0 mt-1">
                 

                  <div class="row">
                   <?php

 $currentURL = URL::full();
 $current = URL::current();
if($currentURL === $current){
   $print_url = $current."?print=single";
   $print_url_detal = $current."?print=detail";
}else{
     $print_url = $currentURL."&print=single";
     $print_url_detal = $currentURL."&print=detail";
}
    

                   ?>
                    <div class="col-md-4">
                      <?php echo $__env->make('backend.item-information.search', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    </div>
                    <div class="col-md-8">
                      <div class="d-flex flex-row justify-content-end">
                         <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('voucher-print')): ?>
                        <li class="nav-item dropdown remove_from_header">
                              <a class="nav-link" data-toggle="dropdown" href="#">
                                
                                <i class="fa fa-print " aria-hidden="true"></i> <i class="right fas fa-angle-down "></i>
                              </a>
                              <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                               
                                <div class="dropdown-divider"></div>
                                
                                <a target="__blank" href="<?php echo e($print_url); ?>" class="dropdown-item">
                                  <i class="fa fa-print mr-2" aria-hidden="true"></i> Print
                                </a>  
                            </li>
                             <?php endif; ?>   
                         <?php echo $datas->render(); ?>

                          </div>
                    </div>
                  </div>
              </div>
              <div class="card-body">
                <div class="">
                  <table class="table table-bordered _list_table">
                      <thead>
                        <tr>
                         
                         <th class="">Action</th>
                         <th>SL</th>
                         <th>ID</th>
                         <th>Item</th>
                         <th>Unit Conversion</th>
                         <th>Image</th>
                         <th>Unit</th>
                         <th>Code</th>
                         <th>Serial</th>
                         <th>Barcode</th>
                         <th>Warranty</th>
                         <th>Category</th>
                         
                         <th>Discount</th>
                         <th>Vat</th>
                         <th>Purchase Rate</th>
                         <th>Sales Rate</th>
                         <th>Stock</th>
                         <th>Reorder Level</th>
                         <th>Order Qty</th>
                         <th>Manufacture Company</th>
                         <th>Created At</th>
                         <th>Updated At</th>
                         <th>Status</th> 
                         <?php
                         $default_image = $settings->logo;
                         ?>           
                      </tr>
                      </thead>
                      <tbody>
                        <?php $__currentLoopData = $datas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                          <td style="display: flex;">
                           
                                <a   
                                  href="<?php echo e(route('item-information.show',$data->id)); ?>"
                                  
                                  class="btn btn-sm btn-default  mr-1"><i class="fa fa-eye"></i></a>


                                  <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('item-information-edit')): ?>
                                  <a   
                                  href="<?php echo e(route('item-information.edit',$data->id)); ?>"
                                  
                                  class="btn btn-sm btn-default  mr-1"><i class="fa fa-pen "></i></a>

                                    
                                  <?php endif; ?>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('item-information-delete')): ?>
                                 <?php echo Form::open(['method' => 'DELETE','route' => ['item-information.destroy', $data->id],'style'=>'display:inline']); ?>

                                      <button onclick="return confirm('Are you sure?')" type="submit" class="btn btn-sm btn-default"><i class="fa fa-trash _required"></i></button>
                                  <?php echo Form::close(); ?>

                               <?php endif; ?>  
                               
                        </td>

                             
                            
                            <td><?php echo e(($key+1)); ?></td>
                            <td><?php echo e($data->id ?? ''); ?></td>
                            <td><?php echo e($data->_item ?? ''); ?><br>
                              <b>Production Item:</b> <?php echo e(selected_yes_no($data->_kitchen_item ?? 0)); ?><br>
                              <b>Unique Barcode: </b><?php echo e(selected_yes_no($data->_unique_barcode ?? 0)); ?><br>

                            </td>
                            <td>
                              <button type="button" class="btn btn-info showUnitConversion" data-toggle="modal" data-target="#unitConversionModal" 
                              attr_item_id="<?php echo e($data->id); ?>"  
                              attr_item_name="<?php echo e($data->_item); ?>" 
                              attr_base_unit="<?php echo e($data->_unit_id); ?>" 
                              attr_base_unit_name="<?php echo e($data->_units->_name ?? ''); ?>" 
                              >Unit Conversions</button>
                            </td>
                            <td>
                              <img class="myImage" src="<?php echo e(asset($data->_image ?? $default_image)); ?>" alt="Click me to open modal" title="Click display Image" data-toggle="modal" data-target="#imageModal" style="max-height:50px;max-width: 50px; " >
                              </td>
                            </td>
                            <td><?php echo e($data->_units->_name ?? ''); ?></td>
                            <td><?php echo e($data->_code ?? ''); ?></td>
                            <td><?php echo e($data->_serial ?? ''); ?></td>
                            <td><?php echo e($data->_barcode ?? ''); ?></td>
                            <td><?php echo e($data->_warranty_name->_name ?? ''); ?></td>
                            <td><?php echo e($data->_category->_parents->_name ?? 'C'); ?>-<?php echo e($data->_category->_name ?? ''); ?></td>
                            <td><?php echo e(_report_amount( $data->_discount ?? 0 )); ?></td>
                            <td><?php echo e(_report_amount( $data->_vat ?? 0 )); ?></td>
                            <td><?php echo e(_report_amount($data->_pur_rate ?? 0 )); ?></td>
                            <td><?php echo e(_report_amount($data->_sale_rate ?? 0 )); ?></td>
                            <td><?php echo e($data->_balance ?? 0); ?></td>
                            <td><?php echo e($data->_reorder ?? 0); ?></td>
                            <td><?php echo e($data->_order_qty ?? 0); ?></td>
                            <td><?php echo e($data->_manufacture_company ?? ''); ?></td>
                            <td><?php echo e($data->created_at ?? ''); ?></td>
                            <td><?php echo e($data->updated_at ?? ''); ?></td>
                           <td><?php echo e(selected_status($data->_status)); ?></td>
                           
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
                <!-- /.d-flex -->

                

                <div class="d-flex flex-row justify-content-end">
                 <?php echo $datas->render(); ?>

                </div>
              </div>
            </div>
            <!-- /.card -->

            
        </div>
        <!-- /.row -->
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
      <!-- /.container-fluid -->
    </div>
</div>
<?php echo $__env->make("backend.item-information.unit_conversion", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>


<?php $__env->startSection("script"); ?>

<script type="text/javascript">

  $('.myImage').on('click', function() {
        var imgSrc = $(this).attr('src');
        $('#modalImage').attr('src', imgSrc);
    });


  var base_unit_name="";
  var item_id="";
  var item_name="";
  var base_unit="";
  $(document).on("click",".showUnitConversion",function(){
     item_id = $(this).attr("attr_item_id");
     item_name = $(this).attr("attr_item_name");
    base_unit = $(this).attr("attr_base_unit");
     base_unit_name = $(this).attr("attr_base_unit_name");
    $(document).find(".unitConversionItem").text(item_name);
    $(document).find(".baseUnitName").text(base_unit_name);
    console.log(item_id)

    var request = $.ajax({
      url: "<?php echo e(url('item-wise-unit-conversion')); ?>",
      method: "GET",
      data: { item_id,base_unit_name },
       dataType: "html"
    });
     
    request.done(function( response ) {
      console.log(response)
      $(document).find(".unitConversionArea").html(response);
      
    });
     
    request.fail(function( jqXHR, textStatus ) {
      alert( "Request failed: " + textStatus );
    });

  })

  $(document).on('click','.unitConversionSave',function(){


    var conversion_id =[];
    var conversion_item_id=[];
    var _base_unit_id =[];
    var _conversion_qty=[];
    var _conversion_unit=[];
    var _converted_unit_names=[];
    $(document).find(".conversion_id").each(function(){
        conversion_id.push($(this).val());
    })
    $(document).find(".conversion_item_id").each(function(){
        conversion_item_id.push(item_id);
    })
    $(document).find("._base_unit_id").each(function(){
        _base_unit_id.push(base_unit);
    })
    $(document).find("._conversion_qty").each(function(){
        _conversion_qty.push($(this).val());
    })
    $(document).find("._conversion_unit").each(function(){
        _conversion_unit.push($(this).val());
        _converted_unit_names.push($(this).text());
    })
   // console.log(_converted_unit_names)

    var request = $.ajax({
      url: "<?php echo e(url('item-wise-unit-conversion-save')); ?>",
      method: "GET",
      data: { item_id,conversion_id,conversion_item_id,_base_unit_id,_conversion_qty,_conversion_unit,_converted_unit_names },
      dataType: "json"
    });
     
    request.done(function( response ) {
      console.log(response)
      alert(response.message)
      $('#exampleModal').modal('hide');
      
    });
     
    request.fail(function( jqXHR, textStatus ) {
      alert( "Request failed: " + textStatus );
    });


    
  })

  $(document).on("click",".unitRemoveButton",function(){
    if(confirm("Are You Sure !")){
      $(this).closest("tr").remove();
    }
  })

  $(document).on("click",".addNewRow",function(){
    $(document).find(".unitConversionArea").append(`<tr>
                  
                  <td>
                    <input type="number" name="_conversion_qty[]" min="0" step="any" class="form-control _conversion_qty" value="">
                  </td>
                  <td>
                    <span class="baseUnitName"></span> <b>=</b>
                    <input type="hidden" name="conversion_id[]" class="conversion_id" value="0">
                    <input type="hidden" name="conversion_item_id[]" class="conversion_item_id" value="0">
                    <input type="hidden" name="_base_unit_id[]" class="_base_unit_id" value="0">
                  </td>
                  <td>
                    <select class="form-control _conversion_unit" id="_conversion_unit" name="_conversion_unit[]" >
                      <option value="" >--Units--</option>
                      <?php $__currentLoopData = $units; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $unit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                       <option value="<?php echo e($unit->id); ?>" ><?php echo e($unit->_name ?? ''); ?></option>
                      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                  </td>
                  <td>
                    <button type="button" class="btn btn-sm btn-danger unitRemoveButton">X</button>
                    
                  </td>
                </tr>`);
    $(document).find(".baseUnitName").text(base_unit_name);
    $(document).find("._base_unit_id").text(base_unit);
    $(document).find(".conversion_item_id").text(item_id);
    
  })
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\solar-acc-invenoty\resources\views/backend/item-information/index.blade.php ENDPATH**/ ?>