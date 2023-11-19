<div  >
                  <form action="" method="GET">
                    @csrf
                      @php 
                        $row_numbers = filter_page_numbers();
                         
                        @endphp
                        <div class="row">
                          <div class="col-md-2">
                            <select name="limit" class="form-control">
                                    @forelse($row_numbers as $row)
                                     <option @if(isset($request->limit)) @if($request->limit == $row) selected @endif  @endif value="{{ $row }}">{{$row}}</option>
                                    @empty
                                    @endforelse
                            </select>
                          </div>
                          <div class="col-md-3">
                            <input type="text" name="_name" class="form-control" placeholder="{{__('label._name')}}" value="@if(isset($request->_name)) {{$request->_name ?? ''}}  @endif">
                          </div>
                          <div class="col-md-3">
                            <input type="text" name="_code" class="form-control" placeholder="{{__('label._code')}}" value="@if(isset($request->_code)) {{$request->_code ?? ''}}  @endif">
                          </div>
                          
                          
                         
                          
                          <div class="col-md-2">
                              <button class="form-control btn btn-warning" type="submit">Search</button>
                          </div>
                        </div><!-- end row -->
                   
                  </form>
                </div>