<nav class="main-header navbar navbar-expand  fixed-top  navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav" >
      
     
      <a href="<?php echo e(url('home')); ?>" class="brand-link _project_main_nav_logo">
      <img src="<?php echo e(asset('/')); ?><?php echo e($settings->logo ?? ''); ?>" alt="<?php echo e($settings->name ?? ''); ?>" class="brand-image  elevation-3"  >
      <span  class="project_title"></span>
    </a>
    <li class="nav-item">
        <a class="nav-link _pushmenu" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
     
 <!-- Messages Dropdown Menu -->
      <li class="nav-item ">
         <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('pos-sales-list')): ?>
          
           <a  href="<?php echo e(url('pos-sales')); ?>" class="dropdown-item custom_nav_item" title="POS Sales">
            <i class="fa fa-shopping-cart " style="margin-top: 9px;" aria-hidden="true"></i> 
          </a>
         <?php endif; ?>
       </li>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('hrm-module')): ?> 
      <li class="nav-item dropdown remove_from_header">
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
       <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('account-menu')): ?> 
      <li class="nav-item dropdown remove_from_header">
        <a class="nav-link" data-toggle="dropdown" href="#">
           <?php echo e(__('label.Accounts')); ?> <i class="right fas fa-angle-down"></i>
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
    <?php endif; ?>
     
      <!-- Messages Dropdown Menu -->
      <li class="nav-item dropdown"> </li>
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
         
          
       <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('sales-order-list')): ?>
         <div class="dropdown-divider"></div>
          
        <div style="display: flex;">
           <a href="<?php echo e(url('sales-order')); ?>" class="dropdown-item">
            <i class="fa fa-shopping-cart mr-2" aria-hidden="true"></i> <?php echo e(__('label.sales-order-list')); ?>

          </a>
          <a  href="<?php echo e(route('sales-order.create')); ?>" 
          class="dropdown-item text-right  "> 
            <i class="nav-icon fas fa-plus"></i> </a>

          </a>
        </div>
         <?php endif; ?>
          <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('pos-sales-list')): ?>
         <div class="dropdown-divider"></div>
          
        <div style="display: flex;">
           <a href="<?php echo e(url('sales')); ?>" class="dropdown-item">
            <i class="fa fa-shopping-cart mr-2" aria-hidden="true"></i> <?php echo e(__('label.pos sales-list')); ?>

          </a>
           <a  href="<?php echo e(url('pos-sales')); ?>" class="dropdown-item text-right">
            <i class="fa fa-shopping-cart " style="color: green;margin-top: 9px;" aria-hidden="true"></i> 
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
        
       <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('damage-list')): ?>
         <div class="dropdown-divider"></div>  
          
        <div style="display: flex;">
           <a href="<?php echo e(url('damage')); ?>" class="dropdown-item">
            <i class="fa fa-recycle mr-2" aria-hidden="true"></i> <?php echo e(__('label.damage_adjustment')); ?>

          </a>
           <a  href="<?php echo e(route('damage.create')); ?>" class="dropdown-item text-right">
            <i class="nav-icon fas fa-plus"></i>
          </a>
        </div>
         <?php endif; ?>
          <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('transfer-list')): ?>
         <div class="dropdown-divider"></div>  
        <div style="display: flex;">
           <a href="<?php echo e(url('transfer')); ?>" class="dropdown-item">
            <i class="fa fa-recycle mr-2" aria-hidden="true"></i> <?php echo e(__('label.project_to_transfer')); ?>

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
    <?php endif; ?>
       <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('inventory-report')): ?> 
      <li class="nav-item dropdown remove_from_header">
        <a class="nav-link" data-toggle="dropdown" href="#">
          
          <?php echo e(__('label.Inventory Report')); ?> <i class="right fas fa-angle-down"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
            
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('warranty-check')): ?>
          
           <div style="display: flex;">
           <a href="<?php echo e(url('warranty-check')); ?>" class="dropdown-item">
            <i class="fa fa-list-alt mr-2" aria-hidden="true"></i> <?php echo e(__('label.warranty-check')); ?>

          </a>
        </div>
         <?php endif; ?>
         
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('bill-party-statement')): ?>
          
           <div style="display: flex;">
           <a href="<?php echo e(url('bill-party-statement')); ?>" class="dropdown-item">
            <i class="fa fa-list-alt mr-2" aria-hidden="true"></i> <?php echo e(__('label.Bill of Supplier Statement')); ?>

          </a>
        </div>
         <?php endif; ?>
         <div class="dropdown-divider"></div>
          
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('date-wise-purchase')): ?>
        <div style="display: flex;">
           <a href="<?php echo e(url('date-wise-purchase')); ?>" class="dropdown-item">
            <i class="fa fa-list-alt mr-2" aria-hidden="true"></i> <?php echo e(__('label.Date Wise Purchase')); ?>

          </a>
        </div>
         
         <?php endif; ?>
         
    
           
       <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('purchase-return-detail')): ?>
        <div style="display: flex;">
           <a href="<?php echo e(url('purchase-return-detail')); ?>" class="dropdown-item">
            <i class="fa fa-list-alt mr-2" aria-hidden="true"></i> <?php echo e(__('label.Purchase Return Detail')); ?>

          </a>
        </div>
         <?php endif; ?> 
         
       <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('date-wise-sales')): ?>
       <div class="dropdown-divider"></div>  
        <div style="display: flex;">
           <a href="<?php echo e(url('date-wise-sales')); ?>" class="dropdown-item">
            <i class="fa fa-list-alt mr-2" aria-hidden="true"></i> <?php echo e(__('label.Date Wise Sales')); ?>

          </a>
        </div>
         <?php endif; ?> 
       <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('actual-sales-report')): ?>
        <div style="display: flex;">
           <a href="<?php echo e(url('filter-actual-sales')); ?>" class="dropdown-item">
            <i class="fa fa-list-alt mr-2" aria-hidden="true"></i> <?php echo e(__('label.actual-sales-report')); ?>

          </a>
        </div>
         <?php endif; ?> 

       <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('date-wise-restaurant-sales')): ?>
       <div class="dropdown-divider"></div>  
        <div style="display: flex;">
           <a href="<?php echo e(url('date-wise-restaurant-sales')); ?>" class="dropdown-item">
            <i class="fa fa-list-alt mr-2" aria-hidden="true"></i> <?php echo e(__('label.Date Wise Restaurant Sales')); ?>

          </a>
        </div>
         <?php endif; ?> 
          
      
          
       <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('sales-return-detail')): ?>
        <div style="display: flex;">
           <a href="<?php echo e(url('sales-return-detail')); ?>" class="dropdown-item">
            <i class="fa fa-list-alt mr-2" aria-hidden="true"></i> <?php echo e(__('label.Sales Return Details')); ?>

          </a>
        </div>
         <?php endif; ?> 
         <div class="dropdown-divider"></div>  
       <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('stock-possition')): ?>
        <div style="display: flex;">
           <a href="<?php echo e(url('stock-possition')); ?>" class="dropdown-item">
            <i class="fa fa-list-alt mr-2" aria-hidden="true"></i><?php echo e(__('label.Stock Possition')); ?>

          </a>
        </div>
         <?php endif; ?> 
          
       <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('stock-ledger')): ?>
        <div style="display: flex;">
           <a href="<?php echo e(url('stock-ledger')); ?>" class="dropdown-item">
            <i class="fa fa-list-alt mr-2" aria-hidden="true"></i><?php echo e(__('label.Stock Ledger')); ?>

          </a>
        </div>
         <?php endif; ?> 
       <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('stock-ledger')): ?>
        <div style="display: flex;">
           <a href="<?php echo e(url('single-stock-ledger')); ?>" class="dropdown-item">
            <i class="fa fa-list-alt mr-2" aria-hidden="true"></i><?php echo e(__('label.single-stock-ledger')); ?>

          </a>
        </div>
         <?php endif; ?> 
          
       <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('stock-value')): ?>
        <div style="display: flex;">
           <a href="<?php echo e(url('stock-value')); ?>" class="dropdown-item">
            <i class="fa fa-list-alt mr-2" aria-hidden="true"></i><?php echo e(__('label.Stock Value')); ?>

          </a>
        </div>
         <?php endif; ?> 
          
       <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('stock-value-register')): ?>
        <div style="display: flex;">
           <a href="<?php echo e(url('stock-value-register')); ?>" class="dropdown-item">
            <i class="fa fa-list-alt mr-2" aria-hidden="true"></i><?php echo e(__('label.Stock Value Register')); ?>

          </a>
        </div>
         <?php endif; ?> 
          <div class="dropdown-divider"></div>
       <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('gross-profit')): ?>
        <div style="display: flex;">
           <a href="<?php echo e(url('gross-profit')); ?>" class="dropdown-item">
            <i class="fa fa-list-alt mr-2" aria-hidden="true"></i><?php echo e(__('label.Gross Profit')); ?>

          </a>
        </div>
         <?php endif; ?>
         <div class="dropdown-divider"></div>  
       <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('expired-item')): ?>
        <div style="display: flex;">
           <a href="<?php echo e(url('expired-item')); ?>" class="dropdown-item">
            <i class="fa fa-list-alt mr-2" aria-hidden="true"></i><?php echo e(__('label.Expired Item')); ?>

          </a>
        </div>
         <?php endif; ?>   
       <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('shortage-item')): ?>
        <div style="display: flex;">
           <a href="<?php echo e(url('shortage-item')); ?>" class="dropdown-item">
            <i class="fa fa-list-alt mr-2" aria-hidden="true"></i><?php echo e(__('label.Shortage Item')); ?>

          </a>
        </div>
         <?php endif; ?>  
      <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('barcode-history')): ?>
        <div style="display: flex;">
           <a href="<?php echo e(url('barcode-history')); ?>" class="dropdown-item">
            <i class="fa fa-list-alt mr-2" aria-hidden="true"></i><?php echo e(__('label.Barcode History')); ?>

          </a>
        </div>
      <?php endif; ?> 
         
      <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('user-wise-collection-payment')): ?>
      <div class="dropdown-divider"></div>
          <a href="<?php echo e(url('user-wise-collection-payment')); ?>" class="dropdown-item">
            <i class="fa fa-sitemap mr-2" aria-hidden="true"></i><?php echo e(__('label.User Wise Collection Payment')); ?>

          </a>
      <?php endif; ?>
          
           <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('date-wise-invoice-print')): ?>
           <div class="dropdown-divider"></div>
          <a href="<?php echo e(url('date-wise-invoice-print')); ?>" class="dropdown-item">
            <i class="fa fa-sitemap mr-2" aria-hidden="true"></i><?php echo e(__('label.Date Wise Invoice Print')); ?>

          </a>
         <?php endif; ?>
        
          <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('date-wise-restaurant-invoice-print')): ?>
            <div class="dropdown-divider"></div>
          <a href="<?php echo e(url('date-wise-restaurant-invoice-print')); ?>" class="dropdown-item">
            <i class="fa fa-sitemap mr-2" aria-hidden="true"></i><?php echo e(__('label.Date Wise Restaurant Invoice Print')); ?>

          </a>
         <?php endif; ?>
          
           <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delivery-man-sales')): ?>
           <div class="dropdown-divider"></div>
          <a href="<?php echo e(url('delivery-man-sales')); ?>" class="dropdown-item">
            <i class="fa fa-sitemap mr-2" aria-hidden="true"></i><?php echo e(__('label.Delivery Man Sales')); ?>

             </a>
         <?php endif; ?>
          
           <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delivery-man-sales-return')): ?>
           <div class="dropdown-divider"></div>
          <a href="<?php echo e(url('delivery-man-sales-return')); ?>" class="dropdown-item">
            <i class="fa fa-sitemap mr-2" aria-hidden="true"></i><?php echo e(__('label.Delivery Man Sales Return')); ?>

             </a>
         <?php endif; ?>
          
           <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('sales-man-sales-return')): ?>
           <div class="dropdown-divider"></div>
          <a href="<?php echo e(url('sales-man-sales-return')); ?>" class="dropdown-item">
            <i class="fa fa-sitemap mr-2" aria-hidden="true"></i><?php echo e(__('label.Sales Man Sales Return')); ?> </a>
         <?php endif; ?>
          
           <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('sales-man-invoice')): ?>
           <div class="dropdown-divider"></div>
          <a href="<?php echo e(url('sales-man-invoice')); ?>" class="dropdown-item">
            <i class="fa fa-sitemap mr-2" aria-hidden="true"></i><?php echo e(__('label.Sales Man Invoice')); ?> </a>
         <?php endif; ?>
          
           <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delivery-man-sales-invoice')): ?>
           <div class="dropdown-divider"></div>
          <a href="<?php echo e(url('delivery-man-sales-invoice')); ?>" class="dropdown-item">
            <i class="fa fa-sitemap mr-2" aria-hidden="true"></i><?php echo e(__('label.Delivery Man Sales Invoice')); ?> </a>
         <?php endif; ?>

          <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('restaurant-report-menu')): ?>
      
       <p style="text-align: center;"><b>Restaurant Report</b></p>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('day-wise-summary-report')): ?>
        <div class="dropdown-divider"></div>
        <div style="display: flex;">
           <a href="<?php echo e(url('day-wise-summary-report')); ?>" class="dropdown-item">
            <i class="fa fa-file mr-2" aria-hidden="true"></i>Work Period Sales Summary Report 
          </a>
          
           
        </div>
        <?php endif; ?>
         <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('item-sales-report')): ?>
        <div class="dropdown-divider"></div>
        <div style="display: flex;">
           <a href="<?php echo e(url('item-sales-report')); ?>" class="dropdown-item">
            <i class="fa fa-file mr-2" aria-hidden="true"></i>Item Sales Report 
          </a>
        </div>
         <?php endif; ?>
         <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('detail-item-sales-report')): ?>
        <div class="dropdown-divider"></div>
        <div style="display: flex;">
           <a href="<?php echo e(url('detail-item-sales-report')); ?>" class="dropdown-item">
            <i class="fa fa-file mr-2" aria-hidden="true"></i>Detail Item Sales Report 
          </a>
        </div>
           <?php endif; ?>
        <?php endif; ?>


      </li>
    <?php endif; ?>
      
      <!-- Notifications Dropdown Menu -->
       <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('account-report-menu')): ?> 
      <li class="nav-item dropdown remove_from_header">
        <a class="nav-link" data-toggle="dropdown" href="#">
          
          <?php echo e(__('label.Accounts Report')); ?> <i class="right fas fa-angle-down"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          
           <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('day-book')): ?>
           <div class="dropdown-divider"></div>
          <a href="<?php echo e(url('day-book')); ?>" class="dropdown-item">
            <i class="fa fa-sitemap mr-2" aria-hidden="true"></i><?php echo e(__('label.Day Book')); ?>

          </a>
         <?php endif; ?>
          
           <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('cash-book')): ?>
           <div class="dropdown-divider"></div>
          <a href="<?php echo e(url('cash-book')); ?>" class="dropdown-item">
            <i class="fa fa-sitemap mr-2" aria-hidden="true"></i><?php echo e(__('label.Cash Book')); ?>

          </a>
         <?php endif; ?>
          
           <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('bank-book')): ?>
           <div class="dropdown-divider"></div>
          <a href="<?php echo e(url('bank-book')); ?>" class="dropdown-item">
            <i class="fa fa-sitemap mr-2" aria-hidden="true"></i><?php echo e(__('label.Bank Book')); ?>

          </a>
         <?php endif; ?>
           <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('receipt-payment')): ?>
          <div class="dropdown-divider"></div>
          <a href="<?php echo e(url('receipt-payment')); ?>" class="dropdown-item">
            <i class="fa fa-sitemap mr-2" aria-hidden="true"></i><?php echo e(__('label.Receipt & Payment')); ?>

          </a>
         <?php endif; ?>
           <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('ledger-report')): ?>
          <div class="dropdown-divider"></div>
          <a href="<?php echo e(url('ledger-report')); ?>" class="dropdown-item">
            <i class="fa fa-sitemap mr-2" aria-hidden="true"></i><?php echo e(__('label.Ledger Report')); ?>

          </a>
         <?php endif; ?>
         
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('group-ledger')): ?>
         <div class="dropdown-divider"></div>
          <a href="<?php echo e(url('group-ledger')); ?>" class="dropdown-item">
            <i class="fa fa-sitemap mr-2" aria-hidden="true"></i> <?php echo e(__('label.Group Ledger Report')); ?>

          </a>
         <?php endif; ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('ledger-summary-report')): ?>
         <div class="dropdown-divider"></div>
          <a href="<?php echo e(url('filter-ledger-summary')); ?>" class="dropdown-item">
            <i class="fa fa-sitemap mr-2" aria-hidden="true"></i>  <?php echo e(__('label.Ledger Summary Report')); ?>

          </a>
         <?php endif; ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('income-statement')): ?>
         <div class="dropdown-divider"></div>
          <a href="<?php echo e(url('income-statement')); ?>" class="dropdown-item">
            <i class="fa fa-sitemap mr-2" aria-hidden="true"></i> <?php echo e(__('label.Income Statement')); ?>

          </a>
         <?php endif; ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('trail-balance')): ?>
         <div class="dropdown-divider"></div>
          <a href="<?php echo e(url('trail-balance')); ?>" class="dropdown-item">
            <i class="fa fa-sitemap mr-2" aria-hidden="true"></i><?php echo e(__('label.Trail Balance')); ?>

          </a>
         <?php endif; ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('work-sheet')): ?>
         <div class="dropdown-divider"></div>
          <a href="<?php echo e(url('work-sheet')); ?>" class="dropdown-item">
            <i class="fa fa-sitemap mr-2" aria-hidden="true"></i> <?php echo e(__('label.Work Sheet')); ?>

          </a>
         <?php endif; ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('balance-sheet')): ?>
         <div class="dropdown-divider"></div>
          <a href="<?php echo e(url('balance-sheet')); ?>" class="dropdown-item">
            <i class="fa fa-sitemap mr-2" aria-hidden="true"></i> <?php echo e(__('label.Balance Sheet')); ?>

          </a>
         <?php endif; ?> 
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('chart-of-account')): ?>
         <div class="dropdown-divider"></div>
          <a href="<?php echo e(url('chart-of-account')); ?>" class="dropdown-item">
            <i class="fa fa-sitemap mr-2" aria-hidden="true"></i> <?php echo e(__('label.Chart of Account')); ?>

          </a>
         <?php endif; ?>  
              
      </li>
    <?php endif; ?>
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
          
           <a  href="<?php echo e(route('account-ledger.create')); ?>" class="dropdown-item text-right attr_base_create_url" data-toggle="modal" 
               data-target="#commonEntryModal_item"
               attr_base_create_url="<?php echo e(route('account-ledger.create')); ?>" >
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
           <a  href="#None" 
           class="dropdown-item text-right attr_base_create_url" 
               data-toggle="modal" 
               data-target="#commonEntryModal_item" 
               attr_base_create_url="<?php echo e(route('item-category.create')); ?>"
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
          <a  href="#None" 
           class="dropdown-item text-right attr_base_create_url" 
               data-toggle="modal" 
               data-target="#commonEntryModal_item" 
               attr_base_create_url="<?php echo e(route('unit.create')); ?>"
                >
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
           <a  href="#None" 
           class="dropdown-item text-right attr_base_create_url" 
               data-toggle="modal" 
               data-target="#commonEntryModal_item" 
               attr_base_create_url="<?php echo e(route('warranty.create')); ?>"
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
          <a  href="#None" 
           class="dropdown-item text-right attr_base_create_url" 
               data-toggle="modal" 
               data-target="#commonEntryModal_item" 
               attr_base_create_url="<?php echo e(route('transection_terms.create')); ?>"
                >
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
           <a  href="#None" 
           class="dropdown-item text-right attr_base_create_url" 
               data-toggle="modal" 
               data-target="#commonEntryModal_item" 
               attr_base_create_url="<?php echo e(route('vat-rules.create')); ?>"
                >
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
           <a  href="#None" 
           class="dropdown-item text-right attr_base_create_url" 
               data-toggle="modal" 
               data-target="#commonEntryModal_item" 
               attr_base_create_url="<?php echo e(route('item-information.create')); ?>"
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
    <?php endif; ?>
      
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
            <a   href="#None" 
          class="dropdown-item text-right attr_base_create_url"
            data-toggle="modal" data-target="#commonEntryModal_item" 
            attr_base_create_url="<?php echo e(route('cost-center.create')); ?>"> 
            <i class="nav-icon fas fa-plus"></i> </a>
        </div>
          
         <?php endif; ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('store-house-list')): ?>
         <div class="dropdown-divider"></div>
        <div style="display: flex;">
          <a href="<?php echo e(url('store-house')); ?>" class="dropdown-item">
           <i class="fa fa-adjust mr-2" aria-hidden="true"></i> <?php echo e(__('label.Store House')); ?> 
          </a>
            <a   href="#None" 
          class="dropdown-item text-right attr_base_create_url"
            data-toggle="modal" data-target="#commonEntryModal_item" 
            attr_base_create_url="<?php echo e(route('store-house.create')); ?>"> 
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
        
              
              
      </li>
      
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-user"></i>
          
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <span class="dropdown-item dropdown-header">User Name :  <b><?php echo e(Auth::user()->name ?? ''); ?></b></span>
          <div class="dropdown-divider"></div>
          <?php if(Auth::user()->ref_id ==0): ?>
          <button  type="button" 
                                  attr_base_edit_url="<?php echo e(route('users.edit',Auth::user()->id)); ?>"
                                  data-toggle="modal" 
                                  data-target="#commonEntryModal_item" 
                                  class="btn btn-sm btn-default attr_base_edit_url mr-1"><i class="fa fa-pen "></i>Update Profile</button>
          <?php else: ?>
          <button  type="button" 
                                  attr_base_edit_url="<?php echo e(route('users.edit',Auth::user()->id)); ?>"
                                  data-toggle="modal" 
                                  data-target="#commonEntryModal_item" 
                                  class="btn btn-sm btn-default attr_base_edit_url mr-1"><i class="fa fa-pen "></i>Update Profile</button>
            <!-- <a class="dropdown-item text-center" 
                        href="<?php echo e(url('branch_user')); ?>/<?php echo e(Auth::user()->id); ?>/edit"
                        >
                  <?php echo e(__('Profile')); ?>

            </a> -->
          <?php endif; ?>
        <div class="dropdown-divider"></div>
                <a class="dropdown-item text-center" 
                        href="<?php echo e(route('logout')); ?>"
                        onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                  <?php echo e(__('Logout')); ?>

                </a>


                  <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" style="display: none;">
                        <?php echo csrf_field(); ?>
                  </form>
             
          <div class="dropdown-divider"></div>
          
        </div>
      </li>
      <li class="nav-item">
        <a  class="nav-link full_screen_show" data-widget="fullscreen" href="#" role="button" >
          <i class="fas fa-expand-arrows-alt"></i>
        </a>
      </li>
    </ul>
  </nav><?php /**PATH D:\xampp\htdocs\solar-acc-invenoty\resources\views/backend/layouts/navbar.blade.php ENDPATH**/ ?>