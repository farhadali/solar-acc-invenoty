

<?php $__env->startSection('title',$page_name); ?>

<?php $__env->startSection('content'); ?>
<style type="text/css">
 
  @media  print {
   .table th {
    vertical-align: top;
    color: #000;
    background-color: #fff; 
}
}
  </style>
<div class="_report_button_header">
    <a class="nav-link"  href="<?php echo e(url('rlp')); ?>" role="button"><i class="fa fa-arrow-left"></i></a>
 <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('rlp-edit')): ?>
 <a  href="<?php echo e(route('rlp.edit',$data->id)); ?>" 
    class="nav-link "  title="Edit"  >
    <i class="nav-icon fas fa-edit"></i>
     </a>
  <?php endif; ?>
    <a style="cursor: pointer;" class="nav-link"  title="Print" onclick="javascript:printDiv('printablediv')"><i class="fas fa-print"></i></a>
      <a style="cursor: pointer;" onclick="fnExcelReport();" class="nav-link"  title="Excel Download" ><i class="fa fa-file-excel" aria-hidden="true"></i></a>
      <?php echo $__env->make('backend.message.message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
  </div>

<section class="invoice" id="printablediv">

<section class="invoice">
    <!-- title row -->
    <div class="row">
      <div class="col-12">
       
        <div style="text-align: center;">
       <h3> <?php echo e($settings->name ?? ''); ?> </h3>
       <div><?php echo e($settings->_address ?? ''); ?></br>
       <?php echo e($settings->_phone ?? ''); ?></div>
       <h3><?php echo e($page_name); ?></h3>

      </div>
      </div>
      
    </div>
    <!-- info row -->
   
  <?php
                      $_item_detail = $data->_item_detail ?? [];
                      $row_span= sizeof($_item_detail);
                      $purpose =[];
                      $item_total_qty=[];
                      $item_total_amount=[];
                      ?>
<?php if($row_span > 0): ?>
<div class="">
    <table class="table table-bordered ">
        <tr>
          <td><?php echo e(__('label._date')); ?>:</td>
          <td><b><?php echo _view_date_formate($data->request_date ?? ''); ?></b></td>
          <td><?php echo $data->rlp_prefix; ?> No:</td>
          <td><b><?php echo $data->rlp_no ?? ''; ?></b></td>
          <td><?php echo e(__('label.priority')); ?>:</td>
          <td><b><?php echo selected_priority($data->priority ?? 1); ?></b></td>
        </tr>
        <tr>
          <td><?php echo e(__('label.organization_id')); ?>:</td>
          <td><b><?php echo $data->_organization->_name ?? ''; ?></b></td>
          <td><?php echo e(__('label._branch_id')); ?>:</td>
          <td><b><?php echo $data->_branch->_name ?? ''; ?></b></td>
          <td><?php echo e(__('label._cost_center_id')); ?>:</td>
          <td><b><?php echo $data->_cost_center->_name ?? ''; ?></b></td>
        </tr>
    </table>
   <table class="table table-bordered _list_table">
                     <thead>
                        <tr>
                         <th class="text-center"><b><?php echo e(__('label.sl')); ?></b></th>
                         <th class="text-center"><b><?php echo e(__('label.item_details')); ?></b></th>
                         <th class="text-center"><b><?php echo e(__('label.purpose_purchase')); ?></b></th>
                         <th colspan="2" class="text-center"><b><?php echo e(__('label.quantity')); ?></b></th>
                         <th class="text-center"><b><?php echo e(__('label.estimated_price')); ?></b></th>
                         <th class="text-center"><b><?php echo e(__('label.total_estimated_price')); ?></b></th>
                         <th class="text-center"><b><?php echo e(__('label.supplier_details')); ?></b></th>
                      </tr>
                     </thead>
                     <tbody>
                      <?php
                      $sl=1;
                      ?>
                      <?php $__empty_1 = true; $__currentLoopData = $_item_detail; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                       <?php
                      
                      $item_total_qty[]=$item->quantity ?? 0;
                      $item_total_amount[]=$item->amount ?? 0;
                      ?>
                      <tr>
                        <td><?php echo e($sl); ?></td>
                        <td><?php echo $item->_items->_item ?? ''; ?> <br>
                          <?php echo $item->_item_description ?? ''; ?>

                        </td>
                        <?php if($key==0): ?>
                        <td rowspan="<?php echo e($row_span); ?>">
                          <?php if(!in_array($item->purpose,$purpose)): ?>
                          <?php
                          array_push($purpose,$item->purpose);
                          ?>
                           <?php echo $item->purpose ?? ''; ?> 
                           <?php endif; ?>
                        </td>
                        <?php endif; ?>
                        <td><?php echo _find_unit($item->_unit_id); ?></td>
                        <td style="text-align:right;"><?php echo _report_amount($item->quantity ?? 0); ?></td>
                        <td style="text-align:right;"><?php echo _report_amount($item->unit_price ?? 0); ?></td>
                        <td style="text-align:right;"><?php echo _report_amount($item->amount ?? 0); ?></td>
                        <td><?php echo $item->_supplier->_name ?? ''; ?></td>
                      </tr>
                      
                      <?php
                      $sl++;
                      ?>
                      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                      <?php endif; ?>

                       <?php
                      $_account_detail = $data->_account_detail ?? [];
                      $row_span= sizeof($_account_detail);
                      $purpose =[];
                      $_total_ac_amount=[];
                      ?>
                      <?php $__empty_1 = true; $__currentLoopData = $_account_detail; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                      
                      <tr>
                        <td><?php echo e($sl); ?></td>
                        <td><?php echo $item->_ledger->_name ?? ''; ?> <br>
                          <?php echo $item->_rlp_ledger_description ?? ''; ?>

                        </td>
                        <?php if($key==0): ?>
                        <td rowspan="<?php echo e($row_span); ?>">
                          <?php if(!in_array($item->purpose,$purpose)): ?>
                          <?php
                          array_push($purpose,$item->purpose);
                          ?>
                           <?php echo $item->purpose ?? ''; ?> 
                           <?php endif; ?>
                        </td>
                        <?php endif; ?>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td style="text-align:right;"><?php echo _report_amount($item->amount ?? 0); ?></td>
                        <?php
                        $item_total_amount[]=$item->amount ?? 0;
                        ?>
                      </tr>

                      <?php
                      $sl++;
                      ?>
                      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                      <?php endif; ?>
        <tr>
            <th colspan="3"><?php echo e(__('label._total')); ?>:</th>
            <th></th>
            <th class="text-right"><?php echo e(_report_amount(array_sum($item_total_qty))); ?></th>
            <th></th>
            <th class="text-right"><?php echo e(_report_amount(array_sum($item_total_amount))); ?></th>
            <th></th>
          </tr>    
          <tr>
            <td colspan="8"><b>In Word:</b> <?php echo convert_number(array_sum($item_total_amount)); ?> Taka Only.</td>
          </tr>  
          <tr>
            <td colspan="8"><?php echo __('label._terms_condition'); ?>: <br> <?php echo $data->_terms_condition ?? ''; ?> </td>
          </tr>

</tbody>

<?php

$_rlp_acks =  $data->_rlp_ack_app ?? [];
?>
                        <tfoot>
                          <tr>
                            <?php $__empty_1 = true; $__currentLoopData = $_rlp_acks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <?php if($val->ack_status==1 && $val->_is_approve==1): ?>
                              <td colspan="2" style="height: 60px;">
                                

                              </td>
                            <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <?php endif; ?>
                          </tr>
                          <tr>
                            <?php $__empty_1 = true; $__currentLoopData = $_rlp_acks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <?php if($val->ack_status==1 && $val->_is_approve==1): ?>
                              <td colspan="2" class="text-center">
                                <b><?php echo $val->_check_group->_display_name ?? ''; ?></b>
                                <br>
                                <?php echo $val->_employee->_name ?? ''; ?><br>
                                <?php echo $val->_employee->_emp_designation->_name ?? ''; ?><br>

                              </td>
                            <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <?php endif; ?>
                          </tr>
                        </tfoot>
                        

                        
                    </table>
                </div>
    <?php endif; ?>

  </section>

</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\own\inv-acc-hrm\resources\views/rlp-module/rlp/show.blade.php ENDPATH**/ ?>