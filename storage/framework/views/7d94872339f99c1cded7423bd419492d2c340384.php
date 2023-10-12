
<div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <a class="m-0 _page_name" href="#"><?php echo $page_name ?? ''; ?> </a>
          </div><!-- /.col -->
          <div class="col-sm-6">
           
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
   <div class="message-area">
    <?php echo $__env->make('backend.message.message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-12">
            <div class="card">
             
              <div class="card-body">
               
                  <?php echo Form::model($data, ['method' => 'PATCH','route' => ['weekworkday.update', $data->id]]); ?>

                    <div class="row">
                      
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <label>Saturday:</label>
                                <input type="hidden" name="id" value="<?php echo e($data->id); ?>">
                                <select class="form-control" name="_saturday">
                                  <?php $__empty_1 = true; $__currentLoopData = office_days_cat(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $off_cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                  <option value="<?php echo e($off_cat); ?>" <?php if($off_cat==$data->_saturday): ?> selected <?php endif; ?>><?php echo e($off_cat); ?></option>
                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                  <?php endif; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <label>Sunday:</label>
                                <select class="form-control" name="_sunday">
                                  <?php $__empty_1 = true; $__currentLoopData = office_days_cat(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $off_cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                  <option value="<?php echo e($off_cat); ?>" <?php if($off_cat==$data->_sunday): ?> selected <?php endif; ?>><?php echo e($off_cat); ?></option>
                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                  <?php endif; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <label>Monday:</label>
                                <select class="form-control" name="_monday">
                                  <?php $__empty_1 = true; $__currentLoopData = office_days_cat(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $off_cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                  <option value="<?php echo e($off_cat); ?>" <?php if($off_cat==$data->_monday): ?> selected <?php endif; ?>><?php echo e($off_cat); ?></option>
                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                  <?php endif; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <label>Tuesday:</label>
                                <select class="form-control" name="_tuesday">
                                  <?php $__empty_1 = true; $__currentLoopData = office_days_cat(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $off_cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                  <option value="<?php echo e($off_cat); ?>" <?php if($off_cat==$data->_tuesday): ?> selected <?php endif; ?>><?php echo e($off_cat); ?></option>
                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                  <?php endif; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <label>Wednesday:</label>
                                <select class="form-control" name="_wednesday">
                                  <?php $__empty_1 = true; $__currentLoopData = office_days_cat(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $off_cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                  <option value="<?php echo e($off_cat); ?>" <?php if($off_cat==$data->_wednesday): ?> selected <?php endif; ?>><?php echo e($off_cat); ?></option>
                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                  <?php endif; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <label>Thursday:</label>
                                <select class="form-control" name="_thursday">
                                  <?php $__empty_1 = true; $__currentLoopData = office_days_cat(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $off_cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                  <option value="<?php echo e($off_cat); ?>" <?php if($off_cat==$data->_thursday): ?> selected <?php endif; ?>><?php echo e($off_cat); ?></option>
                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                  <?php endif; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <label>Friday:</label>
                                <select class="form-control" name="_friday">
                                  <?php $__empty_1 = true; $__currentLoopData = office_days_cat(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $off_cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                  <option value="<?php echo e($off_cat); ?>" <?php if($off_cat==$data->_friday): ?> selected <?php endif; ?>><?php echo e($off_cat); ?></option>
                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                  <?php endif; ?>
                                </select>
                            </div>
                        </div>
                        
                       
                       
                        <div class="col-xs-12 col-sm-12 col-md-12 bottom_save_section text-middle">
                            <button type="submit" class="btn btn-success  ml-5"><i class="fa fa-credit-card mr-2" aria-hidden="true"></i> Save</button>
                           
                        </div>
                        <br><br>
                    </div>
                    </form>
                
              </div>
            </div>
            <!-- /.card -->

            
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </div>
</div>

<?php /**PATH D:\xampp\htdocs\own\sabuz-bhai\sspf.sobuzsathitraders.com\sspf.sobuzsathitraders.com\resources\views/hrm/week-work-day/edit.blade.php ENDPATH**/ ?>