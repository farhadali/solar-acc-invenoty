
<?php $__env->startSection('title',$page_name); ?>

<?php $__env->startSection('content'); ?>

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
          
         
            <div class="card-body filter_body" >
               <form  action="<?php echo e(url('ledger-summary-report')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                    <div class="row">
                    <?php echo $__env->make('basic.org_report', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                      <div class="col-md-6">
                        <label>Branch:</label>
                        <select id="_branch_id" class="form-control _branch_id multiple_select" name="_branch_id[]" multiple size='2' >
                          <?php $__empty_1 = true; $__currentLoopData = $permited_branch; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $branch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                          <option value="<?php echo e($branch->id); ?>" 
                            <?php if(isset($previous_filter["_branch_id"])): ?> 
                               <?php if(in_array($branch->id,$previous_filter["_branch_id"])): ?> selected <?php endif; ?>
                               <?php endif; ?>
                             ><?php echo e($branch->id ?? ''); ?> - <?php echo e($branch->_name ?? ''); ?></option>
                          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                          <?php endif; ?>
                         </select>
                      </div>

                      <div class="col-md-6">
                        <label>Cost Center:</label>
                         <select class="form-control width_150_px _cost_center multiple_select" multiple name="_cost_center[]" size='2'  >
                                            
                            <?php $__empty_1 = true; $__currentLoopData = $permited_costcenters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $costcenter): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <option value="<?php echo e($costcenter->id); ?>" 
                              <?php if(isset($previous_filter["_cost_center"])): ?>
                              <?php if(in_array($costcenter->id,$previous_filter["_cost_center"])): ?> selected <?php endif; ?>
                                 <?php endif; ?>
                              > <?php echo e($costcenter->_name ?? ''); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <?php endif; ?>
                          </select>
                      </div>

                    </div>
                    
                    
                    
                    <div class="row">
                      <label>Ledger Group:</label><br> </div>
                     <div class="row">
                         <select id="_account_group_id" class="form-control _account_group_id multiple_select" name="_account_group_id[]" multiple  size='6'  required>
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
                     <div class="row">
                      <label>Ledger:</label><br></div>
                     <div class="row">
                         <select id="_account_ledger_id" class="form-control  _account_ledger_id multiple_select" name="_account_ledger_id[]" multiple size='6'>
                          <?php if(isset($request->_account_ledger_id)  ): ?>
                           <?php $__empty_1 = true; $__currentLoopData = $account_groups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                           <option value="<?php echo e($group->id); ?>"><?php echo e($group->_name); ?></option>
                           <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                           <?php endif; ?>
                          <?php endif; ?>
                         </select>
                     </div>
                     <br>
                     <div class="row">
                         <select id="_with_zero" class="form-control  _with_zero " name="_with_zero"  >
                           <option value="1" <?php if(isset($previous_filter["_with_zero"])): ?> <?php if($previous_filter["_with_zero"] ==1): ?> selected <?php endif; ?> <?php endif; ?> >Without Zero Value</option>
                           <option value="0" <?php if(isset($previous_filter["_with_zero"])): ?> <?php if($previous_filter["_with_zero"] ==0): ?> selected <?php endif; ?> <?php endif; ?>>With Zero Value</option>
                         
                         </select>
                     </div>
                     <br>
                     <div class="row">
                         <select id="_order_by" class="form-control  _order_by " name="_order_by"  >
                           <option value="desc" <?php if(isset($previous_filter["_order_by"])): ?> <?php if($previous_filter["_order_by"] =='desc'): ?> selected <?php endif; ?> <?php endif; ?> >Amount DESC</option>
                           <option value="asc" <?php if(isset($previous_filter["_order_by"])): ?> <?php if($previous_filter["_order_by"] =='asc'): ?> selected <?php endif; ?> <?php endif; ?>>Amount ASC</option>
                         
                         </select>
                     </div>
                     <br>
                     <div class="row">
                      <label>Account Type:</label><br></div>
                     <div class="row">
                         <select id="_status" class="form-control  _status " name="_status" >
                           <option value=""  >All</option>
                           <option value="1"  <?php if(isset($previous_filter["_status"]) && $previous_filter["_status"]==1 ): ?> selected <?php endif; ?> >Active</option>
                           <option value="0" <?php if(isset($previous_filter["_status"]) && $previous_filter["_status"]==0 ): ?> selected <?php endif; ?> >In Active</option>
                           
                          
                         </select>
                     </div>
                     <br>

                     <div class="row mt-3">
                         <div class="col-xs-6 col-sm-6 col-md-6 ">
                            <button type="submit" class="btn btn-success submit-button form-control"><i class="fa fa-credit-card mr-2" aria-hidden="true"></i> Report</button>
                        </div>
                         <div class="col-xs-6 col-sm-6 col-md-6 ">
                                     <a href="<?php echo e(url('ledger-summary-filter-reset')); ?>" class="btn btn-danger form-control" title="Search Reset"><i class="fa fa-retweet mr-2"></i> </a>
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

    var _account_group_ids = $(document).find('._account_group_id').val();
    if(_account_group_ids?.length > 0){
      _nv_group_base_ledger(_account_group_ids);
    }

  $(document).find('#_account_group_id').on('change',function(){
    var _account_group_id = $(this).val();
    
      _nv_group_base_ledger(_account_group_id);
    
  })

  function _nv_group_base_ledger(_account_group_id){
    var request = $.ajax({
        url: "<?php echo e(url('group-base-ledger')); ?>",
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




$(document).on('keyup','._search_ledger_id_ledger',delay(function(e){
  var _gloabal_this = $(this);

  var _text_val = $(this).val().trim();
  var request = $.ajax({
      url: "<?php echo e(url('ledger-search')); ?>",
      method: "GET",
      data: { _text_val : _text_val },
      dataType: "JSON"
    });
     
    request.done(function( result ) {

      var search_html =``;
      
      var data = result.data; 
      console.log(data)
      if(data.length > 0 ){
        
            search_html +=`<div class="card"><table class="_filter_ledger_search_table" >
                            <tbody>`;
                        for (var i = 0; i < data.length; i++) {
                         search_html += `<tr class="search_row _ledger_search_row" >
                                        <td>${data[i].id}
                                        <input type="hidden" name="_id_ledger" class="_id_ledger" value="${data[i].id}">
                                        </td><td>${data[i]._name}
                                        <input type="hidden" name="_name_leder" class="_name_leder" value="${data[i]._name}">
                                        </td></tr>`;
                        }                         
            search_html += ` </tbody> </table></div>`;
      }else{
        search_html +=`<div class="card"><table style="width: 300px;"> 
        <thead><th colspan="3">No Data Found</th></thead><tbody></tbody></table></div>`;
      }     
      $(document).find('.search_box').html(search_html);
      $(document).find('.search_box').addClass('search_box_show').show();
      
    });
     
    request.fail(function( jqXHR, textStatus ) {
      alert( "Request failed: " + textStatus );
    });

  

}, 500));

  
 $(document).on('click','._ledger_search_row',function(){
  var _id = $(this).children('td').find('._id_ledger').val();
  var _name = $(this).find('._name_leder').val();
  $(document).find('._ledger_id').val(_id);
  var _id_name = `${_name} `;
  $(document).find('._search_ledger_id_ledger').val(_id_name);

  $('.search_box').hide();
  $('.search_box').removeClass('search_box_show').hide();
})

$(document).on('click',function(){
    var searach_show= $('.search_box').hasClass('search_box_show');
    if(searach_show ==true){
      $('.search_box').removeClass('search_box_show').hide();
    }
})

        

         

</script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\own\inv-acc-hrm\resources\views/backend/account-report/filter_ledger_summary.blade.php ENDPATH**/ ?>