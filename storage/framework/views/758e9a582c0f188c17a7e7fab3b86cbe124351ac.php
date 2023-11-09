
<?php $__env->startSection('title',$page_name ?? ''); ?>
<?php $__env->startSection('content'); ?>
<div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
             <a class="m-0 _page_name" href="<?php echo e(route('store-house.index')); ?>"> <?php echo $page_name ?? ''; ?> </a>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <!-- <li class="breadcrumb-item"><a href="<?php echo e(url('home')); ?>">Home</a></li> -->
              <li class="breadcrumb-item active">
                 <a class="btn btn-info" href="<?php echo e(route('store-house.index')); ?>"> <?php echo e($page_name ?? ''); ?> </a>
               </li>
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
                <?php echo Form::open(array('route' => 'store-house.store','method'=>'POST')); ?>

                    <div class="row">
                       
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <label>Name:</label>
                                <?php echo Form::text('_name', null, array('placeholder' => 'Name','class' => 'form-control','required' => 'true')); ?>

                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <label>Code:</label>
                                <?php echo Form::text('_code', null, array('placeholder' => 'Code','class' => 'form-control')); ?>

                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <label><?php echo e(__('label._address')); ?>:</label>
                                <?php echo Form::text('_address', null, array('placeholder' => __('label._address'),'class' => 'form-control')); ?>

                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <label><?php echo e(__('label._authorised_person')); ?>:</label>
                                <?php echo Form::text('_authorised_person', null, array('placeholder' => __('label._authorised_person'),'class' => 'form-control')); ?>

                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <label><?php echo e(__('label._contact_info')); ?>:</label>
                                <?php echo Form::text('_contact_info', null, array('placeholder' => __('label._contact_info'),'class' => 'form-control')); ?>

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
<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\own\inv-acc-hrm\resources\views/backend/store-house/create.blade.php ENDPATH**/ ?>