
<?php $__env->startSection('title','General Settings'); ?>
<?php $__env->startSection('css'); ?>
<link rel="stylesheet" href="<?php echo e(asset('backend/new_style.css')); ?>">
<?php $__env->stopSection(); ?>
 
<?php $__env->startSection('content'); ?>

<div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 _page_name" >General Settings </h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
             
            </ol>
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
             
              <div class="card-body" style="margin-bottom: 20px;">
                <form method="POST" action="<?php echo e(route('admin-settings-store')); ?>" enctype="multipart/form-data">
               <?php echo csrf_field(); ?>
                    <div class="row">
                      <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <label>LOGO:</label>
                               <input type="file" accept="image/*" onchange="loadFile(event,1 )"  name="logo" class="form-control">
                               <img id="output_1" class="banner_image_create" src="<?php echo e(asset('/')); ?><?php echo e($settings->logo ?? ''); ?>"  style="max-height:100px;max-width: 100px; " />
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <label>Title:</label>
                               <input type="text" name="title"  class="form-control" value="<?php echo e(old('title',$settings->title ?? '' )); ?>">
                               <input type="hidden" name="id" value="<?php echo e($settings->id ?? ''); ?>">
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <label>Name:</label>
                               <input type="text" name="name" required class="form-control" value="<?php echo e(old('name',$settings->name ?? '' )); ?>">
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <label>Short Details:</label>
                               <input type="text" name="keywords"  class="form-control" value="<?php echo e(old('keywords',$settings->keywords ?? '' )); ?>" placeholder="Short Details">
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <label>Author:</label>
                               <input type="text" name="author"  class="form-control" value="<?php echo e(old('author',$settings->author ?? '' )); ?>">
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <label> VAT REGISTRATION NO:</label>
                               <input type="text" name="_bin"  class="form-control" value="<?php echo e(old('_bin',$settings->_bin ?? '' )); ?>">
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <label>TIN:</label>
                               <input type="text" name="_tin"  class="form-control" value="<?php echo e(old('_tin',$settings->_tin ?? '' )); ?>">
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <label>Email:</label>
                               <input type="text" name="_email"  class="form-control" value="<?php echo e(old('_email',$settings->_email ?? '' )); ?>">
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <label>Phone:</label>
                               <input type="text" name="_phone"  class="form-control" value="<?php echo e(old('_phone',$settings->_phone ?? '' )); ?>">
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <label>Address:</label>
                                <textarea  name="_address"  class="form-control"><?php echo e(old('_address',$settings->_address ?? '' )); ?></textarea>
                              
                            </div>
                        </div>
                        
                          
                          <div class="col-xs-6 col-sm-6 col-md-6">
                           <div class="form-group">
                                <label>SMS Service:</label>
                               <select class="form-control " name="_sms_service">
                                  <option value="0" <?php if($settings->_sms_service==0): ?> selected <?php endif; ?> >NO</option>
                                  <option value="1" <?php if($settings->_sms_service==1): ?> selected <?php endif; ?> >YES</option>
                                </select>
                              </div>
                            </div>
                            <div class="col-xs-6 col-sm-6 col-md-6">
                              <div class="form-group">
                                <label>Sales Using Unique Barcode:</label>
                               <select class="form-control " name="_barcode_service">
                                  <option value="0" <?php if($settings->_barcode_service==0): ?> selected <?php endif; ?> >NO</option>
                                  <option value="1" <?php if($settings->_barcode_service==1): ?> selected <?php endif; ?> >YES</option>
                                </select>
                              </div>
                            </div>
                            
                            
                           
                        
                       
                          
                            <div class="col-xs-6 col-sm-6 col-md-6">
                              <div class="form-group">
                                  <label>Cash Group:</label>
                                   <select class="form-control select2" name="_cash_group">
                                    <?php $__empty_1 = true; $__currentLoopData = $all_groups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $_group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                      <option value="<?php echo e($_group->id); ?>" <?php if($settings->_cash_group==$_group->id): ?> selected <?php endif; ?> ><?php echo e($_group->_name ?? ''); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <?php endif; ?>
                                    </select>
                              </div>
                                
                            </div>
                            <div class="col-xs-6 col-sm-6 col-md-6">
                                <div class="form-group">
                                  <label>Bank Group:</label>
                                   <select class="form-control select2" name="_bank_group">
                                      <?php $__empty_1 = true; $__currentLoopData = $all_groups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $_group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                      <option value="<?php echo e($_group->id); ?>" <?php if($settings->_bank_group==$_group->id): ?> selected <?php endif; ?> ><?php echo e($_group->_name ?? ''); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <?php endif; ?>
                                    </select>
                                </div>
                            </div>
                            <?php
                            $openig_ledgers = \DB::select(" SELECT t1.id,t1._name,t1._code FROM account_ledgers AS t1
INNER JOIN account_heads AS t2 ON t1._account_head_id=t2.id
INNER JOIN main_account_head AS t3 ON t3.id=t2._account_id
WHERE t3.id IN(5) ");
                            ?>
                            <div class="col-xs-6 col-sm-6 col-md-6">
                                <div class="form-group">
                                  <label>Opening Balance Ledger:</label>
                                   <select class="form-control " name="_opening_ledger">
                                      <?php $__empty_1 = true; $__currentLoopData = $openig_ledgers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ledger): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                      <option value="<?php echo e($ledger->id); ?>" <?php if($settings->_opening_ledger==$ledger->id): ?> selected <?php endif; ?> ><?php echo e($ledger->_name ?? ''); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <?php endif; ?>
                                    </select>
                                </div>
                            </div>

                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('hrm-module')): ?>
                            <?php
                            $_employee_group = $settings->_employee_group ?? '';
                            ?>
                            <div class="col-xs-6 col-sm-6 col-md-6">
                                <div class="form-group">
                                  <label>Employee Group:</label>
                                   <select class="form-control select2" name="_employee_group" >
                                      <?php $__empty_1 = true; $__currentLoopData = $all_groups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $_group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                      <option value="<?php echo e($_group->id); ?>" 
                                        <?php if($_group->id==$_employee_group): ?> selected <?php endif; ?>
                                        ><?php echo e($_group->_name ?? ''); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <?php endif; ?>
                                    </select>
                                </div>
                            </div>
                            <?php endif; ?>
                            
                          <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                 <label>Auto Lock:</label>
                                   <select class="form-control " name="_auto_lock">
                                      <option value="0" <?php if($settings->_auto_lock==0): ?> selected <?php endif; ?> >NO</option>
                                      <option value="1" <?php if($settings->_auto_lock==1): ?> selected <?php endif; ?> >YES</option>
                                    </select>
                            </div>  
                          </div>
                        <div class="col-xs-6 col-sm-6 col-md-6">
                           <div class="form-group">
                                <label>Purchase Base Model Barcode:</label>
                               <select class="form-control " name="_pur_base_model_barcode">
                                  <option value="0" <?php if($settings->_pur_base_model_barcode==0): ?> selected <?php endif; ?> >NO</option>
                                  <option value="1" <?php if($settings->_pur_base_model_barcode==1): ?> selected <?php endif; ?> >YES</option>
                                </select>
                              </div>
                        </div>

                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <label>Invoice Top Text:</label>
                                <textarea class="form-control" name="_top_title" ><?php echo e(old('_top_title',$settings->_top_title ?? '' )); ?></textarea>
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <label>Sales Note:</label>
                                <textarea class="form-control" name="_sales_note" ><?php echo e(old('_sales_note',$settings->_sales_note ?? '' )); ?></textarea>
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <label>Sales Return Note:</label>
                                <textarea class="form-control" name="_sales_return__note" ><?php echo e(old('_sales_return__note',$settings->_sales_return__note ?? '' )); ?></textarea>
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <label>Purchase Note:</label>
                                <textarea class="form-control" name="_purchse_note" ><?php echo e(old('_purchse_note',$settings->_purchse_note ?? '' )); ?></textarea>
                            </div>
                        </div>
                        <div class="col-xs-6 col-sm-6 col-md-6">
                            <div class="form-group">
                                <label>Purchase Return Note:</label>
                                <textarea class="form-control" name="_purchase_return_note" ><?php echo e(old('_purchase_return_note',$settings->_purchase_return_note ?? '' )); ?></textarea>
                            </div>
                        </div>
                        

                       
                        
                        <div class="col-xs-12 col-sm-12 col-md-6 bottom_save_section text-middle">
                            <button type="submit" class="btn btn-success submit-button ml-5"><i class="fa fa-credit-card mr-2" aria-hidden="true"></i> Save</button>
                            
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

<?php $__env->stopSection(); ?>
<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/sobuz23/sspf.sobuzsathitraders.com/resources/views/backend/settings/index.blade.php ENDPATH**/ ?>