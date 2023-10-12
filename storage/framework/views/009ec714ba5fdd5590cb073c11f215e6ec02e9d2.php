
<?php $__env->startSection('title',$page_name); ?>

<?php $__env->startSection('content'); ?>
<style type="text/css">
 
  @media  print {
   .table th {
    vertical-align: top;
    color: silver;
    background-color: #fff; 
}

}
  </style>
  <style>
  
    thead {
      display: table-header-group;
    }
    tfoot {
      display: table-footer-group;
    }
    @media  print {
      thead {
        display: table-header-group;
      }
      tfoot {
        display: table-footer-group;
      }
      td,th{
            font-size:22px !important;
        }
    }
     td,th{
            font-size:22px;
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
 <div class="container-fluid">
  <?php
    $_is_header = $form_settings->_is_header ?? 0;
    $_is_footer = $form_settings->_is_footer ?? 0;
    $_margin_top = $form_settings->_margin_top ?? '0px';
    $_margin_bottom = $form_settings->_margin_bottom ?? '0px';
    $_margin_left = $form_settings->_margin_left ?? '0px';
    $_margin_right = $form_settings->_margin_right ?? '0px';
  ?>
   <div  > 

     

       <table style="width: 100%;margin-bottom: <?php echo e($_margin_bottom); ?>;margin-top: <?php echo e($_margin_top); ?>;margin-left: <?php echo e($_margin_left); ?>;margin-right: <?php echo e($_margin_right); ?>">
        <thead style="border:0px;">
             <?php if($_is_header ==1 ): ?>
            <tr style="border: 0px solid silver !important;">
                <th colspan="8" style="width: 100%;border: 0px solid silver !important;white-space: inherit;">
                   
     <div class="row">
      <div class="col-3">
            <h3>  </h3>
        
        
      </div>
      <div class="col-6 " style="text-align: center;white-space: inherit;">
            <h2 style="text-align: center;"><?php echo e($settings->name ?? ''); ?></h2>
            <h5 style="text-align: center;"><?php echo e($settings->keywords ?? ''); ?></h5>
           <div><?php echo e($settings->_address ?? ''); ?><br>
            Phone: <?php echo e($settings->_phone ?? ''); ?><br>
            Email: <?php echo e($settings->_email ?? ''); ?></div>
      </div>
      <div class="col-3 "></div>
      <div class="col-md-12" >
        <div style="text-align: center;">
          <span style="font-size: 30px;
    font-weight: bold;
    padding: 5px;
    background: #34ce19;
    border-radius: 5px;">Sales Invoice</span> 
        </div>
        
      </div>
     </div>

                </th>
            </tr>
    <?php endif; ?>
    <tr>
        <th colspan="8" style="border: 0px solid silver;">
            <table style="width: 100%;"  > 


  
          <tr>
            <td rowspan="6" style="width: 50%;;border: 1px solid silver;">
                <table style="width: 100%;">
                  <tr>
                    <td style="width: 20%;text-align: left;vertical-align:top;"><b>Customer ID</b></td>
                    
                    <td style="width: 80%;text-align: left;white-space: break-spaces;font-weight:900;font-size:24px;vertical-align:top;">:<?php echo e($data->_ledger->id ?? ''); ?></td>
                  </tr>
                  <tr>
                    <td style="width: 20%;text-align: left;vertical-align:top;"><strong>Customer Name</strong></td>
                    <td style="width: 80%;text-align: left;font-weight:900;font-size:24px;vertical-align:top;">:<?php if($data->_referance !=""): ?>
                    <?php echo e($data->_referance ?? ''); ?>

                  <?php else: ?>
                    <?php echo e($data->_ledger->_name ?? ''); ?>

                  <?php endif; ?></td>
                  </tr>
                  <?php
                  $_alious = $data->_ledger->_alious ?? '';
                  ?>
                  <?php if($_alious !=''): ?>
                  <tr>
                    <td style="width: 20%;text-align: left;vertical-align:top;">Proprietor</td>
                    <td  style="width: 80%;text-align: left;white-space: break-spaces;font-weight:400;vertical-align:top;">:<?php echo e($data->_ledger->_alious ?? ''); ?></td>
                  </tr>
                  <?php endif; ?>
                  <tr>
                    <td style="width: 20%;text-align: left;vertical-align:top;">Cell NO</td>
                    <td  style="width: 80%;text-align: left;white-space: break-spaces;font-weight:400;vertical-align:top;">:<?php echo e($data->_phone ?? ''); ?></td>
                  </tr>
                  <tr>
                    <td  style="width: 20%;text-align: left;vertical-align:top;" >Address</td>
                    <td  style="width: 80%;text-align: left;white-space: break-spaces;font-weight:400;vertical-align:top;">:<?php echo e($data->_address ?? ''); ?></td>
                  </tr>
                  <tr>
                    <td><b></b></td>
                    <td><b></b></td>
                  </tr>
                </table>
            </td>
            <td style="width: 25%;border: 1px solid silver;">Invoice No: </td>
            <td style="width: 25%;border: 1px solid silver;">
              <?php echo e(invoice_barcode($data->_order_number ?? '')); ?>

              <?php echo e($data->_order_number ?? ''); ?> </td>
          </tr>
         <tr>
            <td style="width: 25%;border: 1px solid silver;">Invoice Date</td>
            <td style="width: 25%;border: 1px solid silver;"><?php echo _view_date_formate($data->_date ?? ''); ?></td>
          </tr><tr>
            <td style="width: 25%;border: 1px solid silver;">Sales By</td>
            <td style="width: 15%;border: 1px solid silver;"><?php echo $data->_user_name ?? ''; ?></td>
          </tr>
          <?php if($data->_order_ref_id !=''): ?>
          <tr>
            <td style="width: 25%;border: 1px solid silver;">Sales Order NO</td>
            <td style="width: 15%;border: 1px solid silver;"><?php echo $data->_order_ref_id ?? ''; ?></td>
          </tr>
          <?php endif; ?>
        </table>
        </th>
    </tr>
         <tr>
           <th class="text-center" style="width: 5%;border: 1px solid silver;">SL</th>
          <th class="text-left" style="width: 53%;border: 1px solid silver;">Product Name</th>
          <th class="text-center" style="width: 7%;border: 1px solid silver;">Unit</th>
          <th class="text-center" style="width: 7%;border: 1px solid silver;">Qty</th>
          <th class="text-center" style="width: 8%;border: 1px solid silver;">Rate</th>
          <th class="text-center" style="width: 5%;border: 1px solid silver;">Discount</th>
          <th class="text-center display_none" style="width: 5%;border: 1px solid silver;">VAT</th>
          <th class="text-center" style="width: 10%;border: 1px solid silver;">Amount</th>
         </tr>
        </thead>
        <tbody>
           <?php if(sizeof($_master_detail_reassign) > 0): ?>
         <?php
                                    $_value_total = 0;
                                    $_vat_total = 0;
                                    $_qty_total = 0;
                                    $_total_discount_amount = 0;
                                    $id=1;
                                  ?>
                                  <?php $__empty_1 = true; $__currentLoopData = $_master_detail_reassign; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item_key=>$_item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                  <tr>
                                     <td class="text-center" style="border: 1px solid silver;vertical-align:top;" ><?php echo e(($id)); ?>.</td>

                                    

                              <?php if(sizeof($_item) > 0): ?>
                                     
                                          <td class="  " style="word-break: break-all;vertical-align: text-top;border: 1px solid silver;vertical-align:top;">
                                            
                                           
                              <?php $__empty_2 = true; $__currentLoopData = $_item; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $_in_item_key=>$in_itemVal_multi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                                    <?php
                                      $_value_total +=$in_itemVal_multi->_value ?? 0;
                                      $_vat_total += $in_itemVal_multi->_vat_amount ?? 0;
                                      $_qty_total += $in_itemVal_multi->_qty ?? 0;
                                      $_total_discount_amount += $in_itemVal_multi->_discount_amount ?? 0;
                                     ?>
                                     <?php if($_in_item_key==0): ?>
                                            <?php echo $in_itemVal_multi->_items->_name ?? ''; ?> 
                                    <?php endif; ?>
                                          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                                    <?php endif; ?> 



                                    <?php $__empty_2 = true; $__currentLoopData = $_item; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $_in_item_key=>$in_itemVal_multi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                                            <?php 
                                                  $_barcodes_string = $in_itemVal_multi->_barcode ?? '';
                                                  $_barcodes_string_length = strlen($in_itemVal_multi->_barcode ?? '');
                                                  $_barcodes = explode(",",$_barcodes_string);
                                              ?>
                                              <?php if($_barcodes_string_length > 0): ?>
                                              
                                              <?php if($_in_item_key==0): ?><br> <b>SN:</b> <?php endif; ?>
                                                  <?php $__empty_3 = true; $__currentLoopData = $_barcodes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $barcode): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_3 = false; ?>
                                                    <small ><?php echo e($barcode ?? ''); ?>,</small>
                                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_3): ?>
                                                  <?php endif; ?>
                                              <?php endif; ?>

                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                                    <?php endif; ?>

                                      <?php $__empty_2 = true; $__currentLoopData = $_item; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $_in_item_key=>$in_itemVal_multi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                                      <?php if($_in_item_key==0): ?>
                                              <?php
                                         $warenty_name= $in_itemVal_multi->_warrant->_name ?? '';
                                          ?>

                                          <?php if($warenty_name !=''): ?>
                                          <br>
                                         <b>Warranty:</b>  <?php echo e($in_itemVal_multi->_warrant->_name ?? ''); ?>

                                          <?php endif; ?>
                                    <?php endif; ?>

                                          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                                    <?php endif; ?>
                                          </td>
                                          <td class="text-center  " style="white-space:nowrap;vertical-align: text-top;border: 1px solid silver;vertical-align:top;">
                             <?php $__empty_2 = true; $__currentLoopData = $_item; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $_in_item_key=>$in_itemVal_multi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                              <?php if($_in_item_key==0): ?>
                                  <?php echo $in_itemVal_multi->_items->_units->_name ?? ''; ?>

                              <?php endif; ?>
                              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                              <?php endif; ?>
                                        </td>
                                           
                                            
                                             <td class="text-center  " style="vertical-align: text-top;border: 1px solid silver;vertical-align:top;">
                          <?php
                           $row_qty =0;
                          ?>
                          <?php $__empty_2 = true; $__currentLoopData = $_item; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $_in_item_key=>$in_itemVal_multi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                            <?php
                                 $row_qty +=($in_itemVal_multi->_qty ?? 0);
                             ?>
                          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                          <?php endif; ?>

                                   <?php echo _report_amount($row_qty ?? 0); ?>

                                            </td>
                                            <td class="text-right  " style="vertical-align: text-top;border: 1px solid silver;vertical-align:top;">
                             <?php $__empty_2 = true; $__currentLoopData = $_item; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $_in_item_key=>$in_itemVal_multi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                              <?php if($_in_item_key==0): ?>
                                 <?php echo _report_amount($in_itemVal_multi->_sales_rate ?? 0); ?>

                              <?php endif; ?>
                              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                              <?php endif; ?>
                                              
                                            </td>
                                            <td class="text-right  " style="vertical-align: text-top;border: 1px solid silver;vertical-align:top;">
                            <?php
                           $row_discount_amount =0;
                          ?>
                          <?php $__empty_2 = true; $__currentLoopData = $_item; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $_in_item_key=>$in_itemVal_multi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                            <?php
                                 $row_discount_amount +=($in_itemVal_multi->_discount_amount ?? 0);
                             ?>
                          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                          <?php endif; ?>

                                  <?php echo _report_amount($row_discount_amount ?? 0); ?>


                                            </td>
                                            <td class="text-right display_none " style="vertical-align: text-top;border: 1px solid silver;">
                            <?php
                           $row_vat_amount =0;
                          ?>
                          <?php $__empty_2 = true; $__currentLoopData = $_item; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $_in_item_key=>$in_itemVal_multi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                            <?php
                                 $row_vat_amount +=($in_itemVal_multi->_vat_amount ?? 0);
                             ?>
                          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                          <?php endif; ?>
                                              <?php echo _report_amount($row_vat_amount ?? 0); ?>


                                            </td>
                                            
                                            <td class="text-right  " style="vertical-align: text-top;border: 1px solid silver;">
                            <?php
                           $row__value =0;
                          ?>
                          <?php $__empty_2 = true; $__currentLoopData = $_item; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $_in_item_key=>$in_itemVal_multi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                            <?php
                                 $row__value +=($in_itemVal_multi->_value ?? 0);
                             ?>
                          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                          <?php endif; ?>



                                              <?php echo _report_amount($row__value ?? 0); ?></td>
                                            
                                            
                                    
                              <?php endif; ?>
                                         
                                  </tr>
                                  <?php
                                  $id++;
                                  ?>


                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                  <?php endif; ?>
                            <tr>
                              <td colspan="3" class="text-right " style="border: 1px solid silver;"><b>Total</b></td>
                              <td class="text-right " style="border: 1px solid silver;"> <b><?php echo e(_report_amount($_qty_total ?? 0)); ?></b> </td>
                              <td style="border: 1px solid silver;"></td>
                              <td class="text-right " style="border: 1px solid silver;"> <b><?php echo e(_report_amount($_total_discount_amount ?? 0)); ?></b> </td>
                              <td class="text-right display_none" style="border: 1px solid silver;"> <b><?php echo e(_report_amount($_vat_total ?? 0)); ?></b> </td>
                              <td class=" text-right" style="border: 1px solid silver;"><b> <?php echo e(_report_amount($_value_total ?? 0)); ?></b>
                              </td>
                            </tr>
                            <tr>
                              <td colspan="3" class="text-left " style="width: 50%;border:1px solid silver;">
                              <table style="width: 100%">
                                <tr>
                                  <td>

                                    <?php echo e($settings->_sales_note ?? ''); ?>

                                  </td>
                                </tr>
                                <tr>
                                  <td><p class="lead"> In Words:  <?php echo e(nv_number_to_text($data->_total ?? 0)); ?> </p></td>
                                </tr>
                                <tr>
                                  <td>
                                    <?php echo $__env->make("backend.sales.invoice_history", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                  </td>
                                </tr>
                              </table>
                              </td>
                              
                              <td colspan="5" class=" text-right"  style="width: 50%;">
                                  <table style="width: 100%;">
                                     <tr >
                                      <th style="border:1px solid silver;" class="text-right" ><b>Sub Total</b></th>
                                      <th style="border:1px solid silver;" class="text-right"><?php echo _report_amount($data->_sub_total ?? 0); ?></th>
                                    </tr>
                                   
                                    <tr>
                                      <th style="border:1px solid silver;" class="text-right" ><b>Discount[-]</b></th>
                                      <th style="border:1px solid silver;" class="text-right"><?php echo _report_amount($data->_total_discount ?? 0); ?></th>
                                    </tr>
                                   
                                    <?php if($form_settings->_show_vat==1): ?>
                                    <tr>
                                      <th style="border:1px solid silver;" class="text-right" ><b>VAT[+]</b></th>
                                      <th style="border:1px solid silver;" class="text-right"><?php echo _report_amount($data->_total_vat ?? 0); ?></th>
                                    </tr>
                                    <?php endif; ?>
                                    <tr>
                                      <th style="border:1px solid silver;" class="text-right" ><b>Net Total</b></th>
                                      <th style="border:1px solid silver;" class="text-right"><?php echo _report_amount($data->_total ?? 0); ?></th>
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
                                      <th style="border:1px solid silver;" class="text-right" ><b> <?php echo $ac_val->_ledger->_name ?? ''; ?>[+]
                                        </b></th>
                                      <th style="border:1px solid silver;" class="text-right"><?php echo _report_amount( $ac_val->_cr_amount ?? 0 ); ?></th>
                                    </tr>
                                    <?php endif; ?>
                                    <?php if($ac_val->_dr_amount > 0): ?>
                                     <?php
                                      $_due_amount -=$ac_val->_dr_amount ?? 0;
                                     ?>
                                    <tr>
                                      <th style="border:1px solid silver;" class="text-right" ><b> <?php echo $ac_val->_ledger->_name ?? ''; ?>[+]
                                        </b></th>
                                      <th style="border:1px solid silver;" class="text-right"><?php echo _report_amount( $ac_val->_dr_amount ?? 0 ); ?></th>
                                    </tr>
                                    <?php endif; ?>

                                    <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                      <th style="border:1px solid silver;" class="text-right" ><b>Invoice Due </b></th>
                                      <th style="border:1px solid silver;" class="text-right"><?php echo _report_amount( $_due_amount); ?></th>
                                    </tr>

                                    <?php endif; ?>
                                    <?php if($form_settings->_show_p_balance==1): ?>
                                    <tr>
                                      <th style="border:1px solid silver;" class="text-right" ><b>Previous Balance</b></th>
                                      <th style="border:1px solid silver;" class="text-right"><?php echo _show_amount_dr_cr(_report_amount($data->_p_balance ?? 0)); ?></th>
                                    </tr>
                                    <tr>
                                      <th style="border:1px solid silver;" class="text-right" ><b>Current Balance</b></th>
                                      <th style="border:1px solid silver;" class="text-right"><?php echo _show_amount_dr_cr(_report_amount($data->_l_balance ?? 0)); ?></th>
                                    </tr>
                                    <?php endif; ?>
                                  </table>

                              </td>
                            </tr>
         <?php endif; ?>
        </tbody>
        <tfoot>
<?php if($_is_footer ==1): ?>
               <tr>
                 <td colspan="8">
                    <div class="col-12 mt-5">
                  <div class="row">
                    <div class="col-4 text-left " >
                        <div style="height:120px;width:100%;"></div>
                      <span style="border-bottom: 1px solid #f5f9f9;border-top: 1px solid #000;">Customer Signature</span>
                    </div>
                    <div class="col-4"></div>
                    
                    <div class="col-4 text-center " >
                     <div style="height: 120px;width:auto; ">
                        <img id="output_1" class="banner_image_create" src="<?php echo e(asset('/')); ?><?php echo e($form_settings->_seal_image ?? ''); ?>"   />
                     </div> 
                      <span style="border-bottom: 1px solid #f5f9f9;border-top: 1px solid #000;"> Authorised Signature</span>
                    </div>
                  </div>
                </div>
                 </td>
               </tr> 
<?php endif; ?>
        </tfoot>
       </table>
   
</div>
  </div>
  </section>
   

   <?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\own\sabuz-bhai\sspf.sobuzsathitraders.com\sspf.sobuzsathitraders.com\resources\views/backend/sales/print_4.blade.php ENDPATH**/ ?>