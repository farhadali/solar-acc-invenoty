@extends('backend.layouts.app')
@section('title',$page_name)

@section('content')

<div class="_report_button_header">
 <a class="nav-link"  href="{{url('sales')}}" role="button"><i class="fa fa-arrow-left"></i></a>
 @can('sales-edit')
    <a class="nav-link"  title="Edit" href="{{ route('sales.edit',$data->id) }}">
                                      <i class="nav-icon fas fa-edit"></i>
     </a>
  @endcan
    
    <a style="cursor: pointer;" class="nav-link"  title="Print" onclick="javascript:printDiv('printablediv')"><i class="fas fa-print"></i></a>
      <a style="cursor: pointer;" onclick="fnExcelReport();" class="nav-link"  title="Excel Download" ><i class="fa fa-file-excel" aria-hidden="true"></i></a>
       @include('backend.message.message')
  </div>

<section class="invoice" id="printablediv">
<style>
.ttable > tr > td{
    border: 1px solid black !important;
}
#main {
    
}
 #header {
    display:table-header-group;
}
#footer {
    display:table-footer-group;
	
} 
	@media print {
  #footer {
    position: fixed;
    bottom: 0;
  }
}
</style>
         
<table width="100%;border-collapse: collapse;" style="font-size: 14px;">
	<thead id="header">
		
		<tr>
			<td colspan="2">
		<table style="width:100%;border-collapse: collapse;">
					<tr>
						<td style="text-align:left"><img src="https://i.ibb.co/TvwvB8C/bangladesh-govt-logo-A2-C7688845-seeklogo-com.png" style="width:70px;height:70px;" /></td>
					    <td style="text-align:center">
							<div style="text-align:center">
								<a>গণপ্রজাতন্ত্রী বাংলাদেশ সরকার</a><br>
								<b> জাতীয় রাজস্ব বোর্ড </b><br>
							</div>
						</td>
						<td style="text-align:right;">
							<span style="border:1px solid #000;"> মূসক - ৬.৩</span>
						</td>
					</tr>
					<tr>
						<td colspan="3" style="text-align:center"><b>কর চালানপত্র </b>  </td>
					</tr>
					<tr>
						<td colspan="3" style="text-align:center"> [বিধি ৪০ এর  উপবিধি (১) এর দফা (গ) ও দফা চ দ্রষ্টব্য ]  </td>
					</tr>
					<tr><td colspan="3" style="text-align:center; font-size:16px; ">নিবন্ধিত ব্যক্তির নামঃ </td></tr>
					<tr><td colspan="3" style="text-align:center; font-size:14px; ">চালানপত্র ইস্যুর ঠিকানা:</td></tr>
					<tr><td colspan="3" style="text-align:center; font-size:14px; ">নিবন্ধিত ব্যক্তির বিআইএনঃ </td></tr>
						
				</table>
			</td>
		</tr>
		<tr>
		<td colspan="2">
		  	<table  width="100%;border-collapse: collapse;">
			  <tr>
				<td>ক্রেতার নামঃ  </td>
				<td style="text-align:right;">চালানপত্র নম্বরঃ </td>
				
			  </tr>
			  <tr>
				<td>ক্রেতার বিআইএনঃ </td>
				<td style="text-align:right;">ইস্যুর তারিখঃ </td>
				
				
			 
			  <tr>
				<td>ক্রেতার গন্তব্যস্থল :  </td>
				<td style="text-align:right;">ইস্যুর সময়ঃ </td>
			  </tr>
				<tr>
				<td>যানবাহনের প্রকৃতি ও নম্বর:  </td>
				<td style="text-align:right;"></td>
			  </tr>
			  
		  </table>
  		</td>
		</tr>


	</thead>	
<tbody id="main">
	<tr><td colspan="2" style="height:30px;"></td></tr>
