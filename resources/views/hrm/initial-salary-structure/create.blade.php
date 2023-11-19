@extends('backend.layouts.app')
@section('title',$page_name ?? '')
@section('content')
<div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <a class="m-0 _page_name" href="{{ route('initial-salary-structure.index') }}">{!! $page_name ?? '' !!} </a>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
             @can('initial-salary-structure-list')
              <li class="breadcrumb-item active">
                 <a class="btn btn-info" href="{{ route('initial-salary-structure.index') }}"> <i class="fa fa-th-list" aria-hidden="true"></i></a>
               </li>
               @endcan
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    
    <div class="content">
      <div class="container-fluid">
<div class="card ">
<div class="card-body">
                 @include('backend.message.message')
                {!! Form::open(array('route' => 'initial-salary-structure.store','method'=>'POST','enctype'=>'multipart/form-data')) !!}
                <div class="form-group row pt-2">
                            <label class="col-sm-2 col-form-label" >{{__('Employee')}}:</label>
                             <div class="col-sm-6">
                                <input type="hidden" name="_employee_id" class="_employee_id" value="">
                                <input type="hidden" name="_employee_ledger_id" class="_employee_ledger_id" value="">
                                <input type="text" name="_employee_id_text" class="form-control _employee_id_text" placeholder="{{__('Employee')}}">
                            </div>
                        </div>
                <div class="row">
                    @forelse($payheads as $p_key=>$p_val)
                    <div class="col-md-4 ">
                        <h3>{!! $p_key ?? '' !!}</h3>
                        @if(sizeof($p_val) > 0)
                            @forelse($p_val as $l_val)
                            <div class="form-group row ">
                            <label class="col-sm-6 col-form-label" for="_item">{{$l_val->_ledger ?? '' }}:</label>
                             <div class="col-sm-6">
                                <input type="hidden" name="_payhead_id[]" class="_payhead_id" value="{{$l_val->id}}">
                                <input type="hidden" name="_payhead_type_id[]" class="_payhead_type_id" value="{{$l_val->_type}}">
                              <input type="text"  name="_amount" class="form-control" value="0" placeholder="{{__('label._amount')}}" >
                            </div>
                        </div>
                        @empty
                        @endforelse
                        @endif
                    </div>

                        @empty
                        @endforelse
                </div>

              


<div class="col-xs-12 col-sm-12 col-md-12 bottom_save_section text-middle">
                            <button type="submit" class="btn btn-success submit-button ml-5" ><i class="fa fa-credit-card mr-2" aria-hidden="true"></i> Save</button>
                            
                        </div>


</form> <!-- End of form -->
</div><!-- End of Card body -->
</div><!-- End of Card -->
</div><!-- End of Container -->
</div><!-- End of Content -->



@endsection
@section('script')

</script>
@endsection
