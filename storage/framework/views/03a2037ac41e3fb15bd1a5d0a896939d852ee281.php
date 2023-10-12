
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
            <a class="m-0 _page_name" href="<?php echo e(route('production.index')); ?>"><?php echo $page_name ?? ''; ?> </a>
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
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('production-settings')): ?>
             <li class="breadcrumb-item ">
                 <button type="button" id="form_settings" class="btn btn-sm btn-default" data-toggle="modal" data-target="#exampleModal">
                   <i class="nav-icon fas fa-cog"></i> 
                </button>
               </li>
              <?php endif; ?>
              <li class="breadcrumb-item ">
                 <a class="btn btn-sm btn-success" title="List" href="<?php echo e(route('production.index')); ?>"> <i class="nav-icon fas fa-list"></i> </a>
               </li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>

    <?php
    
    $_show_barcode = $form_settings->_show_barcode ?? 0;
    $_show_cost_rate =  $form_settings->_show_cost_rate ?? 0;
    $_show_self = $form_settings->_show_self ?? 0;
    $_show_warranty = $form_settings->_show_warranty ?? 0;
    ?>
  
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
              <div class="card-body">
                <?php if($settings->_barcode_service ==1): ?>
                <div class="row mb-2">
                  <div class="col-md-2"></div>
                     <div class="col-md-8">
                       <div class="_barcode_search_div mt-2" >
                                <div class="form-group">
                                  <input required="" type="text" id="_serach_baorce" name="_serach_baorce" class="form-control _serach_baorce"  placeholder="Search with Unique Barcode"  >
                                    <div class="_main_item_search_box"></div>
                                </div>
                          </div>
                        </div>
                    <div class="col-md-2">
                   <button class="btn btn-danger mt-2 _clear_icon display_none" title="Clear Search"><i class=" fas fa-retweet "></i></button>
                 </div> 
               </div>
               <?php endif; ?>
               <form action="<?php echo e(route('production.store')); ?>" method="POST" class="purchase_form" >
                <?php echo csrf_field(); ?>
                    <div class="row">
                       <div class="col-xs-12 col-sm-12 col-md-4">
                        <input type="hidden" name="_form_name" class="_form_name"  value="production">
                            <div class="form-group">
                                <label>Date:</label>
                                  <div class="input-group date" id="reservationdate" data-target-input="nearest">
                                      <input type="text" name="_date" class="form-control datetimepicker-input" data-target="#reservationdate"/>
                                      <div class="input-group-append" data-target="#reservationdate" data-toggle="datetimepicker">
                                          <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                      </div>
                                  </div>
                              </div>
                        </div>
                        
                      <?php
$users = \Auth::user();
$permited_organizations = permited_organization(explode(',',$users->organization_ids));
?> 


<div class="col-xs-12 col-sm-12 col-md-2 ">
 <div class="form-group ">
     <label><?php echo __('label.from_organization'); ?>:<span class="_required">*</span></label>
    <select class="form-control " name="_from_organization_id" required >

       
       <?php $__empty_1 = true; $__currentLoopData = $permited_organizations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
       <option value="<?php echo e($val->id); ?>" <?php if(isset($request->organization_id)): ?> <?php if($request->organization_id == $val->id): ?> selected <?php endif; ?>   <?php endif; ?>><?php echo e($val->id ?? ''); ?> - <?php echo e($val->_name ?? ''); ?></option>
       <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
       <?php endif; ?>
     </select>
 </div>
