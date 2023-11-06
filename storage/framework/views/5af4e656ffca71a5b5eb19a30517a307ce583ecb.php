   <?php
    $_show_barcode = $form_settings->_show_barcode ?? 0;
    $_show_short_note = $form_settings->_show_short_note ?? 0;
    $_show_cost_rate =  $form_settings->_show_cost_rate ?? 0;
    $_show_vat =  $form_settings->_show_vat ?? 0;
    $_inline_discount = $form_settings->_inline_discount ?? 0;
    $_show_self = $form_settings->_show_self ?? 0;
    $_show_unit = $form_settings->_show_unit ?? 0;
    $_show_sd = $form_settings->_show_sd ?? 0;
    $_show_cd = $form_settings->_show_cd ?? 0;
    $_show_ait = $form_settings->_show_ait ?? 0;
    $_show_rd  = $form_settings->_show_rd  ?? 0;
    $_show_at  = $form_settings->_show_at  ?? 0;
    $_show_tti  = $form_settings->_show_tti  ?? 0;
    $_show_expected_qty  = $form_settings->_show_expected_qty  ?? 0;
    $_show_sales_rate  = $form_settings->_show_sales_rate  ?? 0;
    ?>

        <div class="form-group row">
        <label for="_default_inventory" class="col-sm-5 col-form-label"><?php echo e(__('label._default_inventory')); ?></label>
        <select class="form-control col-sm-7" name="_default_inventory">
          <option value="0"><?php echo e(__('label.select')); ?></option>
          <?php $__currentLoopData = $inv_accounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $account): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <option value="<?php echo e($account->id); ?>" <?php if(isset($form_settings->_default_inventory)): ?><?php if($form_settings->_default_inventory==$account->id): ?> selected <?php endif; ?> <?php endif; ?>><?php echo e($account->_name ?? ''); ?></option>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
      </div>
       
      <div class="form-group row">
        <label for="_default_purchase" class="col-sm-5 col-form-label"><?php echo e(__('label._default_purchase')); ?></label>
        <select class="form-control col-sm-7" name="_default_purchase">
          <option value="0"><?php echo e(__('label.select')); ?></option>
          <?php $__currentLoopData = $p_accounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $account): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <option value="<?php echo e($account->id); ?>" <?php if(isset($form_settings->_default_purchase)): ?><?php if($form_settings->_default_purchase==$account->id): ?> selected <?php endif; ?> <?php endif; ?>><?php echo e($account->_name ?? ''); ?></option>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
      </div>
      <div class="form-group row">
        <label for="_default_discount" class="col-sm-5 col-form-label"><?php echo e(__('label._default_discount')); ?></label>
        <select class="form-control col-md-7" name="_default_discount">
          <option value="0"><?php echo e(__('label.select')); ?></option>
          <?php $__currentLoopData = $dis_accounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $account): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <option value="<?php echo e($account->id); ?>" <?php if(isset($form_settings->_default_discount)): ?><?php if($form_settings->_default_discount==$account->id): ?> selected <?php endif; ?> <?php endif; ?>><?php echo e($account->_name ?? ''); ?></option>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
      </div>
      <div class="form-group row">
        <label for="_default_vat_account" class="col-sm-5 col-form-label"><?php echo e(__('label._default_vat_account')); ?></label>
        <select class="form-control col-md-7" name="_default_vat_account">
          <option value="0"><?php echo e(__('label.select')); ?></option>
          <?php $__currentLoopData = $p_accounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $account): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <option value="<?php echo e($account->id); ?>" <?php if(isset($form_settings->_default_vat_account)): ?><?php if($form_settings->_default_vat_account==$account->id): ?> selected <?php endif; ?> <?php endif; ?>><?php echo e($account->_name ?? ''); ?></option>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
      </div>
       <div class="form-group row">
        <label for="_opening_inventory" class="col-sm-5 col-form-label"><?php echo e(__('label._opening_inventory')); ?></label>
        <select class="form-control col-sm-7" name="_opening_inventory">
          <option value="0"><?php echo e(__('label.select')); ?></option>
          <?php $__currentLoopData = $inv_accounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $account): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <option value="<?php echo e($account->id); ?>" <?php if(isset($form_settings->_opening_inventory)): ?><?php if($form_settings->_opening_inventory==$account->id): ?> selected <?php endif; ?> <?php endif; ?>><?php echo e($account->_name ?? ''); ?></option>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
      </div>
       <div class="form-group row">
        <label for="_default_capital" class="col-sm-5 col-form-label">Capital Account</label>
        <select class="form-control col-sm-7" name="_default_capital">
          <option value="0"><?php echo e(__('label.select')); ?></option>
          <?php $__currentLoopData = $capital_accounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $account): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <option value="<?php echo e($account->id); ?>" <?php if(isset($form_settings->_default_capital)): ?><?php if($form_settings->_default_capital==$account->id): ?> selected <?php endif; ?> <?php endif; ?>><?php echo e($account->_name ?? ''); ?></option>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
      </div>
       <div class="form-group row">
        <label for="_default_sd_account" class="col-sm-5 col-form-label"><?php echo e(__('label._default_sd_account')); ?></label>
        <select class="form-control col-sm-7" name="_default_sd_account">
          <option value="0"><?php echo e(__('label.select')); ?></option>
          <?php $__currentLoopData = $capital_accounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $account): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <option value="<?php echo e($account->id); ?>" <?php if(isset($form_settings->_default_sd_account)): ?><?php if($form_settings->_default_sd_account==$account->id): ?> selected <?php endif; ?> <?php endif; ?>><?php echo e($account->_name ?? ''); ?></option>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
      </div>
       <div class="form-group row">
        <label for="_default_cd_account" class="col-sm-5 col-form-label"><?php echo e(__('label._default_cd_account')); ?></label>
        <select class="form-control col-sm-7" name="_default_cd_account">
          <option value="0"><?php echo e(__('label.select')); ?></option>
          <?php $__currentLoopData = $capital_accounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $account): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <option value="<?php echo e($account->id); ?>" <?php if(isset($form_settings->_default_cd_account)): ?><?php if($form_settings->_default_cd_account==$account->id): ?> selected <?php endif; ?> <?php endif; ?>><?php echo e($account->_name ?? ''); ?></option>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
      </div>
       <div class="form-group row">
        <label for="_default_ait_account" class="col-sm-5 col-form-label"><?php echo e(__('label._default_ait_account')); ?></label>
        <select class="form-control col-sm-7" name="_default_ait_account">
          <option value="0"><?php echo e(__('label.select')); ?></option>
          <?php $__currentLoopData = $capital_accounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $account): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <option value="<?php echo e($account->id); ?>" <?php if(isset($form_settings->_default_ait_account)): ?><?php if($form_settings->_default_ait_account==$account->id): ?> selected <?php endif; ?> <?php endif; ?>><?php echo e($account->_name ?? ''); ?></option>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
      </div>
       <div class="form-group row">
        <label for="_default_rd_account" class="col-sm-5 col-form-label"><?php echo e(__('label._default_rd_account')); ?></label>
        <select class="form-control col-sm-7" name="_default_rd_account">
          <option value="0"><?php echo e(__('label.select')); ?></option>
          <?php $__currentLoopData = $capital_accounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $account): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <option value="<?php echo e($account->id); ?>" <?php if(isset($form_settings->_default_rd_account)): ?><?php if($form_settings->_default_rd_account==$account->id): ?> selected <?php endif; ?> <?php endif; ?>><?php echo e($account->_name ?? ''); ?></option>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
      </div>
       <div class="form-group row">
        <label for="_default_tti_account" class="col-sm-5 col-form-label"><?php echo e(__('label._default_tti_account')); ?></label>
        <select class="form-control col-sm-7" name="_default_tti_account">
          <option value="0"><?php echo e(__('label.select')); ?></option>
          <?php $__currentLoopData = $capital_accounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $account): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <option value="<?php echo e($account->id); ?>" <?php if(isset($form_settings->_default_tti_account)): ?><?php if($form_settings->_default_tti_account==$account->id): ?> selected <?php endif; ?> <?php endif; ?>><?php echo e($account->_name ?? ''); ?></option>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
      </div>
      <div class="form-group row">
        <label for="_inline_discount" class="col-sm-5 col-form-label">Show Inline Discount</label>
        <select class="form-control col-sm-7" name="_inline_discount">
         
          <option value="0" <?php if(isset($_inline_discount)): ?><?php if($_inline_discount==0): ?> selected <?php endif; ?> <?php endif; ?>>NO</option>
          <option value="1" <?php if(isset($_inline_discount)): ?><?php if($_inline_discount==1): ?> selected <?php endif; ?> <?php endif; ?>>YES</option>
        </select>
      </div>
      <div class="form-group row">
        <label for="_show_unit" class="col-sm-5 col-form-label">Show Unit</label>
        <select class="form-control col-sm-7" name="_show_unit">
         
          <option value="0" <?php if(isset($_show_unit)): ?><?php if($_show_unit==0): ?> selected <?php endif; ?> <?php endif; ?>>NO</option>
          <option value="1" <?php if(isset($_show_unit)): ?><?php if($_show_unit==1): ?> selected <?php endif; ?> <?php endif; ?>>YES</option>
        </select>
      </div>
      <div class="form-group row">
        <label for="_show_vat" class="col-sm-5 col-form-label">Show VAT</label>
        <select class="form-control col-sm-7" name="_show_vat">
         
          <option value="0" <?php if(isset($_show_vat)): ?><?php if($_show_vat==0): ?> selected <?php endif; ?> <?php endif; ?>>NO</option>
          <option value="1" <?php if(isset($_show_vat)): ?><?php if($_show_vat==1): ?> selected <?php endif; ?> <?php endif; ?>>YES</option>
        </select>
      </div>
      <div class="form-group row">
        <label for="_show_sales_rate" class="col-sm-5 col-form-label"><?php echo e(__('label._show_sales_rate')); ?></label>
        <select class="form-control col-sm-7" name="_show_sales_rate">
         
          <option value="0" <?php if(isset($_show_sales_rate)): ?><?php if($_show_sales_rate==0): ?> selected <?php endif; ?> <?php endif; ?>>NO</option>
          <option value="1" <?php if(isset($_show_sales_rate)): ?><?php if($_show_sales_rate==1): ?> selected <?php endif; ?> <?php endif; ?>>YES</option>
        </select>
      </div>
      <div class="form-group row">
        <label for="_show_sd" class="col-sm-5 col-form-label"><?php echo e(__('label._show_sd')); ?></label>
        <select class="form-control col-sm-7" name="_show_sd">
         
          <option value="0" <?php if(isset($_show_sd)): ?><?php if($_show_sd==0): ?> selected <?php endif; ?> <?php endif; ?>>NO</option>
          <option value="1" <?php if(isset($_show_sd)): ?><?php if($_show_sd==1): ?> selected <?php endif; ?> <?php endif; ?>>YES</option>
        </select>
      </div>
      <div class="form-group row">
        <label for="_show_cd" class="col-sm-5 col-form-label"><?php echo e(__('label._show_cd')); ?></label>
        <select class="form-control col-sm-7" name="_show_cd">
         
          <option value="0" <?php if(isset($_show_cd)): ?><?php if($_show_cd==0): ?> selected <?php endif; ?> <?php endif; ?>>NO</option>
          <option value="1" <?php if(isset($_show_cd)): ?><?php if($_show_cd==1): ?> selected <?php endif; ?> <?php endif; ?>>YES</option>
        </select>
      </div>
      <div class="form-group row">
        <label for="_show_ait" class="col-sm-5 col-form-label"><?php echo e(__('label._show_ait')); ?></label>
        <select class="form-control col-sm-7" name="_show_ait">
         
          <option value="0" <?php if(isset($_show_ait)): ?><?php if($_show_ait==0): ?> selected <?php endif; ?> <?php endif; ?>>NO</option>
          <option value="1" <?php if(isset($_show_ait)): ?><?php if($_show_ait==1): ?> selected <?php endif; ?> <?php endif; ?>>YES</option>
        </select>
      </div>
      <div class="form-group row">
        <label for="_show_rd" class="col-sm-5 col-form-label"><?php echo e(__('label._show_rd')); ?></label>
        <select class="form-control col-sm-7" name="_show_rd">
         
          <option value="0" <?php if(isset($_show_rd)): ?><?php if($_show_rd==0): ?> selected <?php endif; ?> <?php endif; ?>>NO</option>
          <option value="1" <?php if(isset($_show_rd)): ?><?php if($_show_rd==1): ?> selected <?php endif; ?> <?php endif; ?>>YES</option>
        </select>
      </div>
      <div class="form-group row">
        <label for="_show_at" class="col-sm-5 col-form-label"><?php echo e(__('label._show_at')); ?></label>
        <select class="form-control col-sm-7" name="_show_at">
         
          <option value="0" <?php if(isset($_show_at)): ?><?php if($_show_at==0): ?> selected <?php endif; ?> <?php endif; ?>>NO</option>
          <option value="1" <?php if(isset($_show_at)): ?><?php if($_show_at==1): ?> selected <?php endif; ?> <?php endif; ?>>YES</option>
        </select>
      </div>
      <div class="form-group row">
        <label for="_show_tti" class="col-sm-5 col-form-label"><?php echo e(__('label._show_tti')); ?></label>
        <select class="form-control col-sm-7" name="_show_tti">
         
          <option value="0" <?php if(isset($_show_tti)): ?><?php if($_show_tti==0): ?> selected <?php endif; ?> <?php endif; ?>>NO</option>
          <option value="1" <?php if(isset($_show_tti)): ?><?php if($_show_tti==1): ?> selected <?php endif; ?> <?php endif; ?>>YES</option>
        </select>
      </div>
      <div class="form-group row">
        <label for="_show_expected_qty" class="col-sm-5 col-form-label"><?php echo e(__('label._show_expected_qty')); ?></label>
        <select class="form-control col-sm-7" name="_show_expected_qty">
         
          <option value="0" <?php if(isset($_show_expected_qty)): ?><?php if($_show_expected_qty==0): ?> selected <?php endif; ?> <?php endif; ?>>NO</option>
          <option value="1" <?php if(isset($_show_expected_qty)): ?><?php if($_show_expected_qty==1): ?> selected <?php endif; ?> <?php endif; ?>>YES</option>
        </select>
      </div>









      <div class="form-group row">
        <label for="_show_short_note" class="col-sm-5 col-form-label">Show Short Note</label>
        <select class="form-control col-sm-7" name="_show_short_note">
         
          <option value="0" <?php if(isset($_show_short_note)): ?><?php if($_show_short_note==0): ?> selected <?php endif; ?> <?php endif; ?>>NO</option>
          <option value="1" <?php if(isset($_show_short_note)): ?><?php if($_show_short_note==1): ?> selected <?php endif; ?> <?php endif; ?>>YES</option>
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
          <option value="0" <?php if(isset($_show_self)): ?><?php if($_show_self==0): ?> selected <?php endif; ?> <?php endif; ?>>NO</option>
          <option value="1" <?php if(isset($_show_self)): ?><?php if($_show_self==1): ?> selected <?php endif; ?> <?php endif; ?>>YES</option>
        </select>
      </div>
      <div class="form-group row">
        <label for="_show_manufacture_date" class="col-sm-5 col-form-label">Use Manufacture Date</label>
        <select class="form-control col-sm-7" name="_show_manufacture_date">
          <option value="0" <?php if(isset($form_settings->_show_manufacture_date)): ?><?php if($form_settings->_show_manufacture_date==0): ?> selected <?php endif; ?> <?php endif; ?>>NO</option>
          <option value="1" <?php if(isset($form_settings->_show_manufacture_date)): ?><?php if($form_settings->_show_manufacture_date==1): ?> selected <?php endif; ?> <?php endif; ?>>YES</option>
        </select>
      </div>
      <div class="form-group row">
        <label for="_show_expire_date" class="col-sm-5 col-form-label">Use Expired Date</label>
        <select class="form-control col-sm-7" name="_show_expire_date">
          <option value="0" <?php if(isset($form_settings->_show_expire_date)): ?><?php if($form_settings->_show_expire_date==0): ?> selected <?php endif; ?> <?php endif; ?>>NO</option>
          <option value="1" <?php if(isset($form_settings->_show_expire_date)): ?><?php if($form_settings->_show_expire_date==1): ?> selected <?php endif; ?> <?php endif; ?>>YES</option>
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
      </div><?php /**PATH D:\xampp\htdocs\own\inv-acc-hrm\resources\views/backend/import-purchase/form_setting_modal.blade.php ENDPATH**/ ?>