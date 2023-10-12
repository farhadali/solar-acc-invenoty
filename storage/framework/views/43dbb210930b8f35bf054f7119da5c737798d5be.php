
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
    <a class="nav-link"  href="<?php echo e(url('stock-value-register')); ?>" role="button">
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
                 <tr style="line-height: 16px;" > <td class="text-center" style="border:none;"><strong>As on Date:<?php echo e($previous_filter["_datex"] ?? ''); ?> </strong></td> </tr>
                 <tr style="line-height: 16px;" > <td class="text-center" style="border:none;">
                  <br/><b><?php $__currentLoopData = $permited_branch; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p_branch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
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
             

            <th>Inventory </th>
            <th style="width: 10%;">ID</th>
            <th style="width: 10%;">Date</th>
            <th style="width: 10%;">Unit</th>
            <th style="width: 10%;" class="text-right">Stock In</th>
            <th style="width: 10%;" class="text-right">Stock Out</th>
            <th style="width: 10%;" class="text-right">Balance</th>
          </tr>
          
          
          </thead>
          <tbody>
            <?php
             
              $_total_stockin = 0;
              $_total_stockout = 0;
              $_total_balance = 0;
               $remove_duplicate_branch=array();
            ?>
            <?php $__empty_1 = true; $__currentLoopData = $group_array_values; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$_detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <?php
              $key_arrays = explode("__",$key);
             $_branch_id =  $key_arrays[0];
             $_cost_center_id =  $key_arrays[1];
             $_store_id =  $key_arrays[2];
             $_category_id =  $key_arrays[3];
             $_item_id =  $key_arrays[4];
              ?>
             <?php if(!in_array($key,$remove_duplicate_branch)): ?>
            <tr>
              <?php
                array_push($remove_duplicate_branch,$key);
              ?>
              <th colspan="7">





            <?php if(sizeof($_branch_ids) > 1 ): ?>
              <?php echo e(_branch_name($_branch_id)); ?> |
             <?php endif; ?>
             <?php if(sizeof($_cost_center_ids) > 1 ): ?>
                <?php echo e(_cost_center_name($_cost_center_id)); ?> |
             <?php endif; ?>
             <?php if(sizeof($_stores) > 1 ): ?>
                <?php echo e(_store_name($_store_id)); ?> |
             <?php endif; ?>
             <?php if(sizeof($category_ids) > 1 ): ?>
                <?php echo e(_category_name($_category_id)); ?> |
             <?php endif; ?>
              <?php echo e(_item_name($_item_id)); ?>

              </th>
            </tr>
            <?php endif; ?>

            <?php
              $_sub_total_stockin = 0;
              $_sub_total_stockout = 0;
              $_sub_total_balance = 0;
              $row_counter =0;
            ?>
            <?php $__empty_2 = true; $__currentLoopData = $_detail; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $g_value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>

            <?php
              $row_counter +=1;
              $_total_stockin += $g_value->_stockin;
              $_total_stockout += $g_value->_stockout;
              $_total_balance += ($g_value->_balance);

              $_sub_total_stockin += $g_value->_stockin;
              $_sub_total_stockout += $g_value->_stockout;
              $_sub_total_balance += ($g_value->_balance);
            ?>
            <tr>
             

            <td><?php echo $g_value->_transection ?? ''; ?> </td>
            <td style="width: 10%;">
               <?php if($g_value->_transection=="Purchase"): ?>
                 <a style="text-decoration: none;" target="__blank" href="<?php echo e(url('purchase/print',$g_value->_transection_ref)); ?>">
                  P-<?php echo $g_value->_transection_ref ?? ''; ?></a>
                    <?php endif; ?>
                   
                    <?php if($g_value->_transection=="Purchase Return"): ?>
                 <a style="text-decoration: none;" target="__blank" href="<?php echo e(url('purchase-return/print',$g_value->_transection_ref)); ?>">
                  PR-<?php echo $g_value->_transection_ref ?? ''; ?></a>
                    <?php endif; ?>
                    
                    <?php if($g_value->_transection=="Sales"): ?>
                 <a style="text-decoration: none;" target="__blank" href="<?php echo e(url('sales/print',$g_value->_transection_ref)); ?>">
                  S-<?php echo $g_value->_transection_ref ?? ''; ?></a>
                    <?php endif; ?>
                    
                    <?php if($g_value->_transection=="Restaurant Sales"): ?>
                 <a style="text-decoration: none;" target="__blank" href="<?php echo e(url('restaurant-sales/print',$g_value->_transection_ref)); ?>">
                  S-<?php echo $g_value->_transection_ref ?? ''; ?></a>
                    <?php endif; ?>
                    <?php if($g_value->_transection=="Sales Return"): ?>
                 <a style="text-decoration: none;" target="__blank" href="<?php echo e(url('sales-return/print',$g_value->_transection_ref)); ?>">
                  SR-<?php echo $g_value->_transection_ref ?? ''; ?></a>
                    <?php endif; ?>
                    <?php if($g_value->_transection=="Kitchen"): ?>
                 <a style="text-decoration: none;" target="__blank" href="<?php echo e(url('restaurant-sales/print',$g_value->_transection_ref)); ?>">
                  KT-<?php echo $g_value->_transection_ref ?? ''; ?></a>
                    <?php endif; ?>
                    <?php if($g_value->_transection=="Damage"): ?>
                 <a style="text-decoration: none;" target="__blank" href="<?php echo e(url('damage/print',$g_value->_transection_ref)); ?>">
                  D-<?php echo $g_value->_transection_ref ?? ''; ?></a>
                    <?php endif; ?>
                    <?php if($g_value->_transection=="transfer"): ?>
                 <a style="text-decoration: none;" target="__blank" href="<?php echo e(url('transfer/print',$g_value->_transection_ref)); ?>">
                  TF-<?php echo $g_value->_transection_ref ?? ''; ?></a>
                    <?php endif; ?>
                    <?php if($g_value->_transection=="transfer in"): ?>
                 <a style="text-decoration: none;" target="__blank" href="<?php echo e(url('transfer/print',$g_value->_transection_ref)); ?>">
                  TF-<?php echo $g_value->_transection_ref ?? ''; ?></a>
                    <?php endif; ?>
                    <?php if($g_value->_transection=="production"): ?>
                 <a style="text-decoration: none;" target="__blank" href="<?php echo e(url('production/print',$g_value->_transection_ref)); ?>">
                  PD-<?php echo $g_value->_transection_ref ?? ''; ?></a>
                    <?php endif; ?>
                    <?php if($g_value->_transection=="production in"): ?>
                 <a style="text-decoration: none;" target="__blank" href="<?php echo e(url('production/print',$g_value->_transection_ref)); ?>">
                  PD-<?php echo $g_value->_transection_ref ?? ''; ?></a>
                    <?php endif; ?>
                    <?php if($g_value->_transection=="Replacement"): ?>
                 <a style="text-decoration: none;" target="__blank" href="<?php echo e(url('item-replace/print',$g_value->_transection_ref)); ?>">
                  RP-<?php echo $g_value->_transection_ref ?? ''; ?></a>
                    <?php endif; ?>
                    <?php if($g_value->_transection=="Replacement In"): ?>
                 <a style="text-decoration: none;" target="__blank" href="<?php echo e(url('item-replace/print',$g_value->_transection_ref)); ?>">
                  RP-<?php echo $g_value->_transection_ref ?? ''; ?></a>
                    <?php endif; ?>

              </td>
            <td style="width: 10%;"><?php echo _view_date_formate($g_value->_date ?? ''); ?></td>
            <td style="width: 10%;"><?php echo _find_unit($g_value->_unit_id); ?></td>
            <td style="width: 10%;" class="text-right"><?php echo _report_amount($g_value->_stockin); ?></td>
            <td style="width: 10%;" class="text-right"><?php echo _report_amount($g_value->_stockout); ?></td>
            <td style="width: 10%;" class="text-right"><?php echo e(_report_amount( $_sub_total_balance)); ?></td>
          </tr>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
          <?php endif; ?>
