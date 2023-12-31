
<?php $__env->startSection('title',$page_name); ?>

<?php $__env->startSection('css'); ?>
<link rel="stylesheet" href="<?php echo e(asset('backend/new_style.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<?php
$__user= Auth::user();
?>


<div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class=" col-sm-6 ">
            <a class="m-0 _page_name" href="<?php echo e(route('purchase-return.index')); ?>"><?php echo $page_name ?? ''; ?> </a>
          </div><!-- /.col -->
          <div class=" col-sm-6 ">
            <ol class="breadcrumb float-sm-right">
               <li class="breadcrumb-item ">
                 <a target="__blank" href="<?php echo e(url('purchase-return/print')); ?>/<?php echo e($data->id); ?>" class="btn btn-sm btn-warning"> <i class="nav-icon fas fa-print"></i> </a>
                  
                
               </li>
               <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('item-information-create')): ?>
             <li class="breadcrumb-item ">
                 <button type="button" class="btn btn-sm btn-default" data-toggle="modal" data-target="#exampleModalLong_item" title="Create New Item (Inventory) ">
                   <i class="nav-icon fas fa-ship"></i> 
                </button>
               </li>
               <?php endif; ?>
               <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('account-ledger-create')): ?>
             <li class="breadcrumb-item ">
                 <button type="button" class="btn btn-sm btn-default" data-toggle="modal" data-target="#exampleModalLong" title="Create Ledger">
                   <i class="nav-icon fas fa-users"></i> 
                </button>
               </li>
               <?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('purchase-form-settings')): ?>
             <li class="breadcrumb-item ">
                 <button type="button" id="form_settings" class="btn btn-sm btn-default" data-toggle="modal" data-target="#exampleModal">
                   <i class="nav-icon fas fa-cog"></i> 
                </button>
               </li>
              <?php endif; ?>
              <li class="breadcrumb-item ">
                 <a class="btn btn-sm btn-success" title="List" href="<?php echo e(route('purchase-return.index')); ?>"> <i class="nav-icon fas fa-list"></i> </a>
               </li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
  <div class="card-header">
                 
                    <?php echo $__env->make('backend.message.message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    
              </div>
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-12">
            <div class="card">
              
              <div class="card-body">
               <form action="<?php echo e(url('purchase-return/update')); ?>" method="POST" class="purchase_form" >
                <?php echo csrf_field(); ?>
                    <div class="row">

                       <div class="col-xs-12 col-sm-12 col-md-4">
                        <input type="hidden" name="_form_name" class="_form_name"  value="purchases_return">
                            <div class="form-group">
                                <label>Date:</label>
                                  <div class="input-group date" id="reservationdate" data-target-input="nearest">
                                      <input type="text" name="_date" class="form-control datetimepicker-input" data-target="#reservationdate" value="<?php echo e(_view_date_formate($data->_date)); ?>"  />
                                      <div class="input-group-append" data-target="#reservationdate" data-toggle="datetimepicker">
                                          <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                      </div>
                                  </div>
                              </div>
                              <input type="hidden" id="_search_form_value" name="_search_form_value" class="_search_form_value" value="1" >
                              <input type="hidden"  name="_purchase_return_id"  value="<?php echo e($data->id); ?>" >
                        </div>

                        <?php echo $__env->make('basic.org_edit', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                        <div class="col-xs-12 col-sm-12 col-md-4 ">
                            <div class="form-group">
                              <label class="mr-2" for="_order_number">Order Number:</label>
                              <input type="text" id="_order_number" name="_order_number" class="form-control _order_number" value="<?php echo e(old('_order_number',$data->_order_number)); ?>" placeholder="Order Number" readonly>
                                
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-3 ">
                            <div class="form-group">
                              <label class="mr-2" for="_order_ref_id">Purchase Order:<span class="_required">*</span></label>
                              <input type="text" id="_search_order_ref_id" name="_search_order_ref_id" class="form-control _search_order_ref_id" value="<?php echo e(old('_order_ref_id',$data->_order_ref_id)); ?>" placeholder="Purchase Order" readonly>
                              <input type="hidden" id="_order_ref_id" name="_order_ref_id" class="form-control _order_ref_id" value="<?php echo e(old('_order_ref_id',$data->_order_ref_id)); ?>" placeholder="Purchase Order" >
                              <div class="search_box_purchase_order"></div>
                                
                            </div>
                        </div>
                         <div class="col-xs-12 col-sm-12 col-md-3 ">
                            <div class="form-group">
                              <label class="mr-2" for="_main_ledger_id">Supplier:<span class="_required">*</span></label>
                            <input type="text" id="_search_main_ledger_id" name="_search_main_ledger_id" class="form-control _search_main_ledger_id" value="<?php echo e(old('_search_main_ledger_id',$data->_ledger->_name ?? '' )); ?>" placeholder="Supplier" required readonly>

                            <input type="hidden" id="_main_ledger_id" name="_main_ledger_id" class="form-control _main_ledger_id" value="<?php echo e(old('_main_ledger_id',$data->_ledger_id)); ?>" placeholder="Supplier" required>
                            <div class="search_box_main_ledger"> </div>

                                
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-3 ">
                            <div class="form-group">
                              <label class="mr-2" for="_referance">Referance:</label>
                              <input type="text" id="_referance" name="_referance" class="form-control _referance" value="<?php echo e(old('_referance',$data->_referance ?? '')); ?>" placeholder="Referance" >
                                
                            </div>
                        </div>
                        <div class="col-md-12  ">
                             <div class="card">
                              <div class="card-header">
                                <strong>Details</strong>

                              </div>
                             
                              <div class="card-body">
                                <div class="table-responsive">
                                      <table class="table table-bordered" >
                                          <thead >
                                            <th class="text-left" >&nbsp;</th>
                                            <th class="text-left" >ID</th>
                                            <th class="text-left" >Item</th>
                                            <th class="text-left display_none" >Base Unit</th>
                                            <th class="text-left display_none" >Con. Qty</th>
                                            <th class="text-left <?php if(isset($form_settings->_show_unit)): ?> <?php if($form_settings->_show_unit==0): ?> display_none    <?php endif; ?> <?php endif; ?>" >Tran. Unit</th>
                                           <?php if(isset($form_settings->_show_barcode)): ?> <?php if($form_settings->_show_barcode==1): ?>
                                            <th class="text-left" >Barcode</th>
                                            <?php else: ?>
                                            <th class="text-left display_none" >Barcode</th>
                                            <?php endif; ?>
                                            <?php endif; ?>
                                            <th class="text-left" >Qty</th>
                                            <th class="text-left" >Rate</th>
                                            <th class="text-left" >Sales Rate</th>
                                            <?php if(isset($form_settings->_show_vat)): ?> <?php if($form_settings->_show_vat==1): ?>
                                            <th class="text-left" >VAT%</th>
                                            <th class="text-left" >VAT</th>
                                             <?php else: ?>
                                            <th class="text-left display_none" >VAT%</th>
                                            <th class="text-left display_none" >VAT Amount</th>
                                            <?php endif; ?>
                                            <?php endif; ?>

                                            <th class="text-left" >Value</th>
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
                                          <?php
                                          $_total_qty_amount = 0;
                                          $_total_vat_amount =0;
                                          $_total_value_amount =0;

                                          $__master_details = $data->_master_details ?? [];
                                          ?>
                                          <tbody class="area__purchase_details" id="area__purchase_details">
                                            <?php if(sizeof($__master_details)> 0): ?>
                                            <?php $__empty_1 = true; $__currentLoopData = $data->_master_details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m_key=> $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                             <?php
                                              $_total_qty_amount += $detail->_qty ??  0;
                                              $_total_vat_amount += $detail->_vat_amount ??  0;
                                              $_total_value_amount += $detail->_value ??  0;
                                              ?>
                                            <tr class="_purchase_row">
                                              <td>
                                                <a  href="#none" class="btn btn-default _purchase_row_remove" ><i class="fa fa-trash"></i></a>
                                              </td>
                                              <td>
                                                <?php echo e($detail->id); ?>

                                                <input type="hidden" name="purchase_detail_id[]" value="<?php echo e($detail->id); ?>" class="form-control purchase_detail_id" >
                                                
                                              </td>
                                              <td>
                                                <input type="text" name="_search_item_id[]" class="form-control _search_item_id width_280_px" placeholder="Item" value="<?php echo e($detail->_items->_name ?? ''); ?>(<?php echo e(($detail->_qty ?? 0)* ($detail->_unit_conversion ?? 1)); ?> + <?php echo e($detail->_product_price_item->_qty ?? ''); ?> ) <?php echo e($detail->_units->_name ?? ''); ?>" readonly>
                                                <input type="hidden" name="_item_id[]" class="form-control _item_id " value="<?php echo e($detail->_item_id); ?>" >
                                                <input type="hidden" name="_purchase_detal_ref[]" class="form-control _purchase_detal_ref " value="<?php echo e($detail->_purchase_detal_ref); ?>" >
                                                <input type="hidden" name="_purchase_ref_id[]" class="form-control _purchase_ref_id " value="<?php echo e($detail->_purchase_ref_id); ?>" >
                                                <div class="search_box_item">
                                                  
                                                </div>
                                              </td>
                                                <td class="display_none">
                                                <input type="hidden" class="form-control _base_unit_id width_100_px" name="_base_unit_id[]" value="<?php echo e($detail->_units->id); ?>" />
                                                <input type="text" class="form-control _main_unit_val width_100_px" readonly name="_main_unit_val[]" value="<?php echo e($detail->_units->_name); ?>" />
                                                <input type="hidden" name="_base_rate[]" class="form-control _base_rate _common_keyup" value="<?php echo $detail->_items->_pur_rate ?? 0; ?>" >
                                              </td>
                                              <td class="display_none">
                                                <input type="number" name="conversion_qty[]" min="0" step="any" class="form-control conversion_qty " value="<?php echo e($detail->_unit_conversion ?? 1); ?>" readonly>
                                              </td>
                                              <td class="<?php if($form_settings->_show_unit==0): ?> display_none <?php endif; ?>">
                                                <select class="form-control _transection_unit" name="_transection_unit[]">
                                                  <?php
                                                  $own_unit_conversions = $detail->unit_conversion ?? [];
                                                  ?>
                                                  <?php $__empty_2 = true; $__currentLoopData = $own_unit_conversions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $conversionUnit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                                                  <option value="<?php echo e($conversionUnit->_conversion_unit); ?>"  
                                                attr_base_unit_id="<?php echo e($conversionUnit->_base_unit_id); ?>" 
                                                attr_conversion_qty="<?php echo e($conversionUnit->_conversion_qty); ?>" 
                                                attr_conversion_unit="<?php echo e($conversionUnit->_conversion_unit); ?>" 
                                                attr_item_id="<?php echo e($conversionUnit->_item_id); ?>"
                                                <?php if($detail->_transection_unit ==$conversionUnit->_conversion_unit): ?> selected  <?php endif; ?>

                                                 ><?php echo e($conversionUnit->_conversion_unit_name ?? ''); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                                                <?php endif; ?>

                                                </select>
                                              </td>
                                               <?php if(isset($form_settings->_show_barcode)): ?> <?php if($form_settings->_show_barcode==1): ?>
                                              <td>
                                                <input type="text" name="<?php echo e(($m_key+1)); ?>__barcode__<?php echo e($detail->_item_id); ?>" class="form-control _barcode <?php echo e(($m_key+1)); ?>__barcode"  value="<?php echo e($detail->_barcode ?? ''); ?>" id="<?php echo e(($m_key+1)); ?>__barcode" >
                                               
                                                <input type="hidden" class="_old_barcode" value="<?php echo e($detail->_barcode ?? ''); ?>"  />

                                                <input type="hidden" name="_ref_counter[]" value="<?php echo e(($m_key+1)); ?>" class="_ref_counter" id="<?php echo e(($m_key+1)); ?>__ref_counter">
                                              </td>
                                              <?php else: ?>
                                              <td class="display_none">
                                                 <input type="hidden" class="_old_barcode" value="<?php echo e($detail->_barcode ?? ''); ?>"  />
                                                <input type="text" name="<?php echo e(($m_key+1)); ?>__barcode__<?php echo e($detail->_item_id); ?>" class="form-control _barcode <?php echo e(($m_key+1)); ?>__barcode"  value="<?php echo e($detail->_barcode ?? ''); ?> " id="<?php echo e(($m_key+1)); ?>__barcode" >
                                               

                                                <input type="hidden" name="_ref_counter[]" value="<?php echo e(($m_key+1)); ?>" class="_ref_counter" id="<?php echo e(($m_key+1)); ?>__ref_counter">
                                              </td>
                                              <?php endif; ?>
                                            <?php endif; ?>
                                              <td>
<?php if($detail->_items->_unique_barcode==1): ?>
 <script type="text/javascript">
  $('#<?php echo ($m_key+1);?>__barcode').amsifySuggestags({
      trimValue: true,
      dashspaces: true,
      showPlusAfter: 1,
      });
</script>
<?php endif; ?>
                                                <input type="number" name="_qty[]" class="form-control _qty _common_keyup"  value="<?php echo e($detail->_qty ?? 0); ?>" <?php if($detail->_items->_unique_barcode==1): ?> readonly <?php endif; ?> >
                                              </td>
                                              <td>
                                                <input type="number" name="_rate[]" class="form-control _rate _common_keyup" value="<?php echo e($detail->_rate ?? 0); ?>" >
                                              </td>
                                              <td>
                                                <input type="number" name="_sales_rate[]" class="form-control _sales_rate " value="<?php echo e($detail->_sales_rate ?? 0); ?>" >
                                              </td>
                                              <?php if(isset($form_settings->_show_vat)): ?> <?php if($form_settings->_show_vat==1): ?>
                                              <td>
                                                <input type="text" name="_vat[]" class="form-control  _vat _common_keyup" value="<?php echo e($detail->_vat ?? 0); ?>">
                                              </td>
                                              <td>
                                                <input type="text" name="_vat_amount[]" class="form-control  _vat_amount" value="<?php echo e($detail->_vat_amount ?? 0); ?>">
                                              </td>
                                              <?php else: ?>
                                              <td class="display_none">
                                                <input type="text" name="_vat[]" class="form-control  _vat _common_keyup" value="<?php echo e($detail->_vat ?? 0); ?>">
                                              </td>
                                              <td class="display_none">
                                                <input type="text" name="_vat_amount[]" class="form-control  _vat_amount" value="<?php echo e($detail->_vat_amount ?? 0); ?>" >
                                              </td>
                                              <?php endif; ?>
                                              <?php endif; ?>
                                              <td>
                                                <input type="number" name="_value[]" class="form-control _value " readonly value="<?php echo e($detail->_value ?? 0); ?>" >
                                              </td>
                                            <?php if(sizeof($permited_branch) > 1): ?>  
                                              <td>
                                                <select class="form-control  _main_branch_id_detail" name="_main_branch_id_detail[]"  required>
                                                  <?php $__empty_2 = true; $__currentLoopData = $permited_branch; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $branch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                                                  <option value="<?php echo e($branch->id); ?>" <?php if(isset($detail->_branch_id)): ?> <?php if($detail->_branch_id == $branch->id): ?> selected <?php endif; ?>   <?php endif; ?>><?php echo e($branch->_name ?? ''); ?></option>
                                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                                                  <?php endif; ?>
                                                </select>
                                              </td>
                                              <?php else: ?>
                                               <td class="display_none">
                                                <select class="form-control  _main_branch_id_detail" name="_main_branch_id_detail[]"  required>
                                                  <?php $__empty_2 = true; $__currentLoopData = $permited_branch; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $branch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                                                  <option value="<?php echo e($branch->id); ?>" <?php if(isset($detail->_branch_id)): ?> <?php if($detail->_branch_id == $branch->id): ?> selected <?php endif; ?>   <?php endif; ?>><?php echo e($branch->_name ?? ''); ?></option>
                                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                                                  <?php endif; ?>
                                                </select>
                                              </td>
                                              <?php endif; ?>
                                             <?php if(sizeof($permited_costcenters) > 1): ?>
                                                <td>
                                                 <select class="form-control  _main_cost_center" name="_main_cost_center[]" required >
                                            
                                                  <?php $__empty_2 = true; $__currentLoopData = $permited_costcenters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $costcenter): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                                                  <option value="<?php echo e($costcenter->id); ?>" <?php if(isset($detail->_cost_center_id)): ?> <?php if($detail->_cost_center_id == $costcenter->id): ?> selected <?php endif; ?>   <?php endif; ?>> <?php echo e($costcenter->_name ?? ''); ?></option>
                                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                                                  <?php endif; ?>
                                                </select>
                                              </td>
                                              <?php else: ?>
                                               <td class="display_none">
                                                 <select class="form-control  _main_cost_center" name="_main_cost_center[]" required >
                                            
                                                  <?php $__empty_2 = true; $__currentLoopData = $permited_costcenters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $costcenter): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                                                  <option value="<?php echo e($costcenter->id); ?>" <?php if(isset($detail->_cost_center_id)): ?> <?php if($detail->_cost_center_id == $costcenter->id): ?> selected <?php endif; ?>   <?php endif; ?> > <?php echo e($costcenter->_name ?? ''); ?></option>
                                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                                                  <?php endif; ?>
                                                </select>
                                              </td>
                                              <?php endif; ?>
                                              <?php if(sizeof($store_houses) > 1): ?>
                                              <td>
                                                <select class="form-control  _main_store_id" name="_main_store_id[]">
                                                  <?php $__empty_2 = true; $__currentLoopData = $store_houses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $store): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                                                  <option value="<?php echo e($store->id); ?>"  <?php if(isset($detail->_store_id)): ?> <?php if($detail->_store_id == $store->id): ?> selected <?php endif; ?>   <?php endif; ?> ><?php echo e($store->_name ?? ''); ?></option>
                                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                                                  <?php endif; ?>
                                                </select>
                                                
                                              </td>
                                              <?php else: ?>
                                              <td class="display_none">
                                                <select class="form-control  _main_store_id" name="_main_store_id[]">
                                                  <?php $__empty_2 = true; $__currentLoopData = $store_houses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $store): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                                                  <option value="<?php echo e($store->id); ?>"   <?php if(isset($detail->_store_id)): ?> <?php if($detail->_store_id == $costcenter->id): ?> selected <?php endif; ?>   <?php endif; ?>  ><?php echo e($store->_name ?? ''); ?></option>
                                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                                                  <?php endif; ?>
                                                </select>
                                                
                                              </td>
                                              <?php endif; ?>
                                              <?php if(isset($form_settings->_show_self)): ?> <?php if($form_settings->_show_self==1): ?>
                                              <td>
                                                <input type="text" name="_store_salves_id[]" class="form-control _store_salves_id " value="<?php echo e($detail->_store_salves_id ?? ''); ?>">
                                              </td>
                                              <?php else: ?>
                                              <td class="display_none">
                                                <input type="text" name="_store_salves_id[]" class="form-control _store_salves_id " value="<?php echo e($detail->_store_salves_id ?? ''); ?>" >
                                              </td>
                                              <?php endif; ?>
                                              <?php endif; ?>
                                              
                                            </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                            <?php endif; ?>
                                            <?php endif; ?>
                                          </tbody>
                                          <tfoot>
                                            <tr>
                                              <td>
                                               
                                              </td>
                                              <td></td>
                                              <td  class="text-right"><b>Total</b></td>
                                              <td class="display_none"></td>
                                              <td class="display_none"></td>
                                              <td class="<?php if($form_settings->_show_unit==0): ?> display_none <?php endif; ?>"></td>
                                              <?php if(isset($form_settings->_show_barcode)): ?> <?php if($form_settings->_show_barcode==1): ?>
                                              <td  class="text-right"></td>
                                              <?php else: ?>
                                                <td  class="text-right display_none"></td>
                                             <?php endif; ?>
                                            <?php endif; ?>
                                              <td>
                                                <input type="number" step="any" min="0" name="_total_qty_amount" class="form-control _total_qty_amount" value="<?php echo e($_total_qty_amount); ?>" readonly required>
                                              </td>
                                              <td></td>
                                              <td></td>
                                              <?php if(isset($form_settings->_show_vat)): ?> <?php if($form_settings->_show_vat==1): ?>
                                              <td></td>
                                              <td>
                                                <input type="number" step="any" min="0" name="_total_vat_amount" class="form-control _total_vat_amount" value="<?php echo e($_total_vat_amount ?? 0); ?>" readonly required>
                                              </td>
                                              <?php else: ?>
                                              <td class="display_none"></td>
                                              <td class="display_none">
                                                <input type="number" step="any" min="0" name="_total_vat_amount" class="form-control _total_vat_amount" value="<?php echo e($_total_vat_amount ?? 0); ?>" readonly required>
                                              </td>
                                              <?php endif; ?>
                                              <?php endif; ?>
                                              <td>
                                                <input type="number" step="any" min="0" name="_total_value_amount" class="form-control _total_value_amount" value="<?php echo e($_total_value_amount ?? 0); ?>" readonly required>



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
                            </div>
                          </div>
                        </div>
                        
                     <?php if($__user->_ac_type==1): ?>
                      <?php echo $__env->make('backend.purchase-return.edit_ac_cb', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                         
                      <?php else: ?>
                       <?php echo $__env->make('backend.purchase-return.edit_ac_detail', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                      <?php endif; ?>     
                          

                        <div class="col-xs-12 col-sm-12 col-md-12 mb-10">
                          <table class="table" style="border-collapse: collapse;">
                            <tr>
                              <td style="width: 10%;border:0px;"><label for="_note">Note<span class="_required">*</span></label></td>
                              <td style="width: 70%;border:0px;">
                                <?php if($_print = Session::get('_print_value')): ?>
                                     <input type="hidden" name="_after_print" value="<?php echo e($_print); ?>" class="_after_print" >
                                    <?php else: ?>
                                    <input type="hidden" name="_after_print" value="0" class="_after_print" >
                                    <?php endif; ?>
                                    <?php if($_master_id = Session::get('_master_id')): ?>
                                     <input type="hidden" name="_master_id" value="<?php echo e(url('purchase-return/print')); ?>/<?php echo e($_master_id); ?>" class="_master_id">
                                    
                                    <?php endif; ?>
                                   
                                       <input type="hidden" name="_print" value="0" class="_save_and_print_value">

                                    <input type="text" id="_note"  name="_note" class="form-control _note" value="<?php echo e(old('_note',$data->_note ?? '' )); ?>" placeholder="Note" required >
                              </td>
                            </tr>
                            <tr>
                              <td style="width: 10%;border:0px;"><label for="_sub_total">Sub Total</label></td>
                              <td style="width: 70%;border:0px;">
                                <input type="text" name="_sub_total" class="form-control width_200_px" id="_sub_total" readonly value="<?php echo e(_php_round($data->_sub_total ?? 0)); ?>">
                              </td>
                            </tr>
                            <tr>
                              <td style="width: 10%;border:0px;"><label for="_discount_input">Invoice Discount</label></td>
                              <td style="width: 70%;border:0px;">
                                <input type="text" name="_discount_input" class="form-control width_200_px" id="_discount_input" value="<?php echo e($data->_discount_input ?? 0); ?>" >
                              </td>
                            </tr>
                            <tr>
                              <td style="width: 10%;border:0px;"><label for="_total_discount">Total Discount</label></td>
                              <td style="width: 70%;border:0px;">
                                <input type="text" name="_total_discount" class="form-control width_200_px" id="_total_discount" readonly value="<?php echo e($data->_total_discount ?? 0); ?>">
                              </td>
                            </tr>
                            <?php if(isset($form_settings->_show_vat)): ?> 
                            <?php if($form_settings->_show_vat==1): ?>
                            <tr>
                              <td style="width: 10%;border:0px;"><label for="_total_vat">Total VAT</label></td>
                              <td style="width: 70%;border:0px;">
                                <input type="text" name="_total_vat" class="form-control width_200_px" id="_total_vat" readonly value="<?php echo e($data->_total_vat ?? 0); ?>">
                              </td>
                            </tr>
                            <?php else: ?>
                            <tr class="display_none">
                              <td style="width: 10%;border:0px;"><label for="_total_vat">Total VAT</label></td>
                              <td style="width: 70%;border:0px;">
                                <input type="text" name="_total_vat" class="form-control width_200_px" id="_total_vat" readonly value="<?php echo e($data->_total_vat ?? 0); ?>">
                              </td>
                            </tr>
                            <?php endif; ?>
                            <?php endif; ?>
                            <tr>
                              <td style="width: 10%;border:0px;"><label for="_total">NET Total </label></td>
                              <td style="width: 70%;border:0px;">
                          <input type="text" name="_total" class="form-control width_200_px" id="_total" readonly value="<?php echo e(_php_round($data->_total ?? 0)); ?>">

                           <input type="hidden" name="_item_row_count" value="<?php echo e(sizeof($__master_details)); ?>" class="_item_row_count">
                              </td>
                            </tr>
                             <?php echo $__env->make('backend.message.send_sms', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                          </table>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 bottom_save_section text-middle">
                            <button type="submit" class="btn btn-success submit-button ml-5"><i class="fa fa-credit-card mr-2" aria-hidden="true"></i> Save</button>
                            <button type="submit" class="btn btn-warning submit-button _save_and_print"><i class="fa fa-print mr-2" aria-hidden="true"></i> Save & Print</button>
                        </div>
                        <br><br>
                        
                    </div>
                    <?php echo Form::close(); ?>

                
              </div>
            </div>
            <!-- /.card -->

            
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form action="<?php echo e(url('purchase-return-settings')); ?>" method="POST">
        <?php echo csrf_field(); ?>
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Purchase Form Settings</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
       
        <div class="form-group row">
        <label for="_default_inventory" class="col-sm-5 col-form-label">Default Inventory</label>
        <select class="form-control col-sm-7" name="_default_inventory">
          <?php $__currentLoopData = $inv_accounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $account): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <option value="<?php echo e($account->id); ?>" <?php if(isset($form_settings->_default_inventory)): ?><?php if($form_settings->_default_inventory==$account->id): ?> selected <?php endif; ?> <?php endif; ?>><?php echo e($account->_name ?? ''); ?></option>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
      </div>
      <div class="form-group row">
        <label for="_default_purchase" class="col-sm-5 col-form-label">Default Purchase Account</label>
        <select class="form-control col-sm-7" name="_default_purchase">
          <?php $__currentLoopData = $p_accounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $account): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <option value="<?php echo e($account->id); ?>" <?php if(isset($form_settings->_default_purchase)): ?><?php if($form_settings->_default_purchase==$account->id): ?> selected <?php endif; ?> <?php endif; ?>><?php echo e($account->_name ?? ''); ?></option>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
      </div>
      <div class="form-group row">
        <label for="_default_discount" class="col-sm-5 col-form-label">Default Discount Account</label>
        <select class="form-control col-md-7" name="_default_discount">
          <?php $__currentLoopData = $dis_accounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $account): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <option value="<?php echo e($account->id); ?>" <?php if(isset($form_settings->_default_discount)): ?><?php if($form_settings->_default_discount==$account->id): ?> selected <?php endif; ?> <?php endif; ?>><?php echo e($account->_name ?? ''); ?></option>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
      </div>
      <div class="form-group row">
        <label for="_default_vat_account" class="col-sm-5 col-form-label">Default VAT Account</label>
        <select class="form-control col-md-7" name="_default_vat_account">
          <?php $__currentLoopData = $vat_accounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $account): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <option value="<?php echo e($account->id); ?>" <?php if(isset($form_settings->_default_vat_account)): ?><?php if($form_settings->_default_vat_account==$account->id): ?> selected <?php endif; ?> <?php endif; ?>><?php echo e($account->_name ?? ''); ?></option>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
      </div>
      <div class="form-group row">
        <label for="_show_vat" class="col-sm-5 col-form-label">Show VAT</label>
        <select class="form-control col-sm-7" name="_show_vat">
         
          <option value="0" <?php if(isset($form_settings->_show_vat)): ?><?php if($form_settings->_show_vat==0): ?> selected <?php endif; ?> <?php endif; ?>>NO</option>
          <option value="1" <?php if(isset($form_settings->_show_vat)): ?><?php if($form_settings->_show_vat==1): ?> selected <?php endif; ?> <?php endif; ?>>YES</option>
        </select>
      </div>
      <div class="form-group row">
        <label for="_show_unit" class="col-sm-5 col-form-label">Show Unit</label>
        <select class="form-control col-sm-7" name="_show_unit">
          <option value="0" <?php if(isset($form_settings->_show_unit)): ?><?php if($form_settings->_show_unit==0): ?> selected <?php endif; ?> <?php endif; ?>>NO</option>
          <option value="1" <?php if(isset($form_settings->_show_unit)): ?><?php if($form_settings->_show_unit==1): ?> selected <?php endif; ?> <?php endif; ?>>YES</option>
        </select>
      </div>
      <div class="form-group row">
        <label for="_show_barcode" class="col-sm-5 col-form-label">Show Barcode</label>
        <select class="form-control col-sm-7" name="_show_barcode">
          <option value="0" <?php if(isset($form_settings->_show_barcode)): ?><?php if($form_settings->_show_barcode==0): ?> selected <?php endif; ?> <?php endif; ?>>NO</option>
          <option value="1" <?php if(isset($form_settings->_show_barcode)): ?><?php if($form_settings->_show_barcode==1): ?> selected <?php endif; ?> <?php endif; ?>>YES</option>
        </select>
      </div>
      <div class="form-group row">
        <label for="_show_store" class="col-sm-5 col-form-label">Show Store</label>
        <select class="form-control col-sm-7" name="_show_store">
          <option value="0" <?php if(isset($form_settings->_show_store)): ?><?php if($form_settings->_show_store==0): ?> selected <?php endif; ?> <?php endif; ?>>NO</option>
          <option value="1" <?php if(isset($form_settings->_show_store)): ?><?php if($form_settings->_show_store==1): ?> selected <?php endif; ?> <?php endif; ?>>YES</option>
        </select>
      </div>
      <div class="form-group row">
        <label for="_show_self" class="col-sm-5 col-form-label">Show Shelf</label>
        <select class="form-control col-sm-7" name="_show_self">
          <option value="0" <?php if(isset($form_settings->_show_self)): ?><?php if($form_settings->_show_self==0): ?> selected <?php endif; ?> <?php endif; ?>>NO</option>
          <option value="1" <?php if(isset($form_settings->_show_self)): ?><?php if($form_settings->_show_self==1): ?> selected <?php endif; ?> <?php endif; ?>>YES</option>
        </select>
      </div>
      <div class="form-group row">
        <label for="_show_p_balance" class="col-sm-5 col-form-label">Invoice Show Previous Balance</label>
        <select class="form-control col-sm-7" name="_show_p_balance">
          <option value="0" <?php if(isset($form_settings->_show_p_balance)): ?><?php if($form_settings->_show_p_balance==0): ?> selected <?php endif; ?> <?php endif; ?>>NO</option>
          <option value="1" <?php if(isset($form_settings->_show_p_balance)): ?><?php if($form_settings->_show_p_balance==1): ?> selected <?php endif; ?> <?php endif; ?>>YES</option>
        </select>
      </div>
       <div class="form-group row">
        <label for="_invoice_template" class="col-sm-5 col-form-label">Invoice Template</label>
        <select class="form-control col-sm-7" name="_invoice_template">
          <option value="1" <?php if(isset($form_settings->_invoice_template)): ?><?php if($form_settings->_invoice_template==1): ?> selected <?php endif; ?> <?php endif; ?>>Template A</option>
          <option value="2" <?php if(isset($form_settings->_invoice_template)): ?><?php if($form_settings->_invoice_template==2): ?> selected <?php endif; ?> <?php endif; ?>>Template B</option>
          <option value="3" <?php if(isset($form_settings->_invoice_template)): ?><?php if($form_settings->_invoice_template==3): ?> selected <?php endif; ?> <?php endif; ?>>Template C</option>
          <option value="4" <?php if(isset($form_settings->_invoice_template)): ?><?php if($form_settings->_invoice_template==4): ?> selected <?php endif; ?> <?php endif; ?>>Template D</option>
        </select>
      </div>
         
      
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary exampleModalClose" >Close</button>
        <button type="submit" class="btn btn-primary">Save changes</button>
      </div>
       </form>
    </div>
  </div>



