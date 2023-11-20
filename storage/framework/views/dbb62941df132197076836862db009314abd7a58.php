
<?php $__env->startSection('title',$page_name ?? ''); ?>
<?php $__env->startSection('content'); ?>
<div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 _page_name"><?php echo $page_name ?? ''; ?> </h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('item-information-list')): ?>
              <li class="breadcrumb-item active">
                 <a class="btn btn-info" href="<?php echo e(route('item-information.index')); ?>"> <i class="fa fa-th-list" aria-hidden="true"></i></a>
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
            <table class="table">
                <thead>
                    <tr>
                        <th>Employee ID:</th>
                        <th class="text-left"><?php echo $data->_employee->_code ?? ''; ?></th>
                        <th>Division:</th>
                        <th class="text-left"><?php echo $data->_employee->_organization->_name ?? ''; ?></th>
                    </tr>
                    <tr>
                        <th>Employee Name:</th>
                        <th class="text-left"><?php echo $data->_employee->_name ?? ''; ?></th>
                        <th>Branch:</th>
                        <th class="text-left"><?php echo $data->_employee->_branch->_name ?? ''; ?></th>
                    </tr>
                    <tr>
                        <th>DOJ:</th>
                        <th class="text-left"><?php echo $data->_employee->_doj ?? ''; ?></th>
                        <th>Section:</th>
                        <th class="text-left"></th>
                    </tr>
                    <tr>
                        <th>Designation:</th>
                        <th class="text-left"><?php echo $data->_employee->_emp_designation->_name ?? ''; ?></th>
                        <th>Location:</th>
                        <th class="text-left"><?php echo $data->_employee->_emp_location->_name ?? ''; ?></th>
                    </tr>
                    <tr>
                        <th>Grade:</th>
                        <th class="text-left"><?php echo $data->_employee->_emp_grade->_name ?? ''; ?></th>
                        <th>Category:</th>
                        <th class="text-left"><?php echo $data->_employee->_employee_cat->_name ?? ''; ?></th>
                    </tr>
                    <tr>
                        <th>Department:</th>
                        <th class="text-left"><?php echo $data->_employee->_emp_department->_name ?? ''; ?></th>
                        <th>Job Type:</th>
                        <th class="text-left"></th>
                    </tr>

                        
                </thead>
            </table>
            <?php
    $previous_detail = $data->_details ?? [];
?>
            <div class="row">
                    <?php $__empty_1 = true; $__currentLoopData = $payheads; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p_key=>$p_val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="col-md-4 ">
                        <h3><?php echo $p_key ?? ''; ?></h3>
                        <?php if(sizeof($p_val) > 0): ?>
                            <?php $__empty_2 = true; $__currentLoopData = $p_val; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $l_val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                            <?php
                            //dump($l_val);
                            ?>
                            <div class="form-group row ">
                            <label class="col-sm-6 col-form-label" for="_item"><?php echo e($l_val->_ledger ?? ''); ?>:</label>
                             <div class="col-sm-6">
                                 <?php if($l_val->_payhead_type->cal_type==2): ?>  <?php endif; ?>
                               <?php $__empty_3 = true; $__currentLoopData = $previous_detail; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p_val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_3 = false; ?>
                                <?php if($p_val->_payhead_id==$l_val->id): ?>
                               <?php echo e($p_val->_amount ?? 0); ?>

                               <?php endif; ?>
                              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_3): ?>
                              <?php endif; ?>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                        <?php endif; ?>
                        <?php endif; ?>
                    </div>

                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <?php endif; ?>
                    
                </div>
                <div class="form-group row pt-2">
                        <label class="col-sm-2 col-form-label" >Total Earnings:</label>
                         <div class="col-sm-6"><b><?php echo e($data->total_earnings ?? 0); ?></b>
                        </div>
                    </div>
                    <div class="form-group row pt-2">
                        <label class="col-sm-2 col-form-label" >Total Deduction:</label>
                         <div class="col-sm-6">
                           <?php echo e($data->total_deduction ?? 0); ?>

                        </div>
                    </div>
                    <div class="form-group row pt-2">
                        <label class="col-sm-2 col-form-label" >Net Total Salary:</label>
                         <div class="col-sm-6"><?php echo e($data->net_total_earning ?? 0); ?></div>
                        </div>
                    </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\own\inv-acc-hrm\resources\views/hrm/initial-salary-structure/show.blade.php ENDPATH**/ ?>