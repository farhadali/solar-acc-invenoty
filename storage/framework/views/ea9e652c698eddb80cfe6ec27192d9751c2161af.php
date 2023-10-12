<?php if($form_settings->_show_due_history==1): ?>
<?php if(sizeof($history_sales_invoices) > 0): ?> 
        <table class="table table-bordered" style="width: 100%; border-collapse: collapse;">
          <thead>
            <tr>
              <th style="border:1px dotted grey;">Date</th>
              <th style="border:1px dotted grey;">Order Number</th>
              <th style="border:1px dotted grey;">Sales Amount</th>
              <th style="border:1px dotted grey;">Due Amount</th>
              <th style="border:1px dotted grey;">Days</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $due_sales_amount=0;
            $due_due_amount =0;
            ?>
            <?php $__empty_1 = true; $__currentLoopData = $history_sales_invoices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $his_val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <?php
            $due_sales_amount +=$his_val->_total ?? 0;
            $due_due_amount +=$his_val->_due_amount ?? 0;
            ?>
              <tr>
              <td style="border:1px dotted grey;"><?php echo e(_view_date_formate($his_val->_date ?? '')); ?></td>
              <td style="border:1px dotted grey;"><?php echo e($his_val->_order_number ?? ''); ?></td>
              <td style="border:1px dotted grey;"><?php echo e(_report_amount($his_val->_total ?? 0)); ?></td>
              <td style="border:1px dotted grey;"><?php echo e(_report_amount($his_val->_due_amount ?? 0)); ?></td>
              <td style="border:1px dotted grey;"><?php echo e(_date_diff($his_val->_date,date('Y-m-d'))); ?></td>
              
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <?php endif; ?>
          </tbody>
          <tfoot>
            <tr>
              <td style="border:1px dotted grey;" colspan="2"><b>Total</b></td>
              <td style="border:1px dotted grey;"><b><?php echo e(_report_amount($due_sales_amount ?? 0)); ?></b></td>
              <td style="border:1px dotted grey;"><b><?php echo e(_report_amount($due_due_amount ?? 0)); ?></b></td>
              <td style="border:1px dotted grey;"></td>
            </tr>
          </tfoot>
        </table>

<?php endif; ?>
<?php endif; ?><?php /**PATH D:\xampp\htdocs\own\inv-acc-hrm\resources\views/backend/sales/invoice_history.blade.php ENDPATH**/ ?>