</div>
<?php echo $__env->make('backend.common-modal.item_ledger_modal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>

<script type="text/javascript">
  <?php if(empty($form_settings)): ?>
    $("#form_settings").click();
  <?php endif; ?>
  var default_date_formate = `<?php echo e(default_date_formate()); ?>`;
  var _after_print = $(document).find("._after_print").val();
  var _master_id = $(document).find("._master_id").val();
  if(_after_print ==1){
      var open_new = window.open(_master_id, '_blank');
      if (open_new) {
          //Browser has allowed it to be opened
          open_new.focus();
      } else {
          //Browser has blocked it
          alert('Please allow popups for this website');
      }
  }

   var _item_row_count = parseFloat($(document).find('._item_row_count').val());
  

  $(document).on('keyup','._search_order_ref_id',delay(function(e){
    $(document).find('._search_order_ref_id').removeClass('required_border');
    var _gloabal_this = $(this);
    var _text_val = $(this).val().trim();

  var request = $.ajax({
      url: "<?php echo e(url('purchase-order-search')); ?>",
      method: "GET",
      data: { _text_val },
      dataType: "JSON"
    });
    request.done(function( result ) {
      var search_html =``;
      var data = result.data; 
      if(data.length > 0 ){
            search_html +=`<div class="card"><table table-bordered style="width: 100%;">
                            <thead>
                              <th style="border:1px solid #ccc;text-align:center;">ID</th>
                              <th style="border:1px solid #ccc;text-align:center;">Supplier</th>
                              <th style="border:1px solid #ccc;text-align:center;">Date</th>
                            </thead>
                            <tbody>`;
                        for (var i = 0; i < data.length; i++) {
                         search_html += `<tr class="search_row_purchase_order" >
                                        <td style="border:1px solid #ccc;">${data[i].id}
                                        <input type="hidden" name="_id_main_ledger" class="_id_main_ledger" value="${data[i]._ledger_id}">
                                        <input type="hidden" name="_purchase_main_id" class="_purchase_main_id" value="${data[i].id}">
                                        <input type="hidden" name="_purchase_main_date" class="_purchase_main_date" value="${after_request_date__today(data[i]._date)}">
                                        </td><td style="border:1px solid #ccc;">${data[i]._ledger._name}
                                        <input type="hidden" name="_name_main_ledger" class="_name_main_ledger" value="${data[i]._ledger._name}">
                                   </td>
                                   <td style="border:1px solid #ccc;">${after_request_date__today(data[i]._date)}
                                   </td></tr>`;
                        }                         
            search_html += ` </tbody> </table></div>`;
      }else{
        search_html +=`<div class="card"><table style="width: 300px;"> 
        <thead><th colspan="3">No Data Found</th></thead><tbody></tbody></table></div>`;
      }     
      _gloabal_this.parent('div').find('.search_box_purchase_order').html(search_html);
      _gloabal_this.parent('div').find('.search_box_purchase_order').addClass('search_box_show').show();
      
    });
     
    request.fail(function( jqXHR, textStatus ) {
      alert( "Request failed: " + textStatus );
    });

  

}, 500));

 $(document).on("click",'.search_row_purchase_order',function(){
    var _id = $(this).children('td').find('._id_main_ledger').val();
    var _name = $(this).find('._name_main_ledger').val();
    var _purchase_main_id = $(this).find('._purchase_main_id').val();
    var _purchase_main_date = $(this).find('._purchase_main_date').val();
    var _main_branch_id = $(this).find('._main_branch_id').val();
    $("._main_ledger_id").val(_id);
    $("._search_main_ledger_id").val(_name);
    $("._order_ref_id").val(_purchase_main_id);
    $("._search_order_ref_id").val(_purchase_main_id);

    $.ajaxSetup({ headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

    var request = $.ajax({
      url: "<?php echo e(url('purchase-order-details')); ?>",
      method: "POST",
      data: { _purchase_main_id,_main_branch_id },
      dataType: "JSON"
    });
    request.done(function( result ) {
      var data = result;
      var _purchase_row_single = ``;
     
if(data.length > 0 ){
  for (var i = 0; i < data.length; i++) {
       _purchase_row_single +=`<tr class="_purchase_row">
                                              <td>
                                                <a  href="#none" class="btn btn-default _purchase_row_remove _purchase_row_remove__${i}" ><i class="fa fa-trash"></i></a>
                                              </td>
                                              <td></td>
                                              <td>
                                                <input type="text" name="_search_item_id[]" class="form-control _search_item_id _search_item_id__${i} width_280_px" placeholder="Item" value="${data[i]._item}" readonly>
                                                <input type="hidden" name="_item_id[]" class="form-control _item_id _item_id__${i} " value="${data[i]._item_id}">
                                                <input type="hidden" name="_price_list_id[]" class="form-control _price_list_id _price_list_id__${i} " value="${data[i].id}">
                                                <input type="hidden" name="_purchase_detal_ref[]" class="form-control _purchase_detal_ref _purchase_detal_ref__${i} " value="${data[i]._purchase_detail_id}">

                                                <div class="search_box_item">
                                                  
                                                </div>
                                              </td>
                                              <?php if(isset($form_settings->_show_barcode)): ?> <?php if($form_settings->_show_barcode==1): ?>
                                              <td>
                                              <input type="hidden" class="_old_barcode" value="${((data[i]._barcode=='null') ? '' : data[i]._barcode) }" />
                                                <input type="text" name="_barcode[]" class="form-control _barcode _barcode__${i} " value="${((data[i]._barcode=='null') ? '' : data[i]._barcode) }" >
                                              </td>
                                              <?php else: ?>
                                              <td class="display_none">
                                              <input type="hidden" class="_old_barcode" value="${((data[i]._barcode=='null') ? '' : data[i]._barcode) }" />
                                                <input type="text" name="_barcode[]" class="form-control _barcode _barcode__${i} " value="${((data[i]._barcode=='null') ? '' : data[i]._barcode) }"  >
                                              </td>
                                              <?php endif; ?>
                                              <?php endif; ?>
                                              <td>
                                                <input type="number" name="_qty[]" class="form-control _qty _qty__${i} _common_keyup" value="${data[i]._qty}" >
                                              </td>
                                              <td>
                                                <input type="number" name="_rate[]" class="form-control _rate _rate__${i} _common_keyup" value="${data[i]._pur_rate}" >
                                              </td>
                                              <td>
                                                <input type="number" name="_sales_rate[]" class="form-control _sales_rate _sales_rate__${i} " value="${data[i]._sales_rate}">
                                              </td>
                                               <?php if(isset($form_settings->_show_vat)): ?> <?php if($form_settings->_show_vat==1): ?>
                                              <td>
                                                <input type="text" name="_vat[]" class="form-control  _vat _vat__${i} _common_keyup" value="${data[i]._p_vat}" >
                                              </td>
                                              <td>
                                                <input type="text" name="_vat_amount[]" class="form-control  _vat_amount _vat_amount__${i}" value="${data[i]._p_vat_amount}" >
                                              </td>
                                              <?php else: ?>
                                                <td class="display_none">
                                                <input type="text" name="_vat[]" class="form-control  _vat _vat__${i} _common_keyup" value="${data[i]._p_vat}" >
                                              </td>
                                              <td class="display_none">
                                                <input type="text" name="_vat_amount[]" class="form-control  _vat_amount _vat_amount__${i}" value="${data[i]._p_vat_amount}" >
                                              </td>
                                              <?php endif; ?>
                                              <?php endif; ?>
                                              <td>
                                                <input type="number" name="_value[]" class="form-control _value _value__${i} " readonly value="${data[i]._value}">
                                              </td> 
                                              <?php if(sizeof($permited_branch)>1): ?>
                                              <td>
                                              <input class="form-control _branch_detail_name__${i}" type="text" name="_branch_detail_name[]" value="${data[i]._detail_branch._name}" />
                                              <input type="hidden" class="form-control _main_branch_id_detail__${i}"  name="_main_branch_id_detail[]" value="${data[i]._detail_branch.id}" />
                                               
                                              </td>
                                              <?php else: ?>
                                              <td class="display_none">
                                                <input class="form-control _branch_detail_name__${i}" type="text" name="_branch_detail_name[]" value="${data[i]._detail_branch._name}" />
                                              <input type="hidden" class="form-control _main_branch_id_detail__${i}"  name="_main_branch_id_detail[]" value="${data[i]._detail_branch.id}" />
                                              </td>
                                              <?php endif; ?>
                                              <?php if(sizeof($permited_costcenters)>1): ?>
                                                <td>
                                                <input class="form-control _main_cost_center_name__${i}" type="text" name="_main_cost_center_name__[]" value="${data[i]._detail_cost_center._name}" />
                                              <input type="hidden" class="form-control _main_cost_center__${i}"  name="_main_cost_center[]" value="${data[i]._detail_cost_center.id}" />

                                                 
                                                </select>
                                              </td>
                                              <?php else: ?>
                                               <td class="display_none">
                                                 <input class="form-control _main_cost_center_name__${i}" type="text" name="_main_cost_center_name__[]" value="${data[i]._detail_cost_center._name}" />
                                              <input type="hidden" class="form-control _main_cost_center__${i}"  name="_main_cost_center[]" value="${data[i]._detail_cost_center.id}" />
                                              </td>
                                              <?php endif; ?>
                                              <?php if(sizeof($store_houses) > 1): ?>
                                              <td>
                                              <input class="form-control _main_store_id_name__${i}" type="text" name="_main_store_id_name[]" value="${data[i]._store._name}" />
                                              <input type="hidden" class="form-control _main_store_id__${i}"  name="_main_store_id[]" value="${data[i]._store.id}" />

                                               
                                                
                                              </td>
                                              <?php else: ?>
                                              <td class="display_none">
                                                <input class="form-control _main_store_id_name__${i}" type="text" name="_main_store_id_name[]" value="${data[i]._store._name}" />
                                              <input type="hidden" class="form-control _main_store_id__${i}"  name="_main_store_id[]" value="${data[i]._store.id}" />
                                                
                                              </td>
                                              <?php endif; ?>
                                              <?php if(isset($form_settings->_show_self)): ?> <?php if($form_settings->_show_self==1): ?>
                                              <td>
                                              
                                                <input type="text" name="_store_salves_id[]" class="form-control _store_salves_id _store_salves_id__${i} " value="${((data[i]._store_salves_id=='null') ? '' : data[i]._store_salves_id) }">
                                              </td>
                                              <?php else: ?>
                                              <td class="display_none">
                                                <input type="text" name="_store_salves_id[]" class="form-control _store_salves_id _store_salves_id__${i} " value="${((data[i]._store_salves_id=='null') ? '' : data[i]._store_salves_id) }">
                                              </td>
                                              <?php endif; ?>

                                              <?php endif; ?>
                                              
                                            </tr>`;
                                          }
                                        }else{
                                          _purchase_row_single += `Returnable Item Not Found !`;
                                        }

            $(document).find("#area__purchase_details").html(_purchase_row_single);
              _purchase_total_calculation();
    })



  })


  $(document).on('keyup','._search_main_ledger_id',delay(function(e){
    $(document).find('._search_main_ledger_id').removeClass('required_border');
    var _gloabal_this = $(this);
    var _text_val = $(this).val().trim();
    var _form = 2;

  var request = $.ajax({
      url: "<?php echo e(url('main-ledger-search')); ?>",
      method: "GET",
      data: { _text_val,_form },
      dataType: "JSON"
    });
    request.done(function( result ) {
      var search_html =``;
      var data = result.data; 
      if(data.length > 0 ){
            search_html +=`<div class="card"><table style="width: 300px;">
                            <tbody>`;
                        for (var i = 0; i < data.length; i++) {
                         search_html += `<tr class="search_row_ledger" >
                                        <td>${data[i].id}
                                        <input type="hidden" name="_id_main_ledger" class="_id_main_ledger" value="${data[i].id}">
                                        </td><td>${data[i]._name}
                                        <input type="hidden" name="_name_main_ledger" class="_name_main_ledger" value="${data[i]._name}">
                                  
                                   </td></tr>`;
                        }                         
            search_html += ` </tbody> </table></div>`;
      }else{
        search_html +=`<div class="card"><table style="width: 300px;"> 
        <thead><th colspan="3">No Data Found</th></thead><tbody></tbody></table></div>`;
      }     
      _gloabal_this.parent('div').find('.search_box_main_ledger').html(search_html);
      _gloabal_this.parent('div').find('.search_box_main_ledger').addClass('search_box_show').show();
      
    });
     
    request.fail(function( jqXHR, textStatus ) {
      alert( "Request failed: " + textStatus );
    });

  

}, 500));


 $(document).on('change','._transection_unit',function(){
  var __this = $(this);
  var conversion_qty = $('option:selected', this).attr('attr_conversion_qty');
 
  $(this).closest('tr').find(".conversion_qty").val(conversion_qty);


  converted_qty_value(__this);
})
function converted_qty_value(__this){

  var _vat_amount =0;
  var _qty = __this.closest('tr').find('._qty').val();
  var _rate = __this.closest('tr').find('._rate').val();
  var _base_rate = __this.closest('tr').find('._base_rate').val();
  var _sales_rate =parseFloat( __this.closest('tr').find('._sales_rate').val());
  var _item_vat = __this.closest('tr').find('._vat').val();
  var conversion_qty = parseFloat(__this.closest('tr').find('.conversion_qty').val());
  var _item_discount = parseFloat(__this.closest('tr').find('._discount').val());




   if(isNaN(_item_vat)){ _item_vat   = 0 }

  if(isNaN(conversion_qty)){ conversion_qty   = 1 }
  var converted_price_rate = (( conversion_qty/1)*_base_rate);

   if(isNaN(_qty)){ _qty   = 0 }
   if(isNaN(_rate)){ _rate =0 }
   if(isNaN(_base_rate)){ _base_rate =0 }

  if(converted_price_rate ==0){
    converted_price_rate = _rate;
  }

   if(isNaN(_item_vat)){ _item_vat   = 0 }
   if(isNaN(_qty)){ _qty   = 0 }
   if(isNaN(_rate)){ _rate =0 }
   if(isNaN(_item_discount)){ _item_discount =0 }
   _vat_amount = Math.ceil(((_qty*converted_price_rate)*_item_vat)/100)
   _discount_amount = Math.ceil(((_qty*converted_price_rate)*_item_discount)/100)


   var _value = parseFloat(converted_price_rate*_qty).toFixed(2);
 __this.closest('tr').find('._rate').val(converted_price_rate);
 __this.closest('tr').find('._value').val(_value);
  __this.closest('tr').find('._vat_amount').val(_vat_amount);
  __this.closest('tr').find('._discount_amount').val(_discount_amount);
    _purchase_total_calculation();


}


  $(document).on("click",'.search_row_ledger',function(){
    var _id = $(this).children('td').find('._id_main_ledger').val();
    var _name = $(this).find('._name_main_ledger').val();
    $("._main_ledger_id").val(_id);
    $("._search_main_ledger_id").val(_name);

    $('.search_box_main_ledger').hide();
    $('.search_box_main_ledger').removeClass('search_box_show').hide();
  })

  $(document).on('keyup','._search_item_id',delay(function(e){
    $(document).find('._search_item_id').removeClass('required_border');
    var _gloabal_this = $(this);
    var _text_val = $(this).val().trim();


  var request = $.ajax({
      url: "<?php echo e(url('item-purchase-search')); ?>",
      method: "GET",
      data: { _text_val : _text_val },
      dataType: "JSON"
    });
     
    request.done(function( result ) {

      var search_html =``;
      var data = result.data; 
      if(data.length > 0 ){
            search_html +=`<div class="card"><table style="width: 300px;">
                            <tbody>`;
                        for (var i = 0; i < data.length; i++) {
                         search_html += `<tr class="search_row_item" >
                                        <td>${data[i].id}
                                        <input type="hidden" name="_id_item" class="_id_item" value="${data[i].id}">
                                        </td><td>${data[i]._name}
                                        <input type="hidden" name="_name_item" class="_name_item" value="${data[i]._name}">
                                  <input type="hidden" name="_item_barcode" class="_item_barcode" value="${data[i]._barcode}">
                                  <input type="hidden" name="_item_rate" class="_item_rate" value="${data[i]._pur_rate}">
                                  <input type="hidden" name="_item_sales_rate" class="_item_sales_rate" value="${data[i]._sale_rate}">
                                  <input type="hidden" name="_item_vat" class="_item_vat" value="${data[i]._vat}">
                                   </td></tr>`;
                        }                         
            search_html += ` </tbody> </table></div>`;
      }else{
        search_html +=`<div class="card"><table style="width: 300px;"> 
        <thead><th colspan="3">No Data Found</th></thead><tbody></tbody></table></div>`;
      }     
      _gloabal_this.parent('td').find('.search_box_item').html(search_html);
      _gloabal_this.parent('td').find('.search_box_item').addClass('search_box_show').show();
      
    });
     
    request.fail(function( jqXHR, textStatus ) {
      alert( "Request failed: " + textStatus );
    });

  

}, 500));

$(document).on('click','.search_row_item',function(){
  var _vat_amount =0;
  var _id = $(this).children('td').find('._id_item').val();
  var _name = $(this).find('._name_item').val();
  var _item_barcode = $(this).find('._item_barcode').val();
  if(_item_barcode=='null'){ _item_barcode='' } 
  var _item_rate = $(this).find('._item_rate').val();
  var _item_sales_rate = $(this).find('._item_sales_rate').val();
  var _item_vat = parseFloat($(this).find('._item_vat').val());
  if(isNaN(_item_vat)){ _item_vat=0 }
  _vat_amount = ((_item_rate*_item_vat)/100)


  

  $(this).parent().parent().parent().parent().parent().parent().find('._item_id').val(_id);
  var _id_name = `${_name} `;
  $(this).parent().parent().parent().parent().parent().parent().find('._search_item_id').val(_id_name);
  $(this).parent().parent().parent().parent().parent().parent().find('._barcode').val(_item_barcode);
  $(this).parent().parent().parent().parent().parent().parent().find('._rate').val(_item_rate);
  $(this).parent().parent().parent().parent().parent().parent().find('._sales_rate').val(_item_sales_rate);
  $(this).parent().parent().parent().parent().parent().parent().find('._vat').val(_item_vat);
  $(this).parent().parent().parent().parent().parent().parent().find('._vat_amount').val(_vat_amount);
  $(this).parent().parent().parent().parent().parent().parent().find('._qty').val(1);
  $(this).parent().parent().parent().parent().parent().parent().find('._value').val(_item_rate);

  _purchase_total_calculation();
  $('.search_box_item').hide();
  $('.search_box_item').removeClass('search_box_show').hide();
})

$(document).on('click',function(){
    var searach_show= $('.search_box_item').hasClass('search_box_show');
    var search_box_purchase_order= $('.search_box_purchase_order').hasClass('search_box_show');
    if(searach_show ==true){
      $('.search_box_item').removeClass('search_box_show').hide();
    }
    if(search_box_purchase_order ==true){
      $('.search_box_purchase_order').removeClass('search_box_show').hide();
    }
})

$(document).on('keyup','._common_keyup',function(){
  var _vat_amount =0;
  var _qty = $(this).closest('tr').find('._qty').val();
  var _rate = $(this).closest('tr').find('._rate').val();
  var _item_vat = $(this).closest('tr').find('._vat').val();
   if(isNaN(_item_vat)){ _item_vat   = 0 }
   if(isNaN(_qty)){ _qty   = 0 }
   if(isNaN(_rate)){ _rate =0 }
   _vat_amount = Math.ceil(((_qty*_rate)*_item_vat)/100)

    $(this).closest('tr').find('._value').val((_qty*_rate));
  $(this).closest('tr').find('._vat_amount').val(_vat_amount);
    _purchase_total_calculation();
})

$(document).on('keyup','._vat_amount',function(){
 var _item_vat =0;
  var _qty = $(this).closest('tr').find('._qty').val();
  var _rate = $(this).closest('tr').find('._rate').val();
  var _vat_amount =  $(this).closest('tr').find('._vat_amount').val();
  
   if(isNaN(_vat_amount)){ _vat_amount = 0 }
   if(isNaN(_qty)){ _qty   = 0 }
   if(isNaN(_rate)){ _rate =0 }
   var _vat = parseFloat((_vat_amount/(_rate*_qty))*100).toFixed(2);
    $(this).closest('tr').find('._vat').val(_vat);

    $(this).closest('tr').find('._value').val((_qty*_rate));
 
    _purchase_total_calculation();
})

$(document).on("change","#_discount_input",function(){
  var _discount_input = $(this).val();
  var res = _discount_input.match(/%/gi);
  if(res){
     res = _discount_input.split("%");
    res= parseFloat(res);
    on_invoice_discount = ($("#_sub_total").val()*res)/100
    $("#_discount_input").val(on_invoice_discount)

  }else{
    on_invoice_discount = _discount_input;
  }

   $("#_total_discount").val(on_invoice_discount);
    _purchase_total_calculation()
})



 function _purchase_total_calculation(){
    var _total_qty = 0;
    var _total__value = 0;
    var _total__vat =0;
      $(document).find("._value").each(function() {
          _total__value +=parseFloat($(this).val());
      });
      $(document).find("._qty").each(function() {
          _total_qty +=parseFloat($(this).val());
      });
      $(document).find("._vat_amount").each(function() {
          _total__vat +=parseFloat($(this).val());
      });
      $("._total_qty_amount").val(_total_qty);
      $("._total_value_amount").val(_total__value);
      $("._total_vat_amount").val(_total__vat);

      var _discount_input = parseFloat($("#_discount_input").val());
      if(isNaN(_discount_input)){ _discount_input =0 }

      $("#_sub_total").val(_math_round(_total__value));
      $("#_total_vat").val(_total__vat);
      $("#_total_discount").val(_discount_input);
      var _total = _math_round((parseFloat(_total__value)+parseFloat(_total__vat))-parseFloat(_discount_input));
      $("#_total").val(_total);
  }


 var single_row =  `<tr class="_voucher_row">
                      <td><a  href="" class="btn btn-sm btn-default _voucher_row_remove" ><i class="fa fa-trash"></i></a></td>
                      <td>
                         <input type="hidden" name="purchase_account_id[]" class="form-control purchase_account_id" value="0">
                        </td>
                      <td><input type="text" name="_search_ledger_id[]" class="form-control _search_ledger_id width_280_px" placeholder="Ledger" <?php if($__user->_ac_type==1): ?> attr_account_head_no="1" <?php endif; ?>   >
                      <input type="hidden" name="_ledger_id[]" class="form-control _ledger_id" >
                      <div class="search_box">
                      </div>
                      </td>
                      
                       <?php if(sizeof($permited_branch)>1): ?>
                      <td>
                      <select class="form-control width_150_px _branch_id_detail" name="_branch_id_detail[]"  required >
                        <?php $__empty_1 = true; $__currentLoopData = $permited_branch; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $branch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <option value="<?php echo e($branch->id); ?>" <?php if(isset($request->_branch_id)): ?> <?php if($request->_branch_id == $branch->id): ?> selected <?php endif; ?>   <?php endif; ?>><?php echo e($branch->_name ?? ''); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <?php endif; ?>
                        </select>
                        </td>
                        <?php else: ?>
                          <td class="display_none">
                      <select class="form-control width_150_px _branch_id_detail" name="_branch_id_detail[]"  required >
                        <?php $__empty_1 = true; $__currentLoopData = $permited_branch; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $branch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <option value="<?php echo e($branch->id); ?>" <?php if(isset($request->_branch_id)): ?> <?php if($request->_branch_id == $branch->id): ?> selected <?php endif; ?>   <?php endif; ?>><?php echo e($branch->_name ?? ''); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <?php endif; ?>
                        </select>
                        </td>
                        <?php endif; ?>

                         <?php if(sizeof($permited_costcenters)>1): ?>
                        <td>
                          <select class="form-control width_150_px _cost_center" name="_cost_center[]" required >
                            <?php $__empty_1 = true; $__currentLoopData = $permited_costcenters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $costcenter): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                              <option value="<?php echo e($costcenter->id); ?>" <?php if(isset($request->_cost_center)): ?> <?php if($request->_cost_center == $costcenter->id): ?> selected <?php endif; ?>   <?php endif; ?>> <?php echo e($costcenter->_name ?? ''); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <?php endif; ?>
                            </select>
                            </td>
                        <?php else: ?>
                        <td class="display_none">
                          <select class="form-control width_150_px _cost_center" name="_cost_center[]" required >
                            <?php $__empty_1 = true; $__currentLoopData = $permited_costcenters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $costcenter): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                              <option value="<?php echo e($costcenter->id); ?>" <?php if(isset($request->_cost_center)): ?> <?php if($request->_cost_center == $costcenter->id): ?> selected <?php endif; ?>   <?php endif; ?>> <?php echo e($costcenter->_name ?? ''); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <?php endif; ?>
                            </select>
                            </td>
                        <?php endif; ?>
                            <td><input type="text" name="_short_narr[]" class="form-control width_250_px" placeholder="Short Narr"></td>
                            <td >
                              <input type="number" name="_dr_amount[]" class="form-control  _dr_amount" placeholder="Dr. Amount" value="<?php echo e(old('_dr_amount',0)); ?>">
                            </td>
                            <td class=" <?php if($__user->_ac_type==1): ?> display_none <?php endif; ?> ">
                              <input type="number" name="_cr_amount[]" class="form-control  _cr_amount" placeholder="Cr. Amount" value="<?php echo e(old('_cr_amount',0)); ?>">
                              </td>
                            </tr>`;

  function voucher_row_add(event) {
      event.preventDefault();
      $("#area__voucher_details").append(single_row);
  }

var _purchase_row_single =`<tr class="_purchase_row">
                                              <td>
                                                <a  href="#none" class="btn btn-default _purchase_row_remove" ><i class="fa fa-trash"></i></a>
                                              </td>
                                              <td></td>
                                              <td>
                                                <input type="text" name="_search_item_id[]" class="form-control _search_item_id width_280_px" placeholder="Item">
                                                <input type="hidden" name="_item_id[]" class="form-control _item_id width_200_px" >
                                                <div class="search_box_item">
                                                  
                                                </div>
                                              </td>
                                              <?php if(isset($form_settings->_show_barcode)): ?> <?php if($form_settings->_show_barcode==1): ?>
                                              <td>
                                                <input type="text" name="_barcode[]" class="form-control _barcode " >
                                              </td>
                                              <?php else: ?>
                                              <td class="display_none">
                                                <input type="text" name="_barcode[]" class="form-control _barcode " >
                                              </td>
                                              <?php endif; ?>
                                              <?php endif; ?>
                                              <td>
                                                <input type="number" name="_qty[]" class="form-control _qty _common_keyup" >
                                              </td>
                                              <td>
                                                <input type="number" name="_rate[]" class="form-control _rate _common_keyup" >
                                              </td>
                                              <td>
                                                <input type="number" name="_sales_rate[]" class="form-control _sales_rate " >
                                              </td>
                                               <?php if(isset($form_settings->_show_vat)): ?> <?php if($form_settings->_show_vat==1): ?>
                                              <td>
                                                <input type="text" name="_vat[]" class="form-control  _vat _common_keyup" >
                                              </td>
                                              <td>
                                                <input type="text" name="_vat_amount[]" class="form-control  _vat_amount" >
                                              </td>
                                              <?php else: ?>
                                                <td class="display_none">
                                                <input type="text" name="_vat[]" class="form-control  _vat _common_keyup" >
                                              </td>
                                              <td class="display_none">
                                                <input type="text" name="_vat_amount[]" class="form-control  _vat_amount" >
                                              </td>
                                              <?php endif; ?>
                                              <?php endif; ?>
                                              <td>
                                                <input type="number" name="_value[]" class="form-control _value " readonly >
                                              </td>
                                              <?php if(sizeof($permited_branch)>1): ?>
                                              <td>
                                                <select class="form-control  _main_branch_id_detail" name="_main_branch_id_detail[]"  required>
                                                  <?php $__empty_1 = true; $__currentLoopData = $permited_branch; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $branch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                  <option value="<?php echo e($branch->id); ?>" <?php if(isset($request->_branch_id)): ?> <?php if($request->_branch_id == $branch->id): ?> selected <?php endif; ?>   <?php endif; ?>><?php echo e($branch->_name ?? ''); ?></option>
                                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                  <?php endif; ?>
                                                </select>
                                              </td>
                                              <?php else: ?>
                                              <td class="display_none">
                                                <select class="form-control  _main_branch_id_detail" name="_main_branch_id_detail[]"  required>
                                                  <?php $__empty_1 = true; $__currentLoopData = $permited_branch; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $branch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                  <option value="<?php echo e($branch->id); ?>" <?php if(isset($request->_branch_id)): ?> <?php if($request->_branch_id == $branch->id): ?> selected <?php endif; ?>   <?php endif; ?>><?php echo e($branch->_name ?? ''); ?></option>
                                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                  <?php endif; ?>
                                                </select>
                                              </td>
                                              <?php endif; ?>
                                              <?php if(sizeof($permited_costcenters)>1): ?>
                                                <td>
                                                 <select class="form-control  _main_cost_center" name="_main_cost_center[]" required >
                                            
                                                  <?php $__empty_1 = true; $__currentLoopData = $permited_costcenters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $costcenter): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                  <option value="<?php echo e($costcenter->id); ?>" <?php if(isset($request->_main_cost_center)): ?> <?php if($request->_main_cost_center == $costcenter->id): ?> selected <?php endif; ?>   <?php endif; ?>> <?php echo e($costcenter->_name ?? ''); ?></option>
                                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                  <?php endif; ?>
                                                </select>
                                              </td>
                                              <?php else: ?>
                                               <td class="display_none">
                                                 <select class="form-control  _main_cost_center" name="_main_cost_center[]" required >
                                            
                                                  <?php $__empty_1 = true; $__currentLoopData = $permited_costcenters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $costcenter): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                  <option value="<?php echo e($costcenter->id); ?>" <?php if(isset($request->_main_cost_center)): ?> <?php if($request->_main_cost_center == $costcenter->id): ?> selected <?php endif; ?>   <?php endif; ?>> <?php echo e($costcenter->_name ?? ''); ?></option>
                                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                  <?php endif; ?>
                                                </select>
                                              </td>
                                              <?php endif; ?>
                                              <?php if(sizeof($store_houses) > 1): ?>
                                              <td>
                                                <select class="form-control  _main_store_id" name="_main_store_id[]">
                                                  <?php $__empty_1 = true; $__currentLoopData = $store_houses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $store): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                  <option value="<?php echo e($store->id); ?>"><?php echo e($store->_name ?? ''); ?></option>
                                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                  <?php endif; ?>
                                                </select>
                                                
                                              </td>
                                              <?php else: ?>
                                              <td class="display_none">
                                                <select class="form-control  _main_store_id" name="_main_store_id[]">
                                                  <?php $__empty_1 = true; $__currentLoopData = $store_houses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $store): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                  <option value="<?php echo e($store->id); ?>"><?php echo e($store->_name ?? ''); ?></option>
                                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                  <?php endif; ?>
                                                </select>
                                                
                                              </td>
                                              <?php endif; ?>
                                              <?php if(isset($form_settings->_show_self)): ?> <?php if($form_settings->_show_self==1): ?>
                                              <td>
                                                <input type="text" name="_store_salves_id[]" class="form-control _store_salves_id " >
                                              </td>
                                              <?php else: ?>
                                              <td class="display_none">
                                                <input type="text" name="_store_salves_id[]" class="form-control _store_salves_id " >
                                              </td>
                                              <?php endif; ?>

                                              <?php endif; ?>
                                              
                                            </tr>`;
function purchase_row_add(event){
   event.preventDefault();
      $("#area__purchase_details").append(_purchase_row_single);
}
 $(document).on('click','._purchase_row_remove',function(event){
      event.preventDefault();
      var ledger_id = $(this).parent().parent('tr').find('._item_id').val();
      if(ledger_id ==""){
          $(this).parent().parent('tr').remove();
      }else{
        if(confirm('Are you sure your want to delete?')){
          $(this).parent().parent('tr').remove();
        } 
      }
      _purchase_total_calculation();
  })

  $(document).on('click','._voucher_row_remove',function(event){
      event.preventDefault();
      var ledger_id = $(this).parent().parent('tr').find('._ledger_id').val();
      if(ledger_id ==""){
          $(this).parent().parent('tr').remove();
      }else{
        if(confirm('Are you sure your want to delete?')){
          $(this).parent().parent('tr').remove();
        } 
      }
      _voucher_total_calculation();
  })




  $(document).on('keyup','._dr_amount',function(){
    $(this).parent().parent('tr').find('._cr_amount').val(0);
    $(document).find("._total_dr_amount").removeClass('required_border');
    $(document).find("._total_cr_amount").removeClass('required_border');
    _voucher_total_calculation();
  })



  $(document).on('keyup','._cr_amount',function(){
     $(this).parent().parent('tr').find('._dr_amount').val(0);
     $(document).find("._total_dr_amount").removeClass('required_border');
      $(document).find("._total_cr_amount").removeClass('required_border');
    _voucher_total_calculation();
  })

  $(document).on('change','._voucher_type',function(){
    $(document).find('._voucher_type').removeClass('required_border');
  })

  $(document).on('keyup','._note',function(){
    $(document).find('._note').removeClass('required_border');
  })

  $(document).on('click','._save_and_print',function(){
    $(document).find('._save_and_print_value').val(1);
  })


  $(document).on('click','.submit-button',function(event){
    event.preventDefault();
    var _total_dr_amount = $(document).find("._total_dr_amount").val();
    var _total_cr_amount = $(document).find("._total_cr_amount").val();
    var _voucher_type = $(document).find('._voucher_type').val();
    var _note = $(document).find('._note').val();
    var _main_ledger_id = $(document).find('._main_ledger_id').val();
    if(_main_ledger_id  ==""){
       alert(" Please Add Ledger  ");
        $(document).find('._search_main_ledger_id').addClass('required_border').focus();
        return false;
    }


    var empty_ledger = [];
    $(document).find("._search_item_id").each(function(){
        if($(this).val() ==""){
          console.log($(this))
          alert(" Please Add Item  ");
          $(this).addClass('required_border');
          empty_ledger.push(1);
        }  
    })

    if(empty_ledger.length > 0){
      return false;
    }


   
<?php if($__user->_ac_type==0): ?>
    if( parseFloat(_total_dr_amount) !=parseFloat(_total_cr_amount)){
      $(document).find("._total_dr_amount").addClass('required_border').focus();
      $(document).find("._total_cr_amount").addClass('required_border').focus();
       alert("Account Details Dr. And Cr. Amount Not Equal");
      return false;

    }
<?php endif; ?>

    if(_note ==""){
       
       $(document).find('._note').focus().addClass('required_border');
      return false;
    }else if(_main_ledger_id ==""){
       
      $(document).find('._search_main_ledger_id').focus().addClass('required_border');
      return false;
    }else{
      $(document).find('.purchase_form').submit();
    }
  })


 function _new_barcode_function(_item_row_count){
      $('#'+_item_row_count+'__barcode').amsifySuggestags({
      trimValue: true,
      dashspaces: true,
      showPlusAfter: 1,
      });
  }


 

</script>


<?php $__env->stopSection(); ?>
<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\own\inv-acc-hrm\resources\views/backend/purchase-return/edit.blade.php ENDPATH**/ ?>