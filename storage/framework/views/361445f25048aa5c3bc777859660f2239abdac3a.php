
<?php $__env->startSection('title',$page_name ?? ''); ?>
<?php $__env->startSection('content'); ?>
<div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <a class="m-0 _page_name" href="<?php echo e(route('initial-salary-structure.index')); ?>"><?php echo $page_name ?? ''; ?> </a>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
             <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('initial-salary-structure-list')): ?>
              <li class="breadcrumb-item active">
                 <a class="btn btn-info" href="<?php echo e(route('initial-salary-structure.index')); ?>"> <i class="fa fa-th-list" aria-hidden="true"></i></a>
               </li>
               <?php endif; ?>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    
    <div class="content">
      <div class="container-fluid">
<div class="card ">
<div class="card-body">
                 <?php echo $__env->make('backend.message.message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <?php echo Form::model($data, ['method' => 'PATCH','route' => ['initial-salary-structure.update', $data->id]]); ?>

                <div class="form-group row pt-2">
                            <label class="col-sm-2 col-form-label" ><?php echo e(__('EMP ID')); ?>:</label>
                             <div class="col-sm-6">
                                <input type="hidden" name="_employee_id" class="_employee_id" value="<?php echo e($data->_employee_id); ?>">
                                <input type="hidden" name="_employee_ledger_id" class="_employee_ledger_id" value="<?php echo e($data->_employee_ledger_id); ?>">
                                <input type="text" name="_employee_id_text" class="form-control _employee_id_text" placeholder="<?php echo e(__('EMP ID')); ?>" value="<?php echo e($data->_employee->_code ?? ''); ?>">
                               <div class="search_box_employee"> </div>
                            </div>
                        </div>
                <div class="form-group row pt-2">
                            <label class="col-sm-2 col-form-label" ><?php echo e(__('Employee Name')); ?>:</label>
                             <div class="col-sm-6">
                                <input type="text" name="_employee_name_text" class="form-control _employee_name_text" placeholder="<?php echo e(__('Employee')); ?>" value="<?php echo e($data->_employee->_name ?? ''); ?>">
                               <div class="search_box_employee"> </div>
                            </div>
                        </div>
                <div class="form-group row pt-2">
                    <label class="col-sm-2 col-form-label" ><?php echo e(__('Department')); ?>:</label>
                     <div class="col-sm-6">
                        <input type="text" name="_department" class="form-control _department" placeholder="<?php echo e(__('Department')); ?>" readonly value="<?php echo e($data->_employee->_emp_department->_name ?? ''); ?>">
                    </div>
                </div>
                <div class="form-group row pt-2">
                    <label class="col-sm-2 col-form-label" ><?php echo e(__('Designation')); ?>:</label>
                     <div class="col-sm-6">
                        <input type="text" name="_emp_designation" class="form-control _emp_designation" placeholder="<?php echo e(__('Designation')); ?>" value="<?php echo e($data->_employee->_emp_designation->_name ?? ''); ?>" readonly>
                    </div>
                </div>
                <div class="form-group row pt-2">
                    <label class="col-sm-2 col-form-label" ><?php echo e(__('Grade')); ?>:</label>
                     <div class="col-sm-6">
                        <input type="text" name="_emp_grade" class="form-control _emp_grade" placeholder="<?php echo e(__('Grade')); ?>"  value="<?php echo e($data->_employee->_emp_grade->_name ?? ''); ?>" readonly>
                    </div>
                </div>
                <div class="form-group row pt-2">
                    <label class="col-sm-2 col-form-label" ><?php echo e(__('Emp Category')); ?>:</label>
                     <div class="col-sm-6">
                        <input type="text" name="_employee_cat" class="form-control _employee_cat" value="<?php echo e($data->_employee_cat->_emp_grade->_name ?? ''); ?>" placeholder="<?php echo e(__('Emp Category')); ?>" readonly>
                    </div>
                </div>

<?php
    $previous_detail = $data->_details ?? [];
?>

                <div class="row">
                    <?php $__empty_1 = true; $__currentLoopData = $payheads; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p_key=>$p_val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="col-md-4 ">
                        <h3><?php echo $p_key ?? ''; ?></h3>
                        <?php if(sizeof($p_val) > 0): ?>
                            <?php $__empty_2 = true; $__currentLoopData = $p_val; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $l_val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                            <?php
                            //dump($l_val);
                            ?>
                            <div class="form-group row ">
                            <label class="col-sm-6 col-form-label" for="_item"><?php echo e($l_val->_ledger ?? ''); ?>:</label>
                             <div class="col-sm-6">
                                <input type="hidden" name="_payhead_id[]" class="_payhead_id" value="<?php echo e($l_val->id); ?>">
                                <input type="hidden" name="_payhead_type_id[]" class="_payhead_type_id" value="<?php echo e($l_val->_type); ?>">
                               
                              <input type="number"  name="_amount[]" class="form-control payhead_amount <?php if(isset($l_val->_payhead_type) && $l_val->_payhead_type->cal_type==1): ?> _add_salary <?php endif; ?>  <?php if($l_val->_payhead_type->cal_type==2): ?> _deduction_salary <?php endif; ?>"
                               <?php $__empty_3 = true; $__currentLoopData = $previous_detail; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p_val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_3 = false; ?>
                                <?php if($p_val->_payhead_id==$l_val->id): ?>
                                value="<?php echo e($p_val->_amount ?? 0); ?>"
                               <?php endif; ?>
                              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_3): ?>
                              <?php endif; ?>

                                placeholder="<?php echo e(__('label._amount')); ?>" >
                              <input type="hidden" name="_detail_row_id[]" class="_detail_row_id" 
                              <?php $__empty_3 = true; $__currentLoopData = $previous_detail; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p_val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_3 = false; ?>
                                <?php if($p_val->_payhead_id==$l_val->id): ?>
                              value="<?php echo e($p_val->id ?? 0); ?>"

                               <?php endif; ?>
                              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_3): ?>
                              <?php endif; ?>

                               >
                              
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                        <?php endif; ?>
                        <?php endif; ?>
                    </div>

                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <?php endif; ?>
                    
                </div>

              <div class="form-group row pt-2">
                        <label class="col-sm-2 col-form-label" >Total Earnings:</label>
                         <div class="col-sm-6">
                            <input type="text" name="total_earnings" class="form-control total_earnings" value="<?php echo e($data->total_earnings ?? 0); ?>"  readonly>
                        </div>
                    </div>
                    <div class="form-group row pt-2">
                        <label class="col-sm-2 col-form-label" >Total Deduction:</label>
                         <div class="col-sm-6">
                            <input type="text" name="total_deduction" class="form-control total_deduction" value="<?php echo e($data->total_deduction ?? 0); ?>"  readonly>
                        </div>
                    </div>
                    <div class="form-group row pt-2">
                        <label class="col-sm-2 col-form-label" >Net Total Salary:</label>
                         <div class="col-sm-6">
                            <input type="text" name="net_total_earning" class="form-control net_total_earning" value="<?php echo e($data->net_total_earning ?? 0); ?>"  readonly>
                        </div>
                    </div>


