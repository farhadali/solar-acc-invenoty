<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo $__env->yieldContent('title'); ?></title>
  <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>" />
  <link rel="icon" type="image/x-icon" href="<?php echo e(asset('/')); ?><?php echo e($settings->logo ?? ''); ?>">

  
  <link rel="stylesheet" href="<?php echo e(asset('plugins/fontawesome-free/css/all.min.css')); ?>">
  <!-- daterange picker -->
  <link rel="stylesheet" href="<?php echo e(asset('plugins/daterangepicker/daterangepicker.css')); ?>">
  <!-- iCheck for checkboxes and radio inputs -->
  <link rel="stylesheet" href="<?php echo e(asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')); ?>">
  <!-- Bootstrap Color Picker -->
  <link rel="stylesheet" href="<?php echo e(asset('plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css')); ?>">
  <!-- Tempusdominus Bootstrap 4 -->
  <link rel="stylesheet" href="<?php echo e(asset('plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css')); ?>">
  <!-- Select2 -->
  <link rel="stylesheet" href="<?php echo e(asset('plugins/select2/css/select2.min.css')); ?>">
  <link rel="stylesheet" href="<?php echo e(asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')); ?>">
  <!-- Bootstrap4 Duallistbox -->
  <link rel="stylesheet" href="<?php echo e(asset('plugins/bootstrap4-duallistbox/bootstrap-duallistbox.min.css')); ?>">
  <!-- BS Stepper -->
  <link rel="stylesheet" href="<?php echo e(asset('plugins/bs-stepper/css/bs-stepper.min.css')); ?>">
  <!-- dropzonejs -->
  <link rel="stylesheet" href="<?php echo e(asset('plugins/dropzone/min/dropzone.min.css')); ?>">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo e(asset('dist/css/adminlte.min.css')); ?>">
<link rel="stylesheet" href="<?php echo e(asset('backend/amsify.suggestags.css')); ?>">
<link rel="stylesheet" href="<?php echo e(asset('backend/style.css?v=9')); ?>">



<link rel="stylesheet" href="<?php echo e(asset('backend/responsive.css')); ?>">

<script src="<?php echo e(asset('plugins/jquery/jquery.min.js')); ?>"></script>
<!-- Bootstrap 4 -->
<script src="<?php echo e(asset('plugins/bootstrap/js/bootstrap.bundle.min.js')); ?>"></script>
<!-- Select2 -->
<script src="<?php echo e(asset('plugins/select2/js/select2.full.min.js')); ?>"></script>
<!-- Bootstrap4 Duallistbox -->
<script src="<?php echo e(asset('plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js')); ?>"></script>
<!-- InputMask -->
<script src="<?php echo e(asset('plugins/moment/moment.min.js')); ?>"></script>
<script src="<?php echo e(asset('plugins/inputmask/jquery.inputmask.min.js')); ?>"></script>
<!-- date-range-picker -->
<script src="<?php echo e(asset('plugins/daterangepicker/daterangepicker.js')); ?>"></script>
<!-- bootstrap color picker -->
<script src="<?php echo e(asset('plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js')); ?>"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="<?php echo e(asset('plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js')); ?>"></script>
<!-- Bootstrap Switch -->
<script src="<?php echo e(asset('plugins/bootstrap-switch/js/bootstrap-switch.min.js')); ?>"></script>
<!-- BS-Stepper -->
<script src="<?php echo e(asset('plugins/bs-stepper/js/bs-stepper.min.js')); ?>"></script>
<!-- dropzonejs -->
<script src="<?php echo e(asset('plugins/dropzone/min/dropzone.min.js')); ?>"></script>
<!-- AdminLTE App -->
<script src="<?php echo e(asset('dist/js/adminlte.min.js')); ?>"></script>
<script src="<?php echo e(asset('plugins/summernote/summernote-bs4.min.js')); ?>"></script>
<script src="<?php echo e(asset('backend/jquery.amsify.suggestags.js')); ?>"></script>


<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>



  <?php echo $__env->yieldContent('css'); ?>
  

  
</head>

<body class="hold-transition sidebar-mini sidebar-collapse" >
  <?php
$currentURL = URL::full();
  ?>

   
<div class="wrapper">
  <div id="spinner_div" >
        <span id="loading_text">Loading...</span>
    </div>
  <div class="ajax_loader"><h5 class="loading_text">Loading.....</span></div>
    <div id="_notify_message_box"></div>
  <!-- Navbar -->
  <?php echo $__env->make('backend.layouts.coel_nav', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <?php echo $__env->make('backend.layouts.main_sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" style="">
    <input type="hidden" class="project_base_url" id="project_base_url" value="<?php echo e(url('/')); ?>">
    <input type="hidden" class="default_date_formate" id="default_date_formate" value="<?php echo e(default_date_formate()); ?>">
      <h5 style='text-align:center;color:#000;padding-top:10px;'>
    <?php
    $auth_user = \Auth::user();
 $permited_stores = permited_stores(explode(',',$auth_user->store_ids));
 if(sizeof($permited_stores)==1){
  foreach($permited_stores as $pkey=>$sval){ ?>
 <small>You are working on</small> <span style="font-weight: bold;color:#d61212"><?php echo e($sval->_name ?? ''); ?></span> 
<?php } } ?>
</h5>

    <!-- Main content -->
    <?php echo $__env->yieldContent('content'); ?>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

 

  <!-- Main Footer -->
 
  <?php echo $__env->make('backend.common-modal.common_modal_entry', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
  <?php echo $__env->make('backend.common-modal.item_ledger_modal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
   <?php echo $__env->make('backend.layouts.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
  
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->

<!-- jQuery -->





<!-- AdminLTE for demo purposes -->

<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<!-- AdminLTE for demo purposes -->
<script src="<?php echo e(asset('dist/js/demo.js')); ?>"></script>
<script src="<?php echo e(asset('backend/js/main.js')); ?>"></script>







<?php echo $__env->yieldContent('script'); ?>
</body>
</html>
<?php /**PATH D:\xampp\htdocs\own\inv-acc-hrm\resources\views/backend/layouts/app.blade.php ENDPATH**/ ?>