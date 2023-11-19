 <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('hrm-module')): ?> 
      <li class="nav-item dropdown remove_from_header display_none">
        <a class="nav-link" data-toggle="dropdown" href="#">
           <?php echo e(__('label.hrm')); ?> <i class="right fas fa-angle-down"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
       <p style="padding-left: 20px;"><b><?php echo e(__('label.leave')); ?></b></p>
       <div class="dropdown-divider"></div>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('week-work-day')): ?>
        <div style="display: flex;">
         <a href="<?php echo e(url('weekworkday')); ?>" class="dropdown-item">
            <i class="fa fa-fax mr-2" aria-hidden="true"></i> <?php echo e(__('label.weekworkday')); ?>

          </a>
        </div>
        <?php endif; ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('holidays-list')): ?>
        <div style="display: flex;">
         <a href="<?php echo e(url('holidays')); ?>" class="dropdown-item">
            <i class="fa fa-fax mr-2" aria-hidden="true"></i> <?php echo e(__('label.holidays')); ?>

          </a>
        </div>
        <?php endif; ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('leave-type-list')): ?>
        <div style="display: flex;">
         <a href="<?php echo e(url('leave-type')); ?>" class="dropdown-item">
            <i class="fa fa-fax mr-2" aria-hidden="true"></i> <?php echo e(__('label.leave-type')); ?>

          </a>
          <a  href="<?php echo e(route('leave-type.create')); ?>" class="dropdown-item text-right">
            <i class="nav-icon fas fa-plus"></i>
          </a>
        </div>
        <?php endif; ?>

        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('hrm-employee-list')): ?>
        <p style="padding-left: 20px;"><b><?php echo e(__('label.hrm-employee')); ?></b></p>
       <div class="dropdown-divider"></div>
        <div style="display: flex;">
         <a href="<?php echo e(url('hrm-employee')); ?>" class="dropdown-item">
            <i class="fa fa-fax mr-2" aria-hidden="true"></i> <?php echo e(__('label.hrm-employee')); ?>

          </a>
          <a  href="<?php echo e(route('hrm-employee.create')); ?>" class="dropdown-item text-right">
            <i class="nav-icon fas fa-plus"></i>
          </a>
        </div>
        <?php endif; ?>
        <p style="padding-left: 20px;"><b><?php echo e(__('label.payrol-information')); ?></b></p>
       <div class="dropdown-divider"></div>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('initial-salary-structure-list')): ?>
        <div style="display: flex;">
         <a href="<?php echo e(url('initial-salary-structure')); ?>" class="dropdown-item">
            <i class="fa fa-fax mr-2" aria-hidden="true"></i> <?php echo e(__('label.initial-salary-structure')); ?>

          </a>
          <a  href="<?php echo e(route('initial-salary-structure.create')); ?>" class="dropdown-item text-right">
            <i class="nav-icon fas fa-plus"></i>
          </a>
        </div>
        <?php endif; ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('pay-heads-list')): ?>
        <div style="display: flex;">
         <a href="<?php echo e(url('pay-heads')); ?>" class="dropdown-item">
            <i class="fa fa-fax mr-2" aria-hidden="true"></i> <?php echo e(__('label.pay-heads')); ?>

          </a>
          <a  href="<?php echo e(route('pay-heads.create')); ?>" class="dropdown-item text-right">
            <i class="nav-icon fas fa-plus"></i>
          </a>
        </div>
        <?php endif; ?>
        
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('hrm-department-list')): ?>
        <div style="display: flex;">
         <a href="<?php echo e(url('hrm-department')); ?>" class="dropdown-item">
            <i class="fa fa-fax mr-2" aria-hidden="true"></i> <?php echo e(__('label.hrm-department')); ?>

          </a>
          <a  href="<?php echo e(route('hrm-department.create')); ?>" class="dropdown-item text-right">
            <i class="nav-icon fas fa-plus"></i>
          </a>
        </div>
        <?php endif; ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('hrm-grade-list')): ?>
        <div style="display: flex;">
         <a href="<?php echo e(url('hrm-grade')); ?>" class="dropdown-item">
            <i class="fa fa-fax mr-2" aria-hidden="true"></i> <?php echo e(__('label.hrm-grade')); ?>

          </a>
          <a  href="<?php echo e(route('hrm-grade.create')); ?>" class="dropdown-item text-right">
            <i class="nav-icon fas fa-plus"></i>
          </a>
        </div>
        <?php endif; ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('hrm-emp-location-list')): ?>
        <div style="display: flex;">
         <a href="<?php echo e(url('hrm-emp-location')); ?>" class="dropdown-item">
            <i class="fa fa-fax mr-2" aria-hidden="true"></i> <?php echo e(__('label.hrm-emp-location')); ?>

          </a>
          <a  href="<?php echo e(route('hrm-emp-location.create')); ?>" class="dropdown-item text-right">
            <i class="nav-icon fas fa-plus"></i>
          </a>
        </div>
        <?php endif; ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('hrm-emp-category-list')): ?>
        <div style="display: flex;">
         <a href="<?php echo e(url('hrm-emp-category')); ?>" class="dropdown-item">
            <i class="fa fa-fax mr-2" aria-hidden="true"></i> <?php echo e(__('label.hrm-emp-category')); ?>

          </a>
          <a  href="<?php echo e(route('hrm-emp-category.create')); ?>" class="dropdown-item text-right">
            <i class="nav-icon fas fa-plus"></i>
          </a>
        </div>
        <?php endif; ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('hrm-designation-list')): ?>
        <div style="display: flex;">
         <a href="<?php echo e(url('hrm-designation')); ?>" class="dropdown-item">
            <i class="fa fa-fax mr-2" aria-hidden="true"></i> <?php echo e(__('label.hrm-designation')); ?>

          </a>
          <a  href="<?php echo e(route('hrm-designation.create')); ?>" class="dropdown-item text-right">
            <i class="nav-icon fas fa-plus"></i>
          </a>
        </div>
        <?php endif; ?>
        
      </li>
    <?php endif; ?>
      <?php /**PATH D:\xampp\htdocs\own\inv-acc-hrm\resources\views/backend/layouts/hrm_module.blade.php ENDPATH**/ ?>