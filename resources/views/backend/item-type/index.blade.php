@extends('backend.layouts.app')
@section('title',$page_name ?? '')
@section('content')
<div class="content-header">
      <div class="container-fluid">

        <div class="col-sm-12" style="display: flex;">
             <a class="m-0 _page_name" href="{{ route('item-type.index') }}"> {!! $page_name ?? '' !!} </a>
            <ol class="breadcrumb float-sm-right ml-2">
               @can('item-type-create')

              <li class="breadcrumb-item active">
                <a  
               class="btn btn-sm btn-info active " 
               href="{{ route('item-type.create') }}">
                   <i class="nav-icon fas fa-plus"></i> Create New
                </a>
               </li>
              @endcan
            </ol>
          </div>
         


      </div><!-- /.container-fluid -->
    </div>
    <div class="message-area">
    @include('backend.message.message')
    </div>
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-12">
            <div class="card">
              <div class="card-header border-0">
                 @include('backend.item-type.search')
              </div>
              <div class="card-body">
                <div class="d-flex">
                  <table class="table table-bordered _list_table">
                      <thead>
                        <tr>
                        <th>{{__('label.sl')}}</th>
                         <th>{{__('label._action')}}</th>
                        <th>{{__('label.id')}}</th>
                         <th>{{__('label._name')}}</th>
                         <th>{{__('label._code')}}</th>
                         <th>{{__('label._detail')}}</th>
                         <th>{{__('label._status')}}</th>
                         </tr>
                      </thead>
                      <tbody>
                        
                      
                        @foreach ($datas as $key => $data)
                        <tr>
                            <td>{{($key+1)}}</td>
                            <td style="display: flex;">
                           
                                <a   
                                  href="{{ route('item-type.show',$data->id) }}"
                                  class="btn btn-sm btn-default  mr-1"><i class="fa fa-eye"></i></a>


                                  @can('item-type-edit')
                                  <a   href="{{ route('item-type.edit',$data->id) }}"
                                  class="btn btn-sm btn-default  mr-1"><i class="fa fa-pen "></i></a>

                                    
                                  @endcan
                                @can('item-type-delete')
                                 {!! Form::open(['method' => 'DELETE','route' => ['item-type.destroy', $data->id],'style'=>'display:inline']) !!}
                                      <button onclick="return confirm('Are you sure?')" type="submit" class="btn btn-sm btn-default"><i class="fa fa-trash _required"></i></button>
                                  {!! Form::close() !!}
                               @endcan  
                               
                        </td>
                          
                             
                            <td>{{ $data->id }}</td>
                            <td> {{ $data->_name ?? '' }}</td>
                            <td> {{ $data->_code ?? '' }}</td>
                            <td> {{ $data->_detail ?? '' }}</td>
                            <td> {{ selected_status($data->_status ?? 0) }}</td>
                            
                           
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