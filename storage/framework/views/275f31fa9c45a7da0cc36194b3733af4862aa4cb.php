
<?php $__env->startSection('title',$page_name); ?>

<?php $__env->startSection('content'); ?>
<div class="wrapper print_content">
  <style type="text/css">
  .table td, .table th {
    padding: 0.10rem;
    vertical-align: top;
    border-top: 1px solid #dee2e6;
}
  </style>
<div class="_report_button_header">
    <a class="nav-link"  href="<?php echo e(url('filter-ledger-summary')); ?>" role="button">
          <i class="fas fa-search"></i>
        </a>
 <a style="cursor: pointer;" class="nav-link"  title="Print" onclick="javascript:printDiv('printablediv')"><i class="fas fa-print"></i></a>
      <a style="cursor: pointer;" onclick="fnExcelReport();" class="nav-link"  title="Excel Download" ><i class="fa fa-file-excel" aria-hidden="true"></i></a>
  </div>

<section class="invoice" id="printablediv">
    
    
    
        <table class="table" style="border:none;width: 100%;">
          <tr>
            
            <td style="border:none;width: 100%;text-align: center;">
              <table class="table" style="border:none;">
                <tr style="line-height: 16px;" > <td class="text-center" style="border:none;font-size: 24px;"><b><?php echo e($settings->name ?? ''); ?></b></td> </tr>
                <tr style="line-height: 16px;" > <td class="text-center" style="border:none;"><?php echo e($settings->_address ?? ''); ?></td></tr>
                <tr style="line-height: 16px;" > <td class="text-center" style="border:none;"><?php echo e($settings->_phone ?? ''); ?>,<?php echo e($settings->_email ?? ''); ?></td></tr>
                 <tr style="line-height: 16px;" > <td class="text-center" style="border:none;"><b><?php echo e($page_name); ?> </b></td> </tr>
                 <tr style="line-height: 16px;" > <td class="text-center" style="border:none;"><strong>AS ON Date:<?php echo e(date('d-m-y')); ?> </strong></td> </tr>
                 <tr style="line-height: 16px;" > <td class="text-center" style="border:none;">
                  <br>
                  <b>
                  <?php $__currentLoopData = $permited_branch; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p_branch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                      <?php if(isset($previous_filter["_branch_id"])): ?>
                        <?php if(in_array($p_branch->id,$previous_filter["_branch_id"])): ?> 
                       <span style="background: #f4f6f9;margin-right: 2px;padding: 5px;"><b><?php echo e($p_branch["_name"]); ?></b></span>    
                        <?php endif; ?>
                      <?php endif; ?>
                      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?> </b></td> </tr>
              </table>
            </td>
           
          </tr>
        </table>
       

    <!-- Table row -->
     <table class="cewReportTable">
          <thead>
          <tr>
            
            <th style="border:1px solid silver;" class="text-left" >SL</th>
            <th style="border:1px solid silver;" class="text-left" >Ledger Name</th>
            <th style="border:1px solid silver;" class="text-left" >Address</th>
            <th style="border:1px solid silver;" class="text-left" >Phone</th>
            <th style="border:1px solid silver;" class="text-right" >Amount</th>
          </tr>
          
          
          </thead>
          <tbody>
            <?php
            $_grand_total = 0;
            ?>
           <?php $__empty_1 = true; $__currentLoopData = $group_array_values; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <?php
            $_group_total = 0;
            ?>
           <tr>
             <td colspan="5" class="text-left"><b><?php echo e($key); ?></b></td>
           </tr>
           <?php $__empty_2 = true; $__currentLoopData = $value; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $_ledger_key=>$_ledger_info): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
          
           <?php
            $_group_total += $_ledger_info->_balance ?? 0;
            $_grand_total += $_ledger_info->_balance ?? 0;
            ?>
            <tr>
              <td ><?php echo e(( $_ledger_key +1 )); ?></td>
              <td> <?php echo e($_ledger_info->_l_name ?? ''); ?></td>
              <td> <?php echo e($_ledger_info->_address ?? ''); ?></td>
              <td> <?php echo e($_ledger_info->_phone ?? ''); ?></td>
              <td class="text-right"> <?php echo e(_show_amount_dr_cr(_report_amount( $_ledger_info->_balance ?? 0 ))); ?>  </td>
            </tr>

           <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
           <?php endif; ?>

           <tr>
             <td  colspan="4" class="text-left"><b>Summary of <?php echo e($key); ?></b></td>
             <td class="text-right"><?php echo e(_show_amount_dr_cr(_report_amount(  $_group_total ))); ?> </td>
           </tr>
           <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
           <?php endif; ?>
           <tr>
             <td colspan="4"  class="text-left"><b>Grand Total</b></td>
             <td class="text-right"><?php echo e(_show_amount_dr_cr(_report_amount(  $_grand_total ))); ?> </td>
           </tr>
          
          </tbody>
          
        </table>
      

    
    <!-- /.row -->
  </section>

</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>



<?php $__env->stopSection(); ?>

<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\own\inv-acc-hrm\resources\views/backend/account-report/report_ledger_summary.blade.php ENDPATH**/ ?>