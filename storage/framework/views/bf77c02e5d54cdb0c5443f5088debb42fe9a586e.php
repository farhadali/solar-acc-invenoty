
<?php $__env->startSection('title',$page_name); ?>

<?php $__env->startSection('content'); ?>
<div class="wrapper print_content">
  <style type="text/css">
  .table td, .table th {
    padding: 0.10rem;
    vertical-align: top;
    border: 1px solid #dee2e6;
}
._report_header_tr{
  line-height: 16px !important;
}
  </style>
    <div class="_report_button_header">
      <a class="nav-link"  href="<?php echo e(url('balance-sheet')); ?>" role="button"><i class="fas fa-search"></i></a>
      <a style="cursor: pointer;" class="nav-link"  title="Print" onclick="javascript:printDiv('printablediv')"><i class="fas fa-print"></i></a>
      <a style="cursor: pointer;" onclick="fnExcelReport();" class="nav-link"  title="Excel Download" ><i class="fa fa-file-excel" aria-hidden="true"></i></a>
    
    </div>

<section class="invoice" id="printablediv">
    
   
    <div class="row">
      <div class="col-12">
        <table class="table" style="border:none;">
          <tr>
            <td style="border:none;width: 20%;text-align: left;">
              
            </td>
            <td style="border:none;width: 60%;text-align: center;">
              <table class="table" style="border:none;">
                <tr class="_report_header_tr" > <td class="text-center" style="border:none;font-size: 24px;"><b><?php echo e($settings->name ?? ''); ?></b></td> </tr>
                <tr class="_report_header_tr" > <td class="text-center" style="border:none;"><?php echo e($settings->_address ?? ''); ?></td></tr>
                <tr class="_report_header_tr" > <td class="text-center" style="border:none;"><?php echo e($settings->_phone ?? ''); ?>,<?php echo e($settings->_email ?? ''); ?></td></tr>
                 <tr class="_report_header_tr" > <td class="text-center" style="border:none;"><b><?php echo e($page_name); ?> </b></td> </tr>
                 <tr class="_report_header_tr" > <td class="text-center" style="border:none;"><b>As on Date :&nbsp;<?php echo e($previous_filter["_datex"] ?? ''); ?> </b></td> </tr>
                 <tr class="_report_header_tr" > <td class="text-center" style="border:none;">
                  <br />
                  <b><?php $__currentLoopData = $permited_branch; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p_branch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                      <?php if(isset($previous_filter["_branch_id"])): ?>
                        <?php if(in_array($p_branch->id,$previous_filter["_branch_id"])): ?> 
                       <span style="background: #f4f6f9;margin-right: 2px;padding: 5px;"><b><?php echo e($p_branch["_name"]); ?></b></span>    
                        <?php endif; ?>
                      <?php endif; ?>
                      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?> </b></td> </tr>
              </table>
            </td>
            <td style="border:none;width: 20%;text-align: right;">
              <p class="text-right">Print: <?php echo e(date('d-m-Y H:s:a')); ?></p>
            </td>
          </tr>
        </table>
        </div>
      </div>
    <!-- /.row -->

    <!-- Table row -->
   <table class="cewReportTable">
          <thead>
          <tr>
            <th style="width: 80%;">Particulars</th>
            <th style="width: 20%;padding-right: 10px;" class="text-right" >Amount</th>
          </tr>
          
          
          </thead>
          <tbody>
            <?php
            $total_liabilites = 0;
             $total_liabilites_capital = 0;
             $capital_li=["Capital","Liability"];
           ?>
           <?php $__empty_1 = true; $__currentLoopData = $balance_sheet_filter; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $l_1key=>$l_1_value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
           <?php
            $summary_l1 = 0;
           ?>
                   <tr>
                     <td colspan="2" style="text-align: left;"><b><?php echo $l_1key; ?></b></td>
                   </tr>
                   <?php $__empty_2 = true; $__currentLoopData = $l_1_value; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $l_2key=>$l2value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                   <?php
                    $summary_l2 = 0;
                   ?>
                   <tr>
                     <td colspan="2" style="text-align: left;"><b>&nbsp; &nbsp;<?php echo $l_2key; ?></b></td>
                   </tr>

                   <?php $__empty_3 = true; $__currentLoopData = $l2value; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $l_3key=>$l3value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_3 = false; ?>
                     <?php
                      $summary_l3 = 0;
                     ?>
                     <tr>
                       <td colspan="2" style="text-align: left;"><b>&nbsp; &nbsp;&nbsp; &nbsp;<?php echo $l_3key; ?></b></td>
                     </tr>
                     <?php $__empty_4 = true; $__currentLoopData = $l3value; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $l_4key=>$l_4value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_4 = false; ?>
                     <?php

                      $summary_l1 +=$l_4value->_amount ?? 0; 
                      $summary_l2 +=$l_4value->_amount ?? 0;
                      $summary_l3 +=$l_4value->_amount ?? 0;
                      $total_liabilites +=$l_4value->_amount ?? 0;

                      if(in_array($l_1key,$capital_li)){
                      $total_liabilites_capital += $l_4value->_amount ?? 0;
                    }

                     ?>
                     <?php if( $_with_zero_qty==1 &&  $l_4value->_amount !=0 ): ?>
                          <tr>
                             <td  style="text-align: left;">&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;<?php echo $l_4value->_l_name ?? ''; ?></td>
                             <td style="text-align: right;padding-right: 10px;"><?php echo _show_amount_dr_cr(_report_amount(   $l_4value->_amount ?? 0 )); ?></td>
                           </tr>
                      <?php endif; ?>
                      <?php if( $_with_zero_qty==0 ): ?>
                      <tr>
                             <td  style="text-align: left;">&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;<?php echo $l_4value->_l_name ?? ''; ?></td>
                             <td style="text-align: right;padding-right: 10px;"><?php echo _show_amount_dr_cr(_report_amount(   $l_4value->_amount ?? 0 )); ?></td>
                           </tr>
                      <?php endif; ?>
                     <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_4): ?>
                     <?php endif; ?>
                     
                     <?php if(sizeof($l3value) > 1): ?>
                   <tr>
                     <td style="text-align: left;"><b>&nbsp; &nbsp;&nbsp; &nbsp;Sub Total for <?php echo $l_3key; ?>:</b></td>
                     <td style="text-align: right;padding-right: 10px;"><b> <?php echo _show_amount_dr_cr(_report_amount(  $summary_l3 )); ?></b></td>
                   </tr>
                   <?php endif; ?>

                   <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_3): ?>
                   <?php endif; ?>

                   <?php if(sizeof($l_1_value) > 1): ?>
                   <tr>
                     <td style="text-align: left;"><b>&nbsp; &nbsp;&nbsp; &nbsp;Sub Total for <?php echo $l_2key; ?>:</b></td>
                     <td style="text-align: right;padding-right: 10px;"><b> <?php echo _show_amount_dr_cr(_report_amount(  $summary_l2 )); ?></b></td>
                   </tr>
                   <?php endif; ?>

                   <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                   <?php endif; ?>
                   <tr>
                     <td style="text-align: left;"><b>Summary for <?php echo $l_1key; ?>:</b></td>
                     <td style="text-align: right;padding-right: 10px;"><b> <?php echo _show_amount_dr_cr(_report_amount(  $summary_l1 )); ?></b></td>
                   </tr>
                   

           <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
           <?php endif; ?>
           <?php if($total_liabilites !=0): ?>
           <tr>
                     <td class="text-left">Diffrance of Balance Sheet</td>
                     <td class="text-right"><?php echo e(_show_amount_dr_cr(_report_amount($total_liabilites))); ?></td>
            </tr>
            <?php endif; ?>

            <tr>
                     <td class="text-left"><b>Total Capital & Liabilities</b></td>
                     <td class="text-right"><b><?php echo e(_show_amount_dr_cr(_report_amount($total_liabilites_capital))); ?></b></td>
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

<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\own\inv-acc-hrm\resources\views/backend/account-report/balance-sheet-report.blade.php ENDPATH**/ ?>