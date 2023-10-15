



<section class="invoice" id="printablediv" style="">
		
            <table class="table" style="border-collapse: collapse;width:388px;margin:0px auto;">
            	<tr>
            		<td colspan="6" style="text-align: center;">
            			  <?php echo e($settings->_top_title ?? ''); ?><br>
                   <img src="<?php echo e(url('/')); ?>/<?php echo e($settings->logo); ?>" alt="<?php echo e($settings->name ?? ''); ?>" style="height: 60px;width: 60px"  ><br>
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
		        <b>Invoice/Bill</b>
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
            				 <tr> <td style="border:none;" > <b>Customer:</b> <?php if($form_settings->_defaut_customer ==$data->_ledger_id): ?>
                      <?php echo e($data->_referance ?? $data->_ledger->_name); ?>

                  <?php else: ?>
                  <?php echo e($data->_ledger->_name ?? ''); ?>

                  <?php endif; ?></td></tr>
            				
			                <tr> <td style="border:none;" >Phone:<?php echo e($data->_phone ?? ''); ?> </td></tr>
			                <tr> <td style="border:none;" >Address:<?php echo e($data->_address ?? ''); ?> </td></tr>
            			</table>
            		</td>
            	
            	</tr>
               
               
                <tbody>
                  <tr>
           <th class="text-center" style="width: 5%;border: 1px solid silver;">SL</th>
          <th class="text-left" style="width: 53%;border: 1px solid silver;">Item</th>
          <th class="text-center" style="width: 7%;border: 1px solid silver;">Unit</th>
          <th class="text-center" style="width: 7%;border: 1px solid silver;">Qty</th>
          <th class="text-center" style="width: 8%;border: 1px solid silver;">Rate</th>
          <th class="text-center" style="width: 5%;border: 1px solid silver;display: none;">Discount</th>
          <th class="text-center " style="width: 5%;border: 1px solid silver;display: none;">VAT</th>
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
                                            <td class="text-right  " style="vertical-align: text-top;border: 1px solid silver;vertical-align:top;display: none;">
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
                                            <td class="text-right display_none " style="vertical-align: text-top;border: 1px solid silver;display: none;">
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
                              <td class="text-right " style="border: 1px solid silver;display: none;"> <b><?php echo e(_report_amount($_total_discount_amount ?? 0)); ?></b> </td>
                              <td class="text-right display_none" style="border: 1px solid silver;display: none;"> <b><?php echo e(_report_amount($_vat_total ?? 0)); ?></b> </td>
                              <td class=" text-right" style="border: 1px solid silver;"><b> <?php echo e(_report_amount($_value_total ?? 0)); ?></b>
                              </td>
                            </tr>
                            <tr>
                              <td colspan="3" class="text-left " style="width: 50%;border:1px solid silver;">
                              <table style="width: 100%;border-collapse: collapse;">
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
                                  <table style="width: 100%;border-collapse: collapse;">
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
            </table>
            
        </section>
	

<script type="text/javascript">
  window.print();
</script><?php /**PATH D:\xampp\htdocs\own\inv-acc-hrm\resources\views/backend/sales/pos_template2.blade.php ENDPATH**/ ?>