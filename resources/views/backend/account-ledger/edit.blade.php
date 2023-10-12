
<div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <a class="m-0 _page_name" href="{{ route('account-ledger.index') }}">{!! $page_name ?? '' !!} </a>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              @can('account-ledger-list')
              <li class="breadcrumb-item active">
                 <a class="btn btn-info" href="{{ route('account-ledger.index') }}"> <i class="fa fa-th-list" aria-hidden="true"></i></a>
               </li>
               @endcan
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-12">
            <div class="card">
              <div class="card-header">
                 @include('backend.message.message')
              </div>
              <div class="card-body">
               
                 <form action="{{ url('account-ledger/update') }}" method="POST">
                    @csrf
                    <div class="row">
                      <input type="hidden" name="id" class="form-control" value="{{ $data->id }}" >
                       <div class="col-xs-12 col-sm-12 col-md-6">
                            <div class="form-group">
                                <label>Account Type: <span class="_required">*</span></label>
                               <select type_base_group="{{url('type_base_group')}}"  class="form-control _account_head_id select2" name="_account_head_id" required>
                                  <option value="">--Select Account Type--</option>
                                  @forelse($account_types as $account_type )
                                  <option value="{{$account_type->id}}" @if(isset($data->_account_head_id)) @if($data->_account_head_id == $account_type->id) selected @endif   @endif>{{ $account_type->id ?? '' }}-{{ $account_type->_name ?? '' }}</option>
                                  @empty
                                  @endforelse
                                </select>
                            </div>
                        </div>
                       <div class="col-xs-12 col-sm-12 col-md-6">
                            <div class="form-group ">
                                <label>Account Group:<span class="_required">*</span></label>
                               <select class="form-control _account_groups select2" name="_account_group_id" required>
                                  <option value="">--Select Account Group--</option>
                                  @forelse($account_groups as $account_group )
                                  <option value="{{$account_group->id}}" @if(isset($data->_account_group_id)) @if($data->_account_group_id == $account_group->id) selected @endif   @endif>{{ $account_group->id ?? '' }} - {{ $account_group->_name ?? '' }}</option>
                                  @empty
                                  @endforelse
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-6">
                            <div class="form-group ">
                                <label>Branch:<span class="_required">*</span></label>
                               <select class="form-control" name="_branch_id" required >
                                  
                                  @forelse($branchs as $branch )
                                  <option value="{{$branch->id}}" @if(isset($data->_branch_id)) @if($data->_branch_id == $branch->id) selected @endif   @endif>{{ $branch->id ?? '' }} - {{ $branch->_name ?? '' }}</option>
                                  @empty
                                  @endforelse
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-6">
                            <div class="form-group">
                                <label>Ledger Name:<span class="_required">*</span></label>
                                
                                <input type="text" name="_name" class="form-control" value="{{old('_name',$data->_name)}}" placeholder="Ledger Name" required>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-6">
                            <div class="form-group">
                                <label>Address:</label>
                                
                                <textarea name="_address" class="form-control" placeholder="Address">{{old('_address',$data->_address)}}</textarea>
                                
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-6">
                            <div class="form-group">
                                <label>Details:</label>
                               
                                <textarea name="_note" class="form-control" placeholder="Details">{{$data->_note ?? ''}}</textarea>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-6">
                            <div class="form-group">
                                <label>Proprietor:</label>
                                <input type="text" name="_alious" class="form-control" value="{{old('_alious',$data->_alious)}}" placeholder="Proprietor">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-3">
                            <div class="form-group">
                                <label>Code:</label>
                                <input type="text" name="_code" class="form-control" value="{{old('_code',$data->_code)}}" placeholder="CODE Number">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-3">
                            <div class="form-group">
                                <label>Email:</label>
                                <input type="email" name="_email" class="form-control" value="{{old('_email',$data->_email)}}" placeholder="Email" >
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-2">
                            <div class="form-group">
                                <label>Phone:</label>
                                <input type="text" name="_phone" class="form-control" value="{{old('_phone',$data->_phone)}}" placeholder="Phone" >
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-2">
                            <div class="form-group">
                                <label>NID Number:</label>
                               <input type="text" name="_nid" class="form-control" value="{{old('_nid',$data->_nid)}}" placeholder="NID Number">
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-2">
                            <div class="form-group">
                                <label>Credit Limit:</label>
                                <input type="number" step="any" name="_credit_limit" class="form-control" value="{{old('_credit_limit',$data->_credit_limit)}}" placeholder="Credit Limit" >
                            </div>
                        </div>
                         <div class="col-xs-12 col-sm-12 col-md-2 display_none">
                            <div class="form-group">
                                <label>Display Possition:</label>
                                {!! Form::text('_short', $data->_short, array('placeholder' => 'Possition','class' => 'form-control')) !!}
                            </div>
                        </div>
                        
                        
                        
                       
                        <div class="col-xs-12 col-sm-12 col-md-2 display_none">
                            <div class="form-group">
                                <label>Is User:</label>
                                <select class="form-control" name="_is_user">
                                  @foreach(yes_nos() as $key=>$s_val)
                                  <option value="{{$key}}" @if($key==$data->_is_user) selected @endif >{{$s_val}}</option>
                                  @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-2 display_none">
                            <div class="form-group">
                                <label>Sales Form:</label>
                                <select class="form-control" name="_is_sales_form">
                                  @foreach(yes_nos() as $key=>$s_val)
                                  <option value="{{$key}}" @if($key==$data->_is_sales_form) selected @endif>{{$s_val}}</option>
                                  @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-2 display_none">
                            <div class="form-group">
                                <label>Is Purchase Form:</label>
                                <select class="form-control" name="_is_purchase_form">
                                  @foreach(yes_nos() as $key=>$s_val)
                                  <option value="{{$key}}"  @if($key==$data->_is_purchase_form) selected @endif >{{$s_val}}</option>
                                  @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-2 display_none">
                            <div class="form-group">
                                <label>Search For All Branch:</label>
                                <select class="form-control" name="_is_all_branch">
                                  @foreach(yes_nos() as $key=>$s_val)
                                  <option value="{{$key}}" @if($key==$data->_is_all_branch) selected @endif >{{$s_val}}</option>
                                  @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-2">
                            <div class="form-group">
                                <label>Status:</label>
                                <select class="form-control" name="_status">
                                  @foreach(common_status() as $key=>$s_val)
                                  <option value="{{$key}}" @if($key==$data->_status) selected @endif  >{{$s_val}}</option>
                                  @endforeach
                                </select>
                            </div>
                        </div>
                        
                       
                       
                       <div class="col-xs-12 col-sm-12 col-md-12 bottom_save_section text-middle">
                            <button type="submit" class="btn btn-success  ml-5"><i class="fa fa-credit-card mr-2" aria-hidden="true"></i> Save</button>
                           
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

