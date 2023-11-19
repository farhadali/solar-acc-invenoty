
<?php $__env->startSection('title',$page_name); ?>

<?php $__env->startSection('content'); ?>
<div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-12" style="display: flex;">
            <a class="m-0 _page_name" href="<?php echo e(route('initial-salary-structure.index')); ?>"><?php echo $page_name ?? ''; ?> </a>
            <ol class="breadcrumb float-sm-right ml-2">
               <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('initial-salary-structure-create')): ?>
                <a 
               class="btn btn-sm btn-info active " 
               
               href="<?php echo e(route('initial-salary-structure.create')); ?>">
                   <i class="nav-icon fas fa-plus"></i> Create New
                </a>
              
              <?php endif; ?>
            </ol>
          </div>
          
        </div><!-- /.row -->
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
              <div class="card-header border-0 mt-1">
                 

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
                      <?php echo $__env->make('hrm.initial-salary-structure.search', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    </div>
                    <div class="col-md-8">
                      <div class="d-flex flex-row justify-content-end">
                           
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
                         
                         <th><?php echo e(__('Action')); ?></th>
                         <th><?php echo e(__('SL')); ?></th>
                         <th><?php echo e(__('EMP ID')); ?></th>
                         <th><?php echo e(__('label.Employee Name')); ?></th>
                         <th><?php echo e(__('Gross Earnings')); ?></th>
                         <th><?php echo e(__('Gross Deduction')); ?></th>
                         <th><?php echo e(__('Net Deduction')); ?></th>
                         <th><?php echo e(__('Created At')); ?></th>
                         <th><?php echo e(__('Updated At')); ?></th>
                         <th><?php echo e(__('label._status')); ?></th>
                         <?php
                         $default_image = $settings->logo;
                         ?>           
                      </tr>
                      </thead>
                      <tbody>
                        <?php $__currentLoopData = $datas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                          <td style="display: flex;">
                           
                                <a   
                                  href="<?php echo e(route('initial-salary-structure.show',$data->id)); ?>"
                                  
                                  class="btn btn-sm btn-default  mr-1"><i class="fa fa-eye"></i></a>


                                  <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('initial-salary-structure-edit')): ?>
                                  <a   
                                  href="<?php echo e(route('initial-salary-structure.edit',$data->id)); ?>"
                                  
                                  class="btn btn-sm btn-default  mr-1"><i class="fa fa-pen "></i></a>

                                    
                                  <?php endif; ?>
                                
                               
                        </td>

                             
                            
                            <td><?php echo e(($key+1)); ?></td>
                            <td><?php echo e($data->_emp_code ?? ''); ?></td>
                            <td><?php echo e($data->_employee->_name ?? ''); ?></td>
                            <td><?php echo e(_report_amount($data->total_earnings ?? 0)); ?></td>
                            <td><?php echo e(_report_amount($data->total_deduction ?? 0)); ?></td>
                            <td><?php echo e(_report_amount($data->net_total_earning ?? 0)); ?></td>
                            
                            <td><?php echo e($data->created_at ?? ''); ?></td>
                            <td><?php echo e($data->updated_at ?? ''); ?></td>
                           <td><?php echo e(selected_status($data->_status)); ?></td>
                           
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


<?php $__env->startSection("script"); ?>


<?php $__env->stopSection(); ?>
<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\own\inv-acc-hrm\resources\views/hrm/initial-salary-structure/index.blade.php ENDPATH**/ ?>