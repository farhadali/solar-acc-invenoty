
       <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('rlp-module')): ?> 
      <li class="nav-item dropdown remove_from_header">
        <a class="nav-link" data-toggle="dropdown" href="#">
           <?php echo e(__('label.procurement')); ?> <i class="right fas fa-angle-down"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
       <p style="padding-left: 20px;"><b><?php echo e(__('label.entry')); ?></b></p>
       <div class="dropdown-divider"></div>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('approval-chain-list')): ?>
         <div style="display: flex;">
         <a href="<?php echo e(route('approval-chain.index')); ?>" class="dropdown-item">
            <i class="fa fa-fax mr-2" aria-hidden="true"></i> <?php echo e(__('label.approval-chain')); ?>

          </a>
          <a  href="<?php echo e(route('approval-chain.create')); ?>" class="dropdown-item text-right">
            <i class="nav-icon fas fa-plus"></i>
          </a>
        </div>
        <?php endif; ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('rlp-list')): ?>
         <div style="display: flex;">
         <a href="<?php echo e(route('rlp.index')); ?>" class="dropdown-item">
            <i class="fa fa-fax mr-2" aria-hidden="true"></i> <?php echo e(__('label.rlp-info')); ?>

          </a>
          <a  href="<?php echo e(route('rlp.create')); ?>" class="dropdown-item text-right">
            <i class="nav-icon fas fa-plus"></i>
          </a>
        </div>
        <?php endif; ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('import-purchase-list')): ?>
         <div style="display: flex;">
         <a href="<?php echo e(route('import-purchase.index')); ?>" class="dropdown-item">
            <i class="fa fa-fax mr-2" aria-hidden="true"></i> <?php echo e(__('label.import-purchase')); ?>

          </a>
          <a  href="<?php echo e(route('import-purchase.create')); ?>" class="dropdown-item text-right">
            <i class="nav-icon fas fa-plus"></i>
          </a>
        </div>
        <?php endif; ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('import-material-receive-list')): ?>
         <div style="display: flex;">
         <a href="<?php echo e(route('import-material-receive.index')); ?>" class="dropdown-item">
            <i class="fa fa-fax mr-2" aria-hidden="true"></i> <?php echo e(__('label.import-material-receive')); ?>

          </a>
          <a  href="<?php echo e(route('import-material-receive.create')); ?>" class="dropdown-item text-right">
            <i class="nav-icon fas fa-plus"></i>
          </a>
        </div>
        <?php endif; ?>
      </li>
    <?php endif; ?><?php /**PATH D:\xampp\htdocs\own\inv-acc-hrm\resources\views/backend/layouts/rlp_module.blade.php ENDPATH**/ ?>