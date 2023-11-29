
<?php $__env->startSection('title',$page_name); ?>
<?php $__env->startSection('css'); ?>
<link rel="stylesheet" href="<?php echo e(asset('backend/new_style.css')); ?>">
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

    <div class="message-area">
     <?php echo $__env->make('backend.message.message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-12">
            <div class="card">

              <div class="card-header">
                <div class="row">
                  <div class="col-md-6">
                     <h1 class="m-0 _page_name"><a  href="<?php echo e(route('voucher.index')); ?>"> <?php echo $page_name ?? ''; ?> </a>  
              <?php echo $__env->make('backend.message.voucher-header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?> 
              </h1>

                  </div>
                  <div class="col-md-6 ">
                    <div class="d-flex right" style="float: right;">
                        
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('voucher-print')): ?>
                           <a class="btn btn-sm btn-default mr-3" href="<?php echo e(route('voucher.show',$data->id)); ?>"><i class="nav-icon fas fa-eye"></i></a>
                         <?php endif; ?>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('voucher-print')): ?>
                           <a target="__blank" title="Print" class="btn btn-sm btn-default mr-3" href="<?php echo e(url('voucher/print')); ?>/<?php echo e($data->id); ?>"> <i class="fa fa-print _required" aria-hidden="true"></i></a>
                         <?php endif; ?>
                         <a href="#" class="mr-2">
                          <button type="button" class="btn btn-sm btn-default" data-toggle="modal" data-target="#exampleModalLong" title="Create Ledger"> New Ledger</button>
                          </a>
                         <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('voucher-list')): ?>
                           <a class="btn  btn-sm btn-default" title="List" href="<?php echo e(route('voucher.index')); ?>">Voucher List</a>
                          <?php endif; ?>

                      </div>
                  </div>
                </div>
              </div>
             
              <div class="card-body">
                 <form action="<?php echo e(url('voucher/update')); ?>" method="POST" class="voucher-form">
                    <?php echo csrf_field(); ?>
                    <div class="row">
                         <input type="hidden" name="_master_id" class="form-control" value="<?php echo e($data->id); ?>" >
                       <div class="col-xs-12 col-sm-12 col-md-2">
                        <input type="hidden" name="_form_name" value="voucher_masters">
                            <div class="form-group">
                                <label>Date:</label>
                                  <div class="input-group date" id="reservationdate" data-target-input="nearest">
                                      <input type="text" name="_date" class="form-control datetimepicker-input" data-target="#reservationdate" value="<?php echo e($data->_date); ?>" />
                                      <div class="input-group-append" data-target="#reservationdate" data-toggle="datetimepicker">
                                          <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                      </div>
                                  </div>
                              </div>
                        </div>

                       <div class="col-xs-12 col-sm-12 col-md-2">
                            <div class="form-group">
                                <label>Voucher Type: <span class="_required">*</span></label>
                               <select class="form-control _voucher_type" name="_voucher_type" required="true">
                                  <option value="">--Voucher Type--</option>
                                  <?php $__empty_1 = true; $__currentLoopData = $voucher_types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $voucher_type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                  <option value="<?php echo e($voucher_type->_code); ?>" <?php if(isset($data->_voucher_type)): ?> <?php if($data->_voucher_type == $voucher_type->_code): ?> selected <?php endif; ?>   <?php endif; ?>>
                                    <?php echo e($voucher_type->_name ?? ''); ?></option>
                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                  <?php endif; ?>
                                </select>
                            </div>
                        </div>

                   <?php echo $__env->make('basic.org_edit', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        <div class="col-xs-12 col-sm-12 col-md-4 ">
                            <div class="form-group">
                              <label class="mr-2" for="_transection_ref">Reference:</label>
                              <input type="text" id="_transection_ref" name="_transection_ref" class="form-control _transection_ref" value="<?php echo e(old('_transection_ref',$data->_transection_ref ?? '')); ?>" placeholder="Reference" >
                                
                            </div>
                        </div>

                       
                          <div class="col-md-12  ">
                             <div class="card">
                              <div class="card-header">
                                <strong>Details</strong>
                              </div>
                              <div class="card-body">
                                <div class="table-responsive">
                                      <table class="table table-bordered" >
                                          <thead>
                                            <th>&nbsp;</th>
                                            <th>ID</th>
                                            <th>Ledger</th>
                                            <th class="<?php if(sizeof($permited_branch)==1): ?> display_none <?php endif; ?>">Branch</th>
                                            <th class="<?php if(sizeof($permited_costcenters)==1): ?> display_none <?php endif; ?>">Cost Center</th>
                                            <th>Short Narr.</th>
                                            <th>Dr. Amount</th>
                                            <th>Cr. Amount</th>
                                          </thead>
                                          <tbody class="area__voucher_details" id="area__voucher_details">
                                  <?php
                                    $_dr_amount = 0;
                                    $_cr_amount = 0;
                                  ?>
                                            <?php $__empty_1 = true; $__currentLoopData = $data->_master_details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mas_key=>$details): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                            <tr class="_voucher_row">
                                              <td>
                                                <a  href="#none" class="btn btn-default _voucher_row_remove" ><i class="fa fa-trash"></i></a>
                                              </td>
                                              <td>
                                                <input type="hidden" name="_detail_id[]" value=" <?php echo e($details->id ?? ''); ?> ">
                                                     <?php echo e($details->id ?? ''); ?>                                      
                                              </td>
                                              <td>
                                                <input type="text" name="_search_ledger_id[]" class="form-control _search_ledger_id width_280_px" placeholder="Ledger" value="<?php echo e($details->_voucher_ledger->_name ?? ''); ?>">
                                                <input type="hidden" name="_ledger_id[]" class="form-control _ledger_id"  value="<?php echo e($details->_ledger_id); ?>" >
                                                <div class="search_box">
                                                  
                                                </div>
                                              </td>
                                              <td class="<?php if(sizeof($permited_branch)==1): ?> display_none <?php endif; ?>">
                                                <select class="form-control width_150_px _branch_id_detail" name="_branch_id_detail[]"  required>
                                                  <?php $__empty_2 = true; $__currentLoopData = $permited_branch; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $branch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                                                  <option value="<?php echo e($branch->id); ?>" <?php if(isset($details->_branch_id)): ?> <?php if($details->_branch_id == $branch->id): ?> selected <?php endif; ?>   <?php endif; ?>><?php echo e($branch->_name ?? ''); ?></option>
                                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                                                  <?php endif; ?>
                                                </select>
                                              </td>
                                             
                                                <td class="<?php if(sizeof($permited_costcenters)==1): ?> display_none <?php endif; ?>">
                                                 <select class="form-control width_150_px _cost_center" name="_cost_center[]" required >
                                            
                                                  <?php $__empty_2 = true; $__currentLoopData = $permited_costcenters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $costcenter): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                                                  <option value="<?php echo e($costcenter->id); ?>" <?php if(isset($details->_cost_center)): ?> <?php if($details->_cost_center == $costcenter->id): ?> selected <?php endif; ?>   <?php endif; ?>> <?php echo e($costcenter->_name ?? ''); ?></option>
                                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                                                  <?php endif; ?>
                                                </select>
                                              </td>
                                              
                                              <td>
                                                <input type="text" name="_short_narr[]" class="form-control width_250_px _short_narr" placeholder="Short Narr" value="<?php echo e($details->_short_narr ?? ''); ?>">
                                              </td>
                                              <td>
                                                <input type="number" name="_dr_amount[]" class="form-control  _dr_amount" placeholder="Dr. Amount" value="<?php echo e(old('_dr_amount',$details->_dr_amount)); ?>">
                                              </td>
                                              <td>
                                                <input type="number" name="_cr_amount[]" class="form-control  _cr_amount" placeholder="Cr. Amount" value="<?php echo e(old('_cr_amount',$details->_cr_amount)); ?>">

                                    <?php 
                                    $_dr_amount += $details->_dr_amount;   
                                    $_cr_amount += $details->_cr_amount;  
                                    ?>
                                              </td>
                                            </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                            <?php endif; ?>
                                          </tbody>
                                          <tfoot>
                                            <tr>
                                              <td>
                                                <a href="#none"  class="btn btn-info" onclick="voucher_row_add(event)"><i class="fa fa-plus"></i></a>
                                              </td>
                                              <td colspan="3" class="text-right"><b>Total</b></td>
                                              <td class="<?php if(sizeof($permited_branch)==1): ?> display_none <?php endif; ?>"></td>
                                              <td class="<?php if(sizeof($permited_costcenters)==1): ?> display_none <?php endif; ?>"></td>
                                              <td>
                                                <input type="number" step="any" min="0" name="_total_dr_amount" class="form-control _total_dr_amount" value="<?php echo e(round($data->_amount ?? 0)); ?>" readonly required>
                                              </td>
                                              <td>
                                                <input type="number" step="any" min="0" name="_total_cr_amount" class="form-control _total_cr_amount" value="<?php echo e(round($data->_amount ?? 0)); ?>" readonly required>
                                              </td>
                                            </tr>
                                          </tfoot>
                                      </table>
                                </div>
                            </div>
                          </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12 mb-10">
                            <div class="form-group">
                               
                                
                                <div class="row">
                                  <div class="col-md-1">
                                     <label class="mr-2" for="_note">Note:<span class="_required">*</span></label>
                                  </div>
                                  <div class="col-md-11">
                                    <input type="text" id="_note"  name="_note" class="form-control _note" value="<?php echo e(old('_note',$data->_note)); ?>" placeholder="Note" required >
                                  </div>
                                </div>
                            </div>
                            <?php echo $__env->make('backend.message.send_sms', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        </div>

                        
                        
                       
                       
                       <div class="col-xs-12 col-sm-12 col-md-12 bottom_save_section text-middle">
                            <button type="submit" class="btn btn-success submit-button ml-5"><i class="fa fa-credit-card mr-2" aria-hidden="true"></i> Save</button>
                            <button type="submit" class="btn btn-warning submit-button _save_and_print"><i class="fa fa-print mr-2" aria-hidden="true"></i> Save & Print</button>
                        </div>
                        <br><br>
                    </div>
                    <?php echo Form::close(); ?>

                
              </div>
            </div>
            <!-- /.card -->

            
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </div>
</div>



<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>

<script type="text/javascript">
  var default_date_formate = `<?php echo e(default_date_formate()); ?>`

 var single_row =  `<tr class="_voucher_row">
                      <td><a  href="" class="btn btn-default _voucher_row_remove" ><i class="fa fa-trash"></i></a></td>
                       <td><input type="hidden" name="_detail_id[]" value="0">&nbsp;&nbsp;</td>
                      <td><input type="text" name="_search_ledger_id[]" class="form-control _search_ledger_id width_280_px" placeholder="Ledger">
                      <input type="hidden" name="_ledger_id[]" class="form-control _ledger_id" >
                      <div class="search_box">
                      </div>
                      </td>
                     
                      <td class="<?php if(sizeof($permited_branch)==1): ?> display_none <?php endif; ?>">
                      <select class="form-control width_150_px _branch_id_detail" name="_branch_id_detail[]"  required >
                        <?php $__empty_1 = true; $__currentLoopData = $permited_branch; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $branch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <option value="<?php echo e($branch->id); ?>" <?php if(isset($request->_branch_id)): ?> <?php if($request->_branch_id == $branch->id): ?> selected <?php endif; ?>   <?php endif; ?>><?php echo e($branch->_name ?? ''); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <?php endif; ?>
                        </select>
                        </td>
                        <td class="<?php if(sizeof($permited_costcenters)==1): ?> display_none <?php endif; ?>">
                          <select class="form-control width_150_px _cost_center" name="_cost_center[]" required >
                            <?php $__empty_1 = true; $__currentLoopData = $permited_costcenters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $costcenter): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                              <option value="<?php echo e($costcenter->id); ?>" <?php if(isset($request->_cost_center)): ?> <?php if($request->_cost_center == $costcenter->id): ?> selected <?php endif; ?>   <?php endif; ?>> <?php echo e($costcenter->_name ?? ''); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <?php endif; ?>
                            </select>
                            </td>
                            <td><input type="text" name="_short_narr[]" class="form-control width_250_px" placeholder="Short Narr"></td>
                            <td>
                              <input type="number" name="_dr_amount[]" class="form-control  _dr_amount" placeholder="Dr. Amount" value="<?php echo e(old('_dr_amount',0)); ?>">
                            </td>
                            <td>
                              <input type="number" name="_cr_amount[]" class="form-control  _cr_amount" placeholder="Cr. Amount" value="<?php echo e(old('_cr_amount',0)); ?>">
                              </td>
                            </tr>`;

  function voucher_row_add(event) {
      event.preventDefault();
      $("#area__voucher_details").append(single_row);
  }

  

  $(document).on('click','._voucher_row_remove',function(event){
      event.preventDefault();
      var ledger_id = $(this).parent().parent('tr').find('._ledger_id').val();
      if(ledger_id ==""){
          $(this).parent().parent('tr').remove();
      }else{
        if(confirm('Are you sure your want to delete?')){
          $(this).parent().parent('tr').remove();
        } 
      }
      _voucher_total_calculation();
  })

  // function _voucher_total_calculation(){
  //   var _total_dr_amount = 0;
  //   var _total_cr_amount = 0;
  //     $(document).find("._cr_amount").each(function() {
  //         _total_cr_amount +=parseFloat($(this).val());
  //     });
  //     $(document).find("._dr_amount").each(function() {
  //         _total_dr_amount +=parseFloat($(this).val());
  //     });
  //     $("._total_dr_amount").val(Math.round(_total_dr_amount));
  //     $("._total_cr_amount").val(Math.round(_total_cr_amount));
  // }


  $(document).on('keyup','._dr_amount',function(){
    $(this).parent().parent('tr').find('._cr_amount').val(0);
    $(document).find("._total_dr_amount").removeClass('required_border');
    $(document).find("._total_cr_amount").removeClass('required_border');
    _voucher_total_calculation();
  })



  $(document).on('keyup','._cr_amount',function(){
     $(this).parent().parent('tr').find('._dr_amount').val(0);
     $(document).find("._total_dr_amount").removeClass('required_border');
      $(document).find("._total_cr_amount").removeClass('required_border');
    _voucher_total_calculation();
  })

  $(document).on('change','._voucher_type',function(){
    $(document).find('._voucher_type').removeClass('required_border');
  })

  $(document).on('keyup','._note',function(){
    $(document).find('._note').removeClass('required_border');
  })


  $(document).on('click','.submit-button',function(event){
    event.preventDefault();
    var _total_dr_amount = $(document).find("._total_dr_amount").val();
    var _total_cr_amount = $(document).find("._total_cr_amount").val();
    var _voucher_type = $(document).find('._voucher_type').val();
    var _note = $(document).find('._note').val();
    var _search_ledger_id = $(document).find('._search_ledger_id').val();


    var empty_ledger = [];
    $(document).find("._ledger_id").each(function(){
        if($(this).val() ==""){
          alert(" Please Add Ledger  ");
          $(document).find('._search_ledger_id').focus().addClass('required_border');
          empty_ledger.push(1);
        }  
    })
 
    if(empty_ledger.length > 0){
      console.log('ok')
      return false;
    }


    if(_total_dr_amount !=_total_cr_amount){
      $(document).find("._total_dr_amount").focus().addClass('required_border');
      $(document).find("._total_cr_amount").focus().addClass('required_border');

      return false;

    }else if(_voucher_type ==""){
       $(document).find('._voucher_type').focus().addClass('required_border');
      return false;
    }else if(_note ==""){
       
       $(document).find('._note').focus().addClass('required_border');
      return false;
    }else if(_search_ledger_id ==""){
       
      $(document).find('._search_ledger_id').focus().addClass('required_border');
      return false;
    }else{
      $('.submit-button').attr('disabled','true');
      $(document).find('.voucher-form').submit();
    }
  })





$(".datetimepicker-input").val(date__today( `<?php echo e($data->_date); ?>` ))

function date__today(_date){
            var data = _date.split('-');
            var yyyy =data[0];
            var mm =data[1];
            var dd =data[2];
            if(default_date_formate=='DD-MM-YYYY'){
              return (dd[1]?dd:"0"+dd[0]) +"-"+ (mm[1]?mm:"0"+mm[0])+"-"+ yyyy ;
            }
            if(default_date_formate=='MM-DD-YYYY'){
              return (mm[1]?mm:"0"+mm[0])+"-" + (dd[1]?dd:"0"+dd[0]) +"-"+  yyyy ;
            }
            

            
          }

</script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\own\inv-acc-hrm\resources\views/backend/voucher/edit.blade.php ENDPATH**/ ?>