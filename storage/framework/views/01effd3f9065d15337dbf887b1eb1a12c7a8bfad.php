
<?php $__env->startSection('title',$page_name ?? ''); ?>
<?php $__env->startSection('content'); ?>
<div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <a class="m-0 _page_name" href="<?php echo e(route('item-information.index')); ?>"><?php echo $page_name ?? ''; ?> </a>
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
                <?php echo Form::open(array('route' => 'item-information.store','method'=>'POST','enctype'=>'multipart/form-data')); ?>

                    <div class="row">
                       <div class="col-xs-12 col-sm-12 col-md-3">
                            <div class="form-group">
                                <label>Category: <span class="_required">*</span></label>
                               <select  class="form-control _category_id " name="_category_id" required>
                                  <option value="">--Select Category--</option>
                                  <?php $__empty_1 = true; $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                  <option value="<?php echo e($category->id); ?>"  <?php if(old('_category_id') == $category->id): ?> selected <?php endif; ?>  ><?php echo e($category->_parents->_name ?? 'C'); ?>-><?php echo e($category->_name ?? ''); ?></option>
                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                  <?php endif; ?>
                                </select>
                            </div>
                        </div>
                      
                       
                        <div class="col-xs-12 col-sm-12 col-md-3">
                            <div class="form-group">
                                <label for="_item">Item:<span class="_required">*</span></label>
                                <input type="text" id="_item" name="_item" class="form-control" value="<?php echo e(old('_item')); ?>" placeholder="Item" required>
                            </div>
                        </div>
                       
                       
                       
                       
                        <div class="col-xs-12 col-sm-12 col-md-3">
                            <div class="form-group">
                                <label for="_unit">Unit:<span class="_required">*</span></label>

                                <select class="form-control _unit_id " id="_unit_id" name="_unit_id" required>
                                  <option value="" >--Units--</option>
                                  <?php $__currentLoopData = $units; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $unit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                   <option value="<?php echo e($unit->id); ?>" <?php if(old('_unit_id')==$unit->id): ?> selected <?php endif; ?> ><?php echo e($unit->_name ?? ''); ?></option>
                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                       <div class="col-xs-12 col-sm-12 col-md-3">
                            <div class="form-group">
                                <label>Warranty: </label>
                               <select  class="form-control _warranty " name="_warranty" >
                                  <option value="">--Select Warranty--</option>
                                  <?php $__empty_1 = true; $__currentLoopData = $_warranties; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $_warranty): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                  <option value="<?php echo e($_warranty->id); ?>" <?php if(isset($request->_warranty)): ?> <?php if($request->_warranty == $_warranty->id): ?> selected <?php endif; ?>   <?php endif; ?>><?php echo e($_warranty->_name ?? ''); ?></option>
                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                  <?php endif; ?>
                                </select>
                            </div>
                        </div>
                         <div class="col-xs-12 col-sm-12 col-md-3">
                            <div class="form-group">
                                <label for="_manufacture_company">Manufacture Company:</label>
                                <input type="text" id="_manufacture_company" name="_manufacture_company" class="form-control _manufacture_company" value="<?php echo e(old('_manufacture_company')); ?>" placeholder="Manufacture Company" >
                                <div class="search_boxManufacCompany"></div>
                            </div>
                        </div>
                         <div class="col-xs-12 col-sm-12 col-md-3">
                            <div class="form-group">
                                <label for="_code">Code:</label>
                                <input type="text" id="_code" name="_code" class="form-control" value="<?php echo e(old('_code')); ?>" placeholder="Code" >
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-3">
                            <div class="form-group">
                                <label for="_barcode">Model:</label>
                                <input type="text" id="_barcode" name="_barcode" class="form-control" value="<?php echo e(old('_barcode')); ?>" placeholder="Model" >
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-2">
                            <div class="form-group">
                                <label for="_discount">Discount Rate:</label>
                                <input type="text" id="_discount" name="_discount" class="form-control" value="<?php echo e(old('_discount')); ?>" placeholder="Discount Rate" >
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-2">
                            <div class="form-group">
                                <label for="_vat">Vat Rate:</label>
                                <input type="text" id="_vat" name="_vat" class="form-control" value="<?php echo e(old('_vat')); ?>" placeholder="Vat Rate" >
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-2">
                            <div class="form-group">
                                <label for="_sd"><?php echo e(__('label._sd')); ?>:</label>
                                <input type="text" id="_sd" name="_sd" class="form-control" value="<?php echo e(old('_sd')); ?>" placeholder="<?php echo e(__('label._sd')); ?>" >
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-2">
                            <div class="form-group">
                                <label for="_cd"><?php echo e(__('label._cd')); ?>:</label>
                                <input type="text" id="_cd" name="_cd" class="form-control" value="<?php echo e(old('_cd')); ?>" placeholder="<?php echo e(__('label._cd')); ?>" >
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-2">
                            <div class="form-group">
                                <label for="_ait"><?php echo e(__('label._ait')); ?>:</label>
                                <input type="text" id="_ait" name="_ait" class="form-control" value="<?php echo e(old('_ait')); ?>" placeholder="<?php echo e(__('label._ait')); ?>" >
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-2">
                            <div class="form-group">
                                <label for="_rd"><?php echo e(__('label._rd')); ?>:</label>
                                <input type="text" id="_rd" name="_rd" class="form-control" value="<?php echo e(old('_rd')); ?>" placeholder="<?php echo e(__('label._rd')); ?>" >
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-2">
                            <div class="form-group">
                                <label for="_at"><?php echo e(__('label._at')); ?>:</label>
                                <input type="text" id="_at" name="_at" class="form-control" value="<?php echo e(old('_at')); ?>" placeholder="<?php echo e(__('label._at')); ?>" >
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-2">
                            <div class="form-group">
                                <label for="_tti"><?php echo e(__('label._tti')); ?>:</label>
                                <input type="text" id="_tti" name="_tti" class="form-control" value="<?php echo e(old('_tti')); ?>" placeholder="<?php echo e(__('label._tti')); ?>" >
                            </div>
                        </div>
                        
                        <div class="col-xs-12 col-sm-12 col-md-2 <?php if(sizeof($permited_branch)==1): ?> display_none <?php endif; ?> ">
                            <div class="form-group ">
                                <label>Branch:<span class="_required">*</span></label>
                               <select class="form-control _master_branch_id" name="_branch_id" required >
                                  
                                  <?php $__empty_1 = true; $__currentLoopData = $permited_branch; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $branch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                  <option value="<?php echo e($branch->id); ?>" <?php if(isset($request->_branch_id)): ?> <?php if($request->_branch_id == $branch->id): ?> selected <?php endif; ?>   <?php endif; ?>><?php echo e($branch->id ?? ''); ?> - <?php echo e($branch->_name ?? ''); ?></option>
                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                  <?php endif; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-2 <?php if(sizeof($permited_costcenters)==1): ?> display_none <?php endif; ?> ">
                            <div class="form-group ">
                                <label>Cost Center:<span class="_required">*</span></label>
                               <select class="form-control _cost_center_id" name="_cost_center_id" required >
                                  
                                  <?php $__empty_1 = true; $__currentLoopData = $permited_costcenters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cost_center): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                  <option value="<?php echo e($cost_center->id); ?>" <?php if(isset($request->_cost_center_id)): ?> <?php if($request->_cost_center_id == $cost_center->id): ?> selected <?php endif; ?>   <?php endif; ?>><?php echo e($cost_center->id ?? ''); ?> - <?php echo e($cost_center->_name ?? ''); ?></option>
                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                  <?php endif; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-2 <?php if(sizeof($store_houses)==1): ?> display_none <?php endif; ?>">
                            <div class="form-group ">
                                <label>Store House:<span class="_required">*</span></label>
                                <select class="form-control  _store_id" name="_store_id">
                                      <?php $__empty_1 = true; $__currentLoopData = $store_houses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $store): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                      <option value="<?php echo e($store->id); ?>"><?php echo e($store->_name ?? ''); ?></option>
                                      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                      <?php endif; ?>
                                    </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-2">
                            <div class="form-group">
                                <label for="_opening_qty">Opening QTY:</label>
                                <input type="number" step="any" min="0" id="_item_opening_qty" name="_opening_qty" class="form-control" value="<?php echo e(old('_opening_qty',0)); ?>" placeholder="Opening QTY" >
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-2">
                            <div class="form-group">
                                <label for="_pur_rate">Purchase Rate:</label>
                                <input type="number" step="any" min="0" id="_item_pur_rate" name="_pur_rate" class="form-control" value="<?php echo e(old('_pur_rate',0)); ?>" placeholder="Purchase Rate" >
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-2">
                            <div class="form-group">
                                <label for="_sale_rate">Sales Rate:</label>
                                <input type="number" step="any" min="0" id="_item_sale_rate" name="_sale_rate" class="form-control" value="<?php echo e(old('_sale_rate',0)); ?>" placeholder="Sales Rate" >
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-2">
                            <div class="form-group">
                                <label for="_reorder">Reorder Level:</label>
                                <input type="text" id="_reorder" name="_reorder" class="form-control" value="<?php echo e(old('_reorder')); ?>" placeholder="Reorder Level" >
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-2">
                            <div class="form-group">
                                <label for="_order_qty">Order Qty:</label>
                                <input type="text" id="_order_qty" name="_order_qty" class="form-control" value="<?php echo e(old('_order_qty')); ?>" placeholder="Order Qty" >
                            </div>
                        </div>
                       <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('restaurant-module')): ?> 
                         <div class="col-xs-12 col-sm-12 col-md-2">
                            <div class="form-group">
                                <label for="_kitchen_item" class="_required" title="if Yes then this item will send to kitchen to cook/production for sales and store deduct as per item ingredient wise automaticaly">Kitchen/Production Item ?:</label>
                                <select class="form-control" name="_kitchen_item" id="_kitchen_item">
                                  <option value="0">No</option>
                                  <option value="1">Yes</option>
                                </select>
                            </div>
                        </div>
                        <?php endif; ?>
                         <div class="col-xs-12 col-sm-12 col-md-2">
                            <div class="form-group">
                                <label for="_unique_barcode" class="_required">Use Unique Barcode ?:</label>
                                <select class="form-control" name="_unique_barcode" id="_unique_barcode">
                                  <option value="0">NO</option>
                                  <option value="1">Yes</option>
                                </select>
                            </div>
                        </div>

                         <div class="col-xs-12 col-sm-12 col-md-2">
                            <div class="form-group">
                                <label for="_status">Status:</label>
                                <select class="form-control" name="_status" id="_status">
                                  <option value="1">Active</option>
                                  <option value="0">In Active</option>
                                </select>
                            </div>
                        </div>
                        
                         <div class="col-xs-12 col-sm-12 col-md-2 mb-10">
                            <div class="form-group">
                                <label>Image:</label>
                               <input type="file" accept="image/*" onchange="loadFile(event,1 )"  name="_image" class="form-control">
                               <img id="output_1" class="banner_image_create" src="<?php echo e(asset('/')); ?><?php echo e($settings->logo ?? ''); ?>"  style="max-height:100px;max-width: 100px; " />
                            </div>
                        </div>
                       
                       
                        <div class="col-xs-12 col-sm-12 col-md-12 bottom_save_section text-middle">
                            <button type="submit" class="btn btn-success item_save  ml-5"><i class="fa fa-credit-card mr-2" aria-hidden="true"></i> Save</button>
                           
                        </div>
                        <br><br>
                    </div>
                    <?php echo Form::close(); ?>

                
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


<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\own\inv-acc-hrm\resources\views/backend/item-information/create.blade.php ENDPATH**/ ?>