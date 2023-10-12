
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
 <a class="nav-link"  href="<?php echo e(url('voucher')); ?>" role="button"><i class="fa fa-arrow-left"></i></a>
 <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('voucher-edit')): ?>
    <a class="nav-link"  title="Edit" href="<?php echo e(route('voucher.edit',$data->id)); ?>">
                                      <i class="nav-icon fas fa-edit"></i>
     </a>
  <?php endif; ?>
    
    <a style="cursor: pointer;" class="nav-link"  title="Print" onclick="javascript:printDiv('printablediv')"><i class="fas fa-print"></i></a>
      <a style="cursor: pointer;" onclick="fnExcelReport();" class="nav-link"  title="Excel Download" ><i class="fa fa-file-excel" aria-hidden="true"></i></a>
      <?php echo $__env->make('backend.message.message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
  </div>

<section class="invoice" id="printablediv">
    <!-- title row -->
    <div class="row">
     
    </div>
    <!-- info row -->
    <div class="row invoice-info">
      <div class="col-sm-12 invoice-col text-center">
        <?php echo e($settings->_top_title ?? ''); ?>

        <h2 class="page-header">
           <img src="<?php echo e(asset('/')); ?><?php echo e($settings->logo ?? ''); ?>" alt="<?php echo e($settings->name ?? ''); ?>"  style="width: 60px;height: 60px;"> <?php echo e($settings->name ?? ''); ?>

          
        </h2>
        <address>
          <strong><?php echo e($settings->_address ?? ''); ?></strong><br>
          <?php echo e($settings->_phone ?? ''); ?><br>
          <?php echo e($settings->_email ?? ''); ?><br>
        </address>
        <h4 class="text-center"><b> Payment Receipt <?php if($data->_voucher_type=='CP'): ?> (Cash Payment) <?php endif; ?> <?php if($data->_voucher_type=='BP'): ?> (Bank Payment) <?php endif; ?></b></h4>
      </div>
      <!-- /.col -->
      
      
    </div>
    <!-- /.row -->

    <!-- Table row -->
    <div class="row">
      <div class="col-12 table-responsive">
        <table  style="width: 100%;border:1px solid silver;">
         
          
         
          <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $data->_master_details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail_key=>$detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <?php if($detail->_dr_amount > 0): ?>
            <tr style="border: 1px solid silver;">
              <td colspan="2" style="border: 1px solid silver;">
                <table style="width: 100%">
                  <tr><td><b>Payment To:</b><?php echo e($detail->_voucher_ledger->_name ?? ''); ?></td> </tr>
                  <tr><td><b>Address:</b><?php echo e($detail->_voucher_ledger->_address ?? ''); ?></td> </tr>
                  <tr><td><b>Phone:</b><?php echo e($detail->_voucher_ledger->_phone ?? ''); ?></td> </tr>
                </table>
              </td>
              <td style="border: 1px solid silver;">
                <table style="width: 100%">
                  <tr><td>
                    <b>Voucher ID: <?php echo e($data->_code ?? ''); ?></b><br>
        <b>Date:</b>  <?php echo e(_view_date_formate($data->_date ?? '')); ?>  <?php echo e($data->_time ?? ''); ?><br>
        <b>Created By:</b> <?php echo e($data->_user_name ?? ''); ?><br>
        <b>Branch:</b> <?php echo e($data->_master_branch->_name ?? ''); ?>

                  </td></tr>
                </table>
              </td>
            </tr>
            <?php endif; ?>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
          <?php endif; ?>
          <tr style="border: 1px solid silver;">
            <td style="border: 1px solid silver;font-weight: bold;">Payment Type</td>
            <td style="border: 1px solid silver;font-weight: bold;">Narration</td>
            <td style="border: 1px solid silver;font-weight: bold;" class="text-right">Amount</td>
          </tr>
           <?php $__empty_1 = true; $__currentLoopData = $data->_master_details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail_key=>$detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
          
            <?php if($detail->_cr_amount > 0): ?>
          <tr style="border: 1px solid silver;">
            
            <td style="border: 1px solid silver;"><?php echo $detail->_voucher_ledger->_name ?? ''; ?></td>
            
            <td style="border: 1px solid silver;"><?php echo $detail->_short_narr ?? ''; ?></td>
            <td style="border: 1px solid silver;" class="text-right" ><?php echo _report_amount( $detail->_cr_amount ?? 0 ); ?></td>
             
          </tr>
          <?php endif; ?>

          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
          <?php endif; ?>
          <tr style="border: 1px solid silver;" >
              <td  style="border: 1px solid silver;" colspan="2" class="text-right"><b>Total</b></td>
              <th  style="border: 1px solid silver;"  class="text-right" ><b><?php echo _report_amount($data->_amount ?? 0); ?></b></th>
            </tr>

            <tr>
              <td colspan="3" class="text-left"><b>In Words: </b><?php echo e(nv_number_to_text( $data->_amount ?? 0)); ?></td>
            </tr>
            <tr>
              <td colspan="3" class="text-left"><b>Narration:</b> <?php echo e($data->_note ?? ''); ?></td>
            </tr>
          
          </tbody>
          <tfoot>
            
          </tfoot>
        </table>
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->

    <div class="row">
      <?php echo $__env->make('backend.message.invoice_footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>
    <!-- /.row -->
  </section>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\own\inv-acc-hrm\resources\views/backend/voucher/payment_receipt.blade.php ENDPATH**/ ?>