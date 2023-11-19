
<?php

$previous_filter= Session::get('groupBaseLedgerReportFilter');

?>

<?php $__empty_1 = true; $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
      <option value="<?php echo e($val->id); ?>" 
      	<?php if(isset($previous_filter["_account_ledger_id"])): ?>
      	<?php if(in_array($val->id,$previous_filter["_account_ledger_id"])): ?> selected <?php endif; ?>
        <?php endif; ?>  ><?php echo e($val->id ?? ''); ?> - <?php echo e($val->_name ?? ''); ?></option>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
      <?php endif; ?><?php /**PATH D:\xampp\htdocs\own\inv-acc-hrm\resources\views/backend/account-ledger/group_base_ledger.blade.php ENDPATH**/ ?>