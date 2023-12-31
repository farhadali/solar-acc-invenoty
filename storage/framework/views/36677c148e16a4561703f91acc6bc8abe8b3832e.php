
<?php $__env->startSection('title',$settings->name ?? ''); ?>
<?php $__env->startSection('content'); ?>
<?php
$users = Auth::user();
?>
<!-- Content Header (Page header) -->


    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 _page_name">Dashboard </h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a class="_page_name" href="<?php echo e(url('home')); ?>">Home</a></li>
              <li class="breadcrumb-item _page_name ">Dashboard </li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <style type="text/css">
      .card-title {
        float: none;
    text-align: center !important;

    font-size: 1.1rem;
    font-weight: 400;
    margin: 0;
    padding: 5px;
}
.card-body{
  background: #fff;
  padding:10px;
}
    </style>
    <!-- /.content-header -->
<div class="content" >
      <div class="container-fluid">
        <div class="row">
          <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('quick-link')): ?>
          <div class="col-md-6">
            <div class="card ">
              <div class="card-header border-0">
                <h3 class="card-title">Quick Link</h3>
                <div class="card-tools"></div>
              </div>
              <div class="card-body table-responsive p-0 info-box">
                  <table class="table table-striped table-valign-middle">
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('voucher-list')): ?>
                    <tr>
                      <th>
                         
                          <div style="display: flex;">
                           <a href="<?php echo e(url('voucher')); ?>" class="dropdown-item">
                              <i class="fa fa-fax mr-2" aria-hidden="true"></i> Voucher
                            </a>
                             <a  href="<?php echo e(route('voucher.create')); ?>" class="dropdown-item text-right">
                              <i class="nav-icon fas fa-plus"></i>
                            </a>
                          </div>
                          
                      </th>
                  </tr>
                   <?php endif; ?> 
                   <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('purchase-list')): ?>
                    <tr>
                      <th>
                        
                           <div style="display: flex;">
                           <a href="<?php echo e(url('purchase')); ?>" class="dropdown-item">
                            <i class="fa fa-list-alt mr-2" aria-hidden="true"></i> <?php echo e(__('label.material_receive')); ?>

                          </a>

                             <a  href="<?php echo e(route('purchase.create')); ?>" class="dropdown-item text-right " > 
            <i class="nav-icon fas fa-plus"></i> </a>
                        </div>
                         
                      </th>
                  </tr>
                  <?php endif; ?>
                   <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('purchase-return-list')): ?>
                    <tr>
                      <th>
                        
                            <div style="display: flex;">
                               <a href="<?php echo e(url('purchase-return')); ?>" class="dropdown-item">
                                <i class="fa fa-list-alt mr-2" aria-hidden="true"></i>  <?php echo e(__('label.material_return')); ?>

                              </a>
                              <a  href="<?php echo e(route('purchase-return.create')); ?>" 
          class="dropdown-item text-right  " > 
            <i class="nav-icon fas fa-plus"></i> </a>
                               
                            </div>
                            
                      </th>
                  </tr>
                   <?php endif; ?>
                  <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('sales-list')): ?>
                    <tr>
                      <th>
                         
                        <div style="display: flex;">
                           <a href="<?php echo e(url('sales')); ?>" class="dropdown-item">
                            <i class="fa fa-shopping-cart mr-2" aria-hidden="true"></i> <?php echo e(__('label.material_issue')); ?>

                          </a>
                           <a  href="<?php echo e(route('sales.create')); ?>" class="dropdown-item text-right">
                            <i class="nav-icon fas fa-plus"></i>
                          </a>
                        </div>
                        
                      </th>
                  </tr>
                   <?php endif; ?> 
                  <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('restaurant-sales-list')): ?>
                    <tr>
                      <th>
                         
                        <div style="display: flex;">
                           <a href="<?php echo e(url('restaurant-sales')); ?>" class="dropdown-item">
                            <i class="fa fa-shopping-cart mr-2" aria-hidden="true"></i> Restaurant Sales
                          </a>
                           <a  href="<?php echo e(route('restaurant-sales.create')); ?>" class="dropdown-item text-right">
                            <i class="nav-icon fas fa-plus"></i>
                          </a>
                        </div>
                        
                      </th>
                  </tr>
                   <?php endif; ?> 
                    <tr>
                      <th>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('sales-return-list')): ?>
          
                        <div style="display: flex;">
                           <a href="<?php echo e(url('sales-return')); ?>" class="dropdown-item">
                            <i class="fa fa-shopping-cart mr-2" aria-hidden="true"></i> <?php echo e(__('label.issued_material_return')); ?>

                          </a>
                           <a  href="<?php echo e(route('sales-return.create')); ?>" class="dropdown-item text-right">
                            <i class="nav-icon fas fa-plus"></i>
                          </a>
                        </div>
                          
                         <?php endif; ?>  
                      </th>
                  </tr>
                  </table>
              </div>
            </div>
          </div>
          <?php endif; ?>

          <div class="container">
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('rlp-list')): ?>
            <div class="col-md-3">
              <div class="card bg-success text-white">
                <div class="card-body">
                  <a href="<?php echo e(url('rlp')); ?>"><?php echo e(__('label.rlp-info')); ?></a>
                </div>
              </div>
            </div>
            <?php endif; ?>
          </div>


    


<?php $__env->stopSection(); ?>
<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\own\inv-acc-hrm\resources\views/backend/dashboard/index.blade.php ENDPATH**/ ?>