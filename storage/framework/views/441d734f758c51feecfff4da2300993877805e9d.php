
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
    <a class="nav-link"  href="<?php echo e(url('date-wise-purchase')); ?>" role="button">
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
            <th style="width: 15%;">Item Name </th>
            <th style="width: 10%;">Store</th>
            <th style="width: 10%;">Barcode</th>
            <th style="width: 7%;">Unit</th>
            <th style="width: 8%;" class="text-right">Quantity</th>
            <th style="width: 10%;" class="text-right">Rate</th>
            <th style="width: 10%;" class="text-right">VAT%</th>
            <th style="width: 10%;" class="text-right">VAT</th>
            <th style="width: 10%;" class="text-right">Value</th>
          </tr>
          
          
          </thead>
          <?php
                $_grand_invoice_qty=0;
                $_grand_invoice_vat_amount=0;
                $_grand_invoice_value=0;
          ?>
           
          <?php $__empty_1 = true; $__currentLoopData = $group_array_values; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$_group_val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
          <?php
                $_group_invoice_qty=0;
                $_group_invoice_vat_amount=0;
                $_group_invoice_value=0;
          ?>
          <tr>
            <th colspan="9" class="text-left"><?php echo $key; ?></th>
          </tr>
           
          <?php $__empty_2 = true; $__currentLoopData = $_group_val; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $master_key=> $master_val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
          <?php
                $_ledger_invoice_qty=0;
                $_ledger_invoice_vat_amount=0;
                $_ledger_invoice_value=0;
              ?>
            <tr>
            <th colspan="9" class="text-left"><?php echo $master_key; ?></th>
          </tr>

            <?php $__empty_3 = true; $__currentLoopData = $master_val; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d_key=> $del_val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_3 = false; ?>
              <tr>
                <th colspan="9" class="text-left">
                  <a style="text-decoration: none;" target="__blank" href="<?php echo e(url('purchase/print',$del_val->id)); ?>">
                    ID: <?php echo $del_val->id ?? ''; ?></a> | Date : <?php echo _view_date_formate($del_val->_date); ?> | Reference : <?php echo $del_val->_order_ref_id ?? ''; ?>

                  </th>
              </tr>
              <?php
                $_invoice_qty=0;
                $_invoice_vat_amount=0;
                $_invoice_value=0;
              ?>
               <?php $__empty_4 = true; $__currentLoopData = $del_val->_master_details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $_master_details): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_4 = false; ?>
               <?php
                $_invoice_qty += $_master_details->_qty;
                $_invoice_vat_amount += $_master_details->_vat_amount;
                $_invoice_value +=$_master_details->_value;

                $_ledger_invoice_qty += $_master_details->_qty;
                $_ledger_invoice_vat_amount += $_master_details->_vat_amount;
                $_ledger_invoice_value +=$_master_details->_value;

                $_group_invoice_qty += $_master_details->_qty;
                $_group_invoice_vat_amount += $_master_details->_vat_amount;
                $_group_invoice_value +=$_master_details->_value;

                $_grand_invoice_qty += $_master_details->_qty;
                $_grand_invoice_vat_amount += $_master_details->_vat_amount;
                $_grand_invoice_value +=$_master_details->_value;
              ?>
              <tr>
                <td class="text-left"><?php echo $_master_details->_items->_name ?? ''; ?></td>
                <td class="text-left"><?php echo $_master_details->_store->_name ?? ''; ?></td>
                <td class="text-left">
                  <?php
                                          $barcode_arrays = explode(',', $_master_details->_barcode ?? '');
                                          ?>
                                          <?php $__empty_5 = true; $__currentLoopData = $barcode_arrays; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $barcode): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_5 = false; ?>
                                        <span><?php echo e($barcode); ?></span><br>
                                          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_5): ?>
                                          <?php endif; ?>
                  </td>
                <td class="text-left"><?php echo $_master_details->_trans_unit->_name ?? ''; ?></td>
                <td class="text-right"><?php echo _report_amount($_master_details->_qty ?? 0); ?></td>
                <td class="text-right"><?php echo _report_amount($_master_details->_rate ?? 0); ?></td>
                <td class="text-right"><?php echo _report_amount($_master_details->_vat ?? 0); ?></td>
                <td class="text-right"><?php echo _report_amount($_master_details->_vat_amount ?? 0); ?></td>
                <td class="text-right"><?php echo _report_amount($_master_details->_value ?? 0); ?></td>
              </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_4): ?>
            <?php endif; ?>
            <tr>
              <th colspan="4" class="text-left">Sub Total:</th>
              <th class="text-right"><?php echo e(_report_amount($_invoice_qty)); ?></th>
              <th class="text-right"></th>
              <th class="text-right"></th>
              <th class="text-right"><?php echo e(_report_amount($_invoice_vat_amount)); ?></th>
              <th class="text-right"><?php echo e(_report_amount($_invoice_value)); ?></th>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_3): ?>
            <?php endif; ?>
            <tr>
              <th colspan="4" class="text-left">Summary for <?php echo $master_key; ?></th>
              <th class="text-right"><?php echo e(_report_amount($_ledger_invoice_qty)); ?></th>
              <th class="text-right"></th>
              <th class="text-right"></th>
              <th class="text-right"><?php echo e(_report_amount($_ledger_invoice_vat_amount)); ?></th>
              <th class="text-right"><?php echo e(_report_amount($_ledger_invoice_value)); ?></th>
            </tr>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
          <?php endif; ?>
            <tr>
              <th colspan="4" class="text-left">Summary for <?php echo $key; ?></th>
              <th class="text-right"><?php echo e(_report_amount($_group_invoice_qty)); ?></th>
              <th class="text-right"></th>
              <th class="text-right"></th>
              <th class="text-right"><?php echo e(_report_amount($_group_invoice_vat_amount)); ?></th>
              <th class="text-right"><?php echo e(_report_amount($_group_invoice_value)); ?></th>
            </tr>

          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
          <?php endif; ?>
         <tr>
              <th colspan="4" class="text-left">Grand Total</th>
              <th class="text-right"><?php echo e(_report_amount($_grand_invoice_qty)); ?></th>
              <th class="text-right"></th>
              <th class="text-right"></th>
              <th class="text-right"><?php echo e(_report_amount($_grand_invoice_vat_amount)); ?></th>
              <th class="text-right"><?php echo e(_report_amount($_grand_invoice_value)); ?></th>
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

<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/sobuz23/sspf.sobuzsathitraders.com/resources/views/backend/inventory-report/report_date_wise_purchase_statement.blade.php ENDPATH**/ ?>