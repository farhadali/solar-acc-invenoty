<div class="col-md-12  ">
                             <div class="card">
                              <div class="card-header">
                                <strong>Account Details</strong>
                              </div>
                              <div class="card-body">
                                <div class="table-responsive">
                                      <table class="table table-bordered" >
                                          <thead>
                                            <th>&nbsp;</th>
                                            <th>Ledger</th>
                                               <th class=" <?php if(sizeof($permited_branch)==1): ?> display_none <?php endif; ?> ">Branch</th>
                                              <th class=" <?php if(sizeof($permited_costcenters)==1): ?> display_none <?php endif; ?> ">Cost Center</th>
                                             
                                            <th>Short Narr.</th>
                                            <th>Dr. Amount</th>
                                            <th>Cr. Amount</th>
                                          </thead>
                                          <tbody class="area__voucher_details form_body" id="area__voucher_details">
                                            <?php
                                            $_total_dr_amount =0;
                                            $_total_cr_amount =0;
                                            ?>
                                            <?php $__empty_1 = true; $__currentLoopData = $data->s_account; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $_s_acc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                            <?php
                                            $_total_dr_amount +=floatval($_s_acc->_dr_amount ?? 0);
                                            $_total_cr_amount +=floatval($_s_acc->_cr_amount ?? 0);
                                            ?>
                                            <tr class="_voucher_row">
                                              <td>
                                                <a  href="#none" class="btn btn-sm btn-default _voucher_row_remove" ><i class="fa fa-trash"></i></a>
                                              </td>
                                              <td>
                                                <input type="text" name="_search_ledger_id[]" class="form-control _search_ledger_id width_280_px" placeholder="Ledger" value="<?php echo e($_s_acc->_ledger->_name ?? ''); ?>">
                                                <input type="hidden" name="_ledger_id[]" class="form-control _ledger_id" value="<?php echo e($_s_acc->_ledger_id); ?>" >
                                                <input type="hidden" name="_sales_account_detail_id[]" class="form-control _sales_account_detail_id" value="<?php echo e($_s_acc->id); ?>" >
                                                <div class="search_box"  >
                                                  
                                                </div>
                                              </td>
                                              
                                              <td class="<?php if(sizeof($permited_branch)==1): ?> display_none <?php endif; ?>">
                                                <select class="form-control width_150_px _branch_id_detail" name="_branch_id_detail[]"  required>
                                                  <?php $__empty_2 = true; $__currentLoopData = $permited_branch; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $branch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                                                  <option value="<?php echo e($branch->id); ?>" <?php if(isset($_s_acc->_branch_id)): ?> <?php if($_s_acc->_branch_id == $branch->id): ?> selected <?php endif; ?>   <?php endif; ?>><?php echo e($branch->_name ?? ''); ?></option>
                                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                                                  <?php endif; ?>
                                                </select>
                                              </td>
                                              <td class="<?php if(sizeof($permited_costcenters)==1): ?> display_none <?php endif; ?>">
                                                 <select class="form-control width_150_px _cost_center" name="_cost_center[]" required >
                                                  <?php $__empty_2 = true; $__currentLoopData = $permited_costcenters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $costcenter): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                                                  <option value="<?php echo e($costcenter->id); ?>" <?php if(isset($_s_acc->_cost_center)): ?> <?php if($_s_acc->_cost_center == $costcenter->id): ?> selected <?php endif; ?>   <?php endif; ?>> <?php echo e($costcenter->_name ?? ''); ?></option>
                                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                                                  <?php endif; ?>
                                                </select>
                                              </td>
                                              <td>
                                                <input type="text" name="_short_narr[]" class="form-control width_250_px _short_narr" placeholder="Short Narr" value="<?php echo e($_s_acc->_short_narr ?? ''); ?>">
                                              </td>
                                              <td>
                                                <input type="number" name="_dr_amount[]" class="form-control  _dr_amount" placeholder="Dr. Amount" value="<?php echo e(old('_dr_amount',$_s_acc->_dr_amount)); ?>">
                                              </td>
                                              <td>
                                                <input type="number" name="_cr_amount[]" class="form-control  _cr_amount" placeholder="Cr. Amount" value="<?php echo e(old('_cr_amount',$_s_acc->_cr_amount)); ?>">
                                              </td>
                                            </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                            <?php endif; ?>
                                          </tbody>
                                          <tfoot>
                                            <tr>
                                              <td>
                                                <a href="#none"  class="btn btn-default btn-sm" onclick="voucher_row_add(event)"><i class="fa fa-plus"></i></a>
                                              </td>
                                              <td></td>
                                              <td class="<?php if(sizeof($permited_branch)==1): ?> display_none <?php endif; ?>"></td>
                                              <td class="<?php if(sizeof($permited_costcenters)==1): ?> display_none <?php endif; ?>"></td>
                                              <td  class="text-right"><b>Total</b></td>
                                              <td>
                                                <input type="number" step="any" min="0" name="_total_dr_amount" class="form-control _total_dr_amount" value="<?php echo e($_total_dr_amount ?? 0); ?>" readonly required>
                                              </td>


                                              <td>
                                                <input type="number" step="any" min="0" name="_total_cr_amount" class="form-control _total_cr_amount" value="<?php echo e($_total_cr_amount ?? 0); ?>"  readonly required>
                                              </td>
                                            </tr>
                                            
                                          </tfoot>
                                      </table>
                                </div>
                            </div>
                          </div>
                        </div><?php /**PATH D:\xampp\htdocs\own\inv-acc-hrm\resources\views/backend/sales-return/edit_ac_detail.blade.php ENDPATH**/ ?>