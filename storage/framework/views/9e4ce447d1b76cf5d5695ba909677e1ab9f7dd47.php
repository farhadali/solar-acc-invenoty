

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
    <a class="nav-link"  href="<?php echo e(url('material-issue')); ?>" role="button"><i class="fa fa-arrow-left"></i></a>
 <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('material-issue-edit')): ?>
    <a class="nav-link"  title="Edit" href="<?php echo e(route('material-issue.edit',$data->id)); ?>">
                                      <i class="nav-icon fas fa-edit"></i>
     </a>
  <?php endif; ?>
    <a style="cursor: pointer;" class="nav-link"  title="Print" onclick="javascript:printDiv('printablediv')"><i class="fas fa-print"></i></a>
      <a style="cursor: pointer;" onclick="fnExcelReport();" class="nav-link"  title="Excel Download" ><i class="fa fa-file-excel" aria-hidden="true"></i></a>
       <?php echo $__env->make('backend.message.message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
  </div>

<section class="invoice" id="printablediv">
   <div class="container-fluid">
    <?php
    $_is_header = $form_settings->_is_header ?? 0;
    $_is_footer = $form_settings->_is_footer ?? 0;
    $_margin_top = $form_settings->_margin_top ?? '0px';
    $_margin_bottom = $form_settings->_margin_bottom ?? '0px';
    $_margin_left = $form_settings->_margin_left ?? '0px';
    $_margin_right = $form_settings->_margin_right ?? '0px';
  ?>
   <div style="width: 100%;margin-bottom: <?php echo e($_margin_bottom); ?>;margin-top: <?php echo e($_margin_top); ?>;margin-left: <?php echo e($_margin_left); ?>;margin-right: <?php echo e($_margin_right); ?>" > 
   
    <!-- /.row -->
    <div class="row">
      <div class="col-12">
        <table class="table" style="border:none;">
          <tr>
            <td style="border:none;width: 33%;text-align: left;">
              <table class="table" style="border:none;">
                  <tr> <td style="border:none;font-size: 20px;" >  <?php echo e(invoice_barcode($data->_order_number ?? '')); ?></td></tr>
                  <tr> <td style="border:none;font-size: 20px;" > <b>INVOICE NO: <?php echo e($data->_order_number ?? ''); ?></b></td></tr>
                  <tr> <td style="border:none;font-size: 20px;" > <b>Date: </b><?php echo e(_view_date_formate($data->_date ?? '')); ?></td></tr>
                <tr> <td style="border:none;font-size: 20px;" > <b> Ledger:</b><?php if($form_settings->_defaut_customer ==$data->_ledger_id): ?>
                      <?php echo e($data->_referance ?? $data->_ledger->_name); ?>

                  <?php else: ?>
                  <?php echo e($data->_ledger->_name ?? ''); ?>

                  <?php endif; ?>

                  
                  
                  
                </td></tr>
                <tr> <td style="border:none;font-size: 20px;" > <b> Phone:</b>  <?php echo e($data->_phone ?? ''); ?> </td></tr>
                <tr> <td style="border:none;font-size: 20px;" > <b> Address:</b> <?php echo e($data->_address ?? ''); ?> </td></tr>
              </table>
            </td>
            <td style="border:none;width: 33%;text-align: center;">
               <?php if($_is_header ==1 ): ?>
              <table class="table" style="border:none;font-size: 20px;">
                <tr> <td class="text-center" style="border:none;font-size: 24px;"><?php echo e($settings->_top_title ?? ''); ?><br></td> </tr>
                <tr> <td class="text-center" style="border:none;font-size: 24px;"><b><?php echo e($settings->name ?? ''); ?></b></td> </tr>
                <tr> <td class="text-center" style="border:none;font-size: 20px;"><b><?php echo e($settings->_address ?? ''); ?></b></td></tr>
                <tr> <td class="text-center" style="border:none;font-size: 20px;"><b><?php echo e($settings->_phone ?? ''); ?></b>,<b><?php echo e($settings->_email ?? ''); ?></b></td></tr>
                 <tr> <td class="text-center" style="border:none;font-size: 20px;"><h3><?php echo e($page_name); ?> </h3></td> </tr>
              </table>
              <?php endif; ?>
            </td>
            <td style="border:none;width: 33%;text-align: right;">
              <table class="table" style="border:none;font-size: 20px;">
                  <tr> <td class="text-right" style="border:none;font-size: 20px;"  > <b>Time:</b> <?php echo e($data->_time ?? ''); ?> </td></tr>
                  <tr> <td class="text-right"  style="border:none;font-size: 20px;" > <b>Created By:</b> <?php echo e($data->_user_name ?? ''); ?></td></tr>
                  <tr> <td class="text-right"  style="border:none;font-size: 20px;" > <b>Branch:</b> <?php echo e($data->_master_branch->_name ?? ''); ?> </td></tr>
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
                                            <th class="text-left "  >SL</th>
                                            <th class="text-left "  >Item</th>
                                            <th class="text-left "  >Unit</th>
                                            <th class="text-middle  <?php if($form_settings->_show_barcode==0): ?> display_none <?php endif; ?>"  >Barcode</th>
                                            <th class="text-right "  >Qty</th>
                                            <th class="text-right "  >Rate</th>
                                            <th class="text-right <?php if($form_settings->_show_vat==0): ?> display_none <?php endif; ?>"  >VAT%</th>
                                            <th class="text-right <?php if($form_settings->_show_vat==0): ?> display_none <?php endif; ?>"  >VAT Amount</th>
                                            <th class="text-right  <?php if($form_settings->_inline_discount==0): ?> display_none <?php endif; ?>"  >Dis%</th>
                                            <th class="text-right  <?php if($form_settings->_inline_discount==0): ?> display_none <?php endif; ?>"  >Discount</th>
                                            <th class="text-right "  >Value</th>
                                            <th class="text-middle   <?php if(sizeof($permited_branch) ==1): ?> display_none <?php endif; ?> "  >Branch</th>
                                             <th class="text-middle   <?php if(sizeof($permited_costcenters) ==1): ?> display_none <?php endif; ?> "  >Cost Center</th>
                                             <th class="text-middle  <?php if(sizeof($store_houses) ==1): ?> display_none <?php endif; ?>"  >Store</th>
                                             <th class="text-middle <?php if($form_settings->_show_self==0): ?> display_none <?php endif; ?>"  >Shelf</th>
                                            
                                           
                                          </thead>
                                <tbody>
                                  <?php
                                    $_value_total = 0;
                                    $_vat_total = 0;
                                    $_qty_total = 0;
                                    $_total_discount_amount = 0;
                                  ?>
                                  <?php $__empty_1 = true; $__currentLoopData = $data->_master_details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item_key=>$_item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                  <tr>
                                     <td class="" ><?php echo e(($item_key+1)); ?></td>
                                     <?php
                                      $_value_total +=$_item->_value ?? 0;
                                      $_vat_total += $_item->_vat_amount ?? 0;
                                      $_qty_total += $_item->_qty ?? 0;
                                      $_total_discount_amount += $_item->_discount_amount ?? 0;
                                     ?>
                                            <td class="  " ><?php echo $_item->_items->_name ?? ''; ?></td>
                                            <td class="  " ><?php echo $_item->_trans_unit->_name ?? ''; ?></td>
                                            <td class="   <?php if($form_settings->_show_barcode==0): ?> display_none <?php endif; ?>" >

                                               <?php
                                          $barcode_arrays = explode(',', $_item->_barcode ?? '');
                                          ?>
                                          <?php $__empty_2 = true; $__currentLoopData = $barcode_arrays; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $barcode): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                                        <span><?php echo e($barcode); ?></span><br>
                                          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                                          <?php endif; ?>
                                             
                                            </td>
                                            <td class="text-right  " ><?php echo $_item->_qty ?? 0; ?></td>
                                            <td class="text-right  " ><?php echo _report_amount($_item->_sales_rate ?? 0); ?></td>
                                            <td class="text-right   <?php if($form_settings->_show_vat==0): ?> display_none <?php endif; ?>" ><?php echo $_item->_vat ?? 0; ?></td>
                                            <td class="text-right   <?php if($form_settings->_show_vat==0): ?> display_none <?php endif; ?>" ><?php echo _report_amount($_item->_vat_amount ?? 0); ?></td>
                                            <td class="text-right   <?php if($form_settings->_inline_discount==0): ?> display_none <?php endif; ?>" ><?php echo $_item->_discount ?? 0; ?></td>
                                            <td class="text-right   <?php if($form_settings->_inline_discount==0): ?> display_none <?php endif; ?>" ><?php echo $_item->_discount_amount ?? 0; ?></td>
                                            <td class="text-right  " ><?php echo _report_amount($_item->_value ?? 0); ?></td>
                                            <td class=" <?php if(sizeof($permited_branch) == 1): ?>  display_none <?php endif; ?>" ><?php echo $_item->_detail_branch->_name ?? ''; ?></td>
                                             <td class="<?php if(sizeof($permited_costcenters) == 1): ?>  display_none <?php endif; ?>" ><?php echo $_item->_detail_cost_center->_name ?? ''; ?></td>
                                             <td class=" <?php if(sizeof($store_houses) == 1): ?>  display_none <?php endif; ?>" ><?php echo $_item->_store->_name ?? ''; ?></td>
                                             <td class="<?php if($form_settings->_show_self==0): ?> display_none <?php endif; ?>" ><?php echo $_item->_store_salves_id ?? ''; ?></td>
                                            
                                           
                                          
                                  </tr>
                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                  <?php endif; ?>
                                </tbody>
                                <tfoot>
                                  <tr>
                                              <td class=""> </td>
                                              <td colspan="2"  class="text-right "><b>Total</b></td>
                                              <?php if(isset($form_settings->_show_barcode)): ?> <?php if($form_settings->_show_barcode==1): ?>
                                              <td  class="text-right"></td>
                                              <?php else: ?>
                                                <td  class="text-right  display_none"></td>
                                             <?php endif; ?>
                                            <?php endif; ?>
                                              <td class="text-right ">
                                                <b><?php echo e($_qty_total ?? 0); ?></b>
                                                


                                              </td>
                                              <td class="display_none"></td>
                                              <td class=""></td>
                                              
                                              <td class=" <?php if($form_settings->_show_vat==0): ?> display_none <?php endif; ?> "></td>
                                              <td class=" text-right <?php if($form_settings->_show_vat==0): ?> display_none <?php endif; ?> ">
                                                 <b><?php echo e(_report_amount($_vat_total ?? 0)); ?></b>
                                              </td>
                                              
                                            <td class=" text-right <?php if($form_settings->_inline_discount==0): ?> display_none <?php endif; ?> " ></td>
                                            <td class=" text-right <?php if($form_settings->_inline_discount==0): ?> display_none <?php endif; ?> " ><b><?php echo $_total_discount_amount ?? 0; ?></b></td>
                                            
                                              <td class=" text-right">
                                               <b> <?php echo e(_report_amount($_value_total ?? 0)); ?></b>
                                              </td>
                                               <td class=" <?php if(sizeof($permited_branch) == 1): ?> display_none <?php endif; ?>"></td>
                                               <td class=" <?php if(sizeof($permited_costcenters) == 1): ?> display_none <?php endif; ?>"></td>
                                               <td class=" <?php if(sizeof($store_houses) == 1): ?> display_none <?php endif; ?>"></td>
                                              <td class="<?php if($form_settings->_show_self==0): ?> display_none <?php endif; ?> "></td>
                                             
                                            </tr>
                                </tfoot>
                              </table>
                           
                          </div>
                        </td>
                        </tr>
                        <?php endif; ?>
         
      </div>

   
     <div class="row">
      <div class="col-12 table-responsive">
        <table class="table">
          
          <tbody>
           
          <tr>
            <th class="text-right" ><b>Sub Total</b></th>
            <th class="text-right"><?php echo _report_amount($data->_sub_total ?? 0); ?></th>
            <th class="<?php if($form_settings->_show_self==0): ?> display_none <?php endif; ?> "></th>
          </tr>
         
          <tr>
            <th class="text-right" ><b> Discount[-]</b></th>
            <th class="text-right"><?php echo _report_amount($data->_total_discount ?? 0); ?></th>
            <th class="<?php if($form_settings->_show_self==0): ?> display_none <?php endif; ?> "></th>
          </tr>
         
          <?php if($form_settings->_show_vat==1): ?>
          <tr>
            <th class="text-right" ><b>VAT[+] </b></th>
            <th class="text-right"><?php echo _report_amount($data->_total_vat ?? 0); ?></th>
            <th class="<?php if($form_settings->_show_self==0): ?> display_none <?php endif; ?> "></th>
          </tr>
          <?php endif; ?>
          <tr>
            <th class="text-right" ><b>NET Total</b></th>
            <th class="text-right"><?php echo _report_amount($data->_total ?? 0); ?></th>
            <th class="<?php if($form_settings->_show_self==0): ?> display_none <?php endif; ?> "></th>
          </tr>
          <?php if($form_settings->_show_p_balance==1): ?>
          <tr>
            <th class="text-right" ><b>Previous Balance</b></th>
            <th class="text-right"><?php echo _show_amount_dr_cr(_report_amount($data->_p_balance ?? 0)); ?></th>
            <th class="<?php if($form_settings->_show_self==0): ?> display_none <?php endif; ?> "></th>
          </tr>
          <tr>
            <th class="text-right" ><b>Current Balance</b></th>
            <th class="text-right"><?php echo _show_amount_dr_cr(_report_amount($data->_l_balance ?? 0)); ?></th>
            <th class="<?php if($form_settings->_show_self==0): ?> display_none <?php endif; ?> "></th>
          </tr>
          <?php endif; ?>


          
          </tbody>
          
        </table>
      </div>
      <!-- /.col -->
    </div>
     <!-- Table row -->
    

    

    <div class="row">
    <div class="col-12">
       
        <p class="lead"> <b>In Words:  <?php echo e(nv_number_to_text($data->_total ?? 0)); ?> </b></p>
        <?php echo $__env->make("backend.sales.invoice_history", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
      </div>
      
      <!-- /.col -->
      <?php echo $__env->make('backend.message.invoice_footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
      <!-- /.col -->
    </div>
  </div>
    <!-- /.row -->
  </div>
  </section>


<!-- Page specific script -->

<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\own\inv-acc-hrm\resources\views/backend/material-issue/print.blade.php ENDPATH**/ ?>