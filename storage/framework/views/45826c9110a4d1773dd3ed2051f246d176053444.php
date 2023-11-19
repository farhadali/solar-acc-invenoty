<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav" >
      
     
    <li class="nav-item">
        <a class="nav-link " data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
     
 <!-- Messages Dropdown Menu -->
    
      <?php echo $__env->make('backend.layouts.hrm_module', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
     <?php echo $__env->make('backend.layouts.account_module', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
     <?php echo $__env->make('backend.layouts.rlp_module', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
     
      <!-- Messages Dropdown Menu -->
      <li class="nav-item dropdown"> </li>
      <?php echo $__env->make('backend.layouts.inventory_module', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
   <li class="nav-item " style="margin-top:5px;">
           <a  href="<?php echo e(url('report-panel')); ?>" class="dropdown-item custom_nav_item" >
            <span ><?php echo e(__('label.report_panel')); ?> </span>
          </a>
         
       </li>
   <?php echo $__env->make('backend.layouts.master_module', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>   
   <?php echo $__env->make('backend.layouts.setting_module', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>   
     
      
      
      
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-user"></i>
          
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <span class="dropdown-item dropdown-header">User Name :  <b><?php echo e(Auth::user()->name ?? ''); ?></b></span>
          <div class="dropdown-divider"></div>
          <div class="text-center">
             <a href="<?php echo e(url('user-profile')); ?>"><?php echo e(__('label.profile')); ?></a>
          </div>
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
  </nav><?php /**PATH D:\xampp\htdocs\own\inv-acc-hrm\resources\views/backend/layouts/coel_nav.blade.php ENDPATH**/ ?>