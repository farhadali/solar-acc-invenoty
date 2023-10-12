
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
 <a class="nav-link"  href="<?php echo e(url('sales')); ?>" role="button"><i class="fa fa-arrow-left"></i></a>
 <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('sales-edit')): ?>
    <a class="nav-link"  title="Edit" href="<?php echo e(route('sales.edit',$data->id)); ?>">
                                      <i class="nav-icon fas fa-edit"></i>
     </a>
  <?php endif; ?>
    
    <a style="cursor: pointer;" class="nav-link"  title="Print" onclick="javascript:printDiv('printablediv')"><i class="fas fa-print"></i></a>
      <a style="cursor: pointer;" onclick="fnExcelReport();" class="nav-link"  title="Excel Download" ><i class="fa fa-file-excel" aria-hidden="true"></i></a>
       <?php echo $__env->make('backend.message.message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
  </div>

<section class="invoice" id="printablediv">

     <div class="row">
      <div class="col-4">
       <h3 class="page-header">
        <img src="<?php echo e(url('/')); ?>/<?php echo e($settings->logo); ?>" alt="<?php echo e($settings->name ?? ''); ?>" style="height: 60px;width: 60px"  > <?php echo e($settings->title ?? ''); ?>


        
       </h3>
      </div>
      <div class="col-md-4"><h3 class="text-center">Challan</h3></div>
      <div class="col-md-4"><small class="float-right">Date: <?php echo _view_date_formate($data->_date ?? ''); ?></small></div>

     </div>

     <div class="row invoice-info">
      <div class="col-sm-4 invoice-col">
       From
       <address>
        <strong><?php echo e($settings->name ?? ''); ?></strong><br>
        Address: <?php echo e($settings->_address ?? ''); ?><br>
        Phone: <?php echo e($settings->_phone ?? ''); ?><br>
        Email: <?php echo e($settings->_email ?? ''); ?>  
        <?php
        $bin = $settings->_bin ?? '';
      ?>
      <?php if($bin !=''): ?>
      <br>
      <b>VAT REGISTRATION NO:</b> <?php echo e($settings->_bin ?? ''); ?>

      <?php endif; ?>
       </address>
      </div>

      <div class="col-sm-4 invoice-col">
       To
       <address>
        <strong><?php echo e($data->_ledger->_name ?? ''); ?></strong><br>
        <?php echo e($data->_address ?? ''); ?><br>
        Phone: <?php echo e($data->_phone ?? ''); ?><br>
        Email: <?php echo e($data->_email ?? ''); ?>

       </address>
      </div>

      <div class="col-sm-4 invoice-col">
       <b>Challan/Bill No: <?php echo e($data->_order_number ?? ''); ?></b><br>
       <b>Referance:</b> <?php echo $data->_referance ?? ''; ?><br>
       <b>Created By:</b> <?php echo $data->_user_name ?? ''; ?><br>
       <b>Branch:</b> <?php echo e($data->_master_branch->_name ?? ''); ?>

      </div>

     </div>


     <div class="row">
      <div class="col-12 table-responsive">
       <table class="table table-striped">
        <thead>
         <tr>
          <th style="border:1px solid silver;" class="text-left">SL</th>
          <th style="border:1px solid silver;" class="text-left">Item</th>
          <th style="border:1px solid silver;" class="text-left">Unit</th>
          <th style="border:1px solid silver;" class="text-right">Qty</th>
         </tr>
        </thead>
        <tbody>
           <?php if(sizeof($data->_master_details) > 0): ?>
         <?php
                                    $_value_total = 0;
                                    $_vat_total = 0;
                                    $_qty_total = 0;
                                    $_total_discount_amount = 0;
                                  ?>
                                  <?php $__empty_1 = true; $__currentLoopData = $data->_master_details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item_key=>$_item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                  <tr>
                                     <td style="border:1px solid silver;" class="text-left" ><?php echo e(($item_key+1)); ?>.</td>
                                     <?php
                                      $_value_total +=$_item->_value ?? 0;
                                      $_vat_total += $_item->_vat_amount ?? 0;
                                      $_qty_total += $_item->_qty ?? 0;
                                      $_total_discount_amount += $_item->_discount_amount ?? 0;
                                     ?>
                                            <td style="border:1px solid silver;" class="  " ><?php echo $_item->_items->_name ?? ''; ?></td>
                                            <td style="border:1px solid silver;" class="  " ><?php echo $_item->_trans_unit->_name ?? ''; ?></td>
                                            
                                             <td style="border:1px solid silver;" class="text-right  " ><?php echo _report_amount($_item->_qty ?? 0); ?></td>
                                            
                                            
                                           
                                          </thead>
                                  </tr>
                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                  <?php endif; ?>
                            <tr>
                              <td style="border:1px solid silver;" colspan="3" class="text-right "><b>Total</b></td>
                              <td style="border:1px solid silver;" class="text-right "> <b><?php echo e(_report_amount($_qty_total ?? 0)); ?></b> </td>
                              
                            </tr>
                            
                              
                             
         <?php endif; ?>
        </tbody>
        <tfoot>

               <tr>
                 <td colspan="4">
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
<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/sobuz23/sspf.sobuzsathitraders.com/resources/views/backend/sales/challan.blade.php ENDPATH**/ ?>