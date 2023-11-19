
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
    <a class="nav-link"  href="<?php echo e(url('trail-balance')); ?>" role="button">
          <i class="fas fa-search"></i>
        </a>
         <a style="cursor: pointer;" class="nav-link"  title="" data-caption="Print"  onclick="javascript:printDiv('printablediv')"
    data-original-title="Print"><i class="fas fa-print"></i></a>
    <a style="cursor: pointer;" onclick="fnExcelReport();" class="nav-link"  title="Excel Download" ><i class="fa fa-file-excel" aria-hidden="true"></i></a>
  </div>

<section class="invoice" id="printablediv">
    
  <table width="100%">
<tr><td colspan="8" align="center" style="font-size:24px; font-weight:bold"><?php echo e($settings->name ?? ''); ?></td></tr>
<tr><td colspan="8" align="center" style="font-size:16px;"><?php echo e($settings->_address ?? ''); ?></td></tr>
<tr><td colspan="8" align="center" style="font-size:16px;"><?php echo e($settings->_phone ?? ''); ?>,<?php echo e($settings->_email ?? ''); ?></td></tr>
<tr><td colspan="8" align="center" style="font-size:16px; font-weight:bold"><?php echo e($page_name); ?> </td></tr>
<tr><td colspan="8" align="center" style="font-size:12px; font-weight:bold">As on Date :&nbsp;<?php echo e($previous_filter["_datex"] ?? ''); ?></td></tr>
<tr><td colspan="8"><?php $__currentLoopData = $permited_branch; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p_branch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                      <?php if(isset($previous_filter["_branch_id"])): ?>
                        <?php if(in_array($p_branch->id,$previous_filter["_branch_id"])): ?> 
                       <span style="background: #f4f6f9;margin-right: 2px;padding: 5px;"><b><?php echo e($p_branch["_name"]); ?></b></span>    
                        <?php endif; ?>
                      <?php endif; ?>
                      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></td></tr>
