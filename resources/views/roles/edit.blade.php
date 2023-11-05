@extends('backend.layouts.app')
@section('title',$page_name ?? '')

@section('content')
<div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
             <a class="m-0 _page_name" href="{{ route('roles.index') }}">Role Management </a>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <!-- <li class="breadcrumb-item"><a href="{{url('home')}}">Home</a></li> -->
              <li class="breadcrumb-item active">
                 <a class="btn btn-primary" href="{{ route('roles.index') }}"> Role Management</a>
               </li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
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
             
              <div class="card-body">
                {!! Form::model($role, ['method' => 'PATCH','route' => ['roles.update', $role->id]]) !!}
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="form-group">
                                    <strong>Name:</strong>
                                    {!! Form::text('name', null, array('placeholder' => 'Name','class' => 'form-control')) !!}
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                              @php
                              $types = ['admin','visitor'];
                              @endphp
                                <strong>Type:</strong>
                                <select class="form-control" name="type">
                                  @forelse($types as $key=>$value)
                                  <option @if($role->type==$value) selected @endif value="{{$value}}">{{$value}}</option>
                                  @empty
                                  @endforelse
                                </select>
                            </div>
                        </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 mb-10" >
                                <div class="form-group">
                                    <strong>Permission: <input type="checkbox" name="all_all_check" class="">ALL </strong>
                                    <br/>
                                @php
                                $number = 1;
                               @endphp
                                   @foreach($permission as $key=>$values)
                                    <div class=" card" >
                                      @php
                                      $make_class = "group_check__".strtolower(str_replace(' ', '', $key));
                                        $class_names ="name all_check ".$make_class;

                                      @endphp
                                      <div class="card-header">
                                        <h4 style="background: #f4f6f9;padding: 5px;border-radius: 5px;">{{$number}}.{{$key}} <input type="checkbox" name="group_check" class="{{$make_class}}"></h4>
                                      </div>

                                      
                                  
                                   <div class="row card-body">
                                    @foreach($values as $value)
                                    <div class="col-md-3">
                                        <label>{{ Form::checkbox('permission[]', $value->id, in_array($value->id, $rolePermissions) ? true : false, array('class' => $class_names)) }}
                                          {{ $value->name }}</label>
                                    </div>
                                      @endforeach 
                                  </div>

                                     
                                  </div>
                                  @php
                                $number++;
                               @endphp
                                   @endforeach
                               </div>
                            </div>
                           
                             <div class="col-xs-12 col-sm-12 col-md-12 bottom_save_section text-middle ">
                            <button type="submit" class="btn btn-success submit-button "><i class="fa fa-credit-card mr-2" aria-hidden="true"></i> Save</button>
                        </div>
                        <br><br>
                        </div>
                        {!! Form::close() !!}
                
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