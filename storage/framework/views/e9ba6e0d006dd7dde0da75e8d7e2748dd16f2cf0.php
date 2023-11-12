<div  >

  
    <div class="modal fade" id="modal-default">
        <div class="modal-dialog modal-lg">
           <form action="" method="GET" class="form-horizontal">
            <?php echo csrf_field(); ?>
              <div class="modal-content">
                <div class="modal-header">
                  
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                 <?php 
                    $row_numbers = filter_page_numbers();
                         
                  ?>
                  <div class="form-group row">
                    <label for="filter_limit" class="col-sm-2 col-form-label">Limit:</label>
                    <div class="col-sm-10">
                     <select name="limit" class="form-control" id="filter_limit" >
                              <?php $__empty_1 = true; $__currentLoopData = $row_numbers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                               <option   value="<?php echo e($row); ?>" <?php if($limit==$row): ?> selected <?php endif; ?> ><?php echo e($row); ?> </option>
                              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                              <?php endif; ?>
                      </select>
                    </div>
                  </div>
                  
                  
                  <div class="form-group row">
                    <label for="id" class="col-sm-2 col-form-label"><?php echo e(__('label.id')); ?>:</label>
                    <div class="col-sm-10">
                      <input type="text" id="id" name="id" class="form-control" placeholder="<?php echo e(__('label.id')); ?>" value="<?php if(isset($request->id)): ?> <?php echo e($request->id ?? ''); ?>  <?php endif; ?>">
                    </div>
                  </div>
                  
                  <div class="form-group row">
                    <label for="_name" class="col-sm-2 col-form-label"><?php echo e(__('label._name')); ?>:</label>
                    <div class="col-sm-10">
                      <input type="text" id="_name" name="_name" class="form-control" placeholder="<?php echo e(__('label._name')); ?>" value="<?php if(isset($request->_name)): ?> <?php echo e($request->_name ?? ''); ?>  <?php endif; ?>">
                    </div>
                  </div>
                  
                  <div class="form-group row">
                    <label for="_code" class="col-sm-2 col-form-label"><?php echo e(__('label._code')); ?>:</label>
                    <div class="col-sm-10">
                      <input type="text" id="_code" name="_code" class="form-control" placeholder="<?php echo e(__('label._code')); ?>" value="<?php if(isset($request->_code)): ?> <?php echo e($request->_code ?? ''); ?>  <?php endif; ?>">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="_code" class="col-sm-2 col-form-label"><?php echo e(__('label._license_no')); ?>:</label>
                    <div class="col-sm-10">
                      <input type="text" id="_license_no" name="_license_no" class="form-control" placeholder="<?php echo e(__('label._license_no')); ?>" value="<?php if(isset($request->_license_no)): ?> <?php echo e($request->_license_no ?? ''); ?>  <?php endif; ?>">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="_country_name" class="col-sm-2 col-form-label"><?php echo e(__('label._country_name')); ?>:</label>
                    <div class="col-sm-10">
                      <input type="text" id="_country_name" name="_country_name" class="form-control" placeholder="<?php echo e(__('label._country_name')); ?>" value="<?php if(isset($request->_country_name)): ?> <?php echo e($request->_country_name ?? ''); ?>  <?php endif; ?>">
                    </div>
                  </div>
                  
                  <div class="form-group row">
                    <label for="_country_name" class="col-sm-2 col-form-label"><?php echo e(__('label._type')); ?>:</label>
                    <div class="col-sm-10">
                        <select class="form-control" name="_type">
                          <option value=""><?php echo e(__('label.select')); ?></option>
                            <?php $__empty_1 = true; $__currentLoopData = _vessel_types(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <option value="<?php echo e($key); ?>" <?php if( isset($request->_type) && $request->_type==$key): ?> selected <?php endif; ?>><?php echo e($val); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <?php endif; ?>
                        </select>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="_route" class="col-sm-2 col-form-label"><?php echo e(__('label._route')); ?>:</label>
                    <div class="col-sm-10">
                      <input type="text" id="_route" name="_route" class="form-control" placeholder="<?php echo e(__('label._route')); ?>" value="<?php if(isset($request->_route)): ?> <?php echo e($request->_route ?? ''); ?>  <?php endif; ?>">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="_owner_name" class="col-sm-2 col-form-label"><?php echo e(__('label._owner_name')); ?>:</label>
                    <div class="col-sm-10">
                      <input type="text" id="_owner_name" name="_owner_name" class="form-control" placeholder="<?php echo e(__('label._owner_name')); ?>" value="<?php if(isset($request->_owner_name)): ?> <?php echo e($request->_owner_name ?? ''); ?>  <?php endif; ?>">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="_contact_one" class="col-sm-2 col-form-label"><?php echo e(__('label._contact_one')); ?>:</label>
                    <div class="col-sm-10">
                      <input type="text" id="_contact_one" name="_contact_one" class="form-control" placeholder="<?php echo e(__('label._contact_one')); ?>" value="<?php if(isset($request->_contact_one)): ?> <?php echo e($request->_contact_one ?? ''); ?>  <?php endif; ?>">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="_contact_two" class="col-sm-2 col-form-label"><?php echo e(__('label._contact_two')); ?>:</label>
                    <div class="col-sm-10">
                      <input type="text" id="_contact_two" name="_contact_two" class="form-control" placeholder="<?php echo e(__('label._contact_two')); ?>" value="<?php if(isset($request->_contact_two)): ?> <?php echo e($request->_contact_two ?? ''); ?>  <?php endif; ?>">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="_contact_three" class="col-sm-2 col-form-label"><?php echo e(__('label._contact_three')); ?>:</label>
                    <div class="col-sm-10">
                      <input type="text" id="_contact_three" name="_contact_three" class="form-control" placeholder="<?php echo e(__('label._contact_three')); ?>" value="<?php if(isset($request->_contact_three)): ?> <?php echo e($request->_contact_three ?? ''); ?>  <?php endif; ?>">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="_capacity" class="col-sm-2 col-form-label"><?php echo e(__('label._capacity')); ?>:</label>
                    <div class="col-sm-10">
                      <input type="text" id="_capacity" name="_capacity" class="form-control" placeholder="<?php echo e(__('label._capacity')); ?>" value="<?php if(isset($request->_capacity)): ?> <?php echo e($request->_capacity ?? ''); ?>  <?php endif; ?>">
                    </div>
                  </div>


                
                    <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label"><?php echo e(__('label.order_by')); ?>:</label>
                    <div class="col-sm-10">
                      <?php
                      $cloumns = [ 'id'=>__('label.id'),'_name'=>__('label._name'),'_code'=>__('label._code')];

                      ?>
                       <select class="form-control" name="asc_cloumn" >
                            
                            <?php $__currentLoopData = $cloumns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($key); ?>" <?php if(isset($request->asc_cloumn)): ?> <?php if($key==$request->asc_cloumn): ?> selected <?php endif; ?> <?php endif; ?> ><?php echo e($val); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label">Sort :</label>
                    <div class="col-sm-10">
                       <select class=" form-control" name="_asc_desc">
                        <?php $__currentLoopData = asc_desc(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($val); ?>" <?php if(isset($request->_asc_desc)): ?> <?php if($val==$request->_asc_desc): ?> selected <?php endif; ?> <?php endif; ?> ><?php echo e($val); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                  </div>   
                </div>
                <div class="modal-footer justify-content-between">
                  <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>

                  <button type="submit" class="btn btn-primary"><i class="fa fa-search mr-2"></i> Search</button>
                </div>
              </div>
            </form>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>
                  <form action="" method="GET">
                    <?php echo csrf_field(); ?>

                        <div class="row">
                          <div class="col-md-4">
                            <div class="form-group">
                                
                                <select name="limit" class="form-control" onchange="this.form.submit()">
                                        <?php $__empty_1 = true; $__currentLoopData = $row_numbers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                         <option <?php if(isset($request->limit)): ?> <?php if($request->limit == $row): ?> selected <?php endif; ?>  <?php endif; ?> value="<?php echo e($row); ?>"><?php echo e($row); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <?php endif; ?>
                                </select>
                              </div>
                          </div>
                          <div class="col-md-8">
                              <div class="form-group mt-1">
                                
                                    <button type="button" class="btn btn-sm btn-warning mr-3" data-toggle="modal" data-target="#modal-default" title="Advance Search"><i class="fa fa-search mr-2"></i> </button>
                                     <a href="<?php echo e(url('vessel-info')); ?>" class="btn btn-sm btn-danger" title="Search Reset"><i class="fa fa-retweet mr-2"></i> </a>
                              </div>
                          </div>
                        </div><!-- end row -->
                   
                  </form>
                </div><?php /**PATH D:\xampp\htdocs\own\inv-acc-hrm\resources\views/backend/mother-vessel-info/search.blade.php ENDPATH**/ ?>