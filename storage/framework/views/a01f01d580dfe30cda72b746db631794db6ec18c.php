
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
 <a class="nav-link"  href="<?php echo e(url('purchase')); ?>" role="button"><i class="fa fa-arrow-left"></i></a>
 <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('purchase-edit')): ?>
    <a  href="<?php echo e(route('purchase.edit',$data->id)); ?>" 
    class="nav-link "  title="Edit" 
   attr_base_edit_url="<?php echo e(route('purchase.edit',$data->id)); ?>" >
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
        <h4 class="text-center"><b>Payment Receipt </b></h4>
      </div>
      <!-- /.col -->
      
      
    </div>
    <!-- /.row -->

    <!-- Table row -->
    <div class="row">
      <div class="col-12 table-responsive">
        <table  style="width: 100%;border:1px solid silver;">
         
          
         
          <tbody>
           
            <tr style="border: 1px solid silver;">
              <td colspan="2" style="border: 1px solid silver;">
                <table style="width: 100%">
                  <tr><td><b>Paid To:</b><?php echo e($data->_ledger->_name ?? ''); ?></td> </tr>
                  <tr><td><b>Address:</b><?php echo e($data->_address ?? ''); ?></td> </tr>
                  <tr><td><b>Phone:</b><?php echo e($data->_phone ?? ''); ?></td> </tr>
                </table>
              </td>
              <td style="border: 1px solid silver;">
                <table style="width: 100%">
                  <tr><td>
                    <b>Invoice No: <?php echo e($data->id ?? ''); ?></b><br>
                    <b>Date:</b>  <?php echo e(_view_date_formate($data->_date ?? '')); ?>  <?php echo e($data->_time ?? ''); ?><br>
                    <b>Created By:</b> <?php echo e($data->_user_name ?? ''); ?><br>
                    <b>Branch:</b> <?php echo e($data->_master_branch->_name ?? ''); ?>

                  </td></tr>
                </table>
              </td>
            </tr>
            
          <tr style="border: 1px solid silver;">
            <td style="border: 1px solid silver;font-weight: bold;">Payment Type</td>
            <td style="border: 1px solid silver;font-weight: bold;">Narration</td>
            <td style="border: 1px solid silver;font-weight: bold;" class="text-right">Amount</td>
          </tr>
          <?php
          $_total_amount=0;
          ?>
           <?php $__empty_1 = true; $__currentLoopData = $data->purchase_account; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail_key=>$detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
          
            <?php if($detail->_cr_amount > 0): ?>
             <?php
          $_total_amount +=$detail->_cr_amount ?? 0;
          ?>
          <tr style="border: 1px solid silver;">
            
            <td style="border: 1px solid silver;"><?php echo $detail->_ledger->_name ?? ''; ?></td>
            
            <td style="border: 1px solid silver;"><?php echo $detail->_short_narr ?? ''; ?></td>
            <td style="border: 1px solid silver;" class="text-right" ><?php echo _report_amount( $detail->_cr_amount ?? 0 ); ?></td>
             
          </tr>
          <?php endif; ?>

          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
          <?php endif; ?>
          <tr style="border: 1px solid silver;" >
              <td  style="border: 1px solid silver;" colspan="2" class="text-right"><b>Total</b></td>
              <th  style="border: 1px solid silver;"  class="text-right" ><b><?php echo _report_amount($_total_amount ?? 0); ?></b></th>
            </tr>

            <tr>
              <td colspan="3" class="text-left"><b>In Words: </b><?php echo e(nv_number_to_text( $_total_amount ?? 0)); ?></td>
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
      <div class="col-12 mt-5">
        <div class="row">
          <div class="col-3 text-center " style="margin-bottom: 50px;"><span style="border-bottom: 1px solid #f5f9f9;">Paid  By</span></div>
          <div class="col-3 text-center " style="margin-bottom: 50px;"><span style="border-bottom: 1px solid #f5f9f9;">Received By</span></div>
          <div class="col-3 text-center " style="margin-bottom: 50px;"><span style="border-bottom: 1px solid #f5f9f9;">Checked By</span></div>
          <div class="col-3 text-center " style="margin-bottom: 50px;"><span style="border-bottom: 1px solid #f5f9f9;"> Approved By</span></div>
        </div>
      </div>
    </div>
    <!-- /.row -->
  </section>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\own\inv-acc-hrm\resources\views/backend/purchase/money_receipt.blade.php ENDPATH**/ ?>