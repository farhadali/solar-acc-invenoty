
<?php $__env->startSection('title',$page_name); ?>

<?php $__env->startSection('content'); ?>
<div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 _page_name"><?php echo $page_name ?? ''; ?> </h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <!-- <li class="breadcrumb-item"><a href="<?php echo e(url('home')); ?>">Home</a></li> -->
              <li class="breadcrumb-item active">
              <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('item-information-create')): ?>
                <button type="button" 
               class="btn btn-sm btn-info active attr_base_create_url" 
               data-toggle="modal" 
               data-target="#commonEntryModal_item" 
               attr_base_create_url="<?php echo e(route('item-information.create')); ?>">
                   <i class="nav-icon fas fa-plus"></i> Create New
                </button>
              
              <?php endif; ?>
               </li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <?php if($message = Session::get('success')): ?>
    <div class="alert alert-success">
      <p><?php echo e($message); ?></p>
    </div>
    <?php endif; ?>
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-12">
            <div class="card">
              <div class="card-header border-0 mt-1">
                 

                  <div class="row">
                   <?php

 $currentURL = URL::full();
 $current = URL::current();
if($currentURL === $current){
   $print_url = $current."?print=single";
   $print_url_detal = $current."?print=detail";
}else{
     $print_url = $currentURL."&print=single";
     $print_url_detal = $currentURL."&print=detail";
}
    

                   ?>
                    <div class="col-md-4">
                      <?php echo $__env->make('backend.item-information.lot_search', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    </div>
                    <div class="col-md-8">
                      <div class="d-flex flex-row justify-content-end">
                         <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('voucher-print')): ?>
                        <li class="nav-item dropdown remove_from_header">
                              <a class="nav-link" data-toggle="dropdown" href="#">
                                
                                <i class="fa fa-print " aria-hidden="true"></i> <i class="right fas fa-angle-down "></i>
                              </a>
                              <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                               
                                <div class="dropdown-divider"></div>
                                
                                <a target="__blank" href="<?php echo e($print_url); ?>" class="dropdown-item">
                                  <i class="fa fa-print mr-2" aria-hidden="true"></i> Print
                                </a>  
                            </li>
                             <?php endif; ?>   
                         <?php echo $datas->render(); ?>

                          </div>
                    </div>
                  </div>
              </div>
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table table-bordered _list_table">
                      <thead>
                        <tr>
                        <th>##</th>
                         <th>SL</th>
                         <th>ID</th>
                         <th>Ref</th>
                         <th>IN Type</th>
                         <th>Item</th>
                         <th>Unit</th>
                         <th>Code</th>
                         <th>Barcode</th>
                         <th>Warranty</th>
                         <th>QTY</th>
                         
                         <th>Discount</th>
                         <th>Vat</th>
                         <th>Purchase Rate</th>
                         <th>Sales Rate</th>
                         <th>Total Value</th>
                         <th>Manu. Date</th>
                         <th>Exp. Date</th>
                         <th>Status</th>            
                      </tr>
                      </thead>
                      <tbody>
                      <?php
                        $total_qty=0;
                        $total_value=0;
                      ?>
                        <?php $__currentLoopData = $datas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                         <?php
                        $total_qty +=$data->_qty;
                        $total_value +=($data->_qty*$data->_pur_rate);
                      ?>
                        <tr>
                           
                           <td class="_list_table_td">
                             <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('item-sales-price-update')): ?>
                            <a class="nav-link"  href="<?php echo e(url('item-sales-price-edit')); ?>/<?php echo e($data->id); ?>" role="button"><i class="nav-icon fas fa-edit"></i></a>
                            <?php endif; ?>
                          </td>
                            <td class="_list_table_td"><?php echo e(($key+1)); ?></td>
                            <td class="_list_table_td"><?php echo e($data->id ?? ''); ?></td>
                            
                            <td class="_list_table_td">
                              <?php if($data->_input_type=='purchase'): ?>
                              <a class="" href="<?php echo e(url('purchase/print')); ?>/<?php echo e($data->_master_id); ?>">
                                     <?php echo e(_purchase_pfix()); ?> <?php echo e($data->_master_id); ?>

                                    </a>
                                <?php endif; ?>
                                <?php if($data->_input_type=='replacement'): ?>
                           <a class="" 
                              href="<?php echo e(url('item-replace/print')); ?>/<?php echo e($data->_master_id); ?>">
                                      RP-<?php echo e($data->_master_id); ?>

                                    </a>
                            <?php endif; ?>
                              </td>
                            
                            
                            <td class="_list_table_td"><?php echo e($data->_input_type ?? ''); ?></td>
                            <td class="_list_table_td"><?php echo e($data->_item ?? ''); ?></td>
                            <td class="_list_table_td"><?php echo e($data->_units->_name ?? ''); ?></td>
                            <td class="_list_table_td"><?php echo e($data->_code ?? ''); ?></td>
                            <td class="_list_barcode">
                              <?php
                                $barcode_arrays = explode(',', $data->_barcode ?? '');
                                ?>
                                <?php $__empty_1 = true; $__currentLoopData = $barcode_arrays; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $barcode): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                              <span style="width: 100%;"><?php echo e($barcode); ?></span><br>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <?php endif; ?>
                            </td>
                            <td class="_list_table_td"><?php echo e($data->_warranty_name->_name ?? ''); ?></td>
                            <td class="text-right _list_table_td"><?php echo e(_report_amount($data->_qty ?? 0)); ?></td>
                            <td class="text-right _list_table_td"><?php echo e(_report_amount( $data->_discount ?? 0 )); ?></td>
                            <td class="text-right _list_table_td"><?php echo e(_report_amount( $data->_vat ?? 0 )); ?></td>
                            <td class="text-right _list_table_td"><?php echo e(_report_amount($data->_pur_rate ?? 0 )); ?></td>
                            <td class="text-right _list_table_td"><?php echo e(_report_amount($data->_sales_rate ?? 0 )); ?></td>
                            <td class="text-right _list_table_td"><?php echo e(_report_amount(($data->_qty*$data->_pur_rate) )); ?></td>
                            <td class="_list_table_td"><?php echo e(_view_date_formate($data->_manufacture_date ?? '')); ?></td>
                            <td class="_list_table_td"><?php echo e(_view_date_formate($data->_expire_date ?? '')); ?></td>
                           <td class="_list_table_td"><?php echo e(selected_status($data->_status)); ?></td>
                           
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                          <th colspan="10" class="text-right">Total</th>
                          <th class="text-right"><?php echo e(_report_amount($total_qty)); ?></th>
                          <th colspan="4"></th>
                          <th class="text-right"><?php echo e(_report_amount($total_value)); ?></th>
                          <th colspan="3"></th>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <!-- /.d-flex -->

                

                <div class="d-flex flex-row justify-content-end">
                 <?php echo $datas->render(); ?>

                </div>
              </div>
            </div>
            <!-- /.card -->

            
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\own\inv-acc-hrm\resources\views/backend/item-information/lot_item.blade.php ENDPATH**/ ?>