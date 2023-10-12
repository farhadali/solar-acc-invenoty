
@extends('backend.layouts.app')
@section('title',$page_name)

@section('content')
<style type="text/css">
 
  @media print {
   .table th {
    vertical-align: top;
    color: #000;
    background-color: #fff; 
}
}
  </style>
<div class="_report_button_header">
    <a class="nav-link"  href="{{url('hrm-grade')}}" role="button"><i class="fa fa-arrow-left"></i></a>
 @can('hrm-grade-edit')
 <a  href="{{ route('hrm-grade.edit',$data->id) }}" 
    class="nav-link "  title="Edit"  >
    <i class="nav-icon fas fa-edit"></i>
     </a>
  @endcan
    <a style="cursor: pointer;" class="nav-link"  title="Print" onclick="javascript:printDiv('printablediv')"><i class="fas fa-print"></i></a>
      <a style="cursor: pointer;" onclick="fnExcelReport();" class="nav-link"  title="Excel Download" ><i class="fa fa-file-excel" aria-hidden="true"></i></a>
      @include('backend.message.message')
  </div>

<section class="invoice" id="printablediv">
    
    <div class="container-fluid">
     
    <!-- /.row -->
    <table class="table">
      <tr>
        <td>{{__('label._id')}}:</td>
        <td>{{ $data->id ?? ''  }}</td>
      </tr>
      <tr>
        <td>{{__('label._grade')}}:</td>
        <td>{{ $data->_grade ?? ''  }}</td>
      </tr>
      
      <tr>
        <td>{{__('label._status')}}:</td>
        <td>{{ selected_status($data->_status) }}</td>
      </tr>
      
    </table>
    
    

    

    
    </div>
  </section>


<!-- Page specific script -->

@endsection

@section('script')

@endsection