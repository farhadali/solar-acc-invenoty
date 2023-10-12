
<?php $__env->startSection('title',$page_name); ?>

<?php $__env->startSection('content'); ?>
<style type="text/css">
 
  @media  print {
   .table th {
    vertical-align: top;
    color: #000;
    background-color: #fff; 
}
.table td{
  border:1px solid silver;
}
}
  </style>
<div class="_report_button_header">
 <a class="nav-link"  href="<?php echo e(url('production')); ?>" role="button"><i class="fa fa-arrow-left"></i></a>
 <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('production-edit')): ?>
    <a class="nav-link"  title="Edit" href="<?php echo e(route('production.edit',$data->id)); ?>">
                                      <i class="nav-icon fas fa-edit"></i>
     </a>
  <?php endif; ?>
    
    <a style="cursor: pointer;" class="nav-link"  title="Print" onclick="javascript:printDiv('printablediv')"><i class="fas fa-print"></i></a>
      <a style="cursor: pointer;" onclick="fnExcelReport();" class="nav-link"  title="Excel Download" ><i class="fa fa-file-excel" aria-hidden="true"></i></a>
     <?php echo $__env->make('backend.message.message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
  </div>

<section class="invoice" id="printablediv">

     


     <div class="row">
      <div class="col-12 table-responsive">
       <table class="table table-bordered">
        <thead style="background: #fff;color: #000;;border:0px solid #fff;">
          <tr style="background: #fff;color: #000;border:0px solid #fff;">
            <td style="background: #fff;color: #000;border:0px solid #fff;" colspan="8" style="text-align: center;">
              <div style="width: 100%;text-align: center;margin: 0px auto;">
                    <img src="<?php echo e(url('/')); ?>/<?php echo e($settings->logo); ?>" alt="<?php echo e($settings->name ?? ''); ?>" style="height: 60px;width: 60px"  > <?php echo e($settings->name ?? ''); ?><br>
                   <address>
                      
                      Address: <?php echo e($settings->_address ?? ''); ?><br>
                      Phone: <?php echo e($settings->_phone ?? ''); ?><br>
                      Email: <?php echo e($settings->_email ?? ''); ?><br>
                      <b style="text-transform: uppercase;"><?php echo e($data->_type ?? ''); ?></b>
                     </address>
                     
              </div>
            </td>
          </tr>
          <tr>
            <td colspan="4" class="text-left">
              <div>
                <b>From Branch: </b><?php echo e(_branch_name($data->_from_branch ?? 1)); ?><br>
                <b>To Branch: </b><?php echo e(_branch_name($data->_to_branch ?? 1)); ?><br>
                <b>Reference: </b><?php echo e($data->_reference ?? ''); ?><br>
              </div>
            </td>
            <td colspan="4" class="text-left">
              <div>
                <b style="text-transform: capitalize;"><?php echo e($data->_type ?? ''); ?> No: </b><?php echo e($data->id); ?><br>
                <b >Date: </b><?php echo e(_view_date_formate($data->_date ?? '')); ?><br>
                <b>Created By:</b> <?php echo $data->_created_by ?? ''; ?><br>
                
              </div>
            </td>
          </tr>
        </thead>
       
         
        <tbody>
          
          <tr>
            <th>ID</th>
            <th>Item</th>
            <th>Store</th>
            <th>Unit</th>
            <th>Qty</th>
            <th>Rate</th>
            <th>Sales Rate</th>
            <th class="text-right">Value</th>
          </tr>

          <?php
              $_stock_in_total = 0;
              $_stock_in_qty = 0;
            ?>
            <?php $__empty_1 = true; $__currentLoopData = $data->_stock_in; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item_key=>$_master_val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
              <td><?php echo e(($item_key+1)); ?></td>
              <td><?php echo e(_item_name($_master_val->_item_id)); ?> <br>
                <?php if($_master_val->_barcode !=""): ?>
                  <small><span>Barcode: <?php echo e($_master_val->_barcode ?? 'N/A'); ?></span></small>
                  <?php endif; ?>
              </td>
              <td><?php echo e(_store_name($_master_val->_store_id ?? 1 )); ?></td>
              <td><?php echo e(_find_unit($_master_val->_transection_unit ?? 1 )); ?></td>
              <td><?php echo e(_report_amount($_master_val->_qty ?? '')); ?></td>
              <td><?php echo e(_report_amount($_master_val->_rate ?? '')); ?></td>
              <td><?php echo e(_report_amount($_master_val->_sales_rate ?? '')); ?></td>
              <td class="text-right"><?php echo e(_report_amount( $_master_val->_value ?? 0)); ?></td>
              <?php 
              $_stock_in_total += $_master_val->_value;    
              $_stock_in_qty += $_master_val->_qty;    
              ?>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <?php endif; ?>
          <tr>
          <tr>
            <td colspan="4" class="text-left"><b>Total</b></td>
            <td><b><?php echo e(_report_amount($_stock_in_qty ?? 0 )); ?> </b></td>
            <td colspan="2"></td>
            <td  class="text-right"><b><?php echo e(_report_amount($_stock_in_total ?? 0 )); ?> </b></td>
          </tr>


            <tr>
              <td colspan="8">
                <b>Note:</b> <?php echo e($data->_note ?? ''); ?>

              </td>
            </tr>
            
        </tbody>
        <tfoot>

               <tr>
                 <td colspan="8">
                  <?php echo $__env->make('backend.message.invoice_footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                 </td>
               </tr> 
        </tfoot>
       </table>
      </div>

     </div>

   

   <?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>


<?php $__env->stopSection(); ?>
<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\own\inv-acc-hrm\resources\views/backend/production/stock_in_print.blade.php ENDPATH**/ ?>