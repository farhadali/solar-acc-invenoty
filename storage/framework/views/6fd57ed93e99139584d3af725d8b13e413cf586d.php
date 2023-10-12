
<?php $__env->startSection('title',$page_name); ?>

<?php $__env->startSection('content'); ?>

  <div class="content mt-5">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
          <div class="card">
            <div class="card-header">
                 <h4 class="text-center"><?php echo e($page_name ?? ''); ?></h4>
            </div>
           <?php echo $__env->make('backend.message.message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
         
            <div class="card-body" style="width: 350px;margin:0px auto;margin-bottom: 20px;">
               <form  action="<?php echo e(url('all-lock')); ?>" method="post">
                <?php echo csrf_field(); ?>
                    <div class="row">
                      <label>Start Date:<span class="_required">*</span></label>
                        <div class="input-group date" id="reservationdate" data-target-input="nearest">
                                      <input type="text" name="_datex" class="form-control datetimepicker-input" data-target="#reservationdate" required <?php if(isset($previous_filter["_datex"])): ?> value='<?php echo e($previous_filter["_datex"]); ?>' <?php endif; ?> />
                                      <div class="input-group-append" data-target="#reservationdate" data-toggle="datetimepicker">
                                          <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                      </div>
                                  </div>
                         <?php if(isset($previous_filter["_datex"])): ?>
                              <input type="hidden" name="_old_filter" class="_old_filter" value="1">
                              <?php else: ?>
                              <input type="hidden" name="_old_filter" class="_old_filter" value="0">
                              <?php endif; ?>
                    </div>
                    <div class="row">
                      <label>End Date:<span class="_required">*</span></label>
                        <div class="input-group date" id="reservationdate_2" data-target-input="nearest">
                                      <input type="text" name="_datey" class="form-control datetimepicker-input_2" data-target="#reservationdate_2" required <?php if(isset($previous_filter["_datey"])): ?> value='<?php echo e($previous_filter["_datey"]); ?>' <?php endif; ?> />
                                      <div class="input-group-append" data-target="#reservationdate_2" data-toggle="datetimepicker">
                                          <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                      </div>
                                  </div>
                    </div>
                    <div class="">
                    <div class="row  ">
                      <label>Branch:<span class="_required">*</span></label><br> </div>
                     <div class="row ">
                         <select id="_branch_id" required class="form-control _branch_id multiple_select" name="_branch_id[]" multiple size='2' >
                          <?php $__empty_1 = true; $__currentLoopData = $permited_branch; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $branch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                          <option value="<?php echo e($branch->id); ?>" <?php if(isset($previous_filter["_branch_id"])): ?> 
                               <?php if(in_array($branch->id,$previous_filter["_branch_id"])): ?> selected <?php endif; ?>
                               <?php endif; ?> ><?php echo e($branch->id ?? ''); ?> - <?php echo e($branch->_name ?? ''); ?></option>
                          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                          <?php endif; ?>
                         </select>
                     </div>
                   </div>
                    <div class="">
                    <div class="row  ">
                      <label>Cost Center:<span class="_required">*</span></label><br> </div>
                     <div class="row ">
                         <select class="form-control width_150_px _cost_center multiple_select" required multiple name="_cost_center[]" size='2'  >
                                            
                            <?php $__empty_1 = true; $__currentLoopData = $permited_costcenters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $costcenter): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <option value="<?php echo e($costcenter->id); ?>" 
                              <?php if(isset($previous_filter["_cost_center"])): ?>
                              <?php if(in_array($costcenter->id,$previous_filter["_cost_center"])): ?> selected <?php endif; ?>
                                 <?php endif; ?> > <?php echo e($costcenter->_name ?? ''); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <?php endif; ?>
                          </select>
                     </div>
                  </div>
                    <div class="row">
                      <label>Transection Type:<span class="_required">*</span></label><br>
                    </div>
                     <div class="row">
                         <select class="form-control" name="_table_name" required>
                           <option value="">Select</option>
                           <?php $__currentLoopData = $tables; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                              <option value="<?php echo e($key); ?>" <?php if(isset($previous_filter["_table_name"])): ?>
                              <?php if($key==$previous_filter["_table_name"]): ?> selected <?php endif; ?>
                                 <?php endif; ?> ><?php echo e($val); ?></option>
                           <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                         </select>
                      </div>
                    <div class="row">
                      <label>Action Type:<span class="_required">*</span></label><br>
                    </div>
                     <div class="row">
                         <select class="form-control" name="_action" required>
                           <option value="1" <?php if(isset($previous_filter["_action"])): ?>
                              <?php if(1==$previous_filter["_action"]): ?> selected <?php endif; ?>
                                 <?php endif; ?> >Lock</option>
                           <option value="0" <?php if(isset($previous_filter["_action"])): ?>
                              <?php if(0==$previous_filter["_action"]): ?> selected <?php endif; ?>
                                 <?php endif; ?>>Unlock </option>
                           
                         </select>
                      </div>
                     
                     <div class="row mt-5">
                         <div class="col-xs-6 col-sm-6 col-md-6 ">
                            <button type="submit" class="btn btn-success submit-button form-control"><i class="fa fa-credit-card mr-2" aria-hidden="true"></i> Submit</button>
                        </div>
                         <div class="col-xs-6 col-sm-6 col-md-6 ">
                                     <a href="<?php echo e(url('lock-reset')); ?>" class="btn btn-danger form-control" title="Search Reset"><i class="fa fa-retweet mr-2"></i> </a>
                        </div>
                        <br><br>
                     </div>
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

    $(function () {

     var default_date_formate = `<?php echo e(default_date_formate()); ?>`
    
     $('#reservationdate').datetimepicker({
        format:default_date_formate
    });

     $('#reservationdate_2').datetimepicker({
        format:default_date_formate
    });
     
     var _old_filter = $(document).find("._old_filter").val();
     if(_old_filter==0){
        $(".datetimepicker-input").val(date__today())
        $(".datetimepicker-input_2").val(date__today())
     }


     function date__today(){
              var d = new Date();
            var yyyy = d.getFullYear().toString();
            var mm = (d.getMonth()+1).toString(); // getMonth() is zero-based
            var dd  = d.getDate().toString();
            if(default_date_formate=='DD-MM-YYYY'){
              return (dd[1]?dd:"0"+dd[0]) +"-"+ (mm[1]?mm:"0"+mm[0])+"-"+ yyyy ;
            }
            if(default_date_formate=='MM-DD-YYYY'){
              return (mm[1]?mm:"0"+mm[0])+"-" + (dd[1]?dd:"0"+dd[0]) +"-"+  yyyy ;
            }
            
          }
     

  })

   

</script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\own\inv-acc-hrm\resources\views/backend/settings/all-lock.blade.php ENDPATH**/ ?>