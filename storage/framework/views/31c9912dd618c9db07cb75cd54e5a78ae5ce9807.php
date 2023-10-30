
<?php $__env->startSection('title',$page_name); ?>

<?php $__env->startSection('content'); ?>
<?php
$__user= Auth::user();
?>
<div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-12" style="display: flex;">
            <a class="m-0 _page_name" href="<?php echo e(route('rlp.index')); ?>"><?php echo $page_name ?? ''; ?> </a>
            <ol class="breadcrumb float-sm-right ml-2">
               
             <li class="breadcrumb-item active">
                <a type="button" 
               class="btn btn-sm btn-info" 
              
               href="<?php echo e(route('rlp.create')); ?>">
                   <i class="nav-icon fas fa-plus"></i> <?php echo e(__('label.create_new')); ?>

                </a>

               </li>
              
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
                      
                    </div>
                    <div class="col-md-8">
                      <div class="d-flex flex-row justify-content-end">
                         
                        <li class="nav-item dropdown remove_from_header">
                              <a class="nav-link" data-toggle="dropdown" href="#">
                                <i class="fa fa-print " aria-hidden="true"></i> <i class="right fas fa-angle-down "></i>
                              </a>
                              <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                               <div class="dropdown-divider"></div>
                                <a target="__blank" href="<?php echo e($print_url_detal); ?>"  class="dropdown-item">
                                  <i class="fa fa-fax mr-2" aria-hidden="true"></i> Detail Print
                                </a>
                              
                                    
                            </li>
                             
                        
                          </div>
                    </div>
                  </div>
              </div>
              <div class="card-body">
                <div class="">
                  
                  <table class="table table-bordered _list_table">
                     <thead>
                        <tr>
                         <th class=""><b>##</b></th>
                         <th class=""><b><?php echo e(__('label.action')); ?></b></th>
                         <th class=""><b><?php echo e(__('label.id')); ?></b></th>
                         <th class=""><b><?php echo e(__('label.organization_id')); ?></b></th>
                         <th class=""><b><?php echo e(__('label._branch_id')); ?></b></th>
                         <th class=""><b><?php echo e(__('label._cost_center_id')); ?></b></th>
                         <th class=""><b><?php echo e(__('label.rlp_no')); ?></b></th>
                         <th class=""><b><?php echo e(__('label.request_date')); ?></b></th>
                         <th class=""><b><?php echo e(__('label._amount')); ?></b></th>
                         <th class=""><b><?php echo e(__('label._status')); ?></b></th>
                         <th class=""><b><?php echo e(__('label.user')); ?></b></th>
                      </tr>
                     </thead>
                     <tbody>
                      
                        <?php $__currentLoopData = $datas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        
                        <tr>
                            
                             <td style="display: flex;">
                              <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('rlp-delete')): ?>
                                 <?php echo Form::open(['method' => 'DELETE','route' => ['rlp.destroy', $data->id],'style'=>'display:inline']); ?>

                                      <button onclick="return confirm('Are you sure?')" type="submit" class="btn btn-sm btn-default"><i class="fa fa-trash _required"></i>  <?php echo e(__('label.trash')); ?></button>
                                  <?php echo Form::close(); ?>

                               <?php endif; ?> 
                              

                             <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('rlp-edit')): ?>
                                  <a  type="button" 
                                  href="<?php echo e(route('rlp.edit',$data->id)); ?>"
                                 
                                  class="btn btn-sm btn-default  mr-1"><i class="fa fa-pen "></i> <?php echo e(__('label.edit')); ?></a>
                              <?php endif; ?> 
                              <a target="__blank"  type="button" 
                                  href="<?php echo e(route('rlp.show',$data->id)); ?>"
                                  class="btn btn-sm btn-default  mr-1"><i class="fa fa-eye"> <?php echo e(__('label._details')); ?></i></a> 
                               
                            </td>

                            <td>
                               <a  type="button" 
                                  href="#None"
                                  attr_rlp_id="<?php echo e($data->id); ?>"
                                  attr_rlp_no="<?php echo e($data->rlp_no); ?>"
                                  attr_rlp_action="approve"
                                  attr_rlp_action_title="Approve"

                                 data-toggle="modal" data-target="#ApproveModal" data-whatever="@mdo"
                                  class="btn btn-sm btn-success approve_reject_revert_button  mr-1"><i class="fa fa-check "></i> <?php echo e(__('label.approve')); ?>

                                </a>
                            
                               <a  type="button" 
                                  href="#None"
                                  attr_rlp_id="<?php echo e($data->id); ?>"
                                  attr_rlp_no="<?php echo e($data->rlp_no); ?>"
                                  attr_rlp_action="reject"
                                  attr_rlp_action_title="Reject"

                                 data-toggle="modal" data-target="#ApproveModal" data-whatever="@mdo"
                                 
                                  class="btn btn-sm btn-warning approve_reject_revert_button  mr-1"><i class="fa fa-trash "></i> <?php echo e(__('label.reject')); ?></a>
                            
                               <a  type="button" 
                                  href="#None"
                                  attr_rlp_id="<?php echo e($data->id); ?>"
                                  attr_rlp_no="<?php echo e($data->rlp_no); ?>"
                                  attr_rlp_action="revert"
                                  attr_rlp_action_title="Revert"

                                 data-toggle="modal" data-target="#ApproveModal" data-whatever="@mdo"
                                 
                                  class="btn btn-sm btn-info approve_reject_revert_button  mr-1"><i class="fa fa-undo "></i> <?php echo e(__('label.revert')); ?></a>
                            </td>
                            <td><?php echo e($data->id); ?></td>
                            <td><?php echo e($data->_organization->_name ?? ''); ?></td>
                            <td><?php echo e($data->_branch->_name ?? ''); ?></td>
                            <td><?php echo e($data->_cost_center->_name ?? ''); ?></td>
                            <td><?php echo e($data->rlp_no ?? ''); ?></td>
                            <td><?php echo e(_view_date_formate($data->request_date ?? '')); ?></td>
                            <td><?php echo e(_report_amount($data->totalamount ?? 0)); ?></td>
                           <td><?php echo e(selected_rlp_status($data->rlp_status ?? 0)); ?></td>
                            <td><?php echo e($data->_entry_by->name ?? ''); ?></td>
                           
                        </tr>
                        
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        

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
<div class="modal fade" id="ApproveModal" tabindex="-1" role="dialog" aria-labelledby="ApproveModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document" style="width:332px;margin: 0px auto;height: auto;">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="ApproveModalLabel">New message</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form>
          
          <div class="form-group">
            <label for="message-text" class="col-form-label"><?php echo e(__('label.rlp_remarks')); ?>:</label>
            <textarea cols="6"  class="form-control" id="message-text"></textarea>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Send message</button>
      </div>
    </div>
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


 $(document).on('click','.approve_reject_revert_button',function(){
  var rlp_id = $(this).attr('attr_rlp_id');
  var rlp_no = $(this).attr('attr_rlp_no');
  var attr_rlp_action = $(this).attr('attr_rlp_action');
  var attr_rlp_action_title = $(this).attr('attr_rlp_action_title');

  $("#ApproveModalLabel").html(attr_rlp_action_title);
 })

 

</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\own\inv-acc-hrm\resources\views/rlp-module/rlp/index.blade.php ENDPATH**/ ?>