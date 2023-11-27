@extends('backend.layouts.app')
@section('title',$page_name)

@section('content')

  <div class="content ">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
          <div class="card">
            <div class="card-header">
                <h4 class="text-center">{{ $page_name ?? '' }}</h4>
                 @include('backend.message.message')
            </div>
          
         
            <div class="card-body filter_body" >
               <form  action="{{url('report-shortage-item')}}" method="POST">
                @csrf
                    <div class="row">
                      @include('basic.org_report')

                    @if(sizeof($permited_branch) > 1)
                      <div class="col-md-12">
                        <label>Branch:</label>
                        <select id="_branch_id" class="form-control _branch_id multiple_select" name="_branch_id[]" multiple size='2' >
                          @forelse($permited_branch as $branch )
                          <option value="{{$branch->id}}" 
                            @if(isset($previous_filter["_branch_id"])) 
                               @if(in_array($branch->id,$previous_filter["_branch_id"])) selected @endif
                               @endif
                             >{{ $branch->id ?? '' }} - {{ $branch->_name ?? '' }}</option>
                          @empty
                          @endforelse
                         </select>
                      </div>
                  @endif
                
                      <div class="col-md-12">
                        <label>Store:</label>
                         <select class="form-control width_150_px _store multiple_select" multiple name="_store[]" size='2' required >
                                            
                            @forelse($stores as $store )
                            <option value="{{$store->id}}" 
                              @if(isset($previous_filter["_store"]))
                              @if(in_array($store->id,$previous_filter["_store"])) selected @endif
                                 @endif
                              > {{ $store->_name ?? '' }}</option>
                            @empty
                            @endforelse
                          </select>
                      </div>
                   
                  
                      <div class="col-md-12">
                        <label>Cost Center:</label>
                         <select class="form-control width_150_px _cost_center multiple_select" multiple name="_cost_center[]" size='2'  >
                                            
                            @forelse($permited_costcenters as $costcenter )
                            <option value="{{$costcenter->id}}" 
                              @if(isset($previous_filter["_cost_center"]))
                              @if(in_array($costcenter->id,$previous_filter["_cost_center"])) selected @endif
                                 @endif
                              > {{ $_category->_parents->_name ?? '' }}/{{ $costcenter->_name ?? '' }}</option>
                            @empty
                            @endforelse
                          </select>
                      </div>
                 
                    </div>
                    <div class="row">
                      <label>Categories:<span class="_required">*</span></label><br>
                        <select class="form-control width_150_px _item_category multiple_select" multiple name="_item_category[]" size='6'  >
                                            
                            @forelse($_item_categories as $_category )
                            <option value="{{$_category->id}}" 
                              @if(isset($previous_filter["_item_category"]))
                              @if(in_array($_category->id,$previous_filter["_item_category"])) selected @endif
                                 @endif
                              > {{ $_category->_name ?? '' }}</option>
                            @empty
                            @endforelse
                          </select>
                      
                    </div>
                    <div class="row">
                      <label>Items:</label><br></div>
                     <div class="row">
                         <select id="_item_id" class="form-control  _item_id multiple_select" multiple name="_item_id[]"  size='6' >
                          @if(isset($request->_item_id))

                           
                          @endif
                         </select>
                     </div>

                     <div class="row mt-3">
                         <div class="col-xs-6 col-sm-6 col-md-6 ">
                            <button type="submit" class="btn btn-success submit-button form-control"><i class="fa fa-credit-card mr-2" aria-hidden="true"></i> Report</button>
                        </div>
                         <div class="col-xs-6 col-sm-6 col-md-6 ">
                                     <a href="{{url('reset-expired-item')}}" class="btn btn-danger form-control" title="Search Reset"><i class="fa fa-retweet mr-2"></i> </a>
                        </div>
                        <br><br>
                     </div>
                    {!! Form::close() !!}
                
              </div>
          
          </div>
        </div>
        <!-- /.row -->
      </div>
    </div>  
</div>



@endsection

@section('script')

<script type="text/javascript">


 
    $(function () {

     var default_date_formate = `{{default_date_formate()}}`
    
     $('#reservationdate').datetimepicker({
        format:default_date_formate
    });

     $('#reservationdate_2').datetimepicker({
        format:default_date_formate
    });

     var _old_filter = $(document).find("._old_filter").val();
     if(_old_filter==0){
        $(".datetimepicker-input").val(date__today())
        $(".datetimepicker-input_2").val(date__today())
     }
     
     


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

    var _item_category_ids = $(document).find('._item_category').val();
    if(_item_category_ids.length > 0){
      _category_base_items(_item_category_ids);
    }

$(document).find('._item_category').on('change',function(){
    var _category_id = $(this).val();
    _category_base_items(_category_id);
    
  })

    function _category_base_items(_category_id){
      var request = $.ajax({
          url: "{{url('shortage-item-cat-item')}}",
          method: "GET",
          data: { _category_id : _category_id },
          dataType: "HTML"
        });
      request.done(function( result ) {
        $("#_item_id").html(result);
        });
         
        request.fail(function( jqXHR, textStatus ) {
         console.log(textStatus)
        });
    }
     

  })

  


        

         

</script>
@endsection

