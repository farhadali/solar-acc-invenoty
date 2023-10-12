
<?php $__env->startSection('title',$page_name); ?>

<?php $__env->startSection('content'); ?>
<?php
$__user= Auth::user();
?>
<div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-12" style="display: flex;">
            <a class="m-0 _page_name" href="<?php echo e(route('damage.index')); ?>"><?php echo $page_name ?? ''; ?> </a>

            <ol class="breadcrumb float-sm-right ml-2">
               <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('damage-create')): ?>
              <li class="breadcrumb-item active">
                        <a title="Add New" class="btn btn-info btn-sm" href="<?php echo e(route('damage.create')); ?>"> Add New </a>
               </li>
              <?php endif; ?>
            </ol>
          </div>
         
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
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
                    <div class="col-md-4">
                       <?php echo $__env->make('backend.damage.search', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    </div>
                    <div class="col-md-8">
                      <div class="d-flex flex-row justify-content-end">
                         <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('damage-print')): ?>
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
                         <?php echo $datas->render(); ?>

                          </div>
                    </div>
                  </div>
              </div>
              <div class="card-body">
                <div class="table-responsive">
                  
                  <table class="table table-bordered _list_table">
                      <thead>
                        <tr>
                         <th class=" _nv_th_action _action_big"><b>Action</b></th>
                         <th class=" _no"><b>ID</b></th>
                         <th class=""><b>Date</b></th>
                         <th class=""><b><?php echo e(__('label.organization')); ?></b></th>
                         <th class=""><b>Branch</b></th>
                         <th class=""><b>Referance</b></th>
                         <th class=""><b>Ledger</b></th>
                         <th class=""><b>Sub Total</b></th>
                         <th class=""><b>VAT</b></th>
                         <th class=""><b>Total</b></th>
                         <th class=""><b>User</b></th>
                         <th>Lock</th>
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
                            <div class="dropdown mr-1">
                                <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-expanded="false"> Action</button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                   <a class="dropdown-item "  href="<?php echo e(url('damage/print')); ?>/<?php echo e($data->id); ?>" >View  </a>
                                  <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('damage-edit')): ?>
                                    <a class="dropdown-item " href="<?php echo e(route('damage.edit',$data->id)); ?>">Edit</a>
                                  <?php endif; ?>
                                 
                                </div>
                              </div>
                       
                               
                                <a class="btn btn-sm btn-default _action_button" data-toggle="collapse" href="#collapseExample__<?php echo e($key); ?>" role="button" aria-expanded="false" aria-controls="collapseExample">
                                      <i class=" fas fa-angle-down"></i>
                                    </a>
                            </td>
                            <td><?php echo e($data->id); ?></td>
                            <td><?php echo e(_view_date_formate($data->_date ?? '')); ?> <?php echo e($data->_time); ?></td>
                            <td><?php echo e($data->_organization->_name ?? ''); ?></td>
                            <td><?php echo e($data->_master_branch->_name ?? ''); ?></td>
                            <td><?php echo e($data->_referance ?? ''); ?></td>
                            <td><?php echo e($data->_ledger->_name ?? ''); ?></td>
                            <td><?php echo e(_report_amount( $data->_sub_total ?? 0)); ?> </td>
                            <td><?php echo e(_report_amount( $data->_total_vat ?? 0)); ?> </td>
                            <td><?php echo e(_report_amount( $data->_total ?? 0)); ?> </td>
                            <td><?php echo e($data->_user_name ?? ''); ?></td>
                            <td style="display: flex;">
                              <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('lock-permission')): ?>
                              <input class="form-control _invoice_lock" type="checkbox" name="_lock" _attr_invoice_id="<?php echo e($data->id); ?>" value="<?php echo e($data->_lock); ?>" <?php if($data->_lock==1): ?> checked <?php endif; ?>>
                              <?php endif; ?>

                              <?php if($data->_lock==1): ?>
                              <i class="fa fa-lock _green ml-1 _icon_change__<?php echo e($data->id); ?>" aria-hidden="true"></i>
                              <?php else: ?>
                              <i class="fa fa-lock _required ml-1 _icon_change__<?php echo e($data->id); ?>" aria-hidden="true"></i>
                              <?php endif; ?>

                            </td>
                            
                           
                        </tr>
                        <?php if(sizeof($data->_master_details) > 0): ?>
                        <tr>
                          <td colspan="13" >
                           <div class="collapse" id="collapseExample__<?php echo e($key); ?>">
                            <div class="card " >
                              <table class="table">
                                <thead >
                                            <th class="text-left" >ID</th>
                                            <th class="text-left" >Item</th>
                                            <th class="text-middle" >Barcode</th>
                                            <th class="text-left" >Unit</th>
                                          
                                           
                                            <th class="text-middle" >Qty</th>
                                            <th class="text-middle" >Cost Rate</th>
                                            <th class="text-middle" > Rate</th>

                                            <?php if(isset($form_settings->_inline_discount)): ?> <?php if($form_settings->_inline_discount==1): ?>
                                            <th class="text-middle" >Dis%</th>
                                            <th class="text-middle" >Dis. Amount</th>
                                             <?php else: ?>
                                            <th class="text-middle display_none" >Dis.%</th>
                                            <th class="text-middle display_none" >Dis. Amount</th>
                                            <?php endif; ?>
                                            <?php endif; ?>
                                            <?php if(isset($form_settings->_show_vat)): ?> <?php if($form_settings->_show_vat==1): ?>
                                            <th class="text-middle" >VAT%</th>
                                            <th class="text-middle" >VAT</th>
                                             <?php else: ?>
                                            <th class="text-middle display_none" >VAT%</th>
                                            <th class="text-middle display_none" >VAT Amount</th>
                                            <?php endif; ?>
                                            <?php endif; ?>

                                            <th class="text-middle" >Value</th>
                                             <?php if(sizeof($permited_branch) > 1): ?>
                                            <th class="text-middle" >Branch</th>
                                            <?php else: ?>
                                            <th class="text-middle display_none" >Branch</th>
                                            <?php endif; ?>
                                             <?php if(sizeof($permited_costcenters) > 1): ?>
                                            <th class="text-middle" >Cost Center</th>
                                            <?php else: ?>
                                             <th class="text-middle display_none" >Cost Center</th>
                                            <?php endif; ?>
                                             <?php if(sizeof($store_houses) > 1): ?>
                                            <th class="text-middle" >Store</th>
                                            <?php else: ?>
                                             <th class="text-middle display_none" >Store</th>
                                            <?php endif; ?>
                                            <?php if(isset($form_settings->_show_self)): ?> <?php if($form_settings->_show_self==1): ?>
                                            <th class="text-middle" >Shelf</th>
                                            <?php else: ?>
                                             <th class="text-middle display_none" >Shelf</th>
                                            <?php endif; ?>
                                            <?php endif; ?>
                                           
                                          </thead>
                                <tbody>
                                  <?php
                                    $_value_total = 0;
                                    $_vat_total = 0;
                                    $_discount_total = 0;
                                    $_qty_total = 0;
                                  ?>
                                  <?php $__empty_1 = true; $__currentLoopData = $data->_master_details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item_key=>$_item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                  <tr>
                                     <td class="" ><?php echo e($_item->id); ?></td>
                                     <?php
                                      $_value_total +=$_item->_value ?? 0;
                                      $_vat_total += $_item->_vat_amount ?? 0;
                                      $_discount_total += $_item->_discount_amount ?? 0;
                                      $_qty_total += $_item->_qty ?? 0;
                                     ?>
                                            <td class="" ><?php echo $_item->_items->_name ?? ''; ?></td>
                                           <td>
                                               <?php
                                          $barcode_arrays = explode(',', $_item->_barcode ?? '');
                                          ?>
                                          <?php $__empty_2 = true; $__currentLoopData = $barcode_arrays; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $barcode): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                                        <span><?php echo e($barcode); ?></span><br>
                                          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                                          <?php endif; ?>
                                              </td>
                                            <td class="text-left" ><?php echo $_item->_trans_unit->_name ?? ''; ?></td>
                                            <td class="text-right" ><?php echo $_item->_qty ?? 0; ?></td>
                                            <td class="text-right" ><?php echo _report_amount($_item->_rate ?? 0); ?></td>
                                            <td class="text-right" ><?php echo _report_amount($_item->_sales_rate ?? 0); ?></td>
                                             <?php if(isset($form_settings->_inline_discount)): ?> <?php if($form_settings->_inline_discount==1): ?>
                                            <td class="text-right" ><?php echo $_item->_discount ?? 0; ?></td>
                                            <td class="text-right" ><?php echo _report_amount($_item->_discount_amount ?? 0); ?></td>
                                             <?php else: ?>
                                            <td class="text-right display_none" ><?php echo $_item->_discount ?? 0; ?></td>
                                            <td class="text-right display_none" ><?php echo _report_amount($_item->_discount_amount ?? 0); ?></td>
                                            <?php endif; ?>
                                            <?php endif; ?>
                                            <?php if(isset($form_settings->_show_vat)): ?> <?php if($form_settings->_show_vat==1): ?>
                                            <td class="text-right" ><?php echo $_item->_vat ?? 0; ?></td>
                                            <td class="text-right" ><?php echo _report_amount($_item->_vat_amount ?? 0); ?></td>
                                             <?php else: ?>
                                            <td class="text-right display_none" ><?php echo $_item->_vat ?? 0; ?></td>
                                            <td class="text-right display_none" ><?php echo _report_amount($_item->_vat_amount ?? 0); ?></td>
                                            <?php endif; ?>
                                            <?php endif; ?>

                                            <td class="text-right" ><?php echo _report_amount($_item->_value ?? 0); ?></td>
                                             <?php if(sizeof($permited_branch) > 1): ?>
                                            <td class="" ><?php echo $_item->_detail_branch->_name ?? ''; ?></td>
                                            <?php else: ?>
                                            <td class=" display_none" ><?php echo $_item->_detail_branch->_name ?? ''; ?></td>
                                            <?php endif; ?>
                                             <?php if(sizeof($permited_costcenters) > 1): ?>
                                            <td class="" ><?php echo $_item->_detail_cost_center->_name ?? ''; ?></td>
                                            <?php else: ?>
                                             <td class=" display_none" ><?php echo $_item->_detail_cost_center->_name ?? ''; ?></td>
                                            <?php endif; ?>
                                             <?php if(sizeof($store_houses) > 1): ?>
                                            <td class="" ><?php echo $_item->_store->_name ?? ''; ?></td>
                                            <?php else: ?>
                                             <td class=" display_none" ><?php echo $_item->_store->_name ?? ''; ?></td>
                                            <?php endif; ?>
                                            <?php if(isset($form_settings->_show_self)): ?> <?php if($form_settings->_show_self==1): ?>
                                            <td class="" ><?php echo $_item->_store_salves_id ?? ''; ?></td>
                                            <?php else: ?>
                                             <td class=" display_none" ><?php echo $_item->_store_salves_id ?? ''; ?></td>
                                            <?php endif; ?>
                                            <?php endif; ?>
                                           
                                          </thead>
                                  </tr>
                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                  <?php endif; ?>
                                </tbody>
                                <tfoot>
                                  <tr>
                                              <td></td>
                                              <td></td>
                                              <td colspan="2"  class="text-right"><b>Total</b></td>
                                              
                                              <td  class="text-right"></td>
                                              
                                              <td class="text-right">
                                                <b><?php echo e($_qty_total ?? 0); ?></b>
                                                


                                              </td>
                                              <td></td>
                                              <td></td>
                                              <?php if(isset($form_settings->_inline_discount)): ?> <?php if($form_settings->_inline_discount==1): ?>
                                              <td></td>
                                              <td class="text-right">
                                                <b><?php echo e(_report_amount($_discount_total ?? 0)); ?></b>
                                              </td>
                                              <?php else: ?>
                                              <td class="display_none"></td>
                                              <td class="text-right display_none">
                                                 <b><?php echo e(_report_amount($_discount_total ?? 0)); ?></b>
                                              </td>
                                              <?php endif; ?>
                                              <?php endif; ?>
                                              <?php if(isset($form_settings->_show_vat)): ?> <?php if($form_settings->_show_vat==1): ?>
                                              <td></td>
                                              <td class="text-right">
                                                <b><?php echo e(_report_amount($_vat_total ?? 0)); ?></b>
                                              </td>
                                              <?php else: ?>
                                              <td class="display_none"></td>
                                              <td class="text-right display_none">
                                                 <b><?php echo e(_report_amount($_vat_total ?? 0)); ?></b>
                                              </td>
                                              <?php endif; ?>
                                              <?php endif; ?>
                                              <td class="text-right">
                                               <b> <?php echo e(_report_amount($_value_total ?? 0)); ?></b>
                                              </td>
                                              <?php if(sizeof($permited_branch) > 1): ?>
                                              <td></td>
                                              <?php else: ?>
                                               <td class="display_none"></td>
                                              <?php endif; ?>
                                              <?php if(sizeof($permited_costcenters) > 1): ?>
                                              <td></td>
                                              <?php else: ?>
                                               <td class="display_none"></td>
                                              <?php endif; ?>
                                              <?php if(sizeof($store_houses) > 1): ?>
                                              <td></td>
                                              <?php else: ?>
                                               <td class="display_none"></td>
                                              <?php endif; ?>

                                              <?php if(isset($form_settings->_show_self)): ?> <?php if($form_settings->_show_self==1): ?>
                                              <td></td>
                                              <?php else: ?>
                                              <?php endif; ?>
                                              <td class="display_none"></td>
                                              <?php endif; ?>
                                            </tr>
                                </tfoot>
                              </table>
                            </div>
                          </div>
                        </td>
                        </tr>
                        <?php endif; ?>
                        
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                          <td colspan="7" class="text-center"><b>Total</b></td>
                          <td><b><?php echo e(_report_amount($sum_of_sub_total)); ?> </b></td>
                          <td></td>
                          <td><b><?php echo e(_report_amount($sum_of_amount)); ?> </b></td>
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
    var _table_name ="damage_adjustments";
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
<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\own\inv-acc-hrm\resources\views/backend/damage/index.blade.php ENDPATH**/ ?>