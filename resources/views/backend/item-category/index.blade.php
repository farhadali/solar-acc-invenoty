@extends('backend.layouts.app')
@section('title',$page_name ?? '')
@section('content')
<div class="content-header">
      <div class="container-fluid">

        <div class="col-sm-12" style="display: flex;">
             <a class="m-0 _page_name" href="{{ route('item-category.index') }}"> {!! $page_name ?? '' !!} </a>
            <ol class="breadcrumb float-sm-right ml-2">
               @can('item-category-create')

              <li class="breadcrumb-item active">
                <a  
               class="btn btn-sm btn-info active " 
               href="{{ route('item-category.create') }}">
                   <i class="nav-icon fas fa-plus"></i> Create New
                </a>
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
                 @include('backend.item-category.search')
              </div>
              <div class="card-body">
                <div class="d-flex">
                  <table class="table table-bordered _list_table">
                      <thead>
                        <tr>
                        <th>SL</th>
                         <th class="">Action</th>
                          <th class="">ID</th>
                         <th>Parents</th>
                         <th>Name</th>
                         <th>Image</th>
                         </tr>
                      </thead>
                      <tbody>
                        @php
                         $default_image = $settings->logo;
                         @endphp 
                      
                        @foreach ($datas as $key => $data)
                        <tr>
                            <td>{{($key+1)}}</td>
                            <td style="display: flex;">
                           
                                <a   
                                  href="{{ route('item-category.show',$data->id) }}"
                                  class="btn btn-sm btn-default  mr-1"><i class="fa fa-eye"></i></a>


                                  @can('item-category-edit')
                                  <a   href="{{ route('item-category.edit',$data->id) }}"
                                  class="btn btn-sm btn-default  mr-1"><i class="fa fa-pen "></i></a>

                                    
                                  @endcan
                                @can('item-category-delete')
                                 {!! Form::open(['method' => 'DELETE','route' => ['item-category.destroy', $data->id],'style'=>'display:inline']) !!}
                                      <button onclick="return confirm('Are you sure?')" type="submit" class="btn btn-sm btn-default"><i class="fa fa-trash _required"></i></button>
                                  {!! Form::close() !!}
                               @endcan  
                               
                        </td>
                          
                             
                            <td>{{ $data->id }}</td>
                            <td> {{ $data->_parents->_name ?? '' }}</td>
                            <td> {{ $data->_name ?? '' }}</td>
                            

                              <td>
                              <img src="{{asset('/')}}{{$data->_image ?? $default_image }}"  style="max-height:50px;max-width: 50px; " /></td>
                            
                            
                            
                           
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