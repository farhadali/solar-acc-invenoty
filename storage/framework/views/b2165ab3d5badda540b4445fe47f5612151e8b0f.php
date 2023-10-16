
<?php $__env->startSection('title',$page_name); ?>

<?php $__env->startSection('content'); ?>

  <div class="content ">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
          <div class="card">
           <div class="row mb-2">
                  <div class="col-sm-6">
                    <a class="m-0 _page_name" href="<?php echo e(route('rlp.index')); ?>"><?php echo $page_name; ?> </a>
                  </div><!-- /.col -->
                  <div class="col-sm-6">
                    
                  </div><!-- /.col -->
                </div><!-- /.row -->
          <div class="message-area">
    <?php echo $__env->make('backend.message.message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>
         
            <div class="card-body p-4" >
                <?php echo Form::open(array('route' => 'rlp.store','method'=>'POST')); ?>

                
            <div class="row" >
            <div class="col-xs-12 col-sm-12 col-md-2">
              <input type="hidden" name="_form_name" class="_form_name" value="rlp_create">
                  <div class="form-group">
                      <label>Date:</label>
                        <div class="input-group date" id="reservationdate" data-target-input="nearest">
                            <input type="text" name="request_date" class="form-control datetimepicker-input" data-target="#reservationdate"/>
                            <div class="input-group-append" data-target="#reservationdate" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                            </div>
                        </div>
                    </div>
              </div>
            
            <div class="col-xs-12 col-sm-12 col-md-2">
              <div class="form-group ">
                <label>Priority:<span class="_required">*</span></label>
                <select class="form-control priority" name="priority" required >
                  <option></option>
                </select>
              </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-2">
                  <div class="form-group">
                      <label>RLP No:</label>
                        <div class="input-group" id="rlp_no" data-target-input="nearest">
                          <input type="text" name="rlp_no" class="form-control"/>
                        </div>
                    </div>
              </div>

            <div class="col-xs-12 col-sm-12 col-md-2 <?php if(sizeof($permited_organizations)==1): ?> display_none <?php endif; ?>">
              <div class="form-group ">
                  <label><?php echo __('label.organization'); ?>:<span class="_required">*</span></label>
                  <select class="form-control _master_organization_id" name="organization_id" required >

                     <?php $__empty_1 = true; $__currentLoopData = $permited_organizations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                     <option value="<?php echo e($val->id); ?>" <?php if(isset($data->organization_id)): ?> <?php if($data->organization_id == $val->id): ?> selected <?php endif; ?>   <?php endif; ?>><?php echo e($val->id ?? ''); ?> - <?php echo e($val->_name ?? ''); ?></option>
                     <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                     <?php endif; ?>
                   </select>
               </div>
              </div>
              <div class="col-xs-12 col-sm-12 col-md-2 <?php if(sizeof($permited_branch)==1): ?> display_none <?php endif; ?>">
                  <div class="form-group ">
                      <label>Branch:<span class="_required">*</span></label>
                     <select class="form-control _master_branch_id" name="_branch_id" required >
                        
                        <?php $__empty_1 = true; $__currentLoopData = $permited_branch; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $branch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <option value="<?php echo e($branch->id); ?>" <?php if(isset($data->_branch_id)): ?> <?php if($data->_branch_id == $branch->id): ?> selected <?php endif; ?>   <?php endif; ?>><?php echo e($branch->id ?? ''); ?> - <?php echo e($branch->_name ?? ''); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <?php endif; ?>
                      </select>
                  </div>
              </div>
              <div class="col-xs-12 col-sm-12 col-md-2 <?php if(sizeof($permited_costcenters)==1): ?> display_none <?php endif; ?>">
                  <div class="form-group ">
                      <label><?php echo e(__('label.Cost center')); ?>:<span class="_required">*</span></label>
                     <select class="form-control _cost_center_id" name="_cost_center_id" required >
                        
                        <?php $__empty_1 = true; $__currentLoopData = $permited_costcenters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cost_center): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <option value="<?php echo e($cost_center->id); ?>" <?php if(isset($data->_cost_center_id)): ?> <?php if($data->_cost_center_id == $cost_center->id): ?> selected <?php endif; ?>   <?php endif; ?>><?php echo e($cost_center->id ?? ''); ?> - <?php echo e($cost_center->_name ?? ''); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <?php endif; ?>
                      </select>
                  </div>
              </div>


              <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <label>Remarks:</label>
                        <textarea class="form-control" name="user_remarks"></textarea>
                    </div>
                </div>

                      <!-- <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <label><?php echo e(__('label._status')); ?>:</label>
                                <select class="form-control" name="_status">
                                  <option value="1">Active</option>
                                  <option value="0">In Active</option>
                                </select>
                            </div>
                        </div> -->
                
                        <div class="col-xs-12 col-sm-12 col-md-12  text-middle">
                            <button type="submit" class="btn btn-success  ml-5"><i class="fa fa-credit-card mr-2" aria-hidden="true"></i> <?php echo e(__('label.save')); ?></button>
                           
                        </div>
                        <br><br>
                    
                 
                    
                    
                     
                    <?php echo Form::close(); ?>

                
              </div>
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




    function new_row_holiday(event){

      var _row =`<tr class="_voucher_row">
                      <td>
                        <a  href="#none" class="btn btn-default _voucher_row_remove" ><i class="fa fa-trash"></i></a>
                      </td>
                      <td>
                        <input type="text" name="_name[]" class="form-control  width_280_px" placeholder="<?php echo e(__('label.title')); ?>">
                      </td>
                      <td>
                        <input type="date" name="_date[]" class="form-control width_250_px _date" placeholder="<?php echo e(__('label.date')); ?>">
                      </td>
                      <td>
                        <select class="form-control" name="_type[]">
                          <?php $__empty_1 = true; $__currentLoopData = full_half(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fh): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                          <option value="<?php echo e($fh); ?>"><?php echo $fh ?? ''; ?></option>
                          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                          <?php endif; ?>
                        </select>
                      </td>
                    </tr>`;

      $(document).find('.area__voucher_details').append(_row);

    }

  

  

     

         

</script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\solar-acc-invenoty\resources\views/rlp-module/rlp/create.blade.php ENDPATH**/ ?>