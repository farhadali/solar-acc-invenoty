
<?php $__env->startSection('title',$page_name); ?>

<?php $__env->startSection('content'); ?>
<div class="wrapper print_content">
  <style type="text/css">
  .table td, .table th {
    padding: 0.10rem;
    vertical-align: top;
    border-top: 1px solid #dee2e6;
}
  </style>
<div class="_report_button_header">
    <a class="nav-link"  href="<?php echo e(url('day-book')); ?>" role="button">
          <i class="fas fa-search"></i>
        </a>
 <a style="cursor: pointer;" class="nav-link"  title="Print" onclick="javascript:printDiv('printablediv')"><i class="fas fa-print"></i></a>
      <a style="cursor: pointer;" onclick="fnExcelReport();" class="nav-link"  title="Excel Download" ><i class="fa fa-file-excel" aria-hidden="true"></i></a>
  </div>

<section class="invoice" id="printablediv">
    
    
    
        <table class="table" style="border:none;width: 100%;">
          <tr>
            
            <td style="border:none;width: 100%;text-align: center;">
              <table class="table" style="border:none;">
                <tr style="line-height: 16px;" > <td class="text-center" style="border:none;font-size: 24px;"><b><?php echo e($settings->name ?? ''); ?></b></td> </tr>
                <tr style="line-height: 16px;" > <td class="text-center" style="border:none;"><?php echo e($settings->_address ?? ''); ?></td></tr>
                <tr style="line-height: 16px;" > <td class="text-center" style="border:none;"><?php echo e($settings->_phone ?? ''); ?>,<?php echo e($settings->_email ?? ''); ?></td></tr>
                 <tr style="line-height: 16px;" > <td class="text-center" style="border:none;"><b><?php echo e($page_name); ?> </b></td> </tr>
                 <tr style="line-height: 16px;" > <td class="text-center" style="border:none;"><strong>Date: <?php echo e(_view_date_formate($request->_datex ?? '')); ?> To <?php echo e(_view_date_formate($request->_datey ?? '')); ?> </strong></td> </tr>
                 <tr style="line-height: 16px;" > <td class="text-center" style="border:none;">
                  <br />
                  <b><?php $__currentLoopData = $permited_branch; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p_branch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                      <?php if(isset($previous_filter["_branch_id"])): ?>
                        <?php if(in_array($p_branch->id,$previous_filter["_branch_id"])): ?> 
                       <span style="background: #f4f6f9;margin-right: 2px;padding: 5px;"><b><?php echo e($p_branch["_name"]); ?></b></span>    
                        <?php endif; ?>
                      <?php endif; ?>
                      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?> </b></td> </tr>
              </table>
            </td>
           
          </tr>
        </table>
       

    <!-- Table row -->
     <table class="cewReportTable">
          <thead>
          <tr>
           <th style="border:1px solid silver;width: 10%;" class="text-left" >ID</th>
           <th style="border:1px solid silver;width: 20%;" class="text-left" >Group</th>
            <th style="border:1px solid silver;width: 20%;" class="text-left" >Ledger </th>
            <th style="border:1px solid silver;width: 20%;" class="text-left" >Narration</th>
            <th style="border:1px solid silver;width: 10%;" class="text-right" >Dr.Amount </th>
            <th style="border:1px solid silver;width: 10%;" class="text-right" >Cr.Amount </th>
          </tr>
          
          
          </thead>
          <tbody>
             <?php $__empty_1 = true; $__currentLoopData = $_result_group; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
             <tr>
               <td colspan="6"><?php echo e($key); ?></td>
             </tr>
             <?php
              $_sub_total_dr_amount =0;
              $_sub_total_cr_amount =0;
             ?>
             <?php $__empty_2 = true; $__currentLoopData = $detail; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=> $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
             <?php
              $_sub_total_dr_amount +=$value->_dr_amount ?? 0;
              $_sub_total_cr_amount +=$value->_cr_amount ?? 0;
             ?>
             <tr>
              
               <td>
                <?php if($key ==0): ?>
                   
               <?php if($value->_table_name=="voucher_masters"): ?>
                 <a style="text-decoration: none;" target="__blank" href="<?php echo e(route('voucher.show',$value->_id)); ?>">
                <?php echo e(voucher_prefix()); ?><?php echo $value->_id ?? ''; ?></a>
                    <?php endif; ?>
                    <?php if($value->_table_name=="purchases"): ?>
                 <a style="text-decoration: none;" target="__blank" href="<?php echo e(url('purchase/print',$value->_id)); ?>">
                  <?php echo e(_purchase_pfix()); ?><?php echo $value->_id ?? ''; ?></a>
                    <?php endif; ?>
                    <?php if($value->_table_name=="purchase_accounts"): ?>
                 <a style="text-decoration: none;" target="__blank" href="<?php echo e(url('purchase/print',$value->_id)); ?>">
                  <?php echo e(_purchase_pfix()); ?><?php echo $value->_id ?? ''; ?></a>
                    <?php endif; ?>
                    <?php if($value->_table_name=="purchases_return"): ?>
                 <a style="text-decoration: none;" target="__blank" href="<?php echo e(url('purchase-return/print',$value->_id)); ?>">
                  <?php echo e(_purchase_return_pfix()); ?> <?php echo $value->_id ?? ''; ?></a>
                    <?php endif; ?>
                    <?php if($value->_table_name=="purchase_return_accounts"): ?>
                 <a style="text-decoration: none;" target="__blank" href="<?php echo e(url('purchase-return/print',$value->_id)); ?>">
                  <?php echo e(_purchase_return_pfix()); ?><?php echo $value->_id ?? ''; ?></a>
                    <?php endif; ?>
                    <?php if($value->_table_name=="sales"): ?>
                 <a style="text-decoration: none;" target="__blank" href="<?php echo e(url('sales/print',$value->_id)); ?>">
                  <?php echo e(_sales_pfix()); ?> <?php echo $value->_id ?? ''; ?></a>
                    <?php endif; ?>
                    <?php if($value->_table_name=="sales_accounts"): ?>
                 <a style="text-decoration: none;" target="__blank" href="<?php echo e(url('sales/print',$value->_id)); ?>">
                  <?php echo e(_sales_pfix()); ?><?php echo $value->_id ?? ''; ?></a>
                    <?php endif; ?>
                    <?php if($value->_table_name=="warranty_masters"): ?>
                 <a style="text-decoration: none;" target="__blank" href="<?php echo e(url('warranty-manage/print',$value->_id)); ?>">
                  <?php echo e(warranty_prefix()); ?> <?php echo $value->_id ?? ''; ?></a> <br>
                    <?php endif; ?>

                    <?php if($value->_table_name=="warranty_accounts"): ?>
                 <a style="text-decoration: none;" target="__blank" href="<?php echo e(url('warranty-manage/print',$value->_id)); ?>">
                   <?php echo e(warranty_prefix()); ?> <?php echo $value->_id ?? ''; ?></a>
                    <?php endif; ?>
                    <?php if($value->_table_name=="resturant_sales"): ?>
                 <a style="text-decoration: none;" target="__blank" href="<?php echo e(url('restaurant-sales/print',$value->_id)); ?>">
                  <?php echo e(resturant_prefix()); ?><?php echo $value->_id ?? ''; ?></a>
                    <?php endif; ?>
                    <?php if($value->_table_name=="restaurant_sales_accounts"): ?>
                 <a style="text-decoration: none;" target="__blank" href="<?php echo e(url('restaurant-sales/print',$value->_id)); ?>">
                  <?php echo e(resturant_prefix()); ?><?php echo $value->_id ?? ''; ?></a>
                    <?php endif; ?>
                    <?php if($value->_table_name=="resturant_sales_accounts"): ?>
                 <a style="text-decoration: none;" target="__blank" href="<?php echo e(url('restaurant-sales/print',$value->_id)); ?>">
                 <?php echo e(resturant_prefix()); ?><?php echo $value->_id ?? ''; ?></a>
                    <?php endif; ?>
                    <?php if($value->_table_name=="sales_return"): ?>
                 <a style="text-decoration: none;" target="__blank" href="<?php echo e(url('sales-return/print',$value->_id)); ?>">
                  <?php echo e(_sales_return_pfix()); ?> <?php echo $value->_id ?? ''; ?></a>
                    <?php endif; ?>
                    <?php if($value->_table_name=="sales_return_accounts"): ?>
                 <a style="text-decoration: none;" target="__blank" href="<?php echo e(url('sales-return/print',$value->_id)); ?>">
                  <?php echo e(_sales_return_pfix()); ?><?php echo $value->_id ?? ''; ?></a>
                    <?php endif; ?>
                    <?php if($value->_table_name=="damage"): ?>
                 <a style="text-decoration: none;" target="__blank" href="<?php echo e(url('damage/print',$value->_id)); ?>">
                  <?php echo e(_damage_pfix()); ?> <?php echo $value->_id ?? ''; ?></a>
                    <?php endif; ?>

                <?php if($value->_table_name=="transfer"): ?>
                 <a style="text-decoration: none;" target="__blank" href="<?php echo e(url('transfer-production/print',$value->_id)); ?>">
                  <?php echo e(_transfer_prefix()); ?> <?php echo $value->_id ?? ''; ?></a>
                    <?php endif; ?>
                    <?php if($value->_table_name=="production"): ?>
                 <a style="text-decoration: none;" target="__blank" href="<?php echo e(url('transfer-production/print',$value->_id)); ?>">
                  <?php echo e(production_prefix()); ?> <?php echo $value->_id ?? ''; ?></a>
                    <?php endif; ?>

                    <?php if($value->_table_name=="replacement_masters"): ?>
                       <a style="text-decoration: none;" target="__blank" href="<?php echo e(url('item-replace/print',$value->_id)); ?>">
                       <?php echo e(_replace_prefix()); ?><?php echo $value->_id ?? ''; ?></a>
                   <?php endif; ?>
                   <?php if($value->_table_name=="replacement_item_accounts"): ?>
                       <a style="text-decoration: none;" target="__blank" href="<?php echo e(url('item-replace/print',$value->_id)); ?>">
                        <?php echo e(_replace_prefix()); ?> <?php echo $value->_id ?? ''; ?></a>
                   <?php endif; ?>

                   <?php if($value->_table_name=="warranty_masters"): ?>
                 <a style="text-decoration: none;" target="__blank" href="<?php echo e(url('warranty-manage/print',$value->_id)); ?>">
                  <?php echo e(warranty_prefix()); ?><?php echo $value->_id ?? ''; ?></a>
                    <?php endif; ?>
                   <?php if($value->_table_name=="service_masters"): ?>
                 <a style="text-decoration: none;" target="__blank" href="<?php echo e(url('third-party-service/print',$value->_id)); ?>">
                  <?php echo e(service_prefix()); ?><?php echo $value->_id ?? ''; ?></a>
                    <?php endif; ?>
                   <?php if($value->_table_name=="individual_replace_masters"): ?>
                 <a style="text-decoration: none;" target="__blank" href="<?php echo e(url('individual-replacement-print',$detail->_id)); ?>">
                  <?php echo e(ind_rep_prefix()); ?><?php echo $detail->_id ?? ''; ?></a>
                    <?php endif; ?>



                    <?php endif; ?>
                    


               </td>
               
               <td><?php echo e(_group_name($value->_account_group)); ?></td>
               <td><?php echo e(_ledger_name($value->_account_ledger)); ?></td>
               <td><?php echo e($value->_narration ?? 'N/A'); ?></td>
               
               <td class="text-right"><?php echo e(_report_amount(  $value->_dr_amount ?? 0 )); ?></td>
               <td class="text-right"><?php echo e(_report_amount(  $value->_cr_amount ?? 0 )); ?></td>
             </tr>
             <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
             <?php endif; ?>
             <tr>
               <td colspan="4"><b>Sub Total</b></td>
               <td class="text-right"><b><?php echo e(_report_amount(  $_sub_total_dr_amount ?? 0 )); ?></b></td>
               <td class="text-right"><b><?php echo e(_report_amount(  $_sub_total_cr_amount ?? 0 )); ?></b></td>


             </tr>

            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <?php endif; ?>
           
          
          </tbody>
          
        </table>

      

    
    <!-- /.row -->
  </section>

</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>



<?php $__env->stopSection(); ?>

<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/sobuz23/sspf.sobuzsathitraders.com/resources/views/backend/account-report/report_day_book.blade.php ENDPATH**/ ?>