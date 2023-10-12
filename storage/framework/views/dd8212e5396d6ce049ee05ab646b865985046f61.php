
<?php $__env->startSection('title',$page_name); ?>

<?php $__env->startSection('content'); ?>

  <div class="content ">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
          <div class="card">
           <div class="row mb-2">
                  <div class="col-sm-6">
                    <a class="m-0 _page_name" href="<?php echo e(route('hrm-employee.index')); ?>"><?php echo $page_name; ?> </a>
                  </div><!-- /.col -->
                  <div class="col-sm-6">
                    
                  </div><!-- /.col -->
                </div><!-- /.row -->
          
         <div class="message-area">
    <?php echo $__env->make('backend.message.message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>
            <div class="card-body p-4" >
                <?php echo Form::open(array('route' => 'hrm-employee.store','method'=>'POST','enctype'=>'multipart/form-data')); ?>

                <div class="row">
                        
                      
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo __('label.employee_category_id'); ?>:<span class="_required">*</span></label>
                                <select class="form-control" name="_category_id" required>
                                  <option value=""><?php echo e(__('label.select')); ?></option>
                                  <?php $__empty_1 = true; $__currentLoopData = $employee_catogories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                  <option value="<?php echo e($val->id); ?>" <?php if(old('_category_id')==$val->id): ?> selected <?php endif; ?>><?php echo $val->_name ?? ''; ?></option>
                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                  <?php endif; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo __('label._department_id'); ?>:<span class="_required">*</span></label>
                                <select class="form-control select2" name="_department_id" required >
                                  <option value=""><?php echo e(__('label.select')); ?></option>
                                  <?php $__empty_1 = true; $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                  <option value="<?php echo e($val->id); ?>" <?php if(old('_department_id')==$val->id): ?> selected <?php endif; ?>><?php echo $val->_department ?? ''; ?></option>
                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                  <?php endif; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo __('label._jobtitle_id'); ?>:<span class="_required">*</span></label>
                                <select class="form-control select2" name="_jobtitle_id" required >
                                  <option value=""><?php echo e(__('label.select')); ?></option>
                                  <?php $__empty_1 = true; $__currentLoopData = $designations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                  <option value="<?php echo e($val->id); ?>" <?php if(old('_jobtitle_id')==$val->id): ?> selected <?php endif; ?>><?php echo $val->_name ?? ''; ?></option>
                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                  <?php endif; ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo __('label._grade_id'); ?>:<span class="_required">*</span></label>
                                <select class="form-control " name="_grade_id" required >
                                  <option value=""><?php echo e(__('label.select')); ?></option>
                                  <?php $__empty_1 = true; $__currentLoopData = $grades; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                  <option value="<?php echo e($val->id); ?>" <?php if(old('_grade_id')==$val->id): ?> selected <?php endif; ?>><?php echo $val->_grade ?? ''; ?></option>
                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                  <?php endif; ?>
                                </select>
                            </div>
                        </div>
                        
                          <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo __('label._name'); ?>:<span class="_required">*</span></label>
                                <input type="text" class="form-control" name="_name" value="<?php echo e(old('_name')); ?>" placeholder="<?php echo e(__('label._name')); ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo __('label._code'); ?>:</label>
                                <?php echo Form::text('_code', old('_code'), array('placeholder' => __('label._code'),'class' => 'form-control')); ?>

                                
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                            <label><?php echo __('label._father'); ?>:<span class="_required">*</span></label>
                                <?php echo Form::text('_father', old('_father'), array('placeholder' => __('label._father'),'class' => 'form-control','required' => 'true')); ?>

                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                            <label><?php echo __('label._mother'); ?>:</label>
                                <?php echo Form::text('_mother', old('_mother'), array('placeholder' => __('label._mother'),'class' => 'form-control')); ?>

                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                            <label><?php echo __('label._spouse'); ?>:</label>
                                <?php echo Form::text('_spouse', old('_spouse'), array('placeholder' => __('label._spouse'),'class' => 'form-control')); ?>

                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                            <label><?php echo __('label._mobile1'); ?>:</label>
                                <?php echo Form::text('_mobile1', old('_mobile1'), array('placeholder' => __('label._mobile1'),'class' => 'form-control')); ?>

                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                            <label><?php echo __('label._mobile2'); ?>:</label>
                                <?php echo Form::text('_mobile2', old('_mobile2'), array('placeholder' => __('label._mobile2'),'class' => 'form-control')); ?>

                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                            <label><?php echo __('label._spousesmobile'); ?>:</label>
                                <?php echo Form::text('_spousesmobile', old('_spousesmobile'), array('placeholder' => __('label._spousesmobile'),'class' => 'form-control')); ?>

                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                            <label><?php echo __('label._nid'); ?>:</label>
                                <?php echo Form::text('_nid', old('_nid'), array('placeholder' => __('label._nid'),'class' => 'form-control')); ?>

                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="form-group">
                            <label><?php echo __('label._gender'); ?>:</label>
                                <select class="form-control" name="_gender">
                                    <option value=""><?php echo __('label.select'); ?>--></option>
                                    <?php $__empty_1 = true; $__currentLoopData = _gender_list(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                     <option value="<?php echo e($val); ?>" <?php if($val==old('_gender')): ?> selected <?php endif; ?>><?php echo $val; ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                            <label><?php echo __('label._bloodgroup'); ?>:</label>
                                <?php echo Form::text('_bloodgroup', old('_bloodgroup'), array('placeholder' => __('label._bloodgroup'),'class' => 'form-control')); ?>

                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                            <label><?php echo __('label._religion'); ?>:</label>
                                <?php echo Form::text('_religion', old('_religion'), array('placeholder' => __('label._religion'),'class' => 'form-control')); ?>

                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                            <label><?php echo __('label._dob'); ?>:</label>
                                <?php echo Form::date('_dob', old('_dob'), array('placeholder' => __('label._dob'),'class' => 'form-control')); ?>

                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                            <label><?php echo __('label._education'); ?>:</label>
                                <?php echo Form::text('_education', old('_education'), array('placeholder' => __('label._education'),'class' => 'form-control')); ?>

                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                            <label><?php echo __('label._email'); ?>:</label>
                                <?php echo Form::email('_email', old('_email'), array('placeholder' => __('label._email'),'class' => 'form-control')); ?>

                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                            <label><?php echo __('label._officedes'); ?>:</label>
                                <?php echo Form::text('_officedes', old('_officedes'), array('placeholder' => __('label._officedes'),'class' => 'form-control')); ?>

                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                            <label><?php echo __('label._bank'); ?>:</label>
                                <?php echo Form::text('_bank', old('_bank'), array('placeholder' => __('label._bank'),'class' => 'form-control')); ?>

                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                            <label><?php echo __('label._bankac'); ?>:</label>
                                <?php echo Form::text('_bankac', old('_bankac'), array('placeholder' => __('label._bankac'),'class' => 'form-control')); ?>

                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                            <label><?php echo __('label._doj'); ?>:</label>
                                <?php echo Form::date('_doj', old('_doj'), array('placeholder' => __('label._doj'),'class' => 'form-control')); ?>

                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                            <label><?php echo __('label._tin'); ?>:</label>
                                <?php echo Form::text('_tin', old('_tin'), array('placeholder' => __('label._tin'),'class' => 'form-control')); ?>

                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo __('label._location'); ?>:</label>
                                <select class="form-control select2" name="_location"  >
                                  <option value=""><?php echo e(__('label.select')); ?></option>
                                  <?php $__empty_1 = true; $__currentLoopData = $job_locations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                  <option value="<?php echo e($val->id); ?>" <?php if(old('_location')==$val->id): ?> selected <?php endif; ?>><?php echo $val->_name ?? ''; ?></option>
                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                  <?php endif; ?>
                                </select>
                            </div>
                        </div>
<?php
$users = \Auth::user();
$permited_organizations = permited_organization(explode(',',$users->organization_ids));
$permited_branch = permited_branch(explode(',',$users->branch_ids));
$permited_costcenters = permited_costcenters(explode(',',$users->cost_center_ids));
?> 


<div class="col-xs-12 col-sm-12 col-md-3 <?php if(sizeof($permited_organizations)==1): ?> display_none <?php endif; ?>">
 <div class="form-group ">
     <label><?php echo __('label.organization'); ?>:<span class="_required">*</span></label>
    <select class="form-control _master_organization_id" name="organization_id" required >

       
       <?php $__empty_1 = true; $__currentLoopData = $permited_organizations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
       <option value="<?php echo e($val->id); ?>" <?php if(isset($request->organization_id)): ?> <?php if($request->organization_id == $val->id): ?> selected <?php endif; ?>   <?php endif; ?>><?php echo e($val->id ?? ''); ?> - <?php echo e($val->_name ?? ''); ?></option>
       <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
       <?php endif; ?>
     </select>
 </div>
</div>
<div class="col-xs-12 col-sm-12 col-md-3 <?php if(sizeof($permited_branch)==1): ?> display_none <?php endif; ?>">
 <div class="form-group ">
     <label><?php echo e(__('label.Branch')); ?>:<span class="_required">*</span></label>
    <select class="form-control _master_branch_id" name="_branch_id" required >
       
       <?php $__empty_1 = true; $__currentLoopData = $permited_branch; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $branch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
       <option value="<?php echo e($branch->id); ?>" <?php if(isset($request->_branch_id)): ?> <?php if($request->_branch_id == $branch->id): ?> selected <?php endif; ?>   <?php endif; ?>><?php echo e($branch->id ?? ''); ?> - <?php echo e($branch->_name ?? ''); ?></option>
       <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
       <?php endif; ?>
     </select>
 </div>
</div>
<div class="col-xs-12 col-sm-12 col-md-3 <?php if(sizeof($permited_costcenters)==1): ?> display_none <?php endif; ?>">
 <div class="form-group ">
     <label><?php echo e(__('label.Cost center')); ?>:<span class="_required">*</span></label>
    <select class="form-control _cost_center_id" name="_cost_center_id" required >
       
       <?php $__empty_1 = true; $__currentLoopData = $permited_costcenters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cost_center): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
       <option value="<?php echo e($cost_center->id); ?>" <?php if(isset($request->_cost_center_id)): ?> <?php if($request->_cost_center_id == $cost_center->id): ?> selected <?php endif; ?>   <?php endif; ?>><?php echo e($cost_center->id ?? ''); ?> - <?php echo e($cost_center->_name ?? ''); ?></option>
       <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
       <?php endif; ?>
     </select>
 </div>
</div>


                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo __('label.image'); ?>:<span class="_required">*</span></label>
                                <input type="file" accept="image/*" onchange="loadFile(event,1 )"  name="_photo" class="form-control">
                               <img id="output_1" class="banner_image_create" src="<?php echo e(asset('/')); ?><?php echo e($settings->logo ?? ''); ?>"  style="max-height:100px;max-width: 100px; " />
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label><?php echo e(__('label._status')); ?>:</label>
                                <select class="form-control" name="_status">
                                  <option value="1">Active</option>
                                  <option value="0">In Active</option>
                                </select>
                            </div>
                        </div>
                </div>
                     
                      
                      
                      
                      
                
                        <div class="col-xs-12 col-sm-12 col-md-12  text-middle">
                            <button type="submit" class="btn btn-success  ml-5"><i class="fa fa-credit-card mr-2" aria-hidden="true"></i> <?php echo e(__('label.save')); ?></button>
                           
                        </div>
                        <br><br>
                    
                 
                    
                    
                     
                    <?php echo Form::close(); ?>

                
              </div>
          
          </div>
        </div>
        <!-- /.row -->
      </div>
    </div>  
</div>



<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>

<script type="text/javascript">


 

  

  

     

         

</script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\own\inv-acc-hrm\resources\views/hrm/hrm-employee/create.blade.php ENDPATH**/ ?>