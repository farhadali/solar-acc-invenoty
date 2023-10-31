<div  >

  
    <div class="modal fade" id="modal-default">
        <div class="modal-dialog modal-lg">
           <form action="" method="GET" class="form-horizontal">
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
                    <label for="_date" class="col-sm-2 col-form-label">Date:</label>
                    <div class="col-sm-10">
                      <div class="row">
                         <div class="col-sm-2">Use Date: 
                          <select class="form-control" name="_user_date">
                            <option value="no" <?php if(isset($request->_user_date)): ?> <?php if($request->_user_date=='no'): ?> selected <?php endif; ?>  <?php endif; ?>>No</option>
                            <option value="yes" <?php if(isset($request->_user_date)): ?> <?php if($request->_user_date=='yes'): ?> selected <?php endif; ?>  <?php endif; ?>>Yes</option>
                          </select>

                         </div>
                        <div class="col-sm-5">From: 
                          
                          <div class="input-group date" id="reservationdate_datex" data-target-input="nearest">
                                      <input type="text" name="_datex" class="form-control datetimepicker-input_datex" data-target="#reservationdate_datex" value="<?php if(isset($request->_datex)): ?><?php echo e($request->_datex ?? ''); ?><?php endif; ?>" />
                                      <div class="input-group-append" data-target="#reservationdate_datex" data-toggle="datetimepicker">
                                          <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                      </div>
                                  </div>
                        </div>
                        <div class="col-sm-5">To: 
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
                    <label for="id" class="col-sm-2 col-form-label"><?php echo e(__('label.id')); ?>:</label>
                    <div class="col-sm-10">
                      <input type="text" id="id" name="id" class="form-control" placeholder="Search By Id" 
                      value="<?php if(isset($request->id)): ?><?php echo e($request->id ?? ''); ?><?php endif; ?>">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="rlp_no" class="col-sm-2 col-form-label"><?php echo e(__('label.rlp_no')); ?>:</label>
                    <div class="col-sm-10">
                      <input type="text" id="rlp_no" name="rlp_no" class="form-control" placeholder="<?php echo e(__('label.rlp_no')); ?>" 
                      value="<?php if(isset($request->rlp_no)): ?><?php echo e($request->rlp_no ?? ''); ?><?php endif; ?>">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="rlp_user_office_id" class="col-sm-2 col-form-label"><?php echo e(__('label.rlp_user_office_id')); ?>:</label>
                    <div class="col-sm-10">
                      <input type="text" id="rlp_user_office_id" name="rlp_user_office_id" class="form-control" placeholder="<?php echo e(__('label.rlp_user_office_id')); ?>" 
                      value="<?php if(isset($request->rlp_user_office_id)): ?><?php echo e($request->rlp_user_office_id ?? ''); ?><?php endif; ?>">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="priority" class="col-sm-2 col-form-label"><?php echo e(__('label.priority')); ?>:</label>
                    <div class="col-sm-10">
                      <select class="form-control priority" name="priority"  >
                            <option value=""><?php echo e(__('label.select')); ?> <?php echo e(__('label.priority')); ?></option>
                            <?php $__empty_1 = true; $__currentLoopData = priorities(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p_key=>$p_val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <option value="<?php echo e($p_key); ?>" <?php if(isset($request->priority) && $p_key==$request->priority ): ?> selected <?php endif; ?> ><?php echo e($p_val); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <?php endif; ?>
                          </select>
                    </div>
                  </div>
<?php
$request_departments = \DB::select("SELECT DISTINCT t2.id,t2._department as _name FROM rlp_masters as t1 INNER JOIN hrm_departments as t2 ON t1.request_department=t2.id ORDER BY t2._department asc");
?>
                  <div class="form-group row">
                    <label for="request_department" class="col-sm-2 col-form-label"><?php echo e(__('label.request_department')); ?>:</label>
                    <div class="col-sm-10">
                      <select id="request_department" class="form-control request_department" name="request_department"  >
                            <option value=""><?php echo e(__('label.select')); ?> <?php echo e(__('label.request_department')); ?></option>
                            <?php $__empty_1 = true; $__currentLoopData = $request_departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <option value="<?php echo e($val->id); ?>" <?php if(isset($request->request_department) && $val->id==$request->request_department ): ?> selected <?php endif; ?> ><?php echo $val->_name ?? ''; ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <?php endif; ?>
                          </select>
                    </div>
                  </div>
<?php
$users = \Auth::user();
$permited_branch = permited_branch(explode(',',$users->branch_ids));
$permited_costcenters = permited_costcenters(explode(',',$users->cost_center_ids));
$permited_organizations = permited_organization(explode(',',$users->organization_ids));
?> 

<div class="form-group row">
    <label for="organization_id" class="col-sm-2 col-form-label"><?php echo __('label.organization'); ?>:</label>
    <div class="col-sm-10">
      <select id="organization_id" class="form-control _master_organization_id" name="organization_id"  >
    <option value=""><?php echo e(__('label.select_organization')); ?></option>
     <?php $__empty_1 = true; $__currentLoopData = $permited_organizations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
     <option value="<?php echo e($val->id); ?>" <?php if(isset($data->organization_id)): ?> <?php if($data->organization_id == $val->id): ?> selected <?php endif; ?>   <?php endif; ?>><?php echo e($val->id ?? ''); ?> - <?php echo e($val->_name ?? ''); ?></option>
     <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
     <?php endif; ?>
   </select>
    </div>
</div>
<div class="form-group row">
    <label for="_master_branch_id" class="col-sm-2 col-form-label"><?php echo __('label.Branch'); ?>:</label>
    <div class="col-sm-10">
      <select class="form-control _master_branch_id" name="_branch_id"  >
          <option value=""><?php echo e(__('label.select_branch')); ?></option>
          <?php $__empty_1 = true; $__currentLoopData = $permited_branch; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $branch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
          <option value="<?php echo e($branch->id); ?>" <?php if(isset($data->_branch_id)): ?> <?php if($data->_branch_id == $branch->id): ?> selected <?php endif; ?>   <?php endif; ?>><?php echo e($branch->id ?? ''); ?> - <?php echo e($branch->_name ?? ''); ?></option>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
          <?php endif; ?>
        </select>
    </div>
</div>
<div class="form-group row">
    <label  class="col-sm-2 col-form-label"><?php echo e(__('label.Cost center')); ?>:</label>
    <div class="col-sm-10">
      <select class="form-control _cost_center_id" name="_cost_center_id"  >
        <option value=""><?php echo e(__('label.select_cost_center')); ?></option>
          <?php $__empty_1 = true; $__currentLoopData = $permited_costcenters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cost_center): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
          <option value="<?php echo e($cost_center->id); ?>" <?php if(isset($data->_cost_center_id)): ?> <?php if($data->_cost_center_id == $cost_center->id): ?> selected <?php endif; ?>   <?php endif; ?>><?php echo e($cost_center->id ?? ''); ?> - <?php echo e($cost_center->_name ?? ''); ?></option>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
          <?php endif; ?>
        </select>
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
                    <label for="inputEmail3" class="col-sm-2 col-form-label">Order By:</label>
                    <div class="col-sm-10">
                      <?php
             $cloumns = [ 'id'=>'ID','request_date'=>'Date','organization_id'=>__('label.organization_id'),'_branch_id'=>__('label._branch_id'),'_cost_center_id'=>__('label._cost_center_id'),'rlp_no'=>__('label.rlp_no'),'rlp_status'=>__('label._status'),];

                      ?>
                       <select class="form-control" name="asc_cloumn" >
                            
                            <?php $__currentLoopData = $cloumns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($key); ?>" <?php if(isset($request->asc_cloumn)): ?> <?php if($key==$request->asc_cloumn): ?> selected <?php endif; ?> <?php endif; ?> ><?php echo e($val); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label">Sort Order:</label>
                    <div class="col-sm-10">
                       <select class=" form-control" name="_asc_desc">
                        <?php $__currentLoopData = asc_desc(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($val); ?>" <?php if(isset($request->_asc_desc)): ?> <?php if($val==$request->_asc_desc): ?> selected <?php endif; ?> <?php endif; ?> ><?php echo e($val); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                  </div> 
                   <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label">Limit:</label>
                    <div class="col-sm-10">
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
                                     <a href="<?php echo e(url('rlp-reset')); ?>" class="btn btn-sm btn-danger" title="Search Reset"><i class="fa fa-retweet mr-2"></i> </a>
                              </div>
                          </div>
                        </div><!-- end row -->
                   
                  </form>
                </div><?php /**PATH D:\xampp\htdocs\own\inv-acc-hrm\resources\views/rlp-module/rlp/search.blade.php ENDPATH**/ ?>