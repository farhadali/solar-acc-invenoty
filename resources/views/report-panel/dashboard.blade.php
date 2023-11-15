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
   <h2 class="text-center">{!! $page_name ?? '' !!}</h2>
    <div class="container-fluid   " >
        <div class="row  ">
                 @can('account-report-menu') 
                <div class="col-md-4">
                    <div class="card bg-default">
                    <h4>{{__('label.account_report')}}</h4>
                    <ul>
                        @can('day-book')
                        <li><a target="__blank" href="{{url('day-book')}}">{{ __('label.Day Book') }}</a></li>
                        @endcan
                        @can('bank-book')
                        <li><a target="__blank" href="{{url('bank-book')}}">{{ __('label.Bank Book') }}</a></li>
                        @endcan
                        @can('receipt-payment')
                        <li><a target="__blank" href="{{url('receipt-payment')}}">{{ __('label.Receipt & Payment') }}</a></li>
                        @endcan
                        @can('ledger-report')
                        <li><a target="__blank" href="{{url('ledger-report')}}">{{ __('label.Ledger Report') }}</a></li>
                        @endcan
                        @can('group-ledger')
                        <li><a target="__blank" href="{{url('group-ledger')}}">{{ __('label.Group Ledger Report') }}</a></li>
                        @endcan
                        @can('ledger-summary-report')
                        <li><a target="__blank" href="{{url('ledger-summary-report')}}">{{ __('label.Ledger Summary Report') }}</a></li>
                        @endcan
                        @can('trail-balance')
                        <li><a target="__blank" href="{{url('trail-balance')}}">{{ __('label.Trail Balance') }}</a></li>
                        @endcan
                        @can('work-sheet')
                        <li><a target="__blank" href="{{url('work-sheet')}}">{{ __('label.Work Sheet') }}</a></li>
                        @endcan
                        @can('balance-sheet')
                        <li><a target="__blank" href="{{url('balance-sheet')}}">{{ __('label.Balance Sheet') }}</a></li>
                        @endcan
                        @can('chart-of-account')
                        <li><a target="__blank" href="{{url('chart-of-account')}}">{{ __('label.Chart of Account') }}</a></li>
                        @endcan
                    </ul>
                   </div>
                </div>
                @endcan
                 @can('inventory-report') 
                <div class="col-md-4">
                    <div class="card bg-default">
                    <h4>{{__('label.inventory_report')}}</h4>
                    <ul>
                         @can('warranty-check')
                         <li>
                            <a target="__blank" href="{{url('warranty-check')}}" > {{ __('label.warranty-check') }}
                                  </a>
                          </li>
                        @endcan
                         @can('bill-party-statement')
                         <li>
                            <a target="__blank" href="{{url('bill-party-statement')}}" >  {{ __('label.Bill of Supplier Statement') }}
                                  </a>
                          </li>
                        @endcan
                         @can('date-wise-purchase')
                         <li>
                            <a target="__blank" href="{{url('date-wise-purchase')}}" >  {{ __('label.Date Wise Purchase') }}
                                  </a>
                          </li>
                        @endcan
                         @can('purchase-return-detail')
                         <li>
                            <a target="__blank" href="{{url('purchase-return-detail')}}" >  {{ __('label.Purchase Return Detail') }}
                                  </a>
                          </li>
                        @endcan
                         @can('date-wise-sales')
                         <li>
                            <a target="__blank" href="{{url('date-wise-sales')}}" >{{ __('label.Date Wise Sales') }}
                                  </a>
                          </li>
                        @endcan
                         
                         @can('sales-return-detail')
                         <li>
                            <a target="__blank" href="{{url('sales-return-detail')}}" >{{ __('label.Sales Return Details') }}
                                  </a>
                          </li>
                        @endcan
                         @can('stock-possition')
                         <li>
                            <a target="__blank" href="{{url('stock-possition')}}" >{{ __('label.Stock Possition') }}
                                  </a>
                          </li>
                        @endcan
                         @can('stock-ledger')
                         <li>
                            <a target="__blank" href="{{url('single-stock-ledger')}}" >{{ __('label.single-stock-ledger') }}
                                  </a>
                          </li>
                        @endcan
                         @can('stock-ledger')
                         <li>
                            <a target="__blank" href="{{url('stock-ledger')}}" >{{ __('label.Stock Ledger') }}
                                  </a>
                          </li>
                        @endcan
                         @can('stock-value')
                         <li>
                            <a target="__blank" href="{{url('stock-value')}}" >{{ __('label.Stock Value') }}
                                  </a>
                          </li>
                        @endcan
                         @can('stock-value-register')
                         <li>
                            <a target="__blank" href="{{url('stock-value-register')}}" >{{ __('label.Stock Value Register') }}
                                  </a>
                          </li>
                        @endcan
                         @can('gross-profit')
                         <li>
                            <a target="__blank" href="{{url('gross-profit')}}" >{{ __('label.Gross Profit') }}
                                  </a>
                          </li>
                        @endcan
                         @can('expired-item')
                         <li>
                            <a target="__blank" href="{{url('expired-item')}}" >{{ __('label.Expired Item') }}
                                  </a>
                          </li>
                        @endcan
                         @can('shortage-item')
                         <li>
                            <a target="__blank" href="{{url('shortage-item')}}" >{{ __('label.Shortage Item') }}
                                  </a>
                          </li>
                        @endcan
                         @can('barcode-history')
                         <li>
                            <a target="__blank" href="{{url('barcode-history')}}" >{{ __('label.Barcode History') }}
                                  </a>
                          </li>
                        @endcan
                         @can('user-wise-collection-payment')
                         <li>
                            <a target="__blank" href="{{url('user-wise-collection-payment')}}" >{{ __('label.User Wise Collection Payment') }}
                                  </a>
                          </li>
                        @endcan
                         @can('date-wise-invoice-print')
                         <li>
                            <a target="__blank" href="{{url('date-wise-invoice-print')}}" >{{ __('label.Date Wise Invoice Print') }}
                                  </a>
                          </li>
                        @endcan
                    </ul>
                   </div>
                </div>
                @endcan

                <div class="col-md-4">
                    <div class="card bg-default">
                    <h4>{{__('label._import_report')}}</h4>
                    <ul>
                        <li><a target="__blank" href="{{url('master_vessel_wise_ligther_report')}}">{{__('label.master_vessel_wise_ligther_report')}}</a></li>
                    </ul>
                   </div>
                </div>
                
        </div>
    </div>
    
</div>
    
    </div>

    @endsection