</div>

                        <div class="col-xs-12 col-sm-12 col-md-2 " >
                            <div class="form-group ">
                                <label>From Branch:<span class="_required">*</span></label>
                               <select class="form-control" name="_from_branch" required >
                                  
                                  <?php $__empty_1 = true; $__currentLoopData = $permited_branch; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $branch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                  <option value="<?php echo e($branch->id); ?>" <?php if(isset($request->_from_branch)): ?> <?php if($request->_from_branch == $branch->id): ?> selected <?php endif; ?>   <?php endif; ?>><?php echo e($branch->id ?? ''); ?> - <?php echo e($branch->_name ?? ''); ?></option>
                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                  <?php endif; ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-2 " >
                            <div class="form-group ">
                                <label>From Cost Center:<span class="_required">*</span></label>
                               <select class="form-control" name="_from_cost_center" required >
                                  
                                  <?php $__empty_1 = true; $__currentLoopData = $permited_costcenters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cost_center): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                  <option value="<?php echo e($cost_center->id); ?>" <?php if(isset($request->_from_cost_center)): ?> <?php if($request->_from_cost_center == $cost_center->id): ?> selected <?php endif; ?>   <?php endif; ?>><?php echo e($cost_center->id ?? ''); ?> - <?php echo e($cost_center->_name ?? ''); ?></option>
                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                  <?php endif; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-4 " >
                            <div class="form-group ">
                                <label>Type:<span class="_required">*</span></label>
                               <select class="form-control" name="_type" required >
                                  <?php $__currentLoopData = $types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($type); ?>" <?php if(isset($request->_type)): ?> <?php if($request->_type == $type ): ?> selected <?php endif; ?>   <?php endif; ?>><?php echo e($type); ?></option>
                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                 
                                  
                                  
                                  
                                </select>
                            </div>
                        </div>
                        <?php
                          $all_organizations = \DB::table('companies')->select('id','_name')->where('_status',1)->get();
                          $all_branch = \DB::table('branches')->select('id','_name')->where('_status',1)->get();
                          $all_costcenters = \DB::table('cost_centers')->select('id','_name')->where('_status',1)->get();
                          ?>
                         <div class="col-xs-12 col-sm-12 col-md-2 " >
                          
                            <div class="form-group ">
                                <label><?php echo e(__('label.to_organization')); ?>:<span class="_required">*</span></label>
                               <select class="form-control" name="_to_organization_id" required >
                                  
                                  <?php $__empty_1 = true; $__currentLoopData = $all_organizations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $organization): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                  <option value="<?php echo e($organization->id); ?>" <?php if(isset($request->_to_organization_id)): ?> <?php if($request->_to_organization_id == $organization->id): ?> selected <?php endif; ?>   <?php endif; ?>><?php echo e($organization->id ?? ''); ?> - <?php echo e($organization->_name ?? ''); ?></option>
                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                  <?php endif; ?>
                                </select>
                            </div>
                        </div>
                         <div class="col-xs-12 col-sm-12 col-md-2 " >
                          
                            <div class="form-group ">
                                <label>To Branch:<span class="_required">*</span></label>
                               <select class="form-control" name="_to_branch" required >
                                  
                                  <?php $__empty_1 = true; $__currentLoopData = $all_branch; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $branch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                  <option value="<?php echo e($branch->id); ?>" <?php if(isset($request->_to_branch)): ?> <?php if($request->_to_branch == $branch->id): ?> selected <?php endif; ?>   <?php endif; ?>><?php echo e($branch->id ?? ''); ?> - <?php echo e($branch->_name ?? ''); ?></option>
                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                  <?php endif; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-2 " >
                            <div class="form-group ">
                                <label>To Cost Center:<span class="_required">*</span></label>
                               <select class="form-control" name="_to_cost_center" required >
                                  
                                  <?php $__empty_1 = true; $__currentLoopData = $all_costcenters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cost_center): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                  <option value="<?php echo e($cost_center->id); ?>" <?php if(isset($request->_to_cost_center)): ?> <?php if($request->_to_cost_center == $cost_center->id): ?> selected <?php endif; ?>   <?php endif; ?>><?php echo e($cost_center->id ?? ''); ?> - <?php echo e($cost_center->_name ?? ''); ?></option>
                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                  <?php endif; ?>
                                </select>
                            </div>
                        </div>
                        
                        
                        <div class="col-xs-12 col-sm-12 col-md-8 ">
                            <div class="form-group">
                              <label class="mr-2" for="_referance">Referance:</label>
                              <input type="text" id="_referance" name="_referance" class="form-control _referance" value="<?php echo e(old('_referance')); ?>" placeholder="Referance" >
                            </div>
                        </div>
                        
                        
                        <div class="col-md-12  ">
                             <div class="card">
                              <div class="card-header">
                                <strong>Stock Out</strong>
                                
                               
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
                                            <th class="text-left  <?php if($form_settings->_show_unit==0): ?> display_none    <?php endif; ?> " >Tran. Unit</th>
                                          
                                            <th class="text-left <?php if($_show_barcode  ==0): ?> display_none <?php endif; ?>" >Barcode</th>
                                            <th class="text-left <?php if($_show_warranty  ==0): ?> display_none <?php endif; ?>" >Warranty</th>
                                            
                                            <th class="text-left" >Qty</th>
                                            <th class="text-left " >Rate</th>
                                            <th class="text-left" >Sales Rate</th>
                                            
                                            <th class="text-left" >Value</th>

                                            <th class="text-middle <?php if(isset($form_settings->_show_manufacture_date)): ?> <?php if($form_settings->_show_manufacture_date==0): ?> display_none <?php endif; ?>
                                            <?php endif; ?>" >Manu. Date</th>
                                             <th class="text-middle <?php if(isset($form_settings->_show_expire_date)): ?> <?php if($form_settings->_show_expire_date==0): ?> display_none <?php endif; ?>
                                            <?php endif; ?>"> Expired Date </th>
                                           
                                            <th class="text-left  <?php if(sizeof($permited_branch)  ==1): ?> display_none <?php endif; ?> " >Branch</th>
                                            
                                            
                                             <th class="text-left  <?php if(sizeof($permited_costcenters)  ==1): ?> display_none <?php endif; ?> " >Cost Center</th>
                                            
                                             
                                             <th class="text-left <?php if(sizeof($store_houses) ==1): ?> display_none <?php endif; ?>" >Store</th>
                                           
                                            
                                             <th class="text-left  <?php if($_show_self  ==0): ?> display_none <?php endif; ?> " >Shelf</th>
                                           
                                           
                                          </thead>
                                          <tbody class="area__purchase_details" id="area__purchase_details">
                                            <tr class="_purchase_row _purchase_row__">
                                              <td>
                                                <a  href="#none" class="btn btn-default _purchase_row_remove" ><i class="fa fa-trash"></i></a>
                                              </td>
                                              <td></td>
                                              <td>
                                                <input type="text" name="_search_item_id[]" class="form-control _search_item_id width_280_px" placeholder="Item">

                                                <input type="hidden" name="_item_id[]" class="form-control _item_id " >
                                                <input type="hidden" name="_p_p_l_id[]" class="form-control _p_p_l_id " >
                                                <input type="hidden" name="_purchase_invoice_no[]" class="form-control _purchase_invoice_no" >
                                                <input type="hidden" name="_purchase_detail_id[]" class="form-control _purchase_detail_id" >
                                                <div class="search_box_item">
                                                  
                                                </div>
                                              </td>
                                              <td class="display_none">
                                                <input type="hidden" class="form-control _base_unit_id width_100_px" name="_base_unit_id[]" />
                                                <input type="text" class="form-control _main_unit_val width_100_px" readonly name="_main_unit_val[]" />
                                              </td>
                                              <td class="display_none">
                                                <input type="number" name="conversion_qty[]" min="0" step="any" class="form-control conversion_qty " value="1" readonly>
                                                <input type="number" name="_base_rate[]" min="0" step="any" class="form-control _base_rate "  readonly>
                                              </td>
                                              <td class="<?php if($form_settings->_show_unit==0): ?> display_none <?php endif; ?>">
                                                <select class="form-control _transection_unit" name="_transection_unit[]">
                                                </select>
                                              </td>
                                             
                                              <td class=" <?php if($_show_barcode  ==0): ?> display_none <?php endif; ?> ">
                                               
                                                <input type="text" name="_barcode_[]" class="form-control _barcode 1__barcode"  value="" id="1__barcode" readonly >
                                                <input type="hidden" name="_ref_counter[]" value="1" class="_ref_counter" id="1__ref_counter">
                                              </td>
                                              <td  class="<?php if($_show_warranty  ==0): ?> display_none <?php endif; ?>" >
                                                <select name="_warranty[]" class="form-control _warranty 1___warranty">
                                                   <option value="0">--Select --</option>
                                                      <?php $__empty_1 = true; $__currentLoopData = $_warranties; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $_warranty): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                      <option value="<?php echo e($_warranty->id); ?>" ><?php echo e($_warranty->_name ?? ''); ?></option>
                                                      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                      <?php endif; ?>
                                                </select>
                                              </td>
                                              
                                              <td>
                                                <input type="number" name="_qty[]" class="form-control _qty _common_keyup" >
                                              </td>
                                              <td class=" " >
                                                <input type="number" name="_rate[]" class="form-control _rate  _common_keyup" >
                                              </td>
                                              <td>
                                                <input type="number" name="_sales_rate[]" class="form-control _sales_rate _common_keyup " >
                                              </td>
                                              
                                              
                                              <td>
                                                <input type="number" name="_value[]" class="form-control _value " readonly >
                                              </td>
                                              <td class="<?php if(isset($form_settings->_show_manufacture_date)): ?> <?php if($form_settings->_show_manufacture_date==0): ?> display_none  <?php endif; ?> <?php endif; ?>">
                                                <input type="date" name="_manufacture_date[]" class="form-control _manufacture_date " >
                                              </td>
                                              <td class="<?php if(isset($form_settings->_show_expire_date)): ?> <?php if($form_settings->_show_expire_date==0): ?> display_none  <?php endif; ?> <?php endif; ?>">
                                                <input type="date" name="_expire_date[]" class="form-control _expire_date " >
                                              </td>
                                            
                                               <td class="<?php if(sizeof($permited_branch) == 1): ?> display_none <?php endif; ?> ">
                                                <select class="form-control  _main_branch_id_detail" name="_main_branch_id_detail[]"  required>
                                                  <?php $__empty_1 = true; $__currentLoopData = $permited_branch; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $branch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                  <option value="<?php echo e($branch->id); ?>" <?php if(isset($request->_branch_id)): ?> <?php if($request->_branch_id == $branch->id): ?> selected <?php endif; ?>   <?php endif; ?>><?php echo e($branch->_name ?? ''); ?></option>
                                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                  <?php endif; ?>
                                                </select>
                                              </td>
                                             
                                             
                                               <td class=" <?php if(sizeof($permited_costcenters) == 1): ?> display_none <?php endif; ?> " >
                                                 <select class="form-control  _main_cost_center" name="_main_cost_center[]" required >
                                            
                                                  <?php $__empty_1 = true; $__currentLoopData = $permited_costcenters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $costcenter): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                  <option value="<?php echo e($costcenter->id); ?>" <?php if(isset($request->_main_cost_center)): ?> <?php if($request->_main_cost_center == $costcenter->id): ?> selected <?php endif; ?>   <?php endif; ?>> <?php echo e($costcenter->_name ?? ''); ?></option>
                                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                  <?php endif; ?>
                                                </select>
                                              </td>
                                            
                                              <td class=" <?php if(sizeof($store_houses) == 1): ?> display_none <?php endif; ?> ">
                                                <select class="form-control  _main_store_id" name="_main_store_id[]">
                                                  <?php $__empty_1 = true; $__currentLoopData = $store_houses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $store): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                  <option value="<?php echo e($store->id); ?>"><?php echo e($store->_name ?? ''); ?></option>
                                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                  <?php endif; ?>
                                                </select>
                                                
                                              </td>
                                             
                                             
                                              <td class=" <?php if($_show_self==0): ?> display_none <?php endif; ?> ">
                                                <input type="text" name="_store_salves_id[]" class="form-control _store_salves_id " >
                                              </td>
                                              
                                              
                                            </tr>
                                          </tbody>
                                          <tfoot>
                                            <tr>
                                              <td>
                                                <a href="#none"  class="btn btn-default btn-sm" onclick="purchase_row_add(event)"><i class="fa fa-plus"></i></a>
                                              </td>
                                              <td colspan="2"  class="text-right"><b>Total</b></td>
                                                <td class="text-left display_none" ></td>
                                            <td class="text-left display_none" ></td>
                                            <td class="text-left <?php if(isset($form_settings->_show_unit)): ?> <?php if($form_settings->_show_unit==0): ?> display_none    <?php endif; ?> <?php endif; ?>" ></td>

                                                <td  class="text-right <?php if($_show_barcode==0): ?> display_none <?php endif; ?>"></td>
                                                <td  class="text-right <?php if($_show_warranty==0): ?> display_none <?php endif; ?>"></td>
                                             
                                              <td>
                                                <input type="number" step="any" min="0" name="_total_qty_amount" class="form-control _total_qty_amount" value="0" readonly required>
                                              </td>
                                              <td class=""></td>
                                              <td></td>
                                              
                                             
                                              
                                             
                                              <td>
                                                <input type="number" step="any" min="0" name="_total_value_amount" class="form-control _total_value_amount" value="0" readonly required>
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

                        
                        <div class="col-md-12  ">
                             <div class="card">
                              <div class="card-header">
                                <strong>Stock In</strong>

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
                                            <th class="text-left  <?php if($form_settings->_show_unit==0): ?> display_none    <?php endif; ?> " >Tran. Unit</th>
                                           
                                            <th class="text-left <?php if(isset($form_settings->_show_barcode)): ?> <?php if($form_settings->_show_barcode==0): ?> display_none    <?php endif; ?> <?php endif; ?>" >Barcode</th>
                                         
                                            <th class="text-left" >Qty</th>
                                            <th class="text-left" >Rate</th>
                                            <th class="text-left" >Sales Rate</th>
                                           
                                            
                                          

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
                                             
                                            <th class="text-left" >Store</th>
                                            
                                             
                                             <th class="text-left <?php if(isset($form_settings->_show_manufacture_date)): ?> <?php if($form_settings->_show_manufacture_date==0): ?> display_none <?php endif; ?>
                                            <?php endif; ?>" >Manu. Date</th>
                                             <th class="text-left <?php if(isset($form_settings->_show_expire_date)): ?> <?php if($form_settings->_show_expire_date==0): ?> display_none <?php endif; ?>
                                            <?php endif; ?>"> Expired Date </th>
                                            <th class="text-left <?php if(isset($form_settings->_show_self)): ?> <?php if($form_settings->_show_self==0): ?> display_none <?php endif; ?>
                                            <?php endif; ?>" >Shelf</th>
                                            
                                           
                                          </thead>
                                          <tbody class="area__purchase_details" id="_stock_in_area__purchase_details">
                                            <tr class="_purchase_row">
                                              <td>
                                                <a  href="#none" class="btn btn-default _purchase_row_remove" ><i class="fa fa-trash"></i></a>
                                              </td>
                                              <td></td>
                                              <td>
                                                <input type="text" name="_stock_in__search_item_id[]" class="form-control _stock_in__search_item_id width_280_px" placeholder="Item">
                                                 <input type="hidden" name="_stock_in__item_id[]" class="form-control _stock_in__item_id width_200_px" >
                                                <input type="hidden" name="_stock_in__p_p_l_id[]" class="form-control _stock_in__p_p_l_id " >
                                                <input type="hidden" name="_stock_in__purchase_invoice_no[]" class="form-control _stock_in__purchase_invoice_no" >
                                                <input type="hidden" name="_stock_in__purchase_detail_id[]" class="form-control _stock_in__purchase_detail_id" >
                                                <div class="_stock_in_search_box_item">
                                                  
                                                </div>
                                              </td>
                                                 <td class="display_none">
                                                <input type="hidden" class="form-control _stock_in_base_unit_id width_100_px" name="_stock_in_base_unit_id[]" />
                                                <input type="text" class="form-control _stock_in_main_unit_val width_100_px" readonly name="_stock_in_main_unit_val[]" />
                                              </td>
                                              <td class="display_none">
                                                <input type="number" name="_stock_inconversion_qty[]" min="0" step="any" class="form-control _stock_inconversion_qty " value="1" readonly>
                                                <input type="number" name="_stock_in_base_rate[]" min="0" step="any" class="form-control _stock_in_base_rate "  readonly>
                                              </td>
                                              <td class="<?php if($form_settings->_show_unit==0): ?> display_none <?php endif; ?>">
                                                <select class="form-control _stock_in_transection_unit" name="_stock_in_transection_unit[]">
                                                </select>
                                              </td>
                                              
                                              <td class="<?php if(isset($form_settings->_show_barcode)): ?> <?php if($form_settings->_show_barcode==0): ?> display_none   <?php endif; ?> <?php endif; ?>">
                                                <input type="text" name="_stock_in__barcode[]" class="form-control _stock_in__barcode 1___stock_in_barcode "  id="1___stock_in_barcode">

                                                <input type="hidden" name="_stock_in__ref_counter[]" value="1" class="_stock_in__ref_counter" id="1___stock_in_ref_counter">

                                              </td>
                                            
                                              <td>
                                                <input type="number" name="_stock_in__qty[]" class="form-control _stock_in__qty _stock_in_common_keyup" >
                                              </td>
                                              <td>
                                                <input type="number" name="_stock_in__rate[]" class="form-control _stock_in__rate _stock_in_common_keyup" >
                                              </td>
                                              <td>
                                                <input type="number" name="_stock_in__sales_rate[]" class="form-control _stock_in__sales_rate " >
                                              </td>
                                             
                                             
                                             
                                              <td>
                                                <input type="number" name="_stock_in__value[]" class="form-control _stock_in__value " readonly >
                                              </td>
                                            <?php if(sizeof($permited_branch) > 1): ?>  
                                              <td>
                                                <select class="form-control  _stock_in__main_branch_id_detail" name="_stock_in__main_branch_id_detail[]"  required>
                                                  <?php $__empty_1 = true; $__currentLoopData = $permited_branch; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $branch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                  <option value="<?php echo e($branch->id); ?>" <?php if(isset($request->_branch_id)): ?> <?php if($request->_branch_id == $branch->id): ?> selected <?php endif; ?>   <?php endif; ?>><?php echo e($branch->_name ?? ''); ?></option>
                                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                  <?php endif; ?>
                                                </select>
                                              </td>
                                              <?php else: ?>
                                               <td class="display_none">
                                                <select class="form-control  _stock_in__main_branch_id_detail" name="_stock_in__main_branch_id_detail[]"  required>
                                                  <?php $__empty_1 = true; $__currentLoopData = $permited_branch; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $branch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                  <option value="<?php echo e($branch->id); ?>" <?php if(isset($request->_branch_id)): ?> <?php if($request->_branch_id == $branch->id): ?> selected <?php endif; ?>   <?php endif; ?>><?php echo e($branch->_name ?? ''); ?></option>
                                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                  <?php endif; ?>
                                                </select>
                                              </td>
                                              <?php endif; ?>
                                             <?php if(sizeof($permited_costcenters) > 1): ?>
                                                <td>
                                                 <select class="form-control  _stock_in__main_cost_center" name="_stock_in__main_cost_center[]" required >
                                            
                                                  <?php $__empty_1 = true; $__currentLoopData = $permited_costcenters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $costcenter): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                  <option value="<?php echo e($costcenter->id); ?>" <?php if(isset($request->_main_cost_center)): ?> <?php if($request->_main_cost_center == $costcenter->id): ?> selected <?php endif; ?>   <?php endif; ?>> <?php echo e($costcenter->_name ?? ''); ?></option>
                                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                  <?php endif; ?>
                                                </select>
                                              </td>
                                              <?php else: ?>
                                               <td class="display_none">
                                                 <select class="form-control  _stock_in__main_cost_center" name="_stock_in__main_cost_center[]" required >
                                            
                                                  <?php $__empty_1 = true; $__currentLoopData = $permited_costcenters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $costcenter): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                  <option value="<?php echo e($costcenter->id); ?>" <?php if(isset($request->_main_cost_center)): ?> <?php if($request->_main_cost_center == $costcenter->id): ?> selected <?php endif; ?>   <?php endif; ?>> <?php echo e($costcenter->_name ?? ''); ?></option>
                                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                  <?php endif; ?>
                                                </select>
                                              </td>
                                              <?php endif; ?>
                                             
                                              <td>
                                                <select class="form-control  _stock_in__main_store_id" name="_stock_in__main_store_id[]">
                                                  <?php $__empty_1 = true; $__currentLoopData = $_all_store_houses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $store): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                  <option value="<?php echo e($store->id); ?>"><?php echo e($store->_name ?? ''); ?>/<?php echo e($store->_branch->_name ?? ''); ?></option>
                                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                  <?php endif; ?>
                                                </select>
                                              </td>
                                              
                                              
                                              
                                              <td class="<?php if(isset($form_settings->_show_manufacture_date)): ?> <?php if($form_settings->_show_manufacture_date==0): ?> display_none  <?php endif; ?> <?php endif; ?>">
                                                <input type="date" name="_stock_in__manufacture_date[]" class="form-control _stock_in__manufacture_date " >
                                              </td>
                                              <td class="<?php if(isset($form_settings->_show_expire_date)): ?> <?php if($form_settings->_show_expire_date==0): ?> display_none  <?php endif; ?> <?php endif; ?>">
                                                <input type="date" name="_stock_in__expire_date[]" class="form-control _stock_in__expire_date " >
                                              </td>
                                             <td class="<?php if(isset($form_settings->_show_self)): ?> <?php if($form_settings->_show_self==0): ?> display_none  <?php endif; ?> <?php endif; ?>">
                                                <input type="text" name="_stock_in__store_salves_id[]" class="form-control _stock_in__store_salves_id " >
                                              </td>
                                              
                                            </tr>
                                          </tbody>
                                          <tfoot>
                                            <tr>
                                              <td>
                                                <a href="#none"  class="btn btn-default btn-sm" onclick="_stock_in_purchase_row_add(event)"><i class="fa fa-plus"></i></a>
                                              </td>
                                              <td class="display_none"></td>
                                              <td class="display_none"></td>
                                              <td class="<?php if($form_settings->_show_unit==0): ?> display_none <?php endif; ?>"></td>
                                              <td colspan="2"  class="text-right"><b>Total</b></td>
                                              <?php if(isset($form_settings->_show_barcode)): ?> <?php if($form_settings->_show_barcode==1): ?>
                                              <td  class="text-right"></td>
                                              <?php else: ?>
                                                <td  class="text-right display_none"></td>
                                             <?php endif; ?>
                                            <?php endif; ?>
                                              <td>
                                                <input type="number" step="any" min="0" name="_total_qty_amount" class="form-control _stock_in__total_qty_amount" value="0" readonly required>
                                              </td>
                                              <td></td>
                                              <td></td>
                                             
                                             
                                            
                                              <td>
                                                <input type="number" step="any" min="0" name="_total_value_amount" class="form-control _stock_in__total_value_amount" value="0" readonly required>
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
                                              
                                              <td></td>
                                              

                                              
                                              <td class="<?php if(isset($form_settings->_show_manufacture_date)): ?> <?php if($form_settings->_show_manufacture_date==0): ?> display_none  <?php endif; ?>  <?php endif; ?>"></td>

                                              <td class="<?php if(isset($form_settings->_show_expire_date)): ?> <?php if($form_settings->_show_expire_date==0): ?> display_none  <?php endif; ?>  <?php endif; ?>"></td>

                                              <td class="<?php if(isset($form_settings->_show_self)): ?> <?php if($form_settings->_show_self==0): ?> display_none  <?php endif; ?>  <?php endif; ?>"></td>
                                              
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
                              <td style="border:0px;width: 20%;"><label for="_note">Note<span class="_required">*</span></label></td>
                              <td style="border:0px;width: 80%;">
                                <?php if($_print = Session::get('_print_value')): ?>
                                     <input type="hidden" name="_after_print" value="<?php echo e($_print); ?>" class="_after_print" >
                                    <?php else: ?>
                                    <input type="hidden" name="_after_print" value="0" class="_after_print" >
                                    <?php endif; ?>
                                    <?php if($_master_id = Session::get('_master_id')): ?>
                                     <input type="hidden" name="_master_id" value="<?php echo e(url('production/print')); ?>/<?php echo e($_master_id); ?>" class="_master_id">
                                    
                                    <?php endif; ?>
                                   
                                       <input type="hidden" name="_print" value="0" class="_save_and_print_value">

                                    <input type="text" id="_note"  name="_note" class="form-control _note" value="<?php echo e(old('_note')); ?>" placeholder="Note" required >
                              </td>
                            </tr>
                            <tr>
                              <td style="border:0px;width: 20%;"><label for="_sub_total">Status</label></td>
                              <td style="border:0px;width: 80%;">
                                <?php
                                $_p_statues = \DB::table("production_status")->get();
                                ?>
                                
                               <select class="form-control" name="_p_status" required >
                                  
                                  <?php $__empty_1 = true; $__currentLoopData = $_p_statues; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $_statues): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                  <option value="<?php echo e($_statues->id); ?>" <?php if(isset($request->_p_status)): ?> <?php if($request->_p_status == $_statues->id): ?> selected <?php endif; ?>   <?php endif; ?>> <?php echo e($_statues->_name ?? ''); ?></option>
                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                  <?php endif; ?>
                                </select>
                              </td>
                            </tr>
                           
                           
                           
                            <tr>
                              <td style="border:0px;width: 20%;"><label for="_total">Stock Out Total </label></td>
                              <td style="border:0px;width: 80%;">
                          <input type="text" name="_total" class="form-control width_200_px" id="_total" readonly value="0">
                           <input type="hidden" name="_item_row_count" value="1" class="_item_row_count">
                           <input type="hidden" name="_stock_in__item_row_count" value="1" class="_stock_in__item_row_count">
                              </td>
                            </tr>
                            <tr>
                              <td style="border:0px;width: 20%;"><label for="_stock_in__total">Stock In Total </label></td>
                              <td style="border:0px;width: 80%;">
                          <input type="text" name="_stock_in__total" class="form-control width_200_px" id="_stock_in__total" readonly value="0">
                              </td>
                            </tr>
                            
                              
                            
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

<div class="modal fade" id="barcodeDisplayModal" tabindex="-1" role="dialog" aria-labelledby="barcodeDisplayModal" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title _barcode_modal_item_name" id="barcodeDisplayModal"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body _barcode_modal_list_body">
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog  modal-lg" role="document">
    <form action="<?php echo e(url('production-form-settings')); ?>" method="POST">
        <?php echo csrf_field(); ?>
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Production/production Form Settings</h5>
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

<?php
      $_string_ids = $form_settings->_cash_customer ?? 0;
      if($_string_ids !=0){
        $_cash_customer = explode(",",$_string_ids);
      }else{
        $_cash_customer =[];
      }
      ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
<?php echo $__env->make('backend.production.stock_out_script', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php echo $__env->make('backend.production.stock_in_script', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\own\inv-acc-hrm\resources\views/backend/production/create.blade.php ENDPATH**/ ?>