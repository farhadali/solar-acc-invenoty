
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
<div style="padding-left: 20px;display: flex;">
 <a class="nav-link"  href="<?php echo e(url('voucher')); ?>" role="button"><i class="fa fa-arrow-left"></i></a>
 <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('voucher-edit')): ?>
    <a class="nav-link"  title="Edit" href="<?php echo e(route('voucher.edit',$data->id)); ?>">
                                      <i class="nav-icon fas fa-edit"></i>
     </a>
  <?php endif; ?>
    
    <a style="cursor: pointer;" id="click_print_button" class="nav-link"  title="Print" onclick="javascript:printDiv('printablediv')"><i class="fas fa-print"></i></a>
      <a style="cursor: pointer;" onclick="fnExcelReport();" class="nav-link"  title="Excel Download" ><i class="fa fa-file-excel" aria-hidden="true"></i></a>
       <?php echo $__env->make('backend.message.message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
  </div>

<section class="invoice" id="printablediv">
    <!-- title row -->
    <div class="row">
      <div class="col-md-12" style="text-align: center;">
        <?php echo e($settings->_top_title ?? ''); ?>

        <h2 class="page-header text-center">
           <img src="<?php echo e(asset('/')); ?><?php echo e($settings->logo ?? ''); ?>" alt="<?php echo e($settings->name ?? ''); ?>"  style="width: 60px;height: 60px;"> <?php echo e($settings->name ?? ''); ?>

          
        </h2>
      </div>
      <!-- /.col -->
    </div>
    <!-- info row -->
    <div class="row invoice-info">
      <div class="col-sm-4 invoice-col">
        
        <address>
          <strong><?php echo e($settings->_address ?? ''); ?></strong><br>
          <?php echo e($settings->_phone ?? ''); ?><br>
          <?php echo e($settings->_email ?? ''); ?><br>
        </address>
      </div>
      <!-- /.col -->
      <div class="col-sm-4 invoice-col">
        <h3 class="text-center"><b><?php echo e(voucher_code_to_name($data->_voucher_type)); ?></b></h3>
      </div>
      <!-- /.col -->
      <div class="col-sm-4 invoice-col text-right">
        <b>Voucher ID: <?php echo e($data->_code ?? ''); ?></b><br>
        <b>Time:</b> <?php echo e($data->_time ?? ''); ?><br>
        <b>Created By:</b> <?php echo e($data->_user_name ?? ''); ?><br>
        <b>Branch:</b> <?php echo e($data->_master_branch->_name ?? ''); ?>

      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->

    <!-- Table row -->
    <div class="row">
      <div class="col-12 table-responsive">
        <table class="table table-striped _grid_table">
          <thead>
          <tr>
            <th>ID</th>
            <th>Ledger</th>
            <?php if(sizeof($permited_branch) > 1): ?>
            <th>Branch</th>
            <?php endif; ?>
            <?php if(sizeof($permited_costcenters) > 1): ?>
            <th>Cost Center</th>
            <?php endif; ?>
            <th>Short Narr.</th>
            <th class="text-right" >Dr. Amount</th>
            <th class="text-right" >Cr. Amount</th>
          </tr>
          </thead>
          <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $data->_master_details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail_key=>$detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
          <tr>
            <td><?php echo $detail->id ?? ''; ?></td>
            <td><?php echo $detail->_voucher_ledger->_name ?? ''; ?></td>
             <?php if(sizeof($permited_branch) > 1): ?>
            <td><?php echo $detail->_detail_branch->_name ?? ''; ?></td>
            <?php endif; ?>
             <?php if(sizeof($permited_costcenters) > 1): ?>
            <td><?php echo $detail->_detail_cost_center->_name ?? ''; ?></td>
            <?php endif; ?>
            <td><?php echo $detail->_short_narr ?? ''; ?></td>
            <td class="text-right" ><?php echo _report_amount( $detail->_dr_amount ?? 0 ); ?></td>
            <td class="text-right" ><?php echo _report_amount($detail->_cr_amount ?? 0 ); ?></td>
             
          </tr>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
          <?php endif; ?>
          
          </tbody>
          <tfoot>
            <tr>
              <th style="background-color: rgba(0,0,0,.05);" colspan="3" class="text-center">Total:</th>
              <?php if(sizeof($permited_branch) > 1): ?>
            <td></td>
            <?php endif; ?>
             <?php if(sizeof($permited_costcenters) > 1): ?>
            <td></td>
            <?php endif; ?>
              <th style="background-color: rgba(0,0,0,.05);" class="text-right" ><?php echo _report_amount($data->_amount ?? 0); ?></th>
              <th style="background-color: rgba(0,0,0,.05);" class="text-right" ><?php echo _report_amount($data->_amount ?? 0); ?></th>
            </tr>
          </tfoot>
        </table>
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->

    <div class="row">
      <!-- accepted payments column -->
      <div class="col-12">
       
        <p class="lead"> <b>In Words: <?php echo e(nv_number_to_text( $data->_amount ?? 0)); ?> </b></p>
        
      </div>
       <?php echo $__env->make('backend.message.invoice_footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>
    <!-- /.row -->
  </section>

<?php $__env->stopSection(); ?>



<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\own\inv-acc-hrm\resources\views/backend/voucher/print.blade.php ENDPATH**/ ?>