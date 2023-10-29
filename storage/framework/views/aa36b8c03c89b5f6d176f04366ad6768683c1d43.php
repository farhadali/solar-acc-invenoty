
<?php $__env->startSection('title',$page_name); ?>

<?php $__env->startSection('content'); ?>
<?php
$__user= Auth::user();
?>
<?php $__env->startSection('css'); ?>
<link rel="stylesheet" href="<?php echo e(asset('backend/new_style.css')); ?>">
<?php $__env->stopSection(); ?>
<div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class=" col-sm-6 ">
             <a class="m-0 _page_name" href="<?php echo e(route('material-issue-return.index')); ?>"><?php echo $page_name ?? ''; ?> </a>
          </div><!-- /.col -->
          
          <div class=" col-sm-6 ">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item ">
                 <a target="__blank" href="<?php echo e(url('material-issue-return/print')); ?>/<?php echo e($data->id); ?>" class="btn btn-sm btn-warning"> <i class="nav-icon fas fa-print"></i> </a>
                  
                
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
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('material-issue-return-settings')): ?>
             <li class="breadcrumb-item ">
                 <button type="button" id="form_settings" class="btn btn-sm btn-default" data-toggle="modal" data-target="#exampleModal">
                   <i class="nav-icon fas fa-cog"></i> 
                </button>
               </li>
              <?php endif; ?>
              <li class="breadcrumb-item ">
                 <a class="btn btn-sm btn-success" title="List" href="<?php echo e(route('material-issue-return.index')); ?>"> <i class="nav-icon fas fa-list"></i> </a>
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
                    <div class="alert _required ">
                      <span class="_over_qty"></span> 
                    </div>

                    
              </div>
    <?php
    $_show_delivery_man = $form_settings->_show_delivery_man ?? 0;
    $_show_sales_man = $form_settings->_show_sales_man ?? 0;
    $_show_barcode = $form_settings->_show_barcode ?? 0;
    $_show_cost_rate =  $form_settings->_show_cost_rate ?? 0;
    $_show_payment_terms =  $form_settings->_show_payment_terms ?? 0;
    $_show_vat =  $form_settings->_show_vat ?? 0;
    $_inline_discount = $form_settings->_inline_discount ?? 0;
    $_show_self = $form_settings->_show_self ?? 0;
    $_show_warranty = $form_settings->_show_warranty ?? 0;
    $_defaut_customer = $form_settings->_defaut_customer ?? 0;
    $_show_unit = $form_settings->_show_unit ?? 0;
    $_show_manufacture_date = $form_settings->_show_manufacture_date ?? 0;
    $_show_expire_date = $form_settings->_show_expire_date ?? 0;

    $_show_branch = $form_settings->_show_branch ?? 0;
    $_show_cost_center = $form_settings->_show_cost_center ?? 0;
    $_show_store = $form_settings->_show_store ?? 0;

    ?>
              <div class="card-body">
              

                <?php echo Form::model($data, ['method' => 'PATCH','class'=>'purchase_form','route' => ['material-issue-return.update', $data->id]]); ?>

                <?php echo csrf_field(); ?>
                    <div class="row">
                       <div class="col-xs-12 col-sm-12 col-md-2">
                        <input type="hidden" name="_form_name" class="_form_name" value="sales_return">
                         <input type="hidden" name="_sales_return_id" class="_sales_return_id" value="<?php echo e($data->id); ?>">
                            <div class="form-group">
                                <label><?php echo e(__('label._date')); ?>:</label>
                                  <div class="input-group date" id="reservationdate" data-target-input="nearest">
                                      <input type="text" name="_date" class="form-control datetimepicker-input" data-target="#reservationdate" value="<?php echo e(_view_date_formate($data->_date)); ?>" />
                                      <div class="input-group-append" data-target="#reservationdate" data-toggle="datetimepicker">
                                          <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                      </div>
                                  </div>
                              </div>
                             <input type="hidden" id="_search_form_value" name="_search_form_value" class="_search_form_value" value="2" >
                        </div>

                        
                        <div class="col-xs-12 col-sm-12 col-md-2 ">
                            <div class="form-group">
                              <label class="mr-2" for="_order_number"><?php echo e(__('label.issue_return_no')); ?>:</label>
                              <input type="text" id="_order_number" name="_order_number" class="form-control _order_number" value="<?php echo e(old('_order_number',$data->_order_number)); ?>" placeholder="Order Number" readonly  >
                            </div>
                        </div>
                        
                        <div class="col-xs-12 col-sm-12 col-md-2 ">
                            <div class="form-group">
                              <label class="mr-2" for="_order_ref_id"><?php echo e(__('label.issue_number')); ?>:<span class="_required">*</span></label>
                              <input readonly type="text" id="_search_order_ref_id" name="_search_order_ref_id" class="form-control _search_order_ref_id" value="<?php echo e(old('_order_ref_id',$data->_issue_master->_order_number)); ?>" >
                              <input type="hidden" id="_order_ref_id" name="_order_ref_id" class="form-control _order_ref_id" value="<?php echo e(old('_order_ref_id',$data->_order_ref_id)); ?>" placeholder="Sales Order" >
                              <div class="search_box_purchase_order"></div>
                                
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-2 ">
                         <div class="form-group ">
                             <label><?php echo __('label.organization'); ?>:<span class="_required">*</span></label>
                            <select class="form-control _master_organization_id" name="organization_id" required >

                               <option value="" disabled><?php echo e(__('label.select_organization')); ?></option>
                               <?php $__empty_1 = true; $__currentLoopData = $permited_organizations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                               <option value="<?php echo e($val->id); ?>" disabled <?php if($val->id==$data->organization_id): ?> selected <?php endif; ?>><?php echo e($val->id ?? ''); ?> - <?php echo e($val->_name ?? ''); ?></option>
                               <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                               <?php endif; ?>
                             </select>
                         </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-2 ">
                         <div class="form-group ">
                             <label><?php echo e(__('label._branch_id')); ?>:<span class="_required">*</span></label>
                            <select class="form-control _master_branch_id" name="_branch_id" required >
                               <option value="" disabled><?php echo e(__('label.select_branch')); ?></option>
                               <?php $__empty_1 = true; $__currentLoopData = $permited_branch; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $branch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                               <option value="<?php echo e($branch->id); ?>" disabled <?php if($data->_branch_id ==$branch->id): ?> selected <?php endif; ?> ><?php echo e($branch->id ?? ''); ?> - <?php echo e($branch->_name ?? ''); ?></option>
                               <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                               <?php endif; ?>
                             </select>
                             <input type="hidden" name="_branch_id" class="_assign_branch_id" value="<?php echo e($data->_branch_id); ?>">
                             <input type="hidden" name="_cost_center_id" class="_assign_cost_center_id" value="<?php echo e($data->_cost_center_id); ?>">
                             <input type="hidden" name="organization_id" class="_assign_organization_id" value="<?php echo e($data->organization_id); ?>">
                         </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-2 ">
                         <div class="form-group ">
                             <label><?php echo e(__('label.Cost center')); ?>:<span class="_required">*</span></label>
                            <select class="form-control _cost_center_id" name="_cost_center_id" required >
                               <option value="" disabled><?php echo e(__('label.select_cost_center')); ?></option>
                               <?php $__empty_1 = true; $__currentLoopData = $permited_costcenters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cost_center): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                               <option value="<?php echo e($cost_center->id); ?>" disabled <?php if($cost_center->id==$data->_cost_center_id): ?> selected <?php endif; ?>><?php echo e($cost_center->_code ?? ''); ?> - <?php echo e($cost_center->_name ?? ''); ?></option>
                               <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                               <?php endif; ?>
                             </select>
                         </div>
                        </div>
                        
                        <?php if($_show_delivery_man ==1): ?>
                        <div class="col-xs-12 col-sm-12 col-md-2 ">
                            <div class="form-group">
                              <label class="mr-2" for="_delivery_man">Delivery Man:</label>
                              <input type="text" id="_search_main_delivery_man" name="_search_main_delivery_man" class="form-control _search_main_delivery_man" 
                               placeholder="Delivery Man" value="<?php echo e(($data->_delivery_man_id ==0) ? '' : $data->_delivery_man->_name); ?>" readonly >

                            <input type="hidden" id="_delivery_man" name="_delivery_man_id" class="form-control _delivery_man"  placeholder="Delivery Man" value="<?php echo e($data->_delivery_man_id); ?>" >
                            <div class="search_box_delivery_man"> </div>
                            </div>
                        </div>
                        <?php endif; ?>
                         <?php if($_show_sales_man ==1): ?>
                        <div class="col-xs-12 col-sm-12 col-md-2 ">
                            <div class="form-group">
                              <label class="mr-2" for="_sales_man">Sales Man:</label>
                              <input type="text" id="_search_main_sales_man" name="_search_main_sales_man" class="form-control _search_main_sales_man"  placeholder="Sales Man" readonly value="<?php echo e(($data->_sales_man_id ==0) ? '' : $data->_sales_man->_name); ?>"  >

                            <input type="hidden" id="_sales_man" name="_sales_man_id" class="form-control _sales_man"  placeholder="Sales Man" value="<?php echo e($data->_sales_man_id); ?>">
                            <div class="search_box_sales_man"> </div>
                            </div>
                        </div>
                        <?php endif; ?>
                       
              </div>
            <div class="row">
                         <div class="col-xs-12 col-sm-12 col-md-3 ">
                            <div class="form-group">
                              <label class="mr-2" for="_main_ledger_id"><?php echo e(__('label._ledger_id')); ?>:<span class="_required">*</span></label>
                            <input type="text" id="_search_main_ledger_id" name="_search_main_ledger_id" class="form-control _search_main_ledger_id" value="<?php echo e(old('_search_main_ledger_id',$data->_ledger->_name)); ?>" placeholder="Customer" required readonly>

                            <input type="hidden" id="_main_ledger_id" name="_main_ledger_id" class="form-control _main_ledger_id" value="<?php echo e(old('_main_ledger_id',$data->_ledger_id)); ?>" placeholder="Customer" required>
                            <div class="search_box_main_ledger"> </div>

                                
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-3 ">
                            <div class="form-group">
                              <label class="mr-2" for="_phone"><?php echo e(__('label._phone')); ?>:</label>
                              <input type="text" id="_phone" name="_phone" class="form-control _phone" value="<?php echo e(old('_phone',$data->_phone)); ?>" placeholder="<?php echo e(__('label._phone')); ?>" >
                                
                            </div>
                        </div>
                        
                        <div class="col-xs-12 col-sm-12 col-md-3 ">
                            <div class="form-group">
                              <label class="mr-2" for="_address"><?php echo e(__('label._address')); ?>:</label>
                              <input type="text" id="_address" name="_address" class="form-control _address" value="<?php echo e(old('_address',$data->_address)); ?>" placeholder="<?php echo e(__('label._address')); ?>" >
                                
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-3 ">
                            <div class="form-group">
                              <label class="mr-2" for="_referance"><?php echo e(__('label._referance')); ?>:</label>
                              <input type="text" id="_referance" name="_referance" class="form-control _referance" value="<?php echo e(old('_referance',$data->_referance)); ?>" placeholder="<?php echo e(__('label._referance')); ?>" >
                            </div>
                        </div>
                         <div class="col-xs-12 col-sm-12 col-md-12 ">
                            <div class="form-group">
                              <label class="mr-2" for="_referance"><?php echo e(__('label._delivery_details')); ?>:</label>
                              <textarea class="form-control" name="_delivery_details" ><?php echo $data->_delivery_details ?? ''; ?></textarea>
                              
                            </div>
                        </div>
                        
                        <div class="col-md-12  ">
                             <div class="card">
                              <div class="card-header">
                                <strong><?php echo e(__('label.details')); ?></strong>

                              </div>
                             
                              <div class="card-body">
                                <div class="table-responsive">
                                      <table class="table table-bordered" >
                                          <thead >
                                            <th class="text-left" >&nbsp;</th>
                                            <th class="text-left" ><?php echo e(__('label._item')); ?></th>
                                            <th class="text-left display_none" ><?php echo e(__('label._base_unit')); ?></th>
                                            <th class="text-left display_none" ><?php echo e(__('lable.conversion_qty')); ?></th>
                                            <th class="text-left <?php if($_show_unit==0): ?> display_none <?php endif; ?> " ><?php echo e(__('label.transection_unit')); ?></th>
                                           
                                            <th class="text-left <?php if($_show_barcode == 0): ?> display_none <?php endif; ?>" ><?php echo e(__('label._barcode')); ?></th>
                                            <th class="text-left <?php if($_show_warranty==0): ?> display_none <?php endif; ?>" ><?php echo e(__('label._warrantry')); ?></th>
                                            <th class="text-left  " ><?php echo e(__('label._qty')); ?></th>
                                            
                                            <th class="text-left <?php if($_show_cost_rate == 0): ?> display_none <?php endif; ?>" ><?php echo e(__('label._cost_rate')); ?></th>
                                            <th class="text-left" ><?php echo e(__('label.issue_rate')); ?></th>
                                            
                                            <th class="text-left <?php if($_show_vat == 0): ?> display_none <?php endif; ?> " ><?php echo e(__('label._vat')); ?>%</th>
                                            <th class="text-left <?php if($_show_vat == 0): ?> display_none <?php endif; ?>" ><?php echo e(__('label._vat_amount')); ?></th>
                                            
                                            <th class="text-left <?php if($_inline_discount == 0): ?> display_none <?php endif; ?> " ><?php echo e(__('label._dis')); ?>%</th>
                                            <th class="text-left <?php if($_inline_discount == 0): ?> display_none <?php endif; ?> " ><?php echo e(__('label._discount_amount')); ?></th>
                                            <th class="text-left" ><?php echo e(__('label._value')); ?></th>
                                             <th class="text-middle <?php if($_show_manufacture_date==0): ?> display_none <?php endif; ?> " ><?php echo e(__('label._manufacture_date')); ?></th>
                                             <th class="text-middle <?php if($_show_expire_date==0): ?> display_none <?php endif; ?> "> <?php echo e(__('label._expire_date')); ?> </th>
                                            <th class="text-left   <?php if($_show_branch == 0): ?> display_none <?php endif; ?>" ><?php echo e(__('label._branch_id')); ?></th>
                                             <th class="text-left <?php if($_show_cost_center==0): ?> display_none <?php endif; ?>" ><?php echo e(__('label._cost_center_id')); ?></th>
                                             <th class="text-left <?php if($_show_store== 0): ?> display_none <?php endif; ?>" ><?php echo e(__('label.store_house')); ?></th>
                                             <th class="text-left <?php if($_show_self==0): ?> display_none <?php endif; ?>" ><?php echo e(__('label.shelf')); ?></th>
                                           
                                           
                                          </thead>
                                          <tbody class="area__purchase_details" id="area__purchase_details">
                                            <?php
                                            $_total_qty = 0;
                                            $_total_vat_amount  = 0;
                                            $_total_discount_amount  = 0;
                                            $_total_value  = 0;
                                             $__master_details = $data->_master_details ?? [];
                                            ?>
                                             <?php if(sizeof($__master_details)> 0): ?>
                                           <?php $__empty_1 = true; $__currentLoopData = $data->_master_details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m_key=> $_detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

                                             <?php
                                            $_total_qty += floatval($_detail->_qty);
                                            $_total_vat_amount   += floatval($_detail->_vat_amount);
                                            $_total_discount_amount  += floatval($_detail->_discount_amount);
                                            $_total_value   += floatval($_detail->_value);
                                            ?>
                                            <tr class="_purchase_row">
                                              <td>
                                                <a  href="#none" class="btn btn-default _purchase_row_remove" ><i class="fa fa-trash"></i></a>
                                              </td>
                                              <td>
                                                <input type="text" name="_search_item_id[]" class="form-control _search_item_id  <?php echo e($m_key+1); ?>__search_item_id width_280_px" placeholder="Item" readonly value="<?php echo e($_detail->_items->_name); ?>">

                                                <input type="hidden" name="_item_id[]" class="form-control _item_id " value="<?php echo e($_detail->_items->_item_id); ?>" >
                                                <input type="hidden" name="_p_p_l_id[]" class="form-control _p_p_l_id " value="<?php echo e($_detail->_p_p_l_id); ?>" >
                                                <input type="hidden" name="_sales_ref_id[]" class="form-control _sales_ref_id" value="<?php echo e($_detail->_sales_ref_id); ?>" >
                                                <input type="hidden" name="_sales_detail_ref_id[]" class="form-control _sales_detail_ref_id" value="<?php echo e($_detail->_sales_detail_ref_id); ?>" >
                                                <input type="hidden" name="_sales_return_detail_id[]" class="form-control _sales_return_detail_id" value="<?php echo e($_detail->id); ?>" >
                                                <div class="search_box_item"> </div>
                                              </td>
                                               <td class="display_none">
                                                <input type="hidden" class="form-control _base_unit_id width_100_px" name="_base_unit_id[]" value="<?php echo e($_detail->_units->id); ?>" />
                                                <input type="text" class="form-control _main_unit_val width_100_px" readonly name="_main_unit_val[]" value="<?php echo e($_detail->_units->_name); ?>" />
                                                <input type="hidden" name="_base_rate[]" class="form-control _base_rate _common_keyup" value="<?php echo $_detail->_base_rate ?? 0; ?>" >
                                              </td>
                                              <td class="display_none">
                                                <input type="number" name="conversion_qty[]" min="0" step="any" class="form-control conversion_qty " value="<?php echo e($_detail->_unit_conversion ?? 1); ?>" readonly>
                                              </td>
                                              <td class="<?php if($form_settings->_show_unit==0): ?> display_none <?php endif; ?>">
                                                <select class="form-control _transection_unit" name="_transection_unit[]">
                                                  <?php
                                                  $own_unit_conversions = $_detail->unit_conversion ?? [];
                                                  ?>
                                                  <?php $__empty_2 = true; $__currentLoopData = $own_unit_conversions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $conversionUnit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                                                  <option value="<?php echo e($conversionUnit->_conversion_unit); ?>"  
                                                attr_base_unit_id="<?php echo e($conversionUnit->_base_unit_id); ?>" 
                                                attr_conversion_qty="<?php echo e($conversionUnit->_conversion_qty); ?>" 
                                                attr_conversion_unit="<?php echo e($conversionUnit->_conversion_unit); ?>" 
                                                attr_item_id="<?php echo e($conversionUnit->_item_id); ?>"
                                                <?php if($_detail->_transection_unit ==$conversionUnit->_conversion_unit): ?> selected  <?php endif; ?>

                                                 ><?php echo e($conversionUnit->_conversion_unit_name ?? ''); ?>

                                               </option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                                                <?php endif; ?>

                                                </select>
                                              </td>
                                             
                                              <td class=" <?php if($_show_barcode  ==0): ?> display_none <?php endif; ?> ">
                                                 <input type="text" name="<?php echo e(($m_key+1)); ?>__barcode__<?php echo e($_detail->_item_id); ?>" class="form-control _barcode <?php echo e(($m_key+1)); ?>__barcode"  value="<?php echo e($_detail->_barcode ?? ''); ?> " id="<?php echo e(($m_key+1)); ?>__barcode" >

                                                 <input type="hidden" class="_old_barcode" value="<?php echo e($_detail->_barcode ?? ''); ?>" />
                                               

                                                <input type="hidden" name="_ref_counter[]" value="<?php echo e(($m_key+1)); ?>" class="_ref_counter" id="<?php echo e(($m_key+1)); ?>__ref_counter">

                                                <input type="hidden" name="_barcode_unique[]" value="<?php echo e($_detail->_items->_unique_barcode); ?>" class="_barcode_unique <?php echo e(($m_key+1)); ?>__barcode_unique" id="<?php echo e(($m_key+1)); ?>__barcode_unique">

                                                 <?php if($_detail->_items->_unique_barcode==1): ?>
 <script type="text/javascript">
  $('#<?php echo ($m_key+1);?>__barcode').amsifySuggestags({
      trimValue: true,
      dashspaces: true,
      showPlusAfter: 1,
      });
                                            </script>
                                            <?php endif; ?>
                                              </td>
                                               <td class="<?php if($_show_warranty==0): ?> display_none <?php endif; ?>">
                                                <select name="_warranty[]" class="form-control _warranty 1___warranty">
                                                   <option value="0">--None --</option>
                                                      <?php $__empty_2 = true; $__currentLoopData = $_warranties; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $_warranty): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                                                      <option value="<?php echo e($_warranty->id); ?>" <?php if($_warranty->id==$_detail->_warranty): ?> selected <?php endif; ?> ><?php echo e($_warranty->_name ?? ''); ?></option>
                                                      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                                                      <?php endif; ?>
                                                </select>
                                              </td>
                                              
                                              <td>
                                                <input type="number" name="_qty[]" class="form-control _qty _common_keyup" value="<?php echo e($_detail->_qty ?? 0); ?>" <?php if($_detail->_items->_unique_barcode==1): ?> readonly <?php endif; ?>>
                                              </td>
                                              <td class=" <?php if($_show_cost_rate ==0): ?> display_none <?php endif; ?> " >
                                                <input type="number" name="_rate[]" class="form-control _rate  " readonly value="<?php echo e($_detail->_rate ?? 0); ?>">
                                              </td>
                                              <td>
                                                <input type="number" name="_sales_rate[]" class="form-control _sales_rate _common_keyup " value="<?php echo e($_detail->_sales_rate ?? 0); ?>">
                                              </td>
                                              
                                              <td class=" <?php if($_show_vat == 0): ?> display_none <?php endif; ?> ">
                                                <input type="text" name="_vat[]" class="form-control  _vat _common_keyup" placeholder="VAT%" value="<?php echo e($_detail->_vat ?? 0); ?>" >
                                              </td>
                                              <td class="<?php if($_show_vat ==0): ?> display_none <?php endif; ?> " >
                                                <input type="text" name="_vat_amount[]" class="form-control  _vat_amount" placeholder="VAT Amount" value="<?php echo e($_detail->_vat_amount ?? 0); ?>" >
                                              </td>
                                              
                                              
                                              <td class="<?php if($_inline_discount ==0): ?> display_none <?php endif; ?> " >
                                                <input type="text" name="_discount[]" class="form-control  _discount _common_keyup" value="<?php echo e($_detail->_discount ?? 0); ?>" >
                                              </td>
                                              <td class="<?php if($_inline_discount ==0): ?> display_none <?php endif; ?>" >
                                                <input type="text" name="_discount_amount[]" class="form-control  _discount_amount" value="<?php echo e($_detail->_discount_amount ?? 0); ?>" >
                                              </td>
                                              
                                              <td>
                                                <input type="number" name="_value[]" class="form-control _value " readonly value="<?php echo e($_detail->_value ?? 0); ?>" >
                                              </td>
                                               <td class="<?php if(isset($form_settings->_show_manufacture_date)): ?> <?php if($form_settings->_show_manufacture_date==0): ?> display_none  <?php endif; ?> <?php endif; ?>">
                                                <input type="date" name="_manufacture_date[]" class="form-control _manufacture_date " value="<?php echo e($_detail->_manufacture_date ?? ''); ?>" >
                                              </td>
                                              <td class="<?php if(isset($form_settings->_show_expire_date)): ?> <?php if($form_settings->_show_expire_date==0): ?> display_none  <?php endif; ?> <?php endif; ?>">
                                                <input type="date" name="_expire_date[]" class="form-control _expire_date " value="<?php echo e($_detail->_expire_date ?? ''); ?>" >
                                              </td>
                                            
                                               <td class="<?php if($_show_branch==0): ?> display_none <?php endif; ?> ">
                                                <input type="hidden" class="_main_branch_id_detail" name="_main_branch_id_detail[]" value="<?php echo e($_detail->_branch_id); ?>">
                                                <input readonly class="form-control" type="text" name="_text_branch_name[]" value="<?php echo $_detail->_detail_branch->_name ?? ''; ?>">
                                               
                                              </td>
                                             
                                             
                                               <td class=" <?php if($_show_cost_center==0): ?> display_none <?php endif; ?> " >
                                                <input type="hidden"  class="form-control  _main_cost_center" name="_main_cost_center[]" value="<?php echo e($_detail->_cost_center_id ?? 0); ?>">

                                                <input readonly type="text"  class="form-control  _text_main_cost_center" name="_text_main_cost_center[]" value="<?php echo e($_detail->_detail_cost_center->_name ?? ''); ?>">

                                                 
                                              </td>
                                            
                                              <td class=" <?php if($_show_store == 0): ?> display_none <?php endif; ?> ">

