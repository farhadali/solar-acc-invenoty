@extends('backend.layouts.app')
@section('title',$page_name)

@section('content')
<div class="content-header">
      <div class="container-fluid">

        <div class="col-sm-12" style="display: flex;">
             <a class="m-0 _page_name" href="{{ route('budgets.index') }}"> {!! $page_name ?? '' !!} </a>
            <ol class="breadcrumb float-sm-right ml-2">
               @can('budgets-create')
              <li class="breadcrumb-item active">
                <a href="{{ route('budgets.create') }}" class="btn btn-sm btn-info active " ><i class="nav-icon fas fa-plus"></i>{!!__('label.create_new')!!}</a>
                
                  
               </li>
              @endcan
            </ol>
          </div>

        
      </div><!-- /.container-fluid -->
    </div>
    @if ($message = Session::get('success'))
    <div class="alert alert-success">
      <p>{{ $message }}</p>
    </div>
    @endif
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-12">
            <div class="card">
              <div class="card-header border-0">
                 @php

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
                 <div class="row">
                     <div class="col-md-4">
                      @include('backend.budgets.search')
                    </div>
                    <div class="col-md-8">
                      <div class="d-flex flex-row justify-content-end">
                         @can('voucher-print')
                        <li class="nav-item dropdown remove_from_header">
                              <a class="nav-link" data-toggle="dropdown" href="#">
                                
                                <i class="fa fa-print " aria-hidden="true"></i> <i class="right fas fa-angle-down "></i>
                              </a>
                              <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                               
                                <div class="dropdown-divider"></div>
                                
                                <a target="__blank" href="{{$print_url}}" class="dropdown-item">
                                  <i class="fa fa-print mr-2" aria-hidden="true"></i> Print
                                </a>  
                            </li>
                             @endcan   
                         {!! $datas->render() !!}
                          </div>
                    </div>
                 </div>
              </div>
              <div class="card-body">
                <div >
                  <table class="table table-bordered _list_table">
                      <thead>
                        <tr>
                         <th class="_no">No</th>
                         <th class="_no">{{__('label.action')}}</th>
                         <th class="">{{__('label.organization')}}</th>
                         <th>{{__('label.Branch')}}</th>
                         <th>{{__('label.Cost center')}}</th>
                         <th>{{__('label._start_date')}}</th>
                         <th>{{__('label._end_date')}}</th>
                         <th>{{__('label._income')}}</th>
                         <th>{{__('label._material_expense')}}</th>
                         <th>{{__('label._other_expense')}}</th>
                         <th>{{__('label._estimated_profit')}}</th>
                         <th>{{__('label._created_by')}}</th>
                         <th>{{__('label._updated_by')}}</th>
                         <th>{{__('label._status')}}</th>
                      </tr>
                      </thead>
                      <tbody>
                        @foreach ($datas as $key => $data)
                        <tr>
                            <td>{{ $key+1 }}</td>
                          <td style="display: flex;">
                                           
                                <a  type="button"
                                target="__blank" 
                                  href="{{ route('budgets.show',$data->id) }}"
                                  class="btn btn-sm btn-default  mr-1"><i class="fa fa-print"></i></a>
                                  @can('budgets-edit')
                                  <a href="{{ route('budgets.edit',$data->id) }}"
                                  class="btn btn-sm btn-default  mr-1"><i class="fa fa-pen "></i></a>
                              @endcan
                                  @can('budget-compare')
                                  <a title="{{__('label.budget-compare-report')}}" href="{{ url('budget-compare') }}?_cost_center_id={{$data->_cost_center_id}}&_branch_id={{$data->_branch_id}}&organization_id={{$data->organization_id}}&_budget_id={{$data->id}}"
                                  class="btn btn-sm btn-default  mr-1"><i class="fa fa-percent "></i></a>
                              @endcan
                                @can('budgets-delete')
                                 {!! Form::open(['method' => 'DELETE','route' => ['budgets.destroy', $data->id],'style'=>'display:inline']) !!}
                                      <button onclick="return confirm('Are you sure?')" type="submit" class="btn btn-sm btn-default"><i class="fa fa-trash _required"></i></button>
                                  {!! Form::close() !!}
                               @endcan  
                               
                        </td>
                            <td>{{ $data->_organization->_name ?? '' }}</td>
                            <td>{{ $data->_master_branch->_name ?? '' }}</td>
                            <td>{{ $data->_master_cost_center->_name ?? '' }}</td>
                            <td>{{ _view_date_formate($data->_start_date ?? '') }}</td>
                            <td>{{ _view_date_formate($data->_end_date ?? '') }}</td>
                            <td>{{ _report_amount($data->_income_amount ?? 0 ) }}</td>
                            <td>{{ _report_amount($data->_material_amount ?? 0 ) }}</td>
                            <td>{{ _report_amount($data->_expense_amount ?? 0 ) }}</td>
                            <td>{{ _report_amount($data->_expense_amount ?? 0 ) }}</td>
                            <td>{{ $data->_created_by ?? '' }}</td>
                            <td>{{ $data->_updated_by ?? '' }}</td>
                            <td>{{ ($data->_status==1) ? 'Active' : 'In Active' }}</td>
                           
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- /.d-flex -->

                

                <div class="d-flex flex-row justify-content-end">
                 {!! $datas->render() !!}
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

@endsection