<tr style="page-break-inside: inherit;">
<td colspan="2" style="white-space: nowrap;">
<table width="100%;border-collapse: collapse;" border="1" style="background-color: inherit;" class="table table-bordered ">
      <tr>
		<td style="width:4%;text-align:center;">ক্রমিক</td>
		<td style="width:15%;text-align:center;">পণ্য বা সেবার বর্ণনা<br> (প্রযোজ্য ক্ষেত্রে ব্রান্ড নামসহ)</td>
		<td style="width:10%;text-align:center;">সরবরাহের একক</td>
		  <td style="width:10%;text-align:center;">পরিমান</td>
		<td style="width:5%;text-align:center;">একক মূল্য<br>(টাকায়)</td>
		<td style="width:10%;text-align:center;">মোট মূল্য<br> (টাকায়) </td>
		<td style="width:8%;text-align:center;">সম্পূরক<br> শুল্কের<br> হার</td>
		<td style="width:8%;text-align:center;">সম্পূরক<br> শুল্কের<br> পরিমাণ<br> (টাকায়)</td>
		<td style="width:5%;text-align:center;">মূল্য <br>সংযোজন করের<br> হার/<br>সুনির্দিষ্ট কর</td>
		<td style="width:5%;text-align:center;">মূল্য সংযোজন <br>কর /সুনির্দিষ্ট কর<br> এর পরিমান<br> (টাকায়)</td>
		<td style="width:10%;text-align:center;">সকল প্রকার<br> শুল্ক ও <br>করসহ মূল্য</td>
      </tr>									
	<tr>
		<td style="text-align:center">(১)</td>
		<td style="text-align:center">(২)</td>
		<td style="text-align:center">(৩)</td>
		<td style="text-align:center">(৪)</td>
		<td style="text-align:center">(৫)</td>
		<td style="text-align:center">(৬)</td>
		<td style="text-align:center">(৭)</td>
		<td style="text-align:center">(৮)</td>
		<td style="text-align:center">(৯)</td>
		<td style="text-align:center">(১০)</td>
		<td style="text-align:center">(১১)</td>
	</tr>

	<tr>
	    <td></td>
	    <td style="white-space: normal;"></td>
	    <td style="white-space: nowrap;"></td>
		<td style="white-space: nowrap;"> </td>
	    <td align="right" style="white-space: nowrap;"></td>
	    <td align="right" style="white-space: nowrap;"></td>
	    <td align="right" style="white-space: nowrap;"></td>
		<td align="right" style="white-space: nowrap;"></td>
    	<td align="right" style="white-space: nowrap;"></td>	
    	<td align="right" style="white-space: nowrap;"></td>	
		<td align="right" style="white-space: nowrap;"> </td>
    </tr>
     @php
        $_value_total = 0;
        $_vat_total = 0;
        $_sd_total = 0;
        $_qty_total = 0;
        $_total_discount_amount = 0;
      @endphp
                                  @forelse($data->_master_details AS $item_key=>$_item )
                                  <tr>
                                  
                                     @php
                                      $_value_total +=$_item->_value ?? 0;
                                      $_vat_total += $_item->_vat_amount ?? 0;
                                      $_sd_total += $_item->_sd_amount ?? 0;
                                      $_qty_total += $_item->_qty ?? 0;
                                      $_total_discount_amount += $_item->_discount_amount ?? 0;
                                     @endphp
<td class="text-left" style="border:1px dotted grey;" >{{($item_key+1)}}</td>
<td  class="text-left" style="border:1px dotted grey;">{!! $_item->_items->_name ?? '' !!}

</td>
<td  class="text-left" style="border:1px dotted grey;">{!! $_item->_trans_unit->_name ?? '' !!}</td>
<td  style="border:1px dotted grey;text-align: right;" >{!! _report_amount($_item->_qty ?? 0) !!}</td>
<td  style="border:1px dotted grey;text-align: right;">{!! _report_amount($_item->_sales_rate ?? 0) !!}</td>
<td  style="border:1px dotted grey;text-align: right;" >{!! _report_amount($_item->_value ?? 0) !!}</td>
<td  style="border:1px dotted grey;text-align: right;">{!! _report_amount($_item->_sd ?? 0) !!}</td>
<td  style="border:1px dotted grey;text-align: right;" >{!! _report_amount($_item->_sd_amount ?? 0) !!}</td>
<td  style="border:1px dotted grey;text-align: right;">{!! _report_amount($_item->_vat ?? 0) !!}</td>
<td  style="border:1px dotted grey;text-align: right;" >{!! _report_amount($_item->_vat_amount ?? 0) !!}</td>
<td  style="border:1px dotted grey;text-align: right;" >{!! _report_amount(($_item->_value ?? 0)+ ($_item->_sd_amount ?? 0)+($_item->_vat_amount ?? 0) )  !!}</td>
                                  </tr>
                                  @empty
                                  @endforelse
                                   <tr>
                              <td style="border:1px dotted grey;" colspan="3" class="text-right "><b> Total</b></td>

                              <td style="border:1px dotted grey;text-align: right;" class="text-right "> <b>{{ _report_amount($_qty_total ?? 0) }}</b> </td>
                              <td style="border:1px dotted grey;text-align: right;"></td>
                              <td style="border:1px dotted grey;text-align: right;" class=" text-right"><b> {{ _report_amount($_value_total ?? 0) }}</b>
                              </td>
                              <td style="border:1px dotted grey;text-align: right;"></td>
                              <td style="border:1px dotted grey;text-align: right;" class=" text-right"><b> {{ _report_amount($_sd_total ?? 0) }}</b>
                              </td>
                              <td style="border:1px dotted grey;text-align: right;"></td>
                              <td style="border:1px dotted grey;text-align: right;" class=" text-right"><b> {{ _report_amount($_vat_total ?? 0) }}</b>
                              </td>
                              <td style="border:1px dotted grey;text-align: right;" class=" text-right"><b> {{ _report_amount($_value_total+$_vat_total+$_sd_total) }}</b>
                              </td>
                            </tr>

	</table>

</tbody>
	<tfoot id="footer">
<tr><td colspan="2" style=" height:60px"></td></tr>
	
<tr>
  <td colspan="2" align="left" style="height:30px;">প্রতিষ্ঠান কর্তৃপক্ষের দায়িত্বপ্রাপ্ত ব্যক্তির নাম : </td>
  </tr>
  <tr>
   <td colspan="2" align="left" style="height:30px;"> পদবী : </td>
  </tr>
  <tr>
   <td colspan="2" align="left" style="height:30px;"> স্বাক্ষর :  </td>
  </tr>
  <tr>
   <td colspan="2" align="left" style="height:30px;"> সীল : </td>
</tr>
</tfoot>
</table>
  
  
   </section>

   @endsection

@section('script')


@endsection
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  