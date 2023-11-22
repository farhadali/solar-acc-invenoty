<div>

<div onload="window.print();" class="print_invoice" id="print_invoice" style="box-shadow: 0 0 1in -0.25in rgba(0, 0, 0, 0.5);
  padding:2mm;
  margin: 0 auto;
  width: 250px;
  background: #FFF;">
    <div class="ticket">

            <p class="centered" style="font-size: .7em;color: #666;line-height: 1.2em;text-align:center;">
        <span> <img src="{{asset($settings->logo)}}" style="width: 200px;margin-top: -50px;margin-bottom: -37px;"><br>
        <span style="text-align:center;font-weight:bold;">{{ $data->_organization->_name ?? '' }}</span>
                
        <br>{!! $data->_organization->_address ?? '' !!}
        <br>{{$settings->_phone ?? '' }}<br>
         
        <br>
        {{ invoice_barcode($data->_order_number ?? '') }}
        <br>
  <span style="font-size:22px;font-weight:bold;color:#000;">{{ $data->_order_number ?? '' }}</span>
              <br><br><span style="font-size:1em;font-weight:bold;color:#000;"> Date: {{ _view_date_formate($data->_date ?? '') }} </span>
      </p>
              
      
       <p class="centered" style="font-size: .7em;color: #666;line-height: 1.2em;margin-top:-15px;">
        
                
                <br>Customer: @if($form_settings->_defaut_customer ==$data->_ledger_id)
                      {{ $data->_referance ?? $data->_ledger->_name }}
                  @else
                  {{$data->_ledger->_name ?? '' }}
                  @endif  
                <br>Phone: {{$data->_phone ?? '' }}  
                <br> Address:{{$data->_address ?? '' }}   
                

      </p> 
      
         
                    
            
            <table style="width: 100%">
                <thead>
                    <tr style="border-top:1px solid #666;">
            
                        <th style="width: 40%;text-align:left;"><h2 style="font-size: .7em;color: #666;line-height: 1.2em;">Item</h2></th>
                        <th style="width: 20%;text-align:left;"><h2 style="font-size: .7em;color: #666;line-height: 1.2em;">Qty</h2></th>
                        <th style="width: 20%;text-align:center;"><h2 style="font-size: .7em;color: #666;line-height: 1.2em;">Rate</h2></th>
                        <th style="width: 20%;text-align:center;"><h2 style="font-size: .7em;color: #666;line-height: 1.2em;">Total</h2></th>
                    </tr>
                </thead>
        
                <tbody id="cartItems">
                 @php
                  $_total_qty = 0;
                  @endphp
                 @foreach($orderdetail as $detail)
                  @php
                  $_total_qty +=$detail->qty ?? 0;
                  @endphp
            <tr >
              <td style="font-size: .7em;color: #666;line-height: 1.2em; border-top:1px solid #a19898;">
                {{ $detail->item_name->category->parent_cat->label ?? '' }} / {{ $detail->item_name->category->label ?? '' }} / {{ $detail->item_name->label ?? '' }}
              </td>
              <td style="font-size: .7em;color: #666;line-height: 1.2em;border-top:1px solid #a19898;">
                {!! $detail->qty ?? ""  !!}
              </td>
              <td style="font-size: .7em;color: #666;line-height: 1.2em;border-top:1px solid #a19898;">
                {!! $detail->unit_price ?? ""  !!}
              </td>
              <td style="font-size: .7em;color: #666;line-height: 1.2em;border-top:1px solid #a19898;text-align:right;">
                {!! $detail->total_price ?? 0  !!}
              </td>
            </tr> 

        @endforeach     
                 
                </tbody>
        <tr>
          <td  style="font-size: .7em;color: #666;line-height: 1.5em;border-top:1px solid #000;">Sub Total: </td>
          <td  style="font-size: .7em;color: #666;line-height: 1.5em;text-align: left;border-top:1px solid #000;"> {{$_total_qty}}</td>
          <td  style="font-size: .7em;color: #666;line-height: 1.5em;border-top:1px solid #000;"> </td>
          <td style="font-size: .7em;color: #666;line-height: 1.5em;text-align:right;border-top:1px solid #000;">
            {{ $order->total_amount ?? '' }}
          </td>
        </tr>
        <tr>
          <td colspan="3" style="font-size: .7em;color: #666;line-height: 1.5em;">Coupon</td>
          <td style="font-size: .7em;color: #666;line-height: 1.5em;text-align:right"><span id="print_discount">
            {{ $order->coupon_calculated_amount ?? 0 }}
          </span></td>
        </tr>
        <tr>
          <td style="font-size: .7em;color: #666;line-height: 1.5em;" colspan="3">@lang('lang.other_discount') </td>
          <td style="font-size: .7em;color: #666;line-height: 1.5em;text-align:right"><span id="print_tax">{{ $order->other_discount ?? 0 }}</span></td>
        </tr>
        <tr>
          <td style="font-size: .7em;color: #666;line-height: 1.5em;" colspan="3">@lang('lang.service_charge') </td>
          <td style="font-size: .7em;color: #666;line-height: 1.5em;text-align:right"><span id="print_tax">{{ $order->service_charge ?? 0 }}</span></td>
        </tr>
        <tr>
          <td style="font-size: .7em;color: #666;line-height: 1.5em;" colspan="3">@lang('lang._other_charge') </td>
          <td style="font-size: .7em;color: #666;line-height: 1.5em;text-align:right"><span id="print_tax">{{ $order->_other_charge ?? 0 }}</span></td>
        </tr>
        <tr style="border-top:1px solid #666;">
          <td  style="font-size: .7em;color: #666;line-height: 1.5em;border-top:1px solid #000;"colspan="3">Net Total </td>
          <td style="font-size: .7em;color: #666;line-height: 1.5em;border-top:1px solid #000;text-align:right"><span id="print_shipping_charge">{{ $order->net_total_amount  }}</span></td>
        </tr>
        <tr>
        <td  style="text-align:left;border-top:1px solid #000;">
           <small>@lang('lang.invoice_note')</small> 
        </td>
                  <td  style="text-align:left;border-top:1px solid #000;">
           <img src="{{asset('images/google_play_qr_code.png')}}" style="width: 100%; height: auto;border-radius: 20px;" />
                    <br>
                    Your Smile Our Confidence
        </td>
        </tr>
        
            </table>
      <div style="text-align:center;font-size: .6em;">
          
          {!!  DNS1D::getBarcodeSVG($order->id, 'PHARMA'); !!}<br>
          <small>This is computer generated invoice. No signature required.</small>
      </div>    
            
        </div>
  </div>
  
</div>
<script type="text/javascript">

window.print();

</script>