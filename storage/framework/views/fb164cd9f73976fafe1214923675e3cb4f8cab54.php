
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
 <a class="nav-link"  href="<?php echo e(url('purchase')); ?>" role="button"><i class="fa fa-arrow-left"></i></a>
 <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('purchase-edit')): ?>
     <a  href="<?php echo e(route('purchase.edit',$data->id)); ?>" 
    class="nav-link "  title="Edit"  >
    <i class="nav-icon fas fa-edit"></i>
     </a>
  <?php endif; ?>
    
    <a style="cursor: pointer;" class="nav-link"  title="Print" onclick="javascript:printDiv('printablediv')"><i class="fas fa-print"></i></a>
      <a style="cursor: pointer;" onclick="fnExcelReport();" class="nav-link"  title="Excel Download" ><i class="fa fa-file-excel" aria-hidden="true"></i></a>
     <?php echo $__env->make('backend.message.message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
  </div>

<section class="invoice" id="printablediv">
 <div class="container-fluid">
     <div class="row">
      <div class="col-4">
       <h3 class="page-header">
        <img src="<?php echo e(url('/')); ?>/<?php echo e($settings->logo); ?>" alt="<?php echo e($settings->name ?? ''); ?>" style="height: 60px;width: 60px"  > <?php echo e($settings->title ?? ''); ?>

       
       </h3>
      </div>
      <div class="col-4 text-center">
         <?php echo e($settings->_top_title ?? ''); ?>

       <h3 class="page-header text-center">
        <?php echo e($page_name); ?>

       </h3>
      </div>
      <div class="col-4">
       <h3 class="page-header">
        <small class="float-right">Date: <?php echo _view_date_formate($data->_date ?? ''); ?></small>
       </h3>
      </div>

     </div>

     <div class="row invoice-info">
      <div class="col-sm-4 invoice-col">
       
       <address>
        <strong><?php echo e($settings->name ?? ''); ?></strong><br>
       Address:  <?php echo e($settings->_address ?? ''); ?><br>
        Phone: <?php echo e($settings->_phone ?? ''); ?><br>
        Email: <?php echo e($settings->_email ?? ''); ?>

       </address>
      </div>

      <div class="col-sm-4 invoice-col ">
       Supplier:
       <address>
        <strong><?php echo e($data->_ledger->_name ?? ''); ?></strong><br>
        Address:<?php echo e($data->_address ?? ''); ?><br>
        Phone: <?php echo e($data->_phone ?? ''); ?><br>
        Email: <?php echo e($data->_email ?? ''); ?>

       </address>
      </div>

      <div class="col-sm-4 invoice-col text-right">
         <?php echo e(invoice_barcode($data->_order_number ?? '')); ?>

         
       <b>Invoice No: <?php echo e($data->_order_number ?? ''); ?></b><br>
       <b>Referance:</b> <?php echo $data->_referance ?? ''; ?><br>
       <b>Account Balance:</b>  <?php echo _show_amount_dr_cr(_report_amount($data->_l_balance ?? 0)); ?><br>
       <b>Created By:</b> <?php echo $data->_user_name ?? ''; ?><br>
       <b>Branch:</b> <?php echo e($data->_master_branch->_name ?? ''); ?>

      </div>

     </div>


     <div class="row">
      <div class="col-12 table-responsive">
       <table class="table table-striped _grid_table">
        <thead>
         <tr>
          <th class="text-left" style="width: 10%;">SL</th>
          <th class="text-left" style="width: 20%;">Item</th>
          <th class="text-left" style="width: 8%;">Unit</th>
          <th class="text-left" style="width: 12%;">Barcode</th>
          <th class="text-right" style="width: 10%;">Qty</th>
          <th class="text-right" style="width: 10%;">Rate</th>
          <th class="text-right" style="width: 10%;">Discount</th>
          <th class="text-right" style="width: 10%;">VAT</th>
          <th class="text-right" style="width: 10%;">Amount</th>
         </tr>
        </thead>
        <tbody>
         <?php
          $_master_details = $data->_master_details ?? [];
          ?>
           <?php if(sizeof( $_master_details) > 0): ?>
         <?php
                                    $_value_total = 0;
                                    $_vat_total = 0;
                                    $_qty_total = 0;
                                    $_total_discount_amount = 0;
                                  ?>
                                  <?php $__empty_1 = true; $__currentLoopData = $data->_master_details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item_key=>$_item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                  <tr>
                                     <td class="text-left" ><?php echo e(($item_key+1)); ?>.</td>
                                     <?php
                                      $_value_total +=$_item->_value ?? 0;
                                      $_vat_total += $_item->_vat_amount ?? 0;
                                      $_qty_total += $_item->_qty ?? 0;
                                      $_total_discount_amount += $_item->_discount_amount ?? 0;
                                     ?>
                                            <td class="  " style="word-break: break-all;vertical-align: text-top;"><?php echo $_item->_items->_name ?? ''; ?></td>
                                             <td class="text-left" ><?php echo $_item->_trans_unit->_name ?? $_item->_items->_units->_name; ?></td>
                                            <td class="  " style="word-break: break-all;vertical-align: text-top;"> <?php
                                                $barcode_arrays = explode(',', $_item->_barcode ?? '');
                                                ?>
                                                <?php $__empty_2 = true; $__currentLoopData = $barcode_arrays; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $barcode): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                                              <span style="width: 100%;"><?php echo e($barcode); ?></span><br>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                                                <?php endif; ?>
                                            </td>
                                            
                                           <td class="text-right  " style="vertical-align: text-top;"><?php echo _report_amount($_item->_qty ?? 0); ?></td>
                                            <td class="text-right  " style="vertical-align: text-top;"><?php echo _report_amount($_item->_rate ?? 0); ?></td>
                                            <td class="text-right  " style="vertical-align: text-top;"><?php echo _report_amount($_item->_discount_amount ?? 0); ?></td>
                                            <td class="text-right  " style="vertical-align: text-top;"><?php echo _report_amount($_item->_vat_amount ?? 0); ?></td>
                                            
                                            <td class="text-right  " style="vertical-align: text-top;"><?php echo _report_amount($_item->_value ?? 0); ?></td>
                                            
                                            
                                           
                                          </thead>
                                  </tr>
                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                  <?php endif; ?>
                            <tr>
                              <td colspan="4" class="text-right "><b>Total</b></td>
                              <td class="text-right "> <b><?php echo e(_report_amount( $_qty_total ?? 0)); ?></b> </td>
                              <td></td>
                              <td class="text-right "> <b><?php echo e(_report_amount( $_total_discount_amount ?? 0)); ?></b> </td>
                              <td class="text-right "> <b><?php echo e(_report_amount($_vat_total ?? 0)); ?></b> </td>
                              <td class=" text-right"><b> <?php echo e(_report_amount($_value_total ?? 0)); ?></b>
                              </td>
                            </tr>
                            <tr>
                              <td colspan="5" class="text-left " style="width: 50%;">
                              <table style="width: 100%">
                                <tr>
                                  <td>

                                    <?php echo e($settings->_purchse_note ?? ''); ?>

                                  </td>
                                </tr>
                                <tr>
                                  <td><p class="lead"> In Words:  <?php echo e(nv_number_to_text($data->_total ?? 0)); ?> </p></td>
                                </tr>
                              </table>
                              </td>
                              
                              <td colspan="5" class=" text-right"  style="width: 50%;">
                                  <table style="width: 100%">
                                     <tr>
                                      <th class="text-right" ><b>Sub Total</b></th>
                                      <th class="text-right"><?php echo _report_amount($data->_sub_total ?? 0); ?></th>
                                    </tr>
                                   
                                    <tr>
                                      <th class="text-right" ><b>Discount</b></th>
                                      <th class="text-right"><?php echo _report_amount($data->_total_discount ?? 0); ?></th>
                                    </tr>
                                   
                                    <?php if($form_settings->_show_vat==1): ?>
                                    <tr>
                                      <th class="text-right" ><b>VAT</b></th>
                                      <th class="text-right"><?php echo _report_amount($data->_total_vat ?? 0); ?></th>
                                    </tr>
                                    <?php endif; ?>
                                    <tr>
                                      <th class="text-right" ><b>Net Total</b></th>
                                      <th class="text-right"><?php echo _report_amount($data->_total ?? 0); ?></th>
                                    </tr>
                                     <?php
                                    $accounts = $data->purchase_account ?? [];
                                    $_due_amount =$data->_total ?? 0;
                                    ?>
                                    <?php if(sizeof($accounts) > 0): ?>
                                    <?php $__currentLoopData = $accounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ac_val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php if($ac_val->_ledger->id !=$data->_ledger_id): ?>
                                     <?php if($ac_val->_cr_amount > 0): ?>
                                     <?php
                                      $_due_amount -=$ac_val->_cr_amount ?? 0;
                                     ?>
                                    <tr>
                                      <th class="text-right" ><b> Less:<?php echo $ac_val->_ledger->_name ?? ''; ?>

                                        </b></th>
                                      <th class="text-right"><?php echo _report_amount( $ac_val->_cr_amount ?? 0 ); ?></th>
                                    </tr>
                                    <?php endif; ?>
                                    <?php if($ac_val->_dr_amount > 0): ?>
                                     <?php
                                      $_due_amount +=$ac_val->_dr_amount ?? 0;
                                     ?>
                                    <tr>
                                      <th class="text-right" ><b> Add:<?php echo $ac_val->_ledger->_name ?? ''; ?>

                                        </b></th>
                                      <th class="text-right"><?php echo _report_amount( $ac_val->_dr_amount ?? 0 ); ?></th>
                                    </tr>
                                    <?php endif; ?>

                                    <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                      <th class="text-right" ><b>Invoice Due </b></th>
                                      <th class="text-right"><?php echo _report_amount( $_due_amount); ?></th>
                                    </tr>

                                    <?php endif; ?>
                                    <?php if($form_settings->_show_p_balance==1): ?>
                                    <tr>
                                      <th class="text-right" ><b>Previous Balance</b></th>
                                      <th class="text-right"><?php echo _show_amount_dr_cr(_report_amount($data->_p_balance ?? 0)); ?></th>
                                    </tr>
                                    <tr>
                                      <th class="text-right" ><b>Current Balance</b></th>
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
                 <td colspan="9">
                    <?php echo $__env->make('backend.message.invoice_footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                 </td>
               </tr> 
        </tfoot>
       </table>
      </div>

     </div>

   </div>
 </section>

   <?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>


<?php $__env->stopSection(); ?>
<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\own\inv-acc-hrm\resources\views/backend/purchase/print_2.blade.php ENDPATH**/ ?>