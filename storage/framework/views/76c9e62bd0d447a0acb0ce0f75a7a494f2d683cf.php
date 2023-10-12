

<?php $__env->startSection('title',$page_name); ?>

<?php $__env->startSection('content'); ?>
<style type="text/css">
 
  @media  print {
   .table th {
    vertical-align: top;
    color: #000;
    background-color: #fff; 
}
}
  </style>
<div class="_report_button_header">
    <a class="nav-link"  href="<?php echo e(url('hrm-emp-location')); ?>" role="button"><i class="fa fa-arrow-left"></i></a>
 <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('hrm-emp-location-edit')): ?>
 <a  href="<?php echo e(route('hrm-emp-location.edit',$data->id)); ?>" 
    class="nav-link "  title="Edit"  >
    <i class="nav-icon fas fa-edit"></i>
     </a>
  <?php endif; ?>
    <a style="cursor: pointer;" class="nav-link"  title="Print" onclick="javascript:printDiv('printablediv')"><i class="fas fa-print"></i></a>
      <a style="cursor: pointer;" onclick="fnExcelReport();" class="nav-link"  title="Excel Download" ><i class="fa fa-file-excel" aria-hidden="true"></i></a>
      <?php echo $__env->make('backend.message.message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
  </div>

<section class="invoice" id="printablediv">
    
    <div class="container-fluid">
     
    <!-- /.row -->
    <table class="table">
      <tr>
        <td><?php echo e(__('label._id')); ?>:</td>
        <td><?php echo e($data->id ?? ''); ?></td>
      </tr>
      <tr>
        <td><?php echo e(__('label._name')); ?>:</td>
        <td><?php echo e($data->_name ?? ''); ?></td>
      </tr>
      
      <tr>
        <td><?php echo e(__('label._status')); ?>:</td>
        <td><?php echo e(selected_status($data->_status)); ?></td>
      </tr>
      
    </table>
    
    

    

    
    </div>
  </section>


<!-- Page specific script -->

<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\own\inv-acc-hrm\resources\views/hrm/hrm-emp-location/show.blade.php ENDPATH**/ ?>