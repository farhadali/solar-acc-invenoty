

<style type="text/css">
  .border_top_2{
    border-top: 1px solid silver;
  }
</style>

<section class="invoice" id="printablediv" style="">
    
            <table class="table" style="border-collapse: collapse;width:100%;margin:0px auto;">
              <tr>
                <td colspan="6" style="text-align: center;">
                    <?php echo e($settings->_top_title ?? ''); ?><br>
                   <img src="<?php echo e(url('/')); ?>/<?php echo e($settings->logo); ?>" alt="<?php echo e($settings->name ?? ''); ?>" style="height: 60px;width: 150px"  ><br>
                  <strong><?php echo e($settings->name ?? ''); ?></strong><br>
             <?php echo e($settings->_address ?? ''); ?><br>
            <?php echo e($settings->_phone ?? ''); ?><br>
            <?php echo e($settings->_email ?? ''); ?><br>
            <?php
        $bin = $settings->_bin ?? '';
      ?>
      <?php if($bin !=''): ?>
      VAT REGISTRATION NO: <?php echo e($settings->_bin ?? ''); ?><br>
      <?php endif; ?>
            <b>CHALLAN/BILL</b>
                </td>
              </tr>
                <tr>
               
                <td colspan="6" style="border: 1px dotted grey;">
                  <table style="text-align: left;">
                    <tr> <td style="border:none;" > <?php echo e(invoice_barcode($data->_order_number ?? '')); ?></td></tr>
                    <tr> <td style="border:none;" > Invoice No: <?php echo e($data->_order_number ?? ''); ?></td></tr>
                  <tr> <td style="border:none;" > Date: <?php echo e(_view_date_formate($data->_date ?? '')); ?></td></tr>
                  </table>
                </td>
              </tr>
              <tr>
                <td colspan="6" style="text-align: left;border: 1px dotted grey;">
                  <table style="">
                     <tr> <td style="border:none;" > <b><?php echo e(__('label._cost_center_id')); ?>:</b> 
                  <?php echo e($data->_master_cost_center->_name ?? ''); ?>

                  </td>
                </tr>
                    
                      <tr> <td style="border:none;" ><b>Phone:</b> <?php echo e($data->_phone ?? ''); ?> </td></tr>
                      <tr> <td style="border:none;" ><b>Address:</b> <?php echo e($data->_address ?? ''); ?> </td></tr>
                      <tr> <td style="border:none;" ><b><?php echo e(__('label._delivery_details')); ?>:</b> <?php echo $data->_delivery_details ?? ''; ?> </td></tr>
                  </table>
                </td>
              
              </tr>
               
               
                <tbody>
                  <tr>
           <th class="text-center" style="width: 5%;border: 1px solid silver;">SL</th>
          <th class="text-left" style="width: 53%;border: 1px solid silver;">Item</th>
          <th class="text-center" style="width: 7%;border: 1px solid silver;">Unit</th>
          <th class="text-center" style="width: 7%;border: 1px solid silver;">Qty</th>
          
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
                                           
                                            
                                             <td class="text-right  " style="vertical-align: text-top;border: 1px solid silver;vertical-align:top;text-align: center;">
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
                              <?php endif; ?>
                                         
                                  </tr>
                                  <?php
                                  $id++;
                                  ?>


                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                  <?php endif; ?>
                            <tr>
                              <td colspan="3" class="text-right " style="border: 1px solid silver;"><b>Total</b></td>
                              <td class="text-right " style="border: 1px solid silver;text-align: center;"> <b><?php echo e(_report_amount($_qty_total ?? 0)); ?></b> </td>
                              
                            </tr>
                           
         <?php endif; ?>

                 <tr>
                   <td colspan="4" style="height:60px;"></td>
                 </tr> 

                 <tr>
                   <td colspan="4">
                     <table style="width:100%;">
                       <tr>
                   <td style="width:25%;text-align: center;"><span class="border_top_2" >Received By</span></td>
                   <td style="width:25%;text-align: center;"><span class="border_top_2" >Prepared By</span></td>
                   <td style="width:25%;text-align: center;"><span class="border_top_2" >Checked By</span></td>
                   <td style="width:25%;text-align: center;"><span class="border_top_2" >Approved By</span></td>
                 </tr>
                     </table>
                   </td>
                 </tr> 

 
                
        </tbody>
            </table>
            
   
        </section>
  

<script type="text/javascript">
  window.print();
</script><?php /**PATH D:\xampp\htdocs\own\inv-acc-hrm\resources\views/backend/material-issue-return/challan.blade.php ENDPATH**/ ?>