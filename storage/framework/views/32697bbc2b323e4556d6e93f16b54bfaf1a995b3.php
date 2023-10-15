
<?php $__env->startSection('title',$page_name); ?>

<?php $__env->startSection('content'); ?>
<div class="content-header">
      <div class="container-fluid">

        <div class="col-sm-12" style="display: flex;">
             <a class="m-0 _page_name" href="<?php echo e(route('budgets.index')); ?>"> <?php echo $page_name ?? ''; ?> </a>
            <ol class="breadcrumb float-sm-right ml-2">
               <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('budgets-create')): ?>
              <li class="breadcrumb-item active">
                <a href="<?php echo e(route('budgets.create')); ?>" class="btn btn-sm btn-info active " ><i class="nav-icon fas fa-plus"></i><?php echo __('label.create_new'); ?></a>
                
                  
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
                 <div class="row">
                     <div class="col-md-4">
                      <?php echo $__env->make('backend.budgets.search', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
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
                                  <i class="fa fa-print mr-2" aria-hidden="true"></i> Print
                                </a>  
                            </li>
                             <?php endif; ?>   
                         <?php echo $datas->render(); ?>

                          </div>
                    </div>
                 </div>
              </div>
              <div class="card-body">
                <div >
                  <table class="table table-bordered _list_table">
                      <thead>
                        <tr>
                         <th class="_no">No</th>
                         <th class="_no"><?php echo e(__('label.action')); ?></th>
                         <th class=""><?php echo e(__('label.organization')); ?></th>
                         <th><?php echo e(__('label.Branch')); ?></th>
                         <th><?php echo e(__('label.Cost center')); ?></th>
                         <th><?php echo e(__('label._start_date')); ?></th>
                         <th><?php echo e(__('label._end_date')); ?></th>
                         <th><?php echo e(__('label._income')); ?></th>
                         <th><?php echo e(__('label._material_expense')); ?></th>
                         <th><?php echo e(__('label._other_expense')); ?></th>
                         <th><?php echo e(__('label._estimated_profit')); ?></th>
                         <th><?php echo e(__('label._created_by')); ?></th>
                         <th><?php echo e(__('label._updated_by')); ?></th>
                         <th><?php echo e(__('label._status')); ?></th>
                      </tr>
                      </thead>
                      <tbody>
                        <?php $__currentLoopData = $datas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($key+1); ?></td>
                          <td style="display: flex;">
                                           
                                <a  type="button"
                                target="__blank" 
                                  href="<?php echo e(route('budgets.show',$data->id)); ?>"
                                  class="btn btn-sm btn-default  mr-1"><i class="fa fa-print"></i></a>
                                  <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('budgets-edit')): ?>
                                  <a href="<?php echo e(route('budgets.edit',$data->id)); ?>"
                                  class="btn btn-sm btn-default  mr-1"><i class="fa fa-pen "></i></a>
                              <?php endif; ?>
                                  <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('budget-compare')): ?>
                                  <a title="<?php echo e(__('label.budget-compare-report')); ?>" href="<?php echo e(url('budget-compare')); ?>?_cost_center_id=<?php echo e($data->_cost_center_id); ?>&_branch_id=<?php echo e($data->_branch_id); ?>&organization_id=<?php echo e($data->organization_id); ?>&_budget_id=<?php echo e($data->id); ?>"
                                  class="btn btn-sm btn-default  mr-1"><i class="fa fa-percent "></i></a>
                              <?php endif; ?>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('budgets-delete')): ?>
                                 <?php echo Form::open(['method' => 'DELETE','route' => ['budgets.destroy', $data->id],'style'=>'display:inline']); ?>

                                      <button onclick="return confirm('Are you sure?')" type="submit" class="btn btn-sm btn-default"><i class="fa fa-trash _required"></i></button>
                                  <?php echo Form::close(); ?>

                               <?php endif; ?>  
                               
                        </td>
                            <td><?php echo e($data->_organization->_name ?? ''); ?></td>
                            <td><?php echo e($data->_master_branch->_name ?? ''); ?></td>
                            <td><?php echo e($data->_master_cost_center->_name ?? ''); ?></td>
                            <td><?php echo e(_view_date_formate($data->_start_date ?? '')); ?></td>
                            <td><?php echo e(_view_date_formate($data->_end_date ?? '')); ?></td>
                            <td><?php echo e(_report_amount($data->_income_amount ?? 0 )); ?></td>
                            <td><?php echo e(_report_amount($data->_material_amount ?? 0 )); ?></td>
                            <td><?php echo e(_report_amount($data->_expense_amount ?? 0 )); ?></td>
                            <td><?php echo e(_report_amount($data->_expense_amount ?? 0 )); ?></td>
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
<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\own\inv-acc-hrm\resources\views/backend/budgets/index.blade.php ENDPATH**/ ?>