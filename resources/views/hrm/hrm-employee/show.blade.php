
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
    <a class="nav-link"  href="{{url('hrm-employee')}}" role="button"><i class="fa fa-arrow-left"></i></a>
 @can('hrm-employee-edit')
 <a  href="{{ route('hrm-employee.edit',$data->id) }}" 
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
    <table class="table table-bordered">
      <tr>
         <tr> 
          <td colspan="6" class="text-center" style="border:none;"> {{ $settings->_top_title ?? '' }}<br>
            <b>{{$settings->name ?? '' }}</b><br/>
            <b>{{$settings->_address ?? '' }}<br>
              <b>{{$settings->_phone ?? '' }}</b><br><b>{{$settings->_email ?? '' }}</b><br><h3>{{$page_name}} </h3></td> 
            </tr>
      </tr>
      <tr>
        <td style="width: 10%;">{{__('label._code')}}:</td>
        <td style="width: 23%;">{{ $data->_code ?? ''  }}</td>

        <td style="width: 10%;">{{__('label._name')}}:</td>
        <td style="width: 23%;">{{ $data->_name ?? ''  }}</td>

        <td style="width: 10%;"></td>
        <td style="width: 23%;"><img id="output_1" class="banner_image_create" src="{{asset($data->_photo)}}"  style="max-height:100px;max-width: 100px; " /></td>
      </tr>
      <tr>
        <td style="width: 10%;">{{__('label._father')}}:</td>
        <td style="width: 23%;">{{ $data->_father ?? ''  }}</td>
        <td style="width: 10%;">{{__('label._mother')}}:</td>
        <td style="width: 23%;">{{ $data->_mother ?? ''  }}</td>
        <td style="width: 10%;">{{__('label._spouse')}}:</td>
        <td style="width: 23%;">{{ $data->_spouse ?? ''  }}</td>
      </tr>
      <tr>
        <td style="width: 10%;">{{__('label._mobile1')}}:</td>
        <td style="width: 23%;">{{ $data->_mobile1 ?? ''  }}</td>
        <td style="width: 10%;">{{__('label._mobile2')}}:</td>
        <td style="width: 23%;">{{ $data->_mobile2 ?? ''  }}</td>
        <td style="width: 10%;">{{__('label._spousesmobile')}}:</td>
        <td style="width: 23%;">{{ $data->_spousesmobile ?? ''  }}</td>
      </tr>
      <tr>
        <td style="width: 10%;">{{__('label._nid')}}:</td>
        <td style="width: 23%;">{{ $data->_nid ?? ''  }}</td>
        <td style="width: 10%;">{{__('label._gender')}}:</td>
        <td style="width: 23%;">{{ $data->_gender ?? ''  }}</td>
        <td style="width: 10%;">{{__('label._bloodgroup')}}:</td>
        <td style="width: 23%;">{{ $data->_bloodgroup ?? ''  }}</td>
      </tr>
      <tr>
        <td style="width: 10%;">{{__('label._religion')}}:</td>
        <td style="width: 23%;">{{ $data->_religion ?? ''  }}</td>
        <td style="width: 10%;">{{__('label._dob')}}:</td>
        <td style="width: 23%;">{{ $data->_dob ?? ''  }}</td>
        <td style="width: 10%;">{{__('label._education')}}:</td>
        <td style="width: 23%;">{{ $data->_education ?? ''  }}</td>
      </tr>
      <tr>
        <td style="width: 10%;">{{__('label._email')}}:</td>
        <td style="width: 23%;">{{ $data->_email ?? ''  }}</td>
        <td style="width: 10%;">{{__('label._bank')}}:</td>
        <td style="width: 23%;">{{ $data->_bank ?? ''  }}</td>
        <td style="width: 10%;">{{__('label._bankac')}}:</td>
        <td style="width: 23%;">{{ $data->_bankac ?? ''  }}</td>
      </tr>
      <tr>
        <td style="width: 10%;">{{__('label.organization_id')}}:</td>
        <td style="width: 23%;">{{ $data->organization->_name ?? ''  }}</td>
        <td style="width: 10%;">{{__('label.Branch')}}:</td>
        <td style="width: 23%;">{{ $data->_branch->_name ?? ''  }}</td>
        <td style="width: 10%;">{{__('label._cost_center_id')}}:</td>
        <td style="width: 23%;">{{ $data->_cost_center->_name ?? ''  }}</td>
      </tr>
      <tr>
        <td style="width: 10%;">{{__('label.employee_category_id')}}:</td>
        <td style="width: 23%;">{{ $data->_employee_cat->_name ?? ''  }}</td>
        <td style="width: 10%;">{{__('label._department_id')}}:</td>
        <td style="width: 23%;">{{ $data->_emp_department->_name ?? ''  }}</td>
        <td style="width: 10%;">{{__('label._jobtitle_id')}}:</td>
        <td style="width: 23%;">{{ $data->_emp_designation->_name ?? ''  }}</td>
      </tr>
      <tr>
        <td style="width: 10%;">{{__('label._grade_id')}}:</td>
        <td style="width: 23%;">{{ $data->_emp_grade->_name ?? ''  }}</td>
        <td style="width: 10%;">{{__('label._location')}}:</td>
        <td style="width: 23%;">{{ $data->_emp_location->_name ?? ''  }}</td>
        <td style="width: 10%;">{{__('label._status')}}:</td>
        <td style="width: 23%;">{{ selected_status($data->_status) }}</td>
      </tr>
      
      
    </table>
    
    

    

    
    </div>
  </section>


<!-- Page specific script -->

@endsection

@section('script')

@endsection