

<?php $__env->startSection('title',$page_name); ?>

<?php $__env->startSection('content'); ?>
<style type="text/css">
 
  @media  print {
   .table th {
    vertical-align: top;
    color: #000;
    background-color: #fff; 
}
}
  </style>
<div class="_report_button_header">
    <a class="nav-link"  href="<?php echo e(url('hrm-employee')); ?>" role="button"><i class="fa fa-arrow-left"></i></a>
 <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('hrm-employee-edit')): ?>
 <a  href="<?php echo e(route('hrm-employee.edit',$data->id)); ?>" 
    class="nav-link "  title="Edit"  >
    <i class="nav-icon fas fa-edit"></i>
     </a>
  <?php endif; ?>
    <a style="cursor: pointer;" class="nav-link"  title="Print" onclick="javascript:printDiv('printablediv')"><i class="fas fa-print"></i></a>
      <a style="cursor: pointer;" onclick="fnExcelReport();" class="nav-link"  title="Excel Download" ><i class="fa fa-file-excel" aria-hidden="true"></i></a>
      <?php echo $__env->make('backend.message.message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
  </div>

<section class="invoice" id="printablediv">
    
    <div class="container-fluid">
     
    <!-- /.row -->
    <table class="table table-bordered">
      <tr>
         <tr> 
          <td colspan="6" class="text-center" style="border:none;"> <?php echo e($settings->_top_title ?? ''); ?><br>
            <b><?php echo e($settings->name ?? ''); ?></b><br/>
            <b><?php echo e($settings->_address ?? ''); ?><br>
              <b><?php echo e($settings->_phone ?? ''); ?></b><br><b><?php echo e($settings->_email ?? ''); ?></b><br><h3><?php echo e($page_name); ?> </h3></td> 
            </tr>
      </tr>
      <tr>
        <td style="width: 10%;"><?php echo e(__('label._code')); ?>:</td>
        <td style="width: 23%;"><?php echo e($data->_code ?? ''); ?></td>

        <td style="width: 10%;"><?php echo e(__('label._name')); ?>:</td>
        <td style="width: 23%;"><?php echo e($data->_name ?? ''); ?></td>

        <td style="width: 10%;"></td>
        <td style="width: 23%;"><img id="output_1" class="banner_image_create" src="<?php echo e(asset($data->_photo)); ?>"  style="max-height:100px;max-width: 100px; " /></td>
      </tr>
      <tr>
        <td style="width: 10%;"><?php echo e(__('label._father')); ?>:</td>
        <td style="width: 23%;"><?php echo e($data->_father ?? ''); ?></td>
        <td style="width: 10%;"><?php echo e(__('label._mother')); ?>:</td>
        <td style="width: 23%;"><?php echo e($data->_mother ?? ''); ?></td>
        <td style="width: 10%;"><?php echo e(__('label._spouse')); ?>:</td>
        <td style="width: 23%;"><?php echo e($data->_spouse ?? ''); ?></td>
      </tr>
      <tr>
        <td style="width: 10%;"><?php echo e(__('label._mobile1')); ?>:</td>
        <td style="width: 23%;"><?php echo e($data->_mobile1 ?? ''); ?></td>
        <td style="width: 10%;"><?php echo e(__('label._mobile2')); ?>:</td>
        <td style="width: 23%;"><?php echo e($data->_mobile2 ?? ''); ?></td>
        <td style="width: 10%;"><?php echo e(__('label._spousesmobile')); ?>:</td>
        <td style="width: 23%;"><?php echo e($data->_spousesmobile ?? ''); ?></td>
      </tr>
      <tr>
        <td style="width: 10%;"><?php echo e(__('label._nid')); ?>:</td>
        <td style="width: 23%;"><?php echo e($data->_nid ?? ''); ?></td>
        <td style="width: 10%;"><?php echo e(__('label._gender')); ?>:</td>
        <td style="width: 23%;"><?php echo e($data->_gender ?? ''); ?></td>
        <td style="width: 10%;"><?php echo e(__('label._bloodgroup')); ?>:</td>
        <td style="width: 23%;"><?php echo e($data->_bloodgroup ?? ''); ?></td>
      </tr>
      <tr>
        <td style="width: 10%;"><?php echo e(__('label._religion')); ?>:</td>
        <td style="width: 23%;"><?php echo e($data->_religion ?? ''); ?></td>
        <td style="width: 10%;"><?php echo e(__('label._dob')); ?>:</td>
        <td style="width: 23%;"><?php echo e($data->_dob ?? ''); ?></td>
        <td style="width: 10%;"><?php echo e(__('label._education')); ?>:</td>
        <td style="width: 23%;"><?php echo e($data->_education ?? ''); ?></td>
      </tr>
      <tr>
        <td style="width: 10%;"><?php echo e(__('label._email')); ?>:</td>
        <td style="width: 23%;"><?php echo e($data->_email ?? ''); ?></td>
        <td style="width: 10%;"><?php echo e(__('label._bank')); ?>:</td>
        <td style="width: 23%;"><?php echo e($data->_bank ?? ''); ?></td>
        <td style="width: 10%;"><?php echo e(__('label._bankac')); ?>:</td>
        <td style="width: 23%;"><?php echo e($data->_bankac ?? ''); ?></td>
      </tr>
      <tr>
        <td style="width: 10%;"><?php echo e(__('label.organization_id')); ?>:</td>
        <td style="width: 23%;"><?php echo e($data->organization->_name ?? ''); ?></td>
        <td style="width: 10%;"><?php echo e(__('label.Branch')); ?>:</td>
        <td style="width: 23%;"><?php echo e($data->_branch->_name ?? ''); ?></td>
        <td style="width: 10%;"><?php echo e(__('label._cost_center_id')); ?>:</td>
        <td style="width: 23%;"><?php echo e($data->_cost_center->_name ?? ''); ?></td>
      </tr>
      <tr>
        <td style="width: 10%;"><?php echo e(__('label.employee_category_id')); ?>:</td>
        <td style="width: 23%;"><?php echo e($data->_employee_cat->_name ?? ''); ?></td>
        <td style="width: 10%;"><?php echo e(__('label._department_id')); ?>:</td>
        <td style="width: 23%;"><?php echo e($data->_emp_department->_name ?? ''); ?></td>
        <td style="width: 10%;"><?php echo e(__('label._jobtitle_id')); ?>:</td>
        <td style="width: 23%;"><?php echo e($data->_emp_designation->_name ?? ''); ?></td>
      </tr>
      <tr>
        <td style="width: 10%;"><?php echo e(__('label._grade_id')); ?>:</td>
        <td style="width: 23%;"><?php echo e($data->_emp_grade->_name ?? ''); ?></td>
        <td style="width: 10%;"><?php echo e(__('label._location')); ?>:</td>
        <td style="width: 23%;"><?php echo e($data->_emp_location->_name ?? ''); ?></td>
        <td style="width: 10%;"><?php echo e(__('label._status')); ?>:</td>
        <td style="width: 23%;"><?php echo e(selected_status($data->_status)); ?></td>
      </tr>
      
      
    </table>
    
    

    

    
    </div>
  </section>


<!-- Page specific script -->

<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\own\inv-acc-hrm\resources\views/hrm/hrm-employee/show.blade.php ENDPATH**/ ?>