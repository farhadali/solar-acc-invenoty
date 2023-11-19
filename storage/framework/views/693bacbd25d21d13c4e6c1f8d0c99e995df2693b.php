
<?php $__env->startSection('title',$page_name ?? ''); ?>
<?php $__env->startSection('content'); ?>
<div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <a class="m-0 _page_name" href="<?php echo e(route('initial-salary-structure.index')); ?>"><?php echo $page_name ?? ''; ?> </a>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
             <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('initial-salary-structure-list')): ?>
              <li class="breadcrumb-item active">
                 <a class="btn btn-info" href="<?php echo e(route('initial-salary-structure.index')); ?>"> <i class="fa fa-th-list" aria-hidden="true"></i></a>
               </li>
               <?php endif; ?>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    
    <div class="content">
      <div class="container-fluid">
<div class="card ">
<div class="card-body">
                 <?php echo $__env->make('backend.message.message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <?php echo Form::open(array('route' => 'initial-salary-structure.store','method'=>'POST','enctype'=>'multipart/form-data')); ?>

                <div class="form-group row pt-2">
                            <label class="col-sm-2 col-form-label" ><?php echo e(__('Employee')); ?>:</label>
                             <div class="col-sm-6">
                                <input type="hidden" name="_employee_id" class="_employee_id" value="">
                                <input type="hidden" name="_employee_ledger_id" class="_employee_ledger_id" value="">
                                <input type="text" name="_employee_id_text" class="form-control _employee_id_text" placeholder="<?php echo e(__('Employee')); ?>">
                            </div>
                        </div>
                <div class="row">
                    <?php $__empty_1 = true; $__currentLoopData = $payheads; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p_key=>$p_val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="col-md-4 ">
                        <h3><?php echo $p_key ?? ''; ?></h3>
                        <?php if(sizeof($p_val) > 0): ?>
                            <?php $__empty_2 = true; $__currentLoopData = $p_val; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $l_val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                            <div class="form-group row ">
                            <label class="col-sm-6 col-form-label" for="_item"><?php echo e($l_val->_ledger ?? ''); ?>:</label>
                             <div class="col-sm-6">
                                <input type="hidden" name="_payhead_id[]" class="_payhead_id" value="<?php echo e($l_val->id); ?>">
                                <input type="hidden" name="_payhead_type_id[]" class="_payhead_type_id" value="<?php echo e($l_val->_type); ?>">
                              <input type="text"  name="_amount" class="form-control" value="0" placeholder="<?php echo e(__('label._amount')); ?>" >
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                        <?php endif; ?>
                        <?php endif; ?>
                    </div>

                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <?php endif; ?>
                </div>

              


<div class="col-xs-12 col-sm-12 col-md-12 bottom_save_section text-middle">
                            <button type="submit" class="btn btn-success submit-button ml-5" ><i class="fa fa-credit-card mr-2" aria-hidden="true"></i> Save</button>
                            
                        </div>


</form> <!-- End of form -->
</div><!-- End of Card body -->
</div><!-- End of Card -->
</div><!-- End of Container -->
</div><!-- End of Content -->



<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>

</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\own\inv-acc-hrm\resources\views/hrm/initial-salary-structure/create.blade.php ENDPATH**/ ?>