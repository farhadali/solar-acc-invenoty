
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
               
                 <form action="<?php echo e(url('account-ledger/update')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="row">
                      <input type="hidden" name="id" class="form-control" value="<?php echo e($data->id); ?>" >
                       <div class="col-xs-12 col-sm-12 col-md-6">
                            <div class="form-group">
                                <label>Account Type: <span class="_required">*</span></label>
                               <select type_base_group="<?php echo e(url('type_base_group')); ?>"  class="form-control _account_head_id select2" name="_account_head_id" required>
                                  <option value="">--Select Account Type--</option>
                                  <?php $__empty_1 = true; $__currentLoopData = $account_types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $account_type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                  <option value="<?php echo e($account_type->id); ?>" <?php if(isset($data->_account_head_id)): ?> <?php if($data->_account_head_id == $account_type->id): ?> selected <?php endif; ?>   <?php endif; ?>><?php echo e($account_type->id ?? ''); ?>-<?php echo e($account_type->_name ?? ''); ?></option>
                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                  <?php endif; ?>
                                </select>
                            </div>
                        </div>
                       <div class="col-xs-12 col-sm-12 col-md-6">
                            <div class="form-group ">
                                <label>Account Group:<span class="_required">*</span></label>
                               <select class="form-control _account_groups select2" name="_account_group_id" required>
                                  <option value="">--Select Account Group--</option>
                                  <?php $__empty_1 = true; $__currentLoopData = $account_groups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $account_group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                  <option value="<?php echo e($account_group->id); ?>" <?php if(isset($data->_account_group_id)): ?> <?php if($data->_account_group_id == $account_group->id): ?> selected <?php endif; ?>   <?php endif; ?>><?php echo e($account_group->id ?? ''); ?> - <?php echo e($account_group->_name ?? ''); ?></option>
                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                  <?php endif; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-6">
                            <div class="form-group ">
                                <label>Branch:<span class="_required">*</span></label>
                               <select class="form-control" name="_branch_id" required >
                                  
                                  <?php $__empty_1 = true; $__currentLoopData = $branchs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $branch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                  <option value="<?php echo e($branch->id); ?>" <?php if(isset($data->_branch_id)): ?> <?php if($data->_branch_id == $branch->id): ?> selected <?php endif; ?>   <?php endif; ?>><?php echo e($branch->id ?? ''); ?> - <?php echo e($branch->_name ?? ''); ?></option>
                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                  <?php endif; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-6">
                            <div class="form-group">
                                <label>Ledger Name:<span class="_required">*</span></label>
                                
                                <input type="text" name="_name" class="form-control" value="<?php echo e(old('_name',$data->_name)); ?>" placeholder="Ledger Name" required>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-6">
                            <div class="form-group">
                                <label>Address:</label>
                                
                                <textarea name="_address" class="form-control" placeholder="Address"><?php echo e(old('_address',$data->_address)); ?></textarea>
                                
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-6">
                            <div class="form-group">
                                <label>Details:</label>
                               
                                <textarea name="_note" class="form-control" placeholder="Details"><?php echo e($data->_note ?? ''); ?></textarea>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-6">
                            <div class="form-group">
                                <label>Proprietor:</label>
                                <input type="text" name="_alious" class="form-control" value="<?php echo e(old('_alious',$data->_alious)); ?>" placeholder="Proprietor">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-3">
                            <div class="form-group">
                                <label>Code:</label>
                                <input type="text" name="_code" class="form-control" value="<?php echo e(old('_code',$data->_code)); ?>" placeholder="CODE Number">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-3">
                            <div class="form-group">
                                <label>Email:</label>
                                <input type="email" name="_email" class="form-control" value="<?php echo e(old('_email',$data->_email)); ?>" placeholder="Email" >
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-2">
                            <div class="form-group">
                                <label>Phone:</label>
                                <input type="text" name="_phone" class="form-control" value="<?php echo e(old('_phone',$data->_phone)); ?>" placeholder="Phone" >
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-2">
                            <div class="form-group">
                                <label>NID Number:</label>
                               <input type="text" name="_nid" class="form-control" value="<?php echo e(old('_nid',$data->_nid)); ?>" placeholder="NID Number">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-2">
                            <div class="form-group">
                                <label>Credit Limit:</label>
                                <input type="number" step="any" name="_credit_limit" class="form-control" value="<?php echo e(old('_credit_limit',$data->_credit_limit)); ?>" placeholder="Credit Limit" >
                            </div>
                        </div>
                         <div class="col-xs-12 col-sm-12 col-md-2 display_none">
                            <div class="form-group">
                                <label>Display Possition:</label>
                                <?php echo Form::text('_short', $data->_short, array('placeholder' => 'Possition','class' => 'form-control')); ?>

                            </div>
                        </div>
                        
                        
                        
                       
                        <div class="col-xs-12 col-sm-12 col-md-2 display_none">
                            <div class="form-group">
                                <label>Is User:</label>
                                <select class="form-control" name="_is_user">
                                  <?php $__currentLoopData = yes_nos(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$s_val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                  <option value="<?php echo e($key); ?>" <?php if($key==$data->_is_user): ?> selected <?php endif; ?> ><?php echo e($s_val); ?></option>
                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-2 display_none">
                            <div class="form-group">
                                <label>Sales Form:</label>
                                <select class="form-control" name="_is_sales_form">
                                  <?php $__currentLoopData = yes_nos(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$s_val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                  <option value="<?php echo e($key); ?>" <?php if($key==$data->_is_sales_form): ?> selected <?php endif; ?>><?php echo e($s_val); ?></option>
                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-2 display_none">
                            <div class="form-group">
                                <label>Is Purchase Form:</label>
                                <select class="form-control" name="_is_purchase_form">
                                  <?php $__currentLoopData = yes_nos(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$s_val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                  <option value="<?php echo e($key); ?>"  <?php if($key==$data->_is_purchase_form): ?> selected <?php endif; ?> ><?php echo e($s_val); ?></option>
                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-2 display_none">
                            <div class="form-group">
                                <label>Search For All Branch:</label>
                                <select class="form-control" name="_is_all_branch">
                                  <?php $__currentLoopData = yes_nos(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$s_val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                  <option value="<?php echo e($key); ?>" <?php if($key==$data->_is_all_branch): ?> selected <?php endif; ?> ><?php echo e($s_val); ?></option>
                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-2">
                            <div class="form-group">
                                <label>Status:</label>
                                <select class="form-control" name="_status">
                                  <?php $__currentLoopData = common_status(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$s_val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                  <option value="<?php echo e($key); ?>" <?php if($key==$data->_status): ?> selected <?php endif; ?>  ><?php echo e($s_val); ?></option>
                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        
                       
                       
                       <div class="col-xs-12 col-sm-12 col-md-12 bottom_save_section text-middle">
                            <button type="submit" class="btn btn-success  ml-5"><i class="fa fa-credit-card mr-2" aria-hidden="true"></i> Save</button>
                           
                        </div>
                        <br><br>
                    </div>
                    </form>
                
              </div>
            </div>
            <!-- /.card -->

            
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </div>
</div>

<?php /**PATH D:\xampp\htdocs\own\inv-acc-hrm\resources\views/backend/account-ledger/edit.blade.php ENDPATH**/ ?>