<input type="hidden" class="form-control  _main_store_id" name="_main_store_id[]" value="<?php echo e($_detail->_store_id); ?>">
<input readonly type="text" class="form-control  _text_main_store_id" name="_text_main_store_id[]" value="<?php echo e($_detail->_store->_name ?? ''); ?>">

                                                
                                                
                                              </td>
                                             
                                             
                                              <td class=" <?php if($_show_self==0): ?> display_none <?php endif; ?> ">
                                                <input type="text" name="_store_salves_id[]" class="form-control _store_salves_id " value="<?php echo e($_detail->_store_salves_id); ?>" >
                                              </td>
                                              
                                              
                                            </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                            <?php endif; ?>
                                            <?php endif; ?>
                                          </tbody>
                                          <tfoot>
                                            <tr>
                                              <td></td>
                                              <td  class="text-right"><b>Total</b></td>
                                              <td class="display_none"></td>
                                              <td class="display_none"></td>
                                              <td class="<?php if($form_settings->_show_unit==0): ?> display_none <?php endif; ?>"></td>
                                              <td  class="text-right <?php if($_show_barcode==0): ?> display_none <?php endif; ?>"></td>
                                              <td class="<?php if($_show_warranty==0): ?> display_none <?php endif; ?>"></td>
                                              <td>
                                                <input type="number" step="any" min="0" name="_total_qty_amount" class="form-control _total_qty_amount" value="<?php echo e($_total_qty ?? 0); ?>" readonly required>
                                              </td>
                                              <td class="<?php if($_show_cost_rate==0): ?> display_none <?php endif; ?>"></td>
                                              <td></td>
                                              <td class="<?php if($_show_vat==0): ?> display_none <?php endif; ?>"></td>
                                              <td class="<?php if($_show_vat==0): ?> display_none <?php endif; ?>">
                                                <input type="number" step="any" min="0" name="_total_vat_amount" class="form-control _total_vat_amount" value="<?php echo e($_total_vat_amount ?? 0); ?>" readonly required>
                                              </td>
                                              <td class="<?php if($_inline_discount==0): ?> display_none <?php endif; ?>"></td>
                                              <td class="<?php if($_inline_discount==0): ?> display_none <?php endif; ?>">
                                                <input type="number" step="any" min="0" name="_total_discount_amount" class="form-control _total_discount_amount" value="<?php echo e($_total_discount_amount ?? 0); ?>" readonly required>
                                              </td>
                                              <td>
                                                <input type="number" step="any" min="0" name="_total_value_amount" class="form-control _total_value_amount" value="<?php echo e($_total_value ?? 0); ?>" readonly required>
                                              </td>
                                               <td class="<?php if(isset($form_settings->_show_manufacture_date)): ?> <?php if($form_settings->_show_manufacture_date==0): ?> display_none  <?php endif; ?> <?php endif; ?>">
                                              </td>
                                              <td class="<?php if(isset($form_settings->_show_expire_date)): ?> <?php if($form_settings->_show_expire_date==0): ?> display_none  <?php endif; ?> <?php endif; ?>">
                                              </td>
                                             
                                               <td class="<?php if(sizeof($permited_branch) == 1): ?> display_none <?php endif; ?>"></td>
                                              
                                              
                                               <td class="<?php if(sizeof($permited_costcenters) == 1): ?> display_none <?php endif; ?>"></td>
                                             
                                               <td class="<?php if(sizeof($store_houses) == 1): ?> display_none <?php endif; ?>"></td>
                                              
                                              <td class="<?php if($_show_self==0): ?> display_none <?php endif; ?>"></td>
                                             
                                            </tr>
                                          </tfoot>
                                      </table>
                                </div>
                            </div>
                          </div>
                        </div>
                        
                        
                          


                        <div class="col-xs-12 col-sm-12 col-md-12 mb-10">
                          <table class="table" style="border-collapse: collapse;margin: 0px auto;">
                            <tr>
                              <td style="border:0px;width: 20%;"><label for="_note">Note</label></td>
                              <td style="border:0px;width: 80%;">
                                <?php if($_print = Session::get('_print_value')): ?>
                                     <input type="hidden" name="_after_print" value="<?php echo e($_print); ?>" class="_after_print" >
                                    <?php else: ?>
                                    <input type="hidden" name="_after_print" value="0" class="_after_print" >
                                    <?php endif; ?>
                                    <?php if($_master_id = Session::get('_master_id')): ?>
                                     <input type="hidden" name="_master_id" value="<?php echo e(url('material-issue-return/print')); ?>/<?php echo e($_master_id); ?>" class="_master_id">
                                    
                                    <?php endif; ?>
                                   
                                       <input type="hidden" name="_print" value="0" class="_save_and_print_value">

                                    <input type="text" id="_note"  name="_note" class="form-control _note" value="<?php echo e(old('_note',$data->_note)); ?>" placeholder="Note" required >
                              </td>
                            </tr>
                            <tr>
                              <td style="border:0px;width: 20%;"><label for="_sub_total">Sub Total</label></td>
                              <td style="border:0px;width: 80%;">
                                <input type="text" name="_sub_total" class="form-control width_200_px" id="_sub_total" readonly value="<?php echo e(_php_round( $data->_sub_total ?? 0)); ?>">
                              </td>
                            </tr>
                            <tr>
                              <td style="border:0px;width: 20%;"><label for="_discount_input">Invoice Discount</label></td>
                              <td style="border:0px;width: 80%;">
                                <input type="text" name="_discount_input" class="form-control width_200_px" id="_discount_input" value="<?php echo e($data->_discount_input ?? 0); ?>" >
                              </td>
                            </tr>
                            <tr>
                              <td style="border:0px;width: 20%;"><label for="_total_discount">Total Discount</label></td>
                              <td style="border:0px;width: 80%;">
                                <input type="text" name="_total_discount" class="form-control width_200_px" id="_total_discount" readonly value="<?php echo e($data->_total_discount ?? 0); ?>">
                              </td>
                            </tr>
                           
                            <tr class=" <?php if($_show_vat==0): ?> display_none <?php endif; ?>">
                              <td style="border:0px;width: 20%;"><label for="_total_vat">Total VAT</label></td>
                              <td style="border:0px;width: 80%;">
                                <input type="text" name="_total_vat" class="form-control width_200_px" id="_total_vat" readonly value="<?php echo e($data->_total_vat ?? 0); ?>">
                              </td>
                            </tr>
                           
                            <tr>
                              <td style="border:0px;width: 20%;"><label for="_total">NET Total </label></td>
                              <td style="border:0px;width: 80%;">
                          <input type="text" name="_total" class="form-control width_200_px" id="_total" readonly value="<?php echo e(_php_round($data->_total ?? 0 )); ?>">
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
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form action="<?php echo e(url('material-issue-return-settings')); ?>" method="POST">
        <?php echo csrf_field(); ?>
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Sales Return Form Settings</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body display_form_setting_info">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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
    setting_data_fetch();
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




  $(document).on("click","#form_settings",function(){
         setting_data_fetch();
  })

  function setting_data_fetch(){
      var request = $.ajax({
            url: "<?php echo e(url('material-issue-return-setting-modal')); ?>",
            method: "GET",
            dataType: "html"
          });
         request.done(function( result ) {
              $(document).find(".display_form_setting_info").html(result);
         })
  }


