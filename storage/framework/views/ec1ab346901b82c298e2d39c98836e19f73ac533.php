
    
<section  class="print_invoice" id="printablediv" style="box-shadow: 0 0 1in -0.25in rgba(0, 0, 0, 0.5);
  padding:2mm;
  margin: 0 auto;
  width: 250px;
  background: #FFF;">
    
            <table style="width:100%;border-collapse: collapse;" >
              <tr>
                <td colspan="6" style="text-align: center;">
                    <div>
                         <?php echo e($settings->_top_title ?? ''); ?><br>
                   <img src="<?php echo e(url('/')); ?>/<?php echo e($settings->logo); ?>" alt="<?php echo e($settings->name ?? ''); ?>" style="width: 200px;"  ><br>
                  <b><span style="font-size: .7em;"><?php echo e($settings->name ?? ''); ?></span></b><br>
                  <div style="font-size:0.7em;">
             <?php echo $settings->_address ?? ''; ?><br>
            <?php echo e($settings->_phone ?? ''); ?><br>
            <?php echo e($settings->_email ?? ''); ?><br>
            </div>
            <b style="font-size:0.7em;">Invoice/Bill</b>
                    </div>
                   
                </td>
              </tr>
                <tr>
               
                <td colspan="6" style="border: 1px dotted grey;">
                  <table style="text-align: left;">
                    <tr> <td style="border:none;" > <?php echo e(invoice_barcode($data->_order_number ?? '')); ?></td></tr>
                    <tr> <td style="border:none;font-size:0.7em;" > Invoice No: <?php echo e($data->_order_number ?? ''); ?></td></tr>
                  <tr> <td style="border:none;font-size:0.7em;" > Date: <?php echo e(_view_date_formate($data->_date ?? '')); ?></td></tr>
                  </table>
                </td>
              </tr>
              <tr>
                <td colspan="6" style="text-align: left;border: 1px dotted grey;">
                  <table style="">
                     <tr> <td style="border:none;font-size:0.7em;" >Customer: <?php if($form_settings->_defaut_customer ==$data->_ledger_id): ?>
                      <?php echo e($data->_referance ?? $data->_ledger->_name); ?>

                  <?php else: ?>
                  <?php echo e($data->_ledger->_name ?? ''); ?>

                  <?php endif; ?></td></tr>
                    
                      <tr> <td style="border:none;font-size:0.7em;" >Phone:<?php echo e($data->_phone ?? ''); ?> </td></tr>
                      <tr> <td style="border:none;font-size:0.7em;" >Address:<?php echo e($data->_address ?? ''); ?> </td></tr>
                  </table>
                </td>
              
              </tr>
               
               
                <tbody>
                  <tr>
           <th  style="width: 5%;border: 1px solid silver;font-size:0.7em;">##</th>
          <th  style="width: 65%;border: 1px solid silver;font-size:0.7em;">Item</th>
          <th  style="width: 10%;border: 1px solid silver;font-size:0.7em;">Qty</th>
          <th  style="width: 10%;border: 1px solid silver;font-size:0.7em;">Rate</th>
          <th  style="width: 10%;border: 1px solid silver;font-size:0.7em;">Amount</th>
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
                                     <td style="font-size: .7em;color: #666;line-height: 1.2em; border: 1px dotted grey;vertical-align:top;" ><?php echo e(($id)); ?>.</td>

                                    

                              <?php if(sizeof($_item) > 0): ?>
                                     
                                          <td style="font-size: .7em;color: #666;line-height: 1.2em; border: 1px dotted grey;vertical-align:top;">
                                            
                                           
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


                                          </td>
                                           
                                            
                                             <td style="font-size: .7em;color: #666;line-height: 1.2em;border: 1px dotted grey;vertical-align:top;">
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

                                   <br>
                                   <?php $__empty_2 = true; $__currentLoopData = $_item; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $_in_item_key=>$in_itemVal_multi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                              <?php if($_in_item_key==0): ?>
                                  <?php echo $in_itemVal_multi->_items->_units->_name ?? ''; ?>

                              <?php endif; ?>
                              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                              <?php endif; ?>
                                            </td>
                                            <td style="font-size: .7em;color: #666;line-height: 1.2em; border: 1px dotted grey;vertical-align:top;text-align:right;">
                             <?php $__empty_2 = true; $__currentLoopData = $_item; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $_in_item_key=>$in_itemVal_multi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                              <?php if($_in_item_key==0): ?>
                                 <?php echo _report_amount($in_itemVal_multi->_sales_rate ?? 0); ?>

                              <?php endif; ?>
                              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                              <?php endif; ?>
                                              
                                            </td>
                                          
                                            
                                            <td style="font-size: .7em;color: #666;line-height: 1.2em; border: 1px dotted grey;vertical-align:top;text-align:right;">
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
                              <td colspan="2"  style="font-size: .7em;color: #666;line-height: 1.2em; border: 1px dotted grey;vertical-align:top;"><b>Total</b></td>
                              <td style="font-size: .7em;color: #666;line-height: 1.2em;border: 1px dotted grey;vertical-align:top;"> <b><?php echo e(_report_amount($_qty_total ?? 0)); ?></b> </td>
                              <td style="font-size: .7em;color: #666;line-height: 1.2em;border: 1px dotted grey;vertical-align:top;"></td>
                              
                              <td style="font-size: .7em;color: #666;line-height: 1.2em;border: 1px dotted grey;vertical-align:top;text-align:right;"><b> <?php echo e(_report_amount($_value_total ?? 0)); ?></b>
                              </td>
                            </tr>
                            <tr>
                             
                              
                              <td colspan="5" class=" text-right"  style="width: 100%;">
                                  <table style="width: 100%;border-collapse: collapse;">
                                     <tr >
                                      <th style="font-size: .7em;color: #666;line-height: 1.2em;border: 1px dotted grey;vertical-align:top;" ><b>Sub Total</b></th>
                                      <th style="font-size: .7em;color: #666;line-height: 1.2em;border: 1px dotted grey;vertical-align:top;text-align:right;"><?php echo _report_amount($data->_sub_total ?? 0); ?></th>
                                    </tr>
                                   
                                    <tr>
                                      <th style="font-size: .7em;color: #666;line-height: 1.2em;border: 1px dotted grey;vertical-align:top;" ><b>Discount[-]</b></th>
                                      <th style="font-size: .7em;color: #666;line-height: 1.2em;border: 1px dotted grey;vertical-align:top;text-align:right;"><?php echo _report_amount($data->_total_discount ?? 0); ?></th>
                                    </tr>
                                   
                                    <?php if($form_settings->_show_vat==1): ?>
                                    <tr>
                                      <th style="font-size: .7em;color: #666;line-height: 1.2em;border: 1px dotted grey;vertical-align:top;" ><b>VAT[+]</b></th>
                                      <th style="font-size: .7em;color: #666;line-height: 1.2em;border: 1px dotted grey;vertical-align:top;text-align:right;"><?php echo _report_amount($data->_total_vat ?? 0); ?></th>
                                    </tr>
                                    <?php endif; ?>
                                    <tr>
                                      <th style="font-size: .7em;color: #666;line-height: 1.2em;border: 1px dotted grey;vertical-align:top;" ><b>Net Total</b></th>
                                      <th style="font-size: .7em;color: #666;line-height: 1.2em;border: 1px dotted grey;vertical-align:top;text-align:right;"><?php echo _report_amount($data->_total ?? 0); ?></th>
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
                                      <th style="font-size: .7em;color: #666;line-height: 1.2em;border: 1px dotted grey;vertical-align:top;" ><b> <?php echo $ac_val->_ledger->_name ?? ''; ?>[+]
                                        </b></th>
                                      <th style="font-size: .7em;color: #666;line-height: 1.2em;border: 1px dotted grey;vertical-align:top;"><?php echo _report_amount( $ac_val->_cr_amount ?? 0 ); ?></th>
                                    </tr>
                                    <?php endif; ?>
                                    <?php if($ac_val->_dr_amount > 0): ?>
                                     <?php
                                      $_due_amount -=$ac_val->_dr_amount ?? 0;
                                     ?>
                                    <tr>
                                      <th style="font-size: .7em;color: #666;line-height: 1.2em;border: 1px dotted grey;vertical-align:top;" ><b> <?php echo $ac_val->_ledger->_name ?? ''; ?>[+]
                                        </b></th>
                                      <th style="font-size: .7em;color: #666;line-height: 1.2em;border: 1px dotted grey;vertical-align:top;text-align:right;"><?php echo _report_amount( $ac_val->_dr_amount ?? 0 ); ?></th>
                                    </tr>
                                    <?php endif; ?>

                                    <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                      <th style="font-size: .7em;color: #666;line-height: 1.2em;border: 1px dotted grey;vertical-align:top;" ><b>Invoice Due </b></th>
                                      <th style="font-size: .7em;color: #666;line-height: 1.2em;border: 1px dotted grey;vertical-align:top;text-align:right;"><?php echo _report_amount( $_due_amount); ?></th>
                                    </tr>

                                    <?php endif; ?>
                                  
                                  </table>

                              </td>
                            </tr>
                            <tr>
                                 <td colspan="5" class="text-left " style="width: 100%;border:1px solid silver;">
                              <table style="width: 100%;border-collapse: collapse;">
                               
                                <tr>
                                  <td style="font-size: .7em;"><p class="lead"> In Words:  <?php echo e(nv_number_to_text($data->_total ?? 0)); ?> </p></td>
                                </tr>
                                 <tr>
                                  <td style="font-size: .7em;">

                                    <?php echo e($settings->_sales_note ?? ''); ?>

                                  </td>
                                </tr>
                                <tr>
                                  <td>
                                    <?php echo $__env->make("backend.sales.invoice_history", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                  </td>
                                </tr>
                              </table>
                              </td>
                            </tr>
         <?php endif; ?>

                   
                
        </tbody>
            </table>
            
        </section>
  

<script type="text/javascript">
  window.print();
</script><?php /**PATH D:\xampp\htdocs\own\inv-acc-hrm\resources\views/backend/sales/pos_template.blade.php ENDPATH**/ ?>