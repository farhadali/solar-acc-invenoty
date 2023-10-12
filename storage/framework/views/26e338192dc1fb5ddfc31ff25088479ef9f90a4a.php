
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
    <a class="nav-link"  href="<?php echo e(url('work-sheet')); ?>" role="button">
          <i class="fas fa-search"></i>
        </a>
 <a style="cursor: pointer;" class="nav-link"  title="Print" onclick="javascript:printDiv('printablediv')"><i class="fas fa-print"></i></a>
      <a style="cursor: pointer;" onclick="fnExcelReport();" class="nav-link"  title="Excel Download" ><i class="fa fa-file-excel" aria-hidden="true"></i></a>
  </div>

<section class="invoice" id="printablediv">
    <table width="100%">
<tr><td colspan="7" align="center" style="font-size:24px; font-weight:bold"><?php echo e($settings->name ?? ''); ?></td></tr>
<tr><td colspan="7" align="center" style="font-size:16px;"><?php echo e($settings->_address ?? ''); ?></td></tr>
<tr><td colspan="7" align="center" style="font-size:16px;"><?php echo e($settings->_phone ?? ''); ?>,<?php echo e($settings->_email ?? ''); ?></td></tr>
<tr><td colspan="7" align="center" style="font-size:16px; font-weight:bold"><?php echo e($page_name); ?> </td></tr>
<tr><td colspan="7" align="center" style="font-size:12px; font-weight:bold">As on Date :&nbsp;<?php echo e($previous_filter["_datex"] ?? ''); ?></td></tr>
<tr><td colspan="4"><?php $__currentLoopData = $permited_branch; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p_branch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
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
            <th style="text-align: left;border-bottom: none;white-space: nowrap;" >Particulars</th>
            <th style="text-align: center;white-space: nowrap;" colspan="2"> TRIAL BALANCE</th>
            <th style="text-align: center;white-space: nowrap;" colspan="2">INCOME STATEMENT</th>
            <th style="text-align: center;white-space: nowrap;" colspan="2">BALANCE SHEET</th>
          </tr>
          <tr>
            <th style="text-align: right;border-top: none;white-space: nowrap;"> </th>
            <th style="text-align: right;white-space: nowrap;">Dr. Amount </th>
            <th style="text-align: right;white-space: nowrap;">Cr. Amount </th>
            <th style="text-align: right;white-space: nowrap;">Expenses</th>
            <th style="text-align: right;white-space: nowrap;">Income</th>
            <th style="text-align: right;white-space: nowrap;">Assets</th>
            <th style="text-align: right;white-space: nowrap;">Liabilities</th>
          </tr>
          
          
          </thead>
          <tbody>
            <?php
            $trail_dr_total = 0;
            $trail_cr_total = 0;
            $income_dr_total = 0;
            $income_cr_total = 0;
            $balance_dr_total = 0;
            $balance_cr_total = 0;

            ?>
           <?php $__empty_1 = true; $__currentLoopData = $work_sheet_result; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
           <tr>
             <td style="text-align: left;padding-left: 10px;"><?php echo $value->_l_name ?? ''; ?></td>
              <?php if($value->_head_name !=4): ?>
                  <?php if($value->_amount >= 0): ?>
                          <?php
                           $trail_dr_total += $value->_amount;
                          ?>
                    <td style="text-align: right;"> <?php echo e(_report_amount(   $value->_amount )); ?></td>
                    <td style="text-align: right;"><?php echo e(_report_amount( 0 )); ?> </td>
                    <?php else: ?>
                        <?php
                         $trail_cr_total += $value->_amount;
                        ?>
                    <td style="text-align: right;"> <?php echo e(_report_amount( 0 )); ?></td>
                    <td style="text-align: right;"> <?php echo e(_report_amount(   $value->_amount )); ?> </td>
                  <?php endif; ?>
              <?php else: ?>
                <td style="text-align: right;"> <?php echo e(_report_amount( 0 )); ?></td>
                <td style="text-align: right;"> <?php echo e(_report_amount(   0 )); ?> </td>
                <?php if($value->_amount < 0): ?>
                <?php
                    $income_dr_total +=abs($value->_amount);
                    $balance_cr_total +=$value->_amount;
                  ?>
                <td style="text-align: right;"> <?php echo e(_report_amount( abs($value->_amount) )); ?></td>
                 <td style="text-align: right;"> <?php echo e(_report_amount(   0 )); ?> </td>

                <td style="text-align: right;"> <?php echo e(_report_amount( 0 )); ?></td>
                 <td style="text-align: right;"> <?php echo e(_report_amount(   abs($value->_amount) )); ?> </td>
                 <?php else: ?>

                 <?php
                    $income_cr_total += abs($value->_amount);
                    $balance_dr_total +=$value->_amount;
                  ?>
                 <td style="text-align: right;"> <?php echo e(_report_amount( 0 )); ?></td>
                 <td style="text-align: right;"> <?php echo e(_report_amount(  abs($value->_amount) )); ?> </td>

                 <td style="text-align: right;"> <?php echo e(_report_amount( abs($value->_amount) )); ?></td>
                 <td style="text-align: right;"> <?php echo e(_report_amount(  0  )); ?> </td>
                 <?php endif; ?>
              <?php endif; ?>

              <?php if($value->_main_head ==4): ?>
                  <?php
                    $income_dr_total +=$value->_amount;
                  ?>
                 <td style="text-align: right;"> <?php echo e(_report_amount( $value->_amount )); ?></td>
                 <td style="text-align: right;"> <?php echo e(_report_amount(  0  )); ?> </td>
              <?php elseif($value->_main_head ==3): ?>
                  <?php
                    $income_cr_total +=$value->_amount;
                  ?>
                <td style="text-align: right;"> <?php echo e(_report_amount( 0 )); ?></td>
                   <td style="text-align: right;"> <?php echo e(_report_amount(  $value->_amount  )); ?> </td>
              <?php else: ?>
                  <?php if($value->_head_name !=4): ?>
                   <td style="text-align: right;"> <?php echo e(_report_amount( 0 )); ?></td>
                   <td style="text-align: right;"> <?php echo e(_report_amount(  0  )); ?> </td>
                   <?php endif; ?>
              <?php endif; ?>


               <?php if($value->_main_head ==2 || $value->_main_head ==5 || $value->_main_head ==1): ?>

                  <?php if($value->_amount >= 0): ?>
                        <?php
                          $balance_dr_total +=$value->_amount;
                        ?>
                       <td style="text-align: right;"> <?php echo e(_report_amount( $value->_amount )); ?></td>
                       <td style="text-align: right;"> <?php echo e(_report_amount(  0  )); ?> </td>
                  <?php else: ?>
                        <?php
                          $balance_cr_total +=$value->_amount;
                        ?>
                        <td style="text-align: right;"> <?php echo e(_report_amount( 0 )); ?></td>
                        <td style="text-align: right;"> <?php echo e(_report_amount(  $value->_amount  )); ?> </td>
                  <?php endif; ?>



               <?php else: ?>
                    <?php if($value->_head_name !=4): ?>
                   <td style="text-align: right;"> <?php echo e(_report_amount( 0 )); ?></td>
                   <td style="text-align: right;"> <?php echo e(_report_amount(  0  )); ?> </td>
                   <?php endif; ?>
               <?php endif; ?>



             
            
           </tr>


           <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
           <?php endif; ?>
                  
           <tr class="cewSubHeader">
             <td style="text-align: left;padding-left: 10px;">Grand Total</td>
             <td style="text-align: right;font-weight: bold;"><?php echo e(_report_amount(  $trail_dr_total )); ?> </td>
             <td style="text-align: right;font-weight: bold;"> <?php echo e(_report_amount(  $trail_cr_total )); ?></td>
             <td style="text-align: right;font-weight: bold;"><?php echo e(_report_amount(  $income_dr_total )); ?> </td>
             <td style="text-align: right;font-weight: bold;"> <?php echo e(_report_amount(  $income_cr_total )); ?></td>
             <td style="text-align: right;font-weight: bold;"> <?php echo e(_report_amount(  $balance_dr_total )); ?></td>
             <td style="text-align: right;font-weight: bold;"> <?php echo e(_report_amount(  $balance_cr_total )); ?></td>
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

<script type="text/javascript">

 function printDiv(divID) {
            //Get the HTML of div
            var divElements = document.getElementById(divID).innerHTML;
            //Get the HTML of whole page
            var oldPage = document.body.innerHTML;

            //Reset the page's HTML with div's HTML only
            document.body.innerHTML =
                "<html><head><title></title></head><body>" +
                divElements + "</body>";

            //Print Page
            window.print();

            //Restore orignal HTML
            document.body.innerHTML = oldPage;


        }
         

</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/sobuz23/sspf.sobuzsathitraders.com/resources/views/backend/account-report/work-sheet-report.blade.php ENDPATH**/ ?>