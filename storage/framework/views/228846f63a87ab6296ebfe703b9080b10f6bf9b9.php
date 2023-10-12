<?php

$previous_filter= Session::get('filter_stock_possition');

?>

<?php $__empty_1 = true; $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
      <option value="<?php echo e($val->id); ?>" 
      	<?php if(isset($previous_filter["_item_id"])): ?>
      	<?php if(in_array($val->id,$previous_filter["_item_id"])): ?> selected <?php endif; ?>
        <?php endif; ?>  ><?php echo e($val->_item ?? ''); ?></option>
  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
  <?php endif; ?><?php /**PATH D:\xampp\htdocs\own\sabuz-bhai\sspf.sobuzsathitraders.com\sspf.sobuzsathitraders.com\resources\views/backend/item-category/stock_possition_cat_base_item.blade.php ENDPATH**/ ?>