<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title></title>
  <style type="text/css">
    @media print {
  #spacer {height: 2em;} /* height of footer + a little extra */
  #footer {
    position: fixed;
    bottom: 0;
  }
}
  </style>
</head>
<body>



<section class="invoice" id="printablediv" style="">
		
            <table class="table" style="border-collapse: collapse;">
            	<tr>
            		<td colspan="2" style="text-align: left;">
                   <img style="" src="{{url('/')}}/{{$settings->logo}}" alt="{{$settings->name ?? '' }}"   ><br>
            		
		        
            		</td>
                <td colspan="2">
                    <strong>{{ $data->_organization->_name ?? '' }}</strong><br>
             {{$data->_organization->_address ?? '' }}<br>
             {{$data->_organization->_bin ?? '' }}<br>
         
      Print Time: {{ date('d-m-Y h:i:sa ')}}
                </td>
            	</tr>
              <tr>
                <td colspan="4" style="text-align:center;"><b>Dellivery Challan</b></td>
              </tr>
                <tr>
               
                <td colspan="4" style="border: 1px dotted grey;">
                  <table style="text-align: left;">
                    <tr> 
                      <td style="border:none;" >

                       {{ invoice_barcode($data->_order_number ?? '') }}
                      
                    </td> 
                    </tr>
                    <tr> 
                      <td style="border:none;" > Invoice No: {{ $data->_order_number ?? '' }}</td></tr>
                  <tr> <td style="border:none;" > Date: {{ _view_date_formate($data->_date ?? '') }}</td></tr>
                  </table>
                </td>
              </tr>
            	<tr>
            		<td colspan="4" style="text-align: left;border: 1px dotted grey;">
            			<table style="">
            				 <tr> <td style="border:none;" > <b>Customer:</b> @if($form_settings->_defaut_customer ==$data->_ledger_id)
                      {{ $data->_referance ?? $data->_ledger->_name }}
                  @else
                  {{$data->_ledger->_name ?? '' }}
                  @endif</td></tr>
            				
			                <tr> <td style="border:none;" >Phone:{{$data->_phone ?? '' }} </td></tr>
			                <tr> <td style="border:none;" >Address:{{$data->_address ?? '' }} </td></tr>
            			</table>
            		</td>
            	
            	</tr>
               
               
                <tbody>
                   <tr>
                   	<td style="text-align: left;border:1px dotted grey;width:5%;">SL</td>
                   	<td style="text-align: left;border:1px dotted grey;width:50%;">Item</td>
                    <td style="text-align: left;border:1px dotted grey;width:15%;">Unit</td>
                   	<td style="text-align: right;border:1px dotted grey;width:30%;">QTY</td>
                   </tr>
                   @php
                                    $_qty_total = 0;
                                  @endphp
                                  @forelse($data->_master_details AS $item_key=>$_item )
                                  <tr>
                                  
                                     @php
                                      $_qty_total += $_item->_qty ?? 0;
                                     @endphp
<td class="text-left" style="border:1px dotted grey;" >{{($item_key+1)}}</td>
<td  class="text-left" style="border:1px dotted grey;">{!! $_item->_items->_name ?? '' !!}<br>
   @php 
                                                  $_barcodes_string = $_item->_barcode ?? '';
                                                  $_barcodes_string_length = strlen($_item->_barcode ?? '');
                                                  $_barcodes = explode(",",$_barcodes_string);
                                              @endphp
                                              @if($_barcodes_string_length > 0)
                                              <b>SN:</b>
                                                  @forelse($_barcodes as $barcode)
                                                    <span style="background: #f5f5f5;">{{$barcode ?? '' }},</span>
                                                  @empty
                                                  @endforelse
                                              @endif

</td>
<td  class="text-left" style="border:1px dotted grey;">{!! $_item->_trans_unit->_name ?? '' !!}</td>
<td  style="border:1px dotted grey;text-align: right;" >{!! _report_amount($_item->_qty ?? 0) !!}</td>
                                  </tr>
                                  @empty
                                  @endforelse
                                   <tr>
                              <td style="border:1px dotted grey;" colspan="3" class="text-right "><b>Total</b></td>

                              <td style="border:1px dotted grey;text-align: right;" class="text-right "> <b>{{ _report_amount($_qty_total ?? 0) }}</b> </td>
                            </tr>
   

<tr>
  <td style="border:1px dotted grey;" colspan="4" class="text-left" >Total Qty (In Words) :  {{ only_number_to_text($_qty_total ?? 0) }}</td>
</tr>
<tr>
  <td style="border:1px dotted grey;" colspan="4" class="text-left" >{{__('label._note')}} :  {!! $data->_note ?? '' !!}</td>
</tr>
<tr>
  <td style="border:1px dotted grey;" colspan="4" class="text-left" >{{__('label._delivery_details')}} :  {!! $data->_delivery_details ?? '' !!}</td>
</tr>



                   
                
				</tbody>
        
            </table>

            <table style="width:100%;">
               <tfoot  >
                  <tr>
                    <td colspan="4">Received the above items in good condition with thanks form SAIF Powertec Ltd.</td>
                  </tr>
                  <tr>
                    <td colspan="4" style="height:100px;"></td>
                  </tr>
                     <tr>
                       <td style="width: 25%;" >
                         <span style="border-bottom: 1px solid #f5f9f9;">Received By</span>
                       </td>
                       <td  style="width: 25%;">
                          <span style="border-bottom: 1px solid #f5f9f9;">Prepared By</span>
                       </td>
                       <td  style="width: 25%;">
                         <span style="border-bottom: 1px solid #f5f9f9;">Received By</span>
                       </td>
                       <td  style="width: 25%;">
                         <span style="border-bottom: 1px solid #f5f9f9;">Received By</span>
                       </td>
                     </tr> 
              </tfoot>
            </table>
            
        </section>
	
</body>
</html>

<script type="text/javascript">
  window.print();
</script>