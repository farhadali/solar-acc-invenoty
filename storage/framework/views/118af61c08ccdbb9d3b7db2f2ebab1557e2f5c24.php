
<?php $__env->startSection('title',$page_name); ?>

<?php $__env->startSection('content'); ?>
<div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
           <a class="m-0 _page_name" href="<?php echo e(route('cost-center.index')); ?>"> <?php echo $page_name ?? ''; ?> </a>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <!-- <li class="breadcrumb-item"><a href="<?php echo e(url('home')); ?>">Home</a></li> -->
              <li class="breadcrumb-item active">
                 <a class="btn btn-primary" href="<?php echo e(route('cost-center.index')); ?>"> <?php echo e($page_name ?? ''); ?> </a>
               </li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <?php echo $__env->make('backend.message.message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-12">
            <div class="card">
             
              <div class="card-body">
               
                 <?php echo Form::model($data, ['method' => 'PATCH','route' => ['cost-center.update', $data->id]]); ?>

                    <?php echo csrf_field(); ?>
                    <div class="row">
                       <div class="col-xs-12 col-sm-12 col-md-12">
                        <input type="hidden" name="id" value="<?php echo e($data->id); ?>">
                            <div class="form-group">
                                <label>Branch:</label>
                                
                                <select class="form-control" name="_branch_id" required>
                                  <?php $__empty_1 = true; $__currentLoopData = $branchs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $branch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                  <option value="<?php echo e($branch->id); ?>" <?php if($data->_branch_id==$branch->id): ?> selected <?php endif; ?>><?php echo e($branch->_name ?? ''); ?></option>
                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                  <?php endif; ?>
                                  
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-8 col-sm-8 col-md-8">
                            <div class="form-group">
                                <label>Name:</label>
                                <?php echo Form::text('_name', $data->_name, array('placeholder' => 'Name','class' => 'form-control','required' => 'true')); ?>

                            </div>
                        </div>
                        <div class="col-xs-4 col-sm-4 col-md-4">
                            <div class="form-group">
                                <label>Code:</label>
                                <?php echo Form::text('_code', $data->_code, array('placeholder' => 'Code','class' => 'form-control')); ?>

                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <label><?php echo e(__('label.start_date')); ?>:</label>
                                <?php echo Form::date('_start_date', $data->_start_date, array('placeholder' => __('label.start_date'),'class' => 'form-control')); ?>

                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <label><?php echo e(__('label.end_date')); ?>:</label>
                                <?php echo Form::date('_end_date', $data->_end_date, array('placeholder' => __('label.end_date'),'class' => 'form-control')); ?>

                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <label><?php echo e(__('label.details')); ?>:</label>
                                <textarea class="form-control" name="_detail"><?php echo $data->_detail ?? ''; ?></textarea>
                               
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <label><?php echo e(__('label.condition')); ?>:</label>
                               <select class="form-control" name="_is_close">
                                    <option value="1" <?php if($data->_is_close==1): ?> selected <?php endif; ?> >Running</option>
                                    <option value="2" <?php if($data->_is_close==2): ?> selected <?php endif; ?> >Complete</option>
                                    <option value="3" <?php if($data->_is_close==3): ?> selected <?php endif; ?> >Stop</option>
                               </select>
                               
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <label><?php echo e(__('label._status')); ?>:</label>
                               <select class="form-control" name="_status">
                                    <option value="1" <?php if($data->_status==1): ?> selected <?php endif; ?> >Active</option>
                                    <option value="0" <?php if($data->_status==0): ?> selected <?php endif; ?> >Disable</option>
                               </select>
                               
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

<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\xampp\htdocs\solar-acc-invenoty\resources\views/backend/cost-center/edit.blade.php ENDPATH**/ ?>