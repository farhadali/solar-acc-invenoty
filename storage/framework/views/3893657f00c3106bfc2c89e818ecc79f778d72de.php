<div  >
                  <form action="" method="GET">
                    <?php echo csrf_field(); ?>
                      <?php 
                        $row_numbers = filter_page_numbers();
                         
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
                            <input type="text" name="_name" class="form-control" placeholder="Search By Name" value="<?php if(isset($request->_name)): ?> <?php echo e($request->_name ?? ''); ?>  <?php endif; ?>">
                          </div>
                          
                         
                          
                          <div class="col-md-2">
                              <button class="form-control btn btn-warning" type="submit">Search</button>
                          </div>
                        </div><!-- end row -->
                   
                  </form>
                </div><?php /**PATH /home/sobuz23/sspf.sobuzsathitraders.com/resources/views/backend/item-category/search.blade.php ENDPATH**/ ?>