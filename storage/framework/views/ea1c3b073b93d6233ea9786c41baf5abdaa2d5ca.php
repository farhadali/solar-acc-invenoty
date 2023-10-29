
<?php $__env->startSection('title',$page_name); ?>

<?php $__env->startSection('css'); ?>
<link rel="stylesheet" href="<?php echo e(asset('backend/new_style.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 _page_name"> <?php echo e($page_name ?? ''); ?> </h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('item-information-list')): ?>
              <li class="breadcrumb-item active">
                 <a class="btn btn-info" href="<?php echo e(url('lot-item-information')); ?>"> <i class="fa fa-th-list" aria-hidden="true"></i></a>
               </li>
               <?php endif; ?>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <div class="message-area">
    <?php if(count($errors) > 0): ?>
           <div class="alert alert-danger">
                
                <ul>
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>
         <?php if($message = Session::get('success')): ?>
    <div class="alert alert-success">
      <p><?php echo e($message); ?></p>
    </div>
    <?php endif; ?>
    </div>
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-12">
            <div class="card">
             
              <div class="card-body">
               
                 <form action="<?php echo e(url('item-sales-price-update')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="row">
                       
                      
                       
                        <div class="col-xs-12 col-sm-12 col-md-6">
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
                        <div class="col-xs-12 col-sm-12 col-md-6">
                            <div class="form-group">
                                <label for="_barcode">Model/Barcode:</label>
                                <input type="text" id="_barcode" name="_barcode" class="form-control" value="<?php echo e(old('_barcode',$data->_barcode)); ?>" <?php if($data->_unique_barcode==1): ?> readonly <?php endif; ?> placeholder="Model" >
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-6">
                            <div class="form-group">
                                <label>Warranty: </label>
                               <select  class="form-control _warranty " name="_warranty" required>
                                  <option value="0">--Select Warranty--</option>
                                  <?php $__empty_1 = true; $__currentLoopData = $_warranties; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $_warranty): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                  <option value="<?php echo e($_warranty->id); ?>" <?php if(isset($data->_warranty)): ?> <?php if($data->_warranty == $_warranty->id): ?> selected <?php endif; ?>   <?php endif; ?>><?php echo e($_warranty->_name ?? ''); ?></option>
                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                  <?php endif; ?>
                                </select>
                            </div>
                        </div>
                      
                        <div class="col-xs-12 col-sm-12 col-md-6">
                            <div class="form-group">
                                <label for="_p_discount_input">Discount Rate:</label>
                                <input type="text" id="_p_discount_input" name="_p_discount_input" class="form-control" value="<?php echo e(old('_p_discount_input',$data->_p_discount_input)); ?>" placeholder="Discount Rate" >
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-6">
                            <div class="form-group">
                                <label for="_p_vat">Vat Rate:</label>
                                <input type="text" id="_p_vat" name="_p_vat" class="form-control" value="<?php echo e(old('_p_vat',$data->_p_vat)); ?>" placeholder="Vat Rate" >
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-6">
                            <div class="form-group">
                                <label for="_pur_rate">Purchase Rate:</label>
                                <input type="text" id="_pur_rate" readonly class="form-control" value="<?php echo e(old('_pur_rate',$data->_pur_rate)); ?>" placeholder="Purchase Rate" >
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-6">
                            <div class="form-group">
                                <label for="_sales_rate">Sales Rate:</label>
                                <input type="text" id="_sales_rate" name="_sales_rate" class="form-control" value="<?php echo e(old('_sales_rate',$data->_sales_rate)); ?>" placeholder="Sales Rate" >
                            </div>
                        </div>
                       
                       
                         <div class="col-xs-12 col-sm-12 col-md-6">
                            <div class="form-group">
                                <label for="_status">Status:</label>
                                <select class="form-control" name="_status" id="_status">
                                 <option value="1" <?php if($data->_status==1): ?> selected <?php endif; ?> >Active</option>
                                  <option value="0" <?php if($data->_status==0): ?> selected <?php endif; ?> >In Active</option>
                                </select>
                            </div>
                        </div>
                        
                        
                       <div class="col-xs-12 col-sm-12 col-md-12 bottom_save_section text-middle">
                            <button type="submit" class="btn btn-success submit-button ml-5"><i class="fa fa-credit-card mr-2" aria-hidden="true"></i> Save</button>
                           
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
<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\own\inv-acc-hrm\resources\views/backend/item-information/lot_edit.blade.php ENDPATH**/ ?>