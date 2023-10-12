
<?php $__env->startSection('title',$page_name); ?>
<?php $__env->startSection('css'); ?>
<link rel="stylesheet" href="<?php echo e(asset('backend/new_style.css')); ?>">
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class=" col-sm-8 ">
            <h1 class="m-0 _page_name">
              <a  href="<?php echo e(route('easy-voucher.index')); ?>"> <?php echo $page_name ?? ''; ?> </a>    
              
              <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('easy-voucher-create')): ?>
            <a title="Add New" class="btn  btn-sm btn-info" href="<?php echo e(route('easy-voucher.create')); ?>"> <i class="nav-icon fas fa-plus"></i> <?php echo e(__('label.Add New')); ?> </a>
                <?php endif; ?> 

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('voucher-print')): ?>
                        
                     <a class="btn btn-sm btn-default mr-3" href="<?php echo e(route('voucher.show',$data->id)); ?>">
                           <i class="nav-icon fas fa-eye"></i>
                      </a>
                   
                   <?php endif; ?>
                  <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('voucher-print')): ?>
                 
                     <a target="__blank" title="Print" class="btn btn-sm btn-default mr-3" href="<?php echo e(url('voucher/print')); ?>/<?php echo e($data->id); ?>"> <i class="fa fa-print _required" aria-hidden="true"></i></a>
                  
                   <?php endif; ?>
              </h1>

          </div><!-- /.col -->
           <div class=" col-sm-4 ">
            <ol class="breadcrumb float-sm-right">
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('account-ledger-create')): ?>
             <li class="breadcrumb-item active">
                 <button type="button" class="btn btn-sm btn-default" data-toggle="modal" data-target="#exampleModalLong" title="Create Ledger"> New Ledger</button>
               </li>
               <?php endif; ?>
              <li class="breadcrumb-item active">
                 <a class="btn btn-sm btn-default" title="List" href="<?php echo e(route('easy-voucher.index')); ?>">Easy Voucher List</a>
               </li>
            </ol>
          </div>
          
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
                
                <?php echo Form::model($data, ['method' => 'PATCH','class'=>'voucher-form','route' => ['easy-voucher.update', $data->id]]); ?>

                    <div class="row">

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
<?php
$users = \Auth::user();
$permited_organizations = permited_organization(explode(',',$users->organization_ids));
?> 


