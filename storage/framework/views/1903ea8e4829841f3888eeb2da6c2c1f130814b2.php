
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo e($page_name); ?></title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo e(asset('dist/css/adminlte.min.css')); ?>">
  <style type="text/css">
    .table td, .table th {
        padding: .15rem !important;
        vertical-align: top;
        border-top: 1px solid #CCCCCC;
    }
  </style>
</head>
<body>
<div class="wrapper">

<section class="invoice">
    <!-- title row -->
    <div class="row">
      <div class="col-12">
        <h2 class="page-header">
           <img src="<?php echo e(asset('/')); ?><?php echo e($settings->logo ?? ''); ?>" alt="<?php echo e($settings->name ?? ''); ?>"  style="width: 60px;height: 60px;"> <?php echo e($settings->name ?? ''); ?>

          <small class="float-right">Date: <?php echo e(change_date_format($current_date ?? '')); ?> Time:<?php echo e($current_time); ?></small>
        </h2>
      </div>
      <!-- /.col -->
    </div>
    <!-- info row -->
    <div class="row invoice-info">
      <div class="col-sm-4 invoice-col">
        
      </div>
      <!-- /.col -->
      <div class="col-sm-4 invoice-col">
        <h3 class="text-center"><b><?php echo e($page_name); ?> Details</b></h3>
      </div>
      <!-- /.col -->
      <div class="col-sm-4 invoice-col text-right">
      
      </div>
      <!-- /.col -->
    </div>
  
