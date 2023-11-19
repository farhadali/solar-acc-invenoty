<aside class="main-sidebar sidebar-dark-primary ">
    <!-- Brand Logo -->
    <a href="<?php echo e(url('home')); ?>" class="brand-link">
      <img src="<?php echo e(asset('/')); ?><?php echo e($settings->logo ?? ''); ?>" alt="<?php echo e($settings->name ?? ''); ?>" class="brand-image  elevation-3" >
      <span class="brand-text font-weight-light"><?php echo e($settings->title ?? ''); ?></span>
    </a>
<?php
   $current_url = Route::current()->getName();
?>
    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar Menu -->
     
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <li class="nav-item ">
            <a href="<?php echo e(url('home')); ?>" class="nav-link <?php echo e(( $current_url=='home' ) ? 'nest_active' : ''); ?>"  >
              <i class="fa fa-credit-card nav-icon" aria-hidden="true"></i>
              <p>
                  <?php echo e(__('label.Dashboard')); ?>

              </p>
            </a>
          </li>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('hrm-module')): ?> 
          <li class="nav-item <?php echo e(Route::is('week-work-day.*') ||  Route::is('holidays.*') ||  Route::is('leave-type.*')  ||  Route::is('hrm-employee.*')  ||  Route::is('initial-salary-structure.*')  ||  Route::is('pay-heads.*')  ||  Route::is('hrm-department.*')   ||  Route::is('hrm-grade.*') ||  Route::is('hrm-emp-location.*')  ||  Route::is('hrm-emp-category.*')   ||  Route::is('hrm-designation.*')  ? 'menu-is-opening menu-open' : ''); ?>">
            <a href="#" class="nav-link <?php echo e(Route::is('week-work-day.*') || Route::is('holidays.*') || Route::is('leave-type.*') || Route::is('hrm-employee.*') || Route::is('initial-salary-structure.*')  || Route::is('pay-heads.*')  || Route::is('hrm-department.*')    || Route::is('hrm-grade.*')   || Route::is('hrm-emp-location.*') ||  Route::is('hrm-emp-category.*')   ||  Route::is('hrm-designation.*')    ? 'main_nav_active' : ''); ?>">
              <i class="fa fa-users  nav-icon" aria-hidden="true"></i>
              <p>


                 <?php echo e(__('label.hrm')); ?>

                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('week-work-day')): ?>
              <li class="nav-item" >
                  <a href="<?php echo e(url('weekworkday')); ?>" class="nav-link " >
                  <i class="fa fa-arrow-right nav-icon"></i><p><?php echo e(__('label.weekworkday')); ?></p></a>
              </li>
              <?php endif; ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('holidays-list')): ?>
              <li class="nav-item" >
                  <a href="<?php echo e(url('holidays')); ?>" class="nav-link " >
                  <i class="fa fa-arrow-right nav-icon"></i><p><?php echo e(__('label.holidays')); ?></p></a>
              </li>
              <?php endif; ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('leave-type-list')): ?>
              <li class="nav-item" >
                  <a href="<?php echo e(url('leave-type')); ?>" class="nav-link " >
                  <i class="fa fa-arrow-right nav-icon"></i><p><?php echo e(__('label.leave-type')); ?></p></a>
              </li>
              <?php endif; ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('hrm-employee-list')): ?>
              <li class="nav-item" >
                  <a href="<?php echo e(url('hrm-employee')); ?>" class="nav-link " >
                  <i class="fa fa-arrow-right nav-icon"></i><p><?php echo e(__('label.hrm-employee')); ?></p></a>
              </li>
              <?php endif; ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('initial-salary-structure-list')): ?>
              <li class="nav-item" >
                  <a href="<?php echo e(url('initial-salary-structure')); ?>" class="nav-link " >
                  <i class="fa fa-arrow-right nav-icon"></i><p><?php echo e(__('label.initial-salary-structure')); ?></p></a>
              </li>
              <?php endif; ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('pay-heads-list')): ?>
              <li class="nav-item" >
                  <a href="<?php echo e(url('pay-heads')); ?>" class="nav-link " >
                  <i class="fa fa-arrow-right nav-icon"></i><p><?php echo e(__('label.pay-heads')); ?></p></a>
              </li>
              <?php endif; ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('hrm-department-list')): ?>
              <li class="nav-item" >
                  <a href="<?php echo e(url('hrm-department')); ?>" class="nav-link " >
                  <i class="fa fa-arrow-right nav-icon"></i><p><?php echo e(__('label.hrm-department')); ?></p></a>
              </li>
              <?php endif; ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('hrm-grade-list')): ?>
              <li class="nav-item" >
                  <a href="<?php echo e(url('hrm-grade')); ?>" class="nav-link " >
                  <i class="fa fa-arrow-right nav-icon"></i><p><?php echo e(__('label.hrm-grade')); ?></p></a>
              </li>
              <?php endif; ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('hrm-emp-location-list')): ?>
              <li class="nav-item" >
                  <a href="<?php echo e(url('hrm-emp-location')); ?>" class="nav-link " >
                  <i class="fa fa-arrow-right nav-icon"></i><p><?php echo e(__('label.hrm-emp-location')); ?></p></a>
              </li>
              <?php endif; ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('hrm-emp-category-list')): ?>
              <li class="nav-item" >
                  <a href="<?php echo e(url('hrm-emp-category')); ?>" class="nav-link " >
                  <i class="fa fa-arrow-right nav-icon"></i><p><?php echo e(__('label.hrm-emp-category')); ?></p></a>
              </li>
              <?php endif; ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('hrm-designation-list')): ?>
              <li class="nav-item" >
                  <a href="<?php echo e(url('hrm-designation')); ?>" class="nav-link " >
                  <i class="fa fa-arrow-right nav-icon"></i><p><?php echo e(__('label.hrm-designation')); ?></p></a>
              </li>
              <?php endif; ?>
             
            </ul>
          </li>
          <?php endif; ?>
          <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('account-menu')): ?> 
          <li class="nav-item <?php echo e(Route::is('voucher.*')  ? 'menu-is-opening menu-open' : ''); ?>">
            <a href="#" class="nav-link <?php echo e(Route::is('purchase-return.*')    ? 'main_nav_active' : ''); ?>">
              <i class="fa fa-coins nav-icon" aria-hidden="true"></i>
              <p>
                 <?php echo e(__('label.Accounts')); ?>

                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('cash-receive')): ?>
              <li class="nav-item" >
                  <a href="<?php echo e(url('voucher')); ?>?_voucher_type=CR" class="nav-link " >
                  <i class="fa fa-arrow-right nav-icon"></i><p><?php echo e(__('label.Cash Receive')); ?></p></a>
              </li>
              <?php endif; ?>
             <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('cash-payment')): ?>
              <li class="nav-item">
                <a href="<?php echo e(url('voucher')); ?>?_voucher_type=CP" class="nav-link" ><i class="fa fa-arrow-right nav-icon"></i><p><?php echo e(__('label.Cash Payment')); ?></p></a>
              </li>
              <?php endif; ?>
             <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('bank-receive')): ?>
              <li class="nav-item">
                <a href="<?php echo e(url('voucher')); ?>?_voucher_type=BR" class="nav-link" ><i class="fa fa-arrow-right nav-icon"></i><p><?php echo e(__('label.Bank Receive')); ?></p></a>
              </li>
              <?php endif; ?>
             <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('bank-payment')): ?>
              <li class="nav-item">
                <a href="<?php echo e(url('voucher')); ?>?_voucher_type=BP" class="nav-link" ><i class="fa fa-arrow-right nav-icon"></i><p><?php echo e(__('label.Bank Payment')); ?></p></a>
              </li>
              <?php endif; ?>
             
             <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('voucher-list')): ?>
              <li class="nav-item">
                <a href="<?php echo e(url('voucher')); ?>" class="nav-link" ><i class="fa fa-arrow-right nav-icon"></i><p><?php echo e(__('label.Voucher')); ?></p></a>
              </li>
              <?php endif; ?>
             <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('easy-voucher-list')): ?>
              <li class="nav-item">
                <a href="<?php echo e(url('easy-voucher')); ?>" class="nav-link" ><i class="fa fa-arrow-right nav-icon"></i><p><?php echo e(__('label.Easy Voucher')); ?></p></a>
              </li>
              <?php endif; ?>
             <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('inter-project-voucher-list')): ?>
              <li class="nav-item">
                <a href="<?php echo e(url('inter-project-voucher')); ?>" class="nav-link" ><i class="fa fa-arrow-right nav-icon"></i><p><?php echo e(__('label.inter-project-voucher')); ?></p></a>
              </li>
              <?php endif; ?>
            </ul>
          </li>
          <?php endif; ?>

 <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('restaurant-module')): ?> 
          <li class="nav-item <?php echo e(Route::is('restaurant-sales.*') || Route::is('table-info.*')  || Route::is('steward-waiter.*')  || Route::is('musak-four-point-three.*')|| Route::is('kitchen.*')   ? 'menu-is-opening menu-open' : ''); ?> ">
            <a href="#" class="nav-link ">
              <i class="fa fa-coffee nav-icon" aria-hidden="true"></i>
              <p>
                <?php echo e(__('label.Restaurants')); ?> 
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
             
             <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('restaurant-pos')): ?>
              <li class="nav-item">
                <a href="<?php echo e(url('restaurant-pos')); ?>" class="nav-link" ><i class="fa fa-arrow-right nav-icon"></i><p><?php echo e(__('label.Restaurant POS')); ?></p></a>
              </li>
              <?php endif; ?>
             
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('restaurant-sales-list')): ?>
              <li class="nav-item">
                <a href="<?php echo e(url('restaurant-sales')); ?>" class="nav-link <?php echo e(Route::is('restaurant-sales.*') ? 'nest_active' : ''); ?>" ><i class="fa fa-arrow-right nav-icon"></i><p> <?php echo e(__('label.Restaurant Sales')); ?></p></a>
              </li>
              <?php endif; ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('table-info-menu')): ?>
              <li class="nav-item">
                <a href="<?php echo e(url('table-info')); ?>"  class="nav-link <?php echo e(Route::is('table-info.*') ? 'nest_active' : ''); ?>" ><i class="fa fa-arrow-right nav-icon"></i><p> Table Information </p></a>
              </li>
              <?php endif; ?>
              <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('steward-waiter-menu')): ?>
              <li class="nav-item">
                <a href="<?php echo e(url('steward-waiter')); ?>"  class="nav-link <?php echo e(Route::is('steward-waiter.*') ? 'nest_active' : ''); ?>" ><i class="fa fa-arrow-right nav-icon"></i><p> Steward/Waiter Setup </p></a>
              </li>
              <?php endif; ?>
              <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('musak-four-point-three-menu')): ?>
              <li class="nav-item">
                <a href="<?php echo e(url('musak-four-point-three')); ?>" class="nav-link <?php echo e(Route::is('musak-four-point-three.*') ? 'nest_active' : ''); ?>" ><i class="fa fa-arrow-right nav-icon"></i><p> Item Wise Ingredients </p></a>
              </li>
              <?php endif; ?>
             <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('kitchen-menu')): ?>
              <li class="nav-item">
                <a href="<?php echo e(url('kitchen')); ?>" class="nav-link <?php echo e(Route::is('kitchen.*') ? 'nest_active' : ''); ?>" ><i class="fa fa-arrow-right nav-icon"></i><p>  Kitchen Panel </p></a>
              </li>
              <?php endif; ?>
            
             
            
              
            </ul>
          </li>
          <?php endif; ?>
          <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('rlp-module')): ?> 
          <li class="nav-item <?php echo e(Route::is('approval-chain.*') || Route::is('rlp.*')  || Route::is('import-purchase.*')  || Route::is('import-material-receive.*')  ? 'menu-is-opening menu-open' : ''); ?>">
            <a href="#" class="nav-link <?php echo e(Route::is('approval-chain.*') || Route::is('rlp.*') || Route::is('import-purchase.*')  || Route::is('import-material-receive.*')    ? 'main_nav_active' : ''); ?>">
              <i class="fa fa-flag nav-icon" aria-hidden="true"></i>
              <p>
                 <?php echo e(__('label.procurement')); ?>

                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
             <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('approval-chain-list')): ?>
              <li class="nav-item">
                <a href="<?php echo e(url('approval-chain')); ?>" class="mr-2  nav-link <?php echo e(( $current_url=='approval-chain.index' ) ? 'nest_active' : ''); ?>" ><i class="fa fa-arrow-right nav-icon"></i><p><?php echo e(__('label.approval-chain')); ?></p></a>
              </li>
              <?php endif; ?>
             <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('rlp-list')): ?>
              <li class="nav-item">
                <a href="<?php echo e(url('rlp')); ?>" class="mr-2  nav-link <?php echo e(( $current_url=='rlp.index' ) ? 'nest_active' : ''); ?>" ><i class="fa fa-arrow-right nav-icon"></i><p><?php echo e(__('label.rlp')); ?></p></a>
              </li>
              <?php endif; ?>
             <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('import-purchase-list')): ?>
              <li class="nav-item">
                <a href="<?php echo e(url('import-purchase')); ?>" class="mr-2  nav-link <?php echo e(( $current_url=='import-purchase.index' ) ? 'nest_active' : ''); ?>" ><i class="fa fa-arrow-right nav-icon"></i><p><?php echo e(__('label.import-purchase')); ?></p></a>
              </li>
              <?php endif; ?>
             <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('import-material-receive-list')): ?>
              <li class="nav-item">
                <a href="<?php echo e(url('import-material-receive')); ?>" class="mr-2  nav-link <?php echo e(( $current_url=='import-material-receive.index' ) ? 'nest_active' : ''); ?>" ><i class="fa fa-arrow-right nav-icon"></i><p><?php echo e(__('label.import-material-receive')); ?></p></a>
              </li>
              <?php endif; ?>
              
              
            </ul>
          </li>
          <?php endif; ?> 
 <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('purchase-order-list')): ?> 
          <li class="nav-item <?php echo e(Route::is('purchase-order.*') || Route::is('purchase.*')  || Route::is('purchase-return.*')  ? 'menu-is-opening menu-open' : ''); ?>">
            <a href="#" class="nav-link <?php echo e(Route::is('purchase-order.*') || Route::is('purchase.*') || Route::is('purchase-return.*')    ? 'main_nav_active' : ''); ?>">
              <i class="fa fa-cart-arrow-down nav-icon" aria-hidden="true"></i>
              <p>
                 <?php echo e(__('label.purchase')); ?>

                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
             
             <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('purchase-order-create')): ?>
              <li class="nav-item">
                <a href="<?php echo e(url('purchase-order/create')); ?>" class="mr-2 nav-link <?php echo e(( $current_url=='purchase-order.create' ) ? 'nest_active' : ''); ?>"  ><i class="fa fa-plus nav-icon"></i><p><?php echo e(__('label.Purchase Order Create')); ?> </p></a>
              </li>
              <?php endif; ?>
             <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('purchase-order-list')): ?>
              <li class="nav-item">
                <a href="<?php echo e(url('purchase-order')); ?>" class="mr-2  nav-link <?php echo e(( $current_url=='purchase-order.index' ) ? 'nest_active' : ''); ?>" ><i class="fa fa-arrow-right nav-icon"></i><p><?php echo e(__('label.Purchase Order List')); ?></p></a>
              </li>
              <?php endif; ?>
              <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('purchase-create')): ?>
              <li class="nav-item">
                <a href="<?php echo e(url('purchase/create')); ?>" class=" mr-2 nav-link <?php echo e(( $current_url=='purchase.create' ) ? 'nest_active' : ''); ?>"  ><i class="fa fa-plus nav-icon"></i><p><?php echo e(__('label.Purchase Create')); ?> </p></a>
              </li>
              <?php endif; ?>
             <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('purchase-list')): ?>
              <li class="nav-item">
                <a href="<?php echo e(url('purchase')); ?>" class="mr-2  nav-link <?php echo e(( $current_url=='purchase.index' ) ? 'nest_active' : ''); ?>" ><i class="fa fa-arrow-right nav-icon"></i><p><?php echo e(__('label.Purchase List')); ?></p></a>
              </li>
              <?php endif; ?>
              <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('purchase-return-create')): ?>
              <li class="nav-item">
                <a href="<?php echo e(url('purchase-return/create')); ?>" class="nav-link <?php echo e(( $current_url=='purchase-return.create' ) ? 'nest_active' : ''); ?>"  ><i class="fa fa-plus nav-icon"></i><p><?php echo e(__('label.purchase-return-create')); ?> </p></a>
              </li>
              <?php endif; ?>
             <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('purchase-return-list')): ?>
              <li class="nav-item">
                <a href="<?php echo e(url('purchase-return')); ?>" class="nav-link <?php echo e(( $current_url=='purchase-return.index' ) ? 'nest_active' : ''); ?>" ><i class="fa fa-arrow-right nav-icon"></i><p><?php echo e(__('label.purchase-return-list')); ?></p></a>
              </li>
              <?php endif; ?>
              
            </ul>
          </li>
          <?php endif; ?> 

          

          <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('sales-order-list')): ?> 
          <li class="nav-item <?php echo e(Route::is('sales-order.*') || Route::is('sales.*') || Route::is('sales-return.*')  ? 'menu-is-opening menu-open' : ''); ?>">
            <a href="#" class="nav-link <?php echo e(Route::is('sales-order.*') || Route::is('sales.*') || Route::is('sales-return.*')    ? 'main_nav_active' : ''); ?>">
              <i class="fa fa-shopping-cart nav-icon" aria-hidden="true"></i>
              <p>
                 <?php echo e(__('label.sales')); ?>

                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
             
             <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('sales-order-create')): ?>
              <li class="nav-item">
                <a href="<?php echo e(url('sales-order/create')); ?>" class="nav-link <?php echo e(( $current_url=='sales-order.create' ) ? 'nest_active' : ''); ?>"  ><i class="fa fa-arrow-right nav-icon"></i><p><?php echo e(__('label.sales-order-create')); ?> </p></a>
              </li>
              <?php endif; ?>
             <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('sales-order-list')): ?>
              <li class="nav-item">
                <a href="<?php echo e(url('sales-order')); ?>" class="nav-link <?php echo e(( $current_url=='sales-order.index' ) ? 'nest_active' : ''); ?>" ><i class="fa fa-arrow-right nav-icon"></i><p><?php echo e(__('label.sales-order-list')); ?></p></a>
              </li>
              <?php endif; ?>
              <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('sales-create')): ?>
              <li class="nav-item">
                <a href="<?php echo e(url('sales/create')); ?>" class="nav-link <?php echo e(( $current_url=='sales.create' ) ? 'nest_active' : ''); ?>"  ><i class="fa fa-arrow-right nav-icon"></i><p><?php echo e(__('label.sales-create')); ?> </p></a>
              </li>
              <?php endif; ?>
             <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('sales-list')): ?>
              <li class="nav-item">
                <a href="<?php echo e(url('sales')); ?>" class="nav-link <?php echo e(( $current_url=='sales.index' ) ? 'nest_active' : ''); ?>" ><i class="fa fa-arrow-right nav-icon"></i><p><?php echo e(__('label.sales-list')); ?></p></a>
              </li>
              <?php endif; ?>
              <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('sales-return-create')): ?>
              <li class="nav-item">
                <a href="<?php echo e(url('sales-return/create')); ?>" class="nav-link <?php echo e(( $current_url=='sales-return.create' ) ? 'nest_active' : ''); ?>"  ><i class="fa fa-arrow-right nav-icon"></i><p><?php echo e(__('label.sales-return-create')); ?> </p></a>
              </li>
              <?php endif; ?>
             <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('sales-return-list')): ?>
              <li class="nav-item">
                <a href="<?php echo e(url('sales-return')); ?>" class="nav-link <?php echo e(( $current_url=='sales-return.index' ) ? 'nest_active' : ''); ?>" ><i class="fa fa-arrow-right nav-icon"></i><p><?php echo e(__('label.sales-return-list')); ?></p></a>
              </li>
              <?php endif; ?>
              
            </ul>
          </li>
          <?php endif; ?>

          

         
          <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('damage-list')): ?> 
          <li class="nav-item <?php echo e(Route::is('damage.*')  ? 'menu-is-opening menu-open' : ''); ?>">
            <a href="#" class="nav-link <?php echo e(Route::is('damage.*')    ? 'main_nav_active' : ''); ?>">
              <i class="fa fa-file nav-icon" aria-hidden="true"></i>
              <p>
                 <?php echo e(__('label.damage')); ?>

                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
             
             <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('damage-create')): ?>
              <li class="nav-item">
                <a href="<?php echo e(url('damage/create')); ?>" class="nav-link <?php echo e(( $current_url=='damage.create' ) ? 'nest_active' : ''); ?>"  ><i class="fa fa-arrow-right nav-icon"></i><p><?php echo e(__('label.damage-create')); ?> </p></a>
              </li>
              <?php endif; ?>
             <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('damage-list')): ?>
              <li class="nav-item">
                <a href="<?php echo e(url('damage')); ?>" class="nav-link <?php echo e(( $current_url=='damage.index' ) ? 'nest_active' : ''); ?>" ><i class="fa fa-arrow-right nav-icon"></i><p><?php echo e(__('label.damage-list')); ?></p></a>
              </li>
              <?php endif; ?>
              
            </ul>
          </li>
          <?php endif; ?>
          <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('transfer-list')): ?> 
          <li class="nav-item <?php echo e(Route::is('transfer.*')  ? 'menu-is-opening menu-open' : ''); ?>">
            <a href="#" class="nav-link <?php echo e(Route::is('transfer.*')    ? 'main_nav_active' : ''); ?>">
              <i class="fas fa-tree nav-icon" aria-hidden="true"></i>
              <p>
                 <?php echo e(__('label.transfer')); ?>

                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
             
             <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('transfer-create')): ?>
              <li class="nav-item">
                <a href="<?php echo e(url('transfer/create')); ?>" class="nav-link <?php echo e(( $current_url=='transfer.create' ) ? 'nest_active' : ''); ?>"  ><i class="fa fa-arrow-right nav-icon"></i><p><?php echo e(__('label.transfer-create')); ?> </p></a>
              </li>
              <?php endif; ?>
             <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('transfer-list')): ?>
              <li class="nav-item">
                <a href="<?php echo e(url('transfer')); ?>" class="nav-link <?php echo e(( $current_url=='transfer.index' ) ? 'nest_active' : ''); ?>" ><i class="fa fa-arrow-right nav-icon"></i><p><?php echo e(__('label.transfer-list')); ?></p></a>
              </li>
              <?php endif; ?>
              
            </ul>
          </li>
          <?php endif; ?>
          <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('production-list')): ?> 
          <li class="nav-item <?php echo e(Route::is('production.*')  ? 'menu-is-opening menu-open' : ''); ?>">
            <a href="#" class="nav-link <?php echo e(Route::is('production.*')    ? 'main_nav_active' : ''); ?>">
              <i class="nav-icon fas fa-edit " aria-hidden="true"></i>
              <p>
                 <?php echo e(__('label.production')); ?>

                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
             
             <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('production-create')): ?>
              <li class="nav-item">
                <a href="<?php echo e(url('production/create')); ?>" class="nav-link <?php echo e(( $current_url=='production.create' ) ? 'nest_active' : ''); ?>"  ><i class="fa fa-arrow-right nav-icon"></i><p><?php echo e(__('label.production-create')); ?> </p></a>
              </li>
              <?php endif; ?>
             <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('production-list')): ?>
              <li class="nav-item">
                <a href="<?php echo e(url('production')); ?>" class="nav-link <?php echo e(( $current_url=='production.index' ) ? 'nest_active' : ''); ?>" ><i class="fa fa-arrow-right nav-icon"></i><p><?php echo e(__('label.production-list')); ?></p></a>
              </li>
              <?php endif; ?>
              
            </ul>
          </li>
          <?php endif; ?>
          <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('third-party-service-list')): ?> 
          <li class="nav-item <?php echo e(Route::is('third-party-service.*')  ? 'menu-is-opening menu-open' : ''); ?>">
            <a href="#" class="nav-link <?php echo e(Route::is('third-party-service.*')    ? 'main_nav_active' : ''); ?>">
              <i class="fa fa-credit-card nav-icon" aria-hidden="true"></i>
              <p>
                 <?php echo e(__('label.third-party-service')); ?>

                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
             
             <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('third-party-service-create')): ?>
              <li class="nav-item">
                <a href="<?php echo e(url('third-party-service/create')); ?>" class="nav-link <?php echo e(( $current_url=='third-party-service.create' ) ? 'nest_active' : ''); ?>"  ><i class="fa fa-arrow-right nav-icon"></i><p><?php echo e(__('label.third-party-service-create')); ?> </p></a>
              </li>
              <?php endif; ?>
             <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('third-party-service-list')): ?>
              <li class="nav-item">
                <a href="<?php echo e(url('third-party-service')); ?>" class="nav-link <?php echo e(( $current_url=='third-party-service.index' ) ? 'nest_active' : ''); ?>" ><i class="fa fa-arrow-right nav-icon"></i><p><?php echo e(__('label.third-party-service-list')); ?></p></a>
              </li>
              <?php endif; ?>
              
            </ul>
          </li>
          <?php endif; ?>

          <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('warranty-manage-list')): ?> 
          <li class="nav-item <?php echo e(Route::is('warranty-manage.*') || Route::is('individual-replacement.*') || Route::is('item-replace.*') ||  Route::is('individual-replacement.*') ||  Route::is('warranty-check')  ? 'menu-is-opening menu-open' : ''); ?>">
            <a href="#" class="nav-link <?php echo e(Route::is('warranty-manage.*') || Route::is('individual-replacement.*') || Route::is('item-replace.*') ||  Route::is('individual-replacement.*') ||  Route::is('warranty-check')   ? 'main_nav_active' : ''); ?>">
              <i class="fa fa-credit-card nav-icon" aria-hidden="true"></i>
              <p>
                 <?php echo e(__('label.warranty-manage')); ?>

                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
             
             <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('warranty-check')): ?>
              <li class="nav-item">
                <a href="<?php echo e(url('warranty-check')); ?>" class="nav-link <?php echo e(( $current_url=='warranty-check' ) ? 'nest_active' : ''); ?>"  ><i class="fa fa-arrow-right nav-icon"></i><p><?php echo e(__('label.warranty-check')); ?> </p></a>
              </li>
              <?php endif; ?>
             <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('warranty-manage-create')): ?>
              <li class="nav-item">
                <a href="<?php echo e(url('warranty-manage/create')); ?>" class="nav-link <?php echo e(( $current_url=='warranty-manage.create' ) ? 'nest_active' : ''); ?>"  ><i class="fa fa-arrow-right nav-icon"></i><p><?php echo e(__('label.warranty-manage-create')); ?> </p></a>
              </li>
              <?php endif; ?>
             <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('warranty-manage-list')): ?>
              <li class="nav-item">
                <a href="<?php echo e(url('warranty-manage')); ?>" class="nav-link <?php echo e(( $current_url=='warranty-manage.index' ) ? 'nest_active' : ''); ?>" ><i class="fa fa-arrow-right nav-icon"></i><p><?php echo e(__('label.warranty-manage-list')); ?></p></a>
              </li>
              <?php endif; ?>
              <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('item-replace-create')): ?>
              <li class="nav-item">
                <a href="<?php echo e(url('item-replace/create')); ?>" class="nav-link <?php echo e(( $current_url=='item-replace.create' ) ? 'nest_active' : ''); ?>"  ><i class="fa fa-arrow-right nav-icon"></i><p><?php echo e(__('label.item-replace-create')); ?> </p></a>
              </li>
              <?php endif; ?>
             <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('item-replace-list')): ?>
              <li class="nav-item">
                <a href="<?php echo e(url('item-replace')); ?>" class="nav-link <?php echo e(( $current_url=='item-replace.index' ) ? 'nest_active' : ''); ?>" ><i class="fa fa-arrow-right nav-icon"></i><p><?php echo e(__('label.item-replace-list')); ?></p></a>
              </li>
              <?php endif; ?>
              <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('individual-replacement-create')): ?>
              <li class="nav-item">
                <a href="<?php echo e(url('individual-replacement/create')); ?>" class="nav-link <?php echo e(( $current_url=='individual-replacement.create' ) ? 'nest_active' : ''); ?>"  ><i class="fa fa-arrow-right nav-icon"></i><p><?php echo e(__('label.individual-replacement-create')); ?> </p></a>
              </li>
              <?php endif; ?>
             <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('individual-replacement-list')): ?>
              <li class="nav-item">
                <a href="<?php echo e(url('individual-replacement')); ?>" class="nav-link <?php echo e(( $current_url=='individual-replacement.index' ) ? 'nest_active' : ''); ?>" ><i class="fa fa-arrow-right nav-icon"></i><p><?php echo e(__('label.individual-replacement-list')); ?></p></a>
              </li>
              <?php endif; ?>
             <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('w-item-receive-from-supp-list')): ?>
              <li class="nav-item">
                <a href="<?php echo e(url('w-item-receive-from-supp')); ?>" class="nav-link <?php echo e(( $current_url=='w-item-receive-from-supp.index' ) ? 'nest_active' : ''); ?>" ><i class="fa fa-arrow-right nav-icon"></i><p><?php echo e(__('label.w-item-receive-from-supp-list')); ?></p></a>
              </li>
              <?php endif; ?>
              
            </ul>
          </li>
          <?php endif; ?>
          
          

        <li class="nav-item ">
            <a href="<?php echo e(url('report-panel')); ?>" class="nav-link <?php echo e(( $current_url=='report-panel' ) ? 'nest_active' : ''); ?>"  >
              <i class="fas fa-chart-pie nav-icon" aria-hidden="true"></i>
              <p>
                  <?php echo e(__('label.report_panel')); ?>

              </p>
            </a>
          </li>

           <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('inventory-menu')): ?> 
          <li class="nav-item <?php echo e(Route::is('account-type.*') || Route::is('account-group.*') || Route::is('unit.*')  || Route::is('item-information.*')  || Route::is('lot-item-information.*')  || Route::is('account-ledger.*')  || Route::is('item-category.*')  || Route::is('warranty.*') || Route::is('transection_terms.*') || Route::is('vat-rules.*') || Route::is('labels-print') || Route::is('lot-item-information')  ? 'menu-is-opening menu-open' : ''); ?>">
            <a href="#" class="nav-link  Route::is('account-type.*') || Route::is('account-group.*')  || Route::is('unit.*')   || Route::is('item-information.*')    || Route::is('lot-item-information.*')   || Route::is('account-ledger.*')  || Route::is('item-category.*') || Route::is('warranty.*') || Route::is('transection_terms.*')  || Route::is('vat-rules.*')   || Route::is('labels-print')   || Route::is('lot-item-information')   ? 'active' : '' }}">
              <i class="fa fa-file nav-icon" aria-hidden="true"></i>
              <p>
               <?php echo e(__('label.Master')); ?>

                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
             <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('account-type-list')): ?>
              <li class="nav-item">
                <a href="<?php echo e(url('account-type')); ?>"  class="nav-link <?php echo e(Route::is('account-type.*')   ? 'active' : ''); ?>" ><i class="fa fa-sitemap nav-icon"></i> <p><?php echo e(__('label.Account Type')); ?></p></a>
              </li>
              <?php endif; ?>
             <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('account-group-list')): ?>
              <li class="nav-item">
                <a href="<?php echo e(url('account-group')); ?>"  class="nav-link <?php echo e(Route::is('account-group.*')   ? 'active' : ''); ?>" ><i class="fa fa-laptop nav-icon"></i> <p><?php echo e(__('label.Account Group')); ?></p></a>
              </li>
              <?php endif; ?>
             <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('account-ledger-list')): ?>
              <li class="nav-item">
                <a href="<?php echo e(url('account-ledger')); ?>"  class="nav-link <?php echo e(Route::is('account-ledger.*')   ? 'active' : ''); ?>" ><i class="fa fa-laptop nav-icon"></i> <p><?php echo e(__('label.Account Ledger')); ?></p></a>
              </li>
              <?php endif; ?>
             <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('item-category-list')): ?>
              <li class="nav-item">
                <a href="<?php echo e(url('item-category')); ?>"  class="nav-link <?php echo e(Route::is('item-category.*')   ? 'active' : ''); ?>" ><i class="fa fa-laptop nav-icon"></i> <p><?php echo e(__('label.Item Category')); ?></p></a>
              </li>
              <?php endif; ?>

             <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('unit-list')): ?>
              <li class="nav-item">
                <a href="<?php echo e(url('unit')); ?>"  class="nav-link <?php echo e(Route::is('unit.*')   ? 'active' : ''); ?>" ><i class="fa fa-laptop nav-icon"></i> <p><?php echo e(__('label.Unit Of Measurment')); ?></p></a>
              </li>
              <?php endif; ?>
             
             <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('warranty-list')): ?>
              <li class="nav-item">
                <a href="<?php echo e(url('warranty')); ?>"  class="nav-link <?php echo e(Route::is('warranty.*')   ? 'active' : ''); ?>" ><i class="fa fa-laptop nav-icon"></i> <p><?php echo e(__('label.Warranty')); ?></p></a>
              </li>
              <?php endif; ?>

             <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('transection_terms-list')): ?>
              <li class="nav-item">
                <a href="<?php echo e(url('transection_terms')); ?>"  class="nav-link <?php echo e(Route::is('transection_terms.*')   ? 'active' : ''); ?>" ><i class="fa fa-laptop nav-icon"></i> <p><?php echo e(__('label.Transection Terms')); ?></p></a>
              </li>
              <?php endif; ?>
             <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('vat-rules-list')): ?>
              <li class="nav-item">
                <a href="<?php echo e(url('vat-rules')); ?>"  class="nav-link <?php echo e(Route::is('vat-rules.*')   ? 'active' : ''); ?>" ><i class="fa fa-laptop nav-icon"></i> <p><?php echo e(__('label.Vat Rules')); ?></p></a>
              </li>
              <?php endif; ?>
             <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('item-information-list')): ?>
              <li class="nav-item">
                <a href="<?php echo e(url('item-information')); ?>"  class="nav-link <?php echo e(Route::is('item-information.*')   ? 'active' : ''); ?>" ><i class="fa fa-laptop nav-icon"></i> <p><?php echo e(__('label.Item Information')); ?></p></a>
              </li>
              <?php endif; ?>
             <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('lot-item-information')): ?>
              <li class="nav-item">
                <a href="<?php echo e(url('lot-item-information')); ?>"  class="nav-link <?php echo e(Route::is('lot-item-information')   ? 'active' : ''); ?>" ><i class="fa fa-laptop nav-icon"></i> <p><?php echo e(__('label.Lot Item Information')); ?></p></a>
              </li>
              <?php endif; ?>
             <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('labels-print')): ?>
              <li class="nav-item">
                <a href="<?php echo e(url('labels-print')); ?>"  class="nav-link <?php echo e(Route::is('labels-print')   ? 'active' : ''); ?>" ><i class="fa fa-laptop nav-icon"></i> <p><?php echo e(__('label.Labels Print')); ?></p></a>
              </li>
              <?php endif; ?>

             
            </ul>
          </li>
          <?php endif; ?>
          <li class="nav-item <?php echo e(Route::is('roles.*') || Route::is('users.*') || Route::is('sms-send')  || Route::is('all-lock') || Route::is('invoice-prefix') || Route::is('database-backup') || Route::is('admin-settings') || Route::is('branch.*') || Route::is('social_media.*') || Route::is('cost-center.*') || Route::is('store-house.*')  ? 'menu-is-opening menu-open' : ''); ?>">
            <a href="#" class="nav-link <?php echo e(Route::is('roles.*') || Route::is('users.*') || Route::is('admin-settings') || Route::is('branch.*')  || Route::is('cost-center.*')|| Route::is('sms-send') || Route::is('invoice-prefix')  || Route::is('database-backup')  || Route::is('all-lock')  || Route::is('store-house.*')   ? 'active' : ''); ?>">
            
               <i class="fas fa-cog nav-icon"></i> 
              <p>
                <?php echo e(__('label.Settings')); ?>

                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
               <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('admin-settings')): ?>
              <li class="nav-item">

                <a href="<?php echo e(url('admin-settings')); ?>" class="nav-link <?php echo e(Route::is('admin-settings')   ? 'active' : ''); ?>">
                  <i class="fa fa-asterisk nav-icon"></i> 
                  <p><?php echo e(__('label.General Settings')); ?></p>
                </a>
              </li>
              <?php endif; ?>
            
               <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('bulk-sms')): ?>
              <li class="nav-item">

                <a href="<?php echo e(url('sms-send')); ?>" class="nav-link <?php echo e(Route::is('sms-send')   ? 'active' : ''); ?>">
                  <i class="fa fa-asterisk nav-icon"></i> 
                  <p><?php echo e(__('label.SMS SEND')); ?></p>
                </a>
              </li>
              <?php endif; ?>
               <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('invoice-prefix')): ?>
              <li class="nav-item">

                <a href="<?php echo e(url('invoice-prefix')); ?>" class="nav-link <?php echo e(Route::is('invoice-prefix')   ? 'active' : ''); ?>">
                  <i class="fa fa-asterisk nav-icon"></i> 
                  <p><?php echo e(__('label.Invoice Prefix')); ?></p>
                </a>
              </li>
              <?php endif; ?>
              <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('social_media-list')): ?>
              <li class="nav-item">

                <a href="<?php echo e(url('social_media')); ?>" class="nav-link <?php echo e(Route::is('social_media.*')   ? 'active' : ''); ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Social Media</p>
                </a>
              </li>
              <?php endif; ?>
               <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('role-list')): ?>
              <li class="nav-item">
                <a href="<?php echo e(url('roles')); ?>" class="nav-link <?php echo e(Route::is('roles.*')   ? 'active' : ''); ?>">
                  <i class="fa fa-server nav-icon"></i>
                  <p><?php echo e(__('label.Roles')); ?></p>
                </a>
              </li>
              <?php endif; ?>
              <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('user-list')): ?>
              <li class="nav-item">
                <a href="<?php echo e(url('users')); ?>" class="nav-link <?php echo e(Route::is('users.*')   ? 'active' : ''); ?>">
                  <i class="fas fa-users nav-icon"></i>
                  <p><?php echo e(__('label.Users')); ?></p>
                </a>
              </li>
              <?php endif; ?>
              <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('branch-list')): ?>
              <li class="nav-item">
                <a href="<?php echo e(url('branch')); ?>" class="nav-link <?php echo e(Route::is('branch.*')   ? 'active' : ''); ?>">
                 <i class="fa fa-share-alt nav-icon"></i>
                  <p><?php echo e(__('label.Branch')); ?> </p>
                </a>
              </li>
              <?php endif; ?>
              <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('cost-center-list')): ?>
              <li class="nav-item">
                <a href="<?php echo e(url('cost-center')); ?>" class="nav-link <?php echo e(Route::is('cost-center.*')   ? 'active' : ''); ?>">
                  <i class="fa fa-adjust nav-icon"></i>
                  <p><?php echo e(__('label.Cost center')); ?></p>
                </a>
              </li>
              <?php endif; ?>
              <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('store-house-list')): ?>
              <li class="nav-item">
                <a href="<?php echo e(url('store-house')); ?>" class="nav-link <?php echo e(Route::is('store-house.*')   ? 'active' : ''); ?>">
                  <i class="fas fa-store  nav-icon"></i>
                  <p><?php echo e(__('label.Store House')); ?></p>
                </a>
              </li>
              <?php endif; ?>
              <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('lock-permission')): ?>
              <li class="nav-item">
                <a href="<?php echo e(url('all-lock')); ?>" class="nav-link <?php echo e(Route::is('all-lock')   ? 'active' : ''); ?>">
                  <i class="fas fa-store  nav-icon"></i>
                  <p><?php echo e(__('label.Transection Lock System')); ?></p>
                </a>
              </li>
              <?php endif; ?>
              <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('database-backup')): ?>
              <li class="nav-item">
                <a href="<?php echo e(url('database-backup')); ?>" class="nav-link <?php echo e(Route::is('database-backup')   ? 'active' : ''); ?>">
                  <i class="fas fa-store  nav-icon"></i>
                  <p><?php echo e(__('label.Data Backup')); ?></p>
                </a>
              </li>
              <?php endif; ?>
            </ul>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside><?php /**PATH D:\xampp\htdocs\own\inv-acc-hrm\resources\views/backend/layouts/main_sidebar.blade.php ENDPATH**/ ?>