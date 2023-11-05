@extends('backend.layouts.app')
@section('title',$page_name)

@section('content')
<div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <a class="m-0 _page_name" href="{{ route('account-group.index') }}"> {!! $page_name ?? '' !!} </a>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              @can('account-group-list')
              <li class="breadcrumb-item active">
                 <a class="btn btn-info" href="{{ route('account-group.index') }}">  <i class="fa fa-th-list" aria-hidden="true"></i> </a>
               </li>
               @endcan
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <div class="message-area">
    @if (count($errors) > 0)
           <div class="alert alert-danger">
                <strong>Whoops!</strong> There were some problems with your input.<br><br>
                <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
                </ul>
            </div>
        @endif
    </div>
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-12">
            <div class="card">
             
              <div class="card-body">
               
                 <form action="{{ url('account-group/update') }}" method="POST">
                    @csrf
                    <div class="row">
                       <div class="col-xs-12 col-sm-12 col-md-6">
                         <input type="hidden" name="id" value="{{ $data->id }}">
                            <div class="form-group">
                                <label>Account Type:</label>
                               <select class="form-control" name="_account_head_id">
                                  <option value="">--Select--</option>
                                  @forelse($account_types as $account_type )
                                  <option value="{{$account_type->id}}"  @if($data->_account_head_id == $account_type->id) selected @endif   >{{ $account_type->_name ?? '' }}</option>
                                  @empty
                                  @endforelse
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-6">
                            <div class="form-group">
                                <label>Name:</label>
                                
                                <input type="text" name="_name" class="form-control" required="true" value="{!! $data->_name ?? '' !!}">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-6">
                            <div class="form-group">
                                <label>Code:</label>
                                
                                 <input type="text" name="_code" class="form-control" value="{!! $data->_code ?? '' !!}">
                            </div>
                        </div>
                         <div class="col-xs-12 col-sm-12 col-md-6">
                            <div class="form-group">
                                <label>Display Possition:</label>
                                {!! Form::text('_short', $data->_short, array('placeholder' => 'Possition','class' => 'form-control')) !!}
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <label>Details:</label>
                              
                                 <textarea placeholder="Details" class="form-control" name="_details" cols="10" rows="5">{!!  $data->_details ?? '' !!}</textarea>
                            </div>
                        </div>
                       
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <label>Status:</label>
                                <select class="form-control" name="_status">
                                  <option value="1" @if($data->_status==1) selected @endif >Active</option>
                                  <option value="0" @if($data->_status==0) selected @endif >In Active</option>
                                </select>
                            </div>
                        </div>
                       
                       
                        
                        <div class="col-xs-6 col-sm-6 col-md-6 text-center mt-1">
                            <button type="submit" class="btn btn-success "><i class="fa fa-credit-card mr-2" aria-hidden="true"></i> Save</button>
                            
                        </div>
                        <br><br>

                        
                    </div>
                    </form>
                
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