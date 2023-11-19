 <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('inventory-menu')): ?> 
      <li class="nav-item dropdown remove_from_header">
        <a class="nav-link" data-toggle="dropdown" href="#">
          
          <?php echo e(__('label.Inventory')); ?> <i class="right fas fa-angle-down"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
           
           <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('purchase-order-list')): ?>
          
           <div style="display: flex;">
           <a href="<?php echo e(url('purchase-order')); ?>" class="dropdown-item">
            <i class="fa fa-list-alt mr-2" aria-hidden="true"></i> <?php echo e(__('label.purchase order')); ?>

          </a>
           <a  href="<?php echo e(route('purchase-order.create')); ?>" class="dropdown-item text-right">
            <i class="nav-icon fas fa-plus"></i>
          </a>
        </div>
         <?php endif; ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('purchase-list')): ?>
         <div class="dropdown-divider"></div>
          
           <div style="display: flex;">
           <a href="<?php echo e(url('purchase')); ?>" class="dropdown-item">
            <i class="fa fa-list-alt mr-2" aria-hidden="true"></i> <?php echo e(__('label.material_receive')); ?>

          </a>

          <a  href="<?php echo e(route('purchase.create')); ?>" 
          class="dropdown-item text-right  " > 
            <i class="nav-icon fas fa-plus"></i> </a>

          
        </div>
         <?php endif; ?>
          
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('purchase-return-list')): ?>
         <div class="dropdown-divider"></div>
        <div style="display: flex;">
           <a href="<?php echo e(url('purchase-return')); ?>" class="dropdown-item">
            <i class="fa fa-list-alt mr-2" aria-hidden="true"></i> <?php echo e(__('label.material_return')); ?>

          </a>
           <a  href="<?php echo e(route('purchase-return.create')); ?>" 
          class="dropdown-item text-right  " > 
            <i class="nav-icon fas fa-plus"></i> </a>
        </div>
         <?php endif; ?>
          <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('production-list')): ?>
          <div class="dropdown-divider"></div> 
        <div style="display: flex;">
           <a href="<?php echo e(url('production')); ?>" class="dropdown-item">
            <i class="fa fa-recycle mr-2" aria-hidden="true"></i> <?php echo e(__('label.finished_goods_fabrication')); ?>

          </a>
           <a  href="<?php echo e(route('production.create')); ?>" class="dropdown-item text-right">
            <i class="nav-icon fas fa-plus"></i>
          </a>
        </div>
         <?php endif; ?> 
         <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('material-issue-list')): ?>
         <div class="dropdown-divider"></div>  
          
        <div style="display: flex;">
           <a href="<?php echo e(url('material-issue')); ?>" class="dropdown-item">
            <i class="fa fa-arrow-circle-right mr-2" aria-hidden="true"></i> <?php echo e(__('label.material_issued')); ?>

          </a>
           <a  href="<?php echo e(route('material-issue.create')); ?>" class="dropdown-item text-right">
            <i class="nav-icon fas fa-plus"></i>
          </a>
        </div>
         <?php endif; ?>
         <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('material-issue-return-list')): ?>
         <div class="dropdown-divider"></div>  
          
        <div style="display: flex;">
           <a href="<?php echo e(url('material-issue-return')); ?>" class="dropdown-item">
            <i class="fa fa-arrow-circle-down mr-2" aria-hidden="true"></i> <?php echo e(__('label.material_issue_return')); ?>

          </a>
           <a  href="<?php echo e(route('material-issue-return.create')); ?>" class="dropdown-item text-right">
            <i class="nav-icon fas fa-plus"></i>
          </a>
        </div>
         <?php endif; ?>  
          
       <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('sales-order-list')): ?>
         <div class="dropdown-divider"></div>
          
        <div style="display: flex;">
           <a href="<?php echo e(url('sales-order')); ?>" class="dropdown-item">
            <i class="fa fa-shopping-cart mr-2" aria-hidden="true"></i> <?php echo e(__('label.sales-order')); ?>

          </a>
          <a  href="<?php echo e(route('sales-order.create')); ?>" 
          class="dropdown-item text-right  "> 
            <i class="nav-icon fas fa-plus"></i> </a>

          </a>
        </div>
         <?php endif; ?>
         
          
       <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('sales-list')): ?>
         <div class="dropdown-divider"></div>
          
        <div style="display: flex;">
           <a href="<?php echo e(url('sales')); ?>" class="dropdown-item">
            <i class="fa fa-shopping-cart mr-2" aria-hidden="true"></i> <?php echo e(__('label.material_issue')); ?>

          </a>
           <a  href="<?php echo e(route('sales.create')); ?>" 
          class="dropdown-item text-right  " > 
            <i class="nav-icon fas fa-plus"></i> </a>

          </a>
        </div>
         <?php endif; ?>
       <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('sales-return-list')): ?>
         <div class="dropdown-divider"></div>  
          
        <div style="display: flex;">
           <a href="<?php echo e(url('sales-return')); ?>" class="dropdown-item">
            <i class="fa fa-shopping-cart mr-2" aria-hidden="true"></i> <?php echo e(__('label.issued_material_return')); ?>

          </a>
           <a  href="<?php echo e(route('sales-return.create')); ?>" class="dropdown-item text-right">
            <i class="nav-icon fas fa-plus"></i>
          </a>
        </div>
         <?php endif; ?> 
       
        
       
          <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('transfer-list')): ?>
         <div class="dropdown-divider"></div>  
        <div style="display: flex;">
           <a href="<?php echo e(url('transfer')); ?>" class="dropdown-item">
            <i class="fa fa-recycle mr-2" aria-hidden="true"></i> <?php echo e(__('label.transfer')); ?>

          </a>
           <a  href="<?php echo e(route('transfer.create')); ?>" class="dropdown-item text-right">
            <i class="nav-icon fas fa-plus"></i>
          </a>
        </div>
         <?php endif; ?>

          
       
          <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('third-party-service-menu')): ?>
          <div class="dropdown-divider"></div> 
        <div style="display: flex;">
           <a href="<?php echo e(url('third-party-service')); ?>" class="dropdown-item">
            <i class="fa fa-recycle mr-2" aria-hidden="true"></i><?php echo e(__('label.third-party-service-list')); ?>

          </a>
           <a  href="<?php echo e(route('third-party-service.create')); ?>" class="dropdown-item text-right">
            <i class="nav-icon fas fa-plus"></i>
          </a>
        </div>
         <?php endif; ?> 
          
       <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('warranty-manage-menu')): ?>
          <div class="dropdown-divider"></div> 
        <div style="display: flex;">
           <a href="<?php echo e(url('warranty-manage')); ?>" class="dropdown-item">
            <i class="fa fa-recycle mr-2" aria-hidden="true"></i> <?php echo e(__('label.warranty-manage-list')); ?>

          </a>
           <a  href="<?php echo e(route('warranty-manage.create')); ?>" class="dropdown-item text-right">
            <i class="nav-icon fas fa-plus"></i>
          </a>
        </div>
         <?php endif; ?> 
          
       <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('item-replace-menu')): ?>
          <div class="dropdown-divider"></div> 
        <div style="display: flex;">
           <a href="<?php echo e(url('item-replace')); ?>" class="dropdown-item">
            <i class="fa fa-recycle mr-2" aria-hidden="true"></i><?php echo e(__('label.item-replace-list')); ?>

          </a>
           <a  href="<?php echo e(route('item-replace.create')); ?>" class="dropdown-item text-right">
            <i class="nav-icon fas fa-plus"></i>
          </a>
        </div>
         <?php endif; ?>  

      
       <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('individual-replacement-menu')): ?>
          <div class="dropdown-divider"></div> 
        <div style="display: flex;">
           <a href="<?php echo e(url('individual-replacement')); ?>" class="dropdown-item">
            <i class="fa fa-recycle mr-2" aria-hidden="true"></i><?php echo e(__('label.individual-replacement-list')); ?>

          </a>
           <a  href="<?php echo e(route('individual-replacement.create')); ?>" class="dropdown-item text-right">
            <i class="nav-icon fas fa-plus"></i>
          </a>
        </div>
         <?php endif; ?> 
       <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('w-item-receive-from-supp-menu')): ?>
          <div class="dropdown-divider"></div> 
        <div style="display: flex;">
           <a href="<?php echo e(url('w-item-receive-from-supp')); ?>" class="dropdown-item">
            <i class="fa fa-recycle mr-2" aria-hidden="true"></i><?php echo e(__('label.w-item-receive-from-supp-list')); ?>

          </a>
           <a  href="<?php echo e(route('w-item-receive-from-supp.create')); ?>" class="dropdown-item text-right">
            <i class="nav-icon fas fa-plus"></i>
          </a>
        </div>
         <?php endif; ?> 
          <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('restaurant-module')): ?> 
         <div class="dropdown-divider"></div>
          <p style="text-align: center;"><b>Restaurant</b></p>

          <div style="display: flex;">
          <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('restaurant-pos')): ?>
           <a href="<?php echo e(url('restaurant-pos')); ?>" class="dropdown-item">
            <i class="fa fa-table mr-2" aria-hidden="true"></i> <?php echo e(__('label.Restaurant POS')); ?>

          </a>
         
          <?php endif; ?>
        </div>
          <div class="dropdown-divider"></div>   
      
        <div style="display: flex;">
          <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('restaurant-sales-list')): ?>
           <a href="<?php echo e(url('restaurant-sales')); ?>" class="dropdown-item">
            <i class="fa fa-table mr-2" aria-hidden="true"></i> <?php echo e(__('label.Restaurant Sales')); ?>

          </a>
          <?php endif; ?>
           <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('restaurant-sales-create')): ?>
           <a  href="<?php echo e(route('restaurant-sales.create')); ?>" class="dropdown-item text-right">
            <i class="nav-icon fas fa-plus"></i>
          </a>
          <?php endif; ?>
        </div>

        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('kitchen-menu')): ?>
        <div style="display: flex;">
          <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('kitchen-list')): ?>
           <a href="<?php echo e(url('kitchen')); ?>" class="dropdown-item">
            <i class="fa fa-table mr-2" aria-hidden="true"></i> Kitchen Panel
          </a>
          <?php endif; ?>
          </div>
        <?php endif; ?> 
          <?php endif; ?>

      </li>
    <?php endif; ?><?php /**PATH D:\xampp\htdocs\own\inv-acc-hrm\resources\views/backend/layouts/inventory_module.blade.php ENDPATH**/ ?>