<?php if($row_counter > 1): ?>
          <tr>
           

            <th colspan="3" class="text-left" >Sub Total </th>
            <th style="width: 10%;" class="text-right"><?php echo _report_amount($_sub_total_stockin); ?></th>
            <th style="width: 10%;" class="text-right"><?php echo _report_amount($_sub_total_stockout); ?></th>
            <th style="width: 10%;" class="text-right"><?php echo _report_amount($_sub_total_balance); ?></th>
          </tr>
<?php endif; ?>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
          <?php endif; ?>
          <tr>
           

            <th colspan="3" class="text-left">Grand Total </th>
            <th style="width: 10%;" class="text-right"><?php echo _report_amount($_total_stockin); ?></th>
            <th style="width: 10%;" class="text-right"><?php echo _report_amount($_total_stockout); ?></th>
            <th style="width: 10%;" class="text-right"><?php echo _report_amount($_total_balance); ?></th>
          </tr>
            
            
          </tbody>
          <tfoot>
            <tr>
              <td colspan="9">
                 <?php echo $__env->make('backend.message.invoice_footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
              </td>
            </tr>
          </tfoot>
        </table>
  </section>

</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/sobuz23/sspf.sobuzsathitraders.com/resources/views/backend/inventory-report/report_stock_value_register.blade.php ENDPATH**/ ?>