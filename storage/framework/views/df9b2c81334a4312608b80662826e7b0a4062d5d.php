
<?php $__env->startSection('title',$page_name ?? ''); ?>
<?php $__env->startSection('content'); ?>
<div class="content-header">
      <div class="container-fluid">

        <div class="col-sm-12" style="display: flex;">
             <a class="m-0 _page_name" href="<?php echo e(route('item-type.index')); ?>"> <?php echo $page_name ?? ''; ?> </a>
            <ol class="breadcrumb float-sm-right ml-2">
               <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('item-type-create')): ?>

              <li class="breadcrumb-item active">
                <a  
               class="btn btn-sm btn-info active " 
               href="<?php echo e(route('item-type.create')); ?>">
                   <i class="nav-icon fas fa-plus"></i> Create New
                </a>
               </li>
              <?php endif; ?>
            </ol>
          </div>
         


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
              <div class="card-header border-0">
                 <?php echo $__env->make('backend.item-type.search', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
              </div>
              <div class="card-body">
                <div class="d-flex">
                  <table class="table table-bordered _list_table">
                      <thead>
                        <tr>
                        <th><?php echo e(__('label.sl')); ?></th>
                         <th><?php echo e(__('label._action')); ?></th>
                        <th><?php echo e(__('label.id')); ?></th>
                         <th><?php echo e(__('label._name')); ?></th>
                         <th><?php echo e(__('label._code')); ?></th>
                         <th><?php echo e(__('label._detail')); ?></th>
                         <th><?php echo e(__('label._status')); ?></th>
                         </tr>
                      </thead>
                      <tbody>
                        
                      
                        <?php $__currentLoopData = $datas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e(($key+1)); ?></td>
                            <td style="display: flex;">
                           
                                <a   
                                  href="<?php echo e(route('item-type.show',$data->id)); ?>"
                                  class="btn btn-sm btn-default  mr-1"><i class="fa fa-eye"></i></a>


                                  <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('item-type-edit')): ?>
                                  <a   href="<?php echo e(route('item-type.edit',$data->id)); ?>"
                                  class="btn btn-sm btn-default  mr-1"><i class="fa fa-pen "></i></a>

                                    
                                  <?php endif; ?>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('item-type-delete')): ?>
                                 <?php echo Form::open(['method' => 'DELETE','route' => ['item-type.destroy', $data->id],'style'=>'display:inline']); ?>

                                      <button onclick="return confirm('Are you sure?')" type="submit" class="btn btn-sm btn-default"><i class="fa fa-trash _required"></i></button>
                                  <?php echo Form::close(); ?>

                               <?php endif; ?>  
                               
                        </td>
                          
                             
                            <td><?php echo e($data->id); ?></td>
                            <td> <?php echo e($data->_name ?? ''); ?></td>
                            <td> <?php echo e($data->_code ?? ''); ?></td>
                            <td> <?php echo e($data->_detail ?? ''); ?></td>
                            <td> <?php echo e(selected_status($data->_status ?? 0)); ?></td>
                            
                           
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
<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\own\inv-acc-hrm\resources\views/backend/item-type/index.blade.php ENDPATH**/ ?>