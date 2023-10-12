
<?php $__env->startSection('title',$page_name); ?>

<?php $__env->startSection('content'); ?>
<div class="content-header">
      <div class="container-fluid">

        <div class="col-sm-12" style="display: flex;">
             <a class="m-0 _page_name" href="<?php echo e(route('branch.index')); ?>"> <?php echo $page_name ?? ''; ?> </a>
            <ol class="breadcrumb float-sm-right ml-2">
               <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('branch-create')): ?>
              <li class="breadcrumb-item active">
                <button type="button" 
       class="btn btn-sm btn-info active attr_base_create_url" 
       data-toggle="modal" data-target="#commonEntryModal_item" 
       attr_base_create_url="<?php echo e(route('branch.create')); ?>">
        <i class="nav-icon fas fa-plus"></i> Create New
       </button>
                  
               </li>
              <?php endif; ?>
            </ol>
          </div>

        
      </div><!-- /.container-fluid -->
    </div>
    <?php if($message = Session::get('success')): ?>
    <div class="alert alert-success">
      <p><?php echo e($message); ?></p>
    </div>
    <?php endif; ?>
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-12">
            <div class="card">
              <div class="card-header border-0">
                 <?php echo $__env->make('backend.branch.search', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
              </div>
              <div class="card-body">
                <div class="d-flex">
                  <table class="table table-bordered _list_table">
                      <thead>
                        <tr>
                         <th class="_no">No</th>
                         <th class="">Action</th>
                         <th>Name</th>
                         <th>Code</th>
                         <th>Address</th>
                         <th>Date</th>
                         <th>Email</th>
                         <th>Phone</th>
                         <th>Created By</th>
                         <th>Updated By</th>
                         <th>Status</th>
                      </tr>
                      </thead>
                      <tbody>
                        <?php $__currentLoopData = $datas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($key+1); ?></td>
 <td style="display: flex;">
                           
                                <button  type="button" 
                                  attr_base_edit_url="<?php echo e(route('branch.show',$data->id)); ?>"
                                  data-toggle="modal" 
                                  data-target="#commonEntryModal_item" 
                                  class="btn btn-sm btn-default attr_base_edit_url mr-1"><i class="fa fa-eye"></i></button>


                                  <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('branch-edit')): ?>
                                  <button  type="button" 
                                  attr_base_edit_url="<?php echo e(route('branch.edit',$data->id)); ?>"
                                  data-toggle="modal" 
                                  data-target="#commonEntryModal_item" 
                                  class="btn btn-sm btn-default attr_base_edit_url mr-1"><i class="fa fa-pen "></i></button>

                                    
                                  <?php endif; ?>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('branch-delete')): ?>
                                 <?php echo Form::open(['method' => 'DELETE','route' => ['branch.destroy', $data->id],'style'=>'display:inline']); ?>

                                      <button onclick="return confirm('Are you sure?')" type="submit" class="btn btn-sm btn-default"><i class="fa fa-trash _required"></i></button>
                                  <?php echo Form::close(); ?>

                               <?php endif; ?>  
                               
                        </td>

                              
                        
                            
                            <td><?php echo e($data->id); ?> - <?php echo e($data->_name); ?></td>
                            <td><?php echo e($data->_code ?? ''); ?></td>
                            <td><?php echo e($data->_address ?? ''); ?></td>
                            <td><?php echo e($data->_date ?? ''); ?></td>
                            <td><?php echo e($data->_email ?? ''); ?></td>
                            <td><?php echo e($data->_phone ?? ''); ?></td>
                            <td><?php echo e($data->_created_by ?? ''); ?></td>
                            <td><?php echo e($data->_updated_by ?? ''); ?></td>
                            <td><?php echo e(($data->_status==1) ? 'Active' : 'In Active'); ?></td>
                           
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
                <!-- /.d-flex -->

                

                <div class="d-flex flex-row justify-content-end">
                 <?php echo $datas->render(); ?>

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
<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\own\inv-acc-hrm\resources\views/backend/branch/index.blade.php ENDPATH**/ ?>