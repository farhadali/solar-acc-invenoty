  <?php
  $__purchase_account = $data->purchase_account ?? [];
  $___master_details = $data->_master_details ?? [];
  ?>

 <?php if(sizeof($___master_details) > 0): ?>
                        
                          
                            <div class="card " >
                              <table class="table">
                                <thead >
                                            <th class="text-left" >ID</th>
                                            <th class="text-left" >Item</th>
                                           
                                            <th class="text-left <?php if($form_settings->_show_barcode==0): ?> display_none <?php endif; ?>" >Barcode</th>
                                            <th class="text-left" >Unit</th>
                                            
                                            <th class="text-right" >Qty</th>
                                            <th class="text-right" >Rate</th>
                                            <th class="text-right" >Sales Rate</th>
                                            <?php if(isset($form_settings->_show_vat)): ?> <?php if($form_settings->_show_vat==1): ?>
                                            <th class="text-right" >VAT%</th>
                                            <th class="text-right" >VAT</th>
                                             <?php else: ?>
                                            <th class="text-left display_none" >VAT%</th>
                                            <th class="text-left display_none" >VAT</th>
                                            <?php endif; ?>
                                            <?php endif; ?>

                                            <th class="text-right" >Value</th>
                                             <?php if(sizeof($permited_branch) > 1): ?>
                                            <th class="text-left" >Branch</th>
                                            <?php else: ?>
                                            <th class="text-left display_none" >Branch</th>
                                            <?php endif; ?>
                                             <?php if(sizeof($permited_costcenters) > 1): ?>
                                            <th class="text-left" >Cost Center</th>
                                            <?php else: ?>
                                             <th class="text-left display_none" >Cost Center</th>
                                            <?php endif; ?>
                                             <?php if(sizeof($store_houses) > 1): ?>
                                            <th class="text-left" >Store</th>
                                            <?php else: ?>
                                             <th class="text-left display_none" >Store</th>
                                            <?php endif; ?>
                                            <?php if(isset($form_settings->_show_self)): ?> <?php if($form_settings->_show_self==1): ?>
                                            <th class="text-left" >Shelf</th>
                                            <?php else: ?>
                                             <th class="text-left display_none" >Shelf</th>
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
                                           
                                            <td class="<?php if($form_settings->_show_barcode==0): ?> display_none <?php endif; ?>">
                                               <?php
                                          $barcode_arrays = explode(',', $_item->_barcode ?? '');
                                          ?>
                                          <?php $__empty_2 = true; $__currentLoopData = $barcode_arrays; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $barcode): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                                        <span><?php echo e($barcode); ?></span><br>
                                          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                                          <?php endif; ?>
                                              </td>
                                           
                                            <td class="text-left" ><?php echo $_item->_trans_unit->_name ?? ''; ?></td>
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
                                              
                                              <td  class="text-right <?php if($form_settings->_show_barcode==0): ?> display_none <?php endif; ?>"></td>
                                              <td></td>
                                              
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
                            </div>
                          
                        <?php endif; ?>
                        <?php if(sizeof($__purchase_account) > 0): ?>
                        
                            <div class="card " >
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
                                  <?php $__empty_1 = true; $__currentLoopData = $data->purchase_account; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail_key=>$_master_val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
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
                            </div>
                          
                        <?php endif; ?><?php /**PATH /home/sobuz23/sspf.sobuzsathitraders.com/resources/views/backend/purchase/detail.blade.php ENDPATH**/ ?>