$(document).on('click',function(){
    var searach_show= $('.search_box_item').hasClass('search_box_show');
    var search_box_main_ledger= $('.search_box_main_ledger').hasClass('search_box_show');
    var search_row_purchase_order= $('.search_box_purchase_order').hasClass('search_box_show');
    if(searach_show ==true){
      $('.search_box_item').removeClass('search_box_show').hide();
    }

    if(search_box_main_ledger ==true){
      $('.search_box_main_ledger').removeClass('search_box_show').hide();
    }

    if(search_row_purchase_order ==true){
      $('.search_box_purchase_order').removeClass('search_box_show').hide();
    }
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
   if(isNaN(_sales_rate)){ _sales_rate =0 }
   if(isNaN(_base_rate)){ _base_rate =0 }

  if(converted_price_rate ==0){
    converted_price_rate = _sales_rate;
  }
  converted_price_rate = parseFloat(converted_price_rate).toFixed(2);
  if(isNaN(converted_price_rate)){converted_price_rate=0}

   if(isNaN(_item_vat)){ _item_vat   = 0 }
   if(isNaN(_qty)){ _qty   = 0 }
   if(isNaN(_rate)){ _rate =0 }
   if(isNaN(_item_discount)){ _item_discount =0 }
   _vat_amount = Math.ceil(((_qty*converted_price_rate)*_item_vat)/100)
   _discount_amount = Math.ceil(((_qty*converted_price_rate)*_item_discount)/100)


   var _value = parseFloat(converted_price_rate*_qty).toFixed(2);
 __this.closest('tr').find('._sales_rate').val(converted_price_rate);
 __this.closest('tr').find('._value').val(_value);
  __this.closest('tr').find('._vat_amount').val(_vat_amount);
  __this.closest('tr').find('._discount_amount').val(_discount_amount);
    _purchase_total_calculation();


}

$(document).on('keyup','._common_keyup',function(){
  var _vat_amount =0;
  var _qty = parseFloat($(this).closest('tr').find('._qty').val());
  var _rate =parseFloat( $(this).closest('tr').find('._rate').val());
  var _sales_rate =parseFloat( $(this).closest('tr').find('._sales_rate').val());
  var _item_vat = parseFloat($(this).closest('tr').find('._vat').val());
  var _item_discount = parseFloat($(this).closest('tr').find('._discount').val());

   if(isNaN(_item_vat)){ _item_vat   = 0 }
   if(isNaN(_qty)){ _qty   = 0 }
   if(isNaN(_rate)){ _rate =0 }
   if(isNaN(_sales_rate)){ _sales_rate =0 }
   if(isNaN(_item_discount)){ _item_discount =0 }
   _vat_amount = Math.ceil(((_qty*_sales_rate)*_item_vat)/100)
   _discount_amount = Math.ceil(((_qty*_sales_rate)*_item_discount)/100)

  $(this).closest('tr').find('._value').val((_qty*_sales_rate));
  $(this).closest('tr').find('._vat_amount').val(_vat_amount);
  $(this).closest('tr').find('._discount_amount').val(_discount_amount);
    _purchase_total_calculation();
})

$(document).on('keyup','._vat_amount',function(){
 var _item_vat =0;
  var _qty = $(this).closest('tr').find('._qty').val();
  var _rate = $(this).closest('tr').find('._rate').val();
  var _sales_rate = $(this).closest('tr').find('._sales_rate').val();
  var _vat_amount =  $(this).closest('tr').find('._vat_amount').val();
  
   if(isNaN(_vat_amount)){ _vat_amount = 0 }
   if(isNaN(_qty)){ _qty   = 0 }
   if(isNaN(_rate)){ _rate =0 }
   if(isNaN(_sales_rate)){ _sales_rate =0 }
   var _vat = parseFloat((_vat_amount/(_sales_rate*_qty))*100).toFixed(2);
    $(this).closest('tr').find('._vat').val(_vat);

    $(this).closest('tr').find('._value').val((_qty*_sales_rate));
 
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
   var _discount = parseFloat((_discount_amount/(_sales_rate*_qty))*100).toFixed(2);
    $(this).closest('tr').find('._discount').val(_discount);

    $(this).closest('tr').find('._value').val((_qty*_sales_rate));
 
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

   $("#_discount_input").val(on_invoice_discount);
    _purchase_total_calculation()
})



 function _purchase_total_calculation(){
    var _total_qty = 0;
    var _total__value = 0;
    var _total__vat =0;
    var _total_discount_amount = 0;
      $(document).find("._value").each(function() {
          _total__value +=parseFloat($(this).val());
      });
      $(document).find("._qty").each(function() {
          _total_qty +=parseFloat($(this).val());
      });
      $(document).find("._vat_amount").each(function() {
          _total__vat +=parseFloat($(this).val());
      });
      $(document).find("._discount_amount").each(function() {
          _total_discount_amount +=parseFloat($(this).val());
      });
      $("._total_qty_amount").val(_total_qty);
      $("._total_value_amount").val(_total__value);
      $("._total_vat_amount").val(_total__vat);
      $("._total_discount_amount").val(_total_discount_amount);

      var _discount_input = parseFloat($("#_discount_input").val());
      if(isNaN(_discount_input)){ _discount_input =0 }
      var _total_discount = parseFloat(_discount_input)+parseFloat(_total_discount_amount);
      $("#_sub_total").val(_math_round(_total__value));
      $("#_total_vat").val(_total__vat);
      $("#_total_discount").val(parseFloat(_discount_input)+parseFloat(_total_discount_amount));
      var _total = _math_round((parseFloat(_total__value)+parseFloat(_total__vat))-parseFloat(_total_discount));
      $("#_total").val(_total);
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

 


  $(document).on('click','.submit-button',function(event){
    event.preventDefault();

    var _p_p_l_ids_qtys = new Array();
     var _only_p_ids = [];
     var empty_qty = [];
    var _id_and_qtys = [];
    var _sales_detail_ref_ids = [];
    var _sales_return_id = $("._sales_return_id").val();
    var _order_ref_id = $("._order_ref_id").val();

    $(document).find('._p_p_l_id').each(function(index){
     var _p_id =  $(this).val();
     var _p_qty = $(document).find('._qty').eq(index).val();
     var _s_d_id = $(document).find('._sales_detail_ref_id').eq(index).val();
      var conversion_qty = $(document).find('.conversion_qty').eq(index).val();
     conversion_qty = parseFloat(conversion_qty);
     _p_qty = parseFloat(parseFloat(_p_qty)*parseFloat(conversion_qty));

     console.log(_p_qty)
     
     if(isNaN(_p_qty)){
        empty_qty.push(_p_id);
     }
     _only_p_ids.push(_p_id);
     _sales_detail_ref_ids.push({_s_d_id:_s_d_id,_p_qty:_p_qty});
      _p_p_l_ids_qtys.push( {_p_id: _p_id, _p_qty: _p_qty});
     

    })
    var counter_array=[];
    var _all_barcode = [];

    

    $(document).find('.show-plus-bg').each(function(index){
         console.log(parseFloat($(this).text())); 
         var _ref_counter = $(this).closest('tr').find('._ref_counter').val(); 
        if(!counter_array.includes(_ref_counter)){
            var _barcode_unique =   $(this).closest('tr').find('._barcode_unique').val(); 
            var _barcode = $(this).closest('tr').find('._barcode').val(); 
            var _qty = $(this).closest('tr').find('._qty').val();
            var _barcodes = isEmpty(_barcode);
            _all_barcode.push(_barcode);
            counter_array.push(_ref_counter);

        }
         
    })

    _all_barcode = _all_barcode.toString();


   


     var unique_p_ids = [...new Set(_only_p_ids)];
     var _stop_sales =0;
    if(_p_p_l_ids_qtys.length > 0){
        var request = $.ajax({
                url: "<?php echo e(url('check-material-issue-return-available-qty')); ?>",
                method: "GET",
                async:false,
                data: { _p_p_l_ids_qtys,unique_p_ids,_sales_detail_ref_ids:_sales_detail_ref_ids,_sales_return_id,_all_barcode,_order_ref_id },
                dataType: "JSON"
              });
               
              request.done(function( result ) {
                console.log(result)
                
                  if(result.length > 0){
                     
                   _stop_sales=1;
                  }else{
                     $(document).find("._over_qty").text('');
                  }
              })
    }
    if(_stop_sales ==1){
        alert(" You Can not Return More then Issue Qty or Invalid Barcode  ");
       var _message =" You Can not Return More then Issue Qty  or Invalid Barcode";
        $(document).find("._over_qty").text(_message);
        $(".remove_area").hide();
      return false;
    }


    
   
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

    if(empty_qty.length > 0){
       alert(" You Can not sale empty qty !");
      return false;

    }


    var empty_ledger = [];
    $(document).find("._search_item_id").each(function(){
        if($(this).val() ==""){
          alert(" Please Add Item  ");
          $(this).addClass('required_border');
          empty_ledger.push(1);
        }  
    })

    if(empty_ledger.length > 0){
      return false;
    }
   



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




 



</script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\own\inv-acc-hrm\resources\views/backend/material-issue-return/edit.blade.php ENDPATH**/ ?>