<div class="table-responsive">
                  
                  <table class="table table-bordered">
                      <tr>
                         <th class=" _nv_th_action _action_big"><b>Action</b></th>
                         <th class="_nv_th_id _no"><b>ID</b></th>
                         <th class="_nv_th_date"><b>Date</b></th>
                         <th class="_nv_th_date"><b>Branch</b></th>
                         <th class="_nv_th_code"><b>Order Number</b></th>
                         <th class="_nv_th_type"><b>Order Ref</b></th>
                         <th class="_nv_th_amount"><b>Referance</b></th>
                         <th class="_nv_th_ref"><b>Ledger</b></th>
                         <th class="_nv_th_branch"><b>Sub Total</b></th>
                         <th class="_nv_th_user"><b>VAT</b></th>
                         <th class="_nv_th_user"><b>Total</b></th>
                         <th class="_nv_th_note"><b>User</b></th>
                      </tr>
                      <?php
                      $sum_of_amount=0;
                      ?>
                        <?php $__currentLoopData = $datas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                           $sum_of_amount += $data->_total ?? 0;
                        ?>
                        <tr>
                            
                             <td>
                                <?php echo e(($key+1)); ?>

                            </td>
                            <td><?php echo e($data->id); ?></td>
                            <td><?php echo e($data->_date ?? ''); ?></td>
                            <td><?php echo e($data->_master_branch->_name ?? ''); ?></td>

                            <td><?php echo e($data->_order_number ?? ''); ?></td>
                            <td><?php echo e($data->_order_ref_id ?? ''); ?></td>
                            <td><?php echo e($data->_referance ?? ''); ?></td>
                            <td><?php echo e($data->_ledger->_name ?? ''); ?></td>
                            <td><?php echo e(_report_amount( $data->_sub_total ?? 0)); ?> </td>
                            <td><?php echo e(_report_amount( $data->_total_vat ?? 0)); ?> </td>
                            <td><?php echo e(_report_amount( $data->_total ?? 0)); ?> </td>
                            <td><?php echo e($data->_user_name ?? ''); ?></td>
                            
                           
                        </tr>
                        <?php if(sizeof($data->_master_details) > 0): ?>
                        <tr>
                          <td colspan="12" >
                          
                              <table class="table">
                                <thead >
                                            <th class="text-middle" >&nbsp;</th>
                                            <th class="text-middle" >Item</th>
                                           <?php if(isset($form_settings->_show_barcode)): ?> <?php if($form_settings->_show_barcode==1): ?>
                                            <th class="text-middle" >Barcode</th>
                                            <?php else: ?>
                                            <th class="text-middle display_none" >Barcode</th>
                                            <?php endif; ?>
                                            <?php endif; ?>
                                            <th class="text-middle" >Qty</th>
                                            <th class="text-middle" >Rate</th>
                                            <th class="text-middle" >Sales Rate</th>
                                            <?php if(isset($form_settings->_show_vat)): ?> <?php if($form_settings->_show_vat==1): ?>
                                            <th class="text-middle" >VAT%</th>
                                            <th class="text-middle" >VAT</th>
                                             <?php else: ?>
                                            <th class="text-middle display_none" >VAT%</th>
                                            <th class="text-middle display_none" >VAT Amount</th>
                                            <?php endif; ?>
                                            <?php endif; ?>

                                            <th class="text-middle" >Value</th>
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
                                             <?php if(sizeof($store_houses) > 1): ?>
                                            <th class="text-middle" >Store</th>
                                            <?php else: ?>
                                             <th class="text-middle display_none" >Store</th>
                                            <?php endif; ?>
                                            <?php if(isset($form_settings->_show_self)): ?> <?php if($form_settings->_show_self==1): ?>
                                            <th class="text-middle" >Shelf</th>
                                            <?php else: ?>
                                             <th class="text-middle display_none" >Shelf</th>
                                            <?php endif; ?>
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
                                     <th class="" ><?php echo e($_item->id); ?></th>
                                     <?php
                                      $_value_total +=$_item->_value ?? 0;
                                      $_vat_total += $_item->_vat_amount ?? 0;
                                      $_qty_total += $_item->_qty ?? 0;
                                     ?>
                                            <td class="" ><?php echo $_item->_items->_name ?? ''; ?></td>
                                           <?php if(isset($form_settings->_show_barcode)): ?> <?php if($form_settings->_show_barcode==1): ?>
                                            <td class="" ><?php echo $_item->_barcode ?? ''; ?></td>
                                            <?php else: ?>
                                            <td class=" display_none" ><?php echo $_item->_barcode ?? ''; ?></td>
                                            <?php endif; ?>
                                            <?php endif; ?>
                                            <td class="text-right" ><?php echo $_item->_qty ?? 0; ?></td>
                                            <td class="text-right" ><?php echo _report_amount($_item->_rate ?? 0); ?></td>
                                            <td class="text-right" ><?php echo _report_amount($_item->_sales_rate ?? 0); ?></td>
                                            <?php if(isset($form_settings->_show_vat)): ?> <?php if($form_settings->_show_vat==1): ?>
                                            <td class="text-right" ><?php echo $_item->_vat ?? 0; ?></td>
                                            <td class="text-right" ><?php echo _report_amount($_item->_vat_amount ?? 0); ?></td>
                                             <?php else: ?>
                                            <td class="text-right display_none" ><?php echo $_item->_vat ?? 0; ?></td>
                                            <td class="text-right display_none" ><?php echo _report_amount($_item->_vat_amount ?? 0); ?></td>
                                            <?php endif; ?>
                                            <?php endif; ?>

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
                                             <?php if(sizeof($store_houses) > 1): ?>
                                            <td class="" ><?php echo $_item->_store->_name ?? ''; ?></td>
                                            <?php else: ?>
                                             <td class=" display_none" ><?php echo $_item->_store->_name ?? ''; ?></td>
                                            <?php endif; ?>
                                            <?php if(isset($form_settings->_show_self)): ?> <?php if($form_settings->_show_self==1): ?>
                                            <td class="" ><?php echo $_item->_store_salves_id ?? ''; ?></td>
                                            <?php else: ?>
                                             <td class=" display_none" ><?php echo $_item->_store_salves_id ?? ''; ?></td>
                                            <?php endif; ?>
                                            <?php endif; ?>
                                           
                                          </thead>
                                  </tr>
                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                  <?php endif; ?>
                                </tbody>
                                <tfoot>
                                  <tr>
                                              <td>
                                                
                                              </td>
                                              <td  class="text-right"><b>Total</b></td>
                                              <?php if(isset($form_settings->_show_barcode)): ?> <?php if($form_settings->_show_barcode==1): ?>
                                              <td  class="text-right"></td>
                                              <?php else: ?>
                                                <td  class="text-right display_none"></td>
                                             <?php endif; ?>
                                            <?php endif; ?>
                                              <td class="text-right">
                                                <b><?php echo e($_qty_total ?? 0); ?></b>
                                                


                                              </td>
                                              <td></td>
                                              <td></td>
                                              <?php if(isset($form_settings->_show_vat)): ?> <?php if($form_settings->_show_vat==1): ?>
                                              <td></td>
                                              <td class="text-right">
                                                <b><?php echo e(_report_amount($_vat_total ?? 0)); ?></b>
                                              </td>
                                              <?php else: ?>
                                              <td class="display_none"></td>
                                              <td class="text-right display_none">
                                                 <b><?php echo e(_report_amount($_vat_total ?? 0)); ?></b>
                                              </td>
                                              <?php endif; ?>
                                              <?php endif; ?>
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
                                              <?php if(sizeof($store_houses) > 1): ?>
                                              <td></td>
                                              <?php else: ?>
                                               <td class="display_none"></td>
                                              <?php endif; ?>

                                              <?php if(isset($form_settings->_show_self)): ?> <?php if($form_settings->_show_self==1): ?>
                                              <td></td>
                                              <?php else: ?>
                                              <?php endif; ?>
                                              <td class="display_none"></td>
                                              <?php endif; ?>
                                            </tr>
                                </tfoot>
                              </table>
                           
                        </td>
                        </tr>
                        <?php endif; ?>
                        <?php if(sizeof($data->s_account) > 0): ?>
                        <tr>
                          <td colspan="12" >
                           
                              <table class="table">
                                <thead>
                                  <th>ID</th>
                                  <th>Ledger</th>
                                  <th>Branch</th>
                                  <th>Cost Center</th>
                                  <th>Short Narr.</th>
                                  <th class="text-right" >Dr. Amount</th>
                                  <th class="text-right" >Cr. Amount</th>
                                </thead>
                                <tbody>
                                  <?php
                                    $_dr_amount = 0;
                                    $_cr_amount = 0;
                                  ?>
                                  <?php $__empty_1 = true; $__currentLoopData = $data->s_account; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail_key=>$_master_val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                  <tr>
                                    <td><?php echo e(($_master_val->id)); ?></td>
                                    <td><?php echo e($_master_val->_ledger->_name ?? ''); ?></td>
                                    <td><?php echo e($_master_val->_detail_branch->_name ?? ''); ?></td>
                                    <td><?php echo e($_master_val->_detail_cost_center->_name ?? ''); ?></td>
                                    <td><?php echo e($_master_val->_short_narr ?? ''); ?></td>
                  <td class="text-right"><?php echo e(_report_amount( $_master_val->_dr_amount ?? 0)); ?></td>
                  <td class="text-right"> <?php echo e(_report_amount( $_master_val->_cr_amount ?? 0)); ?> </td>
                                    <?php 
                                    $_dr_amount += $_master_val->_dr_amount;   
                                    $_cr_amount += $_master_val->_cr_amount;  
                                    ?>
                                  </tr>
                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                  <?php endif; ?>
                                </tbody>
                                <tfoot>
                                  <tr>
                                    <td colspan="5" class="text-right"><b>Total</b></td>
                                    <td  class="text-right"><b><?php echo e(_report_amount($_dr_amount ?? 0 )); ?> </b></td>
                                    <td  class="text-right"><b><?php echo e(_report_amount( $_cr_amount ?? 0 )); ?> </b></td>
                                    
                                  </tr>
                                </tfoot>
                              </table>
                           
                        </td>
                        </tr>
                        <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                          <td colspan="10" class="text-center"><b>Total</b></td>
                          <td><b><?php echo e(_report_amount($sum_of_amount)); ?> </b></td>
                          <td></td>
                        </tr>

                    </table>
                </div>
  </section>

</div>
<!-- ./wrapper -->
<!-- Page specific script -->
<script>
  window.addEventListener("load", window.print());
</script>
</body>
</html><?php /**PATH D:\xampp\htdocs\own\inv-acc-hrm\resources\views/backend/sales/details_print.blade.php ENDPATH**/ ?>