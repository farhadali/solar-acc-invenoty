
<?php $__env->startSection('title',$page_name); ?>

<?php $__env->startSection('content'); ?>
<div class="content-header">
      <div class="container-fluid">
        <div class="col-sm-12" style="display: flex;">
             <a class="m-0 _page_name" href="<?php echo e(route('account-group.index')); ?>"> <?php echo $page_name ?? ''; ?> </a>
            <ol class="breadcrumb float-sm-right ml-2">
               <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('account-group-create')): ?>
              <li class="breadcrumb-item active">
                  
                  <a 
               class="btn btn-sm btn-info active " 
               
               href="<?php echo e(route('account-group.create')); ?>">
                   <i class="nav-icon fas fa-plus"></i> Create New
                </a>
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
                 
                 <div class="row">
                   <?php

                     $currentURL = URL::full();
                     $current = URL::current();
                    if($currentURL === $current){
                       $print_url = $current."?print=single";
                       $print_url_detal = $current."?print=detail";
                    }else{
                         $print_url = $currentURL."&print=single";
                         $print_url_detal = $currentURL."&print=detail";
                    }
    

                   ?>
                    <div class="col-md-4">
                     <?php echo $__env->make('backend.account-group.search', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    </div>
                    <div class="col-md-8">
                      <div class="d-flex flex-row justify-content-end">
                         <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('voucher-print')): ?>
                        <li class="nav-item dropdown remove_from_header">
                              <a class="nav-link" data-toggle="dropdown" href="#">
                                
                                <i class="fa fa-print " aria-hidden="true"></i> <i class="right fas fa-angle-down "></i>
                              </a>
                              <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                               
                                <div class="dropdown-divider"></div>
                                
                                <a target="__blank" href="<?php echo e($print_url); ?>" class="dropdown-item">
                                  <i class="fa fa-print mr-2" aria-hidden="true"></i>Print
                                </a>
                               <div class="dropdown-divider"></div>
                              
                                
                              
                                    
                            </li>
                             <?php endif; ?>   
                         <?php echo $datas->render(); ?>

                          </div>
                    </div>
                  </div>
              </div>
              <div class="card-body">
                <div class="">
                  <table class="table table-bordered _list_table">
                    <thead>
                      <tr>
                        <th>SL</th>
                         <th  style="width: 10%" class="">Action</th>
                         <th  style="width: 5%" class="_no">ID</th>
                         <th style="width: 10%" >Account Type</th>
                         <th style="width: 5%" >Code</th>
                         <th style="width: 20%" >Name</th>
                         <th style="width: 35%" >Details</th>
                         <th style="width: 5%" >Status</th>
                         <th style="width: 5%" >Possition</th>
                      </tr>
                    </thead>
                      <tbody>
                        <?php $__currentLoopData = $datas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                          <td><?php echo e(($key+1)); ?></td>
                          <td style="display: flex;">
                                <a   href="<?php echo e(route('account-group.show',$data->id)); ?>"
                                  class="btn btn-sm btn-default  mr-1"><i class="fa fa-eye"></i></a>
                                  <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('account-group-edit')): ?>
                                  <a   
                                  href="<?php echo e(route('account-group.edit',$data->id)); ?>"
                                  
                                  class="btn btn-sm btn-default  mr-1"><i class="fa fa-pen "></i></a>

                                    
                                  <?php endif; ?>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('account-group-delete')): ?>
                                 <?php echo Form::open(['method' => 'DELETE','route' => ['account-group.destroy', $data->id],'style'=>'display:inline']); ?>

                                      <button onclick="return confirm('Are you sure?')" type="submit" class="btn btn-sm btn-default"><i class="fa fa-trash _required"></i></button>
                                  <?php echo Form::close(); ?>

                               <?php endif; ?>  
                               
                        </td>

                          
                             
                             <td><?php echo e($data->id); ?></td>
                            <td><?php echo e($data->account_type->_name ?? ''); ?></td>
                            <td><?php echo e($data->_code ?? ''); ?></td>
                            <td><?php echo e($data->id ?? ''); ?> - <?php echo e($data->_name ?? ''); ?></td>
                            <td><?php echo e($data->_details ?? ''); ?></td>
                            <td><?php echo e(selected_status($data->_status)); ?></td>
                            <td><?php echo e($data->_short ?? ''); ?></td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                        <tfoot>
                          <tr>
                            <td colspan="8">  <?php echo $datas->render(); ?> </td>
                          </tr>
                        </tfoot>
                    </table>
                </div>
                <!-- /.d-flex -->

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
<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\own\inv-acc-hrm\resources\views/backend/account-group/index.blade.php ENDPATH**/ ?>