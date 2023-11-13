
<?php $__env->startSection('title',$page_name); ?>

<?php $__env->startSection('content'); ?>
<?php
$__user= Auth::user();
?>

    <?php echo $__env->make('backend.message.message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-12">
            <div class="card">
              <div class="card-header border-0 mt-1">
                <div class="row">
                   <?php

                     $currentURL = URL::full();
                     $current = URL::current();
                    if($currentURL === $current){
                       $print_url = $current."?print=single";
                       $print_url_detal = $current."?print=detail";
                    }else{
                         $print_url = $currentURL."&print=single";
                         $print_url_detal = $currentURL."&print=detail";
                    }
    

                   ?>
                    <div class="col-md-6">
                       <?php echo $__env->make('backend.import-material-receive.search', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    </div>
                    <div class="col-md-6">
                      <div class="d-flex flex-row justify-content-end">
                         <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('import-material-receive-print')): ?>
                        <li class="nav-item dropdown remove_from_header">
                              <a class="nav-link" data-toggle="dropdown" href="#">
                                
                                <i class="fa fa-print " aria-hidden="true"></i> <i class="right fas fa-angle-down "></i>
                              </a>
                              <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                               
                                <div class="dropdown-divider"></div>
                                
                                <a target="__blank" href="<?php echo e($print_url); ?>" class="dropdown-item">
                                  <i class="fa fa-print mr-2" aria-hidden="true"></i>Main  Print
                                </a>
                               <div class="dropdown-divider"></div>
                              
                                <a target="__blank" href="<?php echo e($print_url_detal); ?>"  class="dropdown-item">
                                  <i class="fa fa-fax mr-2" aria-hidden="true"></i> Detail Print
                                </a>
                              
                                    
                            </li>
                            <?php endif; ?>   
                            <div class="form-group ml-2">
                                <?php echo $datas->render(); ?>

                            </div>  
                            <div class="form-group ml-2">
                              <form action="" method="GET">
                                  <?php echo csrf_field(); ?>
                                <select name="limit" class="form-control" onchange="this.form.submit()">
                                        <?php $__empty_1 = true; $__currentLoopData = filter_page_numbers(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                         <option  <?php if($limit == $row): ?> selected <?php endif; ?>  value="<?php echo e($row); ?>"><?php echo e($row); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <?php endif; ?>
                                </select>
                              </form>
                              </div>
                             
                         
                          </div>
                    </div>
                  </div>
              </div>
              <div class="card-body">
                <div class="">

                  <table class="table table-bordered table-striped table-hover _list_table">
                    <thead>
                      <tr>
                         <th class=" _nv_th_action _action_big"><b>Action</b></th>
                         <th><?php echo e(__('label._lock')); ?></th>
                         <th class=" _no"><b><?php echo e(__('label.id')); ?></b></th>
                         
                         <?php if(sizeof($permited_organizations) > 1): ?>
                         <th class=""><b><?php echo e(__('label.organization')); ?></b></th>
                         <?php endif; ?>
                         <?php if(sizeof($permited_branch) > 1): ?>
                         <th class=""><b>Branch</b></th>
                         <?php endif; ?>
                         <?php if(sizeof($permited_costcenters) > 1): ?>
                         <th class=""><b><?php echo e(__('label._cost_center_id')); ?></b></th>
                         <?php endif; ?>
                         <th class=""><b><?php echo e(__('label._order_number')); ?></b></th>
                         <th class=""><b><?php echo e(__('label._date')); ?></b></th>
                         <th class=""><b>Order Ref</b></th>
                         <th class=""><b>Referance</b></th>
                         <th class=""><b>Ledger</b></th>
                         <th class=""><b>Total</b></th>
                         <th class=""><b>User</b></th>
                         <th class=""><b>Created At</b></th>
                         <th class=""><b>Updated At</b></th>
                         
                       

                      </tr>
                    </thead>
                    <tbody>
                      
                      <?php
                      $sum_of_amount=0;
                       $sum_of_sub_total=0;
                      ?>
                        <?php $__currentLoopData = $datas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                           $sum_of_amount += $data->_total ?? 0;
                           $sum_of_sub_total += $data->_sub_total ?? 0;
                        ?>

                       
                        <tr>
<td style="display: flex;">
                           
                                <a  
                                  href="<?php echo e(url('import-material-receive/print')); ?>/<?php echo e($data->id); ?>"
                                  class="btn btn-sm btn-default  mr-1"><i class="fa fa-eye"></i></a>


                                  <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('import-material-receive-edit')): ?>
                                  
                                  <a  href="<?php echo e(route('import-material-receive.edit',$data->id)); ?>" 
                                  
                                  class="btn btn-sm btn-default  mr-1"><i class="fa fa-pen "></i></a>
                                  <?php endif; ?>
                                  <div class="dropdown mr-1">
                                  <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-expanded="false">
                                   <i class="fa fa-print "></i>
                                  </button>
                                  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                     <a class="dropdown-item " href="<?php echo e(url('import-material-receive/print')); ?>/<?php echo e($data->id); ?>" >
                                         View & Print
                                      </a>
                                     
                                     <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('money-receipt-print')): ?>
                                    
                                        <a class="dropdown-item " href="<?php echo e(url('import-material-receive-money-receipt')); ?>/<?php echo e($data->id); ?>">
                                         Payment Receipt
                                        </a>
                                     
                                    <?php endif; ?>
                                  </div>
                                </div>
                                
                        </td>

                           <td>
                              
                             <div style="display: flex;">
                              <?php if($data->_lock==1): ?>
                              <i class="fa fa-lock _green mr-2 _icon_change__<?php echo e($data->id); ?>" aria-hidden="true"></i>
                              <?php else: ?>
                              <i class="fa fa-lock _required mr-2 _icon_change__<?php echo e($data->id); ?>" aria-hidden="true"></i>
                              <?php endif; ?>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('lock-permission')): ?>
                              <input class="form-control _invoice_lock" type="checkbox" name="_lock" _attr_invoice_id="<?php echo e($data->id); ?>" value="<?php echo e($data->_lock); ?>" <?php if($data->_lock==1): ?> checked <?php endif; ?>>
                              <?php endif; ?>

                              
                             </div>

                           </td>
                            <td><?php echo e($data->id); ?></td>
                           

                            <?php if(sizeof($permited_organizations) > 1): ?>
                         <td><?php echo e($data->_organization->_name ?? ''); ?></td>
                         <?php endif; ?>
                         <?php if(sizeof($permited_branch) > 1): ?>
                        <td><?php echo e($data->_master_branch->_name ?? ''); ?></td>
                         <?php endif; ?>
                         <?php if(sizeof($permited_costcenters) > 1): ?>
                         <td><?php echo e($data->_cost_center->_name ?? ''); ?></td>
                         <?php endif; ?>

                            <td><?php echo e($data->_order_number ?? ''); ?></td>
                             <td><?php echo e(_view_date_formate($data->_date ?? '')); ?> <?php echo e($data->_time ?? ''); ?></td>
                            <td><?php echo e($data->_order_ref_id ?? ''); ?></td>
                            <td><?php echo e($data->_referance ?? ''); ?></td>
                            <td><?php echo e($data->_ledger->_name ?? ''); ?></td>
                            <td><?php echo e(_report_amount( $data->_total ?? 0)); ?> </td>
                            <td><?php echo e($data->_user_name ?? ''); ?></td>
                            <td><?php echo e($data->created_at ?? ''); ?></td>
                            <td><?php echo e($data->updated_at ?? ''); ?></td>
                           
                            
                           
                        </tr>
                        

                       
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                          <td colspan="3"></td>
                          <?php if(sizeof($permited_organizations) > 1): ?>
                         <td></td>
                         <?php endif; ?>
                         <?php if(sizeof($permited_branch) > 1): ?>
                         <td></td>
                         <?php endif; ?>
                         <?php if(sizeof($permited_costcenters) > 1): ?>
                         <td></td>
                         <?php endif; ?>
                          <td colspan="5" class="text-center"><b>Total</b></td>
                          <td><b><?php echo e(_report_amount($sum_of_amount)); ?> </b></td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <!-- /.d-flex -->
                
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
 $(function () {
   var default_date_formate = `<?php echo e(default_date_formate()); ?>`
   var _datex = `<?php echo e($request->_datex ?? ''); ?>`
   var _datey = `<?php echo e($request->_datey ?? ''); ?>`
    
     $('#reservationdate_datex').datetimepicker({
        format:'L'
    });
     $('#reservationdate_datey').datetimepicker({
         format:'L'
    });
 
$(document).on("click","._single_data_click",function(){
      var has_class = $(this).hasClass("already_show")
      if(has_class){return false; }
        var invoice_id = $(this).attr("attr_invoice_id");
        var _attr_key = $(this).attr("_attr_key");
        $(this).addClass("already_show");
        $.ajaxSetup({ headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

        $.ajax({
           type:'POST',
           url:"<?php echo e(url('purchase-wise-detail')); ?>",
           data:{invoice_id,_attr_key},
           success:function(data){
            $(document).find("._single_data_display__"+invoice_id).html(data);
           }
        });
    })
 

// if(_datex =='' && _datey =='' ){
//   $(".datetimepicker-input_datex").val(date__today());
//   $(".datetimepicker-input_datey").val(date__today());
//   console.log('Ok new Page')
// }else{
//   $(".datetimepicker-input_datex").val(after_request_date__today( `<?php echo e($request->_datex); ?>` ))
//   $(".datetimepicker-input_datey").val(after_request_date__today( `<?php echo e($request->_datey); ?>` ))
//   console.log('after search')
// }

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


  

function after_request_date__today(_date){
            var data = _date.split('-');
            var yyyy =data[0];
            var mm =data[1];
            var dd =data[2];
            if(default_date_formate=='DD-MM-YYYY'){
              return (dd[1]?dd:"0"+dd[0]) +"-"+ (mm[1]?mm:"0"+mm[0])+"-"+ yyyy ;
            }
            if(default_date_formate=='MM-DD-YYYY'){
              return (mm[1]?mm:"0"+mm[0])+"-" + (dd[1]?dd:"0"+dd[0]) +"-"+  yyyy ;
            }
            

            
          }

});

 $(document).on('keyup','._search_main_ledger_id',delay(function(e){
    $(document).find('._search_main_ledger_id').removeClass('required_border');
    var _gloabal_this = $(this);
    var _text_val = $(this).val().trim();
    var _account_head_id = 13;

  var request = $.ajax({
      url: "<?php echo e(url('main-ledger-search')); ?>",
      method: "GET",
      data: { _text_val,_account_head_id },
      dataType: "JSON"
    });
     
    request.done(function( result ) {

      var search_html =``;
      var data = result.data; 
      if(data.length > 0 ){
            search_html +=`<div class="card"><table style="width: 300px;">
                            <tbody>`;
                        for (var i = 0; i < data.length; i++) {
                         search_html += `<tr class="search_row_ledger" >
                                        <td>${data[i].id}
                                        <input type="hidden" name="_id_main_ledger" class="_id_main_ledger" value="${data[i].id}">
                                        </td><td>${data[i]._name}
                                        <input type="hidden" name="_name_main_ledger" class="_name_main_ledger" value="${data[i]._name}">
                                  
                                   </td></tr>`;
                        }                         
            search_html += ` </tbody> </table></div>`;
      }else{
        search_html +=`<div class="card"><table style="width: 300px;"> 
        <thead><th colspan="3">No Data Found</th></thead><tbody></tbody></table></div>`;
      }     
      _gloabal_this.parent('div').find('.search_box_main_ledger').html(search_html);
      _gloabal_this.parent('div').find('.search_box_main_ledger').addClass('search_box_show').show();
      
    });
     
    request.fail(function( jqXHR, textStatus ) {
      alert( "Request failed: " + textStatus );
    });

  

}, 500));


  $(document).on("click",'.search_row_ledger',function(){
    var _id = $(this).children('td').find('._id_main_ledger').val();
    var _name = $(this).find('._name_main_ledger').val();
    $("._ledger_id").val(_id);
    $("._search_main_ledger_id").val(_name);

    $('.search_box_main_ledger').hide();
    $('.search_box_main_ledger').removeClass('search_box_show').hide();
  })
  
  $(document).on("click",'.search_modal',function(){
    $('.search_box_main_ledger').hide();
    $('.search_box_main_ledger').removeClass('search_box_show').hide();
  })


  $(document).on("click","._invoice_lock",function(){
    var _id = $(this).attr('_attr_invoice_id');
    var _table_name ="purchases";
   if($(this).is(':checked')){
            $(this).prop("selected", "selected");
          var _action = 1;
          $('._icon_change__'+_id).addClass('_green').removeClass('_required');
         
         
        } else {
          $(this).removeAttr("selected");
          var _action = 0;
            $('._icon_change__'+_id).addClass('_required').removeClass('_green');
           
        }
      _lock_action(_id,_action,_table_name)
       
  })



</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\own\inv-acc-hrm\resources\views/backend/import-material-receive/index.blade.php ENDPATH**/ ?>