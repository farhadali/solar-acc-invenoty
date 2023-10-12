

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
    <a class="nav-link"  href="<?php echo e(url('purchase-order')); ?>" role="button"><i class="fa fa-arrow-left"></i></a>
 <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('purchase-order-edit')): ?>
    <a class="nav-link "
      title="Edit" 
    href="<?php echo e(route('purchase-order.edit',$data->id)); ?>"><i class="nav-icon fas fa-edit"></i> </a>
  <?php endif; ?>

    <a style="cursor: pointer;" class="nav-link"  title="Print" onclick="javascript:printDiv('printablediv')"><i class="fas fa-print"></i></a>
      <a style="cursor: pointer;" onclick="fnExcelReport();" class="nav-link"  title="Excel Download" ><i class="fa fa-file-excel" aria-hidden="true"></i></a>
       <?php echo $__env->make('backend.message.message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
  </div>

<section class="invoice" id="printablediv">
    <!-- title row -->

    <div class="row">
      <div class="col-12">
        <table class="table" style="border:none;">
          <tr>
            <td style="border:none;width: 33%;text-align: left;">
              <table class="table" style="border:none;">
                  <tr> <td style="border:none;" > <?php echo e(invoice_barcode($data->_order_number ?? '')); ?></td></tr>
                  <tr>
                  <tr> <td style="border:none;" > <b>Invoice No:<?php echo e($data->_order_number ?? ''); ?></b></td></tr>
                  <tr> <td style="border:none;" > <b>Date</b><?php echo e(_view_date_formate($data->_date ?? '')); ?></td></tr>
                <tr> <td style="border:none;" > <b> Supplier:</b>  <?php echo e($data->_ledger->_name ?? ''); ?></td></tr>
                <tr> <td style="border:none;" > <b> Phone:</b>  <?php echo e($data->_phone ?? ''); ?> </td></tr>
                <tr> <td style="border:none;" > <b> Address:</b> <?php echo e($data->_address ?? ''); ?> </td></tr>
              </table>
            </td>
            <td style="border:none;width: 33%;text-align: center;">
              <table class="table" style="border:none;">
                <tr> <td class="text-center" style="border:none;"> <?php echo e($settings->_top_title ?? ''); ?></td> </tr>
                <tr> <td class="text-center" style="border:none;font-size: 24px;"><b><?php echo e($settings->name ?? ''); ?></b></td> </tr>
                <tr> <td class="text-center" style="border:none;"><b><?php echo e($settings->_address ?? ''); ?></b></td></tr>
                <tr> <td class="text-center" style="border:none;"><b><?php echo e($settings->_phone ?? ''); ?></b>,<b><?php echo e($settings->_email ?? ''); ?></b></td></tr>
                 <tr> <td class="text-center" style="border:none;"><h3><?php echo e($page_name); ?> Invoice</h3></td> </tr>
              </table>
            </td>
            <td style="border:none;width: 33%;text-align: right;">
              <table class="table" style="border:none;">
                  <tr> <td class="text-right" style="border:none;"  > <b>Time:</b> <?php echo e($data->_time ?? ''); ?> </td></tr>
                  <tr> <td class="text-right"  style="border:none;" > <b>Created By:</b> <?php echo e($data->_user_name ?? ''); ?></td></tr>
                  <tr> <td class="text-right"  style="border:none;" > <b>Branch:</b> <?php echo e($data->_master_branch->_name ?? ''); ?> </td></tr>
              </table>
            </td>
          </tr>
        </table>
        </div>
      </div>
    
   
      <div class="row">
        <div class="col-12 table-responsive">
         
            <?php if(sizeof($data->_master_details) > 0): ?>
                        
                              <table class="table _grid_table">
                                <thead >
                                            <th class="text-left" >SL</th>
                                            <th class="text-left" >Item</th>
                                            <th class="text-center" >Unit</th>
                                            <th class="text-right" >Qty</th>
                                            <th class="text-right" >Rate</th>
                                            <th class="text-right" >Value</th>
                                             <?php if(sizeof($permited_branch) > 1): ?>
                                            <th class="text-middle" >Branch</th>
                                            <?php else: ?>
                                            <th class="text-middle display_none" >Branch</th>
                                            <?php endif; ?>
                                             <?php if(sizeof($permited_costcenters) > 1): ?>
                                            <th class="text-middle" >Cost Center</th>
                                            <?php else: ?>
                                             <th class="text-middle display_none" >Cost Center</th>
                                            <?php endif; ?>
                                            
                                           
                                          </thead>
                                <tbody>
                                  <?php
                                    $_value_total = 0;
                                    $_vat_total = 0;
                                    $_qty_total = 0;
                                  ?>
                                  <?php $__empty_1 = true; $__currentLoopData = $data->_master_details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item_key=>$_item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                  <tr>
                                     <td class="" ><?php echo e(($item_key+1)); ?></td>
                                     <?php
                                      $_value_total +=$_item->_value ?? 0;
                                      $_qty_total += $_item->_qty ?? 0;
                                     ?>
                                            <td class="" ><?php echo $_item->_items->_name ?? ''; ?></td>
                                          <td class="text-center" ><?php echo $_item->_trans_unit->_name ?? $_item->_items->_units->_name; ?></td>
                                            <td class="text-right" ><?php echo _report_amount($_item->_qty ?? 0); ?></td>
                                            
                                            <td class="text-right" ><?php echo _report_amount(($_item->_rate ?? 0)); ?></td>
                                            <td class="text-right" ><?php echo _report_amount($_item->_value ?? 0); ?></td>
                                             <?php if(sizeof($permited_branch) > 1): ?>
                                            <td class="" ><?php echo $_item->_detail_branch->_name ?? ''; ?></td>
                                            <?php else: ?>
                                            <td class=" display_none" ><?php echo $_item->_detail_branch->_name ?? ''; ?></td>
                                            <?php endif; ?>
                                             <?php if(sizeof($permited_costcenters) > 1): ?>
                                            <td class="" ><?php echo $_item->_detail_cost_center->_name ?? ''; ?></td>
                                            <?php else: ?>
                                             <td class=" display_none" ><?php echo $_item->_detail_cost_center->_name ?? ''; ?></td>
                                            <?php endif; ?>
                                             
                                           
                                          </thead>
                                  </tr>
                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                  <?php endif; ?>
                                </tbody>
                                <tfoot>
                                  <tr>
                                              <td colspan="2" class="text-right"> <b>Total</b></td>
                                               <td></td>
                                              <td class="text-right">
                                                <b><?php echo e(_report_amount($_qty_total ?? 0)); ?></b> </td>
                                              <td></td>
                                             
                                              
                                              <td class="text-right">
                                               <b> <?php echo e(_report_amount($_value_total ?? 0)); ?></b>
                                              </td>
                                              <?php if(sizeof($permited_branch) > 1): ?>
                                              <td></td>
                                              <?php else: ?>
                                               <td class="display_none"></td>
                                              <?php endif; ?>
                                              <?php if(sizeof($permited_costcenters) > 1): ?>
                                              <td></td>
                                              <?php else: ?>
                                               <td class="display_none"></td>
                                              <?php endif; ?>
                                             
                                            </tr>
                                </tfoot>
                              </table>
                           
                          </div>
                        </td>
                        </tr>
                        <?php endif; ?>
         
      </div>

    

    <div class="row">
    
       <?php echo $__env->make('backend.message.invoice_footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>
    <!-- /.row -->
  </section>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\own\inv-acc-hrm\resources\views/backend/purchase-order/print.blade.php ENDPATH**/ ?>