
<?php $__env->startSection('title',$page_name ?? ''); ?>

<?php $__env->startSection('css'); ?>
<link rel="stylesheet" href="<?php echo e(asset('backend/new_style.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<?php
$__user= Auth::user();
?>

    <div class="content">
      <div class="container-fluid">
   <h2 class="text-center"><?php echo e(__('label.import_report_dashbaord')); ?></h2>
    <div class="container-fluid   " >
        <div class="row  ">
                <div class="col-md-6">
                    <ul>
                        <li><a target="__blank" href="<?php echo e(url('master_vessel_wise_ligther_report')); ?>"><?php echo e(__('label.master_vessel_wise_ligther_report')); ?></a></li>
                    </ul>
                   
                </div>
        </div>
    </div>
    
</div>
    
    </div>

    <?php $__env->stopSection(); ?>
<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\own\inv-acc-hrm\resources\views/import-report/dashboard.blade.php ENDPATH**/ ?>