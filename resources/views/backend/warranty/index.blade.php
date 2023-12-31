@extends('backend.layouts.app')
@section('title',$page_name ?? '')
@section('content')
<div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
           <div class="col-sm-12" style="display: flex;">
             <a class="m-0 _page_name" href="{{ route('warranty.index') }}"> {!! $page_name ?? '' !!} </a>
            <ol class="breadcrumb float-sm-right ml-2">
               @can('warranty-create')
               

              <li class="breadcrumb-item active">
                  <a type="button" 
               class="btn btn-sm btn-info active " 
               href="{{ route('warranty.create') }}">
                   <i class="nav-icon fas fa-plus"></i> Create New
                </a>
               </li>
              @endcan
            </ol>
          </div>
        </div><!-- /.row -->

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
                 @include('backend.warranty.search')
              </div>
              <div class="card-body">
                <div class="">
                  <table class="table table-bordered _list_table ">
                      <thead>
                        <tr>
                         <th class="_no">No</th>
                         <th>Action</th>
                         <th>Name</th>
                         <th>Description</th>
                         <th>Duration</th>
                         <th>Period</th>
                        
                      </tr>
                      </thead>
                      <tbody>
                        @foreach ($datas as $key => $data)
                        <tr>
                            <td>{{ $key+1 }}</td>
 <td style="display: flex;">
                           
                                <a   
                                  href="{{ route('warranty.show',$data->id) }}"
                                  
                                  class="btn btn-sm btn-default  mr-1"><i class="fa fa-eye"></i></a>


                                  @can('warranty-edit')
                                  <a  
                                  href="{{ route('warranty.edit',$data->id) }}"
                                  class="btn btn-sm btn-default  mr-1"><i class="fa fa-pen "></i></a>

                                  @endcan
                                  
                                @can('warranty-delete')
                                 {!! Form::open(['method' => 'DELETE','route' => ['warranty.destroy', $data->id],'style'=>'display:inline']) !!}
                                      <button onclick="return confirm('Are you sure?')" type="submit" class="btn btn-sm btn-default"><i class="fa fa-trash _required"></i></button>
                                  {!! Form::close() !!}
                               @endcan  
                               
                        </td>
                        
                            <td>{{ $data->_name }}</td>
                            <td>{{ $data->_description ?? '' }}</td>
                            <td>{{ $data->_duration ?? '' }}</td>
                            <td>{{ $data->_period ?? '' }}</td>
                            
                           
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