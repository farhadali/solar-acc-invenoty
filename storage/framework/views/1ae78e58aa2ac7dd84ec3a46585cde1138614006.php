
<?php $__env->startSection('title',$page_name); ?>

<?php $__env->startSection('content'); ?>
<style type="text/css">
 
  @media  print {
   .table th {
    vertical-align: top;
    color: #000;
    background-color: #fff; 
    border: 1px solid silver;
} 
.table td {
    vertical-align: top;
    color: #000;
    background-color: #fff; 
     border: 1px solid silver;
}

}

  </style>
<div class="_report_button_header">
 <a class="nav-link"  href="<?php echo e(url('date-wise-invoice-print')); ?>" role="button"><i class="fas fa-search"></i></a>

    
    <a style="cursor: pointer;" class="nav-link"  title="Print" onclick="javascript:printDiv('printablediv')"><i class="fas fa-print"></i></a>
      <a style="cursor: pointer;" onclick="fnExcelReport();" class="nav-link"  title="Excel Download" ><i class="fa fa-file-excel" aria-hidden="true"></i></a>
       <?php echo $__env->make('backend.message.message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
  </div>

<section class="invoice" id="printablediv">


     
<?php $__empty_1 = true; $__currentLoopData = $datas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

     
       <table class="table table-striped" style="width: 100%;">
        <thead style="background: #fff;color: #000;border:0px">
         <tr style="border:0px">
           <td colspan="7" style="border:0px">
            <div style="width: 100%;text-align: center;">
              <?php echo e($settings->_top_title ?? ''); ?><br>
              <img src="<?php echo e(url('/')); ?>/<?php echo e($settings->logo); ?>" alt="<?php echo e($settings->name ?? ''); ?>" style="height: 50px;width: 50px;"  > <?php echo e($settings->title ?? ''); ?>

             <address>
               <?php echo e($settings->_address ?? ''); ?><br>
                <?php echo e($settings->_phone ?? ''); ?> <?php echo e($settings->_email ?? ''); ?><br>
                <b> Invoice/Bill</b>
               </address>
            </div>
            
           </td>
         </tr>
         <tr style="border:0px">
           <td colspan="3" class="text-left" style="border:1px solid silver;">
            <b> Customer:</b>
             <address>
              <strong>Name: </strong><?php echo e($data->_ledger->_name ?? ''); ?><br>
              <strong>Address: </strong><?php echo e($data->_address ?? 'N/A'); ?><br>
              <strong>Phone: </strong> <?php echo e($data->_phone ?? 'N/A'); ?><br>
              <strong>Email: </strong> <?php echo e($data->_email ?? 'N/A'); ?>

             </address>
           </td>
           <td colspan="4" class="text-right" style="border:1px solid silver;">
             <b>Invoice/Bill No:</b> <?php echo e($data->_order_number ?? ''); ?><br>
             <b>Date:</b> <?php echo _view_date_formate($data->_date ?? ''); ?><br>
             <b>Referance:</b> <?php echo $data->_referance ?? ''; ?><br>
             <b>Sales By:</b> <?php echo $data->_sales_man->_name ?? 'N/A'; ?><br>
             <b>Delivered by:</b> <?php echo $data->_delivery_man->_name ?? 'N/A'; ?><br>
             <b>Created By:</b> <?php echo $data->_user_name ?? ''; ?><br>
             <b>Branch:</b> <?php echo e($data->_master_branch->_name ?? ''); ?>

           </td>
         </tr>
        </thead>
        <tbody>
          <tr>
          <th class="text-left" style="border:1px solid silver;">SL</th>
          <th class="text-left" style="border:1px solid silver;">Item</th>
          <th class="text-right" style="border:1px solid silver;">Qty</th>
          <th class="text-right" style="border:1px solid silver;">Rate</th>
          <th class="text-right" style="border:1px solid silver;">Discount</th>
          <th class="text-right" style="border:1px solid silver;">VAT</th>
          <th class="text-right" style="border:1px solid silver;">Amount</th>
         </tr>
           <?php if(sizeof($data->_master_details) > 0): ?>
         <?php
                                    $_value_total = 0;
                                    $_vat_total = 0;
                                    $_qty_total = 0;
                                    $_total_discount_amount = 0;
                                  ?>
                                  <?php $__empty_2 = true; $__currentLoopData = $data->_master_details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item_key=>$_item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                                  <tr style="border:1px solid silver;">
                                     <td class="text-left" style="border:1px solid silver;"><?php echo e(($item_key+1)); ?>.</td>
                                     <?php
                                      $_value_total +=$_item->_value ?? 0;
                                      $_vat_total += $_item->_vat_amount ?? 0;
                                      $_qty_total += $_item->_qty ?? 0;
                                      $_total_discount_amount += $_item->_discount_amount ?? 0;
                                     ?>
                                            <td class="  " style="border:1px solid silver;"><?php echo $_item->_items->_name ?? ''; ?></td>
                                            
                                           <td class="text-right  " style="border:1px solid silver;"><?php echo _report_amount($_item->_qty ?? 0); ?></td>
                                            <td class="text-right  " style="border:1px solid silver;"><?php echo _report_amount($_item->_sales_rate ?? 0); ?></td>
                                            <td class="text-right  " style="border:1px solid silver;"><?php echo _report_amount($_item->_discount_amount ?? 0); ?></td>
                                            <td class="text-right  " style="border:1px solid silver;"><?php echo _report_amount($_item->_vat_amount ?? 0); ?></td>
                                            
                                            <td class="text-right  " style="border:1px solid silver;"><?php echo _report_amount($_item->_value ?? 0); ?></td>
                                            
                                            
                                           
                                          </thead>
                                  </tr>
                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                                  <?php endif; ?>
                            <tr style="border:1px solid silver;">
                              <td style="border:1px solid silver;" colspan="2" class="text-left "><b>Total</b></td>
                              <td style="border:1px solid silver;" class="text-right "> <b><?php echo e(_report_amount( $_qty_total ?? 0)); ?></b> </td>
                              <td style="border:1px solid silver;"></td>
                              <td style="border:1px solid silver;" class="text-right "> <b><?php echo e(_report_amount( $_total_discount_amount ?? 0)); ?></b> </td>
                              <td style="border:1px solid silver;" class="text-right "> <b><?php echo e(_report_amount($_vat_total ?? 0)); ?></b> </td>
                              <td style="border:1px solid silver;" class=" text-right"><b> <?php echo e(_report_amount($_value_total ?? 0)); ?></b>
                              </td>
                            </tr>
                            <tr style="border:1px solid silver;">
                              <td colspan="3" class="text-left " style="width: 50%;border:0px;">
                              <table style="width: 100%;border:0px;">
                                <tr style="border:0px;">
                                  <td style="border:0px;">

                                    <?php echo e($settings->_sales_note ?? ''); ?>

                                  </td>
                                </tr>
                                <tr style="border:0px;">
                                  <td style="border:0px;"><small class="lead"> In Words:  <?php echo e(nv_number_to_text($data->_total ?? 0)); ?> </small></td>
                                </tr>
                              </table>
                              </td>
                              
                              <td colspan="4" class=" text-right"  style="width: 50%;border:1px solid silver;">
                                  <table style="width: 100%">
                                     <tr>
                                      <th class="text-left" ><b>Sub Total</b></th>
                                      <th class="text-right"><?php echo _report_amount($data->_sub_total ?? 0); ?></th>
                                    </tr>
                                   
                                    <tr>
                                      <th class="text-left" ><b>Discount[-]</b></th>
                                      <th class="text-right"><?php echo _report_amount($data->_total_discount ?? 0); ?></th>
                                    </tr>
                                   
                                    <?php if($form_settings->_show_vat==1): ?>
                                    <tr>
                                      <th class="text-left" ><b>VAT[+]</b></th>
                                      <th class="text-right"><?php echo _report_amount($data->_total_vat ?? 0); ?></th>
                                    </tr>
                                    <?php endif; ?>
                                    <tr>
                                      <th class="text-left" ><b>Net Total</b></th>
                                      <th class="text-right"><?php echo _report_amount($data->_total ?? 0); ?></th>
                                    </tr>
                                    <?php
                                    $accounts = $data->s_account ?? [];
                                    $_due_amount =$data->_total ?? 0;
                                    ?>
                                    <?php if(sizeof($accounts) > 0): ?>
                                    <?php $__currentLoopData = $accounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ac_val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php if($ac_val->_ledger->id !=$data->_ledger_id): ?>
                                     <?php if($ac_val->_cr_amount > 0): ?>
                                     <?php
                                      $_due_amount +=$ac_val->_cr_amount ?? 0;
                                     ?>
                                    <tr>
                                      <th class="text-left" ><b> <?php echo $ac_val->_ledger->_name ?? ''; ?>[+]
                                        </b></th>
                                      <th class="text-right"><?php echo _report_amount( $ac_val->_cr_amount ?? 0 ); ?></th>
                                    </tr>
                                    <?php endif; ?>
                                    <?php if($ac_val->_dr_amount > 0): ?>
                                     <?php
                                      $_due_amount -=$ac_val->_dr_amount ?? 0;
                                     ?>
                                    <tr>
                                      <th class="text-left" ><b> <?php echo $ac_val->_ledger->_name ?? ''; ?>[-]
                                        </b></th>
                                      <th class="text-right"><?php echo _report_amount( $ac_val->_dr_amount ?? 0 ); ?></th>
                                    </tr>
                                    <?php endif; ?>

                                    <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                      <th class="text-left" ><b>Invoice Due </b></th>
                                      <th class="text-right"><?php echo _report_amount( $_due_amount); ?></th>
                                    </tr>

                                    <?php endif; ?>
                                    <?php if($form_settings->_show_p_balance==1): ?>
                                    <tr>
                                      <th class="text-left" ><b>Previous Balance</b></th>
                                      <th class="text-right"><?php echo _show_amount_dr_cr(_report_amount($data->_p_balance ?? 0)); ?></th>
                                    </tr>
                                    <tr>
                                      <th class="text-left" ><b>Current Balance</b></th>
                                      <th class="text-right"><?php echo _show_amount_dr_cr(_report_amount($data->_l_balance ?? 0)); ?></th>
                                    </tr>
                                    <?php endif; ?>
                                  </table>

                              </td>
                            </tr>
         <?php endif; ?>
        </tbody>
        <tfoot>

               <tr>
                 <td colspan="7">
                    <?php echo $__env->make('backend.message.invoice_footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                 </td>
               </tr> 
        </tfoot>
       </table>
      <p style="page-break-after: always;">&nbsp;</p>
<!-- <p style="page-break-before: always;">&nbsp;</p> -->
      
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
<?php endif; ?>

   

   <?php $__env->stopSection(); ?>


<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\own\inv-acc-hrm\resources\views/backend/account-report/report_date_wise_invoice_print.blade.php ENDPATH**/ ?>