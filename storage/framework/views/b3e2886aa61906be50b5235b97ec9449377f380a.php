<div  >
                  <form action="" method="GET">
                    <?php echo csrf_field(); ?>
                     <?php 
                        $row_numbers = [10,20,30,40,50,100,200,300,400,500,600,1000,2000,100000,200000,500000];
                        ?>
                        <div class="row">
                          <div class="col-md-2">
                            <select name="limit" class="form-control">
                                    <?php $__empty_1 = true; $__currentLoopData = $row_numbers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                     <option <?php if(isset($request->limit)): ?> <?php if($request->limit == $row): ?> selected <?php endif; ?>  <?php endif; ?> value="<?php echo e($row); ?>"><?php echo e($row); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <?php endif; ?>
                            </select>
                          </div>
                          <div class="col-md-4">
                            <input type="text" name="name" class="form-control" placeholder="Search By Name" value="<?php if(isset($request->name)): ?> <?php echo e($request->name ?? ''); ?>  <?php endif; ?>">
                          </div>
                          <div class="col-md-2">
                            <input type="text" name="email" class="form-control" placeholder="Search By email" value="<?php if(isset($request->email)): ?> <?php echo e($request->email ?? ''); ?>  <?php endif; ?>">
                          </div>
                          <div class="col-md-2">
                            <select class="form-control" name="branch_ids" >
                              <option value="">--Select Branch--</option>
                                  <?php $__empty_1 = true; $__currentLoopData = $branchs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $branch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                  <option value="<?php echo e($branch->id); ?>" <?php if($request->branch_ids==$branch->id): ?> selected <?php endif; ?>><?php echo e($branch->_name ?? ''); ?></option>
                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                  <?php endif; ?>
                                  
                                </select>
                          </div>
                          
                          <div class="col-md-2">
                              <button class="form-control btn btn-warning" type="submit">Search</button>
                          </div>
                        </div><!-- end row -->
                   
                  </form>
                </div><?php /**PATH D:\xampp\htdocs\own\inv-acc-hrm\resources\views/users/search.blade.php ENDPATH**/ ?>