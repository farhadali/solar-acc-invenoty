
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
              <a class="m-0 _page_name" href="<?php echo e(route('import-purchase.index')); ?>"><?php echo $page_name ?? ''; ?> </a>
           
          </div><!-- /.col -->
          <div class=" col-sm-6 ">
            <ol class="breadcrumb float-sm-right">
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
                 <a class="btn btn-sm btn-success" title="List" href="<?php echo e(route('import-purchase.index')); ?>"> <i class="nav-icon fas fa-list"></i> </a>
               </li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
  
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-12">
            <div class="card">
              <div class="card-header">
                 
                   <?php echo $__env->make('backend.message.message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    
              </div>
  <?php
    $_show_barcode = $form_settings->_show_barcode ?? 0;
    $_show_short_note = $form_settings->_show_short_note ?? 0;
    $_show_cost_rate =  $form_settings->_show_cost_rate ?? 0;
    $_show_vat =  $form_settings->_show_vat ?? 0;
    $_inline_discount = $form_settings->_inline_discount ?? 0;
    $_show_self = $form_settings->_show_self ?? 0;
    $_show_unit = $form_settings->_show_unit ?? 0;
    $_show_sd = $form_settings->_show_sd ?? 0;
    $_show_cd = $form_settings->_show_cd ?? 0;
    $_show_ait = $form_settings->_show_ait ?? 0;
    $_show_rd  = $form_settings->_show_rd  ?? 0;
    $_show_at  = $form_settings->_show_at  ?? 0;
    $_show_tti  = $form_settings->_show_tti  ?? 0;
    $_show_expected_qty  = $form_settings->_show_expected_qty  ?? 0;
    $_show_sales_rate  = $form_settings->_show_sales_rate  ?? 0;
    $_show_po  = $form_settings->_show_po  ?? 0;
    $_show_rlp  = $form_settings->_show_rlp  ?? 0;
    $_show_note_sheet  = $form_settings->_show_note_sheet  ?? 0;
    $_show_wo  = $form_settings->_show_wo  ?? 0;
    $_show_lc  = $form_settings->_show_lc  ?? 0;
    $_show_vn  = $form_settings->_show_vn  ?? 0;


    ?>
              <div class="card-body">
               <form action="<?php echo e(route('import-purchase.store')); ?>" method="POST" class="purchase_form" >
                <?php echo csrf_field(); ?>
                                   <div class="row">

                       <div class="col-xs-12 col-sm-12 col-md-2">
                        <input type="hidden" name="_form_name" class="_form_name" value="import_puchases">
                            <div class="form-group">
                                <label><?php echo e(__('label._date')); ?>:</label>
                                  <div class="input-group date" id="reservationdate" data-target-input="nearest">
                                      <input type="text" name="_date" class="form-control datetimepicker-input" data-target="#reservationdate"/>
                                      <div class="input-group-append" data-target="#reservationdate" data-toggle="datetimepicker">
                                          <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                      </div>
                                  </div>
                              </div>
                              <input type="hidden" id="_search_form_value" name="_search_form_value" class="_search_form_value" value="1" >
                              <input type="hidden" name="_item_row_count" value="1" class="_item_row_count">
                        </div>

                        
                        <div class="col-xs-12 col-sm-12 col-md-2 ">
                            <div class="form-group">
                              <label class="mr-2" for="_order_number"><?php echo e(__('label._order_number')); ?>:</label>
                              <input type="text" id="_order_number" name="_order_number" class="form-control _order_number" value="<?php echo e(old('_order_number')); ?>" placeholder="Invoice No" readonly >
                              <input type="hidden" name="_search_form_value" class="_search_form_value" value="2">
                                
                            </div>
                        </div>
                        
                        <div class="col-xs-12 col-sm-12 col-md-2 ">
                            <div class="form-group">
                              <label class="mr-2" for="_purchase_type"><?php echo e(__('label._purchase_type')); ?>:</label>
                              <select class="form-control" name="_purchase_type" >
                                <option value="2">Import</option>
                              </select>
                                
                            </div>
                        </div>
                        <?php echo $__env->make('basic.org_create', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                          <div class="col-xs-12 col-sm-12 col-md-2  <?php if($_show_po==0): ?> display_none <?php endif; ?>">
                            <div class="form-group">
                              <label class="mr-2" for="_order_ref_id"><?php echo e(__('label.purchase_order')); ?>:</label>
                              <input type="text" id="_search_order_ref_id" name="_search_order_ref_id" class="form-control _search_order_ref_id" value="<?php echo e(old('_order_ref_id')); ?>" placeholder="<?php echo e(__('label.purchase_order')); ?>" >
                              <input type="hidden" id="_order_ref_id" name="_order_ref_id" class="form-control _order_ref_id" value="<?php echo e(old('_order_ref_id')); ?>" placeholder="Purchase Order" >
                              <div class="search_box_purchase_order"></div>
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-2 <?php if($_show_po==0): ?> display_none <?php endif; ?>">
                            <div class="form-group">
                              <label class="mr-2" for="_rlp_no"><?php echo e(__('label._rlp_no')); ?>:</label>
                              <input type="text" id="_rlp_no" name="_rlp_no" class="form-control _rlp_no" value="<?php echo e(old('_rlp_no')); ?>" placeholder="<?php echo e(__('label._rlp_no')); ?>" >
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-2 <?php if($_show_note_sheet==0): ?> display_none <?php endif; ?>">
                            <div class="form-group">
                              <label class="mr-2" for="_note_sheet_no"><?php echo e(__('label._note_sheet_no')); ?>:</label>
                              <input type="text" id="_note_sheet_no" name="_note_sheet_no" class="form-control _note_sheet_no" value="<?php echo e(old('_note_sheet_no')); ?>" placeholder="<?php echo e(__('label._note_sheet_no')); ?>" >
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-2 <?php if($_show_wo==0): ?> display_none <?php endif; ?> ">
                            <div class="form-group">
                              <label class="mr-2" for="_workorder_no"><?php echo e(__('label._workorder_no')); ?>:</label>
                              <input type="text" id="_workorder_no" name="_workorder_no" class="form-control _workorder_no" value="<?php echo e(old('_workorder_no')); ?>" placeholder="<?php echo e(__('label._workorder_no')); ?>" >
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-2 <?php if($_show_lc==0): ?> display_none <?php endif; ?>">
                            <div class="form-group">
                              <label class="mr-2" for="_lc_no"><?php echo e(__('label._lc_no')); ?>:</label>
                              <input type="text" id="_lc_no" name="_lc_no" class="form-control _lc_no" value="<?php echo e(old('_lc_no')); ?>" placeholder="<?php echo e(__('label._lc_no')); ?>" >
                            </div>
                        </div>
                        <?php
                        $vessels = \DB::table('mother_vessels')->get();
                        ?>
                        <div class="col-xs-12 col-sm-12 col-md-2   <?php if($_show_vn==0): ?> display_none <?php endif; ?>">
                            <div class="form-group">
                              <label class="mr-2" for="_vessel_no"><?php echo e(__('label._mother_vessel_no')); ?>:</label>
                             
                              <select class="form-control " name="_vessel_no">
                                <option value=""><?php echo e(__('label.select')); ?></option>
                                <?php $__empty_1 = true; $__currentLoopData = $vessels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <option value="<?php echo e($val->id); ?>"><?php echo e($val->_name ?? ''); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <?php endif; ?>
                              </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-2 ">
                            <div class="form-group">
                              <label class="mr-2" for="_arrival_date_time"><?php echo e(__('label._arrival_date_time')); ?>:</label>
                              <input type="datetime-local" id="_arrival_date_time" name="_arrival_date_time" class="form-control _arrival_date_time" value="<?php echo e(old('_arrival_date_time')); ?>" placeholder="<?php echo e(__('label._arrival_date_time')); ?>" >
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-2 ">
                            <div class="form-group">
                              <label class="mr-2" for="_discharge_date_time"><?php echo e(__('label._discharge_date_time')); ?>:</label>
                              <input type="datetime-local" id="_discharge_date_time" name="_discharge_date_time" class="form-control _discharge_date_time" value="<?php echo e(old('_discharge_date_time')); ?>" placeholder="<?php echo e(__('label._discharge_date_time')); ?>" >
                            </div>
                        </div>

                         <div class="col-xs-12 col-sm-12 col-md-3 ">
                            <div class="form-group">
                              <label class="mr-2" for="_main_ledger_id">Supplier:<span class="_required">*</span></label>
                            <input type="text" id="_search_main_ledger_id" name="_search_main_ledger_id" class="form-control _search_main_ledger_id" value="<?php echo e(old('_search_main_ledger_id')); ?>" placeholder="Supplier" required>

                            <input type="hidden" id="_main_ledger_id" name="_main_ledger_id" class="form-control _main_ledger_id" value="<?php echo e(old('_main_ledger_id')); ?>" placeholder="Supplier" required>
                            <div class="search_box_main_ledger"> </div>

                                
                            </div>
                        </div>
                         <div class="col-xs-12 col-sm-12 col-md-2 ">
                            <div class="form-group">
                              <label class="mr-2" for="_phone">Phone:</label>
                              <input type="text" id="_phone" name="_phone" class="form-control _phone" value="<?php echo e(old('_phone')); ?>" placeholder="Phone" >
                                
                            </div>
                        </div>
                        
                        <div class="col-xs-12 col-sm-12 col-md-3 ">
                            <div class="form-group">
                              <label class="mr-2" for="_address">Address:</label>
                              <input type="text" id="_address" name="_address" class="form-control _address" value="<?php echo e(old('_address')); ?>" placeholder="Address" >
                                
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-3 ">
                            <div class="form-group">
                              <label class="mr-2" for="_referance">Referance:</label>
                              <input type="text" id="_referance" name="_referance" class="form-control _referance" value="<?php echo e(old('_referance')); ?>" placeholder="Referance" >
                                
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
                                            <th class="text-left" ><?php echo e(__('label._item_id')); ?></th>
                                            <th class="text-left display_none" ><?php echo e(__('label._base_unit')); ?></th>
                                            <th class="text-left display_none" ><?php echo e(__('label.conversion_qty')); ?></th>
                                            <th class="text-left <?php if(isset($_show_unit)): ?> <?php if($_show_unit==0): ?> display_none    <?php endif; ?> <?php endif; ?>" ><?php echo e(__('label._transection_unit')); ?></th>
                                           
                                            <th class="text-left <?php if($_show_barcode==0): ?> display_none    <?php endif; ?> " ><?php echo e(__('label._barcode')); ?></th>
                                            <th class="text-left <?php if($_show_short_note==0): ?> display_none    <?php endif; ?> " ><?php echo e(__('label._note')); ?></th>
                                         
                                            <th class="text-left <?php if($_show_expected_qty==0): ?> display_none <?php endif; ?>" ><?php echo e(__('label._expected_qty')); ?></th>
                                            <th class="text-left" ><?php echo e(__('label._qty')); ?></th>
                                            <th class="text-left" ><?php echo e(__('label._rate')); ?></th>
                                            <th class="text-left <?php if($_show_sales_rate==0): ?> display_none <?php endif; ?>" ><?php echo e(__('label._sales_rate')); ?></th>

                                            <th class="text-left <?php if($_inline_discount  ==0): ?> display_none <?php endif; ?>" ><?php echo e(__('label._discount')); ?>%</th>
                                            <th class="text-left <?php if($_inline_discount  ==0): ?> display_none <?php endif; ?>" ><?php echo e(__('label._discount_value')); ?></th>
                                           
                                            <th class="text-left <?php if(isset($_show_vat)): ?> <?php if($_show_vat==0): ?> display_none   <?php endif; ?> <?php endif; ?>" ><?php echo e(__('label._vat')); ?>%</th>
                                            <th class="text-left <?php if(isset($_show_vat)): ?> <?php if($_show_vat==0): ?> display_none   <?php endif; ?> <?php endif; ?>" ><?php echo e(__('label._vat_amount')); ?></th>
                                            
                                            <th class="text-left" ><?php echo e(__('label._value')); ?></th>
                                            <th class="text-left  <?php if(sizeof($store_houses) == 1): ?> display_none <?php endif; ?>"><?php echo e(__('label._store_id')); ?></th>
                                             <th class="text-left <?php if(isset($_show_self)): ?> <?php if($_show_self==0): ?> display_none <?php endif; ?>
                                            <?php endif; ?>" ><?php echo e(__('label._shelf')); ?></th>
                                             <th class="text-left <?php if(isset($form_settings->_show_manufacture_date)): ?> <?php if($form_settings->_show_manufacture_date==0): ?> display_none <?php endif; ?>
                                            <?php endif; ?>" ><?php echo e(__('label._manufacture_date')); ?></th>
                                             <th class="text-left <?php if(isset($form_settings->_show_expire_date)): ?> <?php if($form_settings->_show_expire_date==0): ?> display_none <?php endif; ?>
                                            <?php endif; ?>"> <?php echo e(__('label._expire_date')); ?> </th>
                                            
                                           
                                          </thead>
                                          <tbody class="area__purchase_details" id="area__purchase_details">
                                            <tr class="_purchase_row">
                                              <td>
                                                <a  href="#none" class="btn btn-default _purchase_row_remove" ><i class="fa fa-trash"></i></a>
                                              </td>
                                              <td>
                                                <input type="text" name="_search_item_id[]" class="form-control _search_item_id width_280_px" placeholder="Item">
                                                <input type="hidden" name="_item_id[]" class="form-control _item_id width_200_px" >
                                                <div class="search_box_item">
                                                  
                                                </div>
                                              </td>

                                               <td class="display_none">
                                                <input type="hidden" class="form-control _base_unit_id width_100_px" name="_base_unit_id[]" />
                                                <input type="text" class="form-control _main_unit_val width_100_px" readonly name="_main_unit_val[]" />
                                              </td>
                                              <td class="display_none">
                                                <input type="number" name="conversion_qty[]" min="0" step="any" class="form-control conversion_qty " value="1" readonly>
                                              </td>
                                              <td class="<?php if($_show_unit==0): ?> display_none <?php endif; ?>">
                                                <select class="form-control _transection_unit" name="_transection_unit[]">
                                                </select>
                                              </td>
                                              
                                              <td class="<?php if(isset($_show_barcode)): ?> <?php if($_show_barcode==0): ?> display_none   <?php endif; ?> <?php endif; ?>">
                                                <input type="text" name="_barcode[]" class="form-control _barcode 1__barcode "  id="1__barcode">

                                                <input type="hidden" name="_ref_counter[]" value="1" class="_ref_counter" id="1__ref_counter">

                                              </td>
                                              <td class="<?php if(isset($_show_short_note)): ?> <?php if($_show_short_note==0): ?> display_none   <?php endif; ?> <?php endif; ?>">
                                                <input type="text" name="_short_note[]" class="form-control _short_note 1__short_note "  >
                                              </td>
                                            
                                              <td class="<?php if($_show_expected_qty==0): ?> display_none <?php endif; ?>">
                                                <input type="number" name="_expected_qty[]" class="form-control _expected_qty _common_keyup" >
                                              </td>
                                              <td>
                                                <input type="number" name="_qty[]" class="form-control _qty _common_keyup" >
                                              </td>
                                              <td>
                                                <input type="number" name="_rate[]" class="form-control _rate _common_keyup" >
                                                <input type="hidden" name="_base_rate[]" class="form-control _base_rate _common_keyup" >
                                              </td>
                                              <td class="<?php if($_show_sales_rate==0): ?> display_none <?php endif; ?>">
                                                <input type="number" name="_sales_rate[]" class="form-control _sales_rate " >
                                              </td>

                                               <td class="<?php if($_inline_discount ==0): ?> display_none <?php endif; ?> " >
                                                <input type="number" name="_discount[]" class="form-control  _discount _common_keyup" >
                                              </td>
                                              <td class="<?php if($_inline_discount ==0): ?> display_none <?php endif; ?>" >
                                                <input type="number" name="_discount_amount[]" class="form-control  _discount_amount" >
                                              </td>
                                             
                                              <td class="<?php if(isset($_show_vat)): ?> <?php if($_show_vat==0): ?> display_none  <?php endif; ?> <?php endif; ?> ">
                                                <input type="number" name="_vat[]" class="form-control  _vat _common_keyup" >
                                              </td>
                                              <td class="<?php if(isset($_show_vat)): ?> <?php if($_show_vat==0): ?> display_none  <?php endif; ?> <?php endif; ?> ">
                                                <input type="number" name="_vat_amount[]" class="form-control  _vat_amount" >
                                              </td>
                                              <td>
                                                <input type="number" name="_value[]" class="form-control _value "  >
                                              </td>
                                            
                                              <td class="<?php if(sizeof($store_houses)== 1): ?> display_none <?php endif; ?>">
                                                <select class="form-control  _main_store_id" name="_main_store_id[]">
                                                  <?php $__empty_1 = true; $__currentLoopData = $store_houses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $store): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                  <option value="<?php echo e($store->id); ?>"><?php echo e($store->_name ?? ''); ?></option>
                                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                  <?php endif; ?>
                                                </select>
                                                
                                              </td>
                                              
                                              
                                              <td class="<?php if(isset($_show_self)): ?> <?php if($_show_self==0): ?> display_none  <?php endif; ?> <?php endif; ?>">
                                                <input type="text" name="_store_salves_id[]" class="form-control _store_salves_id " >
                                              </td>
                                              <td class="<?php if(isset($form_settings->_show_manufacture_date)): ?> <?php if($form_settings->_show_manufacture_date==0): ?> display_none  <?php endif; ?> <?php endif; ?>">
                                                <input type="date" name="_manufacture_date[]" class="form-control _manufacture_date " >
                                              </td>
                                              <td class="<?php if(isset($form_settings->_show_expire_date)): ?> <?php if($form_settings->_show_expire_date==0): ?> display_none  <?php endif; ?> <?php endif; ?>">
                                                <input type="date" name="_expire_date[]" class="form-control _expire_date " >
                                              </td>
                                             
                                              
                                            </tr>
                                          </tbody>
                                          <tfoot>
                                            <tr>
                                              <td>
                                                <a href="#none"  class="btn btn-default btn-sm" onclick="purchase_row_add(event)"><i class="fa fa-plus"></i></a>
                                              </td>
                                              <td  class="text-right"><b>Total</b></td>
                                              <td class="display_none"></td>
                                              <td class="display_none"></td>
                                              <td class="<?php if($_show_unit==0): ?> display_none <?php endif; ?>"></td>
                                              
                                                <td  class="text-right <?php if($_show_barcode==0): ?> display_none <?php endif; ?>"></td>
                                             
                                              
                                                <td  class="text-right <?php if($_show_short_note==0): ?> display_none <?php endif; ?>"></td>
                                             
                                              <td class="<?php if($_show_expected_qty==0): ?> display_none <?php endif; ?>">
                                                <input type="number" step="any" min="0" name="_total_expected_qty_amount" class="form-control _total_expected_qty_amount" value="0" readonly required>
                                              </td>
                                              <td>
                                                <input type="number" step="any" min="0" name="_total_qty_amount" class="form-control _total_qty_amount" value="0" readonly required>
                                              </td>
                                              <td></td>
                                              <td class="<?php if($_show_sales_rate==0): ?> display_none <?php endif; ?>"></td>
                                              <td class="<?php if($_inline_discount==0): ?> display_none <?php endif; ?>"></td>
                                              <td class="<?php if($_inline_discount==0): ?> display_none <?php endif; ?>">
                                                <input type="number" step="any" min="0" name="_total_discount_amount" class="form-control _total_discount_amount" value="0" readonly required>
                                              </td>
                                             
                                              <td class="<?php if(isset($_show_vat)): ?> <?php if($_show_vat==0): ?> display_none   <?php endif; ?>  <?php endif; ?>"></td>
                                              <td class="<?php if(isset($_show_vat)): ?> <?php if($_show_vat==0): ?> display_none   <?php endif; ?>  <?php endif; ?>">
                                                <input type="number" step="any" min="0" name="_total_vat_amount" class="form-control _total_vat_amount" value="0" readonly required>
                                              </td>

                                              
                                            
                                              <td>
                                                <input type="number" step="any" min="0" name="_total_value_amount" class="form-control _total_value_amount" value="0" readonly required>
                                              </td>
                                              
                                               <td class="<?php if(sizeof($store_houses) == 1): ?> display_none <?php endif; ?>"></td>
                                              <td class="<?php if(isset($_show_self)): ?> <?php if($_show_self==0): ?> display_none  <?php endif; ?>  <?php endif; ?>"></td>
                                              <td class="<?php if(isset($form_settings->_show_manufacture_date)): ?> <?php if($form_settings->_show_manufacture_date==0): ?> display_none  <?php endif; ?>  <?php endif; ?>"></td>

                                              <td class="<?php if(isset($form_settings->_show_expire_date)): ?> <?php if($form_settings->_show_expire_date==0): ?> display_none  <?php endif; ?>  <?php endif; ?>"></td>
                                              
                                            </tr>
                                          </tfoot>
                                      </table>
                                </div>
                            </div>
                          </div>
                        </div>
                      <?php if($__user->_ac_type==1): ?>
                      <?php echo $__env->make('backend.purchase.create_ac_cb', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                         
                      <?php else: ?>
                       <?php echo $__env->make('backend.purchase.create_ac_detail', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
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
                                     <input type="hidden" name="_master_id" value="<?php echo e(url('purchase/print')); ?>/<?php echo e($_master_id); ?>" class="_master_id">
                                    
                                    <?php endif; ?>
                                   
                                       <input type="hidden" name="_print" value="0" class="_save_and_print_value">

                                    <input type="text" id="_note"  name="_note" class="form-control _note" value="<?php echo e(old('_note')); ?>" placeholder="Note" required >
                              </td>
                            </tr>
                            <tr>
                              <td style="width: 10%;border:0px;"><label for="_sub_total">Sub Total</label></td>
                              <td style="width: 70%;border:0px;">
                                <input type="text" name="_sub_total" class="form-control width_200_px" id="_purchase_sub_total" readonly value="0">
                              </td>
                            </tr>
                            <tr>
                              <td style="width: 10%;border:0px;"><label for="_discount_input">Invoice Discount</label></td>
                              <td style="width: 70%;border:0px;">
                                <input type="text" name="_discount_input" class="form-control width_200_px" id="_purchase_discount_input" value="0" >
                              </td>
                            </tr>
                            <tr>
                              <td style="width: 10%;border:0px;"><label for="_total_discount">Total Discount</label></td>
                              <td style="width: 70%;border:0px;">
                                <input type="text" name="_total_discount" class="form-control width_200_px" id="_purchase_total_discount" readonly value="0">
                              </td>
                            </tr>
                           
                            <tr class="<?php if($_show_vat==0): ?> display_none <?php endif; ?>">
                              <td style="width: 10%;border:0px;"><label for="_total_vat">Total VAT</label></td>
                              <td style="width: 70%;border:0px;">
                                <input type="text" name="_total_vat" class="form-control width_200_px" id="_purchase_total_vat" readonly value="0">
                              </td>
                            </tr>

                           
                            
                            <tr>
                              <td style="width: 10%;border:0px;"><label for="_total">NET Total </label></td>
                              <td style="width: 70%;border:0px;">
                          <input type="text" name="_total" class="form-control width_200_px" id="_purchase_total" readonly value="0">
                              </td>
                            </tr>
                             <?php echo $__env->make('backend.message.send_sms', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                          </table>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 bottom_save_section text-middle">

                          <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('duplicate-barcode-entry-check')): ?>
                            <button type="button" data-toggle="modal" data-target="#duplicateBarcodeModal" class="btn btn-info buttonCheckDuplicateBarcode "><i class="fa fa-credit-card mr-2" aria-hidden="true"></i> Check Duplicate Barcode</button>
                          <?php endif; ?>

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
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form action="<?php echo e(url('import-purchase-settings')); ?>" method="POST">
        <?php echo csrf_field(); ?>
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Purchase Form Settings</h5>
        <button type="button" class="close exampleModalClose"  aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body display_form_setting_info">
       
         
      
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary exampleModalClose" >Close</button>
        <button type="submit" class="btn btn-primary">Save changes</button>
      </div>
       </form>
    </div>
  </div>





</div>

<div class="modal fade" id="duplicateBarcodeModal" tabindex="-1" role="dialog" aria-labelledby="duplicateBarcodeModalList" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="duplicateBarcodeModalList">Duplicate Barcode List</h5>
        <button type="button" class="close duplicateBarcodeModalclose" >
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body duplicate_barcode_list">
        <h4>Please Wait...Checking Duplicate Barcode</h4>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary duplicateBarcodeModalclose" >Close</button>
      </div>
    </div>
  </div>
</div>
<?php echo $__env->make('backend.common-modal.item_ledger_modal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>

<script type="text/javascript">

  <?php if(empty($form_settings)): ?>
    $(document).find("#form_settings").click();
    setting_data_fetch();
  <?php endif; ?>
  var default_date_formate = `<?php echo e(default_date_formate()); ?>`;
  var _after_print = $(document).find("._after_print").val();
  var _master_id = $(document).find("._master_id").val();
  var _item_row_count =1;
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
$(document).on("click","#form_settings",function(){
         setting_data_fetch();
  })

  function setting_data_fetch(){
      var request = $.ajax({
            url: "<?php echo e(url('import-purchase-setting-modal')); ?>",
            method: "GET",
            dataType: "html"
          });
         request.done(function( result ) {
              $(document).find(".display_form_setting_info").html(result);
         })
  }

var duplicate_barcode_status=0;
  

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
      console.log(result.data)

      var search_html =``;
      var data = result.data; 
      if(data.length > 0 ){
            search_html +=`<div class="card"><table style="width: 500px;">
                            <tbody>`;
                        for (var i = 0; i < data.length; i++) {
                          var   _manufacture_company = data[i]?. _manufacture_company;
                          var _balance = data[i]?._balance
                         search_html += `<tr class="search_row_item" >
                                        <td>${data[i].id}
                                        <input type="hidden" name="_id_item" class="_id_item" value="${data[i].id}">
                                        </td><td>${data[i]._name}
                                        <input type="hidden" name="_name_item" class="_name_item" value="${data[i]._name}">
                                  <input type="hidden" name="_item_barcode" class="_item_barcode" value="${data[i]._barcode}">
                                  <input type="hidden" name="_item_rate" class="_item_rate" value="${data[i]?._pur_rate}">

                                  <input type="hidden" name="_item_sd" class="_item__sd" value="${data[i]?._sd}">
                                  <input type="hidden" name="_item_cd" class="_item__cd" value="${data[i]?._cd}">
                                  <input type="hidden" name="_item_ait" class="_item__ait" value="${data[i]?._ait}">
                                  <input type="hidden" name="_item_rd" class="_item__rd" value="${data[i]?._rd}">
                                  <input type="hidden" name="_item_at" class="_item__at" value="${data[i]?._at}">
                                  <input type="hidden" name="_item_tti" class="_item__tti" value="${data[i]?._tti}">

                                  <input type="hidden" name="_unique_barcode" class="_unique_barcode" value="${data[i]._unique_barcode}">
                                  <input type="hidden" name="_item_sales_rate" class="_item_sales_rate" value="${data[i]._sale_rate}">
                                  <input type="hidden" name="_item_vat" class="_item_vat" value="${data[i]._vat}">
                                   <input type="hidden" name="_main_unit_id" class="_main_unit_id" value="${data[i]._unit_id}">
                                  <input type="hidden" name="_main_unit_text" class="_main_unit_text" value="${data[i]._units?._name}">
                                   </td>
                                   <td>${_balance} ${data[i]._units?._name}</td>
                                   </tr>`;
                        }                         
            search_html += ` </tbody> </table></div>`;
      }else{
        search_html +=`<div class="card"><table style="width: 400px;"> 
        <thead><th colspan="4"><button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target="#exampleModalLong_item" title="Create New Item (Inventory) ">
                   <i class="nav-icon fas fa-plus"></i> New Item
                </button></th></thead><tbody></tbody></table></div>`;
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
  var _unique_barcode = parseFloat($(this).find('._unique_barcode').val());
  var _item_sd = parseFloat($(this).find('._item_sd').val());
  var _item_cd = parseFloat($(this).find('._item_cd').val());
  var _item_ait = parseFloat($(this).find('._item_ait').val());
  var _item_rd = parseFloat($(this).find('._item_rd').val());
  var _item_at = parseFloat($(this).find('._item_at').val());
  var _item_tti = parseFloat($(this).find('._item_tti').val());

  var _main_unit_id = $(this).children('td').find('._main_unit_id').val();
  var _main_unit_val = $(this).children('td').find('._main_unit_text').val();

 var _item_row_count = _ref_counter;
  if(_unique_barcode ==1){
    _new_barcode_function(_item_row_count);
  }
  
  if(isNaN(_item_vat)){ _item_vat=0 }
  _vat_amount = ((_item_rate*_item_vat)/100);
  if(isNaN(_item_sd)){ _item_sd=0 }
  _sd_amount = ((_item_rate*_item_sd)/100);
  if(isNaN(_item_cd)){ _item_cd=0 }
  _cd_amount = ((_item_rate*_item_cd)/100);
  if(isNaN(_item_ait)){ _item_ait=0 }
  _ait_amount = ((_item_rate*_item_ait)/100);
  if(isNaN(_item_rd)){ _item_rd=0 }
  _rd_amount = ((_item_rate*_item_rd)/100);
  if(isNaN(_item_at)){ _item_at=0 }
  _at_amount = ((_item_rate*_item_at)/100);
  if(isNaN(_item_tti)){ _item_tti=0 }
  _tti_amount = ((_item_rate*_item_tti)/100);



var self = $(this);

    var request = $.ajax({
      url: "<?php echo e(url('item-wise-units')); ?>",
      method: "GET",
      data: { item_id:_id },
       dataType: "html"
    });
     
    request.done(function( response ) {
      self.parent().parent().parent().parent().parent().parent().find('._transection_unit').html("")
      self.parent().parent().parent().parent().parent().parent().find("._transection_unit").html(response);
    });
     
    request.fail(function( jqXHR, textStatus ) {
      alert( "Request failed: " + textStatus );
    });
  

  $(this).parent().parent().parent().parent().parent().parent().find('._item_id').val(_id);
  var _id_name = `${_name} `;
  $(this).parent().parent().parent().parent().parent().parent().find('._search_item_id').val(_id_name);
  $(this).parent().parent().parent().parent().parent().parent().find('._barcode').val(_item_barcode);
  $(this).parent().parent().parent().parent().parent().parent().find('._rate').val(_item_rate);
  
  $(this).parent().parent().parent().parent().parent().parent().find('._sales_rate').val(_item_sales_rate);
  $(this).parent().parent().parent().parent().parent().parent().find('._vat').val(_item_vat);
  $(this).parent().parent().parent().parent().parent().parent().find('._vat_amount').val(_vat_amount);

  
  $(this).parent().parent().parent().parent().parent().parent().find('._qty').val(1);
  if(_unique_barcode ==1){
    $(this).parent().parent().parent().parent().parent().parent().find('._qty').val(0);
    $(this).parent().parent().parent().parent().parent().parent().find('._qty').attr('readonly',true);
  }
  $(this).parent().parent().parent().parent().parent().parent().find('._value').val(_item_rate);
 var _ref_counter = $(this).parent().parent().parent().parent().parent().parent().find('._ref_counter').val();
  $(this).parent().parent().parent().parent().parent().parent().find('._barcode').attr('name',_ref_counter+'__barcode__'+_id);
  var _item_row_count = _ref_counter;
  if(_unique_barcode ==1){
    _new_barcode_function(_item_row_count);
  }
  $(this).parent().parent().parent().parent().parent().parent().find('._base_rate').val(_item_rate);
  $(this).parent().parent().parent().parent().parent().parent().find('._base_unit_id').val(_main_unit_id);
  $(this).parent().parent().parent().parent().parent().parent().find('._main_unit_val').val(_main_unit_val);

  _purchase_total_calculation();
  $(document).find('.search_box_item').hide();
  $(document).find('.search_box_item').removeClass('search_box_show').hide();
  
})

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


$(document).on('click',function(){
    var searach_show= $(document).find('.search_box_item').hasClass('search_box_show');
    var search_box_main_ledger= $(document).find('.search_box_main_ledger').hasClass('search_box_show');
    var search_box_purchase_order= $(document).find('.search_box_purchase_order').hasClass('search_box_show');
    if(searach_show ==true){
      $(document).find('.search_box_item').removeClass('search_box_show').hide();
    }

    if(search_box_main_ledger ==true){
      $(document).find('.search_box_main_ledger').removeClass('search_box_show').hide();
    }

    if(search_box_purchase_order ==true){
      $(document).find('.search_box_purchase_order').removeClass('search_box_show').hide();
    }
})

$(document).on('keyup','._value',function(){

  var _vat_amount =0;
  var _value = parseFloat($(this).closest('tr').find('._value').val());
  var _qty = parseFloat($(this).closest('tr').find('._qty').val());
  if(isNaN(_value)){_value=1}
  if(isNaN(_qty)){_qty=1}
  var _rate = parseFloat(_value)/parseFloat(_qty);
  if(isNaN(_rate)){_rate=0}
  $(this).closest('tr').find('._rate').val(_rate);


  var _rate =parseFloat( $(this).closest('tr').find('._rate').val());
  var _sales_rate =parseFloat( $(this).closest('tr').find('._sales_rate').val());
  var _item_vat = parseFloat($(this).closest('tr').find('._vat').val());
  var _item_discount = parseFloat($(this).closest('tr').find('._discount').val());
 


   if(isNaN(_item_vat)){ _item_vat   = 0 }
   if(isNaN(_qty)){ _qty   = 0 }
   if(isNaN(_rate)){ _rate =0 }
   if(isNaN(_sales_rate)){ _sales_rate =0 }
   if(isNaN(_item_discount)){ _item_discount =0 }
   _vat_amount = Math.ceil(((_qty*_rate)*_item_vat)/100)
   _discount_amount = Math.ceil(((_qty*_rate)*_item_discount)/100)
   _value = parseFloat((_qty*_rate)).toFixed(2);

  $(this).closest('tr').find('._value').val(_value);
  $(this).closest('tr').find('._vat_amount').val(_vat_amount);
  $(this).closest('tr').find('._discount_amount').val(_discount_amount);
    _purchase_total_calculation();

})

$(document).on('keyup','._common_keyup',function(){
  var _vat_amount =0;
  var _qty = parseFloat($(this).closest('tr').find('._qty').val());
  var _rate =parseFloat( $(this).closest('tr').find('._rate').val());
  var _sales_rate =parseFloat( $(this).closest('tr').find('._sales_rate').val());
  var _item_vat = parseFloat($(this).closest('tr').find('._vat').val());
  var _item_discount = parseFloat($(this).closest('tr').find('._discount').val());
  var _sd = parseFloat($(this).closest('tr').find('._sd').val());
  var _cd = parseFloat($(this).closest('tr').find('._cd').val());
  var _ait = parseFloat($(this).closest('tr').find('._ait').val());
  var _rd = parseFloat($(this).closest('tr').find('._rd').val());
  var _at = parseFloat($(this).closest('tr').find('._at').val());
  var _tti = parseFloat($(this).closest('tr').find('._tti').val());







   if(isNaN(_item_vat)){ _item_vat   = 0 }
   if(isNaN(_qty)){ _qty   = 0 }
   if(isNaN(_rate)){ _rate =0 }
   if(isNaN(_sales_rate)){ _sales_rate =0 }
   if(isNaN(_item_discount)){ _item_discount =0 }
   _vat_amount = Math.ceil(((_qty*_rate)*_item_vat)/100)
   _discount_amount = Math.ceil(((_qty*_rate)*_item_discount)/100)

  $(this).closest('tr').find('._value').val((_qty*_rate));
  $(this).closest('tr').find('._vat_amount').val(_vat_amount);
  $(this).closest('tr').find('._discount_amount').val(_discount_amount);
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

$(document).on('keyup','._discount_amount',function(){
 var _item_vat =0;
  var _qty = $(this).closest('tr').find('._qty').val();
  var _rate = $(this).closest('tr').find('._rate').val();
  var _sales_rate = $(this).closest('tr').find('._sales_rate').val();
  var _discount_amount =  $(this).closest('tr').find('._discount_amount').val();
  
   if(isNaN(_discount_amount)){ _discount_amount = 0 }
   if(isNaN(_qty)){ _qty   = 0 }
   if(isNaN(_rate)){ _rate =0 }
   if(isNaN(_sales_rate)){ _sales_rate =0 }
   var _discount = parseFloat((_discount_amount/(_rate*_qty))*100).toFixed(2);
    $(this).closest('tr').find('._discount').val(_discount);

    $(this).closest('tr').find('._value').val((_qty*_rate));
 
    _purchase_total_calculation();
})

$(document).on("change","#_purchase_discount_input",function(){
  var _discount_input = $(this).val();
  var res = _discount_input.match(/%/gi);
  if(res){
     res = _discount_input.split("%");
    res= parseFloat(res);
    on_invoice_discount = ($(document).find("#_purchase_sub_total").val()*res)/100
    $(document).find("#_purchase_discount_input").val(on_invoice_discount)

  }else{
    on_invoice_discount = _discount_input;
  }

   $(document).find("#_purchase_total_discount").val(on_invoice_discount);
    _purchase_total_calculation()
})

$(document).on('keyup','._search_order_ref_id',delay(function(e){
    $(document).find('._search_order_ref_id').removeClass('required_border');
    var _gloabal_this = $(this);
    var _text_val = $(this).val().trim();
    var _branch_id = $(document).find('._master_branch_id').val();

  var request = $.ajax({
      url: "<?php echo e(url('purchase-pre-order-search')); ?>",
      method: "GET",
      data: { _text_val,_branch_id },
      dataType: "JSON"
    });
    request.done(function( result ) {

      var search_html =``;
      var data = result.data; 
       console.log(data)
      if(data.length > 0 ){
            search_html +=`<div class="card"><table table-bordered style="width: 100%;">
                            <thead>
                              <th style="border:1px solid #ccc;text-align:center;">ID</th>
                              <th style="border:1px solid #ccc;text-align:center;">PO NO</th>
                              <th style="border:1px solid #ccc;text-align:center;">Supplier</th>
                              <th style="border:1px solid #ccc;text-align:center;">Date</th>
                            </thead>
                            <tbody>`;
                        for (var i = 0; i < data.length; i++) {
                         search_html += `<tr class="search_row_purchase_order" >
                                        <td style="border:1px solid #ccc;">${data[i].id}
                                        <input type="hidden" name="_id_main_ledger" class="_id_main_ledger" value="${data[i]._ledger_id}">
                                        <input type="hidden" name="_purchase_main_id" class="_purchase_main_id" value="${data[i].id}">
                                        <input type="hidden" name="_purchase_order_number" class="_purchase_order_number" value="${data[i]._order_number}">
                                        <input type="hidden" name="_purchase_main_date" class="_purchase_main_date" value="${after_request_date__today(data[i]._date)}">
                                        </td>
                                        <td>${data[i]?._order_number}</td>
                                        <td style="border:1px solid #ccc;">${data[i]._ledger._name}
                                        <input type="hidden" name="_name_main_ledger" class="_name_main_ledger" value="${data[i]._ledger._name}">
                                        <input type="hidden" name="_address_main_ledger" class="_address_main_ledger" value="${data[i]._address}">
                                        <input type="hidden" name="_phone_main_ledger" class="_phone_main_ledger" value="${data[i]._phone}">
                                        
                                        <input type="hidden" name="_date_main_ledger" class="_date_main_ledger" value="${after_request_date__today(data[i]._date)}">

                                   </td>
                                   <td style="border:1px solid #ccc;">${after_request_date__today(data[i]._date)}
                                   </td></tr>`;
                        }                         
            search_html += ` </tbody> </table></div>`;
      }else{
        search_html +=`<div class="card"><table style="width: 400px;"> 
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
    var _purchase_order_number = $(this).find('._purchase_order_number').val();
    var _purchase_main_date = $(this).find('._purchase_main_date').val();
    var _main_branch_id = $(this).find('._main_branch_id').val();
    var _date_main_ledger = $(this).find('._date_main_ledger').val();
    var _address_main_ledger = $(this).find('._address_main_ledger').val();


    var _phone_main_ledger = $(this).find('._phone_main_ledger').val();
   
    if(_address_main_ledger =='null' ){ _address_main_ledger =""; } 
    if(_phone_main_ledger =='null' ){ _phone_main_ledger =""; } 

    $(document).find("._main_ledger_id").val(_id);
    $(document).find("._search_main_ledger_id").val(_name);
    $(document).find("._order_ref_id").val(_purchase_main_id);
    $(document).find("._phone").val(_phone_main_ledger);
    $(document).find("._address").val(_address_main_ledger);



    $(document).find("._search_order_ref_id").val(_purchase_order_number);

    $.ajaxSetup({ headers: {'X-CSRF-TOKEN': $(document).find('meta[name="csrf-token"]').attr('content') } });

    var request = $.ajax({
      url: "<?php echo e(url('purchase-pre-order-details')); ?>",
      method: "POST",
      data: { _purchase_main_id,_main_branch_id },
      dataType: "JSON"
    });
    request.done(function( result ) {
     
      var data = result;
      console.log(data)
      var _purchase_row_single = ``;
      $(document).find("#area__purchase_details").empty();
     
if(data.length > 0 ){
  $(document).find('._purchase_row').remove();
  $(document).find("._item_row_count").val(0)
  for (var i = 0; i < data.length; i++) {
   var _item_row_count = (parseFloat(i)+1);
    var _item_name = (data[i]._items._name) ? data[i]._items._name : '' ;
    var _unique_barcode = (data[i]._items._unique_barcode) ? data[i]._items._unique_barcode : '' ;
    var _item_id = (data[i]._item_id) ? data[i]._item_id : '' ;
    var _qty   = (data[i]._qty  ) ? data[i]._qty   : 0 ;
    var _rate    = (data[i]._rate) ? data[i]._rate    : 0 ;
    var _sales_rate =  0 ;
    var _value = ( (data[i]._qty*data[i]._rate) ) ? (data[i]._qty*data[i]._rate) : 0 ;
    var _main_unit_id= data[i]._transection_unit;
    var _unit_name = data[i]._items._units._name;

    $(document).find("._item_row_count").val(_item_row_count)
   

      $(document).find("#area__purchase_details").append(`<tr class="_purchase_row">
                                              <td>
                                                <a  href="#none" class="btn btn-default _purchase_row_remove" ><i class="fa fa-trash"></i></a>
                                              </td>
                                              <td>
                                                <input type="text" name="_search_item_id[]" class="form-control _search_item_id width_280_px" placeholder="Item" value="${_item_name}">
                                                <input type="hidden" name="_item_id[]" class="form-control _item_id width_200_px" value="${_item_id}">
                                                <div class="search_box_item">
                                                  
                                                </div>

                                              </td>
                                              <td class="display_none">
                                                <input type="hidden" class="form-control _base_unit_id width_100_px" name="_base_unit_id[]" />
                                                <input type="text" class="form-control _main_unit_val width_100_px" readonly name="_main_unit_val[]" />
                                              </td>
                                              <td class="display_none">
                                                <input type="number" name="conversion_qty[]" min="0" step="any" class="form-control conversion_qty " value="1" readonly>
                                              </td>
                                              <td class="<?php if($_show_unit==0): ?> display_none <?php endif; ?>">
                                                <select class="form-control _transection_unit" name="_transection_unit[]">
                                                <option value="${_main_unit_id}">${_unit_name}</option>
                                                </select>
                                              </td>
                                              
                                              <td class="<?php if($_show_barcode==0): ?> display_none <?php endif; ?>">
                                                <input type="text" name="${_item_row_count}_barcode__${_item_id}" class="form-control _barcode ${_item_row_count}__barcode " id="${_item_row_count}__barcode"  >
                                              </td>
                                             
                                              <td class="<?php if($_show_expected_qty==0): ?> display_none <?php endif; ?>">
                                                <input type="number" name="_expected_qty[]" class="form-control _expected_qty _common_keyup" fdprocessedid="io4gi">
                                              </td>

                                              <td>
                                                <input type="number" name="_qty[]" class="form-control _qty _common_keyup" value="${_qty}">
                                                <input type="hidden" name="_ref_counter[]" value="${_item_row_count}" class="_ref_counter" id="${_item_row_count}__ref_counter">
                                              </td>
                                              <td>
                                                <input type="number" name="_rate[]" class="form-control _rate _common_keyup" value="${_rate}">
                                                <input type="hidden" name="_base_rate[]" class="form-control _base_rate _common_keyup" value="${_rate}" >
                                              </td>
                                              <td class="<?php if($_show_sales_rate==0): ?> display_none <?php endif; ?>">
                                                <input type="number" name="_sales_rate[]" class="form-control _sales_rate " value="${_sales_rate}">
                                              </td>
                                              <td class="<?php if($_inline_discount==0): ?> display_none <?php endif; ?>">
                                                <input type="number" name="_discount[]" class="form-control  _discount _discount__${_item_row_count} _common_keyup" value="0" >
                                              </td>
                                              <td class="<?php if($_inline_discount==0): ?> display_none <?php endif; ?>">
                                                <input type="number" name="_discount_amount[]" class="form-control  _discount_amount _discount_amount__${_item_row_count}" value="0" >
                                              </td>
                                               
                                              
                                              <td class=" <?php if($_show_vat==0): ?> display_none <?php endif; ?>">
                                                <input type="number" name="_vat[]" class="form-control  _vat _common_keyup" >
                                              </td>
                                              <td class=" <?php if($_show_vat==0): ?> display_none <?php endif; ?>">
                                                <input type="number" name="_vat_amount[]" class="form-control  _vat_amount" >
                                              </td>
                                              
                                              
                                              <td>
                                                <input type="number" name="_value[]" class="form-control _value "  value="${_value}">
                                              </td>
                                             
                                              <td class="<?php if(sizeof($store_houses) == 1): ?> display_none <?php endif; ?>">
                                                <select class="form-control  _main_store_id" name="_main_store_id[]">
                                                  <?php $__empty_1 = true; $__currentLoopData = $store_houses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $store): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                  <option value="<?php echo e($store->id); ?>"><?php echo e($store->_name ?? ''); ?></option>
                                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                  <?php endif; ?>
                                                </select>
                                                
                                              </td>
                                              
                                             
                                              <td class="<?php if($_show_self==0): ?> display_none <?php endif; ?>">
                                                <input type="text" name="_store_salves_id[]" class="form-control _store_salves_id " >
                                              </td>
                                              
                                               <td class="<?php if(isset($form_settings->_show_manufacture_date)): ?> <?php if($form_settings->_show_manufacture_date==0): ?> display_none  <?php endif; ?> <?php endif; ?>">
                                                <input type="date" name="_manufacture_date[]" class="form-control _manufacture_date " >
                                              </td>
                                              <td class="<?php if(isset($form_settings->_show_expire_date)): ?> <?php if($form_settings->_show_expire_date==0): ?> display_none  <?php endif; ?> <?php endif; ?>">
                                                <input type="date" name="_expire_date[]" class="form-control _expire_date " >
                                              </td>
                                              
                                            </tr>`);
if(_unique_barcode ==1){
  _new_barcode_function(_item_row_count);
}
                                           
                                            
                                          }

                                        }else{
                                          _purchase_row_single += `Returnable Item Not Found !`;
                                        }

            
            
              _purchase_total_calculation();
    })



  })





 function _purchase_total_calculation(){
  console.log('calculation here')
    var _total_qty = 0;
    var _total__value = 0;
    var _total__vat =0;
    var _total_discount_amount = 0;
    
      $(document).find("._value").each(function() {
            var _s_value =parseFloat($(this).val());
            if(isNaN(_s_value)){_s_value = 0}
          _total__value +=parseFloat(_s_value);
      });
      $(document).find("._qty").each(function() {
            var _s_qty =parseFloat($(this).val());
            if(isNaN(_s_qty)){_s_qty = 0}
          _total_qty +=parseFloat(_s_qty);
      });
      $(document).find("._vat_amount").each(function() {
            var _s_vat =parseFloat($(this).val());
            if(isNaN(_s_vat)){_s_vat = 0}
          _total__vat +=parseFloat(_s_vat);
      });
      $(document).find("._discount_amount").each(function() {
            var _s_discount_amount =parseFloat($(this).val());
            if(isNaN(_s_discount_amount)){_s_discount_amount = 0}
          _total_discount_amount +=parseFloat(_s_discount_amount);
      });

      var _total__expected_qty = 0;
      $(document).find("._expected_qty").each(function() {
            var _expected_qty =parseFloat($(this).val());
            if(isNaN(_expected_qty)){_expected_qty = 0}
          _total__expected_qty +=parseFloat(_expected_qty);
      });
      $(document).find("._total_expected_qty_amount").val(_total__expected_qty);

      
      $(document).find("._total_qty_amount").val(_total_qty);
      $(document).find("._total_value_amount").val(_total__value);
      $(document).find("._total_vat_amount").val(_total__vat);
      $(document).find("._total_discount_amount").val(_total_discount_amount);

      var _discount_input = parseFloat($(document).find("#_purchase_discount_input").val());
      if(isNaN(_discount_input)){ _discount_input =0 }
      var _total_discount = parseFloat(_discount_input)+parseFloat(_total_discount_amount);
      $(document).find("#_purchase_sub_total").val(_math_round(_total__value));
      $(document).find("#_purchase_total_vat").val(_total__vat);
      $(document).find("#_purchase_total_discount").val(parseFloat(_discount_input)+parseFloat(_total_discount_amount));
      var _total = _math_round((parseFloat(_total__value)+parseFloat(_total__vat))-parseFloat(_total_discount));
      $(document).find("#_purchase_total").val(_total);
  }


 var single_row =  `<tr class="_voucher_row">
                      <td><a  href="" class="btn btn-sm btn-default _voucher_row_remove" ><i class="fa fa-trash"></i></a></td>
                      <td><input type="text" name="_search_ledger_id[]" class="form-control _search_ledger_id width_280_px" placeholder="Ledger" <?php if($__user->_ac_type==1): ?> attr_account_head_no="1" <?php endif; ?> >
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
                            <td class=" <?php if($__user->_ac_type==1): ?> display_none <?php endif; ?> ">
                              <input type="number" name="_dr_amount[]" class="form-control  _dr_amount" placeholder="Dr. Amount" value="<?php echo e(old('_dr_amount',0)); ?>">
                            </td>
                            <td>
                              <input type="number" name="_cr_amount[]" class="form-control  _cr_amount" placeholder="Cr. Amount" value="<?php echo e(old('_cr_amount',0)); ?>">
                              </td>
                            </tr>`;

  function voucher_row_add(event) {
      event.preventDefault();
      $(document).find("#area__voucher_details").append(single_row);

      change_branch_cost_strore();
  }


function purchase_row_add(event){
   event.preventDefault();
      

       _item_row_count= $(document).find("._item_row_count").val();
      $(document).find("._item_row_count").val((parseFloat(_item_row_count)+1));
     var  _item_row_count = (parseFloat(_item_row_count)+1);
     $(document).find("#area__purchase_details").append(`<tr class="_purchase_row">
                                              <td>
                                                <a  href="#none" class="btn btn-default _purchase_row_remove" ><i class="fa fa-trash"></i></a>
                                              </td>
                                              <td>
                                                <input type="text" name="_search_item_id[]" class="form-control _search_item_id width_280_px" placeholder="Item">
                                                <input type="hidden" name="_item_id[]" class="form-control _item_id width_200_px" >
                                                <div class="search_box_item">
                                                  
                                                </div>
                                              </td>
                                              <td class="display_none">
                                                <input type="hidden" class="form-control _base_unit_id width_100_px" name="_base_unit_id[]" />
                                                <input type="text" class="form-control _main_unit_val width_100_px" readonly name="_main_unit_val[]" />
                                              </td>
                                              <td class="display_none">
                                                <input type="number" name="conversion_qty[]" min="0" step="any" class="form-control conversion_qty " value="1" readonly>
                                              </td>
                                              <td class="<?php if($_show_unit==0): ?> display_none <?php endif; ?>">
                                                <select class="form-control _transection_unit" name="_transection_unit[]">
                                                </select>
                                              </td>
                                              
                                              <td class="<?php if($_show_barcode==0): ?>display_none <?php endif; ?>">
                                                <input type="text" name="_barcode[]" class="form-control _barcode  ${_item_row_count}__barcode " id="${_item_row_count}__barcode"  >
                                              </td>
                                              
                                            
                                              <td class="<?php if($_show_short_note==0): ?> display_none <?php endif; ?>">
                                                <input type="text" name="_short_note[]" class="form-control _short_note  ${_item_row_count}__short_note " id="${_item_row_count}__short_note"  >
                                              </td>
                                              
                                               <td class="<?php if($_show_expected_qty==0): ?> display_none <?php endif; ?>">
                                                <input type="number" name="_expected_qty[]" class="form-control _expected_qty _common_keyup" >
                                              </td>
                                              <td>
                                                <input type="number" name="_qty[]" class="form-control _qty _common_keyup" >
                                                 <input type="hidden" name="_ref_counter[]" value="${_item_row_count}" class="_ref_counter" id="${_item_row_count}__ref_counter">
                                              </td>
                                              <td>
                                                <input type="number" name="_rate[]" class="form-control _rate _common_keyup" >
                                                <input type="hidden" name="_base_rate[]" class="form-control _base_rate _common_keyup"  >
                                              </td>
                                              <td class="<?php if($_show_sales_rate==0): ?> display_none <?php endif; ?>">
                                                <input type="number" name="_sales_rate[]" class="form-control _sales_rate " >
                                              </td>
                                              <td class="<?php if($_inline_discount==0): ?> display_none <?php endif; ?>">
                                                <input type="number" name="_discount[]" class="form-control  _discount _discount__0 _common_keyup" value="" >
                                              </td>
                                              <td class="<?php if($_inline_discount==0): ?> display_none <?php endif; ?>">
                                                <input type="number" name="_discount_amount[]" class="form-control  _discount_amount _discount_amount__0" value="" >
                                              </td>

                                               <td class=" <?php if($_show_vat==0): ?> display_none <?php endif; ?>">
                                                <input type="number" name="_vat[]" class="form-control  _vat _common_keyup" >
                                              </td>
                                              <td class=" <?php if($_show_vat==0): ?> display_none <?php endif; ?>">
                                                <input type="number" name="_vat_amount[]" class="form-control  _vat_amount" >
                                              </td>
                                              
                                              
                                              <td>
                                                <input type="number" name="_value[]" class="form-control _value "  >
                                              </td>
                                              
                                             
                                              <td class="<?php if(sizeof($store_houses) == 1): ?> display_none <?php endif; ?>">
                                                <select class="form-control  _main_store_id" name="_main_store_id[]">
                                                  <?php $__empty_1 = true; $__currentLoopData = $store_houses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $store): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                  <option value="<?php echo e($store->id); ?>"><?php echo e($store->_name ?? ''); ?></option>
                                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                  <?php endif; ?>
                                                </select>
                                                
                                              </td>
                                              
                                             
                                              <td class="<?php if($_show_self==0): ?> display_none <?php endif; ?>">
                                                <input type="text" name="_store_salves_id[]" class="form-control _store_salves_id " >
                                              </td>
                                              
                                               <td class="<?php if(isset($form_settings->_show_manufacture_date)): ?> <?php if($form_settings->_show_manufacture_date==0): ?> display_none  <?php endif; ?> <?php endif; ?>">
                                                <input type="date" name="_manufacture_date[]" class="form-control _manufacture_date " >
                                              </td>
                                              <td class="<?php if(isset($form_settings->_show_expire_date)): ?> <?php if($form_settings->_show_expire_date==0): ?> display_none  <?php endif; ?> <?php endif; ?>">
                                                <input type="date" name="_expire_date[]" class="form-control _expire_date " >
                                              </td>
                                              
                                            </tr>`);

change_branch_cost_strore();



     
      

}
 $(document).on('click','._purchase_row_remove',function(event){
      event.preventDefault();
      var ledger_id = $(this).parent().parent('tr').find('._item_id').val();
      if(ledger_id ==""){
          $(this).parent().parent('tr').remove();
        
        $(this).parent().parent('tr').find('._ref_counter').remove();
      }else{
        if(confirm('Are you sure your want to delete?')){
          $(this).parent().parent('tr').remove();
           $(this).parent().parent('tr').find('._ref_counter').remove();
        } 
      }
      _purchase_total_calculation();
  })

 var _purchase_row_single_new =``;

 
 $(document).on("click",".buttonCheckDuplicateBarcode",function(){
    duplicateBarcodeEntryCheck();
 })

 function duplicateBarcodeEntryCheck(){

    //$(document).find("#duplicateBarcodeModal").show()
    duplicate_barcode_status =0;
  var all_barcodes = $(document).find('.amsify-select-tag').map(function() {
              return $(this).data('val');
        }).get();
  

      if(all_barcodes?.length > 0){
            var request = $.ajax({
            url: "<?php echo e(url('item-purchase-barcode-check')); ?>",
            method: "GET",
            data: { all_barcodes : all_barcodes },
            dataType: "JSON",
           async:false

          });
           
          request.done(function( result ) {
            var barcode_data = result?.barcode_data;
            var barcode_message= result?.message;
            if(barcode_data?.length > 0){
              $(document).find('#duplicateBarcodeModal').modal('show');
              duplicate_barcode_status =1;
            
              var table_data =`<table class="table table-bordered" style="width:100%">`;
              for (var i = 0; i < barcode_data.length; i++) {
                table_data +=`<tr>
                <td>${barcode_data[i]?._barcode}</td>
                <td><button class="btn btn-sm btn-danger remove_duplicate_barcode" attr_duplicate_barcode="${barcode_data[i]?._barcode}" >X</button></td></tr>`;
               
              }
              table_data +=`</table>`;
              $(document).find(".duplicate_barcode_list").html(table_data);
              
            }
          })

         
          
      }

       return duplicate_barcode_status;

 } 

$(document).on('click',".remove_duplicate_barcode",function(){
  $(this).closest('tr').remove();
  duplicate_barcode = $(this).attr("attr_duplicate_barcode");
   $(document).find('[data-val='+duplicate_barcode+']').find(".amsify-remove-tag").click();
   $(document).find("body").click();
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

//Empty or Zero Qty Check
    var empty_or_zero_qty=[];
    $(document).find("._qty").each(function(){
      var zero_qty = parseFloat($(this).val());
      if(isNaN(zero_qty) || zero_qty==0 ){
         empty_or_zero_qty.push(1);
      }
    })

    if(empty_or_zero_qty.length > 0){
      alert("Please input QTY");
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
      var check_duplicate_unique_barcode = duplicateBarcodeEntryCheck();
      console.log(check_duplicate_unique_barcode)
      if(check_duplicate_unique_barcode==1){
        return false;
      }

      $(document).find('.purchase_form').submit();
    }
  })

//_new_barcode_function(_item_row_count);
  function _new_barcode_function(_item_row_count){
      $(document).find('#'+_item_row_count+'__barcode').amsifySuggestags({
      trimValue: true,
      dashspaces: true,
      showPlusAfter: 1,
      });
  }


 

$(document).find(".datetimepicker-input").val(date__today())

          function date__today(){
              var d = new Date();
            var yyyy = d.getFullYear().toString();
            var mm = (d.getMonth()+1).toString(); // getMonth() is zero-based
            var dd  = d.getDate().toString();
            if(default_date_formate=='DD-MM-YYYY'){
              return (dd[1]?dd:"0"+dd[0]) +"-"+ (mm[1]?mm:"0"+mm[0])+"-"+ yyyy ;
            }
            if(default_date_formate=='MM-DD-YYYY'){
              return (mm[1]?mm:"0"+mm[0])+"-" + (dd[1]?dd:"0"+dd[0]) +"-"+  yyyy ;
            }
            

            
          }

</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\own\inv-acc-hrm\resources\views/backend/import-purchase/create.blade.php ENDPATH**/ ?>