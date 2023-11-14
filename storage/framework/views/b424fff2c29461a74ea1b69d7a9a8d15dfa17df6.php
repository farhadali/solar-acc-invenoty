
<?php $__env->startSection('title',$page_name); ?>

<?php $__env->startSection('content'); ?>
<style type="text/css">
  .invoice {
    background-color: #fff;
    border: none;
    position: relative;
}
</style>

  <div class="content ">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
          <div class="card">
            <div class="card-header">
                 <h4 class="text-center"><?php echo e($page_name ?? ''); ?></h4>
            </div>
 
         
            <div class="card-body filter_body" >
               <form  action="<?php echo e(url('master_vessel_wise_ligther_report')); ?>" method="GET">
                <?php echo csrf_field(); ?>
                

                    <div class="row">
                      <select class="form-control " name="import_invoice_no" required>
                        <option value=""><?php echo e(__('label.select')); ?></option>
                        <?php $__empty_1 = true; $__currentLoopData = $importInvoices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <option value="<?php echo e($val->id); ?>" <?php if(isset($request->import_invoice_no) && $request->import_invoice_no==$val->id): ?> selected <?php endif; ?> ><?php echo $val->_order_number; ?> || <?php echo $val->_mother_vessel->_name ?? ''; ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <?php endif; ?>
                      </select>
                    </div>
                     <div class="row mt-3">
                         <div class="col-xs-6 col-sm-6 col-md-6 ">
                            <button type="submit" class="btn btn-success submit-button form-control"><i class="fa fa-credit-card mr-2" aria-hidden="true"></i> Report</button>
                        </div>
                         <div class="col-xs-6 col-sm-6 col-md-6 ">
                                     <a href="<?php echo e(url('master_vessel_wise_ligther_report')); ?>" class="btn btn-danger form-control" title="Search Reset"><i class="fa fa-retweet mr-2"></i> </a>
                        </div>
                        <br><br>
                     </div>
                    <?php echo Form::close(); ?>

                
              </div>
          
          </div>


          <?php if(isset($request->import_invoice_no)): ?>

          <div class="row">
            <div class="_report_button_header" style="width:100%;">
                <a class="nav-link"  href="<?php echo e(url('import-report-dashboard')); ?>" role="button">
                      <i class="fa fa-arrow-left"></i>
                    </a>
             <a style="cursor: pointer;" class="nav-link"  title="Print" onclick="javascript:printDiv('printablediv')"><i class="fas fa-print"></i></a>
                  <a style="cursor: pointer;" onclick="fnExcelReport();" class="nav-link"  title="Excel Download" ><i class="fa fa-file-excel" aria-hidden="true"></i></a>
              </div>
      </div>
            <section class="invoice" id="printablediv">

              <table class="cewReportTable">
                  <thead>
                  <tr>
                   <th style="border:1px solid silver;" class="text-left" >Lighter<br>Sl.</th>
                   <th style="border:1px solid silver;" class="text-left" >Name of <br>Vessel</th>
                    <th style="border:1px solid silver;" class="text-left" >Capacity </th>
                    <th style="border:1px solid silver;" class="text-left" >Loading Point</th>
                    <th style="border:1px solid silver;" class="text-left" >Destination </th>
                    <th style="border:1px solid silver;" class="text-left" >Loading Date & Time </th>
                    <th style="border:1px solid silver;" class="text-left" >Arrival Date & Time </th>
                    <th style="border:1px solid silver;" class="text-left" >Discharge Date & Time </th>
                    <th style="border:1px solid silver;" class="text-left" >Approx. QTY as per<br>draft survey at CTG<br>(MT) </th>
                    <th style="border:1px solid silver;" class="text-left" >Discharge point<br>Weight Scale Weight<br>(MT)</th>
                    <th style="border:1px solid silver;" class="text-left" >Diffrence</th>
                  </tr>
                  
                  
                  </thead>
                  <tbody>

                    <?php $__empty_1 = true; $__currentLoopData = $datas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                    <td style="border:1px solid silver;" class="text-left" ><?php echo e(($key+1)); ?></td>
                    <td style="border:1px solid silver;" class="text-left" ><?php echo $data->_lighter_info->_name ?? ''; ?></td>
                    <td style="border:1px solid silver;" class="text-left" ><?php echo $data->_capacity ??  $data->_lighter_info->_capacity ?? ''; ?> </td>
                    <td style="border:1px solid silver;" class="text-left" ><?php echo e(_store_name($data->_loding_point)); ?></td>
                    <td style="border:1px solid silver;" class="text-left" ><?php echo e(_store_name($data->_unloading_point)); ?> </td>
                    <td style="border:1px solid silver;" class="text-left" ><?php echo e(_view_date_formate($data->_loading_date_time ?? '')); ?> </td>
                    <td style="border:1px solid silver;" class="text-left" ><?php echo e(_view_date_formate($data->_arrival_date_time ?? '')); ?> </td>
                    <td style="border:1px solid silver;" class="text-left" ><?php echo e(_view_date_formate($data->_discharge_date_time ?? '')); ?> </td>
                    <td style="border:1px solid silver;" class="text-right" ><?php echo _report_amount($data->_total_expected_qty ?? 0); ?></td>
                    <td style="border:1px solid silver;" class="text-right" ><?php echo _report_amount($data->_total_qty ?? 0); ?></td>
                    <td style="border:1px solid silver;" class="text-right" ><?php echo _report_amount(($data->_total_expected_qty ?? 0) - ($data->_total_qty ?? 0)); ?></td>
                  </tr>

                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <?php endif; ?>
                    </tbody>
                  </table>
            </section>
          </div>
          <?php endif; ?>
        </div>
        <!-- /.row -->
      </div>
    </div>  
</div>



<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>

<script type="text/javascript">

</script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\own\inv-acc-hrm\resources\views/import-report/master_vessel_wise_ligther_report.blade.php ENDPATH**/ ?>