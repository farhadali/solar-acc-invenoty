     @php
              $users = \Auth::user();
              $permited_organizations = permited_organization(explode(',',$users->organization_ids));
              @endphp 


              <div class="col-xs-12 col-sm-12 col-md-12 ">
              <div class="form-group ">
              <label>{!! __('label.organization') !!}:</label>
              <select multiple class="form-control _master_organization_id" name="organization_id[]"  >
               @forelse($permited_organizations as $val )
               <option value="{{$val->id}}"  @if(isset($previous_filter["organization_id"])) 
                               @if(in_array($val->id,$previous_filter["organization_id"])) selected @endif
                               @endif>{{ $val->id ?? '' }} - {{ $val->_name ?? '' }}</option>
               @empty
               @endforelse
              </select>
              </div>
              </div>