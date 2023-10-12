
<?php $__env->startSection('title',$page_name); ?>
<?php $__env->startSection('css'); ?>
<link rel="stylesheet" href="<?php echo e(asset('backend/new_style.css')); ?>">
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<?php
$__user =\Auth::user();
?>
<div class="content-header">
      <div class="container-fluid">

        <div class="col-sm-12" style="display: flex;">
             <a class="m-0 _page_name" href="warranty-check"> <?php echo $page_name ?? ''; ?> </a>
            <ol class="breadcrumb float-sm-right ml-2">
            </ol>

          </div>
         


      </div><!-- /.container-fluid -->
    </div>
    <?php echo $__env->make('backend.message.message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-12">
            <div class="card">
              
              <div class="card-body">
                <form action="<?php echo e(url('warranty-check')); ?>" method="GET">
                  <?php echo csrf_field(); ?>
                    <div class="row">
                       <div class="col-xs-12 col-sm-12 col-md-12">
                        <input type="hidden" name="_form_name" class="_form_name"  value="warranty_masters">
                            <div class="form-group">
                                  
                              <input required type="text" name="_barcode" class="form-control " placeholder="Barcode" value="<?php echo e($request->_barcode ?? ''); ?>" />
                                      
                                      </div>
                                  </div>
                              </div>
                              <div class="col-xs-12 col-sm-12 col-md-12 bottom_save_section text-middle">
                          
                            <button type="submit" class="btn btn-success submit-button ml-5"><i class="fa fa-credit-card mr-2" aria-hidden="true"></i> Check</button>
                            <a href="<?php echo e(url('warranty-check')); ?>" class="btn btn-warning  ml-5"><i class="fa fa-credit-card mr-2" aria-hidden="true"></i> Reset</a>
                           
                        </div>
                    </form>
                  </div>
              </div>
            </div>
            <!-- /.card -->

        </div>  
<?php if(sizeof($data) > 0): ?>
        <div class="row">
          <div class="col-md-12">
            <div class="table-responsive">
              <table class="table table-bordered">
                <thead>
                  <th>Item Information</th>
                  <th>Purchase Information</th>
                  <th>Sales Information</th>
                  <th>Status</th>
                </thead>
                <tbody>
                  <?php $__empty_1 = true; $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                  <tr>
                    <td>
                     <p> Name: <?php echo e($value->_item ?? ''); ?></p>
                     <p> Barcode: <?php echo e($value->_barcode ?? ''); ?></p>
                     <p> Warranty: <?php echo e($value->_name ?? ''); ?></p>
                    </td>
                    <td>
                     <p> Invoice No: <a href="<?php echo e(url('purchase/print')); ?>/<?php echo e($value->_master_id); ?>"><?php echo e($value->_p_order_number ?? ''); ?></a></p>
                     <p> Supplier: <?php echo e(_ledger_name($value->_p_ledger ?? '')); ?></p>
                     <p> Date: <?php echo e(_view_date_formate($value->_p_date ?? '')); ?></p>
                    </td>
                     <td>
                     <p> Invoice No: <a href="<?php echo e(url('sales/print')); ?>/<?php echo e($value->_s_id); ?>"><?php echo e($value->_order_number ?? ''); ?></a></p>
                     <p> Customer: <?php echo e(_ledger_name($value->_s_ledger ?? '')); ?></p>
                     <p> Date: <?php echo e(_view_date_formate($value->_date ?? '')); ?></p>
                    </td>
                    <td>
                      <?php
                      $date = $value->_date;
                    $warranty_valid_date = date('Y-m-d', strtotime($date.'+'.$value->_duration.' '.$value->_period.''));
                    $current_date= date('Y-m-d');

                    if ($warranty_valid_date > $current_date) { ?>

                  <p style="color:green;">Warranty Validity Date Untill <?php echo e(_view_date_formate($warranty_valid_date)); ?></p>
                 <h3 style="color:green;">Warranty  available</h3>

                 <?php  } else { ?>
                 <p style="color:red;">Warranty Validity Date Expired <?php echo e(_view_date_formate($warranty_valid_date)); ?></p>
                 <h3 style="color:red;">Warranty Not available</h3>
                  <?php }  ?>
                      
                    </td>
                  </tr>
                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                  <tr>
                    <td colspan="4">
                      <h3 style="text-align: center;">No Data Found</h3>
                    </td>
                  </tr>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
<?php else: ?>
<?php if(isset($request->_barcode)): ?>
    <h3 style="text-align: center;">No Data Found</h3> 
<?php endif; ?>   
<?php endif; ?>


        </div>
        <!-- /.row -->
      </div>
     



<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>


<?php $__env->stopSection(); ?>
<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\own\inv-acc-hrm\resources\views/backend/warranty-manage/check.blade.php ENDPATH**/ ?>