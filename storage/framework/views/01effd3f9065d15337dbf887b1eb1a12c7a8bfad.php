
<?php $__env->startSection('title',$page_name ?? ''); ?>
<?php $__env->startSection('content'); ?>
<div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <a class="m-0 _page_name" href="<?php echo e(route('item-information.index')); ?>"><?php echo $page_name ?? ''; ?> </a>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
             <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('item-information-list')): ?>
              <li class="breadcrumb-item active">
                 <a class="btn btn-info" href="<?php echo e(route('item-information.index')); ?>"> <i class="fa fa-th-list" aria-hidden="true"></i></a>
               </li>
               <?php endif; ?>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-12">
            <div class="col-md-12">
<div class="card">
<div class="card-header p-2">
<ul class="nav nav-pills">
<li class="nav-item"><a class="nav-link active" href="#tab1" data-toggle="tab"><?php echo e(__('Basic')); ?></a></li>
<li class="nav-item"><a class="nav-link" href="#tab2" data-toggle="tab"><?php echo e(__('Pricing Information')); ?></a></li>
<li class="nav-item"><a class="nav-link" href="#tab3" data-toggle="tab"><?php echo e(__('Other Information')); ?></a></li>
<li class="nav-item display_none"><a class="nav-link" href="#tab4" data-toggle="tab"><?php echo e(__('Opening Balance')); ?></a></li>
</ul>
</div>
<div class="card-body">
  <?php echo $__env->make('backend.message.message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <?php echo Form::open(array('route' => 'item-information.store','method'=>'POST','enctype'=>'multipart/form-data')); ?>

<div class="tab-content">
    
<div class="tab-pane active" id="tab1">
    <div class="form-group row">
        <label class="col-sm-2 col-form-label"><?php echo e(__('label._category_id')); ?>: <span class="_required">*</span></label>
         <div class="col-sm-6">
           <select  class="form-control _category_id " name="_category_id" required>
              <option value="">--Select Category--</option>
              <?php $__empty_1 = true; $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
              <option value="<?php echo e($category->id); ?>"  <?php if(old('_category_id') == $category->id): ?> selected <?php endif; ?>  ><?php echo e($category->_parents->_name ?? 'C'); ?>-><?php echo e($category->_name ?? ''); ?></option>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
              <?php endif; ?>
            </select>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label" for="_item"><?php echo e(__('label._item')); ?>:<span class="_required">*</span></label>
         <div class="col-sm-6">
          <input type="text" id="_item" name="_item" class="form-control" value="<?php echo e(old('_item')); ?>" placeholder="Item" required>
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label" for="_unit"><?php echo e(__('label._unit')); ?>:<span class="_required">*</span></label>
         <div class="col-sm-2">
            <select class="form-control _unit_id " id="_unit_id" name="_unit_id" required>
              <option value="" >--Units--</option>
              <?php $__currentLoopData = $units; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $unit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
               <option value="<?php echo e($unit->id); ?>" <?php if(old('_unit_id')==$unit->id): ?> selected <?php endif; ?> ><?php echo e($unit->_name ?? ''); ?></option>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
    </div>
     
    <div class="form-group row">
        <label class="col-sm-2 col-form-label"  for="_code">Code:</label>
        <div class="col-sm-6">
            <input type="text" id="_code" name="_code" class="form-control" value="<?php echo e(old('_code')); ?>" placeholder="Code" >
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label" for="_barcode">Model:</label>
        <div class="col-sm-6">
            <input type="text" id="_barcode" name="_barcode" class="form-control" value="<?php echo e(old('_barcode')); ?>" placeholder="Model" >
        </div>
    </div>
    
    <div class="form-group row">
            <label class="col-sm-2 col-form-label" for="_manufacture_company">Manufacture Company:</label>
            <div class="col-sm-6">
                <input type="text" id="_manufacture_company" name="_manufacture_company" class="form-control _manufacture_company" value="<?php echo e(old('_manufacture_company')); ?>" placeholder="Manufacture Company" >
                <div class="search_boxManufacCompany"></div>
            </div>
    </div>

    <div class="form-group row">
            <label class="col-sm-2 col-form-label"  for="_unique_barcode" class="_required">Use Unique Barcode ?:</label>
            <div class="col-sm-2 ">
                <select class="form-control" name="_unique_barcode" id="_unique_barcode">
                  <option value="0">NO</option>
                  <option value="1">Yes</option>
                </select>
            </div>
    </div>
     <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('restaurant-module')): ?> 
        <div class="form-group row">
            <label class="col-sm-2 col-form-label" for="_kitchen_item" class="_required" title="if Yes then this item will send to kitchen to cook/production for sales and store deduct as per item ingredient wise automaticaly">Kitchen/Production Item ?:</label>
            <div class="col-sm-2 ">
            <select class="form-control" name="_kitchen_item" id="_kitchen_item">
              <option value="0">No</option>
              <option value="1">Yes</option>
            </select>
        </div>
    </div>
    <?php endif; ?>
     <div class="form-group row">
        <label class="col-sm-2 col-form-label" for="_status">Status:</label>
        <div class="col-sm-2">
        <select class="form-control" name="_status" id="_status">
          <option value="1">Active</option>
          <option value="0">In Active</option>
        </select>
    </div>
