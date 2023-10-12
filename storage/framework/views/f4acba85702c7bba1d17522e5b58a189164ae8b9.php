
<div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <a class="m-0 _page_name" href="<?php echo e(route('account-ledger.index')); ?>"><?php echo $page_name ?? ''; ?> </a>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
             <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('account-ledger-list')): ?>
              <li class="breadcrumb-item active">
                 <a class="btn btn-info" href="<?php echo e(route('account-ledger.index')); ?>"> <i class="fa fa-th-list" aria-hidden="true"></i></a>
               </li>
               <?php endif; ?>
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
             
              <div class="card-body">
                <?php echo Form::open(array('route' => 'account-ledger.store','method'=>'POST')); ?>

                    <div class="row">
                       <div class="col-xs-12 col-sm-12 col-md-3">
                            <div class="form-group">
                                <label>Account Type: <span class="_required">*</span></label>
                               <select type_base_group="<?php echo e(url('type_base_group')); ?>" class="form-control _account_head_id select2" name="_account_head_id" required>
                                  <option value="">--Select Account Type--</option>
                                  <?php $__empty_1 = true; $__currentLoopData = $account_types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $account_type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                  <option value="<?php echo e($account_type->id); ?>"  <?php if(old('_account_head_id') == $account_type->id): ?> selected <?php endif; ?>   ><?php echo e($account_type->id ?? ''); ?>-<?php echo e($account_type->_name ?? ''); ?></option>
                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                  <?php endif; ?>
                                </select>
                            </div>
                        </div>
                       <div class="col-xs-12 col-sm-12 col-md-3">
                            <div class="form-group ">
                                <label>Account Group:<span class="_required">*</span></label>
                               <select class="form-control _account_groups select2" name="_account_group_id" required>
                                  <option value="">--Select Account Group--</option>
                                  <?php $__empty_1 = true; $__currentLoopData = $account_groups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $account_group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                  <option value="<?php echo e($account_group->id); ?>"  <?php if(old('_account_group_id') == $account_group->id): ?> selected <?php endif; ?>   ><?php echo e($account_group->id ?? ''); ?> - <?php echo e($account_group->_name ?? ''); ?></option>
                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                  <?php endif; ?>
                                </select>
                            </div>
                        </div>
                        <?php
                            $users = \Auth::user();
                            $permited_organizations = permited_organization(explode(',',$users->organization_ids));
                            $permited_costcenters = permited_costcenters(explode(',',$users->cost_center_ids));
                            ?> 


                            <div class="col-xs-12 col-sm-12 col-md-2 <?php if(sizeof($permited_organizations)==1): ?> display_none <?php endif; ?>">
                             <div class="form-group ">
                                 <label><?php echo __('label.organization'); ?>:<span class="_required">*</span></label>
                                <select class="form-control _ledger_organization_id" name="organization_id" required >

                                   
                                   <?php $__empty_1 = true; $__currentLoopData = $permited_organizations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                   <option value="<?php echo e($val->id); ?>" <?php if(isset($request->organization_id)): ?> <?php if($request->organization_id == $val->id): ?> selected <?php endif; ?>   <?php endif; ?>><?php echo e($val->id ?? ''); ?> - <?php echo e($val->_name ?? ''); ?></option>
                                   <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                   <?php endif; ?>
                                 </select>
                             </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-2">
                            <div class="form-group ">
                                <label>Branch:<span class="_required">*</span></label>
                               <select class="form-control" name="_branch_id" required >
                                  
                                  <?php $__empty_1 = true; $__currentLoopData = $branchs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $branch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                  <option value="<?php echo e($branch->id); ?>" <?php if(isset($request->_branch_id)): ?> <?php if($request->_branch_id == $branch->id): ?> selected <?php endif; ?>   <?php endif; ?>><?php echo e($branch->id ?? ''); ?> - <?php echo e($branch->_name ?? ''); ?></option>
                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                  <?php endif; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-2 <?php if(sizeof($permited_costcenters)==1): ?> display_none <?php endif; ?>">
                         <div class="form-group ">
                             <label>Cost Center:<span class="_required">*</span></label>
                            <select class="form-control _ledger_cost_center_id" name="_cost_center_id" required >
                               
                               <?php $__empty_1 = true; $__currentLoopData = $permited_costcenters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cost_center): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                               <option value="<?php echo e($cost_center->id); ?>" <?php if(isset($request->_cost_center_id)): ?> <?php if($request->_cost_center_id == $cost_center->id): ?> selected <?php endif; ?>   <?php endif; ?>><?php echo e($cost_center->id ?? ''); ?> - <?php echo e($cost_center->_name ?? ''); ?></option>
                               <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                               <?php endif; ?>
                             </select>
                         </div>
                        </div>
                       
                        <div class="col-xs-12 col-sm-12 col-md-6">
                            <div class="form-group">
                                <label>Ledger Name:<span class="_required">*</span></label>
                                
                                <input type="text" name="_name" class="form-control" value="<?php echo e(old('_name')); ?>" placeholder="Ledger Name" required>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-6">
                            <div class="form-group">
                                <label>Address:</label>
                                
                                
                                <textarea name="_address" class="form-control" placeholder="Address"><?php echo e(old('_address')); ?></textarea>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-6">
                            <div class="form-group">
                                <label>Details:</label>
                                <textarea name="_note" class="form-control" placeholder="Details"></textarea>
                               
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-6">
                            <div class="form-group">
                                <label>Proprietor:</label>
                                <input type="text" name="_alious" class="form-control" value="<?php echo e(old('_alious')); ?>" placeholder="Proprietor">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-3">
                            <div class="form-group">
                                <label>Email:</label>
                                <input type="email" name="_email" class="form-control" value="<?php echo e(old('_email')); ?>" placeholder="Email" >
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-3">
                            <div class="form-group">
                                <label>Phone:</label>
                                <input type="text" name="_phone" class="form-control" value="<?php echo e(old('_phone')); ?>" placeholder="Phone" >
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-2">
                            <div class="form-group">
                                <label>Code:</label>
                                <input type="text" name="_code" class="form-control" value="<?php echo e(old('_code')); ?>" placeholder="CODE Number">
                            </div>
                        </div>
                        
                        <div class="col-xs-12 col-sm-12 col-md-2 display_none">
                            <div class="form-group">
                                <label>Display Possition:</label>
                                <?php echo Form::text('_short', null, array('placeholder' => 'Possition','class' => 'form-control')); ?>

                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-2">
                            <div class="form-group">
                                <label>NID Number:</label>
                               <input type="text" name="_nid" class="form-control" value="<?php echo e(old('_nid')); ?>" placeholder="NID Number">
                            </div>
                        </div>
                        
                        <div class="col-xs-12 col-sm-12 col-md-2">
                            <div class="form-group">
                                <label>Credit Limit:</label>
                                <input type="number" step="any" name="_credit_limit" class="form-control" value="<?php echo e(old('_credit_limit',0)); ?>" placeholder="Credit Limit" >
                            </div>
                        </div>
                       
                        <div class="col-xs-12 col-sm-12 col-md-2 display_none">
                            <div class="form-group">
                                <label>Is User:</label>
                                <select class="form-control" name="_is_user">
                                  <?php $__currentLoopData = yes_nos(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$s_val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                  <option value="<?php echo e($key); ?>"><?php echo e($s_val); ?></option>
                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-2 display_none">
                            <div class="form-group">
                                <label>Sales Form:</label>
                                <select class="form-control" name="_is_sales_form">
                                  <?php $__currentLoopData = yes_nos(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$s_val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                  <option value="<?php echo e($key); ?>"><?php echo e($s_val); ?></option>
                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-2 display_none">
                            <div class="form-group">
                                <label>Is Purchase Form:</label>
                                <select class="form-control" name="_is_purchase_form">
                                  <?php $__currentLoopData = yes_nos(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$s_val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                  <option value="<?php echo e($key); ?>"><?php echo e($s_val); ?></option>
                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-2 display_none">
                            <div class="form-group">
                                <label>Search For All Branch:</label>
                                <select class="form-control" name="_is_all_branch">
                                  <?php $__currentLoopData = yes_nos(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$s_val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                  <option value="<?php echo e($key); ?>"><?php echo e($s_val); ?></option>
                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-2">
                            <div class="form-group">
                                <label>Opening Dr Amount:</label>
                                <input id="opening_dr_amount" type="number" name="opening_dr_amount" class="form-control" placeholder="Dr Amount" value="0" step="any" min="0">
                            </div>
                        </div>
                         <div class="col-xs-12 col-sm-12 col-md-2">
                            <div class="form-group">
                                <label>Opening Cr Amount:</label>
                                <input id="opening_cr_amount" type="number" name="opening_cr_amount" class="form-control" placeholder="Cr Amount" value="0" step="any" min="0">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-2">
                            <div class="form-group">
                                <label>Status:</label>
                                <select class="form-control" name="_status">
                                  <?php $__currentLoopData = common_status(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$s_val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                  <option value="<?php echo e($key); ?>"><?php echo e($s_val); ?></option>
                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                         
                         
                       
                       
                       <div class="col-xs-12 col-sm-12 col-md-12 bottom_save_section text-middle">
                            <button type="submit" class="btn btn-success  ml-5"><i class="fa fa-credit-card mr-2" aria-hidden="true"></i> Save</button>
                           
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


<?php /**PATH D:\xampp\htdocs\own\inv-acc-hrm\resources\views/backend/account-ledger/create.blade.php ENDPATH**/ ?>