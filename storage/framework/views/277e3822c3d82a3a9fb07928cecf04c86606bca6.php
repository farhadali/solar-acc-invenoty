
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
 <a class="nav-link"  href="<?php echo e(url('sales')); ?>" role="button"><i class="fa fa-arrow-left"></i></a>
 <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('sales-edit')): ?>
    <a class="nav-link"  title="Edit" href="<?php echo e(route('sales.edit',$data->id)); ?>">
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
        <h4 class="text-center"><b>Money Receipt </b></h4>
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
                  <tr><td><b>Received From:</b><?php echo e($data->_ledger->_name ?? ''); ?></td> </tr>
                  <tr><td><b>Address:</b><?php echo e($data->_ledger->_address ?? ''); ?></td> </tr>
                  <tr><td><b>Phone:</b><?php echo e($data->_ledger->_phone ?? ''); ?></td> </tr>
                </table>
              </td>
              <td style="border: 1px solid silver;">
                <table style="width: 100%">
                  <tr><td>
                    <b>Invoice No: <?php echo e($data->_order_number ?? ''); ?></b><br>
                    <b>Date:</b>  <?php echo e(_view_date_formate($data->_date ?? '')); ?>  <?php echo e($data->_time ?? ''); ?><br>
                    <b>Created By:</b> <?php echo e($data->_user_name ?? ''); ?><br>
                    <b>Branch:</b> <?php echo e($data->_master_branch->_name ?? ''); ?>

                  </td></tr>
                </table>
              </td>
            </tr>
            
          <tr style="border: 1px solid silver;">
            <td style="border: 1px solid silver;font-weight: bold;">Receipt Type</td>
            <td style="border: 1px solid silver;font-weight: bold;">Narration</td>
            <td style="border: 1px solid silver;font-weight: bold;" class="text-right">Amount</td>
          </tr>
          <?php
          $_total_amount=0;
          ?>
           <?php $__empty_1 = true; $__currentLoopData = $data->s_account; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail_key=>$detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
          
            <?php if($detail->_dr_amount > 0): ?>
             <?php
          $_total_amount +=$detail->_dr_amount ?? 0;
          ?>
          <tr style="border: 1px solid silver;">
            
            <td style="border: 1px solid silver;"><?php echo $detail->_ledger->_name ?? ''; ?></td>
            
            <td style="border: 1px solid silver;"><?php echo $detail->_short_narr ?? ''; ?></td>
            <td style="border: 1px solid silver;" class="text-right" ><?php echo _report_amount(  $detail->_dr_amount ?? 0 ); ?></td>
             
          </tr>
          <?php endif; ?>

          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
          <?php endif; ?>
          <tr style="border: 1px solid silver;" >
              <td  style="border: 1px solid silver;" colspan="2" class="text-right"><b>Total</b></td>
              <td  style="border: 1px solid silver;"  class="text-right" ><b><?php echo _report_amount($_total_amount ?? 0); ?></b></td>
            </tr>
            <tr style="border:none;">
              <td colspan="3" style="border:none;height: 10px;"></td>
            </tr>

            <tr style="border:none;">
              <td colspan="2" class="text-left" style="border:none;">
                <table class="table table-bordered">
                  <?php
                  $voucher_master_id = $data->id;
                  $_table_name = 'sales_accounts';
                  $receive_ledger = $data->_ledger_id;
                  $collected_amount = 0;

                  $cash_group = $settings->_cash_group ?? 0;
                  $_bank_group = $settings->_bank_group ?? 0;

                    $customer_accuont_lastrow =\DB::table('accounts')
                        ->where('_ref_master_id',$voucher_master_id)
                        ->where('_table_name',$_table_name)
                        ->where('_account_ledger',$receive_ledger)
                        ->where('_status',1)
                        ->orderBy('id','DESC')
                        ->first();
                  $collected_amount =\DB::table('accounts')
                        ->where('_ref_master_id',$voucher_master_id)
                        ->where('_table_name',$_table_name)
                        ->whereIn('_account_group',[$cash_group,$_bank_group])
                        ->where('_status',1)
                        ->orderBy('id','DESC')
                        ->SUM('_dr_amount');
                       // dump($collected_amount);
                        
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
                
                 
      
    </div>
    <!-- /.row -->
  </section>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\own\inv-acc-hrm\resources\views/backend/sales/money_receipt.blade.php ENDPATH**/ ?>