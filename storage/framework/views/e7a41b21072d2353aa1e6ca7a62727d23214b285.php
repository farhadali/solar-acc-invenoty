
<?php

$previous_filter= Session::get('date_wise_purchase_statement');

?>

<?php $__empty_1 = true; $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
      <option value="<?php echo e($val->id); ?>" 
      	<?php if(isset($previous_filter["_account_ledger_id"])): ?>
      	<?php if(in_array($val->id,$previous_filter["_account_ledger_id"])): ?> selected <?php endif; ?>
        <?php endif; ?>  ><?php echo e($val->id ?? ''); ?> - <?php echo e($val->_name ?? ''); ?></option>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
      <?php endif; ?><?php /**PATH /home/sobuz23/sspf.sobuzsathitraders.com/resources/views/backend/account-ledger/group_base_ledger_pur_statement.blade.php ENDPATH**/ ?>