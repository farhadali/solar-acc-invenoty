     <?php
              $users = \Auth::user();
              $permited_organizations = permited_organization(explode(',',$users->organization_ids));
              ?> 


              <div class="col-xs-12 col-sm-12 col-md-12 ">
              <div class="form-group ">
              <label><?php echo __('label.organization'); ?>:</label>
              <select multiple class="form-control _master_organization_id" name="organization_id[]"  >
               <?php $__empty_1 = true; $__currentLoopData = $permited_organizations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
               <option value="<?php echo e($val->id); ?>"  <?php if(isset($previous_filter["organization_id"])): ?> 
                               <?php if(in_array($val->id,$previous_filter["organization_id"])): ?> selected <?php endif; ?>
                               <?php endif; ?>><?php echo e($val->id ?? ''); ?> - <?php echo e($val->_name ?? ''); ?></option>
               <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
               <?php endif; ?>
              </select>
              </div>
              </div><?php /**PATH D:\xampp\htdocs\own\inv-acc-hrm\resources\views/basic/org_report.blade.php ENDPATH**/ ?>