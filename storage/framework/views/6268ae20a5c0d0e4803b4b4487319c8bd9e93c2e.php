

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
    <a class="nav-link"  href="<?php echo e(url('approval-chain')); ?>" role="button"><i class="fa fa-arrow-left"></i></a>
 <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('approval-chain-edit')): ?>
 <a  href="<?php echo e(route('approval-chain.edit',$data->id)); ?>" 
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
    <table class="table" style="width:100%;">
      <tr>
                <td colspan="2" style="text-align: center;border: 0px;">
                    <?php echo e($settings->_top_title ?? ''); ?><br>
                   <img src="<?php echo e(url('/')); ?>/<?php echo e($settings->logo); ?>" alt="<?php echo e($settings->name ?? ''); ?>" style="height: 60px;width: 120px"  ><br>
                  <strong><?php echo e($settings->name ?? ''); ?></strong><br>
             <?php echo e($settings->_address ?? ''); ?><br>
            <?php echo e($settings->_phone ?? ''); ?><br>
            <?php echo e($settings->_email ?? ''); ?><br>
            
      
            <b><?php echo e(__('label.approval-chain')); ?></b>
                </td>
              </tr>
      <tr>
        <td><?php echo e(__('label._id')); ?>:</td>
        <td><?php echo e($data->id ?? ''); ?></td>
      </tr>
      <tr>
        <td><?php echo e(__('label._name')); ?>:</td>
        <td><?php echo e($data->chain_name ?? ''); ?></td>
      </tr>
      <tr>
        <td><?php echo e(__('label.chain_type')); ?>:</td>
        <td><?php echo e(selected_access_chain_types($data->chain_type)); ?></td>
      </tr>
      
      <tr>
        <td><?php echo e(__('label.organization_id')); ?>:</td>
        <td><?php echo e($data->_organization->_name ?? ''); ?></td>
      </tr>
      <tr>
        <td><?php echo e(__('label._branch_id')); ?>:</td>
        <td><?php echo e($data->_branch->_name ?? ''); ?></td>
      </tr>
      <tr>
        <td><?php echo e(__('label._cost_center_id')); ?>:</td>
        <td><?php echo e($data->_cost_center->_name ?? ''); ?></td>
      </tr>
      <tr>
        <td><?php echo e(__('label._status')); ?>:</td>
        <td><?php echo e(selected_status($data->_status)); ?></td>
      </tr>
      <tr>
        <td colspan="2"><b><?php echo e(__('label._details')); ?></b></td>
      </tr>
      <tr>
        <td colspan="2">
          <?php
                              $_chain_user = $data->_chain_user ?? [];
                              ?>
                              <?php if(sizeof($_chain_user) > 0): ?>
                              <table class="table">
                                 <thead >
                                            <th class="text-left" ><?php echo e(__('label.id')); ?></th>
                                            <th class="text-left" ><?php echo e(__('label._name')); ?></th>
                                            <th class="text-left" ><?php echo e(__('label._group_name')); ?></th>
                                            <th class="text-left" ><?php echo e(__('label._order')); ?></th>
                                          </thead>
                                <?php $__empty_1 = true; $__currentLoopData = $_chain_user; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                  <tr>
                                    <td><?php echo $val->user_id ?? ''; ?></td>
                                    <td><?php echo _find_employee_name($val->user_id ?? ''); ?></td>
                                    <td><?php echo $val->_user_group->_name ?? ''; ?></td>
                                    <td><?php echo $val->_order ?? ''; ?></td>
                                    
                                  </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <?php endif; ?>
                                </table>
                              <?php endif; ?>
        </td>
      </tr>
      
      
    </table>
    
    <div class="col-12 mt-5">
  <div class="row">
    <div class="col-3 text-center " style="margin-bottom: 50px;"><span style="border-bottom: 1px solid #f5f9f9;">Received By</span></div>
    <div class="col-3 text-center " style="margin-bottom: 50px;"><span style="border-bottom: 1px solid #f5f9f9;">Prepared By</span></div>
    <div class="col-3 text-center " style="margin-bottom: 50px;"><span style="border-bottom: 1px solid #f5f9f9;">Checked By</span></div>
    <div class="col-3 text-center " style="margin-bottom: 50px;"><span style="border-bottom: 1px solid #f5f9f9;"> Approved By</span></div>
  </div>
</div>

    

    
    </div>
  </section>


<!-- Page specific script -->

<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\own\inv-acc-hrm\resources\views/rlp-module/approval-chain/show.blade.php ENDPATH**/ ?>