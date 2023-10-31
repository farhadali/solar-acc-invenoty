
<?php $__env->startSection('title',$page_name); ?>

<?php $__env->startSection('content'); ?>
<div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
           <a class="m-0 _page_name" href="<?php echo e(route('account-type.index')); ?>"> <?php echo $page_name ?? ''; ?> </a>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('account-type-list')): ?>
              <li class="breadcrumb-item active">
                 <a class="btn btn-primary" href="<?php echo e(route('account-type.index')); ?>"> <i class="fa fa-th-list" aria-hidden="true"></i></a>
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
                <label>Whoops!</label> There were some problems with your input.<br><br>
                <ul>
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>
    </div>
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-12">
            <div class="card">
             
              <div class="card-body">
                <?php echo Form::open(array('route' => 'account-type.store','method'=>'POST')); ?>

                    <div class="row">
                       <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <label>Account:</label>
                                <select class="form-control" name="_account_id">
                                  <option value="1">Assets</option>
                                  <option value="2">Liability</option>
                                  <option value="3">Income</option>
                                  <option value="4">Expenses</option>
                                  <option value="5">Capital</option>
                                </select>
                            </div>
                        </div>
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
                                <label>Status:</label>
                                <select class="form-control" name="_status">
                                  <option value="1">Active</option>
                                  <option value="0">In Active</option>
                                </select>
                            </div>
                        </div>
                       
                       
                        <div class="col-xs-6 col-sm-6 col-md-6  text-center mt-1">
                            <button type="submit" class="btn btn-success "><i class="fa fa-credit-card mr-2" aria-hidden="true"></i> Save</button>
                            
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
<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\own\inv-acc-hrm\resources\views/backend/account-type/create.blade.php ENDPATH**/ ?>