</div>

</div><!-- End fo Tab One -->

<div class="tab-pane" id="tab2"><!-- Starting Point Two -->
    
    <div class="form-group row">
        <label class="col-sm-2 col-form-label" for="_pur_rate">Purchase Rate:</label>
        <div class=" col-sm-6">
            <input type="number" step="any" min="0" id="_item_pur_rate" name="_pur_rate" class="form-control" value="<?php echo e(old('_pur_rate',0)); ?>" placeholder="Purchase Rate" >
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label" for="_sale_rate">Sales Rate:</label>
         <div class=" col-sm-6">
        
            <input type="number" step="any" min="0" id="_item_sale_rate" name="_sale_rate" class="form-control" value="<?php echo e(old('_sale_rate',0)); ?>" placeholder="Sales Rate" >
        </div>
    </div>

        <div class="form-group row">
            
            <label class="col-sm-2 col-form-label" for="_discount">Discount Rate:</label>
            <div class="col-sm-6">
            <input type="text" id="_discount" name="_discount" class="form-control" value="<?php echo e(old('_discount')); ?>" placeholder="Discount Rate" >
        </div>
    </div>
    
        <div class="form-group row">
            
            <label class="col-sm-2 col-form-label" for="_vat">Vat Rate:</label>
            <div class="col-sm-6">
            <input type="text" id="_vat" name="_vat" class="form-control" value="<?php echo e(old('_vat')); ?>" placeholder="Vat Rate" >
        </div>
    </div>
    
        <div class="form-group row">
            
            <label class="col-sm-2 col-form-label" for="_sd"><?php echo e(__('label._sd')); ?>:</label>
            <div class="col-sm-6">
            <input type="text" id="_sd" name="_sd" class="form-control" value="<?php echo e(old('_sd')); ?>" placeholder="<?php echo e(__('label._sd')); ?>" >
        </div>
    </div>
    
        <div class="form-group row">
            
            <label class="col-sm-2 col-form-label" for="_cd"><?php echo e(__('label._cd')); ?>:</label>
            <div class="col-sm-6">
            <input type="text" id="_cd" name="_cd" class="form-control" value="<?php echo e(old('_cd')); ?>" placeholder="<?php echo e(__('label._cd')); ?>" >
        </div>
    </div>
    
        <div class="form-group row">
            
            <label class="col-sm-2 col-form-label" for="_ait"><?php echo e(__('label._ait')); ?>:</label>
            <div class="col-sm-6">
            <input type="text" id="_ait" name="_ait" class="form-control" value="<?php echo e(old('_ait')); ?>" placeholder="<?php echo e(__('label._ait')); ?>" >
        </div>
    </div>
    
        <div class="form-group row">
            
            <label class="col-sm-2 col-form-label" for="_rd"><?php echo e(__('label._rd')); ?>:</label>
            <div class="col-sm-6">
            <input type="text" id="_rd" name="_rd" class="form-control" value="<?php echo e(old('_rd')); ?>" placeholder="<?php echo e(__('label._rd')); ?>" >
        </div>
    </div>
    
        <div class="form-group row">
            
            <label class="col-sm-2 col-form-label" for="_at"><?php echo e(__('label._at')); ?>:</label>
            <div class="col-sm-6">
            <input type="text" id="_at" name="_at" class="form-control" value="<?php echo e(old('_at')); ?>" placeholder="<?php echo e(__('label._at')); ?>" >
        </div>
    </div>
    
        <div class="form-group row">
            
            <label class="col-sm-2 col-form-label" for="_tti"><?php echo e(__('label._tti')); ?>:</label>
            <div class="col-sm-6">
            <input type="text" id="_tti" name="_tti" class="form-control" value="<?php echo e(old('_tti')); ?>" placeholder="<?php echo e(__('label._tti')); ?>" >
        </div>
    </div>
</div><!-- End of Second Tab -->

