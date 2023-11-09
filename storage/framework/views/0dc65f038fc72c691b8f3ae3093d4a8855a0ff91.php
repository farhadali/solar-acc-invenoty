
<?php $__env->startSection('title',$page_name ?? ''); ?>
<?php $__env->startSection('content'); ?>
<div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <a class="m-0 _page_name" href="<?php echo e(route('vessel-info.index')); ?>"> <?php echo $page_name ?? ''; ?> </a>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('vessel-info-list')): ?>
              <li class="breadcrumb-item active">
                 <a class="btn btn-primary" href="<?php echo e(route('vessel-info.index')); ?>"> <i class="fa fa-th-list" aria-hidden="true"></i></a>
               </li>
               <?php endif; ?>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <div class="message-area">
    <?php echo $__env->make('backend.message.message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-12">
            <div class="card">
             
              <div class="card-body">
                <?php echo Form::open(array('route' => 'vessel-info.store','method'=>'POST')); ?>

                    <div class="row">
                       
                        <div class="col-xs-12 col-sm-12 col-md-3">
                            <div class="form-group">
                                <label><?php echo e(__('label._name')); ?>:</label>
                                <?php echo Form::text('_name', null, array('placeholder' => __('label._name'),'class' => 'form-control','required' => 'true')); ?>

                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-3">
                            <div class="form-group">
                                <label><?php echo e(__('label._capacity')); ?>:</label>
                                <?php echo Form::text('_capacity', null, array('placeholder' => __('label._capacity'),'class' => 'form-control')); ?>

                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-3">
                            <div class="form-group">
                                <label><?php echo e(__('label._code')); ?>:</label>
                                <?php echo Form::text('_code', null, array('placeholder' => __('label._code'),'class' => 'form-control')); ?>

                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-3">
                            <div class="form-group">
                                <label><?php echo e(__('label._license_no')); ?>:</label>
                                <?php echo Form::text('_license_no', null, array('placeholder' => __('label._license_no'),'class' => 'form-control')); ?>

                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-3">
                            <div class="form-group">
                                <label><?php echo e(__('label._type')); ?>:</label>
                                <select class="form-control" name="_type">
                                    <?php $__empty_1 = true; $__currentLoopData = _vessel_types(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <option value="<?php echo e($key); ?>"><?php echo e($val); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <?php endif; ?>
                                </select>
                                
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-3">
                            <div class="form-group">
                                <label><?php echo e(__('label._route')); ?>:</label>
                                <?php echo Form::text('_route', null, array('placeholder' => __('label._route'),'class' => 'form-control')); ?>

                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-3">
                            <div class="form-group">
                                <label><?php echo e(__('label._owner_name')); ?>:</label>
                                <?php echo Form::text('_owner_name', null, array('placeholder' => __('label._owner_name'),'class' => 'form-control')); ?>

                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-3">
                            <div class="form-group">
                                <label><?php echo e(__('label._country_name')); ?>:</label>
                                <?php echo Form::text('_country_name', null, array('placeholder' => __('label._country_name'),'class' => 'form-control')); ?>

                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-3">
                            <div class="form-group">
                                <label><?php echo e(__('label._contact_one')); ?>:</label>
                                <?php echo Form::text('_contact_one', null, array('placeholder' => __('label._contact_one'),'class' => 'form-control')); ?>

                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-3">
                            <div class="form-group">
                                <label><?php echo e(__('label._contact_two')); ?>:</label>
                                <?php echo Form::text('_contact_two', null, array('placeholder' => __('label._contact_two'),'class' => 'form-control')); ?>

                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-3">
                            <div class="form-group">
                                <label><?php echo e(__('label._contact_three')); ?>:</label>
                                <?php echo Form::text('_contact_three', null, array('placeholder' => __('label._contact_three'),'class' => 'form-control')); ?>

                            </div>
                        </div>
                        
                       
                        <div class="col-xs-12 col-sm-12 col-md-3">
                            <div class="form-group">
                                <label>Status:</label>
                                <select class="form-control" name="_status">
                                  <option value="1">Active</option>
                                  <option value="0">In Active</option>
                                </select>
                            </div>
                        </div>
                       
                       
                        <div class="col-xs-12 col-sm-12 col-md-12 bottom_save_section text-middle">
                            <button type="submit" class="btn btn-success  ml-5"><i class="fa fa-credit-card mr-2" aria-hidden="true"></i> Save</button>
                           
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
<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\own\inv-acc-hrm\resources\views/backend/vessel-info/create.blade.php ENDPATH**/ ?>