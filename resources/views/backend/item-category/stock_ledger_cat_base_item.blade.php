@php

$previous_filter= Session::get('filter_stock_ledger');

@endphp

@forelse($data as $val )
      <option value="{{$val->id}}" 
      	@if(isset($previous_filter["_item_id"]))
      	@if(in_array($val->id,$previous_filter["_item_id"])) selected @endif
        @endif  >{{ $val->_item ?? '' }}</option>
  @empty
  @endforelse