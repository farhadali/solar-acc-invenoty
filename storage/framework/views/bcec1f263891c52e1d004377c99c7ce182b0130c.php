<div  >

  
    <div class="modal fade" id="modal-default">
        <div class="modal-dialog modal-lg">
           <form action="<?php echo e(url('sales-return')); ?>" method="GET" class="form-horizontal">
            <?php echo csrf_field(); ?>
              <div class="modal-content">
                <div class="modal-header">
                  
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body search_modal">
                 <?php 
                    $row_numbers = filter_page_numbers();
                         
                  ?>
                 
                  <div class="form-group row">
                    <label for="_date" class="col-sm-3 col-form-label">Date:</label>
                    <div class="col-sm-9">
                      <div class="row">
                         <div class="col-sm-4">Use Date: 
                          <select class="form-control" name="_user_date">
                            <option value="no" <?php if(isset($request->_user_date)): ?> <?php if($request->_user_date=='no'): ?> selected <?php endif; ?>  <?php endif; ?>>No</option>
                            <option value="yes" <?php if(isset($request->_user_date)): ?> <?php if($request->_user_date=='yes'): ?> selected <?php endif; ?>  <?php endif; ?>>Yes</option>
                          </select>

                         </div>
                        <div class="col-sm-4">From: 
                          
                          <div class="input-group date" id="reservationdate_datex" data-target-input="nearest">
                                      <input type="text" name="_datex" class="form-control datetimepicker-input_datex" data-target="#reservationdate_datex" value="<?php if(isset($request->_datex)): ?><?php echo e($request->_datex ?? ''); ?><?php endif; ?>" />
                                      <div class="input-group-append" data-target="#reservationdate_datex" data-toggle="datetimepicker">
                                          <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                      </div>
                                  </div>
                        </div>
                        <div class="col-sm-4">To: 
                          <div class="input-group date" id="reservationdate_datey" data-target-input="nearest">
                                      <input type="text" name="_datey" class="form-control datetimepicker-input_datey" data-target="#reservationdate_datey" value="<?php if(isset($request->_datey)): ?><?php echo e($request->_datey ?? ''); ?><?php endif; ?>" />
                                      <div class="input-group-append" data-target="#reservationdate_datey" data-toggle="datetimepicker">
                                          <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                      </div>
                                  </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  
                  <div class="form-group row">
                    <label for="id" class="col-sm-3 col-form-label">ID:</label>
                    <div class="col-sm-9">
                      <input type="text" id="id" name="id" class="form-control" placeholder="Search By Id" 
                      value="<?php if(isset($request->id)): ?><?php echo e($request->id ?? ''); ?><?php endif; ?>">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="_branch_id " class="col-sm-3 col-form-label">Branch:</label>
                    <div class="col-sm-9">
                        <select class="form-control" name="_branch_id" required >
                                  
                                  <?php $__empty_1 = true; $__currentLoopData = $permited_branch; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $branch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                  <option value="<?php echo e($branch->id); ?>" <?php if(isset($request->_branch_id)): ?> <?php if($request->_branch_id == $branch->id): ?> selected <?php endif; ?>   <?php endif; ?>><?php echo e($branch->id ?? ''); ?> - <?php echo e($branch->_name ?? ''); ?></option>
                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                  <?php endif; ?>
                                </select>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="_ledger_id " class="col-sm-3 col-form-label">Ledger:</label>
                    <div class="col-sm-9">
                      <input type="text" id="_search_main_ledger_id" name="_search_main_ledger_id" class="form-control _search_main_ledger_id" value="<?php if(isset($request->_search_main_ledger_id)): ?> <?php echo e($request->_search_main_ledger_id ?? ''); ?>  <?php endif; ?>" placeholder="Supplier" >
                            <input type="hidden" id="_ledger_id" name="_ledger_id" class="form-control _ledger_id" value="<?php if(isset($request->_ledger_id)): ?><?php echo e($request->_ledger_id ?? ''); ?><?php endif; ?>" placeholder="Supplier" required>
                            <div class="search_box_main_ledger"> </div>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="_cost_center_id" class="col-sm-3 col-form-label">Cost Center:</label>
                    <div class="col-sm-9">
                      <input type="text" id="_search_main_cost_center_id" name="_search_main_cost_center_id" class="form-control _search_main_cost_center_id" value="<?php if(isset($request->_search_main_cost_center_id)): ?> <?php echo e($request->_search_main_cost_center_id ?? ''); ?>  <?php endif; ?>" placeholder="Cost Center" >
                            <input type="hidden" id="_cost_center_id" name="_cost_center_id" class="form-control _cost_center_id" value="<?php if(isset($request->_cost_center_id)): ?><?php echo e($request->_cost_center_id ?? ''); ?><?php endif; ?>" placeholder="Supplier" required>
                            <div class="search_box_main_cost_center"> </div>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="_delivery_man_id" class="col-sm-3 col-form-label">Delivery Man:</label>
                    <div class="col-sm-9">
                      <input type="text" id="_search_main_delivery_man_id" name="_search_main_delivery_man_id" class="form-control _search_main_delivery_man_id" value="<?php if(isset($request->_search_main_delivery_man_id)): ?> <?php echo e($request->_search_main_delivery_man_id ?? ''); ?>  <?php endif; ?>" placeholder="Delivery Man" >
                            <input type="hidden" id="_delivery_man_id" name="_delivery_man_id" class="form-control _delivery_man_id" value="<?php if(isset($request->_delivery_man_id)): ?><?php echo e($request->_delivery_man_id ?? ''); ?><?php endif; ?>" placeholder="Supplier" required>
                            <div class="search_box_delivery_man"> </div>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="_sales_man_id" class="col-sm-3 col-form-label">Delivery Man:</label>
                    <div class="col-sm-9">
                      <input type="text" id="_search_main_sales_man_id" name="_search_main_sales_man_id" class="form-control _search_main_sales_man_id" value="<?php if(isset($request->_search_main_sales_man_id)): ?> <?php echo e($request->_search_main_sales_man_id ?? ''); ?>  <?php endif; ?>" placeholder="Sales Man" >
                            <input type="hidden" id="_sales_man_id" name="_sales_man_id" class="form-control _sales_man_id" value="<?php if(isset($request->_sales_man_id)): ?><?php echo e($request->_sales_man_id ?? ''); ?><?php endif; ?>" placeholder="Sales Man" required>
                            <div class="search_box_sales_man"> </div>
                    </div>
                  </div>
                  
                  
                  <div class="form-group row">
                    <label for="_order_ref_id" class="col-sm-3 col-form-label">Purchase Number:</label>
                    <div class="col-sm-9">
                      <input type="text" id="_order_ref_id" name="_order_ref_id" class="form-control" placeholder="Search By Purchase Number" value="<?php if(isset($request->_order_ref_id)): ?><?php echo e($request->_order_ref_id ?? ''); ?><?php endif; ?>">
                    </div>
                  </div>
                  
                  <div class="form-group row">
                    <label for="_store_salves_id" class="col-sm-3 col-form-label">Store Self:</label>
                    <div class="col-sm-9">
                      <input type="text" id="_store_salves_id" name="_store_salves_id" class="form-control" placeholder="Search By Store Self" value="<?php if(isset($request->_store_salves_id)): ?><?php echo e($request->_store_salves_id ?? ''); ?><?php endif; ?>">
                    </div>
                  </div>
                  
                  <div class="form-group row">
                    <label for="_sales_type" class="col-sm-3 col-form-label">Sales Type:</label>
                    <div class="col-sm-9">
                      <input type="text" id="_sales_type" name="_sales_type" class="form-control" placeholder="Search By Sales Type" value="<?php if(isset($request->_sales_type)): ?><?php echo e($request->_sales_type ?? ''); ?><?php endif; ?>">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="_referance" class="col-sm-3 col-form-label">Referance:</label>
                    <div class="col-sm-9">
                      <input type="text" id="_referance" name="_referance" class="form-control" placeholder="Search By Referance" value="<?php if(isset($request->_referance)): ?><?php echo e($request->_referance ?? ''); ?><?php endif; ?>">
                    </div>
                  </div>
                  
                  <div class="form-group row">
                    <label for="_note" class="col-sm-3 col-form-label">Note:</label>
                    <div class="col-sm-9">
                      <input type="text" id="_note" name="_note" class="form-control" placeholder="Search By Note" value="<?php if(isset($request->_note)): ?><?php echo e($request->_note ?? ''); ?><?php endif; ?>">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="_sub_total" class="col-sm-3 col-form-label">Sub Total:</label>
                    <div class="col-sm-9">
                      <input type="text" id="_sub_total" name="_sub_total" class="form-control" placeholder="Search By Sub Total" value="<?php if(isset($request->_sub_total)): ?><?php echo e($request->_sub_total ?? ''); ?><?php endif; ?>">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="_total_discount" class="col-sm-3 col-form-label">Total Discount:</label>
                    <div class="col-sm-9">
                      <input type="text" id="_total_discount" name="_total_discount" class="form-control" placeholder="Search By Total Discount" value="<?php if(isset($request->_total_discount)): ?><?php echo e($request->_total_discount ?? ''); ?><?php endif; ?>">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="_total_vat" class="col-sm-3 col-form-label">Total VAT:</label>
                    <div class="col-sm-9">
                      <input type="text" id="_total_vat" name="_total_vat" class="form-control" placeholder="Search By Total VAT" value="<?php if(isset($request->_total_vat)): ?><?php echo e($request->_total_vat ?? ''); ?><?php endif; ?>">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="_total" class="col-sm-3 col-form-label">Total Amount:</label>
                    <div class="col-sm-9">
                      <input type="text" id="_total" name="_total" class="form-control" placeholder="Search By Total Amount" value="<?php if(isset($request->_total)): ?><?php echo e($request->_total ?? ''); ?><?php endif; ?>">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="_user_name" class="col-sm-3 col-form-label">User:</label>
                    <div class="col-sm-9">
                      <input type="text" id="_user_name" name="_user_name" class="form-control" placeholder="Search By User" value="<?php if(isset($request->_user_name)): ?><?php echo e($request->_user_name ?? ''); ?><?php endif; ?>">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="_lock" class="col-sm-2 col-form-label">Lock:</label>
                    <div class="col-sm-10">
                      <?php
                    $_locks = [ '0'=>'Open', '1'=>'Locked'];
                      ?>
                       <select id="_lock" class="form-control" name="_lock" >
                        <option value="">Select</option>
                            <?php $__currentLoopData = $_locks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($key); ?>" <?php if(isset($request->_lock)): ?> <?php if($key==$request->_lock): ?> selected <?php endif; ?> <?php endif; ?> ><?php echo e($val); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                  </div>
                  

                
                    <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-3 col-form-label">Order By:</label>
                    <div class="col-sm-9">
                      <?php
             $cloumns = [ 'id'=>'ID','_date'=>'Date','_user_name'=>'User name','_order_number'=>'Order Number','_order_ref_id'=>'Order Refarance','_referance'=>'Referance','_note'=>'Note', '_branch_id '=>'Branch','_ledger_id'=>'Ledger','_sub_total'=>'Sub Total','_total_discount'=>'Total Discount','_total_vat'=>'Total VAT','_total'=>'Total','_store_id'=>'Store','_cost_center_id'=>'Cost Center',
             '_store_salves_id'=>'Store Self','_delivery_man_id'=>'Delivery Man','_sales_man_id'=>'Sales Man','_sales_type'=>'Sales Type'];

                      ?>
                       <select class="form-control" name="asc_cloumn" >
                            
                            <?php $__currentLoopData = $cloumns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($key); ?>" <?php if(isset($request->asc_cloumn)): ?> <?php if($key==$request->asc_cloumn): ?> selected <?php endif; ?> <?php endif; ?> ><?php echo e($val); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-3 col-form-label">Sort Order:</label>
                    <div class="col-sm-9">
                       <select class=" form-control" name="_asc_desc">
                        <?php $__currentLoopData = asc_desc(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($val); ?>" <?php if(isset($request->_asc_desc)): ?> <?php if($val==$request->_asc_desc): ?> selected <?php endif; ?> <?php endif; ?> ><?php echo e($val); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                  </div> 
                   <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-3 col-form-label">Limit:</label>
                    <div class="col-sm-9">
                     <select name="limit" class="form-control" >
                              <?php $__empty_1 = true; $__currentLoopData = $row_numbers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                               <option  <?php if($limit == $row): ?> selected <?php endif; ?>   value="<?php echo e($row); ?>"><?php echo e($row); ?></option>
                              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                              <?php endif; ?>
                      </select>
                    </div>
                  </div>
                         

                             
                          
                       
                </div>
                <div class="modal-footer justify-content-between">
                  <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>

                  <button type="submit" class="btn btn-primary"><i class="fa fa-search mr-2"></i> Search</button>
                </div>
              </div>
            </form>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
                  <form action="" method="GET">
                    <?php echo csrf_field(); ?>

                        <div class="row">
                          <div class="col-md-4">
                            <div class="form-group">
                                
                                <select name="limit" class="form-control" onchange="this.form.submit()">
                                        <?php $__empty_1 = true; $__currentLoopData = $row_numbers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                         <option  <?php if($limit == $row): ?> selected <?php endif; ?>  value="<?php echo e($row); ?>"><?php echo e($row); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <?php endif; ?>
                                </select>
                              </div>
                          </div>
                          <div class="col-md-8">
                              <div class="form-group ">
                                
                                    <button type="button" class="btn btn-sm btn-warning mr-3" data-toggle="modal" data-target="#modal-default" title="Advance Search"><i class="fa fa-search mr-2"></i> </button>
                                     <a href="<?php echo e(url('sales-return-reset')); ?>" class="btn btn-sm btn-danger" title="Search Reset"><i class="fa fa-retweet mr-2"></i> </a>
                              </div>
                          </div>
                        </div><!-- end row -->
                   
                  </form>
                </div><?php /**PATH D:\xampp\htdocs\own\inv-acc-hrm\resources\views/backend/sales-return/search.blade.php ENDPATH**/ ?>