<div class="col-xs-12 col-sm-12 col-md-2 ">
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
                        <div class="col-xs-12 col-sm-12 col-md-2 ">
                            <div class="form-group ">
                                <label>Branch:<span class="_required">*</span></label>
                               <select class="form-control" name="_branch_id" required >
                                  
                                  <?php $__empty_1 = true; $__currentLoopData = $permited_branch; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $branch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                  <option value="<?php echo e($branch->id); ?>" <?php if(isset($data->_branch_id)): ?> <?php if($data->_branch_id == $branch->id): ?> selected <?php endif; ?>   <?php endif; ?>><?php echo e($branch->id ?? ''); ?> - <?php echo e($branch->_name ?? ''); ?></option>
                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                  <?php endif; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-2 ">
                            <div class="form-group ">
                                <label>Cost Center:<span class="_required">*</span></label>
                               <select class="form-control" name="_cost_center_id" required >
                                   <?php $__empty_1 = true; $__currentLoopData = $permited_costcenters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $costcenter): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                  <option value="<?php echo e($costcenter->id); ?>" <?php if(isset($data->_cost_center_id)): ?> <?php if($data->_cost_center_id == $costcenter->id): ?> selected <?php endif; ?>   <?php endif; ?>> <?php echo e($costcenter->_name ?? ''); ?></option>
                                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                  <?php endif; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-6 ">
                            <div class="form-group">
                              <label class="mr-2" for="_transection_ref">Reference:</label>
                              <input type="text" id="_transection_ref" name="_transection_ref" class="form-control _transection_ref" value="<?php echo e(old('_transection_ref',$data->_transection_ref)); ?>" placeholder="Reference" >
                                
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
                                             <th class="dr_account_title">DR. Account</th>
                                            <th class="dr_particular_title">DR. Particular</th>
                                            <th class="cr_account_title">CR. Account</th>
                                            <th class="cr_particular_title">CR. Particular</th>
                                            <th>Branch</th>
                                            <th>Cost Center</th>
                                            <th>Amount</th>
                                          </thead>
                                          <tbody class="area__voucher_details" id="area__voucher_details">
                                            <?php $__empty_1 = true; $__currentLoopData = $rearrange_data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$v_data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                            <tr class="_voucher_row">
                                              <?php
                                              $_dr_row_id = "0";
                                              $_dr_search_ledger_id = "0";
                                              $_dr_ledger_id = "0";
                                              $_dr_short_narr = "0";


                                              $_cr_row_id = "0";
                                              $_cr_search_ledger_id = "0";
                                              $_cr_ledger_id = "0";
                                              $_cr_short_narr = "0";

                                              $_branch_id_detail = "0";
                                              $_cost_center = "0";
                                              $_amount = "0";

                                              ?>
                                              <?php $__empty_2 = true; $__currentLoopData = $v_data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $da_key=>$da_val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                                              <?php
                                              if($da_val->_dr_amount > 0){
                                                $_dr_row_id = $da_val->id;
                                                $_dr_search_ledger_id = $da_val->_voucher_ledger->_name ?? '';
                                                $_dr_ledger_id = $da_val->_ledger_id;
                                                $_dr_short_narr = $da_val->_short_narr;
                                                $_dr_branch_id_detail = $da_val->_branch_id;
                                                $_dr_cost_center = $da_val->_cost_center;
                                                $_amount = $da_val->_dr_amount;
                                            }
                                              
                                            if($da_val->_cr_amount > 0){
                                                $_cr_row_id = $da_val->id;
                                                  $_cr_search_ledger_id = $da_val->_voucher_ledger->_name ?? '';
                                                  $_cr_ledger_id = $da_val->_ledger_id;
                                                  $_cr_short_narr = $da_val->_short_narr;
                                                  $_cr_branch_id_detail = $da_val->_branch_id;
                                                  $_cr_cost_center = $da_val->_cost_center;
                                                  $_amount = $da_val->_cr_amount;
                                            }

                                            $_branch_id_detail = $da_val->_branch_id;
                                            $_cost_center = $da_val->_cost_center;

                                            ?>
                                              
                                          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                                          <?php endif; ?>

                                              <td>
                                                <a  href="#none" class="btn btn-default easy_voucher_row_remove" ><i class="fa fa-trash"></i></a>
                                              </td>
                                              <td>
                                                
                                                <input type="hidden" name="_dr_row_id[]" value="<?php echo e($_dr_row_id); ?>">
                                                <input type="text" name="_dr_search_ledger_id[]" class="form-control _dr_search_ledger_id width_150_px"  value="<?php echo e($_dr_search_ledger_id); ?>">

                                                <input type="hidden" name="_dr_ledger_id[]" class="form-control _dr_ledger_id" value="<?php echo e($_dr_ledger_id); ?>" >
                                                <div class="_dr_search_box">
                                                  
                                                </div>
                                                
                                              </td>
                                              <td>
                                                <input type="text" name="_dr_short_narr[]" class="form-control width_150_px _dr_short_narr"  value="<?php echo e($_dr_short_narr); ?>">
                                              </td>
                                              <td>
                                                 <input type="hidden" name="_cr_row_id[]" value="<?php echo e($_cr_row_id); ?>">

                                                <input type="text" name="_cr_search_ledger_id[]" class="form-control _cr_search_ledger_id width_150_px"  value="<?php echo $_cr_search_ledger_id; ?>">
                                                <input type="hidden" name="_cr_ledger_id[]" class="form-control _cr_ledger_id"  value="<?php echo $_cr_ledger_id; ?>">
                                                <div class="_cr_search_box">
                                                  
                                                </div>
                                              </td>
                                              <td>
                                                <input type="text" name="_cr_short_narr[]" class="form-control width_150_px _cr_short_narr"  value="<?php echo $_cr_short_narr; ?>">
                                              </td>
                                              <td>
                                                <select class="form-control width_150_px _branch_id_detail" name="_branch_id_detail[]"  required>
                                                  <?php $__empty_2 = true; $__currentLoopData = $permited_branch; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $branch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                                                  <option value="<?php echo e($branch->id); ?>" <?php if($_branch_id_detail == $branch->id): ?> selected <?php endif; ?>   ><?php echo e($branch->_name ?? ''); ?></option>
                                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                                                  <?php endif; ?>
                                                </select>
                                              </td>
                                             
                                                <td>
                                                 <select class="form-control width_150_px _cost_center" name="_cost_center[]" required >
                                            
                                                  <?php $__empty_2 = true; $__currentLoopData = $permited_costcenters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $costcenter): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                                                  <option value="<?php echo e($costcenter->id); ?>" <?php if($_cost_center == $costcenter->id): ?> selected <?php endif; ?>  > <?php echo e($costcenter->_name ?? ''); ?></option>
                                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                                                  <?php endif; ?>
                                                </select>
                                              </td>
                                              
                                              
                                              <td>
                                                <input type="number" name="_amount[]" class="form-control  _amount" placeholder="Dr. Amount" value="<?php echo e(old('_amount',$_amount)); ?>" >

                                              </td>
                                             
                                            </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                            <?php endif; ?>
                                          </tbody>
                                          <tfoot>
                                            <tr>
                                              <td>
                                                <a href="#none"  class="btn btn-info" onclick="easy_voucherAddRow(event)"><i class="fa fa-plus"></i></a>
                                              </td>
                                              <td colspan="6" class="text-right"><b>Total</b></td>
                                              <td>
                                                <input type="number" step="any" min="0" name="_total_amount" class="form-control _total_amount" value="<?php echo e($data->_amount ?? 0); ?>" readonly required>
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
                                    <?php if($_print = Session::get('_print_value')): ?>
                                     <input type="hidden" name="_after_print" value="<?php echo e($_print); ?>" class="_after_print" >
                                    <?php else: ?>
                                    <input type="hidden" name="_after_print" value="0" class="_after_print" >
                                    <?php endif; ?>
                                    <?php if($_master_id = Session::get('_master_id')): ?>
                                     <input type="hidden" name="_master_id" value="<?php echo e(url('voucher/print')); ?>/<?php echo e($_master_id); ?>" class="_master_id">
                                    
                                    <?php endif; ?>
                                   
                                       <input type="hidden" name="_print" value="0" class="_save_and_print_value">

                                    <input type="text" id="_note"  name="_note" class="form-control _note" value="<?php echo e(old('_note',$data->_note)); ?>" placeholder="Note" required >
                                  </div>
                                </div>
                                 <?php echo $__env->make('backend.message.send_sms', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                            </div>
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

  $(function(){
    var voucher_type = $("._voucher_type").val();
    title_change_voucher(voucher_type)
  })


  var default_date_formate = `<?php echo e(default_date_formate()); ?>`;
  var _after_print = $(document).find("._after_print").val();
  var _master_id = $(document).find("._master_id").val();
  if(_after_print ==1){
      var open_new = window.open(_master_id, '_blank');
      if (open_new) {
          //Browser has allowed it to be opened
          open_new.focus();
      } else {
          //Browser has blocked it
          alert('Please allow popups for this website');
      }
  }
$(document).on("change","._voucher_type",function(){
    var voucher_type = $(this).val();
    title_change_voucher(voucher_type)
  })



  function title_change_voucher(voucher_type){
      if(voucher_type=="BP"){
          $(".dr_account_title").text('Receiver Account');
          $(".dr_particular_title").text('Receiver Account Note');
          $(".cr_account_title").text('Bank Account');
          $(".cr_particular_title").text('Bank Account Note');
      }else if(voucher_type=="BR"){
          $(".dr_account_title").text('Bank Account');
          $(".dr_particular_title").text('Bank Account Note');
          $(".cr_account_title").text('Payee Account');
          $(".cr_particular_title").text('Payee Account Note');
      }else if(voucher_type=="CR"){
          $(".dr_account_title").text('Cash Account');
          $(".dr_particular_title").text('Cash Account Note');
          $(".cr_account_title").text('Payee Account');
          $(".cr_particular_title").text('Payee Account Note');
      }else if(voucher_type=="CP"){
          $(".dr_account_title").text('Receiver Account');
          $(".dr_particular_title").text('Receiver Account Note');
          $(".cr_account_title").text('Cash Account');
          $(".cr_particular_title").text('Cash Account Note');
      }
      else{
          $(".dr_account_title").text('DR. Account');
          $(".dr_particular_title").text('DR. Particular');
          $(".cr_account_title").text('CR. Account');
          $(".cr_particular_title").text('CR. Particular');
      }
  }

 var single_row =  `<tr class="_voucher_row">
                      <td>
                        <a  href="#none" class="btn btn-default easy_voucher_row_remove" ><i class="fa fa-trash"></i></a>
                      </td>
                      <td>
                        <input type="hidden" name="_dr_row_id[]" value="0">
                        <input type="text" name="_dr_search_ledger_id[]" class="form-control _dr_search_ledger_id width_150_px" >

                        <input type="hidden" name="_dr_ledger_id[]" class="form-control _dr_ledger_id" >
                        <div class="_dr_search_box">
                          
                        </div>
                      </td>
                      <td>
                        <input type="text" name="_dr_short_narr[]" class="form-control width_150_px _dr_short_narr" >
                      </td>
                      <td>
                         <input type="hidden" name="_cr_row_id[]" value="0">
                        <input type="text" name="_cr_search_ledger_id[]" class="form-control _cr_search_ledger_id width_150_px" >
                        <input type="hidden" name="_cr_ledger_id[]" class="form-control _cr_ledger_id" >
                        <div class="_cr_search_box">
                          
                        </div>
                      </td>
                      <td>
                        <input type="text" name="_cr_short_narr[]" class="form-control width_150_px _cr_short_narr" >
                      </td>
                      <td>
                        <select class="form-control width_150_px _branch_id_detail" name="_branch_id_detail[]"  required>
                          <?php $__empty_1 = true; $__currentLoopData = $permited_branch; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $branch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                          <option value="<?php echo e($branch->id); ?>" <?php if(isset($request->_branch_id)): ?> <?php if($request->_branch_id == $branch->id): ?> selected <?php endif; ?>   <?php endif; ?>><?php echo e($branch->_name ?? ''); ?></option>
                          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                          <?php endif; ?>
                        </select>
                      </td>
                     
                        <td>
                         <select class="form-control width_150_px _cost_center" name="_cost_center[]" required >
                          <?php $__empty_1 = true; $__currentLoopData = $permited_costcenters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $costcenter): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                          <option value="<?php echo e($costcenter->id); ?>" <?php if(isset($request->_cost_center)): ?> <?php if($request->_cost_center == $costcenter->id): ?> selected <?php endif; ?>   <?php endif; ?>> <?php echo e($costcenter->_name ?? ''); ?></option>
                          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                          <?php endif; ?>
                        </select>
                      </td>
                      <td>
                        <input type="number" name="_amount[]" class="form-control  _amount" placeholder="Dr. Amount" value="<?php echo e(old('_amount',0)); ?>">
                        
                      </td>
                     
                    </tr>`;

  function easy_voucherAddRow(event) {
      event.preventDefault();
      $("#area__voucher_details").append(single_row);
  }

  

  $(document).on('click','.easy_voucher_row_remove',function(event){
      event.preventDefault();
      var ledger_id = $(this).parent().parent('tr').find('._ledger_id').val();
      if(ledger_id ==""){
          $(this).parent().parent('tr').remove();
      }else{
        if(confirm('Are you sure your want to delete?')){
          $(this).parent().parent('tr').remove();
        } 
      }
      _easy_voucher_calculation();
  })

  function _easy_voucher_calculation(){
    var _total_amount = 0;
      $(document).find("._amount").each(function() {
          _total_amount +=parseFloat($(this).val());
      });
      
      $("._total_amount").val(Math.round(_total_amount));
  }


  $(document).on('keyup','._amount',function(){
    _easy_voucher_calculation();
  })




  $(document).on('change','._voucher_type',function(){
    $(document).find('._voucher_type').removeClass('required_border');
  })

  $(document).on('keyup','._note',function(){
    $(document).find('._note').removeClass('required_border');
  })

  $(document).on('click','._save_and_print',function(){
    $(document).find('._save_and_print_value').val(1);
  })


  $(document).on('click','.submit-button',function(event){

    event.preventDefault();
    var _total_amount = $(document).find("._total_amount").val();
    var _voucher_type = $(document).find('._voucher_type').val();
    var _note = $(document).find('._note').val();
    var _search_ledger_id = $(document).find('._search_ledger_id').val();


    var empty_ledger = [];
    $(document).find("._dr_ledger_id").each(function(){
        if($(this).val() ==""){
          alert(" Please Add Dr Account  ");
          $(document).find('._dr_search_ledger_id').focus().addClass('required_border');
          empty_ledger.push(1);
        }  
    })
    $(document).find("._cr_ledger_id").each(function(){
        if($(this).val() ==""){
          alert(" Please Add CR Account  ");
          $(document).find('._cr_search_ledger_id').focus().addClass('required_border');
          empty_ledger.push(1);
        }  
    })

    if(empty_ledger.length > 0){
      return false;
    }


    if(_voucher_type ==""){
       $(document).find('._voucher_type').focus().addClass('required_border');
       alert('Please Select Voucher Type.');
      return false;
    }else if(_note ==""){
       
       $(document).find('._note').focus().addClass('required_border');
      return false;
    }else{
      $(document).find('.voucher-form').submit();
    }
  })



$(document).on('keyup','._dr_search_ledger_id',delay(function(e){
    $(document).find('._dr_search_ledger_id').removeClass('required_border');
  var _gloabal_this = $(this);
  var _text_val = $(this).val().trim();
  var _head_no = $(this).attr('attr_account_head_no');
  if(isNaN(_head_no)){ _head_no=0 }
    console.log("_text_val "+_text_val)
    console.log("_head_no "+_head_no)
  var request = $.ajax({
      url: "<?php echo e(url('ledger-search')); ?>",
      method: "GET",
      data: { _text_val,_head_no },
      dataType: "JSON"
    });
     
    request.done(function( result ) {
      var search_html =``;
      var data = result.data; 
      if(data.length > 0 ){
            search_html +=`<div class="card"><table style="width: 400px;"> <tbody>`;
                        for (var i = 0; i < data.length; i++) {
                         search_html += `<tr class="_dr_search_row" >
                                        <td>${data[i].id}
                                        <input type="hidden" name="_id_ledger" class="_id_ledger" value="${data[i].id}">
                                        </td><td>${data[i]._name}
                                        <input type="hidden" name="_name_leder" class="_name_leder" value="${data[i]._name}">
                                        <input type="hidden" name="_s_l_address" class="_s_l_address" value="${data[i]._address}">
                                        <input type="hidden" name="_s_l_phone" class="_s_l_phone" value="${data[i]?._phone}">
                                        <input type="hidden" name="_s_l_balance" class="_s_l_balance" value="${data[i]?._balance}">
                                        </td>
                                        <td>${data[i]?._phone}</td>
                                        <td>${data[i]?._balance}</td>
                                        </tr>`;
                        }                         
            search_html += ` </tbody> </table></div>`;
      }else{
        search_html +=`<div class="card"><table style="width: 300px;"> 
        <thead><th colspan="3"><button type="button" class="btn btn-sm btn-default" data-toggle="modal" data-target="#exampleModalLong" title="Create Ledger"> New Ledger</button></th></thead><tbody></tbody></table></div>`;
      }     
      _gloabal_this.parent('td').find('._dr_search_box').html(search_html);
      _gloabal_this.parent('td').find('._dr_search_box').addClass('search_box_show').show();
      
    });
     
    request.fail(function( jqXHR, textStatus ) {
      alert( "Request failed: " + textStatus );
    });

  

}, 500));

$(document).on('click','._dr_search_row',function(){
  var _id = $(this).children('td').find('._id_ledger').val();
  var _name = $(this).find('._name_leder').val();
  var _s_l_balance = $(this).find('._s_l_balance').val();
  console.log(_s_l_balance)
  $(this).parent().parent().parent().parent().parent().parent().find('._dr_ledger_id').val(_id);
  var _id_name = `${_name},`+_s_l_balance;
  $(this).parent().parent().parent().parent().parent().parent().find('._dr_search_ledger_id').val(_id_name);


  $('._dr_search_box').hide();
  $('._dr_search_box').removeClass('search_box_show').hide();
})
 



$(document).on('keyup','._cr_search_ledger_id',delay(function(e){
    $(document).find('._cr_search_ledger_id').removeClass('required_border');
  var _gloabal_this = $(this);
  var _text_val = $(this).val().trim();
  var _head_no = $(this).attr('attr_account_head_no');
  if(isNaN(_head_no)){ _head_no=0 }
    console.log("_text_val "+_text_val)
    console.log("_head_no "+_head_no)
  var request = $.ajax({
      url: "<?php echo e(url('ledger-search')); ?>",
      method: "GET",
      data: { _text_val,_head_no },
      dataType: "JSON"
    });
     
    request.done(function( result ) {
      var search_html =``;
      var data = result.data; 
      if(data.length > 0 ){
            search_html +=`<div class="card"><table style="width: 400px;"> <tbody>`;
                        for (var i = 0; i < data.length; i++) {
                         search_html += `<tr class="_cr_search_row" >
                                        <td>${data[i].id}
                                        <input type="hidden" name="_id_ledger" class="_id_ledger" value="${data[i].id}">
                                        </td><td>${data[i]._name}
                                        <input type="hidden" name="_name_leder" class="_name_leder" value="${data[i]._name}">
                                        <input type="hidden" name="_s_l_address" class="_s_l_address" value="${data[i]._address}">
                                        <input type="hidden" name="_s_l_phone" class="_s_l_phone" value="${data[i]?._phone}">
                                        <input type="hidden" name="_s_l_balance" class="_s_l_balance" value="${data[i]?._balance}">
                                        </td>
                                        <td>${data[i]?._phone}</td>
                                        <td>${data[i]?._balance}</td>
                                        </tr>`;
                        }                         
            search_html += ` </tbody> </table></div>`;
      }else{
        search_html +=`<div class="card"><table style="width: 300px;"> 
        <thead><th colspan="3"><button type="button" class="btn btn-sm btn-default" data-toggle="modal" data-target="#exampleModalLong" title="Create Ledger"> New Ledger</button></th></thead><tbody></tbody></table></div>`;
      }     
      _gloabal_this.parent('td').find('._cr_search_box').html(search_html);
      _gloabal_this.parent('td').find('._cr_search_box').addClass('search_box_show').show();
      
    });
     
    request.fail(function( jqXHR, textStatus ) {
      alert( "Request failed: " + textStatus );
    });

  

}, 500));

$(document).on('click','._cr_search_row',function(){
  var _id = $(this).children('td').find('._id_ledger').val();
  var _name = $(this).find('._name_leder').val();
  var _s_l_balance = $(this).find('._s_l_balance').val();
  console.log(_s_l_balance)
$(this).parent().parent().parent().parent().parent().parent().find('._cr_ledger_id').val(_id);
  var _id_name = `${_name},`+_s_l_balance;
  $(this).parent().parent().parent().parent().parent().parent().find('._cr_search_ledger_id').val(_id_name);


  $('._cr_search_box').hide();
  $('._cr_search_box').removeClass('search_box_show').hide();
})
 



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

</script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\own\inv-acc-hrm\resources\views/backend/easy-voucher/edit.blade.php ENDPATH**/ ?>