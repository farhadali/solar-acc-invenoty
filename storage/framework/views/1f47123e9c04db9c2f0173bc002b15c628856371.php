<div class="form-group row">
        <label for="_default_inventory" class="col-sm-5 col-form-label">Default Inventory</label>
        <select class="form-control col-sm-7" name="_default_inventory">
          <?php $__currentLoopData = $inv_accounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $account): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <option value="<?php echo e($account->id); ?>" <?php if(isset($form_settings->_default_inventory)): ?><?php if($form_settings->_default_inventory==$account->id): ?> selected <?php endif; ?> <?php endif; ?>><?php echo e($account->_name ?? ''); ?></option>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
      </div>
      <div class="form-group row">
        <label for="_default_sales" class="col-sm-5 col-form-label">Default Sales Account</label>
        <select class="form-control col-sm-7" name="_default_sales">
          <?php $__currentLoopData = $p_accounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $account): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <option value="<?php echo e($account->id); ?>" <?php if(isset($form_settings->_default_sales)): ?><?php if($form_settings->_default_sales==$account->id): ?> selected <?php endif; ?> <?php endif; ?>><?php echo e($account->_name ?? ''); ?></option>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
      </div>
      <div class="form-group row">
        <label for="_default_cost_of_solds" class="col-sm-5 col-form-label">Cost of Goods Sold</label>
        <select class="form-control col-sm-7" name="_default_cost_of_solds">
          <?php $__currentLoopData = $cost_of_solds; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $account): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <option value="<?php echo e($account->id); ?>" <?php if(isset($form_settings->_default_cost_of_solds)): ?><?php if($form_settings->_default_cost_of_solds==$account->id): ?> selected <?php endif; ?> <?php endif; ?>><?php echo e($account->_name ?? ''); ?></option>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
      </div>
      <div class="form-group row">
        <label for="_default_discount" class="col-sm-5 col-form-label">Default Discount Account</label>
        <select class="form-control col-md-7" name="_default_discount">
          <?php $__currentLoopData = $dis_accounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $account): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <option value="<?php echo e($account->id); ?>" <?php if(isset($form_settings->_default_discount)): ?><?php if($form_settings->_default_discount==$account->id): ?> selected <?php endif; ?> <?php endif; ?>><?php echo e($account->_name ?? ''); ?></option>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
      </div>
      <div class="form-group row">
        <label for="_default_vat_account" class="col-sm-5 col-form-label">Default VAT Account</label>
        <select class="form-control col-md-7" name="_default_vat_account">
          <?php $__currentLoopData = $dis_accounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $account): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <option value="<?php echo e($account->id); ?>" <?php if(isset($form_settings->_default_vat_account)): ?><?php if($form_settings->_default_vat_account==$account->id): ?> selected <?php endif; ?> <?php endif; ?>><?php echo e($account->_name ?? ''); ?></option>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
      </div>
      <div class="form-group row">
        <label for="_show_cost_rate" class="col-sm-5 col-form-label">Show Cost Rate</label>
        <select class="form-control col-sm-7" name="_show_cost_rate">
         
          <option value="0" <?php if(isset($form_settings->_show_cost_rate)): ?><?php if($form_settings->_show_cost_rate==0): ?> selected <?php endif; ?> <?php endif; ?>>NO</option>
          <option value="1" <?php if(isset($form_settings->_show_cost_rate)): ?><?php if($form_settings->_show_cost_rate==1): ?> selected <?php endif; ?> <?php endif; ?>>YES</option>
        </select>
      </div>
      <div class="form-group row">
        <label for="_show_unit" class="col-sm-5 col-form-label">Show Unit</label>
        <select class="form-control col-sm-7" name="_show_unit">
         
          <option value="0" <?php if(isset($form_settings->_show_unit)): ?><?php if($form_settings->_show_unit==0): ?> selected <?php endif; ?> <?php endif; ?>>NO</option>
          <option value="1" <?php if(isset($form_settings->_show_unit)): ?><?php if($form_settings->_show_unit==1): ?> selected <?php endif; ?> <?php endif; ?>>YES</option>
        </select>
      </div>
      <div class="form-group row">
        <label for="_show_sales_man" class="col-sm-5 col-form-label">Show Sales Man</label>
        <select class="form-control col-sm-7" name="_show_sales_man">
         
          <option value="0" <?php if(isset($form_settings->_show_sales_man)): ?><?php if($form_settings->_show_sales_man==0): ?> selected <?php endif; ?> <?php endif; ?>>NO</option>
          <option value="1" <?php if(isset($form_settings->_show_sales_man)): ?><?php if($form_settings->_show_sales_man==1): ?> selected <?php endif; ?> <?php endif; ?>>YES</option>
        </select>
      </div>
      <div class="form-group row">
        <label for="_show_delivery_man" class="col-sm-5 col-form-label">Show Delivery Man</label>
        <select class="form-control col-sm-7" name="_show_delivery_man">
         
          <option value="0" <?php if(isset($form_settings->_show_delivery_man)): ?><?php if($form_settings->_show_delivery_man==0): ?> selected <?php endif; ?> <?php endif; ?>>NO</option>
          <option value="1" <?php if(isset($form_settings->_show_delivery_man)): ?><?php if($form_settings->_show_delivery_man==1): ?> selected <?php endif; ?> <?php endif; ?>>YES</option>
        </select>
      </div>
      <div class="form-group row">
        <label for="_inline_discount" class="col-sm-5 col-form-label">Show Inline Discount</label>
        <select class="form-control col-sm-7" name="_inline_discount">
         
          <option value="0" <?php if(isset($form_settings->_inline_discount)): ?><?php if($form_settings->_inline_discount==0): ?> selected <?php endif; ?> <?php endif; ?>>NO</option>
          <option value="1" <?php if(isset($form_settings->_inline_discount)): ?><?php if($form_settings->_inline_discount==1): ?> selected <?php endif; ?> <?php endif; ?>>YES</option>
        </select>
      </div>
      <div class="form-group row">
        <label for="_show_vat" class="col-sm-5 col-form-label">Show VAT</label>
        <select class="form-control col-sm-7" name="_show_vat">
         
          <option value="0" <?php if(isset($form_settings->_show_vat)): ?><?php if($form_settings->_show_vat==0): ?> selected <?php endif; ?> <?php endif; ?>>NO</option>
          <option value="1" <?php if(isset($form_settings->_show_vat)): ?><?php if($form_settings->_show_vat==1): ?> selected <?php endif; ?> <?php endif; ?>>YES</option>
        </select>
      </div>
      <div class="form-group row">
        <label for="_show_barcode" class="col-sm-5 col-form-label">Show Barcode</label>
        <select class="form-control col-sm-7" name="_show_barcode">
          <option value="0" <?php if(isset($form_settings->_show_barcode)): ?><?php if($form_settings->_show_barcode==0): ?> selected <?php endif; ?> <?php endif; ?>>NO</option>
          <option value="1" <?php if(isset($form_settings->_show_barcode)): ?><?php if($form_settings->_show_barcode==1): ?> selected <?php endif; ?> <?php endif; ?>>YES</option>
        </select>
      </div>
      <div class="form-group row">
        <label for="_show_store" class="col-sm-5 col-form-label">Show Store</label>
        <select class="form-control col-sm-7" name="_show_store">
          <option value="0" <?php if(isset($form_settings->_show_store)): ?><?php if($form_settings->_show_store==0): ?> selected <?php endif; ?> <?php endif; ?>>NO</option>
          <option value="1" <?php if(isset($form_settings->_show_store)): ?><?php if($form_settings->_show_store==1): ?> selected <?php endif; ?> <?php endif; ?>>YES</option>
        </select>
      </div>
      <div class="form-group row">
        <label for="_show_self" class="col-sm-5 col-form-label">Show Shelf</label>
        <select class="form-control col-sm-7" name="_show_self">
          <option value="0" <?php if(isset($form_settings->_show_self)): ?><?php if($form_settings->_show_self==0): ?> selected <?php endif; ?> <?php endif; ?>>NO</option>
          <option value="1" <?php if(isset($form_settings->_show_self)): ?><?php if($form_settings->_show_self==1): ?> selected <?php endif; ?> <?php endif; ?>>YES</option>
        </select>
      </div>
      <div class="form-group row">
        <label for="_show_warranty" class="col-sm-5 col-form-label">Show Warranty</label>
        <select class="form-control col-sm-7" name="_show_warranty">
          <option value="0" <?php if(isset($form_settings->_show_warranty)): ?><?php if($form_settings->_show_warranty==0): ?> selected <?php endif; ?> <?php endif; ?>>NO</option>
          <option value="1" <?php if(isset($form_settings->_show_warranty)): ?><?php if($form_settings->_show_warranty==1): ?> selected <?php endif; ?> <?php endif; ?>>YES</option>
        </select>
      </div>

      <div class="form-group row">
        <label for="_show_expire_date" class="col-sm-5 col-form-label">Show Expire Date</label>
        <select class="form-control col-sm-7" name="_show_expire_date">
          <option value="0" <?php if(isset($form_settings->_show_expire_date)): ?><?php if($form_settings->_show_expire_date==0): ?> selected <?php endif; ?> <?php endif; ?>>NO</option>
          <option value="1" <?php if(isset($form_settings->_show_expire_date)): ?><?php if($form_settings->_show_expire_date==1): ?> selected <?php endif; ?> <?php endif; ?>>YES</option>
        </select>
      </div>
      <div class="form-group row">
        <label for="_show_manufacture_date" class="col-sm-5 col-form-label">Show Manufacture Date</label>
        <select class="form-control col-sm-7" name="_show_manufacture_date">
          <option value="0" <?php if(isset($form_settings->_show_manufacture_date)): ?><?php if($form_settings->_show_manufacture_date==0): ?> selected <?php endif; ?> <?php endif; ?>>NO</option>
          <option value="1" <?php if(isset($form_settings->_show_manufacture_date)): ?><?php if($form_settings->_show_manufacture_date==1): ?> selected <?php endif; ?> <?php endif; ?>>YES</option>
        </select>
      </div>
      <div class="form-group row">
        <label for="_show_p_balance" class="col-sm-5 col-form-label">Invoice Show Previous Balance</label>
        <select class="form-control col-sm-7" name="_show_p_balance">
          <option value="0" <?php if(isset($form_settings->_show_p_balance)): ?><?php if($form_settings->_show_p_balance==0): ?> selected <?php endif; ?> <?php endif; ?>>NO</option>
          <option value="1" <?php if(isset($form_settings->_show_p_balance)): ?><?php if($form_settings->_show_p_balance==1): ?> selected <?php endif; ?> <?php endif; ?>>YES</option>
        </select>
      </div>
       <div class="form-group row">
        <label for="_invoice_template" class="col-sm-5 col-form-label">Invoice Template</label>
        <select class="form-control col-sm-7" name="_invoice_template">
          <option value="1" <?php if(isset($form_settings->_invoice_template)): ?><?php if($form_settings->_invoice_template==1): ?> selected <?php endif; ?> <?php endif; ?>>Template A</option>
          <option value="2" <?php if(isset($form_settings->_invoice_template)): ?><?php if($form_settings->_invoice_template==2): ?> selected <?php endif; ?> <?php endif; ?>>Template B</option>
          <option value="3" <?php if(isset($form_settings->_invoice_template)): ?><?php if($form_settings->_invoice_template==3): ?> selected <?php endif; ?> <?php endif; ?>>Template C</option>
          <option value="4" <?php if(isset($form_settings->_invoice_template)): ?><?php if($form_settings->_invoice_template==4): ?> selected <?php endif; ?> <?php endif; ?>>Template D</option>
        </select>
      </div><?php /**PATH D:\xampp\htdocs\own\inv-acc-hrm\resources\views/backend/sales-return/form_setting_modal.blade.php ENDPATH**/ ?>