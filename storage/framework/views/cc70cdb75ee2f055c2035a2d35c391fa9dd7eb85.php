
<div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <a class="m-0 _page_name" href="<?php echo e(route('users.index')); ?>">User Management </a>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <!-- <li class="breadcrumb-item"><a href="<?php echo e(url('home')); ?>">Home</a></li> -->
              <li class="breadcrumb-item active">
                 <a class="btn btn-primary" href="<?php echo e(route('users.index')); ?>"> User Management</a>
               </li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <div class="message-area">
     <?php echo $__env->make('backend.message.message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-12">
            <div class="card">
             
              <div class="card-body">
               <?php echo Form::model($user, ['method' => 'PATCH','route' => ['users.update', $user->id]]); ?>

                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-6">
                            <div class="form-group">
                                <label>Organization:<span class="_required">*</span></label>
                                <?php
                                $organizations= \DB::table('companies')->where('_status',1)->get();
                                ?>
                                <?php
                                $selected_organization_ids=[];
                                if($user->organization_ids !=0){
                                 $selected_organization_ids =  explode(",",$user->organization_ids);
                                }
                                ?>
                                
                                <select class="form-control" name="organization_ids[]" multiple="" required>
                                  <?php $__empty_1 = true; $__currentLoopData = $organizations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                  <option value="<?php echo e($val->id); ?>" <?php if(in_array($val->id,$selected_organization_ids)): ?> selected <?php endif; ?> ><?php echo e($val->_name ?? ''); ?></option>
                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                  <?php endif; ?>
                                  
                                </select>
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-6">
                            <div class="form-group">
                                <label>Branch:<span class="_required">*</span></label>
                                <?php
                                $selected_branchs=[];
                                if($user->branch_ids !=0){
                                 $selected_branchs =  explode(",",$user->branch_ids);
                                }
                                ?>
                                <select class="form-control" name="branch_ids[]" multiple required>
                                  <?php $__empty_1 = true; $__currentLoopData = $branchs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $branch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                  <option value="<?php echo e($branch->id); ?>" <?php if(in_array($branch->id,$selected_branchs)): ?> selected <?php endif; ?> ><?php echo e($branch->_name ?? ''); ?></option>
                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                  <?php endif; ?>
                                  
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-6">
                            <div class="form-group">
                                <label>Cost Center:<span class="_required">*</span></label>
                                <?php
                                $selected_costcenter=[];
                                if($user->cost_center_ids !=0){
                                 $selected_costcenter =  explode(",",$user->cost_center_ids);
                                }
                                ?>
                                <select class="form-control" name="cost_center_ids[]" multiple required >
                                  <?php $__empty_1 = true; $__currentLoopData = $cost_centers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cost_center): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                  <option value="<?php echo e($cost_center->id); ?>" <?php if(in_array($cost_center->id,$selected_costcenter)): ?> selected <?php endif; ?>  ><?php echo e($cost_center->_name ?? ''); ?></option>
                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                  <?php endif; ?>
                                  
                                </select>
                            </div>
                        </div>
                         <div class="col-xs-12 col-sm-12 col-md-6">
                            <div class="form-group">
                                <label>Store:<span class="_required">*</span></label>
                                <?php
                                $stores= \DB::table('store_houses')->where('_status',1)->get();
                                
                                $selected_stores=[];
                                if($user->store_ids !=0){
                                 $selected_stores =  explode(",",$user->store_ids);
                                }
                                ?>
                                <select class="form-control" name="store_ids[]" multiple="" required>
                                  <?php $__empty_1 = true; $__currentLoopData = $stores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                  <option value="<?php echo e($val->id); ?>" <?php if(in_array($val->id,$selected_stores)): ?> selected <?php endif; ?>  ><?php echo e($val->_name ?? ''); ?></option>
                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                  <?php endif; ?>
                                  
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <label>Name:</label>
                                <?php echo Form::text('name', null, array('placeholder' => 'Name','class' => 'form-control')); ?>

                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <label>Email:</label>
                                <?php echo Form::text('email', null, array('placeholder' => 'Email','class' => 'form-control')); ?>

                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <label>Password:</label>
                                <?php echo Form::password('password', array('placeholder' => 'Password','class' => 'form-control')); ?>

                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <label>Confirm Password:</label>
                                <?php echo Form::password('confirm-password', array('placeholder' => 'Confirm Password','class' => 'form-control')); ?>

                            </div>
                        </div>
                        <?php if(\Auth::user()->user_type=='admin'): ?>
                         <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <label>User Type:</label>
                               <select name="user_type" class="form-control">
                                 <option value="visitor" <?php if($user->user_type=='visitor'): ?> selected <?php endif; ?> >User</option>
                                 <option value="admin"  <?php if($user->user_type=='admin'): ?> selected <?php endif; ?> >Admin</option>
                               </select>
                            </div>
                        </div>
                        <?php endif; ?>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <label>Account Details Type: [If use multiple branch and multiple cost center must use All Ledger Option]</label>
                                <select class="form-control " name="_ac_type">
                                      <option value="0" <?php if($user->_ac_type==0): ?> selected <?php endif; ?> >All Ledger</option>
                                      <option value="1" <?php if($user->_ac_type==1): ?> selected <?php endif; ?> >Only Cash & Bank Ledger</option>
                                    </select>
                            </div>
                        </div>
                         <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <label>Status</label>
                                <select class="form-control " name="status">
                                      <option value="1"  <?php if($user->status==1): ?> selected <?php endif; ?> >Active</option>
                                      <option value="0"  <?php if($user->status==0): ?> selected <?php endif; ?> >In Active</option>
                                    </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 mb-5">
                            <div class="form-group">
                                <label>Role:</label>
                                <?php echo Form::select('roles[]', $roles,$userRole, array('class' => 'form-control','multiple')); ?>

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
<?php /**PATH D:\xampp\htdocs\own\inv-acc-hrm\resources\views/users/edit.blade.php ENDPATH**/ ?>