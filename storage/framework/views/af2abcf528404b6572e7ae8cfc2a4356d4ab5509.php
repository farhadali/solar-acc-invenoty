<?php
$users = \Auth::user();
$permited_organizations = permited_organization(explode(',',$users->organization_ids));
$permited_branch = permited_branch(explode(',',$users->branch_ids));
$permited_costcenters = permited_costcenters(explode(',',$users->cost_center_ids));
?> 


<div class="col-xs-12 col-sm-12 col-md-2 ">
 <div class="form-group ">
     <label><?php echo __('label.organization'); ?>:<span class="_required">*</span></label>
    <select class="form-control _master_organization_id" name="organization_id" required >

<?php if(sizeof($permited_organizations) > 0): ?>
       <option value=""><?php echo e(__('label.select')); ?> <?php echo __('label.organization'); ?></option>
<?php endif; ?>
       <?php $__empty_1 = true; $__currentLoopData = $permited_organizations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
       <option value="<?php echo e($val->id); ?>" <?php if(isset($request->organization_id)): ?> <?php if($request->organization_id == $val->id): ?> selected <?php endif; ?>   <?php endif; ?>><?php echo e($val->id ?? ''); ?> - <?php echo e($val->_name ?? ''); ?></option>
       <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
       <?php endif; ?>
     </select>
 </div>
</div>
<div class="col-xs-12 col-sm-12 col-md-2 ">
 <div class="form-group ">
     <label><?php echo __('label.Branch'); ?>:<span class="_required">*</span></label>
    <select class="form-control _master_branch_id" name="_branch_id" required >
      <?php if(sizeof($permited_branch) > 0): ?>
       <option value=""><?php echo e(__('label.select')); ?> <?php echo __('label.Branch'); ?></option>
       <?php endif; ?>
       <?php $__empty_1 = true; $__currentLoopData = $permited_branch; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $branch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
       <option value="<?php echo e($branch->id); ?>" <?php if(isset($request->_branch_id)): ?> <?php if($request->_branch_id == $branch->id): ?> selected <?php endif; ?>   <?php endif; ?>><?php echo e($branch->id ?? ''); ?> - <?php echo e($branch->_name ?? ''); ?></option>
       <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
       <?php endif; ?>
     </select>
 </div>
</div>
<div class="col-xs-12 col-sm-12 col-md-2 ">
 <div class="form-group ">
     <label><?php echo e(__('label.Cost center')); ?>:<span class="_required">*</span></label>
    <select class="form-control _cost_center_id" name="_cost_center_id" required >
       <?php if(sizeof($permited_costcenters) > 0): ?>
       <option value=""><?php echo e(__('label.select')); ?> <?php echo e(__('label.Cost center')); ?></option>
       <?php endif; ?>
       <?php $__empty_1 = true; $__currentLoopData = $permited_costcenters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cost_center): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
       <option value="<?php echo e($cost_center->id); ?>" <?php if(isset($request->_cost_center_id)): ?> <?php if($request->_cost_center_id == $cost_center->id): ?> selected <?php endif; ?>   <?php endif; ?>><?php echo e($cost_center->id ?? ''); ?> - <?php echo e($cost_center->_name ?? ''); ?></option>
       <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
       <?php endif; ?>
     </select>
 </div>
</div><?php /**PATH D:\xampp\htdocs\own\inv-acc-hrm\resources\views/basic/org_create.blade.php ENDPATH**/ ?>