
<?php $__env->startSection('title',$settings->title); ?>

<?php $__env->startSection('content'); ?>
<!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="col-sm-12" style="display: flex;">
             <a class="m-0 _page_name" href="<?php echo e(route('project_management.index')); ?>"><?php echo e($page_name); ?></a>
            <ol class="breadcrumb float-sm-right ml-2">
               <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('user-create')): ?>
              <li class="breadcrumb-item active">
                <a 
               class="btn btn-sm btn-info active " 
               
               href="<?php echo e(route('project_management.create')); ?>">
                   <i class="nav-icon fas fa-plus"></i> Create New
                </a>
                  
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
                
              </div>
              <div class="card-body">
                <div class="">
                  <table class="table table-bordered _list_table">
                     <thead>
                       <tr>
                       <th>No</th>
                       <th class="">Action</th>
                       <th>Name</th>
                       <th>Address</th>
                       <th>Status</th>
                     
                     </tr>
                     </thead>
                     <tbody>
                     <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                      <tr>

                        <td><?php echo e($key+1); ?></td>
                        <td><?php echo e($val->id); ?> - <?php echo $val->project_name ?? ''; ?></td>
                        <td><?php echo $val->project_addess ?? ''; ?></td>
                        
                        <td>
                          
                          <?php echo e(($val->status==1) ? 'Active' : 'In Active'); ?></td>
                       
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
<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\xampp\htdocs\solar-acc-invenoty\resources\views/pm/project_management/index.blade.php ENDPATH**/ ?>