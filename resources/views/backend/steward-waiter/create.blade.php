@extends('backend.layouts.app')
@section('title',$page_name)

@section('css')

<link rel="stylesheet" href="{{asset('backend/pos-template/css/select2.min.css')}}">
@endsection

@section('content')
<div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
             <a class="m-0 _page_name" href="{{ route('steward-waiter.index') }}">{!! $page_name ?? '' !!} </a>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <!-- <li class="breadcrumb-item"><a href="{{url('home')}}">Home</a></li> -->
              <li class="breadcrumb-item active">
                 <a class="btn btn-info" href="{{ route('steward-waiter.index') }}"> {{ $page_name ?? '' }} </a>
               </li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <div class="message-area">
    @if (count($errors) > 0)
           <div class="alert alert-danger">
               
                <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
                </ul>
            </div>
        @endif
        @if ($message = Session::get('success'))
        <div class="alert alert-success">
          <p>{{ $message }}</p>
        </div>
        @endif
    </div>
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-12">
            <div class="card">
             
              <div class="card-body">
                {!! Form::open(array('route' => 'steward-waiter.store','method'=>'POST')) !!}
                    <div class="row">
                       <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <label>Branch:</label>
                                <select class="form-control" name="_branch_id" required>
                                  @forelse($branchs as $branch)
                                  <option value="{{$branch->id}}">{{ $branch->_name ?? '' }}</option>
                                  @empty
                                  @endforelse
                                  
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <label>Steward/Waiter:</label>
                                <select multiple class="form-control _ledgers select2" name="_ledgers[]" >
                                    <option value="">Steward/Waiter</option>
                                    @forelse($_ledgerss as $ledger)
                                    <option value="{{$ledger->id}}">{{$ledger->_name ?? ''}}</option>
                                    @empty
                                    @endforelse
                                  </select>
                               
                            </div>
                        </div>
                        
                        <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <label>Status:</label>
                                <select class="form-control" name="_status">
                                  <option value="1">Active</option>
                                  <option value="0">In Active</option>
                                </select>
                            </div>
                        </div>
                       
                        
                       
                       <div class="col-xs-12 col-sm-12 col-md-12 bottom_save_section text-middle">
                            <button type="submit" class="btn btn-success  ml-5"><i class="fa fa-credit-card mr-2" aria-hidden="true"></i> Save</button>
                           
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

@section('script')
<script src="{{asset('backend/pos-template/js/select2.full.min.js')}}"></script>
@endsection