</table>
    

    <!-- Table row -->
    <table class="cewReportTable">
          <thead>
          <tr>
            <th style="width: 5%;">ID</th>
            <th style="width: 35%;">Ledger</th>
            <th style="width: 10%;" class="text-right">Opening DR</th>
            <th style="width: 10%;" class="text-right">Opening CR</th>
            <th style="width: 10%;" class="text-right" >Current DR</th>
            <th style="width: 10%;" class="text-right" >Current Cr</th>
            <th style="width: 10%;" class="text-right" >Closing DR</th>
            <th style="width: 10%;" class="text-right" >Closing CR</th>
          </tr>
          
          
          </thead>
          <tbody>
            <?php
            $_grand_total_opening_dr = 0;
            $_grand_total_opening_cr = 0;
            $_grand_total_current_dr = 0;
            $_grand_total_current_cr = 0;
            $_grand_total_closing_dr = 0;
            $_grand_total_closing_cr = 0;
            ?>
            <?php $__empty_1 = true; $__currentLoopData = $group_array_values; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
              
                <td colspan="8" >
                  
                     <b> <?php echo e($key ?? ''); ?> </b>
                    
                
              
              </td>
            </tr>
             <?php
                    $_running_sub_opening_group_dr = 0;
                    $_running_sub_opening_group_cr = 0;
                    $_running_sub_current_group_dr = 0;
                    $_running_sub_current_group_cr = 0;
                    $_running_sub_closing_group_dr = 0;
                    $_running_sub_closing_group_cr = 0;
                  ?>
                <?php $__empty_2 = true; $__currentLoopData = $value; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $l_key=>$l_val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>

               
                  <?php $__empty_3 = true; $__currentLoopData = $l_val; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $_dkey=>$detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_3 = false; ?>
                  <?php
                    

                     $_grand_total_opening_dr +=$detail->_o_dr_amount ?? 0;
                    $_grand_total_opening_cr +=$detail->_o_cr_amount ?? 0;
                    $_grand_total_current_dr +=$detail->_c_dr_amount ?? 0;
                    $_grand_total_current_cr +=$detail->_c_cr_amount ?? 0;
                    $_grand_total_closing_dr += ( $detail->_o_dr_amount +$detail->_c_dr_amount);
                    $_grand_total_closing_cr += ($detail->_o_cr_amount+$detail->_c_cr_amount);

                    $_running_sub_opening_group_dr  +=$detail->_o_dr_amount ?? 0;
                    $_running_sub_opening_group_cr +=$detail->_o_cr_amount ?? 0;
                    $_running_sub_current_group_dr +=$detail->_c_dr_amount ?? 0;
                    $_running_sub_current_group_cr +=$detail->_c_cr_amount ?? 0;
                    $_running_sub_closing_group_dr += ( $detail->_o_dr_amount +$detail->_c_dr_amount);
                    $_running_sub_closing_group_cr += ($detail->_o_cr_amount+$detail->_c_cr_amount);

                  ?>
                  
                    <tr>
                    
                    <td style="text-align: left;">&nbsp; &nbsp;<?php echo e($detail->_account_ledger ?? ''); ?> </td>
                    <td style="text-align: left;"><?php echo e($detail->_l_name ?? ''); ?> </td>
                    <td style="text-align: right;"><?php echo e(_report_amount($detail->_o_dr_amount ?? 0)); ?> </td>
                    <td style="text-align: right;"><?php echo e(_report_amount($detail->_o_cr_amount ?? 0)); ?> </td>
                    <td style="text-align: right;"><?php echo e(_report_amount($detail->_c_dr_amount ?? 0)); ?> </td>
                    <td style="text-align: right;"><?php echo e(_report_amount($detail->_c_cr_amount ?? 0)); ?> </td>
                    <td style="text-align: right;"><?php echo e(_report_amount(  $_running_sub_closing_group_dr )); ?> </td>
                    <td style="text-align: right;"><?php echo e(_report_amount(  $_running_sub_closing_group_cr )); ?> </td>

                  </tr>

                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_3): ?>
                  <?php endif; ?>

                 
                

                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                <?php endif; ?>



              <tr>
              
                <td colspan="2" style="text-align: left;background: #f5f9f9;">&nbsp; &nbsp;Sub Total of <?php echo e($key ?? ''); ?>:  </td>
                <td style="text-align: right;background: #f5f9f9;"> <b><?php echo e(_report_amount($_running_sub_opening_group_dr ?? 0)); ?> </b> </td>
                <td style="text-align: right;background: #f5f9f9;"> <b><?php echo e(_report_amount($_running_sub_opening_group_cr ?? 0)); ?></b> </td>
                <td style="text-align: right;background: #f5f9f9;"> <b><?php echo e(_report_amount($_running_sub_current_group_dr ?? 0)); ?></b> </td>
                <td style="text-align: right;background: #f5f9f9;"> <b><?php echo e(_report_amount($_running_sub_current_group_cr ?? 0)); ?></b> </td>
                <td style="text-align: right;background: #f5f9f9;"> <b><?php echo e(_report_amount($_running_sub_closing_group_dr ?? 0)); ?></b> </td>
                <td style="text-align: right;background: #f5f9f9;"> <b><?php echo e(_report_amount($_running_sub_closing_group_cr ?? 0)); ?></b> </td>

            </tr>
           

            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <?php endif; ?>
          <tr>
              
                <td colspan="2" style="text-align: left;background: #f5f9f9;"> &nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;<b>Grand Total </b> </td>
                <td style="text-align: right;background: #f5f9f9;"> <b><?php echo e(_report_amount($_grand_total_opening_dr)); ?></b> </td>
                <td style="text-align: right;background: #f5f9f9;"> <b><?php echo e(_report_amount($_grand_total_opening_cr)); ?></b> </td>
                <td style="text-align: right;background: #f5f9f9;"> <b><?php echo e(_report_amount($_grand_total_current_dr)); ?></b> </td>
                <td style="text-align: right;background: #f5f9f9;"> <b><?php echo e(_report_amount($_grand_total_current_cr)); ?></b> </td>
                <td style="text-align: right;background: #f5f9f9;"> <b><?php echo e(_report_amount($_grand_total_closing_dr)); ?></b> </td>
                <td style="text-align: right;background: #f5f9f9;"> <b><?php echo e(_report_amount($_grand_total_closing_cr)); ?></b> </td>
                
            </tr>
          </tbody>
          <tfoot>
            <tr>
              <td colspan="8">
                <div class="row">
                   <?php echo $__env->make('backend.message.invoice_footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                </div>
              </td>
            </tr>
          </tfoot>
        </table>


    <!-- /.row -->
  </section>

</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\own\inv-acc-hrm\resources\views/backend/account-report/trail-balance-report.blade.php ENDPATH**/ ?>