<div class="tab-pane" id="tab3"><!-- Starting point tab 3 -->


    <div class="form-group row">
         <label class="col-sm-2 col-form-label">Warranty: </label>
         <div class="col-sm-6">
           <select  class="form-control _warranty " name="_warranty" >
              <option value="">--Select Warranty--</option>
              <?php $__empty_1 = true; $__currentLoopData = $_warranties; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $_warranty): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
              <option value="<?php echo e($_warranty->id); ?>" <?php if(isset($request->_warranty)): ?> <?php if($request->_warranty == $_warranty->id): ?> selected <?php endif; ?>   <?php endif; ?>><?php echo e($_warranty->_name ?? ''); ?></option>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
              <?php endif; ?>
            </select>
        </div>
    </div>

    <div class="form-group row">
        <label class="col-sm-2 col-form-label" for="_reorder">Reorder Level:</label>
        <div class="col-sm-6">
        <input type="text" id="_reorder" name="_reorder" class="form-control" value="<?php echo e(old('_reorder')); ?>" placeholder="Reorder Level" >
    </div>
</div>

    <div class="form-group row">
        <label class="col-sm-2 col-form-label" for="_order_qty">Order Qty:</label>
        <div class="col-sm-6">
        <input type="text" id="_order_qty" name="_order_qty" class="form-control" value="<?php echo e(old('_order_qty')); ?>" placeholder="Order Qty" >
    </div>
</div>

 

 
   

 
    <div class="form-group">
        <label class="col-sm-2 col-form-label">Image:</label>
        <div class="col-sm-6">
       <input type="file" accept="image/*" onchange="loadFile(event,1 )"  name="_image" class="form-control">
       <img id="output_1" class="banner_image_create" src="<?php echo e(asset('/')); ?><?php echo e($settings->logo ?? ''); ?>"  style="max-height:100px;max-width: 100px; " />
    </div>
</div>


</div><!-- End of Tab Three -->
<div class="tab-pane display_none" id="tab4">
<table class="table">
    <thead>
        <tr>
            <th>#</th>
            <th><?php echo e(__('label.organization_id')); ?></th>
            <th><?php echo e(__('label._branch_id')); ?></th>
            <th><?php echo e(__('label._cost_center_id')); ?></th>
            <th><?php echo e(__('label._store_id')); ?></th>
            <th><?php echo e(__('label._qty')); ?></th>
            <th><?php echo e(__('label._cost_rate')); ?></th>
            <th><?php echo e(__('label._sales_rate')); ?></th>
            <th><?php echo e(__('label._amount')); ?></th>
        </tr>
    </thead>
    <tbody class="opeing_body">
        <tr>
            <td>
            <a href="#none" class="btn btn-default _opening_row_remove"><i class="fa fa-trash"></i></a>
          </td>
            <td>
                <select class="form-control _master_branch_id" name="_branch_id" required >
                  <?php $__empty_1 = true; $__currentLoopData = $permited_organizations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                  <option value="<?php echo e($val->id); ?>" ><?php echo e($val->id ?? ''); ?> - <?php echo e($val->_name ?? ''); ?></option>
                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                  <?php endif; ?>
                </select>
            </td>
            <td>
                <select class="form-control _master_branch_id" name="_branch_id" required >
                  <?php $__empty_1 = true; $__currentLoopData = $permited_branch; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $branch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                  <option value="<?php echo e($branch->id); ?>" <?php if(isset($request->_branch_id)): ?> <?php if($request->_branch_id == $branch->id): ?> selected <?php endif; ?>   <?php endif; ?>><?php echo e($branch->id ?? ''); ?> - <?php echo e($branch->_name ?? ''); ?></option>
                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                  <?php endif; ?>
                </select>
            </td>
            <td>
                <select class="form-control _cost_center_id" name="_cost_center_id" required >    
                  <?php $__empty_1 = true; $__currentLoopData = $permited_costcenters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cost_center): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                  <option value="<?php echo e($cost_center->id); ?>" <?php if(isset($request->_cost_center_id)): ?> <?php if($request->_cost_center_id == $cost_center->id): ?> selected <?php endif; ?>   <?php endif; ?>><?php echo e($cost_center->id ?? ''); ?> - <?php echo e($cost_center->_name ?? ''); ?></option>
                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                  <?php endif; ?>
                </select>
            </td>
            <td>
                <select class="form-control  _store_id" name="_store_id">
                  <?php $__empty_1 = true; $__currentLoopData = $store_houses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $store): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                  <option value="<?php echo e($store->id); ?>"><?php echo e($store->_name ?? ''); ?></option>
                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                  <?php endif; ?>
                </select>
            </td>
            
            <td>
                 <input type="number" step="any" min="0" id="_item_opening_qty" name="_opening_qty" class="form-control" value="<?php echo e(old('_opening_qty',0)); ?>" placeholder="Opening QTY" >
            </td>
            <td>
                 <input type="number" step="any" min="0"  name="_cost_rate" class="form-control" value="<?php echo e(old('_cost_rate',0)); ?>" placeholder="Opening Cost Rate" >
            </td>
            <td>
                 <input type="number" step="any" min="0"  name="_opening_sales_rate" class="form-control" value="<?php echo e(old('_opening_sales_rate',0)); ?>" placeholder="Opening Sales Rate" >
            </td>
            <td>
                <input type="number" step="any" min="0"  name="_opening_amount" class="form-control" value="<?php echo e(old('_opening_amount',0)); ?>"  >
            </td>
        </tr>
    </tbody>
    <tfoot>
        <tr>
                <th colspan="2">
                    <a href="#none" class="btn btn-default" onclick="addNewRowForOpenig(event)"><i class="fa fa-plus"></i></a>
                  </th>
           
            <th colspan="3"><?php echo e(__('label._total')); ?></th>
            <th>
                <input type="text" name="_total_opening_qty" class="form-control _total_opening_qty" value="0" readonly>
            </th>
            <th></th>
            <th></th>
            <th>
                 <input type="text" name="_total_opening_amount" class="form-control _total_opening_amount" value="0" readonly>
            </th>
        </tr>
    </tfoot>
