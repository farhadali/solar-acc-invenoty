
<?php $__env->startSection('title',$settings->title); ?>

<?php $__env->startSection('content'); ?>
<!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="col-sm-12" style="display: flex;">
             <a class="m-0 _page_name" href="<?php echo e(route('users.index')); ?>">User Management </a>
            <ol class="breadcrumb float-sm-right ml-2">
               <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('user-create')): ?>
              <li class="breadcrumb-item active">
                <button type="button" 
               class="btn btn-sm btn-info active attr_base_create_url" 
               data-toggle="modal" 
               data-target="#commonEntryModal_item" 
               attr_base_create_url="<?php echo e(route('users.create')); ?>">
                   <i class="nav-icon fas fa-plus"></i> Create New
                </button>
                  
               </li>
              <?php endif; ?>
            </ol>
          </div>

        
      </div><!-- /.container-fluid -->
    </div>
     <?php echo $__env->make('backend.message.message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <!-- /.content-header -->
<div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-12">
            <div class="card">
              <div class="card-header border-0">
                <?php echo $__env->make('users.search', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
              </div>
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table table-bordered _list_table">
                     <thead>
                       <tr>
                       <th>No</th>
                       <th class="">Action</th>
                       <th>Name</th>
                       <th>Email</th>
                       <th>Roles</th>
                       <th>Branch</th>
                       <th>Cost Center</th>
                       <th>Status</th>
                     </tr>
                     </thead>
                     <tbody>
                     <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                      <tr>

                        <td><?php echo e($key+1); ?></td>
                         <td style="display: flex;">
                           
                                <button  type="button" 
                                  attr_base_edit_url="<?php echo e(route('users.show',$user->id)); ?>"
                                  data-toggle="modal" 
                                  data-target="#commonEntryModal_item" 
                                  class="btn btn-sm btn-default attr_base_edit_url mr-1"><i class="fa fa-eye"></i></button>


                                  <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('user-edit')): ?>
                                  <button  type="button" 
                                  attr_base_edit_url="<?php echo e(route('users.edit',$user->id)); ?>"
                                  data-toggle="modal" 
                                  data-target="#commonEntryModal_item" 
                                  class="btn btn-sm btn-default attr_base_edit_url mr-1"><i class="fa fa-pen "></i></button>

                                    
                                  <?php endif; ?>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('user-delete')): ?>
                                 <?php echo Form::open(['method' => 'DELETE','route' => ['users.destroy', $user->id],'style'=>'display:inline']); ?>

                                      <button onclick="return confirm('Are you sure?')" type="submit" class="btn btn-sm btn-default"><i class="fa fa-trash _required"></i></button>
                                  <?php echo Form::close(); ?>

                               <?php endif; ?>  
                               
                        </td>

                              
                        
                        <td><?php echo e($user->id); ?> - <?php echo e($user->name); ?></td>
                        <td><?php echo e($user->email); ?></td>
                        <td>
                          <?php if(!empty($user->getRoleNames())): ?>
                            <?php $__currentLoopData = $user->getRoleNames(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                               <label class="badge badge-success"><?php echo e($v); ?></label>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                          <?php endif; ?>
                        </td>
                        <td>
                         <?php
                            $selected_branchs=[];
                            if($user->branch_ids !=0){
                                 $selected_branchs =  explode(",",$user->branch_ids);
                            }
                          ?>
                          <?php $__empty_1 = true; $__currentLoopData = $branchs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $branch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                              <?php if(in_array($branch->id,$selected_branchs)): ?> <label class="badge badge-info"><?php echo e($branch->_name); ?></label> <?php endif; ?>
                              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                              <?php endif; ?>


                        </td>
                        <td>
                         <?php
                            $selected_costcenters=[];
                            if($user->cost_center_ids !=0){
                                 $selected_costcenters =  explode(",",$user->cost_center_ids);
                            }
                          ?>
                          <?php $__empty_1 = true; $__currentLoopData = $cost_centers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $costcenter): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                              <?php if(in_array($costcenter->id,$selected_costcenters)): ?> <label class="badge badge-info"><?php echo e($costcenter->_name); ?></label> <?php endif; ?>
                              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                              <?php endif; ?>


                        </td>
                        <td>
                          
                          <?php echo e(($user->status==1) ? 'Active' : 'In Active'); ?></td>
                       
                      </tr>
                     <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                     </tbody>
                    </table>
                </div>
                <!-- /.d-flex -->

 

                

                <div class="d-flex flex-row justify-content-end">
                  <?php echo $data->render(); ?>

                </div>
              </div>
            </div>
            <!-- /.card -->

            
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\own\sabuz-bhai\sspf.sobuzsathitraders.com\sspf.sobuzsathitraders.com\resources\views/users/index.blade.php ENDPATH**/ ?>