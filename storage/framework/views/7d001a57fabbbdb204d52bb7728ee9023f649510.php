
<?php $__env->startSection('title',$page_name ?? ''); ?>
<?php $__env->startSection('content'); ?>
<div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <a class="m-0 _page_name" href="<?php echo e(route('item-information.index')); ?>"><?php echo $page_name ?? ''; ?> </a>
            <ol class="breadcrumb float-sm-right ml-2">
               
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('item-information-list')): ?>
              <li class="breadcrumb-item active">
                 <a class="btn btn-info" href="<?php echo e(route('item-information.index')); ?>"> <i class="fa fa-th-list" aria-hidden="true"></i></a>
               </li>
               <?php endif; ?>
               
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
                   <?php echo $__env->make('backend.message.message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
              </div>
              <div class="card-body">
               
                 <form action="<?php echo e(url('item-information/update')); ?>" method="POST" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <div class="row">
                       <div class="col-xs-12 col-sm-12 col-md-3">
                            <div class="form-group">
                                <label>Category: <span class="_required">*</span></label>
                               <select  class="form-control _category_id " name="_category_id" required>
                                  <option value="">--Select Category--</option>
                                  <?php $__empty_1 = true; $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                  <option value="<?php echo e($category->id); ?>" <?php if(isset($data->_category_id)): ?> <?php if($data->_category_id == $category->id): ?> selected <?php endif; ?>   <?php endif; ?>><?php echo e($category->_parents->_name ?? 'C'); ?>-><?php echo e($category->_name ?? ''); ?></option>
                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                  <?php endif; ?>
                                </select>
                            </div>
                        </div>
                       
                      
                       
                        <div class="col-xs-12 col-sm-12 col-md-3">
                            <div class="form-group">
                                <label for="_item">Item:<span class="_required">*</span></label>
                                <input type="text" id="_item" name="_item" class="form-control" value="<?php echo e(old('_item',$data->_item)); ?>" placeholder="Item" required>
                                <input type="hidden" name="id" value="<?php echo e($data->id); ?>">
                            </div>
                        </div>
                        
                       
                        
                        <div class="col-xs-12 col-sm-12 col-md-3">
                            <div class="form-group">
                                <label for="_unit">Unit:<span class="_required">*</span></label>

                                <select class="form-control _unit_id " id="_unit_id" name="_unit_id" required>
                                  <option value="" >--Units--</option>
                                  <?php $__currentLoopData = $units; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $unit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                   <option value="<?php echo e($unit->id); ?>" <?php if(isset($data->_unit_id)): ?> <?php if($data->_unit_id==$unit->id): ?> selected <?php endif; ?> <?php endif; ?> ><?php echo e($unit->_name ?? ''); ?></option>
                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                         <div class="col-xs-12 col-sm-12 col-md-3">
                            <div class="form-group">
                                <label>Warranty: </label>
                               <select  class="form-control _warranty " name="_warranty" >
                                  <option value="0">--None--</option>
                                  <?php $__empty_1 = true; $__currentLoopData = $_warranties; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $_warranty): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                  <option value="<?php echo e($_warranty->id); ?>" <?php if(isset($data->_warranty)): ?> <?php if($data->_warranty == $_warranty->id): ?> selected <?php endif; ?>   <?php endif; ?>><?php echo e($_warranty->_name ?? ''); ?></option>
                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                  <?php endif; ?>
                                </select>
                            </div>
                        </div>
                         <div class="col-xs-12 col-sm-12 col-md-3">
                            <div class="form-group">
                                <label>Previous Sales Item Overwrite: </label>
                               <select  class="form-control _update_all_item_name " name="_update_all_item_name" >
                                  <option value="0">No</option>
                                  <option value="1">Yes</option>
                               </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-3">
                            <div class="form-group">
                                <label for="_manufacture_company">Manufacture Company:</label>
                                <input type="text" id="_manufacture_company" name="_manufacture_company" class="form-control _manufacture_company" value="<?php echo e(old('_manufacture_company',$data->_manufacture_company)); ?>" placeholder="Manufacture Company" >
                                <div class="search_boxManufacCompany"></div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-3">
                            <div class="form-group">
                                <label for="_code">Code:</label>
                                <input type="text" id="_code" name="_code" class="form-control" value="<?php echo e(old('_code',$data->_code)); ?>" placeholder="Code" >
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-3">
                            <div class="form-group">
                                <label for="_barcode">Model:</label>
                                <input type="text" id="_barcode" name="_barcode" class="form-control" value="<?php echo e(old('_barcode',$data->_barcode)); ?>" placeholder="Model" >
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-2">
                            <div class="form-group">
                                <label for="_discount">Discount Rate:</label>
                                <input type="text" id="_discount" name="_discount" class="form-control" value="<?php echo e(old('_discount',$data->_discount)); ?>" placeholder="Discount Rate" >
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-2">
                            <div class="form-group">
                                <label for="_vat">Vat Rate:</label>
                                <input type="text" id="_vat" name="_vat" class="form-control" value="<?php echo e(old('_vat',$data->_vat)); ?>" placeholder="Vat Rate" >
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-2">
                            <div class="form-group">
                                <label for="_sd"><?php echo e(__('label._sd')); ?>:</label>
                                <input type="text" id="_sd" name="_sd" class="form-control" value="<?php echo e(old('_sd',$data->_sd)); ?>" placeholder="<?php echo e(__('label._sd')); ?>" >
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-2">
                            <div class="form-group">
                                <label for="_cd"><?php echo e(__('label._cd')); ?>:</label>
                                <input type="text" id="_cd" name="_cd" class="form-control" value="<?php echo e(old('_cd',$data->_cd)); ?>" placeholder="<?php echo e(__('label._cd')); ?>" >
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-2">
                            <div class="form-group">
                                <label for="_ait"><?php echo e(__('label._ait')); ?>:</label>
                                <input type="text" id="_ait" name="_ait" class="form-control" value="<?php echo e(old('_ait',$data->_ait)); ?>" placeholder="<?php echo e(__('label._ait')); ?>" >
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-2">
                            <div class="form-group">
                                <label for="_rd"><?php echo e(__('label._rd')); ?>:</label>
                                <input type="text" id="_rd" name="_rd" class="form-control" value="<?php echo e(old('_rd',$data->_rd)); ?>" placeholder="<?php echo e(__('label._rd')); ?>" >
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-2">
                            <div class="form-group">
                                <label for="_at"><?php echo e(__('label._at')); ?>:</label>
                                <input type="text" id="_at" name="_at" class="form-control" value="<?php echo e(old('_at',$data->_at)); ?>" placeholder="<?php echo e(__('label._at')); ?>" >
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-2">
                            <div class="form-group">
                                <label for="_tti"><?php echo e(__('label._tti')); ?>:</label>
                                <input type="text" id="_tti" name="_tti" class="form-control" value="<?php echo e(old('_tti',$data->_tti)); ?>" placeholder="<?php echo e(__('label._tti')); ?>" >
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-2">
                            <div class="form-group">
                                <label for="_opening_qty">Opening QTY:</label>
                                <input type="text" id="_opening_qty" name="_opening_qty" class="form-control" value="<?php echo e(old('_opening_qty',$data->_opening_qty ?? 0)); ?>" placeholder="Opening QTY" >
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-2">
                            <div class="form-group">
                                <label for="_pur_rate">Purchase Rate:</label>
                                <input type="text" id="_pur_rate" name="_pur_rate" class="form-control" value="<?php echo e(old('_pur_rate',$data->_pur_rate)); ?>" placeholder="Purchase Rate" >
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-2">
                            <div class="form-group">
                                <label for="_sale_rate">Sales Rate:</label>
                                <input type="text" id="_sale_rate" name="_sale_rate" class="form-control" value="<?php echo e(old('_sale_rate',$data->_sale_rate)); ?>" placeholder="Sales Rate" >
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-2">
                            <div class="form-group">
                                <label for="_reorder">Reorder Level:</label>
                                <input type="text" id="_reorder" name="_reorder" class="form-control" value="<?php echo e(old('_reorder',$data->_reorder)); ?>" placeholder="Reorder Level" >
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-2">
                            <div class="form-group">
                                <label for="_order_qty">Order Qty:</label>
                                <input type="text" id="_order_qty" name="_order_qty" class="form-control" value="<?php echo e(old('_order_qty',$data->_order_qty)); ?>" placeholder="Order Qty" >
                            </div>
                        </div>
                        
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('restaurant-module')): ?> 
                         <div class="col-xs-12 col-sm-12 col-md-2">
                            <div class="form-group">
                                <label for="_kitchen_item" class="_required" title="if Yes then this item will send to kitchen to cook/production for sales and store deduct as per item ingredient wise automaticaly">Kitchen/Production Item ?:</label>
                                <select class="form-control" name="_kitchen_item" id="_kitchen_item">
                                  <option value="0" <?php if($data->_kitchen_item==0): ?> selected <?php endif; ?>>No</option>
                                  <option value="1" <?php if($data->_kitchen_item==1): ?> selected <?php endif; ?>>Yes</option>
                                </select>
                            </div>
                        </div>
                        <?php endif; ?>
                         <div class="col-xs-12 col-sm-12 col-md-2">
                            <div class="form-group">
                                <label for="_unique_barcode" class="_required">Use Unique Barcode ?:</label>
                                <select class="form-control" name="_unique_barcode" id="_unique_barcode">
                                 <option value="1" <?php if($data->_unique_barcode==1): ?> selected <?php endif; ?> >Yes</option>
                                  <option value="0" <?php if($data->_unique_barcode==0): ?> selected <?php endif; ?> >No</option>
                                </select>
                            </div>
                        </div>
                         <div class="col-xs-12 col-sm-12 col-md-2">
                            <div class="form-group">
                                <label for="_status">Status:</label>
                                <select class="form-control" name="_status" id="_status">
                                 <option value="1" <?php if($data->_status==1): ?> selected <?php endif; ?> >Active</option>
                                  <option value="0" <?php if($data->_status==0): ?> selected <?php endif; ?> >In Active</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-3 mb-10">
                            <div class="form-group">
                                <label>Image:</label>
                               <input type="file" accept="image/*" onchange="loadFile(event,1 )"  name="_image" class="form-control">
                               
                               <img id="output_1" class="banner_image_create" src="<?php echo e(asset($data->_image)); ?>"  style="max-height:100px;max-width: 100px; " />
                               
                            </div>
                        </div>
                        
                        
                       <div class="col-xs-12 col-sm-12 col-md-12 bottom_save_section text-middle">
                            <button type="submit" class="btn btn-success  ml-5"><i class="fa fa-credit-card mr-2" aria-hidden="true"></i> Save</button>
                           
                        </div>
                        <br><br>
                    </div>
                    </form>
                
              </div>
            </div>
            <!-- /.card -->

            
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\own\inv-acc-hrm\resources\views/backend/item-information/edit.blade.php ENDPATH**/ ?>