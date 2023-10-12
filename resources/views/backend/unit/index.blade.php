@extends('backend.layouts.app')
@section('title',$page_name)

@section('content')
<div class="content-header">
      <div class="container-fluid">

         <div class="col-sm-12" style="display: flex;">
             <a class="m-0 _page_name" href="{{ route('unit.index') }}"> {!! $page_name ?? '' !!} </a>
            <ol class="breadcrumb float-sm-right ml-2">
               @can('unit-create')
              <li class="breadcrumb-item active">
                <button type="button" 
               class="btn btn-sm btn-info active attr_base_create_url" 
               data-toggle="modal" 
               data-target="#commonEntryModal_item" 
               attr_base_create_url="{{ route('unit.create') }}">
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
                      @include('backend.unit.search')
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
                <div class="table-respoisive">
                  <table class="table table-bordered _list_table">
                      <thead>
                        <tr>
                         
                         <th class="_action">Action</th>
                         <th>ID</th>
                         <th>Name</th>
                         <th>Code</th>
                         
                         <th>Status</th>
                      </tr>
                      </thead>
                      <tbody>
                        @foreach ($datas as $key => $data)
                        <tr>
                             <td style="display: flex;">
                           
                                <button  type="button" 
                                  attr_base_edit_url="{{ route('unit.show',$data->id) }}"
                                  data-toggle="modal" 
                                  data-target="#commonEntryModal_item" 
                                  class="btn btn-sm btn-default attr_base_edit_url mr-1"><i class="fa fa-eye"></i></button>


                                  @can('unit-edit')
                                  <button  type="button" 
                                  attr_base_edit_url="{{ route('unit.edit',$data->id) }}"
                                  data-toggle="modal" 
                                  data-target="#commonEntryModal_item" 
                                  class="btn btn-sm btn-default attr_base_edit_url mr-1"><i class="fa fa-pen "></i></button>

                                    
                                  @endcan
                                @can('unit-delete')
                                 {!! Form::open(['method' => 'DELETE','route' => ['unit.destroy', $data->id],'style'=>'display:inline']) !!}
                                      <button onclick="return confirm('Are you sure?')" type="submit" class="btn btn-sm btn-default"><i class="fa fa-trash _required"></i></button>
                                  {!! Form::close() !!}
                               @endcan  
                               
                        </td>

                           
                            <td>{{ $data->id }} </td>
                            <td> {{ $data->_name ?? '' }}</td>
                            <td> {{ $data->_code ?? '' }}</td>
                            <td>{{ selected_status($data->_status) }}</td>
                           
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