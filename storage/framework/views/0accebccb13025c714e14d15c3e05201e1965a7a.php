
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
    <a class="nav-link"  href="<?php echo e(url('stock-possition')); ?>" role="button">
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
             

            <th >Item Name </th>
            <th style="width: 10%;">Unit</th>
            <th style="width: 10%;" class="text-right">Opening</th>
            <th style="width: 10%;" class="text-right">Stock In</th>
            <th style="width: 10%;" class="text-right">Stock Out</th>
            <th style="width: 10%;" class="text-right">Closing</th>
          </tr>
          
          
          </thead>
          <tbody>
            <?php
              $_total_opening = 0;
              $_total_stockin = 0;
              $_total_stockout = 0;
              $_total_balance = 0;
               $remove_duplicate_branch=array();
            ?>
            <?php $__empty_1 = true; $__currentLoopData = $group_array_values; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $branch_key=> $branch_data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
             <?php if(sizeof($_branch_ids) > 1 ): ?>
            <tr>
              <th colspan="7">
                Branch: <?php echo e(_branch_name($branch_key)); ?>

              </th>
            </tr>
            <?php endif; ?>

            <?php $__empty_2 = true; $__currentLoopData = $branch_data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cost_key=> $cost_data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
             <?php if(sizeof($_cost_center_ids) > 1 ): ?>
            <tr>
              <th colspan="7">
                Cost Center: <?php echo e(_cost_center_name($cost_key)); ?>

              </th>
            </tr>
            <?php endif; ?>

             <?php $__empty_3 = true; $__currentLoopData = $cost_data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $store_key=> $store_data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_3 = false; ?>
              <?php if(sizeof($_stores) > 1 ): ?>
             <tr>
              <th colspan="7">
                Store: <?php echo e(_store_name($store_key)); ?>

              </th>
            </tr>
            <?php endif; ?>

             <?php $__empty_4 = true; $__currentLoopData = $store_data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category_key=> $category_data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_4 = false; ?>
             <tr>
              <th colspan="7">
               Category: <?php echo e(_category_name($category_key)); ?>

              </th>
            </tr>


             <?php
              $_sub_total_opening =0;
              $_sub_total_stockin =0;
              $_sub_total_stockout =0;
              $_sub_total_balance =0;
            ?>

             <?php $__empty_5 = true; $__currentLoopData = $category_data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $g_value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_5 = false; ?>

            <?php
             $_sub_total_opening += $g_value->_opening;
              $_sub_total_stockin += $g_value->_stockin;
              $_sub_total_stockout += $g_value->_stockout;
              $_sub_total_balance += ($g_value->_opening+$g_value->_stockin+$g_value->_stockout);

              $_total_opening += $g_value->_opening;
              $_total_stockin += $g_value->_stockin;
              $_total_stockout += $g_value->_stockout;
              $_total_balance += ($g_value->_opening+$g_value->_stockin+$g_value->_stockout);
            ?>
            <tr>
             

            <td><?php echo $g_value->_name ?? ''; ?> </td>
            <td style="width: 10%;"><?php echo $g_value->_unit ?? ''; ?></td>
            <td style="width: 10%;" class="text-right"><?php echo _report_amount($g_value->_opening); ?></td>
            <td style="width: 10%;" class="text-right"><?php echo _report_amount($g_value->_stockin); ?></td>
            <td style="width: 10%;" class="text-right"><?php echo _report_amount($g_value->_stockout); ?></td>
            <td style="width: 10%;" class="text-right"><?php echo e(_report_amount($g_value->_opening+$g_value->_stockin+$g_value->_stockout)); ?></td>
          </tr>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_5): ?>
          <?php endif; ?>

          <tr>
            

            <th colspan="2" class="text-left" >Sub Total </th>
            <th style="width: 10%;" class="text-right"><?php echo _report_amount($_sub_total_opening); ?></th>
            <th style="width: 10%;" class="text-right"><?php echo _report_amount($_sub_total_stockin); ?></th>
            <th style="width: 10%;" class="text-right"><?php echo _report_amount($_sub_total_stockout); ?></th>
            <th style="width: 10%;" class="text-right"><?php echo _report_amount($_sub_total_balance); ?></th>
          </tr>

          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_4): ?>
          <?php endif; ?>

          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_3): ?>
          <?php endif; ?>

          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
          <?php endif; ?>
           

           <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
          <?php endif; ?>
          <tr>
            

            <th colspan="2" class="text-left" >Grand Total </th>
            <th style="width: 10%;" class="text-right"><?php echo _report_amount($_total_opening); ?></th>
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


    
    <!-- /.row -->
  </section>

</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/sobuz23/sspf.sobuzsathitraders.com/resources/views/backend/inventory-report/report_stock_possition.blade.php ENDPATH**/ ?>