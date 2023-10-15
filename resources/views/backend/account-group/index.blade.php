@extends('backend.layouts.app')
@section('title',$page_name)

@section('content')
<div class="content-header">
      <div class="container-fluid">
        <div class="col-sm-12" style="display: flex;">
             <a class="m-0 _page_name" href="{{ route('account-group.index') }}"> {!! $page_name ?? '' !!} </a>
            <ol class="breadcrumb float-sm-right ml-2">
               @can('account-group-create')
              <li class="breadcrumb-item active">
                  
                  <button type="button" 
               class="btn btn-sm btn-info active attr_base_create_url" 
               data-toggle="modal" 
               data-target="#commonEntryModal_item" 
               attr_base_create_url="{{ route('account-group.create') }}">
                   <i class="nav-icon fas fa-plus"></i> Create New
                </button>
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
                 
                 <div class="row">
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
                    <div class="col-md-4">
                     @include('backend.account-group.search')
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
                                  <i class="fa fa-print mr-2" aria-hidden="true"></i>Print
                                </a>
                               <div class="dropdown-divider"></div>
                              
                                
                              
                                    
                            </li>
                             @endcan   
                         {!! $datas->render() !!}
                          </div>
                    </div>
                  </div>
              </div>
              <div class="card-body">
                <div class="">
                  <table class="table table-bordered _list_table">
                    <thead>
                      <tr>
                        <th>SL</th>
                         <th  style="width: 10%" class="">Action</th>
                         <th  style="width: 5%" class="_no">ID</th>
                         <th style="width: 10%" >Account Type</th>
                         <th style="width: 5%" >Code</th>
                         <th style="width: 20%" >Name</th>
                         <th style="width: 35%" >Details</th>
                         <th style="width: 5%" >Status</th>
                         <th style="width: 5%" >Possition</th>
                      </tr>
                    </thead>
                      <tbody>
                        @foreach ($datas as $key => $data)
                        <tr>
                          <td>{{($key+1)}}</td>
                          <td style="display: flex;">
                           
                                <button  type="button" 
                                  attr_base_edit_url="{{ route('account-group.show',$data->id) }}"
                                  data-toggle="modal" 
                                  data-target="#commonEntryModal_item" 
                                  class="btn btn-sm btn-default attr_base_edit_url mr-1"><i class="fa fa-eye"></i></button>


                                  @can('account-group-edit')
                                  <button  type="button" 
                                  attr_base_edit_url="{{ route('account-group.edit',$data->id) }}"
                                  data-toggle="modal" 
                                  data-target="#commonEntryModal_item" 
                                  class="btn btn-sm btn-default attr_base_edit_url mr-1"><i class="fa fa-pen "></i></button>

                                    
                                  @endcan
                                @can('account-group-delete')
                                 {!! Form::open(['method' => 'DELETE','route' => ['account-group.destroy', $data->id],'style'=>'display:inline']) !!}
                                      <button onclick="return confirm('Are you sure?')" type="submit" class="btn btn-sm btn-default"><i class="fa fa-trash _required"></i></button>
                                  {!! Form::close() !!}
                               @endcan  
                               
                        </td>

                          
                             
                             <td>{{ $data->id }}</td>
                            <td>{{ $data->account_type->_name ?? '' }}</td>
                            <td>{{ $data->_code ?? '' }}</td>
                            <td>{{ $data->id ?? '' }} - {{ $data->_name ?? '' }}</td>
                            <td>{{ $data->_details ?? '' }}</td>
                            <td>{{ selected_status($data->_status) }}</td>
                            <td>{{ $data->_short ?? '' }}</td>
                        </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
                          <tr>
                            <td colspan="8">  {!! $datas->render() !!} </td>
                          </tr>
                        </tfoot>
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

@endsection