</table>

</div>
<div class="form-group row">
<div class="offset-sm-2 col-sm-6">
<button type="submit" class="btn btn-danger">Submit</button>
</div>
</div>

</div> <!-- End of tab content -->
</form> <!-- End of form -->

</div>
</div>

</div>
         
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </div>
</div>




<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
<script type="text/javascript">
    function addNewRowForOpenig(event){
        $(document).find(".opeing_body").append(` <tr>
            <td>
            <a href="#none" class="btn btn-default _opening_row_remove"><i class="fa fa-trash"></i></a>
          </td>
            <td>
                <select class="form-control _master_branch_id" name="_branch_id" required >
                  <?php $__empty_1 = true; $__currentLoopData = $permited_organizations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                  <option value="<?php echo e($val->id); ?>" ><?php echo e($val->id ?? ''); ?> - <?php echo e($val->_name ?? ''); ?></option>
                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                  <?php endif; ?>
                </select>
            </td>
            <td>
                <select class="form-control _master_branch_id" name="_branch_id" required >
                  <?php $__empty_1 = true; $__currentLoopData = $permited_branch; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $branch): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                  <option value="<?php echo e($branch->id); ?>" <?php if(isset($request->_branch_id)): ?> <?php if($request->_branch_id == $branch->id): ?> selected <?php endif; ?>   <?php endif; ?>><?php echo e($branch->id ?? ''); ?> - <?php echo e($branch->_name ?? ''); ?></option>
                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                  <?php endif; ?>
                </select>
            </td>
            <td>
                <select class="form-control _cost_center_id" name="_cost_center_id" required >    
                  <?php $__empty_1 = true; $__currentLoopData = $permited_costcenters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cost_center): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                  <option value="<?php echo e($cost_center->id); ?>" <?php if(isset($request->_cost_center_id)): ?> <?php if($request->_cost_center_id == $cost_center->id): ?> selected <?php endif; ?>   <?php endif; ?>><?php echo e($cost_center->id ?? ''); ?> - <?php echo e($cost_center->_name ?? ''); ?></option>
                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                  <?php endif; ?>
                </select>
            </td>
            <td>
                <select class="form-control  _store_id" name="_store_id">
                  <?php $__empty_1 = true; $__currentLoopData = $store_houses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $store): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                  <option value="<?php echo e($store->id); ?>"><?php echo e($store->_name ?? ''); ?></option>
                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                  <?php endif; ?>
                </select>
            </td>
            
            <td>
                 <input type="number" step="any" min="0" id="_item_opening_qty" name="_opening_qty" class="form-control" value="<?php echo e(old('_opening_qty',0)); ?>" placeholder="Opening QTY" >
            </td>
            <td>
                 <input type="number" step="any" min="0"  name="_cost_rate" class="form-control" value="<?php echo e(old('_cost_rate',0)); ?>" placeholder="Opening Cost Rate" >
            </td>
            <td>
                 <input type="number" step="any" min="0"  name="_opening_sales_rate" class="form-control" value="<?php echo e(old('_opening_sales_rate',0)); ?>" placeholder="Opening Sales Rate" >
            </td>
            <td>
                <input type="number" step="any" min="0"  name="_opening_amount" class="form-control" value="<?php echo e(old('_opening_amount',0)); ?>"  >
            </td>
        </tr>`);
    }

    $(document).on('click','._opening_row_remove',function(){
        $(this).closest('tr').remove();
    })
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\own\inv-acc-hrm\resources\views/backend/item-information/create.blade.php ENDPATH**/ ?>