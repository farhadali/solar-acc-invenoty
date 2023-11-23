<li class="nav-item dropdown remove_from_header">
        <a class="nav-link" data-toggle="dropdown" href="#">
          
          <?php echo e(__('label.Settings')); ?> <i class="right fas fa-angle-down"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
         
           <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin-settings')): ?>
          <div class="dropdown-divider"></div>
          <a href="<?php echo e(url('admin-settings')); ?>" class="dropdown-item">
            <i class="fas fa-cog mr-2"></i> <?php echo e(__('label.General Settings')); ?>

          </a>
         <?php endif; ?>
           <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('bulk-sms')): ?>
          <div class="dropdown-divider"></div>
          <a href="<?php echo e(url('sms-send')); ?>" class="dropdown-item">
            <i class="fas fa-cog mr-2"></i> <?php echo e(__('label.SMS SEND')); ?>

          </a>
         <?php endif; ?>
         <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('invoice-prefix')): ?>
         <div class="dropdown-divider"></div>
        <div style="display: flex;">
          <a href="<?php echo e(url('invoice-prefix')); ?>" class="dropdown-item">
            <i class="fas fa-cog    mr-2"></i><?php echo e(__('label.Invoice Prefix')); ?>

          </a>
        </div>
         <?php endif; ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('role-list')): ?>
         <div class="dropdown-divider"></div>
        <div style="display: flex;">
         <a href="<?php echo e(url('roles')); ?>" class="dropdown-item">
          <i class="fa fa-server  mr-2" aria-hidden="true"></i><?php echo e(__('label.Roles')); ?>

          </a>
           <a   href="#None" 
          class="dropdown-item text-right attr_base_create_url"
            data-toggle="modal" data-target="#commonEntryModal_item" 
            attr_base_create_url="<?php echo e(route('roles.create')); ?>"> 
            <i class="nav-icon fas fa-plus"></i> </a>
        </div>
          
         <?php endif; ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('user-list')): ?>
         <div class="dropdown-divider"></div>
        <div style="display: flex;">
         <a href="<?php echo e(url('users')); ?>" class="dropdown-item">
            <i class="fas fa-users  mr-2"></i> <?php echo e(__('label.Users')); ?>

          </a>
          <a   href="#None" 
          class="dropdown-item text-right attr_base_create_url"
            data-toggle="modal" data-target="#commonEntryModal_item" 
            attr_base_create_url="<?php echo e(route('users.create')); ?>"> 
            <i class="nav-icon fas fa-plus"></i> </a>
        </div>
          
         <?php endif; ?>
         <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('companies-list')): ?>
        <div style="display: flex;">
         <a href="<?php echo e(url('companies')); ?>" class="dropdown-item">
            <i class="fa fa-fax mr-2" aria-hidden="true"></i> <?php echo e(__('label.companies')); ?>

          </a>
          <a  href="<?php echo e(route('companies.create')); ?>" class="dropdown-item text-right">
            <i class="nav-icon fas fa-plus"></i>
          </a>
        </div>
        <?php endif; ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('branch-list')): ?>
         <div class="dropdown-divider"></div>
        <div style="display: flex;">
          <a href="<?php echo e(url('branch')); ?>" class="dropdown-item">
            <i class="fa fa-share-alt mr-2" aria-hidden="true"></i> <?php echo e(__('label.Branch')); ?> 
          </a>
           <a   href="#None" 
          class="dropdown-item text-right attr_base_create_url"
            data-toggle="modal" data-target="#commonEntryModal_item" 
            attr_base_create_url="<?php echo e(route('branch.create')); ?>"> 
            <i class="nav-icon fas fa-plus"></i> </a>
        </div>
         
         <?php endif; ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('cost-center-list')): ?>
         <div class="dropdown-divider"></div>
        <div style="display: flex;">
          <a href="<?php echo e(url('cost-center')); ?>" class="dropdown-item">
           <i class="fa fa-adjust mr-2" aria-hidden="true"></i> <?php echo e(__('label.Cost center')); ?> 
          </a>
            <a   
          class="dropdown-item text-right "
            
            href="<?php echo e(route('cost-center.create')); ?>"> 
            <i class="nav-icon fas fa-plus"></i> </a>
        </div>
          
         <?php endif; ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('store-house-list')): ?>
         <div class="dropdown-divider"></div>
        <div style="display: flex;">
          <a href="<?php echo e(url('store-house')); ?>" class="dropdown-item">
           <i class="fa fa-adjust mr-2" aria-hidden="true"></i> <?php echo e(__('label._store_id')); ?> 
          </a>
            <a   
          class="dropdown-item text-right "
            href="<?php echo e(route('store-house.create')); ?>"> 
            <i class="nav-icon fas fa-plus"></i> </a>
        </div>
          
         <?php endif; ?>
       
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('budgets-list')): ?>
         <div class="dropdown-divider"></div>
        <div style="display: flex;">
          <a href="<?php echo e(url('budgets')); ?>" class="dropdown-item">
            <i class="fas fa-store   mr-2"></i><?php echo e(__('label.budgets')); ?>

          </a>
          <a  href="<?php echo e(route('budgets.create')); ?>"
          class="dropdown-item text-right "> 
            <i class="nav-icon fas fa-plus"></i> </a>
        </div>
         <?php endif; ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('lock-permission')): ?>
         <div class="dropdown-divider"></div>
        <div style="display: flex;">
          <a href="<?php echo e(url('all-lock')); ?>" class="dropdown-item">
            <i class="fas fa-lock _required   mr-2"></i><?php echo e(__('label.Transection Lock System')); ?>

          </a>
        </div>
         <?php endif; ?>
        
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('database-backup')): ?>
         <div class="dropdown-divider"></div>
        <div style="display: flex;">
          <a href="<?php echo e(url('database-backup')); ?>" class="dropdown-item">
            <i class="fa fa-database    mr-2"></i><?php echo e(__('label.Data Backup')); ?>

          </a>
        </div>
         <?php endif; ?>
        
              
              
      </li><?php /**PATH D:\xampp\htdocs\own\inv-acc-hrm\resources\views/backend/layouts/setting_module.blade.php ENDPATH**/ ?>