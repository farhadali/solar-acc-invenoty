@extends('backend.layouts.app')
@section('title',$page_name)

@section('content')

  <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
          <div class="card">
            <div class="card-header">
                 <h4 class="text-center">{{ $page_name ?? '' }}</h4>
            </div>
          
         
            <div class="card-body filter_body" >
               <form  action="{{url('ledger-report-show')}}" method="GET">
                @csrf
                    <div class="row">
                      <label>Start Date:</label>
                        <div class="input-group date" id="reservationdate" data-target-input="nearest">
                                      <input type="text" name="_datex" class="form-control datetimepicker-input" data-target="#reservationdate" required @if(isset($previous_filter["_datex"])) value='{{$previous_filter["_datex"] }}' @endif />
                                      <div class="input-group-append" data-target="#reservationdate" data-toggle="datetimepicker">
                                          <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                      </div>
                                  </div>
                         @if(isset($previous_filter["_datex"]))
                              <input type="hidden" name="_old_filter" class="_old_filter" value="1">
                              @else
                              <input type="hidden" name="_old_filter" class="_old_filter" value="0">
                              @endif
                    </div>
                    <div class="row">
                      <label>End Date:</label>
                        <div class="input-group date" id="reservationdate_2" data-target-input="nearest">
                                      <input type="text" name="_datey" class="form-control datetimepicker-input_2" data-target="#reservationdate_2" required @if(isset($previous_filter["_datey"])) value='{{$previous_filter["_datey"] }}' @endif />
                                      <div class="input-group-append" data-target="#reservationdate_2" data-toggle="datetimepicker">
                                          <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                      </div>
                                  </div>
                    </div>
                    @include('basic.org_report')
                    <div class="@if(sizeof($permited_branch) == 1) display_none @endif">
                    <div class="row  ">
                      <label>Branch:</label><br> </div>
                     <div class="row ">
                         <select id="_branch_id" class="form-control _branch_id multiple_select" name="_branch_id[]" multiple size='2' >
                          @forelse($permited_branch as $branch )
                          <option value="{{$branch->id}}" @if(isset($previous_filter["_branch_id"])) 
                               @if(in_array($branch->id,$previous_filter["_branch_id"])) selected @endif
                               @endif >{{ $branch->id ?? '' }} - {{ $branch->_name ?? '' }}</option>
                          @empty
                          @endforelse
                         </select>
                     </div>
                   </div>
                    <div class="@if(sizeof($permited_costcenters) == 1) display_none @endif">
                    <div class="row  ">
                      <label>Cost Center:</label><br> </div>
                     <div class="row ">
                         <select class="form-control width_150_px _cost_center multiple_select" multiple name="_cost_center[]" size='2'  >
                                            
                            @forelse($permited_costcenters as $costcenter )
                            <option value="{{$costcenter->id}}" 
                              @if(isset($previous_filter["_cost_center"]))
                              @if(in_array($costcenter->id,$previous_filter["_cost_center"])) selected @endif
                                 @endif > {{ $costcenter->_name ?? '' }}</option>
                            @empty
                            @endforelse
                          </select>
                     </div>
                  </div>
                    <div class="row">
                      <label>Ledger:</label><br>
                      

                    </div>
                     <div class="row">
                         <input type="text" name="_search_ledger_id" class="form-control _search_ledger_id_ledger " placeholder="Ledger" required value='@if(isset($previous_filter["_search_ledger_id"])) {{$previous_filter["_search_ledger_id"]}} @endif'>
                          <input type="hidden" name="_ledger_id" class="form-control _ledger_id" value='@if(isset($previous_filter["_ledger_id"])) {{$previous_filter["_ledger_id"]}} @endif' >
                          <div class="search_box _filter_ledger_search_display" >
                            
                          </div>
                     </div>
                     <br>
                     <div class="row">
                         <div style="width: 100%;text-align: center;">
                          <div style="width: 33%;float: left;">
                            <input type="checkbox" name="_check_id" class="form-control" <?php echo (isset($previous_filter['_check_id'])?"value='y'":"value='n'")?>  <?php echo (isset($previous_filter['_check_id'])?"checked":"") ?> ><span>ID</span>
                          </div>
                          <div style="width: 33%;float: left;">
                            <input type="checkbox" name="short_naration" class="form-control" <?php echo (isset($previous_filter['short_naration'])?"value='y'":"value='n'")?>  <?php echo (isset($previous_filter['short_naration'])?"checked":"") ?> ><span>Short Naration</span>
                          </div>
                          <div style="width: 33%;float: left;">
                             <input type="checkbox" name="naration" class="form-control"<?php echo (isset($previous_filter['naration'])?"value='y'":"value='n'")?>  <?php echo (isset($previous_filter['naration'])?"checked":"") ?> ><span> Naration</span>
                          </div>
                          
                           
                         </div>
                         
                     </div>
                     <div class="row mt-5">
                         <div class="col-xs-6 col-sm-6 col-md-6 ">
                            <button type="submit" class="btn btn-success submit-button form-control"><i class="fa fa-credit-card mr-2" aria-hidden="true"></i> Report</button>
                        </div>
                         <div class="col-xs-6 col-sm-6 col-md-6 ">
                                     <a href="{{url('LedgerReportFilterReset')}}" class="btn btn-danger form-control" title="Search Reset"><i class="fa fa-retweet mr-2"></i> </a>
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
     

  })

$(document).on('keyup','._search_ledger_id_ledger',delay(function(e){
  var _gloabal_this = $(this);

  var _text_val = $(this).val().trim();
console.log($(this).val());

  var request = $.ajax({
      url: "{{url('ledger-search')}}",
      method: "GET",
      data: { _text_val : _text_val },
      dataType: "JSON"
    });
     
    request.done(function( result ) {

      var search_html =``;
      
      var data = result.data; 
      console.log(data)
      if(data.length > 0 ){
        
            search_html +=`<div class="card"><table class="_filter_ledger_search_table">
                            <tbody>`;
                        for (var i = 0; i < data.length; i++) {
                         search_html += `<tr class="search_row _ledger_search_row" >
                                        <td>${data[i].id}
                                        <input type="hidden" name="_id_ledger" class="_id_ledger" value="${data[i].id}">
                                        </td><td>${data[i]._name}
                                        <input type="hidden" name="_name_leder" class="_name_leder" value="${data[i]._name}">
                                        </td></tr>`;
                        }                         
            search_html += ` </tbody> </table></div>`;
      }else{
        search_html +=`<div class="card"><table style="width: 300px;"> 
        <thead><th colspan="3">No Data Found</th></thead><tbody></tbody></table></div>`;
      }     
      $(document).find('.search_box').html(search_html);
      $(document).find('.search_box').addClass('search_box_show').show();
      
    });
     
    request.fail(function( jqXHR, textStatus ) {
      alert( "Request failed: " + textStatus );
    });

  

}, 500));

  
 $(document).on('click','._ledger_search_row',function(){
  var _id = $(this).children('td').find('._id_ledger').val();
  var _name = $(this).find('._name_leder').val();
  $(document).find('._ledger_id').val(_id);
  var _id_name = `${_name} `;
  $(document).find('._search_ledger_id_ledger').val(_id_name);

  $('.search_box').hide();
  $('.search_box').removeClass('search_box_show').hide();
})

$(document).on('click',function(){
    var searach_show= $('.search_box').hasClass('search_box_show');
    if(searach_show ==true){
      $('.search_box').removeClass('search_box_show').hide();
    }
})

        

         

</script>
@endsection

