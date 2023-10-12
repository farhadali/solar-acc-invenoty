@extends('backend.layouts.app')
@section('title',$page_name)

@section('content')

  <div class="content ">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
          <div class="card">
           <div class="row mb-2">
                  <div class="col-sm-6">
                    <a class="m-0 _page_name" href="{{ route('hrm-employee.index') }}">{!! $page_name !!} </a>
                  </div><!-- /.col -->
                  <div class="col-sm-6">
                    
                  </div><!-- /.col -->
                </div><!-- /.row -->
          
         <div class="message-area">
    @include('backend.message.message')
    </div>
            <div class="card-body p-4" >
                {!! Form::open(array('route' => 'hrm-employee.store','method'=>'POST')) !!}
                <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>{!!__('label.image') !!}:<span class="_required">*</span></label>
                                <input type="file" accept="image/*" onchange="loadFile(event,1 )"  name="_photo" class="form-control">
                               <img id="output_1" class="banner_image_create" src="{{asset('/')}}{{$settings->logo ?? ''}}"  style="max-height:100px;max-width: 100px; " />
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>{!!__('label._name') !!}:<span class="_required">*</span></label>
                                <input type="text" class="form-control" name="_name" value="{{old('_name')}}" placeholder="{{__('label._name')}}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>{!!__('label._code') !!}:</label>
                                <input type="text" class="form-control" name="_code" value="{{old('_code')}}" placeholder="{{__('label._code')}}" readonly>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>{!!__('label.employee_category_id') !!}:<span class="_required">*</span></label>
                                <select class="form-control" name="_category_id" required>
                                  <option value=""><--{{__('label.select')}}--></option>
                                  @forelse($employee_catogories as $val)
                                  <option value="{{$val->id}}" @if(old('_category_id')==$val->id) selected @endif>{!! $val->_name ?? '' !!}</option>
                                  @empty
                                  @endforelse
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>{!!__('label._department_id') !!}:<span class="_required">*</span></label>
                                <select class="form-control select2" name="_department_id" required >
                                  <option value=""><--{{__('label.select')}}--></option>
                                  @forelse($departments as $val)
                                  <option value="{{$val->id}}" @if(old('_department_id')==$val->id) selected @endif>{!! $val->_department ?? '' !!}</option>
                                  @empty
                                  @endforelse
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>{!!__('label._jobtitle_id') !!}:<span class="_required">*</span></label>
                                <select class="form-control select2" name="_jobtitle_id" required >
                                  <option value=""><--{{__('label.select')}}--></option>
                                  @forelse($designations as $val)
                                  <option value="{{$val->id}}" @if(old('_jobtitle_id')==$val->id) selected @endif>{!! $val->_name ?? '' !!}</option>
                                  @empty
                                  @endforelse
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>{!!__('label._jobtitle_id') !!}:<span class="_required">*</span></label>
                                <select class="form-control select2" name="_jobtitle_id" required >
                                  <option value=""><--{{__('label.select')}}--></option>
                                  @forelse($designations as $val)
                                  <option value="{{$val->id}}" @if(old('_jobtitle_id')==$val->id) selected @endif>{!! $val->_name ?? '' !!}</option>
                                  @empty
                                  @endforelse
                                </select>
                            </div>
                        </div>
                </div>
                     
                      
                      
                      
                      <div class="col-xs-12 col-sm-12 col-md-12">
                            <div class="form-group">
                                <label>{{__('label._status')}}:</label>
                                <select class="form-control" name="_status">
                                  <option value="1">Active</option>
                                  <option value="0">In Active</option>
                                </select>
                            </div>
                        </div>
                
                        <div class="col-xs-12 col-sm-12 col-md-12  text-middle">
                            <button type="submit" class="btn btn-success  ml-5"><i class="fa fa-credit-card mr-2" aria-hidden="true"></i> {{__('label.save')}}</button>
                           
                        </div>
                        <br><br>
                    
                 
                    
                    
                     
                    {!! Form::close() !!}
                
              </div>
          
          </div>
        </div>
        <!-- /.row -->
      </div>
    </div>  
</div>



@endsection

@section('script')

<script type="text/javascript">


 

  

  

     

         

</script>
@endsection

