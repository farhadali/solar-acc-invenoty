@extends('backend.layouts.app')
@section('title',$page_name ?? '')

@section('css')
<link rel="stylesheet" href="{{asset('backend/new_style.css')}}">
@endsection

@section('content')
@php
$__user= Auth::user();
@endphp

    <div class="content">
      <div class="container-fluid">
   <h2 class="text-center">{{__('label.import_report_dashbaord')}}</h2>
    <div class="container-fluid   " >
        <div class="row  ">
                <div class="col-md-6">
                    <ul>
                        <li><a target="__blank" href="{{url('master_vessel_wise_ligther_report')}}">{{__('label.master_vessel_wise_ligther_report')}}</a></li>
                    </ul>
                   
                </div>
        </div>
    </div>
    
</div>
    
    </div>

    @endsection