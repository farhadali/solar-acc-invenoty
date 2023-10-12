
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
    <a class="nav-link"  href="<?php echo e(url('bill-party-statement')); ?>" role="button">
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
                 <tr style="line-height: 16px;" > <td class="text-center" style="border:none;"><strong>Date:<?php echo e($previous_filter["_datex"] ?? ''); ?> To <?php echo e($previous_filter["_datey"] ?? ''); ?></strong></td> </tr>
                 <tr style="line-height: 16px;" > <td class="text-center" style="border:none;">
                  <br/><b>
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
            <th style="width: 15%;">Date</th>
            <th style="width: 10%;">ID</th>
            <th style="width: 20%;">Short Narration</th>
            <th style="width: 25%;">Narration</th>
            <th style="width: 10%;" class="text-right" >Dr. Amount</th>
            <th style="width: 10%;" class="text-right" >Cr. Amount</th>
            <th style="width: 10%;" class="text-right" >Balance</th>
          </tr>
          
          
          </thead>
          <tbody>
            <?php
            $_dr_grand_total = 0;
            $_cr_grand_total = 0;
            ?>
            <?php $__empty_1 = true; $__currentLoopData = $group_array_values; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
              
                <td colspan="7" style="text-align: left;background: #f5f9f9;">
                  
                     <b> <?php echo e($key ?? ''); ?> </b>
                    
                
              
              </td>
            </tr>
                <?php $__empty_2 = true; $__currentLoopData = $value; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $l_key=>$l_val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>

               
                  <tr>
                    <td colspan="7" style="text-align: left;">
                     
                        <b>  <?php echo e($l_key ?? ''); ?> </b>
                        
                     </td>
                  </tr>
                  <?php
                    $running_sub_dr_total=0;
                    $running_sub_cr_total=0;
                    $runing_balance_total = 0;
                  ?>
                  <?php $__empty_3 = true; $__currentLoopData = $l_val; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $_dkey=>$detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_3 = false; ?>
                  <?php
                    $_dr_grand_total +=$detail->_dr_amount ?? 0;
                    $_cr_grand_total +=$detail->_cr_amount ?? 0;
                    $running_sub_dr_total +=$detail->_dr_amount ?? 0;
                    $running_sub_cr_total +=$detail->_cr_amount ?? 0;
                    $runing_balance_total += (($detail->_balance+$detail->_dr_amount)-$detail->_cr_amount);
                  ?>
                  
                    <tr>
                    <td style="text-align: left;">
                      
                      <?php echo e(_view_date_formate($detail->_date ?? $_datex)); ?> </td>
                    <td class="text-center">
                    <?php if($detail->_table_name=="voucher_masters"): ?>
                 <a style="text-decoration: none;" target="__blank" href="<?php echo e(route('voucher.show',$detail->_id)); ?>">
                  A-<?php echo $detail->_id ?? ''; ?></a>
                    <?php endif; ?>
                    <?php if($detail->_table_name=="purchases"): ?>
                 <a style="text-decoration: none;" target="__blank" href="<?php echo e(url('purchase/print',$detail->_id)); ?>">
                  P-<?php echo $detail->_id ?? ''; ?></a>
                    <?php endif; ?>
                    <?php if($detail->_table_name=="purchase_accounts"): ?>
                 <a style="text-decoration: none;" target="__blank" href="<?php echo e(url('purchase/print',$detail->_id)); ?>">
                  PA-<?php echo $detail->_id ?? ''; ?></a>
                    <?php endif; ?>
                    <?php if($detail->_table_name=="purchases_return"): ?>
                 <a style="text-decoration: none;" target="__blank" href="<?php echo e(url('purchase-return/print',$detail->_id)); ?>">
                  PR-<?php echo $detail->_id ?? ''; ?></a>
                    <?php endif; ?>
                    <?php if($detail->_table_name=="purchase_return_accounts"): ?>
                 <a style="text-decoration: none;" target="__blank" href="<?php echo e(url('purchase-return/print',$detail->_id)); ?>">
                  PRA-<?php echo $detail->_id ?? ''; ?></a>
                    <?php endif; ?>
                    <?php if($detail->_table_name=="sales"): ?>
                 <a style="text-decoration: none;" target="__blank" href="<?php echo e(url('sales/print',$detail->_id)); ?>">
                  S-<?php echo $detail->_id ?? ''; ?></a>
                    <?php endif; ?>
                    <?php if($detail->_table_name=="sales_accounts"): ?>
                 <a style="text-decoration: none;" target="__blank" href="<?php echo e(url('sales/print',$detail->_id)); ?>">
                  SA-<?php echo $detail->_id ?? ''; ?></a>
                    <?php endif; ?>
                    <?php if($detail->_table_name=="sales_return"): ?>
                 <a style="text-decoration: none;" target="__blank" href="<?php echo e(url('sales-return/print',$detail->_id)); ?>">
                  SR-<?php echo $detail->_id ?? ''; ?></a>
                    <?php endif; ?>
                    <?php if($detail->_table_name=="sales_return_accounts"): ?>
                 <a style="text-decoration: none;" target="__blank" href="<?php echo e(url('sales-return/print',$detail->_id)); ?>">
                  SRA-<?php echo $detail->_id ?? ''; ?></a>
                    <?php endif; ?>
                    <?php if($detail->_table_name=="damage"): ?>
                 <a style="text-decoration: none;" target="__blank" href="<?php echo e(url('damage/print',$detail->_id)); ?>">
                  DM-<?php echo $detail->_id ?? ''; ?></a>
                    <?php endif; ?>
                    <?php if($detail->_table_name=="replacement_masters"): ?>
                       <a style="text-decoration: none;" target="__blank" href="<?php echo e(url('item-replace/print',$detail->_id)); ?>">
                        RP-<?php echo $detail->_id ?? ''; ?></a>
                   <?php endif; ?>
                   <?php if($detail->_table_name=="replacement_item_accounts"): ?>
                       <a style="text-decoration: none;" target="__blank" href="<?php echo e(url('item-replace/print',$detail->_id)); ?>">
                        RP-<?php echo $detail->_id ?? ''; ?></a>
                   <?php endif; ?>

                   <?php if($detail->_table_name=="warranty_masters"): ?>
                 <a style="text-decoration: none;" target="__blank" href="<?php echo e(url('warranty-manage/print',$detail->_id)); ?>">
                  WM-<?php echo $detail->_id ?? ''; ?></a>
                    <?php endif; ?>
                   <?php if($detail->_table_name=="resturant_sales_accounts"): ?>
                 <a style="text-decoration: none;" target="__blank" href="<?php echo e(url('restaurant-sales/print',$detail->_id)); ?>">
                  RS-<?php echo $detail->_id ?? ''; ?></a>
                    <?php endif; ?>
                   <?php if($detail->_table_name=="restaurant_sales_accounts"): ?>
                 <a style="text-decoration: none;" target="__blank" href="<?php echo e(url('restaurant-sales/print',$detail->_id)); ?>">
                  RS-<?php echo $detail->_id ?? ''; ?></a>
                    <?php endif; ?>
                   <?php if($detail->_table_name=="resturant_sales"): ?>
                 <a style="text-decoration: none;" target="__blank" href="<?php echo e(url('restaurant-sales/print',$detail->_id)); ?>">
                  RS-<?php echo $detail->_id ?? ''; ?></a>
                    <?php endif; ?>
             </td>
                    <td style="text-align: left;"><?php echo e($detail->_short_narration ?? ''); ?> </td>
                    <td style="text-align: left;"><?php echo e($detail->_narration ?? ''); ?> </td>
                    <td style="text-align: right;"><?php echo e(_report_amount($detail->_dr_amount ?? 0)); ?> </td>
                    <td style="text-align: right;"><?php echo e(_report_amount($detail->_cr_amount ?? 0)); ?> </td>
                    <td style="text-align: right;"><?php echo e(_show_amount_dr_cr(_report_amount(  $runing_balance_total ))); ?> </td>

                  </tr>

                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_3): ?>
                  <?php endif; ?>

                  <tr>
                    <td colspan="4" style="text-align: left;background: #f5f9f9;"> <b>Sub Total of <?php echo e($l_key ?? ''); ?>: </b> </td>
                    <td style="text-align: right;background: #f5f9f9;"><b><?php echo e(_report_amount($running_sub_dr_total ?? 0)); ?></b> </td>
                    <td style="text-align: right;background: #f5f9f9;"><b><?php echo e(_report_amount($running_sub_cr_total ?? 0)); ?></b> </td>
                    <td style="text-align: right;background: #f5f9f9;"><b></b> </td>
                </tr>
                

                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                <?php endif; ?>


            <tr>
                  <td colspan="7"></td>
            </tr>

            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <?php endif; ?>
            <tr>
              
                <td colspan="4" style="text-align: left;background: #f5f9f9;"><b>Grand Total </b> </td>
                <td style="text-align: right;background: #f5f9f9;"> <b><?php echo e(_report_amount($_dr_grand_total)); ?></b> </td>
                <td style="text-align: right;background: #f5f9f9;"> <b><?php echo e(_report_amount($_cr_grand_total)); ?></b> </td>
                <td style="text-align: right;background: #f5f9f9;"> <b><?php echo e(_show_amount_dr_cr(_report_amount($_dr_grand_total-$_cr_grand_total))); ?></b> </td>
            </tr>
          
          </tbody>
          <tfoot>
            <tr>
              <td colspan="7">
                 <?php echo $__env->make('backend.message.invoice_footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
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

<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\own\inv-acc-hrm\resources\views/backend/inventory-report/report_bill_of_party_statement.blade.php ENDPATH**/ ?>