<div class="col-xs-12 col-sm-12 col-md-12 bottom_save_section text-middle">
                            <button type="submit" class="btn btn-success submit-button ml-5" ><i class="fa fa-credit-card mr-2" aria-hidden="true"></i> Save</button>
                            
                        </div>


</form> <!-- End of form -->
</div><!-- End of Card body -->
</div><!-- End of Card -->
</div><!-- End of Container -->
</div><!-- End of Content -->



<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
<script type="text/javascript">

$(document).on('keyup','.payhead_amount',function(){
    var total_earnings =0;
    var total_deduction=0;
    $(document).find('._deduction_salary').each(function(){
        var deduction = parseFloat(isEmpty($(this).val()));
        total_deduction +=parseFloat(deduction);
    })
    $(document).find('._add_salary').each(function(){
        var earning = parseFloat(isEmpty($(this).val()));
        total_earnings +=parseFloat(earning);
    })
    var net_total_earning = (parseFloat(total_earnings)-parseFloat(total_deduction));

    $(document).find(".total_earnings").val(total_earnings);
    $(document).find(".total_deduction").val(total_deduction);
    $(document).find(".net_total_earning").val(net_total_earning);

})


$(document).on('keyup','._employee_id_text',delay(function(e){
    
  var _gloabal_this = $(this);
  var _text_val = $(this).val().trim();
  var request = $.ajax({
      url: "<?php echo e(url('employee-search')); ?>",
      method: "GET",
      data: { _text_val : _text_val },
      dataType: "JSON"
    });
     
    request.done(function( result ) {
      var search_html =``;
      var data = result.data; 
      if(data.length > 0 ){
            search_html +=`<div class="card"><table style="width: 300px;"> <tbody>`;
                        for (var i = 0; i < data.length; i++) {
                         search_html += `<tr class="_employee_search_row_em _cursor_pointer" >
                                        <td>${data[i]._code}
                                        <input type="hidden" name="_emp_all_data" class="_emp_all_data" attr_value='${JSON.stringify(data[i])}'>
                                        <input type="hidden" name="_emplyee_row_id" class="_emplyee_row_id" value="${data[i].id}">
                                        <input type="hidden" name="_emplyee_row_code_id" class="_emplyee_row_code_id" value="${data[i]._code}">
                                        </td>
                                        <td>${data[i]._name}
                                        <input type="hidden" name="_search_employee_name" class="_search_employee_name" value="${data[i]._name}">
                                        
                                        </td>
                                        
                                       
                                        </tr>`;
                        }                         
            search_html += ` </tbody> </table></div>`;
      }else{
        search_html +=`<div class="card"><table style="width: 300px;"> 
        <thead><th colspan="3">No Data Found</th></thead><tbody></tbody></table></div>`;
      }   

       _gloabal_this.parent('div').find('.search_box_employee').html(search_html);
      _gloabal_this.parent('div').find('.search_box_employee').addClass('search_box_show').show();  
      
      
    });
     
    request.fail(function( jqXHR, textStatus ) {
      alert( "Request failed: " + textStatus );
    });
}, 500));

    


$(document).on('click','._employee_search_row_em',function(){
 var _emp_all_data = $(this).children('td').find('._emp_all_data').attr('attr_value');

var data = JSON.parse(_emp_all_data);
console.log(data)
$(document).find("._employee_name_text").val(data?._name);
$(document).find("._employee_id").val(data?.id);
$(document).find("._employee_id_text").val(data?._code);
$(document).find("._employee_ledger_id").val(data?._ledger_id);
$(document).find("._department").val(data?._emp_department?._name);
$(document).find("._emp_designation").val(data?._emp_designation?._name);
$(document).find("._emp_grade").val(data?._emp_grade?._name);
$(document).find("._employee_cat").val(data?._employee_cat?._name);

  $(document).find('.search_box_employee').hide();
  $(document).find('.search_box_employee').removeClass('search_box_show').hide();
})
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\own\inv-acc-hrm\resources\views/hrm/initial-salary-structure/edit.blade.php ENDPATH**/ ?>