<?php $__empty_1 = true; $__currentLoopData = $conversionUnits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
	<tr>
                  
                  <td>
                    <input type="number" name="_conversion_qty[]" min="0" step="any" class="form-control _conversion_qty" value="<?php echo e($value->_conversion_qty ?? 0); ?>">
                  </td>
                  <td>
                    <span class="baseUnitName"><?php echo e($base_unit_name); ?></span> <b>=</b>
                    <input type="hidden" name="conversion_id[]" class="conversion_id" value="<?php echo e($value->id); ?>">
                    <input type="hidden" name="conversion_item_id[]" class="conversion_item_id" value="<?php echo e($value->_item_id); ?>">
                    <input type="hidden" name="_base_unit_id[]" class="_base_unit_id" value="<?php echo e($value->_base_unit_id); ?>">
                  </td>
                  <td>
                    <select class="form-control _conversion_unit" id="_conversion_unit" name="_conversion_unit[]" >
                      <option value="" >--Units--</option>
                      <?php $__currentLoopData = $units; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $unit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                       <option value="<?php echo e($unit->id); ?>" <?php if($unit->id==$value->_conversion_unit): ?> selected <?php endif; ?> ><?php echo e($unit->_name ?? ''); ?></option>
                      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                  </td>
                  <td>
                    <button type="button" class="btn btn-sm btn-danger unitRemoveButton">X</button>
                    
                  </td>
                </tr>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
<?php endif; ?><?php /**PATH D:\xampp\htdocs\own\inv-acc-hrm\resources\views/backend/item-information/unit_ajax.blade.php ENDPATH**/ ?>