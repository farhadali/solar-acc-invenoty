
<?php $__env->startSection('title',$page_name); ?>

<?php $__env->startSection('content'); ?>

  <div class="content ">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
          <div class="card">
           <div class="row mb-2">
                  <div class="col-sm-6">
                    <a class="m-0 _page_name" href="<?php echo e(route('hrm-employee.index')); ?>"><?php echo $page_name; ?> </a>
                  </div><!-- /.col -->
                  <div class="col-sm-6">
                    
                  </div><!-- /.col -->
                </div><!-- /.row -->
          
         <div class="message-area">
    <?php echo $__env->make('backend.message.message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>
            <div class="card-body p-4" >
                <?php echo Form::open(array('route' => 'hrm-employee.store','method'=>'POST')); ?>

                <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo __('label.image'); ?>:<span class="_required">*</span></label>
                                <input type="file" accept="image/*" onchange="loadFile(event,1 )"  name="_photo" class="form-control">
                               <img id="output_1" class="banner_image_create" src="<?php echo e(asset('/')); ?><?php echo e($settings->logo ?? ''); ?>"  style="max-height:100px;max-width: 100px; " />
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo __('label._name'); ?>:<span class="_required">*</span></label>
                                <input type="text" class="form-control" name="_name" value="<?php echo e(old('_name')); ?>" placeholder="<?php echo e(__('label._name')); ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo __('label._code'); ?>:</label>
                                <input type="text" class="form-control" name="_code" value="<?php echo e(old('_code')); ?>" placeholder="<?php echo e(__('label._code')); ?>" readonly>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo __('label.employee_category_id'); ?>:<span class="_required">*</span></label>
                                <select class="form-control" name="_category_id" required>
                                  <option value=""><--<?php echo e(__('label.select')); ?>--></option>
                                  <?php $__empty_1 = true; $__currentLoopData = $employee_catogories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                  <option value="<?php echo e($val->id); ?>" <?php if(old('_category_id')==$val->id): ?> selected <?php endif; ?>><?php echo $val->_name ?? ''; ?></option>
                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                  <?php endif; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo __('label._department_id'); ?>:<span class="_required">*</span></label>
                                <select class="form-control select2" name="_department_id" required >
                                  <option value=""><--<?php echo e(__('label.select')); ?>--></option>
                                  <?php $__empty_1 = true; $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                  <option value="<?php echo e($val->id); ?>" <?php if(old('_department_id')==$val->id): ?> selected <?php endif; ?>><?php echo $val->_department ?? ''; ?></option>
                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                  <?php endif; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo __('label._jobtitle_id'); ?>:<span class="_required">*</span></label>
                                <select class="form-control select2" name="_jobtitle_id" required >
                                  <option value=""><--<?php echo e(__('label.select')); ?>--></option>
                                  <?php $__empty_1 = true; $__currentLoopData = $designations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                  <option value="<?php echo e($val->id); ?>" <?php if(old('_jobtitle_id')==$val->id): ?> selected <?php endif; ?>><?php echo $val->_name ?? ''; ?></option>
                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                  <?php endif; ?>
                                </select>
                            </div>
                        </div>
                </div>
                     
                      
                      
                      
                      <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <label><?php echo e(__('label._status')); ?>:</label>
                                <select class="form-control" name="_status">
                                  <option value="1">Active</option>
                                  <option value="0">In Active</option>
                                </select>
                            </div>
                        </div>
                
                        <div class="col-xs-12 col-sm-12 col-md-12  text-middle">
                            <button type="submit" class="btn btn-success  ml-5"><i class="fa fa-credit-card mr-2" aria-hidden="true"></i> <?php echo e(__('label.save')); ?></button>
                           
                        </div>
                        <br><br>
                    
                 
                    
                    
                     
                    <?php echo Form::close(); ?>

                
              </div>
          
          </div>
        </div>
        <!-- /.row -->
      </div>
    </div>  
</div>



<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>

<script type="text/javascript">


 

  

  

     

         

</script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\own\inv-acc-hrm\resources\views/hrm/hrm-employee/create.blade.php ENDPATH**/ ?>