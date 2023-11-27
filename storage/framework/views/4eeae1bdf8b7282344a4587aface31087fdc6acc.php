
<?php $__env->startSection('title',$page_name); ?>

<?php $__env->startSection('content'); ?>
<div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
           <div class="col-sm-12" style="display: flex;">
            <a class="m-0 _page_name" href="#"><?php echo $page_name ?? ''; ?> </a>
            <ol class="breadcrumb float-sm-right ml-2">
              
            </ol>
          </div>
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
   <div class="message-area">
    <?php echo $__env->make('backend.message.message', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-12">
            <div class="card">
              <div class="card-header border-0">
                 
                 
              </div>
              <div class="card-body">
                <div class="">
                  <table class="table table-bordered _list_table">
                      <thead>
                        <tr>
                         <th>Action</th>
                         <th>Sunday</th>
                         <th>Monday</th>
                         <th>Tuesday</th>
                         <th>Wednesday</th>
                         <th>Thursday</th>
                         <th>Friday</th>
                         <th>Saturday</th>
                      </tr>
                      </thead>
                      <tbody>
                       
                        <tr>
                            
                    <td style="display: flex;">
                           
                                <button  type="button" 
                                  attr_base_edit_url="<?php echo e(route('weekworkday.show',$data->id ?? 0)); ?>"
                                  data-toggle="modal" 
                                  data-target="#commonEntryModal_item" 
                                  class="btn btn-sm btn-default attr_base_edit_url mr-1"><i class="fa fa-eye"></i></button>


                                  <button  type="button" 
                                  attr_base_edit_url="<?php echo e(route('weekworkday.edit',$data->id ?? 0)); ?>"
                                  data-toggle="modal" 
                                  data-target="#commonEntryModal_item" 
                                  class="btn btn-sm btn-default attr_base_edit_url mr-1"><i class="fa fa-pen "></i></button>  
                               
                        </td>

                          <td><?php echo $data->_sunday ?? ''; ?></td>
                          <td><?php echo $data->_monday ?? ''; ?></td>
                          <td><?php echo $data->_tuesday ?? ''; ?></td>
                          <td><?php echo $data->_wednesday ?? ''; ?></td>
                          <td><?php echo $data->_thursday ?? ''; ?></td>
                          <td><?php echo $data->_friday ?? ''; ?></td>
                          <td><?php echo $data->_saturday ?? ''; ?></td>
                            
                           
                        </tr>
                        </tbody>
                    </table>
                </div>
                <!-- /.d-flex -->

                

                <div class="d-flex flex-row justify-content-end">
                 
                </div>
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
<?php echo $__env->make('backend.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\own\inv-acc-hrm\resources\views/hrm/week-work-day/index.blade.php ENDPATH**/ ?>