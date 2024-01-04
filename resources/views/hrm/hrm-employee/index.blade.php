@extends('backend.layouts.app')
@section('title',$page_name)

@section('content')
@php
$__user= Auth::user();
$row_numbers = filter_page_numbers();


                     $currentURL = URL::full();
                     $current = URL::current();
                    if($currentURL === $current){
                       $print_url = $current."?print=single";
                       $print_url_detal = $current."?print=detail";
                    }else{
                         $print_url = $currentURL."&print=single";
                         $print_url_detal = $currentURL."&print=detail";
                    }
    

                   @endphp
<div class="nav_div">
  

  <nav class="second_nav" aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{url('home')}}">
      <i class="fa fa-credit-card nav-icon" aria-hidden="true"></i>
    </a></li>
    <li class="breadcrumb-item"><a href="{{ route('hrm-employee.index') }}">{{$page_name ?? ''}}</a></li>
    

    
  </ol>
  <ol class="breadcrumb print_tools color_info">
    <li class="breadcrumb-item" title="{{__('Print')}}">
     <a  href="{{ route('hrm-employee.create') }}"><i class="nav-icon fas fa-plus"></i> {{__('label.create_new')}}</a> 
    </li>
  </ol>
  <ol class="breadcrumb print_tools">
    <li class="breadcrumb-item" title="{{__('Search')}}">
      <a type="button"  data-toggle="modal" data-target="#modal-default" title="Advance Search"><i class="fa fa-search mr-2"></i> </a>
    </li>
    <li class="breadcrumb-item" title="{{__('Reset')}}">
      <a href="{{url('hrm-employee')}}" class="" title="Search Reset"><i class="fa fa-retweet mr-2"></i> </a>
    </li>
  </ol>
  <ol class="breadcrumb print_tools">
    <li class="breadcrumb-item" title="{{__('Search')}}">
      <form action="" method="GET">
                    @csrf
              <select name="limit" class="" onchange="this.form.submit()">
                      @forelse($row_numbers as $row)
                       <option  @if($limit == $row) selected @endif  value="{{ $row }}">{{$row}}</option>
                      @empty
                      @endforelse
              </select>
       </form>
    </li>
  </ol> 
  <ol class="breadcrumb print_tools">
    <li class="breadcrumb-item" title="{{__('Print')}}">
      <a target="__blank" href="{{$print_url_detal}}"  ><i class="fa fa-print mr-2"></i></a>
    </li>
  </ol>                                
</nav>
</div>

    @include('hrm.hrm-employee.search')
  <div class="form_div container-fluid">
           
                <div class="">
                  
                  <table class="table table-bordered _list_table">
                     <thead>
                        <tr>
                         <th class=""><b>##</b></th>
                         <th class=""><b>{{__('label.sl')}}</b></th>
                         <th class=""><b>{{__('label.id')}}</b></th>
                         <th class=""><b>{{__('label._name')}}</b></th>
                         <th class=""><b>{{__('label._code')}}</b></th>
                         <th class=""><b>{{__('label._mobile1')}}</b></th>
                         <th class=""><b>{{__('label._email')}}</b></th>
                         <th class=""><b>{{__('label.employee_category_id')}}</b></th>
                         <th class=""><b>{{__('label._department_id')}}</b></th>
                         <th class=""><b>{{__('label._jobtitle_id')}}</b></th>
                         <th class=""><b>{{__('label._grade_id')}}</b></th>
                         <th class=""><b>{{__('label.organization')}}</b></th>
                         <th class=""><b>{{__('label.Branch')}}</b></th>
                         <th class=""><b>{{__('label.Cost center')}}</b></th>
                         <th class=""><b>{{__('label._location')}}</b></th>
                         <th class=""><b>{{__('label._status')}}</b></th>
                         <th class=""><b>{{__('label.user')}}</b></th>
                      </tr>

                      



                     </thead>
                     <tbody>
                      
                        @foreach ($datas as $key => $data)
                        <tr>
                             <td style="display: flex;">
                              @can('hrm-employee-delete')
                                 {!! Form::open(['method' => 'DELETE','route' => ['hrm-employee.destroy', $data->id],'style'=>'display:inline']) !!}
                                      <button onclick="return confirm('Are you sure?')" type="submit" class="btn btn-sm btn-default"><i class="fa fa-trash _required"></i></button>
                                  {!! Form::close() !!}
                               @endcan 
                              <a  type="button" 
                                  href="{{ route('hrm-employee.show',$data->id) }}"
                                  class="btn btn-sm btn-default  mr-1"><i class="fa fa-eye"></i></a>

                             @can('hrm-employee-edit')
                                  <a  type="button" 
                                  href="{{ route('hrm-employee.edit',$data->id) }}"
                                 
                                  class="btn btn-sm btn-default  mr-1"><i class="fa fa-pen "></i></a>
                              @endcan  
                               
                            </td>
                            <td>{{ ($key+1) }}</td>
                            <td>{{ $data->id }}</td>
                            <td>{{ $data->_name ?? '' }}</td>
                            <td>{{ $data->_code ?? '' }}</td>
                            <td>{{ $data->_mobile1 ?? '' }}</td>
                            <td>{{ $data->_email ?? '' }}</td>
                            <td>{{ $data->_employee_cat->_name ?? '' }}</td>
                            <td>{{ $data->_emp_department->_name ?? '' }}</td>
                            <td>{{ $data->_emp_designation->_name ?? '' }}</td>
                            <td>{{ $data->_emp_grade->_name ?? '' }}</td>
                            <td>{{ $data->_organization->_name ?? '' }}</td>
                            <td>{{ $data->_branch->_name ?? '' }}</td>
                            <td>{{ $data->_cost_center->_name ?? '' }}</td>
                            <td>{{ $data->_emp_location->_name ?? '' }}</td>
                            <td>{{ selected_status($data->_status) }}</td>
                            <td>{{ $data->_entry_by->name ?? '' }}</td>



                        </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
                          <tr>
                            <td colspan="16">
                              <div class="d-flex flex-row justify-content-start">
                                 {!! $datas->render() !!}
                                </div>
                            </td>
                          </tr>
                        </tfoot>
                    </table>
                </div>
        </div>

@endsection

@section('script')

<script type="text/javascript">
 $(function () {
   var default_date_formate = `{{default_date_formate()}}`
   var _datex = `{{$request->_datex ?? '' }}`
   var _datey = `{{$request->_datey ?? '' }}`
    
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

 

</script>
@endsection