 <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('account-menu')): ?> 
      <li class="nav-item dropdown remove_from_header">
        <a class="nav-link" data-toggle="dropdown" href="#">
           <?php echo e(__('label.receive_payment')); ?> <i class="right fas fa-angle-down"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
       
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('cash-receive')): ?>
        <div style="display: flex;">
         <a href="<?php echo e(url('voucher')); ?>?_voucher_type=CR" class="dropdown-item">
            <i class="fa fa-fax mr-2" aria-hidden="true"></i> <?php echo e(__('label.Cash Receive')); ?>

          </a>
           <a  href="<?php echo e(url('cash-receive')); ?>" class="dropdown-item text-right">
            <i class="nav-icon fas fa-plus"></i>
          </a>
        </div>
         <?php endif; ?>
        
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('cash-payment')): ?>
         <div class="dropdown-divider"></div>
        <div style="display: flex;">
         <a href="<?php echo e(url('voucher')); ?>?_voucher_type=CP" class="dropdown-item">
            <i class="fa fa-fax mr-2" aria-hidden="true"></i> <?php echo e(__('label.Cash Payment')); ?>

          </a>
           <a  href="<?php echo e(url('cash-payment')); ?>" class="dropdown-item text-right">
            <i class="nav-icon fas fa-plus"></i>
          </a>
        </div>
         <?php endif; ?> 
        
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('bank-receive')): ?>
         <div class="dropdown-divider"></div>
        <div style="display: flex;">
         <a href="<?php echo e(url('voucher')); ?>?_voucher_type=BR" class="dropdown-item">
            <i class="fa fa-fax mr-2" aria-hidden="true"></i> <?php echo e(__('label.Bank Receive')); ?>

          </a>
           <a  href="<?php echo e(url('bank-receive')); ?>" class="dropdown-item text-right">
            <i class="nav-icon fas fa-plus"></i>
          </a>
        </div>
         <?php endif; ?> 
         
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('bank-payment')): ?>
        <div class="dropdown-divider"></div>
        <div style="display: flex;">
         <a href="<?php echo e(url('voucher')); ?>?_voucher_type=BP" class="dropdown-item">
            <i class="fa fa-fax mr-2" aria-hidden="true"></i> <?php echo e(__('label.Bank Payment')); ?>

          </a>
           <a  href="<?php echo e(url('bank-payment')); ?>" class="dropdown-item text-right">
            <i class="nav-icon fas fa-plus"></i>
          </a>
        </div>
         <?php endif; ?> 
         
          <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('voucher-list')): ?>
          <div class="dropdown-divider"></div>
        <div style="display: flex;">
         <a href="<?php echo e(url('voucher')); ?>" class="dropdown-item">
            <i class="fa fa-fax mr-2" aria-hidden="true"></i> <?php echo e(__('label.Voucher')); ?>

          </a>
           <a  href="<?php echo e(route('voucher.create')); ?>" class="dropdown-item text-right">
            <i class="nav-icon fas fa-plus"></i>
          </a>
        </div>
         <?php endif; ?>
          <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('easy-voucher-list')): ?>
         <div class="dropdown-divider"></div>
        <div style="display: flex;">
         <a href="<?php echo e(url('easy-voucher')); ?>" class="dropdown-item">
            <i class="fa fa-fax mr-2" aria-hidden="true"></i> <?php echo e(__('label.Easy Voucher')); ?>

          </a>
           <a  href="<?php echo e(route('easy-voucher.create')); ?>" class="dropdown-item text-right">
            <i class="nav-icon fas fa-plus"></i>
          </a>
        </div>
         <?php endif; ?> 
          <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('inter-project-voucher-list')): ?>
         <div class="dropdown-divider"></div>
        <div style="display: flex;">
         <a href="<?php echo e(url('inter-project-voucher')); ?>" class="dropdown-item">
            <i class="fa fa-fax mr-2" aria-hidden="true"></i> <?php echo e(__('label.inter-project-voucher')); ?>

          </a>
           <a  href="<?php echo e(route('inter-project-voucher.create')); ?>" class="dropdown-item text-right">
            <i class="nav-icon fas fa-plus"></i>
          </a>
        </div>
         <?php endif; ?> 
         
      </li>
    <?php endif; ?><?php /**PATH D:\xampp\htdocs\solar-acc-invenoty\resources\views/backend/layouts/account_module.blade.php ENDPATH**/ ?>