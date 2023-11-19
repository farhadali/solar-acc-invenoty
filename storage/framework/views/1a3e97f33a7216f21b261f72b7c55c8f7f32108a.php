
<?php $__env->startSection('title',$page_name ?? ''); ?>
<?php $__env->startSection('content'); ?>
<div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <a class="m-0 _page_name" href="<?php echo e(route('item-type.index')); ?>"> <?php echo $page_name ?? ''; ?> </a>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item active">
                 <a class="btn btn-primary" href="<?php echo e(route('item-type.index')); ?>"> <i class="fa fa-th-list" aria-hidden="true"></i> </a>
               </li>
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
               
                  <?php echo Form::model($data, ['method' => 'PATCH','route' => ['item-type.update', $data->id]]); ?> 
                    <?php echo csrf_field(); ?>
                    <div class="row">
                       <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <label><?php echo e(__('label._name')); ?>:</label>
                                <?php echo Form::text('_name', null, array('placeholder' => 'Name','class' => 'form-control','required' => 'true')); ?>

                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <label><?php echo e(__('label._code')); ?>:</label>
                                <?php echo Form::text('_code', null, array('placeholder' => __('label._code'),'class' => 'form-control')); ?>

                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <label><?php echo e(__('label._detail')); ?>:</label>
                                <?php echo Form::text('_detail', null, array('placeholder' => __('label._detail'),'class' => 'form-control')); ?>

                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-2">
                            <div class="form-group">
                                <label for="_status"><?php echo e(__('label._status')); ?>:</label>
                                <select class="form-control" name="_status" id="_status">
                                  <option value="1" <?php if($data->_status==1): ?> selected <?php endif; ?> >Active</option>
                                  <option value="0" <?php if($data->_status==0): ?> selected <?php endif; ?> >In Active</option>
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

<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\own\inv-acc-hrm\resources\views/backend/item-type/edit.blade.php ENDPATH**/ ?>