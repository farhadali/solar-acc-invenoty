

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

<section class="invoice" id="printablediv" style="">
		
            <table class="table" style="border-collapse: collapse;">
            	<tr>
            		<td colspan="6" style="text-align: center;">
            			  <?php echo e($settings->_top_title ?? ''); ?><br>
                   <img src="<?php echo e(url('/')); ?>/<?php echo e($settings->logo); ?>" alt="<?php echo e($settings->name ?? ''); ?>" style="height: 60px;width: 60px"  ><br>
            			<strong><?php echo e($settings->name ?? ''); ?></strong><br>
		         <?php echo e($settings->_address ?? ''); ?><br>
		        <?php echo e($settings->_phone ?? ''); ?><br>
		        <?php echo e($settings->_email ?? ''); ?><br>
            <?php
        $bin = $settings->_bin ?? '';
      ?>
      <?php if($bin !=''): ?>
      VAT REGISTRATION NO: <?php echo e($settings->_bin ?? ''); ?><br>
      <?php endif; ?>
		        <b>Invoice/Bill</b>
            		</td>
            	</tr>
            	<tr>
            		<td colspan="2" style="text-align: left;border: 1px dotted grey;">
            			<table style="">
            				 <tr> <td style="border:none;" > <b>Customer:</b> <?php if($form_settings->_defaut_customer ==$data->_ledger_id): ?>
                      <?php echo e($data->_referance ?? $data->_ledger->_name); ?>

                  <?php else: ?>
                  <?php echo e($data->_ledger->_name ?? ''); ?>

                  <?php endif; ?></td></tr>
            				
			                <tr> <td style="border:none;" >Phone:<?php echo e($data->_phone ?? ''); ?> </td></tr>
			                <tr> <td style="border:none;" >Address:<?php echo e($data->_address ?? ''); ?> </td></tr>
            			</table>
            		</td>
            		<td colspan="4" style="border: 1px dotted grey;">
            			<table style="text-align: left;">
            				<tr> <td style="border:none;" > <?php echo e(invoice_barcode($data->_order_number ?? '')); ?></td></tr>
                    <tr> <td style="border:none;" > Invoice No: <?php echo e($data->_order_number ?? ''); ?></td></tr>
                  <tr> <td style="border:none;" > Date: <?php echo e(_view_date_formate($data->_date ?? '')); ?></td></tr>
            			</table>
            		</td>
            	</tr>
               
               
                <tbody>
                   <tr>
                   	<td style="text-align: left;border:1px dotted grey;width:5%;">SL</td>
                   	<td style="text-align: left;border:1px dotted grey;width:55%;">Item</td>
                    <td style="text-align: left;border:1px dotted grey;width:10%;">Unit</td>
                   	<td style="text-align: right;border:1px dotted grey;width:10%;">QTY</td>
                   	<td style="text-align: right;border:1px dotted grey;width:10%;">Rate</td>
                   	<td style="text-align: right;border:1px dotted grey;width:10%;">Amount</td>
                   </tr>
                   <?php
                                    $_value_total = 0;
                                    $_vat_total = 0;
                                    $_qty_total = 0;
                                    $_total_discount_amount = 0;
                                  ?>
                                  <?php $__empty_1 = true; $__currentLoopData = $data->_master_details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item_key=>$_item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                  <tr>
                                  
                                     <?php
                                      $_value_total +=$_item->_value ?? 0;
                                      $_vat_total += $_item->_vat_amount ?? 0;
                                      $_qty_total += $_item->_qty ?? 0;
                                      $_total_discount_amount += $_item->_discount_amount ?? 0;
                                     ?>
<td class="text-left" style="border:1px dotted grey;" ><?php echo e(($item_key+1)); ?></td>
<td  class="text-left" style="border:1px dotted grey;"><?php echo $_item->_items->_name ?? ''; ?><br>
   <?php 
                                                  $_barcodes_string = $_item->_barcode ?? '';
                                                  $_barcodes_string_length = strlen($_item->_barcode ?? '');
                                                  $_barcodes = explode(",",$_barcodes_string);
                                              ?>
                                              <?php if($_barcodes_string_length > 0): ?>
                                              <b>SN:</b>
                                                  <?php $__empty_2 = true; $__currentLoopData = $_barcodes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $barcode): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                                                    <span style="background: #f5f5f5;"><?php echo e($barcode ?? ''); ?>,</span>
                                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                                                  <?php endif; ?>
                                              <?php endif; ?>

</td>
<td  class="text-left" style="border:1px dotted grey;"><?php echo $_item->_trans_unit->_name ?? ''; ?></td>
<td  style="border:1px dotted grey;text-align: right;" ><?php echo _report_amount($_item->_qty ?? 0); ?></td>
<td  style="border:1px dotted grey;text-align: right;"><?php echo _report_amount($_item->_sales_rate ?? 0); ?></td>
<td  style="border:1px dotted grey;text-align: right;" ><?php echo _report_amount($_item->_value ?? 0); ?></td>
                                  </tr>
                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                  <?php endif; ?>
                                   <tr>
                              <td style="border:1px dotted grey;" colspan="3" class="text-right "><b>Sub Total</b></td>

                              <td style="border:1px dotted grey;text-align: right;" class="text-right "> <b><?php echo e(_report_amount($_qty_total ?? 0)); ?></b> </td>
                              <td style="border:1px dotted grey;text-align: right;"></td>
                              <td style="border:1px dotted grey;text-align: right;" class=" text-right"><b> <?php echo e(_report_amount($_value_total ?? 0)); ?></b>
                              </td>
                            </tr>
   
