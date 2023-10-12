
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
        <h4 class="text-center"><b>Money Receipt <?php if($data->_voucher_type=='CR'): ?> (Cash) <?php endif; ?> <?php if($data->_voucher_type=='BR'): ?> (Bank) <?php endif; ?></b></h4>
      </div>
      <!-- /.col -->
      
      
    </div>
    <!-- /.row -->

    <!-- Table row -->
    <div class="row">
      <div class="col-12 table-responsive">
        <table  style="width: 100%;border:1px solid silver;">
         
          
         
          <tbody>
            <?php
              $receive_ledger='';
              $voucher_master_id='';
              $voucher_detail_id='';
              $_table_name='voucher_masters';
              $collected_amount=0;
            ?>
            <?php $__empty_1 = true; $__currentLoopData = $data->_master_details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail_key=>$detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <?php if($detail->_cr_amount > 0): ?>
            <tr style="border: 1px solid silver;">
              <td colspan="2" style="border: 1px solid silver;">
                <table style="width: 100%">
                  <tr><td><b>Received From:</b><?php echo e($detail->_voucher_ledger->_name ?? ''); ?></td> </tr>
            <?php
              $receive_ledger=$detail->_voucher_ledger->id ?? '';
              $voucher_master_id =$data->id;
              $voucher_detail_id =$detail->id;
              $collected_amount +=$detail->_cr_amount; 
            ?>
                  <tr><td><b>Address:</b><?php echo e($detail->_voucher_ledger->_address ?? ''); ?></td> </tr>
                  <tr><td><b>Phone:</b><?php echo e($detail->_voucher_ledger->_phone ?? ''); ?></td> </tr>
                </table>
              </td>
              <td style="border: 1px solid silver;">
                <table style="width: 100%">
                  <tr><td>
                    <b>Voucher ID: <?php echo e($data->_code ?? ''); ?></b><br>
        <b>Date:</b> <?php echo e(_view_date_formate($data->_date ?? '')); ?>  <?php echo e($data->_time ?? ''); ?><br>
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
            <td style="border: 1px solid silver;font-weight: bold;">Receipt Type</td>
            <td style="border: 1px solid silver;font-weight: bold;">Narration</td>
            <td style="border: 1px solid silver;font-weight: bold;" class="text-right">Amount</td>
          </tr>
           <?php $__empty_1 = true; $__currentLoopData = $data->_master_details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail_key=>$detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
          
            <?php if($detail->_dr_amount > 0): ?>
          <tr style="border: 1px solid silver;">
            
            <td style="border: 1px solid silver;"><?php echo $detail->_voucher_ledger->_name ?? ''; ?></td>
            
            <td style="border: 1px solid silver;"><?php echo $detail->_short_narr ?? ''; ?></td>
            <td style="border: 1px solid silver;" class="text-right" ><?php echo _report_amount( $detail->_dr_amount ?? 0 ); ?></td>
             
          </tr>
          <?php endif; ?>

          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
          <?php endif; ?>
          <tr style="border: 1px solid silver;" >
              <td  style="border: 1px solid silver;" colspan="2" class="text-right"><b>Total</b></td>
              <th  style="border: 1px solid silver;"  class="text-right" ><b><?php echo _report_amount($data->_amount ?? 0); ?></b></th>
            </tr>
            <tr style="border:none;">
              <td colspan="2" class="text-left" style="border:none;">
                <table class="table table-bordered">
                  <?php
                    $customer_accuont_lastrow =\DB::table('accounts')
                        ->where('_ref_master_id',$voucher_master_id)
                        ->where('_ref_detail_id',$voucher_detail_id)
                        ->where('_table_name',$_table_name)
                        ->where('_account_ledger',$receive_ledger)
                        ->where('_status',1)
                        ->first();

                $befor_row= $customer_accuont_lastrow->id ?? 0; 

                  $previous_balance = \DB::select("SELECT SUM(t1._dr_amount-t1._cr_amount) as _balance FROM accounts as t1 WHERE t1._account_ledger=$receive_ledger AND t1.id < $befor_row AND _status=1 ");
                  ?>
                  <tr>
                    <td>Previous Balance</td>
                    <td><?php echo e(_show_amount_dr_cr(_report_amount($previous_balance[0]->_balance ?? 0))); ?></td>
                  </tr>
                  <tr>
                    <td>Collected</td>
                    <td><?php echo e(_show_amount_dr_cr(_report_amount(-($collected_amount ?? 0)))); ?></td>
                  </tr>
                  <tr>
                    <td>Net Balance</td>
                    <?php
                      $net_balance= (($previous_balance[0]->_balance ?? 0)+(-($collected_amount ?? 0)));
                    ?>
                    <td><?php echo e(_show_amount_dr_cr(_report_amount( $net_balance ?? 0))); ?></td>
                  </tr>
                </table>
              </td>
              <td>
                
              </td>
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
      <br>
                    <div class="col-4 text-left " style="margin-bottom: 50px;">
                      <span style="border-bottom: 1px solid #f5f9f9;border-top: 1px solid #000;">Customer Signature</span>
                    </div>
                    <div class="col-4"></div>
                    <?php
                    $form_settings=\DB::table('sales_form_settings')->first();
                    $_seal_image =$form_settings->_seal_image ?? '';
                    ?>
                    
                    <div class="col-4 text-center " style="margin-bottom: 50px;">
                      <?php if($form_settings->_seal_image !=''): ?>
                     <div style="height: 120px;width:auto; ">
                        <img id="output_1" class="banner_image_create" src="<?php echo e(asset('/')); ?><?php echo e($form_settings->_seal_image ?? ''); ?>"   />
                     </div> <br>
                     <?php endif; ?>
                      <span style="border-bottom: 1px solid #f5f9f9;border-top: 1px solid #000;"> Authorised Signature</span>
                    </div>
    <!-- /.row -->
  </section>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/sobuz23/sspf.sobuzsathitraders.com/resources/views/backend/voucher/monty_receipt.blade.php ENDPATH**/ ?>