
<?php $__env->startSection('title',$page_name); ?>

<?php $__env->startSection('content'); ?>
<style type="text/css">
  ._sms_filter_body{
    width: 90%;
    margin: 0px auto;
    margin-bottom: 20px;
  }
</style>
  <div class="content ">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
          <div class="card">
            <div class="card-header">
                 <h4 class="text-center"><?php echo e($page_name ?? ''); ?></h4>
                <?php if(count($errors) > 0): ?>
           <div class="alert alert-danger">
                <strong>Whoops!</strong> There were some problems with your input.<br><br>
                <ul>
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>
            </div>
          
         
            <div class="card-body _sms_filter_body" >
               <form  action="<?php echo e(url('bulk-sms-send')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                    
                  <div class="row">
                    <div class="col-md-6">
                      <label>Ledger Group:<span class="_required">*</span></label><br>
                      <select id="_account_group_id" class="form-control _account_group_id multiple_select" name="_account_group_id[]" multiple  size='8'  required>
                           <?php $__empty_1 = true; $__currentLoopData = $account_groups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                           <option value="<?php echo e($group->id); ?>"
            <?php if(isset($previous_filter["_account_group_id"])): ?>
                  <?php if(in_array($group->id,$previous_filter["_account_group_id"])): ?> selected <?php endif; ?>
            <?php endif; ?>
                             ><?php echo e($group->_name); ?></option>
                           <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                           <?php endif; ?>
                         </select>

                    </div>
                    <div class="col-md-6">
                       <label>Ledger:<span class="_required">*</span></label><br>
                       <select id="_account_ledger_id" class="form-control  _account_ledger_id multiple_select" name="_account_ledger_id[]" multiple size='8'>
                          <?php if(isset($request->_account_ledger_id)  ): ?>
                           <?php $__empty_1 = true; $__currentLoopData = $account_groups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                           <option value="<?php echo e($group->id); ?>"><?php echo e($group->_name); ?></option>
                           <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                           <?php endif; ?>
                          <?php endif; ?>
                         </select>
                    </div>
                  </div>
                    
                     <div class="row">
                      <label>Message:<span class="_required">*</span></label><br></div>
                     <div class="row">
                       <textarea class="form-control" name="_message" required></textarea>
                     </div>
                     <br>
                     
                     <div class="row mt-3 text-center">
                         
                            <button type="submit" class="btn btn-success submit-button form-control"><i class="fa fa-credit-card mr-2" aria-hidden="true"></i> SEND SMS</button>
                       
                        
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


 
  

    var _account_group_ids = $(document).find('._account_group_id').val();
    if(_account_group_ids.length > 0){
      _nv_group_base_ledger(_account_group_ids);
    }

  $(document).find('#_account_group_id').on('change',function(){
    var _account_group_id = $(this).val();
    
      _nv_group_base_ledger(_account_group_id);
    
  })

  function _nv_group_base_ledger(_account_group_id){
    var request = $.ajax({
        url: "<?php echo e(url('group-base-sms-ledger')); ?>",
        method: "GET",
        data: { _account_group_id : _account_group_id },
        dataType: "HTML"
      });
    request.done(function( result ) {
      $("#_account_ledger_id").html(result);
      });
       
      request.fail(function( jqXHR, textStatus ) {
       console.log(textStatus)
      });
  }






  

        

         

</script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\own\inv-acc-hrm\resources\views/backend/bulk_sms/index.blade.php ENDPATH**/ ?>