<tr>
  <td style="border:1px dotted grey;text-align: right;" colspan="5" class="text-right" ><b>Discount[-]</b></td>
  <td  style="border:1px dotted grey;text-align: right;" class="text-right"><?php echo _report_amount($data->_total_discount ?? 0); ?></td>
</tr>

<?php if($form_settings->_show_vat==1): ?>
<tr>
  <td style="border:1px dotted grey;text-align: right;" colspan="5" class="text-right" ><b>VAT[+]</b></td>
  <td style="border:1px dotted grey;text-align: right;" class="text-right"><?php echo _report_amount($data->_total_vat ?? 0); ?></td>
</tr>
<?php endif; ?>
<tr>
  <td style="border:1px dotted grey;text-align: right;" colspan="5" class="text-right" ><b>Net Total</b></td>
  <td style="border:1px dotted grey;text-align: right;" class="text-right"><?php echo _report_amount($data->_total ?? 0); ?></td>
</tr>
<?php
$accounts = $data->s_account ?? [];
$_due_amount =$data->_total ?? 0;
?>
<?php if(sizeof($accounts) > 0): ?>
<?php $__currentLoopData = $accounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ac_val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<?php if($ac_val->_ledger->id !=$data->_ledger_id): ?>
 <?php if($ac_val->_cr_amount > 0): ?>
 <?php
  $_due_amount +=$ac_val->_cr_amount ?? 0;
 ?>
<tr>
  <td style="border:1px dotted grey;text-align: right;" colspan="5" class="text-right" ><b> <?php echo $ac_val->_ledger->_name ?? ''; ?>[+]
    </b></td>
  <td style="border:1px dotted grey;text-align: right;" class="text-right"><?php echo _report_amount( $ac_val->_cr_amount ?? 0 ); ?></td>
</tr>
<?php endif; ?>
<?php if($ac_val->_dr_amount > 0): ?>
 <?php
  $_due_amount -=$ac_val->_dr_amount ?? 0;
 ?>
<tr>
  <td style="border:1px dotted grey;text-align: right;" colspan="5" class="text-right"><b> <?php echo $ac_val->_ledger->_name ?? ''; ?>[-]
    </b></td>
  <td style="border:1px dotted grey;text-align: right;" class="text-right"><?php echo _report_amount( $ac_val->_dr_amount ?? 0 ); ?></td>
</tr>
<?php endif; ?>

<?php endif; ?>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php if($_due_amount >= 0): ?>
<tr>
  <td style="border:1px dotted grey;text-align: right;" colspan="5" class="text-right" ><b>Invoice Due </b></td>
  <td style="border:1px dotted grey;text-align: right;" class="text-right"><?php echo _report_amount( $_due_amount); ?></td>
</tr>
<?php else: ?>
<tr>
  <td style="border:1px dotted grey;text-align: right;" colspan="5" class="text-right" ><b>Advance </b></td>
  <td style="border:1px dotted grey;text-align: right;" class="text-right"><?php echo _report_amount( -($_due_amount)); ?></td>
</tr>
  
  <?php endif; ?>

<?php endif; ?>
<?php if($form_settings->_show_p_balance==1): ?>
<tr>
  <td style="border:1px dotted grey;text-align: right;" colspan="5" class="text-right" ><b>Previous Balance</b></td>
  <td  style="border:1px dotted grey;text-align: right;" class="text-right"><?php echo _show_amount_dr_cr(_report_amount($data->_p_balance ?? 0)); ?></td>
</tr>
<tr>
  <td style="border:1px dotted grey;text-align: right;" colspan="5" class="text-right" ><b>Current Balance</b></td>
  <td style="border:1px dotted grey;text-align: right;"class="text-right"><?php echo _show_amount_dr_cr(_report_amount($data->_l_balance ?? 0)); ?></td>
</tr>
<?php endif; ?>
<tr>
  <td style="border:1px dotted grey;" colspan="6" class="text-left" >In Words:  <?php echo e(nv_number_to_text($data->_total ?? 0)); ?></td>
</tr>

<tr>
  <td style="border:1px dotted grey;" colspan="6" class="text-left" >  <?php echo e($settings->_sales_note ?? ''); ?></td>
</tr>
<tr>
  <td colspan="6">
    <?php echo $__env->make("backend.sales.invoice_history", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
  </td>
</tr>

                   
                
				</tbody>
            </table>
            
        </section>
	

<script type="text/javascript">
  window.print();
</script><?php /**PATH D:\xampp\htdocs\own\inv-acc-hrm\resources\views/backend/sales/pos_template.blade.php ENDPATH**/ ?>