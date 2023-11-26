<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('inventory-menu')): ?> 
      <li class="nav-item dropdown remove_from_header">
        <a class="nav-link" data-toggle="dropdown" href="#">
          
          <?php echo e(__('label.Master')); ?> <i class="right fas fa-angle-down"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
         
           <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('account-type-list')): ?>
          <div class="dropdown-divider"></div>
          <a href="<?php echo e(url('account-type')); ?>" class="dropdown-item">
            <i class="fa fa-sitemap mr-2" aria-hidden="true"></i><?php echo e(__('label.Account Type')); ?>

          </a>
         <?php endif; ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('account-group-list')): ?>
         <div class="dropdown-divider"></div>
          <a href="<?php echo e(url('account-group')); ?>" class="dropdown-item">
            <i class="fa fa-fax mr-2" aria-hidden="true"></i> <?php echo e(__('label.Account Group')); ?>

          </a>
         <?php endif; ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('account-ledger-list')): ?>
         <div class="dropdown-divider"></div>
          
        <div style="display: flex;">
          <a href="<?php echo e(url('account-ledger')); ?>" class="dropdown-item">
            <i class="fa fa-fax mr-2" aria-hidden="true"></i> <?php echo e(__('label.Account Ledger')); ?>

          </a>
          
           <a  href="<?php echo e(route('account-ledger.create')); ?>" class="dropdown-item text-right "  >
            <i class="nav-icon fas fa-plus"></i>
          </a>
        </div>
          
         <?php endif; ?>
          
           <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('item-category-list')): ?>
        <div class="dropdown-divider"></div>
          
        <div style="display: flex;">
          <a href="<?php echo e(url('item-category')); ?>" class="dropdown-item">
            <i class="fa fa-laptop mr-2" aria-hidden="true"></i><?php echo e(__('label.Item Category')); ?>

          </a>
           <a  
           class="dropdown-item text-right " 
               href="<?php echo e(route('item-category.create')); ?>"
                >
            <i class="nav-icon fas fa-plus"></i>
          </a>

        </div>
          
         <?php endif; ?>
           <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('unit-list')): ?>
          <div class="dropdown-divider"></div>
          
        <div style="display: flex;">
           <a href="<?php echo e(url('unit')); ?>" class="dropdown-item">
            <i class="fa fa-laptop mr-2" aria-hidden="true"></i><?php echo e(__('label.Unit Of Measurment')); ?>

          </a>
          <a   
           class="dropdown-item text-right " 
               href="<?php echo e(route('unit.create')); ?>" >
            <i class="nav-icon fas fa-plus"></i>
          </a>

        </div>
          
         <?php endif; ?>
           <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('warranty-list')): ?>
          <div class="dropdown-divider"></div>
          
        <div style="display: flex;">
           <a href="<?php echo e(url('warranty')); ?>" class="dropdown-item">
            <i class="fa fa-laptop mr-2" aria-hidden="true"></i><?php echo e(__('label.Warranty')); ?>

          </a>
           <a   
           class="dropdown-item text-right " 
               href="<?php echo e(route('warranty.create')); ?>"
                >
            <i class="nav-icon fas fa-plus"></i>
          </a>
          
        </div>
          
         <?php endif; ?>
         
          <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('transection_terms-list')): ?>
           <div class="dropdown-divider"></div>
        <div style="display: flex;">
           <a href="<?php echo e(url('transection_terms')); ?>" class="dropdown-item">
            <i class="fa fa-laptop mr-2" aria-hidden="true"></i><?php echo e(__('label.Transection Terms')); ?>

          </a>
          <a   
           class="dropdown-item text-right " 
               href="<?php echo e(route('transection_terms.create')); ?>" >
            <i class="nav-icon fas fa-plus"></i>
          </a>
          

        </div>
          
         <?php endif; ?>

          <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('vat-rules-list')): ?>
           <div class="dropdown-divider"></div>
        <div style="display: flex;">
           <a href="<?php echo e(url('vat-rules')); ?>" class="dropdown-item">
            <i class="fa fa-laptop mr-2" aria-hidden="true"></i><?php echo e(__('label.Vat Rules')); ?>

          </a>
           <a  
           class="dropdown-item text-right " 
               href="<?php echo e(route('vat-rules.create')); ?>" >
            <i class="nav-icon fas fa-plus"></i>
          </a>
          
        </div>
          
         <?php endif; ?>
         
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('item-information-list')): ?>
        <div class="dropdown-divider"></div>
        <div style="display: flex;">
           <a href="<?php echo e(url('item-information')); ?>" class="dropdown-item">
            <i class="fa fa-list-alt mr-2" aria-hidden="true"></i> <?php echo e(__('label.Item Information')); ?>

          </a>
           <a  
           class="dropdown-item text-right " 
              
               href="<?php echo e(route('item-information.create')); ?>"
                >
            <i class="nav-icon fas fa-plus"></i>
          </a>
        </div>
          
         <?php endif; ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('lot-item-information')): ?>
         <div class="dropdown-divider"></div>
        <div style="display: flex;">
           <a href="<?php echo e(url('lot-item-information')); ?>" class="dropdown-item">
            <i class="fa fa-list-alt mr-2" aria-hidden="true"></i><?php echo e(__('label.Lot Item Information')); ?>

          </a>
           
        </div>
         <?php endif; ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('labels-print')): ?>
         <div class="dropdown-divider"></div> 
        <div style="display: flex;">
           <a href="<?php echo e(url('labels-print')); ?>" class="dropdown-item">
            <i class="fa fa-list-alt mr-2" aria-hidden="true"></i><?php echo e(__('label.Labels Print')); ?>

          </a>
           
        </div>
         <?php endif; ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('vessel-info-list')): ?>
         <div class="dropdown-divider"></div> 
        <div style="display: flex;">
           <a href="<?php echo e(url('vessel-info')); ?>" class="dropdown-item">
            <i class="fa fa-list-alt mr-2" aria-hidden="true"></i><?php echo e(__('label.vessel-info')); ?>

          </a>
           
        </div>
         <?php endif; ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('mother-vessel-info-list')): ?>
         <div class="dropdown-divider"></div> 
        <div style="display: flex;">
           <a href="<?php echo e(url('mother-vessel-info')); ?>" class="dropdown-item">
            <i class="fa fa-list-alt mr-2" aria-hidden="true"></i><?php echo e(__('label.mother-vessel-info')); ?>

          </a>
           
        </div>
         <?php endif; ?>
          <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('restaurant-module')): ?> 
          <p style="text-align: center;margin-bottom: 1px solid #000;"><b>Resturant Module</b></p>
      <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('table-info-menu')): ?>
        <div style="display: flex;">
          <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('table-info-list')): ?>
           <a href="<?php echo e(url('table-info')); ?>" class="dropdown-item">
            <i class="fa fa-table mr-2" aria-hidden="true"></i> Table Information
          </a>
          <?php endif; ?>
           <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('table-info-create')): ?>
           <a  href="<?php echo e(route('table-info.create')); ?>" class="dropdown-item text-right">
            <i class="nav-icon fas fa-plus"></i>
          </a>
          <?php endif; ?>
        </div>
         <?php endif; ?> 
       <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('steward-waiter-menu')): ?>
          <div class="dropdown-divider"></div>   
        <div style="display: flex;">
          <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('steward-waiter-list')): ?>
           <a href="<?php echo e(url('steward-waiter')); ?>" class="dropdown-item">
            <i class="fa fa-table mr-2" aria-hidden="true"></i> Steward Waiter Setup
          </a>
          <?php endif; ?>
           <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('steward-waiter-create')): ?>
           <a  href="<?php echo e(route('steward-waiter.create')); ?>" class="dropdown-item text-right">
            <i class="nav-icon fas fa-plus"></i>
          </a>
          <?php endif; ?>
        </div>
         <?php endif; ?> 
       <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('musak-four-point-three-menu')): ?>
         <div class="dropdown-divider"></div>
        <div style="display: flex;">
          <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('musak-four-point-three-list')): ?>
           <a href="<?php echo e(url('musak-four-point-three')); ?>" class="dropdown-item">
            <i class="fa fa-table mr-2" aria-hidden="true"></i> Item Wise Ingredients
          </a>
          <?php endif; ?>
           <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('musak-four-point-three-create')): ?>
           <a  href="<?php echo e(route('musak-four-point-three.create')); ?>" class="dropdown-item text-right">
            <i class="nav-icon fas fa-plus"></i>
          </a>
          <?php endif; ?>
        </div>
         <?php endif; ?> 
       
       <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('category-allocation-list')): ?>
        <div class="dropdown-divider"></div>
        <div style="display: flex;">
          <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('category-allocation-list')): ?>
           <a href="<?php echo e(url('category-allocation')); ?>" class="dropdown-item">
            <i class="fa fa-table mr-2" aria-hidden="true"></i>Pos Dispaly Category 
          </a>
          <?php endif; ?>
           
        </div>
          <?php endif; ?>
    <?php endif; ?>  
      </li>
    <?php endif; ?><?php /**PATH D:\xampp\htdocs\solar-acc-invenoty\resources\views/backend/layouts/master_module.blade.php ENDPATH**/ ?>