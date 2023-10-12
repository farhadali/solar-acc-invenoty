
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
        <label for="_show_unit" class="col-sm-5 col-form-label">Show Unit</label>
        <select class="form-control col-sm-7" name="_show_unit">
         
          <option value="0" <?php if(isset($form_settings->_show_unit)): ?><?php if($form_settings->_show_unit==0): ?> selected <?php endif; ?> <?php endif; ?>>NO</option>
          <option value="1" <?php if(isset($form_settings->_show_unit)): ?><?php if($form_settings->_show_unit==1): ?> selected <?php endif; ?> <?php endif; ?>>YES</option>
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
        <label for="_show_payment_terms" class="col-sm-5 col-form-label">Show Payment Terms</label>
        <select class="form-control col-sm-7" name="_show_payment_terms">
         
          <option value="0" <?php if(isset($form_settings->_show_payment_terms)): ?><?php if($form_settings->_show_payment_terms==0): ?> selected <?php endif; ?> <?php endif; ?>>NO</option>
          <option value="1" <?php if(isset($form_settings->_show_payment_terms)): ?><?php if($form_settings->_show_payment_terms==1): ?> selected <?php endif; ?> <?php endif; ?>>YES</option>
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
        <label for="_show_warranty" class="col-sm-5 col-form-label"> Show Warranty</label>
        <select class="form-control col-sm-7" name="_show_warranty">
          <option value="0" <?php if(isset($form_settings->_show_warranty)): ?><?php if($form_settings->_show_warranty==0): ?> selected <?php endif; ?> <?php endif; ?>>NO</option>
          <option value="1" <?php if(isset($form_settings->_show_warranty)): ?><?php if($form_settings->_show_warranty==1): ?> selected <?php endif; ?> <?php endif; ?>>YES</option>
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
        <label for="_show_due_history" class="col-sm-5 col-form-label">Show Due Invoice</label>
        <select class="form-control col-sm-7" name="_show_due_history">
          <option value="0" <?php if(isset($form_settings->_show_due_history)): ?><?php if($form_settings->_show_due_history==0): ?> selected <?php endif; ?> <?php endif; ?>>NO</option>
          <option value="1" <?php if(isset($form_settings->_show_due_history)): ?><?php if($form_settings->_show_due_history==1): ?> selected <?php endif; ?> <?php endif; ?>>YES</option>
        </select>
      </div>
      
      <div class="form-group row">
        <label for="_invoice_template" class="col-sm-5 col-form-label">Invoice Template</label>
        <select class="form-control col-sm-7" name="_invoice_template">
          <option value="1" <?php if(isset($form_settings->_invoice_template)): ?><?php if($form_settings->_invoice_template==1): ?> selected <?php endif; ?> <?php endif; ?>>Template A</option>
          <option value="2" <?php if(isset($form_settings->_invoice_template)): ?><?php if($form_settings->_invoice_template==2): ?> selected <?php endif; ?> <?php endif; ?>>Template B</option>
          <option value="3" <?php if(isset($form_settings->_invoice_template)): ?><?php if($form_settings->_invoice_template==3): ?> selected <?php endif; ?> <?php endif; ?>>Template C</option>
          <option value="4" <?php if(isset($form_settings->_invoice_template)): ?><?php if($form_settings->_invoice_template==4): ?> selected <?php endif; ?> <?php endif; ?>>Template D</option>
          <option value="6" <?php if(isset($form_settings->_invoice_template)): ?><?php if($form_settings->_invoice_template==6): ?> selected <?php endif; ?> <?php endif; ?>>Template E</option>
          <option value="5" <?php if(isset($form_settings->_invoice_template)): ?><?php if($form_settings->_invoice_template==5): ?> selected <?php endif; ?> <?php endif; ?>>Pos Template</option>
        </select>
      </div>

      <?php
      $_string_ids = $form_settings->_cash_customer ?? 0;
      if($_string_ids !=0){
        $_cash_customer = explode(",",$_string_ids);
      }else{
        $_cash_customer =[];
      }
      ?>
      <div class="form-group row">
        <label for="_cash_customer" class="col-sm-4 col-form-label">Cash Customer</label>
        <select class="form-control col-sm-8 select2" name="_cash_customer[]" multiple size="8">
          <?php $__empty_1 = true; $__currentLoopData = $_cash_customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
          <option value="<?php echo e($value->id); ?>" <?php if(in_array($value->id,$_cash_customer)): ?> selected <?php endif; ?> ><?php echo e($value->_name ?? ''); ?></option>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
          <?php endif; ?>
         
        </select>
      </div>
      <br><br><br>
      <div class="form-group row">
        <label for="_defaut_customer" class="col-sm-4 col-form-label">Default Customer</label>
        <select class="form-control col-sm-8 " name="_defaut_customer"  >
            <option value="">Select Customer</option>
          <?php $__empty_1 = true; $__currentLoopData = $_cash_customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
          <option value="<?php echo e($value->id); ?>" <?php if($value->id==$form_settings->_defaut_customer): ?> selected <?php endif; ?> ><?php echo e($value->_name ?? ''); ?></option>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
          <?php endif; ?>
         
        </select>
      </div>
      <div class="form-group row">
        <label for="_is_header" class="col-sm-5 col-form-label">Invoice Header Show</label>
        <select class="form-control col-sm-7" name="_is_header">
          <option value="0" <?php if(isset($form_settings->_is_header)): ?><?php if($form_settings->_is_header==0): ?> selected <?php endif; ?> <?php endif; ?>>NO</option>
          <option value="1" <?php if(isset($form_settings->_is_header)): ?><?php if($form_settings->_is_header==1): ?> selected <?php endif; ?> <?php endif; ?>>YES</option>
        </select>
      </div>
      <div class="form-group row">
        <label for="_is_footer" class="col-sm-5 col-form-label">Invoice Footer Show</label>
        <select class="form-control col-sm-7" name="_is_footer">
          <option value="0" <?php if(isset($form_settings->_is_footer)): ?><?php if($form_settings->_is_footer==0): ?> selected <?php endif; ?> <?php endif; ?>>NO</option>
          <option value="1" <?php if(isset($form_settings->_is_footer)): ?><?php if($form_settings->_is_footer==1): ?> selected <?php endif; ?> <?php endif; ?>>YES</option>
        </select>
      </div>
      <div class="form-group row">
        <label for="_margin_top" class="col-sm-5 col-form-label">Invoice Margin top</label>
         <input id="_margin_top" class="form-control col-sm-7" type="text" name="_margin_top" value="<?php if(isset($form_settings->_margin_top)): ?><?php echo e($form_settings->_margin_top); ?> <?php endif; ?>">
      </div>
      <div class="form-group row">
        <label for="_margin_bottom" class="col-sm-5 col-form-label">Invoice Margin Bottom</label>
         <input id="_margin_bottom" class="form-control col-sm-7" type="text" name="_margin_bottom" value="<?php if(isset($form_settings->_margin_bottom)): ?><?php echo e($form_settings->_margin_bottom); ?> <?php endif; ?>">
      </div>
      <div class="form-group row">
        <label for="_margin_left" class="col-sm-5 col-form-label">Invoice Margin Left</label>
         <input id="_margin_left" class="form-control col-sm-7" type="text" name="_margin_left" value="<?php if(isset($form_settings->_margin_left)): ?><?php echo e($form_settings->_margin_left); ?> <?php endif; ?>">
      </div>
      <div class="form-group row">
        <label for="_margin_right" class="col-sm-5 col-form-label">Invoice Margin Right</label>
         <input id="_margin_right" class="form-control col-sm-7" type="text" name="_margin_right" value="<?php if(isset($form_settings->_margin_right)): ?><?php echo e($form_settings->_margin_right); ?> <?php endif; ?>">
      </div>
      <div class="form-group row">
        <label for="_seal_image" class="col-sm-5 col-form-label">Authorised Signature</label>
        <div class="col-sm-7">
          <input id="_seal_image" class="form-control " type="file" name="_seal_image" value="<?php if(isset($form_settings->_seal_image)): ?><?php echo e($form_settings->_seal_image); ?> <?php endif; ?>" onchange="loadFile(event,1 )">
         
         <img id="output_1" class="banner_image_create" src="<?php echo e(asset('/')); ?><?php echo e($form_settings->_seal_image ?? ''); ?>"  style="max-height:100px;max-width: 100px; " />
        </div>
         
         
         
      </div>
      <br><br><br>

      <script type="text/javascript">
         $('.select2').select2()
      </script><?php /**PATH /home/sobuz23/sspf.sobuzsathitraders.com/resources/views/backend/sales/form_setting_modal.blade.php ENDPATH**/ ?>