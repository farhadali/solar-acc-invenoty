
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
    <a class="nav-link"  href="<?php echo e(url('shortage-item')); ?>" role="button">
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
                 <tr style="line-height: 16px;" > <td class="text-center" style="border:none;"><strong>Date:<?php echo _view_date_formate(date('Y-m-d')); ?> </strong></td> </tr>
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
            
            <th class="text-left">Manufacture Company </th>
            <th class="text-left">Item Name </th>
            <th style="width: 10%;">Unit</th>
            <th style="width: 10%;" class="text-right">Stock </th>
            <th style="width: 10%;" class="text-right">Re order Level </th>
            <th style="width: 10%;" class="text-right">Re order Qty</th>
          </tr>
          
          
          </thead>
          <tbody>
            <?php
             
              $_total_qty = 0;
              $_total_cost_value = 0;
               $remove_duplicate_branch=array();
            ?>
            <?php $__empty_1 = true; $__currentLoopData = $group_array_values; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$_detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <?php
              $key_arrays = explode("__",$key);
             $_branch_id =  $key_arrays[0];
             $_cost_center_id =  $key_arrays[1];
             $_store_id =  $key_arrays[2];
             $_category_id =  $key_arrays[3];
           
              ?>
             <?php if(!in_array($key,$remove_duplicate_branch)): ?>
            <tr>
              <?php
                array_push($remove_duplicate_branch,$key);
              ?>
              <th colspan="6">





            <?php if(sizeof($_branch_ids) > 1 ): ?>
             Branch: <?php echo e(_branch_name($_branch_id)); ?> |
             <?php endif; ?>
             <?php if(sizeof($_cost_center_ids) > 1 ): ?>
               Cost Center: <?php echo e(_cost_center_name($_cost_center_id)); ?> |
             <?php endif; ?>
             <?php if(sizeof($_stores) > 1 ): ?>
               Store: <?php echo e(_store_name($_store_id)); ?> |
             <?php endif; ?>
            
              Category:  <?php echo e(_category_name($_category_id)); ?> 
            
              
              </th>
            </tr>
            <?php endif; ?>

            <?php
              $_sub_total_qty = 0;
              $_sub_total_cost_value = 0;
              $row_counter =0;
            ?>
            <?php $__empty_2 = true; $__currentLoopData = $_detail; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $g_value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>

            <?php
              $row_counter +=1;

              $_total_qty += $g_value->_qty;
              

              $_sub_total_qty += $g_value->_qty;
             
            ?>
            <tr>
             
              
            <td class="text-left"><?php echo $g_value->_manufacture_company ?? ''; ?> </td>
            <td class="text-left"><?php echo $g_value->_item_name ?? ''; ?> </td>
            <td class="text-left" style="width: 10%;"><?php echo $g_value->_unit_name ?? ''; ?></td>
            <td style="width: 10%;" class="text-right"><?php echo _report_amount($g_value->_qty); ?></td>
            <td style="width: 10%;" class="text-right"><?php echo _report_amount($g_value->_reorder); ?></td>
            <td style="width: 10%;" class="text-right"><?php echo _report_amount($g_value->_order_qty); ?></td>



          </tr>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
          <?php endif; ?>
<?php if($row_counter > 1): ?>
          <tr>
           

            <th colspan="3" class="text-left" >Sub Total </th>
            <th style="width: 10%;" class="text-right"><?php echo _report_amount($_sub_total_qty); ?></th>
            <th style="width: 10%;" class="text-right"></th>
            <th style="width: 10%;" class="text-right"></th>
          </tr>
<?php endif; ?>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
          <?php endif; ?>
           <tr>
           

            <th colspan="3" class="text-left" >Grand Total </th>
            <th style="width: 10%;" class="text-right"><?php echo _report_amount($_total_qty); ?></th>
            <th style="width: 10%;" class="text-right"></th>
            <th style="width: 10%;" class="text-right"></th>
          </tr>
            
            
          </tbody>
          <tfoot>
            <tr>
              <td colspan="6">
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

<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/sobuz23/sspf.sobuzsathitraders.com/resources/views/backend/inventory-report/report_shortage_item.blade.php ENDPATH**/ ?>