<!-- Modal -->

<?php

$users =\Auth::user();
$permited_branch = permited_branch(explode(',',$users->branch_ids));
        $permited_costcenters = permited_costcenters(explode(',',$users->cost_center_ids));
        $store_houses = \App\Models\StoreHouse::whereIn('_branch_id',explode(',',$users->cost_center_ids))->get();
?>

<div class="modal fade" id="exampleModalLong_item" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Create New Item (Inventory)</h5>
        <button type="button" class="close inventoryEntryModal" >
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form class="_item_modal_form">
          <div class="row">
                       <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <label>Category: <span class="_required">*</span></label>
                               <select  class="form-control _category_id " name="_category_id" required>
                                  <option value="">--Select Category--</option>
                                  <?php
                                  $categories = $categories ?? [];
                                  ?>
                                  <?php $__empty_1 = true; $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                  <option value="<?php echo e($category->id); ?>" ><?php echo e($category->_parents->_name ?? ''); ?>/<?php echo e($category->_name ?? ''); ?></option>
                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                  <?php endif; ?>
                                </select>
                            </div>
                        </div>
                      
                       
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <label for="_item">Item:<span class="_required">*</span></label>
                                <input type="text" id="_item" name="_item" class="form-control _item_item" value="<?php echo e(old('_item')); ?>" placeholder="Item" required>
                            </div>
                        </div>
                       
                        <div class="col-xs-12 col-sm-12 col-md-6">
                            <div class="form-group">
                                <label for="_code">Code:</label>
                                <input type="text" id="_code" name="_code" class="form-control _item_code" value="<?php echo e(old('_code')); ?>" placeholder="Code" >
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-6">
                            <div class="form-group">
                                <label for="_unit">Unit:<span class="_required">*</span></label>
                                <?php
                                  $units = $units ?? [];
                                  ?>
                                <select class="form-control _unit_id _item_unit_id" id="_unit_id" name="_unit_id" required>
                                  <option value="" >--Units--</option>
                                  <?php $__currentLoopData = $units; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $unit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                   <option value="<?php echo e($unit->id); ?>" ><?php echo e($unit->_name ?? ''); ?></option>
                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-6">
                            <div class="form-group">
                                <label for="_barcode">Model:</label>
                                <input type="text" id="_barcode" name="_barcode" class="form-control _item_barcode" value="<?php echo e(old('_barcode')); ?>" placeholder="Model" >
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-6">
                            <div class="form-group">
                                <label for="_discount">Discount Rate:</label>
                                <input type="text" id="_discount" name="_discount" class="form-control _item_discount" value="<?php echo e(old('_discount')); ?>" placeholder="Discount Rate" >
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-6">
                            <div class="form-group">
                                <label for="_vat">Vat Rate:</label>
                                <input type="text" id="_vat" name="_vat" class="form-control _item_vat" value="<?php echo e(old('_vat')); ?>" placeholder="Vat Rate" >
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-6">
                            <div class="form-group">
                                <label for="_item_opening_qty">Opening QTY:</label>
                                <input type="text" id="_item_opening_qty" name="_item_opening_qty" class="form-control" value="<?php echo e(old('_opening_qty')); ?>" placeholder="Opening QTY" >
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-6">
                            <div class="form-group">
                                <label for="_pur_rate">Purchase Rate:</label>
                                <input type="text" id="_pur_rate" name="_pur_rate" class="form-control _item_pur_rate" value="<?php echo e(old('_pur_rate')); ?>" placeholder="Purchase Rate" >
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-6">
                            <div class="form-group">
                                <label for="_sale_rate">Sales Rate:</label>
                                <input type="text" id="_sale_rate" name="_sale_rate" class="form-control _item_sale_rate" value="<?php echo e(old('_sale_rate')); ?>" placeholder="Sales Rate" >
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-3 <?php if(sizeof($permited_branch)==1): ?> display_none <?php endif; ?> ">
                            <div class="form-group ">
                                <label>Branch:<span class="_required">*</span></label>
                               <select class="form-control _item_branch_id" name="_branch_id" required >
                                  
                                  <?php $__empty_1 = true; $__currentLoopData = $permited_branch; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $branch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                  <option value="<?php echo e($branch->id); ?>" <?php if(isset($request->_branch_id)): ?> <?php if($request->_branch_id == $branch->id): ?> selected <?php endif; ?>   <?php endif; ?>><?php echo e($branch->id ?? ''); ?> - <?php echo e($branch->_name ?? ''); ?></option>
                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                  <?php endif; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-3 <?php if(sizeof($permited_costcenters)==1): ?> display_none <?php endif; ?> ">
                            <div class="form-group ">
                                <label>Cost Center:<span class="_required">*</span></label>
                               <select class="form-control _item_cost_center_id" name="_cost_center_id" required >
                                  
                                  <?php $__empty_1 = true; $__currentLoopData = $permited_costcenters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cost_center): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                  <option value="<?php echo e($cost_center->id); ?>" <?php if(isset($request->_cost_center_id)): ?> <?php if($request->_cost_center_id == $cost_center->id): ?> selected <?php endif; ?>   <?php endif; ?>><?php echo e($cost_center->id ?? ''); ?> - <?php echo e($cost_center->_name ?? ''); ?></option>
                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                  <?php endif; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-3 <?php if(sizeof($store_houses)==1): ?> display_none <?php endif; ?>">
                            <div class="form-group ">
                                <label>Store House:<span class="_required">*</span></label>
                                <select class="form-control  _item_store_id" name="_store_id">
                                      <?php $__empty_1 = true; $__currentLoopData = $store_houses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $store): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                      <option value="<?php echo e($store->id); ?>"><?php echo e($store->_name ?? ''); ?></option>
                                      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                      <?php endif; ?>
                                    </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-6">
                            <div class="form-group">
                                <label for="_manufacture_company">Manufacture Company:</label>
                                <input type="text" id="_manufacture_company" name="_manufacture_company" class="form-control _item_manufacture_company" value="<?php echo e(old('_manufacture_company')); ?>" placeholder="Manufacture Company" >
                            </div>
                        </div>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('restaurant-module')): ?> 
                         <div class="col-xs-12 col-sm-12 col-md-6">
                            <div class="form-group">
                                <label for="_kitchen_item" class="_required" title="if Yes then this item will send to kitchen to cook/production for sales and store deduct as per item ingredient wise automaticaly">Kitchen/Production Item ?:</label>
                                <select class="form-control" name="_kitchen_item" id="_kitchen_item">
                                  <option value="0">No</option>
                                  <option value="1">Yes</option>
                                </select>
                            </div>
                        </div>
                        <?php endif; ?>
                         <div class="col-xs-12 col-sm-12 col-md-6">
                            <div class="form-group">
                                <label for="_unique_barcode">Use Unique Barcode ?:</label>
                                <select class="form-control _item_unique_barcode" name="_unique_barcode" id="_item_unique_barcode">
                                  <option value="0">NO</option>
                                  <option value="1">Yes</option>
                                </select>
                            </div>
                        </div>
                         <div class="col-xs-12 col-sm-12 col-md-6">
                            <div class="form-group">
                                <label for="_status">Status:</label>
                                <select class="form-control _item_status" name="_status" id="_status">
                                  <option value="1">Active</option>
                                  <option value="0">In Active</option>
                                </select>
                            </div>
                        </div>
                        
                    </div>
          </form> 
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary modal_close inventoryEntryModal" >Close</button>
        <button type="button" class="btn btn-primary save_item">Save </button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Create Ledger</h5>
        <button type="button" class="close ledgerEntryModal" >
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form class="_ledger_modal_form">
        <div class="row">
                                  <?php
                                  $account_types = $account_types ?? [];
                                  ?>
                       <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Account Type: <span class="_required">*</span></strong>
                               <select type_base_group="<?php echo e(url('type_base_group')); ?>" class="form-control _account_head_id " name="_account_head_id" required>
                                  <option value="">--Select Account Type--</option>
                                  <?php $__empty_1 = true; $__currentLoopData = $account_types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $account_type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                  <option value="<?php echo e($account_type->id); ?>" <?php if(isset($request->_account_head_id)): ?> <?php if($request->_account_head_id == $account_type->id): ?> selected <?php endif; ?>   <?php endif; ?>><?php echo e($account_type->id ?? ''); ?>-<?php echo e($account_type->_name ?? ''); ?></option>
                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                  <?php endif; ?>
                                </select>
                            </div>
                        </div>
                       <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group ">
                                  <?php
                                  $account_groups = $account_groups ?? [];
                                  ?>
                                <strong>Account Group:<span class="_required">*</span></strong>
                               <select class="form-control _account_groups " name="_account_group_id" required>
                                  <option value="">--Select Account Group--</option>
                                  <?php $__empty_1 = true; $__currentLoopData = $account_groups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $account_group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                  <option value="<?php echo e($account_group->id); ?>" <?php if(isset($request->_account_group_id)): ?> <?php if($request->_account_group_id == $account_group->id): ?> selected <?php endif; ?>   <?php endif; ?>><?php echo e($account_group->id ?? ''); ?> - <?php echo e($account_group->_name ?? ''); ?></option>
                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                  <?php endif; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group ">
                              <?php
                                  $permited_branch = $permited_branch ?? [];
                                  ?>
                                <strong>Branch:<span class="_required">*</span></strong>
                               <select class="form-control _ledger_branch_id" name="_ledger_branch_id" required >
                                  
                                  <?php $__empty_1 = true; $__currentLoopData = $permited_branch; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $branch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                  <option value="<?php echo e($branch->id); ?>" <?php if(isset($request->_branch_id)): ?> <?php if($request->_branch_id == $branch->id): ?> selected <?php endif; ?>   <?php endif; ?>><?php echo e($branch->id ?? ''); ?> - <?php echo e($branch->_name ?? ''); ?></option>
                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                  <?php endif; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <strong>Ledger Name:<span class="_required">*</span></strong>
                                
                                <input type="text" name="_name" class="form-control _ledger_name" value="<?php echo e(old('_name')); ?>" placeholder="Ledger Name" required>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-6">
                            <div class="form-group">
                                <strong>Address:</strong>
                                <input type="text" name="_address" class="form-control _ledger_address" value="<?php echo e(old('_address')); ?>" placeholder="Address" >
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-6">
                            <div class="form-group">
                                <strong>Code:</strong>
                                <input type="text" name="_code" class="form-control _ledger_code" value="<?php echo e(old('_code')); ?>" placeholder="CODE Number">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-6">
                            <div class="form-group">
                                <strong>Display Possition:</strong>
                                <?php echo Form::text('_short', null, array('placeholder' => 'Possition','class' => 'form-control _ledger_short')); ?>

                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-6">
                            <div class="form-group">
                                <strong>NID Number:</strong>
                               <input type="text" name="_nid" class="form-control _ledger_nid" value="<?php echo e(old('_nid')); ?>" placeholder="NID Number">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-6">
                            <div class="form-group">
                                <strong>Email:</strong>
                                <input type="email" name="_email" class="form-control _ledger_email" value="<?php echo e(old('_email')); ?>" placeholder="Email" >
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-6">
                            <div class="form-group">
                                <strong>Phone:</strong>
                                <input type="text" name="_phone" class="form-control _ledger_phone" value="<?php echo e(old('_phone')); ?>" placeholder="Phone" >
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-6">
                            <div class="form-group">
                                <strong>Credit Limit:</strong>
                                <input type="number" step="any" name="_credit_limit" class="form-control _ledger_credit_limit" value="<?php echo e(old('_credit_limit',0)); ?>" placeholder="Credit Limit" >
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-6">
                            <div class="form-group">
                                <label>Opening Dr Amount:</label>
                                <input id="opening_dr_amount" type="number" name="opening_dr_amount" class="form-control opening_dr_amount" placeholder="Dr Amount" value="0" step="any" min="0">
                            </div>
                        </div>
                         <div class="col-xs-12 col-sm-12 col-md-6">
                            <div class="form-group">
                                <label>Opening Cr Amount:</label>
                                <input id="opening_cr_amount" type="number" name="opening_cr_amount" class="form-control opening_cr_amount" placeholder="Cr Amount" value="0" step="any" min="0">
                            </div>
                        </div>
                       
                        <div class="col-xs-12 col-sm-12 col-md-6 display_none">
                            <div class="form-group">
                                <strong>Is User:</strong>
                                <select class="form-control _ledger_is_user" name="_is_user">
                                  <?php $__currentLoopData = yes_nos(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$s_val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                  <option value="<?php echo e($key); ?>"><?php echo e($s_val); ?></option>
                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-6 display_none">
                            <div class="form-group">
                                <strong>Sales Form:</strong>
                                <select class="form-control _ledger_is_sales_form" name="_is_sales_form">
                                  <?php $__currentLoopData = yes_nos(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$s_val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                  <option value="<?php echo e($key); ?>"><?php echo e($s_val); ?></option>
                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-6 display_none">
                            <div class="form-group">
                                <strong>Is Purchase Form:</strong>
                                <select class="form-control _ledger_is_purchase_form" name="_is_purchase_form">
                                  <?php $__currentLoopData = yes_nos(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$s_val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                  <option value="<?php echo e($key); ?>"><?php echo e($s_val); ?></option>
                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-6 display_none">
                            <div class="form-group">
                                <strong>Search For All Branch:</strong>
                                <select class="form-control _ledger_is_all_branch" name="_is_all_branch">
                                  <?php $__currentLoopData = yes_nos(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$s_val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                  <option value="<?php echo e($key); ?>"><?php echo e($s_val); ?></option>
                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-6">
                            <div class="form-group">
                                <strong>Status:</strong>
                                <select class="form-control _ledger_status" name="_status">
                                  <?php $__currentLoopData = common_status(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$s_val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                  <option value="<?php echo e($key); ?>"><?php echo e($s_val); ?></option>
                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                       
                      
                      
                    </div>
              </form> 
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary modal_close ledgerEntryModal" >Close</button>
        <button type="button" class="btn btn-primary save_ledger">Save </button>
      </div>
    </div>
  </div>
</div><?php /**PATH /home/sobuz23/sspf.sobuzsathitraders.com/resources/views/backend/common-modal/item_ledger_modal.blade.php ENDPATH**/ ?>