@php
$users = \Auth::user();
$permited_branch = permited_branch(explode(',',$users->branch_ids));
$permited_costcenters = permited_costcenters(explode(',',$users->cost_center_ids));
$permited_organizations = permited_organization(explode(',',$users->organization_ids));
@endphp 

<div class="col-xs-12 col-sm-12 col-md-2 @if(sizeof($permited_organizations)==1) display_none @endif">
 <div class="form-group ">
     <label>{!! __('label.organization') !!}:<span class="_required">*</span></label>
    <select class="form-control _master_organization_id" name="organization_id" required >

       @forelse($permited_organizations as $val )
       <option value="{{$val->id}}" @if(isset($data->organization_id)) @if($data->organization_id == $val->id) selected @endif   @endif>{{ $val->id ?? '' }} - {{ $val->_name ?? '' }}</option>
       @empty
       @endforelse
     </select>
 </div>
</div>
<div class="col-xs-12 col-sm-12 col-md-2 @if(sizeof($permited_branch)==1) display_none @endif">
    <div class="form-group ">
        <label>Branch:<span class="_required">*</span></label>
       <select class="form-control _master_branch_id" name="_branch_id" required >
          
          @forelse($permited_branch as $branch )
          <option value="{{$branch->id}}" @if(isset($data->_branch_id)) @if($data->_branch_id == $branch->id) selected @endif   @endif>{{ $branch->id ?? '' }} - {{ $branch->_name ?? '' }}</option>
          @empty
          @endforelse
        </select>
    </div>
</div>
<div class="col-xs-12 col-sm-12 col-md-2 @if(sizeof($permited_costcenters)==1) display_none @endif">
    <div class="form-group ">
        <label>{{__('label.Cost center')}}:<span class="_required">*</span></label>
       <select class="form-control _cost_center_id" name="_cost_center_id" required >
          
          @forelse($permited_costcenters as $cost_center )
          <option value="{{$cost_center->id}}" @if(isset($data->_cost_center_id)) @if($data->_cost_center_id == $cost_center->id) selected @endif   @endif>{{ $cost_center->id ?? '' }} - {{ $cost_center->_name ?? '' }}</option>
          @empty
          @endforelse
        </select>
    </div>
</div>