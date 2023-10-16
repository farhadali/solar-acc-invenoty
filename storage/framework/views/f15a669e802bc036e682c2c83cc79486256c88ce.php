
<?php $__env->startSection('title',$page_name); ?>

<?php $__env->startSection('content'); ?>
<div class="content-header">
      <div class="container-fluid">

        <div class="col-sm-12" style="display: flex;">
             <a class="m-0 _page_name" href="<?php echo e(route('rlp-chain.index')); ?>"> <?php echo $page_name ?? ''; ?> </a>
            
          </div>

        
      </div><!-- /.container-fluid -->
    </div>
    <div class="message-area">
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
        <?php if($message = Session::get('success')): ?>
    <div class="alert alert-success">
      <p><?php echo e($message); ?></p>
    </div>
    <?php endif; ?>
    </div>
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-12">
            <div class="card">
              
              <div class="card-body">
 <?php echo Form::open(array('route' => 'rlp-chain.store','method'=>'POST')); ?>                    <?php echo csrf_field(); ?>
                    <div class="row">
                 

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
          <option value="<?php echo e($cost_center->id); ?>" <?php if(isset($data->id)): ?> <?php if($data->id == $cost_center->id): ?> selected <?php endif; ?>   <?php endif; ?>><?php echo e($cost_center->id ?? ''); ?> - <?php echo e($cost_center->_name ?? ''); ?></option>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
          <?php endif; ?>
        </select>
    </div>
</div>
<div class="col-xs-12 col-sm-12 col-md-2 ">
    <div class="form-group ">
        <label><?php echo e(__('label.chain_type')); ?>:<span class="_required">*</span></label>
       <select class="form-control chain_type" name="chain_type" required >
          
          <?php $__empty_1 = true; $__currentLoopData = access_chain_types(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=> $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
          <option value="<?php echo e($key); ?>" > <?php echo e($val); ?></option>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
          <?php endif; ?>
        </select>
    </div>
</div>

<div class="col-xs-12 col-sm-12 col-md-12 ">
    <div class="form-group ">
        <label><?php echo e(__('label.chain_name')); ?>:<span class="_required">*</span></label>
      <input type="text" name="chain_name" class="form-control" required value="<?php echo old('chain_name'); ?>" placeholder="<?php echo __('label.chain_name'); ?>" />
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
                                          <thead >
                                            <th class="text-left" >&nbsp;</th>
                                            <th class="text-left" >User</th>
                                            <th class="text-left" >Order</th>
                                            
                                          </thead>
                                          <tbody class="area__user_chain" id="area__user_chain">
                            <?php

                            $chain_datas= $data->chain ?? [];
                            ?>
                        <?php $__empty_1 = true; $__currentLoopData = $chain_datas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="_purchase_row">
                          <td>
                            <a  href="#none" class="btn btn-default _purchase_row_remove" ><i class="fa fa-trash"></i></a>
                            <input type="hidden" name="_row_id[]" value="<?php echo e($val->id); ?>">
                          </td>
                          <td>
                              <select class="form-control select2 employee_change" name="user_id[]">
                                <?php $__empty_2 = true; $__currentLoopData = $_list_data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $uval): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                                <option value="">Select Employee</option>
                                <option attr_id="<?php echo $uval->id; ?>" value="<?php echo $uval->_code; ?>" <?php if($val->erp_user_id==$uval->_code): ?> selected <?php endif; ?> ><?php echo $uval->_code ?? ''; ?>-<?php echo $uval->_name ?? ''; ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                                <?php endif; ?>
                              </select>
                               <input type="hidden" class="user_auto_id" name="user_row_id[]" value="0"  />
                          </td>
                          <td>
                              <input type="text" name="ack_order[]" class="form-control" value="<?php echo e($val->ack_order); ?>">
                          </td>
                         
                        </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <?php endif; ?>
                                          </tbody>
                                          <tfoot>
                                            <tr>
                                              <td>
                                                <a href="#none"  class="btn btn-default btn-sm" onclick="add_new_user(event)"><i class="fa fa-plus"></i></a>
                                              </td>
                                              <td></td>
                                              <td></td>
                                              
                                              
                                              
                                            </tr>
                                          </tfoot>
                                      </table>
                                </div>
                            </div>
                          </div>
                        </div>
                        
                        
                        
                       
                        <div class="col-xs-12 col-sm-12 col-md-12 bottom_save_section text-middle">
                            <button type="submit" class="btn btn-success  ml-5"><i class="fa fa-credit-card mr-2" aria-hidden="true"></i> Save</button>
                           
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
 $(document).on('click','._purchase_row_remove',function(event){
      event.preventDefault();
      $(this).parent().parent('tr').remove();
      
  })

   var single_row_user= `<tr class="_purchase_row">
              <td>
                <a  href="#none" class="btn btn-default _purchase_row_remove" ><i class="fa fa-trash"></i></a>
                <input type="hidden" name="_row_id[]" value="0">
              </td>
              <td>
                  <select class="form-control select2 employee_change" name="user_id[]">
                  <option value="">Select Employee</option>
                    <?php $__empty_1 = true; $__currentLoopData = $_list_data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $uval): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
<option
data-id="<?php echo $uval->id; ?>"
 value="<?php echo $uval->_code; ?>"><?php echo $uval->_code ?? ''; ?>-<?php echo $uval->_name ?? ''; ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <?php endif; ?>
                  </select>
                  <input type="hidden" class="user_auto_id" name="user_row_id[]" value="0"  />
              </td>
              <td>
                  <input type="text" name="ack_order[]" class="form-control">
              </td>
            </tr>`;

            function add_new_user(event){
                $(document).find("#area__user_chain").append(single_row_user);
                $(document).find('.select2').select2();
            }

            $(document).on('change','.employee_change',function(){
              var user_row_id = $(this).find(":selected").data("id");
              $(this).closest('td').find('.user_auto_id').val(user_row_id);
            })
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\solar-acc-invenoty\resources\views/rlp-module/rlp-chain/create.blade.php ENDPATH**/ ?>