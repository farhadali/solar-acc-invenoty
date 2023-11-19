<nav class="main-header navbar navbar-expand  fixed-top  navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav" >
      
     
      <a href="{{url('home')}}" class="brand-link _project_main_nav_logo">
      <img src="{{asset('/')}}{{$settings->logo ?? ''}}" alt="{{$settings->name ?? '' }}" class="brand-image  elevation-3"  >
      <span  class="project_title"></span>
    </a>
    <li class="nav-item">
        <a class="nav-link _pushmenu" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
     
 <!-- Messages Dropdown Menu -->
      <li class="nav-item ">
         @can('pos-sales-list')
          
           <a  href="{{url('pos-sales')}}" class="dropdown-item custom_nav_item" title="POS Sales">
            <i class="fa fa-shopping-cart " style="margin-top: 9px;" aria-hidden="true"></i> 
          </a>
         @endcan
       </li>
       @can('rlp-module') 
      <li class="nav-item dropdown remove_from_header">
        <a class="nav-link" data-toggle="dropdown" href="#">
           {{ __('label.procurement') }} <i class="right fas fa-angle-down"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
       <p style="padding-left: 20px;"><b>{{__('label.entry')}}</b></p>
       <div class="dropdown-divider"></div>
        @can('approval-chain-list')
         <div style="display: flex;">
         <a href="{{route('approval-chain.index')}}" class="dropdown-item">
            <i class="fa fa-fax mr-2" aria-hidden="true"></i> {{ __('label.approval-chain') }}
          </a>
          <a  href="{{route('approval-chain.create')}}" class="dropdown-item text-right">
            <i class="nav-icon fas fa-plus"></i>
          </a>
        </div>
        @endcan
        @can('rlp-list')
         <div style="display: flex;">
         <a href="{{route('rlp.index')}}" class="dropdown-item">
            <i class="fa fa-fax mr-2" aria-hidden="true"></i> {{ __('label.rlp-info') }}
          </a>
          <a  href="{{route('rlp.create')}}" class="dropdown-item text-right">
            <i class="nav-icon fas fa-plus"></i>
          </a>
        </div>
        @endcan
        @can('import-purchase-list')
         <div style="display: flex;">
         <a href="{{route('import-purchase.index')}}" class="dropdown-item">
            <i class="fa fa-fax mr-2" aria-hidden="true"></i> {{ __('label.import-purchase') }}
          </a>
          <a  href="{{route('import-purchase.create')}}" class="dropdown-item text-right">
            <i class="nav-icon fas fa-plus"></i>
          </a>
        </div>
        @endcan
      </li>
    @endcan
        @can('hrm-module') 
      <li class="nav-item dropdown remove_from_header">
        <a class="nav-link" data-toggle="dropdown" href="#">
           {{ __('label.hrm') }} <i class="right fas fa-angle-down"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
       <p style="padding-left: 20px;"><b>{{__('label.leave')}}</b></p>
       <div class="dropdown-divider"></div>
        @can('week-work-day')
        <div style="display: flex;">
         <a href="{{url('weekworkday')}}" class="dropdown-item">
            <i class="fa fa-fax mr-2" aria-hidden="true"></i> {{ __('label.weekworkday') }}
          </a>
        </div>
        @endcan
        @can('holidays-list')
        <div style="display: flex;">
         <a href="{{url('holidays')}}" class="dropdown-item">
            <i class="fa fa-fax mr-2" aria-hidden="true"></i> {{ __('label.holidays') }}
          </a>
        </div>
        @endcan
        @can('leave-type-list')
        <div style="display: flex;">
         <a href="{{url('leave-type')}}" class="dropdown-item">
            <i class="fa fa-fax mr-2" aria-hidden="true"></i> {{ __('label.leave-type') }}
          </a>
          <a  href="{{route('leave-type.create')}}" class="dropdown-item text-right">
            <i class="nav-icon fas fa-plus"></i>
          </a>
        </div>
        @endcan

        @can('hrm-employee-list')
        <p style="padding-left: 20px;"><b>{{__('label.hrm-employee')}}</b></p>
       <div class="dropdown-divider"></div>
        <div style="display: flex;">
         <a href="{{url('hrm-employee')}}" class="dropdown-item">
            <i class="fa fa-fax mr-2" aria-hidden="true"></i> {{ __('label.hrm-employee') }}
          </a>
          <a  href="{{route('hrm-employee.create')}}" class="dropdown-item text-right">
            <i class="nav-icon fas fa-plus"></i>
          </a>
        </div>
        @endcan
        <p style="padding-left: 20px;"><b>{{__('label.payrol-information')}}</b></p>
       <div class="dropdown-divider"></div>
        @can('pay-heads-list')
        <div style="display: flex;">
         <a href="{{url('pay-heads')}}" class="dropdown-item">
            <i class="fa fa-fax mr-2" aria-hidden="true"></i> {{ __('label.pay-heads') }}
          </a>
          <a  href="{{route('pay-heads.create')}}" class="dropdown-item text-right">
            <i class="nav-icon fas fa-plus"></i>
          </a>
        </div>
        @endcan
        
        @can('hrm-department-list')
        <div style="display: flex;">
         <a href="{{url('hrm-department')}}" class="dropdown-item">
            <i class="fa fa-fax mr-2" aria-hidden="true"></i> {{ __('label.hrm-department') }}
          </a>
          <a  href="{{route('hrm-department.create')}}" class="dropdown-item text-right">
            <i class="nav-icon fas fa-plus"></i>
          </a>
        </div>
        @endcan
        @can('hrm-grade-list')
        <div style="display: flex;">
         <a href="{{url('hrm-grade')}}" class="dropdown-item">
            <i class="fa fa-fax mr-2" aria-hidden="true"></i> {{ __('label.hrm-grade') }}
          </a>
          <a  href="{{route('hrm-grade.create')}}" class="dropdown-item text-right">
            <i class="nav-icon fas fa-plus"></i>
          </a>
        </div>
        @endcan
        @can('hrm-emp-location-list')
        <div style="display: flex;">
         <a href="{{url('hrm-emp-location')}}" class="dropdown-item">
            <i class="fa fa-fax mr-2" aria-hidden="true"></i> {{ __('label.hrm-emp-location') }}
          </a>
          <a  href="{{route('hrm-emp-location.create')}}" class="dropdown-item text-right">
            <i class="nav-icon fas fa-plus"></i>
          </a>
        </div>
        @endcan
        @can('hrm-emp-category-list')
        <div style="display: flex;">
         <a href="{{url('hrm-emp-category')}}" class="dropdown-item">
            <i class="fa fa-fax mr-2" aria-hidden="true"></i> {{ __('label.hrm-emp-category') }}
          </a>
          <a  href="{{route('hrm-emp-category.create')}}" class="dropdown-item text-right">
            <i class="nav-icon fas fa-plus"></i>
          </a>
        </div>
        @endcan
        @can('hrm-designation-list')
        <div style="display: flex;">
         <a href="{{url('hrm-designation')}}" class="dropdown-item">
            <i class="fa fa-fax mr-2" aria-hidden="true"></i> {{ __('label.hrm-designation') }}
          </a>
          <a  href="{{route('hrm-designation.create')}}" class="dropdown-item text-right">
            <i class="nav-icon fas fa-plus"></i>
          </a>
        </div>
        @endcan
        
      </li>
       <li class="nav-item ">
           <a  href="{{url('report-panel')}}" class="dropdown-item custom_nav_item" >
            <span style="margin-bottom:9px;">{{__('label.report_panel')}} </span>
          </a>
         
       </li>
    @endcan
       @can('account-menu') 
      <li class="nav-item dropdown remove_from_header">
        <a class="nav-link" data-toggle="dropdown" href="#">
           {{ __('label.Accounts') }} <i class="right fas fa-angle-down"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
       
        @can('cash-receive')
        <div style="display: flex;">
         <a href="{{url('voucher')}}?_voucher_type=CR" class="dropdown-item">
            <i class="fa fa-fax mr-2" aria-hidden="true"></i> {{ __('label.Cash Receive') }}
          </a>
           <a  href="{{url('cash-receive')}}" class="dropdown-item text-right">
            <i class="nav-icon fas fa-plus"></i>
          </a>
        </div>
         @endcan
        
        @can('cash-payment')
         <div class="dropdown-divider"></div>
        <div style="display: flex;">
         <a href="{{url('voucher')}}?_voucher_type=CP" class="dropdown-item">
            <i class="fa fa-fax mr-2" aria-hidden="true"></i> {{ __('label.Cash Payment') }}
          </a>
           <a  href="{{url('cash-payment')}}" class="dropdown-item text-right">
            <i class="nav-icon fas fa-plus"></i>
          </a>
        </div>
         @endcan 
        
        @can('bank-receive')
         <div class="dropdown-divider"></div>
        <div style="display: flex;">
         <a href="{{url('voucher')}}?_voucher_type=BR" class="dropdown-item">
            <i class="fa fa-fax mr-2" aria-hidden="true"></i> {{ __('label.Bank Receive') }}
          </a>
           <a  href="{{url('bank-receive')}}" class="dropdown-item text-right">
            <i class="nav-icon fas fa-plus"></i>
          </a>
        </div>
         @endcan 
         
        @can('bank-payment')
        <div class="dropdown-divider"></div>
        <div style="display: flex;">
         <a href="{{url('voucher')}}?_voucher_type=BP" class="dropdown-item">
            <i class="fa fa-fax mr-2" aria-hidden="true"></i> {{ __('label.Bank Payment') }}
          </a>
           <a  href="{{url('bank-payment')}}" class="dropdown-item text-right">
            <i class="nav-icon fas fa-plus"></i>
          </a>
        </div>
         @endcan 
         
          @can('voucher-list')
          <div class="dropdown-divider"></div>
        <div style="display: flex;">
         <a href="{{url('voucher')}}" class="dropdown-item">
            <i class="fa fa-fax mr-2" aria-hidden="true"></i> {{ __('label.Voucher') }}
          </a>
           <a  href="{{route('voucher.create')}}" class="dropdown-item text-right">
            <i class="nav-icon fas fa-plus"></i>
          </a>
        </div>
         @endcan
          @can('easy-voucher-list')
         <div class="dropdown-divider"></div>
        <div style="display: flex;">
         <a href="{{url('easy-voucher')}}" class="dropdown-item">
            <i class="fa fa-fax mr-2" aria-hidden="true"></i> {{ __('label.Easy Voucher') }}
          </a>
           <a  href="{{route('easy-voucher.create')}}" class="dropdown-item text-right">
            <i class="nav-icon fas fa-plus"></i>
          </a>
        </div>
         @endcan 
          @can('inter-project-voucher-list')
         <div class="dropdown-divider"></div>
        <div style="display: flex;">
         <a href="{{url('inter-project-voucher')}}" class="dropdown-item">
            <i class="fa fa-fax mr-2" aria-hidden="true"></i> {{ __('label.inter-project-voucher') }}
          </a>
           <a  href="{{route('inter-project-voucher.create')}}" class="dropdown-item text-right">
            <i class="nav-icon fas fa-plus"></i>
          </a>
        </div>
         @endcan 
         
      </li>
    @endcan
     
      <!-- Messages Dropdown Menu -->
      <li class="nav-item dropdown"> </li>
       @can('inventory-menu') 
      <li class="nav-item dropdown remove_from_header">
        <a class="nav-link" data-toggle="dropdown" href="#">
          
          {{ __('label.Inventory') }} <i class="right fas fa-angle-down"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
           
          @can('purchase-order-list')
          
           <div style="display: flex;">
           <a href="{{url('purchase-order')}}" class="dropdown-item">
            <i class="fa fa-list-alt mr-2" aria-hidden="true"></i> {{ __('label.purchase order') }}
          </a>
           <a  href="{{route('purchase-order.create')}}" class="dropdown-item text-right">
            <i class="nav-icon fas fa-plus"></i>
          </a>
        </div>
         @endcan
        @can('purchase-list')
         <div class="dropdown-divider"></div>
          
           <div style="display: flex;">
           <a href="{{url('purchase')}}" class="dropdown-item">
            <i class="fa fa-list-alt mr-2" aria-hidden="true"></i> {{ __('label.material_receive') }}
          </a>

          <a  href="{{ route('purchase.create') }}" 
          class="dropdown-item text-right  " > 
            <i class="nav-icon fas fa-plus"></i> </a>

          
        </div>
         @endcan
          
        @can('purchase-return-list')
         <div class="dropdown-divider"></div>
        <div style="display: flex;">
           <a href="{{url('purchase-return')}}" class="dropdown-item">
            <i class="fa fa-list-alt mr-2" aria-hidden="true"></i> {{ __('label.material_return') }}
          </a>
           <a  href="{{ route('purchase-return.create') }}" 
          class="dropdown-item text-right  " > 
            <i class="nav-icon fas fa-plus"></i> </a>
        </div>
         @endcan
          @can('production-list')
          <div class="dropdown-divider"></div> 
        <div style="display: flex;">
           <a href="{{url('production')}}" class="dropdown-item">
            <i class="fa fa-recycle mr-2" aria-hidden="true"></i> {{ __('label.finished_goods_fabrication') }}
          </a>
           <a  href="{{route('production.create')}}" class="dropdown-item text-right">
            <i class="nav-icon fas fa-plus"></i>
          </a>
        </div>
         @endcan 
         @can('material-issue-list')
         <div class="dropdown-divider"></div>  
          
        <div style="display: flex;">
           <a href="{{url('material-issue')}}" class="dropdown-item">
            <i class="fa fa-arrow-circle-right mr-2" aria-hidden="true"></i> {{ __('label.material_issued') }}
          </a>
           <a  href="{{route('material-issue.create')}}" class="dropdown-item text-right">
            <i class="nav-icon fas fa-plus"></i>
          </a>
        </div>
         @endcan
         @can('material-issue-return-list')
         <div class="dropdown-divider"></div>  
          
        <div style="display: flex;">
           <a href="{{url('material-issue-return')}}" class="dropdown-item">
            <i class="fa fa-arrow-circle-down mr-2" aria-hidden="true"></i> {{ __('label.material_issue_return') }}
          </a>
           <a  href="{{route('material-issue-return.create')}}" class="dropdown-item text-right">
            <i class="nav-icon fas fa-plus"></i>
          </a>
        </div>
         @endcan  
          
       @can('sales-order-list')
         <div class="dropdown-divider"></div>
          
        <div style="display: flex;">
           <a href="{{url('sales-order')}}" class="dropdown-item">
            <i class="fa fa-shopping-cart mr-2" aria-hidden="true"></i> {{ __('label.sales-order-list') }}
          </a>
          <a  href="{{ route('sales-order.create') }}" 
          class="dropdown-item text-right  "> 
            <i class="nav-icon fas fa-plus"></i> </a>

          </a>
        </div>
         @endcan
          @can('pos-sales-list')
         <div class="dropdown-divider"></div>
          
        <div style="display: flex;">
           <a href="{{url('sales')}}" class="dropdown-item">
            <i class="fa fa-shopping-cart mr-2" aria-hidden="true"></i> {{ __('label.pos sales-list') }}
          </a>
           <a  href="{{url('pos-sales')}}" class="dropdown-item text-right">
            <i class="fa fa-shopping-cart " style="color: green;margin-top: 9px;" aria-hidden="true"></i> 
          </a>
        </div>
         @endcan
          
       @can('sales-list')
         <div class="dropdown-divider"></div>
          
        <div style="display: flex;">
           <a href="{{url('sales')}}" class="dropdown-item">
            <i class="fa fa-shopping-cart mr-2" aria-hidden="true"></i> {{ __('label.material_issue') }}
          </a>
           <a  href="{{ route('sales.create') }}" 
          class="dropdown-item text-right  " > 
            <i class="nav-icon fas fa-plus"></i> </a>

          </a>
        </div>
         @endcan
       @can('sales-return-list')
         <div class="dropdown-divider"></div>  
          
        <div style="display: flex;">
           <a href="{{url('sales-return')}}" class="dropdown-item">
            <i class="fa fa-shopping-cart mr-2" aria-hidden="true"></i> {{ __('label.issued_material_return') }}
          </a>
           <a  href="{{route('sales-return.create')}}" class="dropdown-item text-right">
            <i class="nav-icon fas fa-plus"></i>
          </a>
        </div>
         @endcan 
       
        
       @can('damage-list')
         <div class="dropdown-divider"></div>  
          
        <div style="display: flex;">
           <a href="{{url('damage')}}" class="dropdown-item">
            <i class="fa fa-recycle mr-2" aria-hidden="true"></i> {{ __('label.damage_adjustment') }}
          </a>
           <a  href="{{route('damage.create')}}" class="dropdown-item text-right">
            <i class="nav-icon fas fa-plus"></i>
          </a>
        </div>
         @endcan
          @can('transfer-list')
         <div class="dropdown-divider"></div>  
        <div style="display: flex;">
           <a href="{{url('transfer')}}" class="dropdown-item">
            <i class="fa fa-recycle mr-2" aria-hidden="true"></i> {{ __('label.project_to_transfer') }}
          </a>
           <a  href="{{route('transfer.create')}}" class="dropdown-item text-right">
            <i class="nav-icon fas fa-plus"></i>
          </a>
        </div>
         @endcan

          
       
          @can('third-party-service-menu')
          <div class="dropdown-divider"></div> 
        <div style="display: flex;">
           <a href="{{url('third-party-service')}}" class="dropdown-item">
            <i class="fa fa-recycle mr-2" aria-hidden="true"></i>{{ __('label.third-party-service-list') }}
          </a>
           <a  href="{{route('third-party-service.create')}}" class="dropdown-item text-right">
            <i class="nav-icon fas fa-plus"></i>
          </a>
        </div>
         @endcan 
          
       @can('warranty-manage-menu')
          <div class="dropdown-divider"></div> 
        <div style="display: flex;">
           <a href="{{url('warranty-manage')}}" class="dropdown-item">
            <i class="fa fa-recycle mr-2" aria-hidden="true"></i> {{ __('label.warranty-manage-list') }}
          </a>
           <a  href="{{route('warranty-manage.create')}}" class="dropdown-item text-right">
            <i class="nav-icon fas fa-plus"></i>
          </a>
        </div>
         @endcan 
          
       @can('item-replace-menu')
          <div class="dropdown-divider"></div> 
        <div style="display: flex;">
           <a href="{{url('item-replace')}}" class="dropdown-item">
            <i class="fa fa-recycle mr-2" aria-hidden="true"></i>{{ __('label.item-replace-list') }}
          </a>
           <a  href="{{route('item-replace.create')}}" class="dropdown-item text-right">
            <i class="nav-icon fas fa-plus"></i>
          </a>
        </div>
         @endcan  

      
       @can('individual-replacement-menu')
          <div class="dropdown-divider"></div> 
        <div style="display: flex;">
           <a href="{{url('individual-replacement')}}" class="dropdown-item">
            <i class="fa fa-recycle mr-2" aria-hidden="true"></i>{{ __('label.individual-replacement-list') }}
          </a>
           <a  href="{{route('individual-replacement.create')}}" class="dropdown-item text-right">
            <i class="nav-icon fas fa-plus"></i>
          </a>
        </div>
         @endcan 
       @can('w-item-receive-from-supp-menu')
          <div class="dropdown-divider"></div> 
        <div style="display: flex;">
           <a href="{{url('w-item-receive-from-supp')}}" class="dropdown-item">
            <i class="fa fa-recycle mr-2" aria-hidden="true"></i>{{ __('label.w-item-receive-from-supp-list') }}
          </a>
           <a  href="{{route('w-item-receive-from-supp.create')}}" class="dropdown-item text-right">
            <i class="nav-icon fas fa-plus"></i>
          </a>
        </div>
         @endcan 
          @can('restaurant-module') 
         <div class="dropdown-divider"></div>
          <p style="text-align: center;"><b>Restaurant</b></p>

          <div style="display: flex;">
          @can('restaurant-pos')
           <a href="{{url('restaurant-pos')}}" class="dropdown-item">
            <i class="fa fa-table mr-2" aria-hidden="true"></i> {{ __('label.Restaurant POS') }}
          </a>
         
          @endcan
        </div>
          <div class="dropdown-divider"></div>   
      
        <div style="display: flex;">
          @can('restaurant-sales-list')
           <a href="{{url('restaurant-sales')}}" class="dropdown-item">
            <i class="fa fa-table mr-2" aria-hidden="true"></i> {{ __('label.Restaurant Sales') }}
          </a>
          @endcan
           @can('restaurant-sales-create')
           <a  href="{{route('restaurant-sales.create')}}" class="dropdown-item text-right">
            <i class="nav-icon fas fa-plus"></i>
          </a>
          @endcan
        </div>

        @can('kitchen-menu')
        <div style="display: flex;">
          @can('kitchen-list')
           <a href="{{url('kitchen')}}" class="dropdown-item">
            <i class="fa fa-table mr-2" aria-hidden="true"></i> Kitchen Panel
          </a>
          @endcan
          </div>
        @endcan 
          @endcan

      </li>
    @endcan
       @can('inventory-report') 
      <li class="nav-item dropdown remove_from_header">
        <a class="nav-link" data-toggle="dropdown" href="#">
          
          {{ __('label.Inventory Report') }} <i class="right fas fa-angle-down"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
            
        @can('warranty-check')
          
           <div style="display: flex;">
           <a href="{{url('warranty-check')}}" class="dropdown-item">
            <i class="fa fa-list-alt mr-2" aria-hidden="true"></i> {{ __('label.warranty-check') }}
          </a>
        </div>
         @endcan
         
        @can('bill-party-statement')
          
           <div style="display: flex;">
           <a href="{{url('bill-party-statement')}}" class="dropdown-item">
            <i class="fa fa-list-alt mr-2" aria-hidden="true"></i> {{ __('label.Bill of Supplier Statement') }}
          </a>
        </div>
         @endcan
         <div class="dropdown-divider"></div>
          
        @can('date-wise-purchase')
        <div style="display: flex;">
           <a href="{{url('date-wise-purchase')}}" class="dropdown-item">
            <i class="fa fa-list-alt mr-2" aria-hidden="true"></i> {{ __('label.Date Wise Purchase') }}
          </a>
        </div>
         
         @endcan
         
    
           
       @can('purchase-return-detail')
        <div style="display: flex;">
           <a href="{{url('purchase-return-detail')}}" class="dropdown-item">
            <i class="fa fa-list-alt mr-2" aria-hidden="true"></i> {{ __('label.Purchase Return Detail') }}
          </a>
        </div>
         @endcan 
         
       @can('date-wise-sales')
       <div class="dropdown-divider"></div>  
        <div style="display: flex;">
           <a href="{{url('date-wise-sales')}}" class="dropdown-item">
            <i class="fa fa-list-alt mr-2" aria-hidden="true"></i> {{ __('label.Date Wise Sales') }}
          </a>
        </div>
         @endcan 
       @can('actual-sales-report')
        <div style="display: flex;">
           <a href="{{url('filter-actual-sales')}}" class="dropdown-item">
            <i class="fa fa-list-alt mr-2" aria-hidden="true"></i> {{ __('label.actual-sales-report') }}
          </a>
        </div>
         @endcan 

       @can('date-wise-restaurant-sales')
       <div class="dropdown-divider"></div>  
        <div style="display: flex;">
           <a href="{{url('date-wise-restaurant-sales')}}" class="dropdown-item">
            <i class="fa fa-list-alt mr-2" aria-hidden="true"></i> {{ __('label.Date Wise Restaurant Sales') }}
          </a>
        </div>
         @endcan 
          
      
          
       @can('sales-return-detail')
        <div style="display: flex;">
           <a href="{{url('sales-return-detail')}}" class="dropdown-item">
            <i class="fa fa-list-alt mr-2" aria-hidden="true"></i> {{ __('label.Sales Return Details') }}
          </a>
        </div>
         @endcan 
         <div class="dropdown-divider"></div>  
       @can('stock-possition')
        <div style="display: flex;">
           <a href="{{url('stock-possition')}}" class="dropdown-item">
            <i class="fa fa-list-alt mr-2" aria-hidden="true"></i>{{ __('label.Stock Possition') }}
          </a>
        </div>
         @endcan 
          
       @can('stock-ledger')
        <div style="display: flex;">
           <a href="{{url('stock-ledger')}}" class="dropdown-item">
            <i class="fa fa-list-alt mr-2" aria-hidden="true"></i>{{ __('label.Stock Ledger') }}
          </a>
        </div>
         @endcan 
       @can('stock-ledger')
        <div style="display: flex;">
           <a href="{{url('single-stock-ledger')}}" class="dropdown-item">
            <i class="fa fa-list-alt mr-2" aria-hidden="true"></i>{{ __('label.single-stock-ledger') }}
          </a>
        </div>
         @endcan 
          
       @can('stock-value')
        <div style="display: flex;">
           <a href="{{url('stock-value')}}" class="dropdown-item">
            <i class="fa fa-list-alt mr-2" aria-hidden="true"></i>{{ __('label.Stock Value') }}
          </a>
        </div>
         @endcan 
          
       @can('stock-value-register')
        <div style="display: flex;">
           <a href="{{url('stock-value-register')}}" class="dropdown-item">
            <i class="fa fa-list-alt mr-2" aria-hidden="true"></i>{{ __('label.Stock Value Register') }}
          </a>
        </div>
         @endcan 
          <div class="dropdown-divider"></div>
       @can('gross-profit')
        <div style="display: flex;">
           <a href="{{url('gross-profit')}}" class="dropdown-item">
            <i class="fa fa-list-alt mr-2" aria-hidden="true"></i>{{ __('label.Gross Profit') }}
          </a>
        </div>
         @endcan
         <div class="dropdown-divider"></div>  
       @can('expired-item')
        <div style="display: flex;">
           <a href="{{url('expired-item')}}" class="dropdown-item">
            <i class="fa fa-list-alt mr-2" aria-hidden="true"></i>{{ __('label.Expired Item') }}
          </a>
        </div>
         @endcan   
       @can('shortage-item')
        <div style="display: flex;">
           <a href="{{url('shortage-item')}}" class="dropdown-item">
            <i class="fa fa-list-alt mr-2" aria-hidden="true"></i>{{ __('label.Shortage Item') }}
          </a>
        </div>
         @endcan  
      @can('barcode-history')
        <div style="display: flex;">
           <a href="{{url('barcode-history')}}" class="dropdown-item">
            <i class="fa fa-list-alt mr-2" aria-hidden="true"></i>{{ __('label.Barcode History') }}
          </a>
        </div>
      @endcan 
         
      @can('user-wise-collection-payment')
      <div class="dropdown-divider"></div>
          <a href="{{url('user-wise-collection-payment')}}" class="dropdown-item">
            <i class="fa fa-sitemap mr-2" aria-hidden="true"></i>{{ __('label.User Wise Collection Payment') }}
          </a>
      @endcan
          
           @can('date-wise-invoice-print')
           <div class="dropdown-divider"></div>
          <a href="{{url('date-wise-invoice-print')}}" class="dropdown-item">
            <i class="fa fa-sitemap mr-2" aria-hidden="true"></i>{{ __('label.Date Wise Invoice Print') }}
          </a>
         @endcan
        
          @can('date-wise-restaurant-invoice-print')
            <div class="dropdown-divider"></div>
          <a href="{{url('date-wise-restaurant-invoice-print')}}" class="dropdown-item">
            <i class="fa fa-sitemap mr-2" aria-hidden="true"></i>{{ __('label.Date Wise Restaurant Invoice Print') }}
          </a>
         @endcan
          
           @can('delivery-man-sales')
           <div class="dropdown-divider"></div>
          <a href="{{url('delivery-man-sales')}}" class="dropdown-item">
            <i class="fa fa-sitemap mr-2" aria-hidden="true"></i>{{ __('label.Delivery Man Sales') }}
             </a>
         @endcan
          
           @can('delivery-man-sales-return')
           <div class="dropdown-divider"></div>
          <a href="{{url('delivery-man-sales-return')}}" class="dropdown-item">
            <i class="fa fa-sitemap mr-2" aria-hidden="true"></i>{{ __('label.Delivery Man Sales Return') }}
             </a>
         @endcan
          
           @can('sales-man-sales-return')
           <div class="dropdown-divider"></div>
          <a href="{{url('sales-man-sales-return')}}" class="dropdown-item">
            <i class="fa fa-sitemap mr-2" aria-hidden="true"></i>{{ __('label.Sales Man Sales Return') }} </a>
         @endcan
          
           @can('sales-man-invoice')
           <div class="dropdown-divider"></div>
          <a href="{{url('sales-man-invoice')}}" class="dropdown-item">
            <i class="fa fa-sitemap mr-2" aria-hidden="true"></i>{{ __('label.Sales Man Invoice') }} </a>
         @endcan
          
           @can('delivery-man-sales-invoice')
           <div class="dropdown-divider"></div>
          <a href="{{url('delivery-man-sales-invoice')}}" class="dropdown-item">
            <i class="fa fa-sitemap mr-2" aria-hidden="true"></i>{{ __('label.Delivery Man Sales Invoice') }} </a>
         @endcan

          @can('restaurant-report-menu')
      
       <p style="text-align: center;"><b>Restaurant Report</b></p>
        @can('day-wise-summary-report')
        <div class="dropdown-divider"></div>
        <div style="display: flex;">
           <a href="{{url('day-wise-summary-report')}}" class="dropdown-item">
            <i class="fa fa-file mr-2" aria-hidden="true"></i>Work Period Sales Summary Report 
          </a>
          
           
        </div>
        @endcan
         @can('item-sales-report')
        <div class="dropdown-divider"></div>
        <div style="display: flex;">
           <a href="{{url('item-sales-report')}}" class="dropdown-item">
            <i class="fa fa-file mr-2" aria-hidden="true"></i>Item Sales Report 
          </a>
        </div>
         @endcan
         @can('detail-item-sales-report')
        <div class="dropdown-divider"></div>
        <div style="display: flex;">
           <a href="{{url('detail-item-sales-report')}}" class="dropdown-item">
            <i class="fa fa-file mr-2" aria-hidden="true"></i>Detail Item Sales Report 
          </a>
        </div>
           @endcan
        @endcan


      </li>
    @endcan
      
      <!-- Notifications Dropdown Menu -->
       @can('account-report-menu') 
      <li class="nav-item dropdown remove_from_header">
        <a class="nav-link" data-toggle="dropdown" href="#">
          
          {{ __('label.Accounts Report') }} <i class="right fas fa-angle-down"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          
           @can('day-book')
           <div class="dropdown-divider"></div>
          <a href="{{url('day-book')}}" class="dropdown-item">
            <i class="fa fa-sitemap mr-2" aria-hidden="true"></i>{{ __('label.Day Book') }}
          </a>
         @endcan
          
           @can('cash-book')
           <div class="dropdown-divider"></div>
          <a href="{{url('cash-book')}}" class="dropdown-item">
            <i class="fa fa-sitemap mr-2" aria-hidden="true"></i>{{ __('label.Cash Book') }}
          </a>
         @endcan
          
           @can('bank-book')
           <div class="dropdown-divider"></div>
          <a href="{{url('bank-book')}}" class="dropdown-item">
            <i class="fa fa-sitemap mr-2" aria-hidden="true"></i>{{ __('label.Bank Book') }}
          </a>
         @endcan
           @can('receipt-payment')
          <div class="dropdown-divider"></div>
          <a href="{{url('receipt-payment')}}" class="dropdown-item">
            <i class="fa fa-sitemap mr-2" aria-hidden="true"></i>{{ __('label.Receipt & Payment') }}
          </a>
         @endcan
           @can('ledger-report')
          <div class="dropdown-divider"></div>
          <a href="{{url('ledger-report')}}" class="dropdown-item">
            <i class="fa fa-sitemap mr-2" aria-hidden="true"></i>{{ __('label.Ledger Report') }}
          </a>
         @endcan
         
        @can('group-ledger')
         <div class="dropdown-divider"></div>
          <a href="{{url('group-ledger')}}" class="dropdown-item">
            <i class="fa fa-sitemap mr-2" aria-hidden="true"></i> {{ __('label.Group Ledger Report') }}
          </a>
         @endcan
        @can('ledger-summary-report')
         <div class="dropdown-divider"></div>
          <a href="{{url('filter-ledger-summary')}}" class="dropdown-item">
            <i class="fa fa-sitemap mr-2" aria-hidden="true"></i>  {{ __('label.Ledger Summary Report') }}
          </a>
         @endcan
        @can('income-statement')
         <div class="dropdown-divider"></div>
          <a href="{{url('income-statement')}}" class="dropdown-item">
            <i class="fa fa-sitemap mr-2" aria-hidden="true"></i> {{ __('label.Income Statement') }}
          </a>
         @endcan
        @can('trail-balance')
         <div class="dropdown-divider"></div>
          <a href="{{url('trail-balance')}}" class="dropdown-item">
            <i class="fa fa-sitemap mr-2" aria-hidden="true"></i>{{ __('label.Trail Balance') }}
          </a>
         @endcan
        @can('work-sheet')
         <div class="dropdown-divider"></div>
          <a href="{{url('work-sheet')}}" class="dropdown-item">
            <i class="fa fa-sitemap mr-2" aria-hidden="true"></i> {{ __('label.Work Sheet') }}
          </a>
         @endcan
        @can('balance-sheet')
         <div class="dropdown-divider"></div>
          <a href="{{url('balance-sheet')}}" class="dropdown-item">
            <i class="fa fa-sitemap mr-2" aria-hidden="true"></i> {{ __('label.Balance Sheet') }}
          </a>
         @endcan 
        @can('chart-of-account')
         <div class="dropdown-divider"></div>
          <a href="{{url('chart-of-account')}}" class="dropdown-item">
            <i class="fa fa-sitemap mr-2" aria-hidden="true"></i> {{ __('label.Chart of Account') }}
          </a>
         @endcan  
              
      </li>
    @endcan
     @can('inventory-menu') 
      <li class="nav-item dropdown remove_from_header">
        <a class="nav-link" data-toggle="dropdown" href="#">
          
          {{ __('label.Master') }} <i class="right fas fa-angle-down"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
         
           @can('account-type-list')
          <div class="dropdown-divider"></div>
          <a href="{{url('account-type')}}" class="dropdown-item">
            <i class="fa fa-sitemap mr-2" aria-hidden="true"></i>{{ __('label.Account Type') }}
          </a>
         @endcan
        @can('account-group-list')
         <div class="dropdown-divider"></div>
          <a href="{{url('account-group')}}" class="dropdown-item">
            <i class="fa fa-fax mr-2" aria-hidden="true"></i> {{ __('label.Account Group') }}
          </a>
         @endcan
        @can('account-ledger-list')
         <div class="dropdown-divider"></div>
          
        <div style="display: flex;">
          <a href="{{url('account-ledger')}}" class="dropdown-item">
            <i class="fa fa-fax mr-2" aria-hidden="true"></i> {{ __('label.Account Ledger') }}
          </a>
          
           <a  href="{{route('account-ledger.create')}}"  >
            <i class="nav-icon fas fa-plus"></i>
          </a>
        </div>
          
         @endcan
          
           @can('item-category-list')
        <div class="dropdown-divider"></div>
          
        <div style="display: flex;">
          <a href="{{url('item-category')}}" class="dropdown-item">
            <i class="fa fa-laptop mr-2" aria-hidden="true"></i>{{ __('label.Item Category') }}
          </a>
           <a  
           class="dropdown-item text-right " 
               href="{{route('item-category.create')}}"
                >
            <i class="nav-icon fas fa-plus"></i>
          </a>

        </div>
          
         @endcan
           @can('unit-list')
          <div class="dropdown-divider"></div>
          
        <div style="display: flex;">
           <a href="{{url('unit')}}" class="dropdown-item">
            <i class="fa fa-laptop mr-2" aria-hidden="true"></i>{{ __('label.Unit Of Measurment') }}
          </a>
          <a   
           class="dropdown-item text-right " 
               href="{{route('unit.create')}}" >
            <i class="nav-icon fas fa-plus"></i>
          </a>

        </div>
          
         @endcan
           @can('warranty-list')
          <div class="dropdown-divider"></div>
          
        <div style="display: flex;">
           <a href="{{url('warranty')}}" class="dropdown-item">
            <i class="fa fa-laptop mr-2" aria-hidden="true"></i>{{ __('label.Warranty') }}
          </a>
           <a   
           class="dropdown-item text-right " 
               href="{{route('warranty.create')}}"
                >
            <i class="nav-icon fas fa-plus"></i>
          </a>
          
        </div>
          
         @endcan
         
          @can('transection_terms-list')
           <div class="dropdown-divider"></div>
        <div style="display: flex;">
           <a href="{{url('transection_terms')}}" class="dropdown-item">
            <i class="fa fa-laptop mr-2" aria-hidden="true"></i>{{ __('label.Transection Terms') }}
          </a>
          <a   
           class="dropdown-item text-right " 
               href="{{route('transection_terms.create')}}" >
            <i class="nav-icon fas fa-plus"></i>
          </a>
          

        </div>
          
         @endcan

          @can('vat-rules-list')
           <div class="dropdown-divider"></div>
        <div style="display: flex;">
           <a href="{{url('vat-rules')}}" class="dropdown-item">
            <i class="fa fa-laptop mr-2" aria-hidden="true"></i>{{ __('label.Vat Rules') }}
          </a>
           <a  
           class="dropdown-item text-right " 
               href="{{route('vat-rules.create')}}" >
            <i class="nav-icon fas fa-plus"></i>
          </a>
          
        </div>
          
         @endcan
         
        @can('item-information-list')
        <div class="dropdown-divider"></div>
        <div style="display: flex;">
           <a href="{{url('item-information')}}" class="dropdown-item">
            <i class="fa fa-list-alt mr-2" aria-hidden="true"></i> {{ __('label.Item Information') }}
          </a>
           <a  
           class="dropdown-item text-right " 
              
               href="{{route('item-information.create')}}"
                >
            <i class="nav-icon fas fa-plus"></i>
          </a>
        </div>
          
         @endcan
        @can('lot-item-information')
         <div class="dropdown-divider"></div>
        <div style="display: flex;">
           <a href="{{url('lot-item-information')}}" class="dropdown-item">
            <i class="fa fa-list-alt mr-2" aria-hidden="true"></i>{{ __('label.Lot Item Information') }}
          </a>
           
        </div>
         @endcan
        @can('labels-print')
         <div class="dropdown-divider"></div> 
        <div style="display: flex;">
           <a href="{{url('labels-print')}}" class="dropdown-item">
            <i class="fa fa-list-alt mr-2" aria-hidden="true"></i>{{ __('label.Labels Print') }}
          </a>
           
        </div>
         @endcan
        @can('vessel-info-list')
         <div class="dropdown-divider"></div> 
        <div style="display: flex;">
           <a href="{{url('vessel-info')}}" class="dropdown-item">
            <i class="fa fa-list-alt mr-2" aria-hidden="true"></i>{{ __('label.vessel-info') }}
          </a>
           
        </div>
         @endcan
          @can('restaurant-module') 
          <p style="text-align: center;margin-bottom: 1px solid #000;"><b>Resturant Module</b></p>
      @can('table-info-menu')
        <div style="display: flex;">
          @can('table-info-list')
           <a href="{{url('table-info')}}" class="dropdown-item">
            <i class="fa fa-table mr-2" aria-hidden="true"></i> Table Information
          </a>
          @endcan
           @can('table-info-create')
           <a  href="{{route('table-info.create')}}" class="dropdown-item text-right">
            <i class="nav-icon fas fa-plus"></i>
          </a>
          @endcan
        </div>
         @endcan 
       @can('steward-waiter-menu')
          <div class="dropdown-divider"></div>   
        <div style="display: flex;">
          @can('steward-waiter-list')
           <a href="{{url('steward-waiter')}}" class="dropdown-item">
            <i class="fa fa-table mr-2" aria-hidden="true"></i> Steward Waiter Setup
          </a>
          @endcan
           @can('steward-waiter-create')
           <a  href="{{route('steward-waiter.create')}}" class="dropdown-item text-right">
            <i class="nav-icon fas fa-plus"></i>
          </a>
          @endcan
        </div>
         @endcan 
       @can('musak-four-point-three-menu')
         <div class="dropdown-divider"></div>
        <div style="display: flex;">
          @can('musak-four-point-three-list')
           <a href="{{url('musak-four-point-three')}}" class="dropdown-item">
            <i class="fa fa-table mr-2" aria-hidden="true"></i> Item Wise Ingredients
          </a>
          @endcan
           @can('musak-four-point-three-create')
           <a  href="{{route('musak-four-point-three.create')}}" class="dropdown-item text-right">
            <i class="nav-icon fas fa-plus"></i>
          </a>
          @endcan
        </div>
         @endcan 
       
       @can('category-allocation-list')
        <div class="dropdown-divider"></div>
        <div style="display: flex;">
          @can('category-allocation-list')
           <a href="{{url('category-allocation')}}" class="dropdown-item">
            <i class="fa fa-table mr-2" aria-hidden="true"></i>Pos Dispaly Category 
          </a>
          @endcan
           
        </div>
          @endcan
    @endcan  
      </li>
    @endcan
      
      <li class="nav-item dropdown remove_from_header">
        <a class="nav-link" data-toggle="dropdown" href="#">
          
          {{ __('label.Settings') }} <i class="right fas fa-angle-down"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
         
           @can('admin-settings')
          <div class="dropdown-divider"></div>
          <a href="{{url('admin-settings')}}" class="dropdown-item">
            <i class="fas fa-cog mr-2"></i> {{ __('label.General Settings') }}
          </a>
         @endcan
           @can('bulk-sms')
          <div class="dropdown-divider"></div>
          <a href="{{url('sms-send')}}" class="dropdown-item">
            <i class="fas fa-cog mr-2"></i> {{ __('label.SMS SEND') }}
          </a>
         @endcan
         @can('invoice-prefix')
         <div class="dropdown-divider"></div>
        <div style="display: flex;">
          <a href="{{url('invoice-prefix')}}" class="dropdown-item">
            <i class="fas fa-cog    mr-2"></i>{{ __('label.Invoice Prefix') }}
          </a>
        </div>
         @endcan
        @can('role-list')
         <div class="dropdown-divider"></div>
        <div style="display: flex;">
         <a href="{{url('roles')}}" class="dropdown-item">
          <i class="fa fa-server  mr-2" aria-hidden="true"></i>{{ __('label.Roles') }}
          </a>
           <a   href="#None" 
          class="dropdown-item text-right attr_base_create_url"
            data-toggle="modal" data-target="#commonEntryModal_item" 
            attr_base_create_url="{{route('roles.create')}}"> 
            <i class="nav-icon fas fa-plus"></i> </a>
        </div>
          
         @endcan
        @can('user-list')
         <div class="dropdown-divider"></div>
        <div style="display: flex;">
         <a href="{{url('users')}}" class="dropdown-item">
            <i class="fas fa-users  mr-2"></i> {{ __('label.Users') }}
          </a>
          <a   href="#None" 
          class="dropdown-item text-right attr_base_create_url"
            data-toggle="modal" data-target="#commonEntryModal_item" 
            attr_base_create_url="{{route('users.create')}}"> 
            <i class="nav-icon fas fa-plus"></i> </a>
        </div>
          
         @endcan
         @can('companies-list')
        <div style="display: flex;">
         <a href="{{url('companies')}}" class="dropdown-item">
            <i class="fa fa-fax mr-2" aria-hidden="true"></i> {{ __('label.companies') }}
          </a>
          <a  href="{{route('companies.create')}}" class="dropdown-item text-right">
            <i class="nav-icon fas fa-plus"></i>
          </a>
        </div>
        @endcan
        @can('branch-list')
         <div class="dropdown-divider"></div>
        <div style="display: flex;">
          <a href="{{url('branch')}}" class="dropdown-item">
            <i class="fa fa-share-alt mr-2" aria-hidden="true"></i> {{ __('label.Branch') }} 
          </a>
           <a   href="#None" 
          class="dropdown-item text-right attr_base_create_url"
            data-toggle="modal" data-target="#commonEntryModal_item" 
            attr_base_create_url="{{route('branch.create')}}"> 
            <i class="nav-icon fas fa-plus"></i> </a>
        </div>
         
         @endcan
        @can('cost-center-list')
         <div class="dropdown-divider"></div>
        <div style="display: flex;">
          <a href="{{url('cost-center')}}" class="dropdown-item">
           <i class="fa fa-adjust mr-2" aria-hidden="true"></i> {{ __('label.Cost center') }} 
          </a>
            <a   
          class="dropdown-item text-right "
            
            href="{{route('cost-center.create')}}"> 
            <i class="nav-icon fas fa-plus"></i> </a>
        </div>
          
         @endcan
        @can('store-house-list')
         <div class="dropdown-divider"></div>
        <div style="display: flex;">
          <a href="{{url('store-house')}}" class="dropdown-item">
           <i class="fa fa-adjust mr-2" aria-hidden="true"></i> {{ __('label.Store House') }} 
          </a>
            <a   
          class="dropdown-item text-right "
            href="{{route('store-house.create')}}"> 
            <i class="nav-icon fas fa-plus"></i> </a>
        </div>
          
         @endcan
       
        @can('budgets-list')
         <div class="dropdown-divider"></div>
        <div style="display: flex;">
          <a href="{{url('budgets')}}" class="dropdown-item">
            <i class="fas fa-store   mr-2"></i>{{ __('label.budgets') }}
          </a>
          <a  href="{{route('budgets.create')}}"
          class="dropdown-item text-right "> 
            <i class="nav-icon fas fa-plus"></i> </a>
        </div>
         @endcan
        @can('lock-permission')
         <div class="dropdown-divider"></div>
        <div style="display: flex;">
          <a href="{{url('all-lock')}}" class="dropdown-item">
            <i class="fas fa-lock _required   mr-2"></i>{{ __('label.Transection Lock System') }}
          </a>
        </div>
         @endcan
        
        @can('database-backup')
         <div class="dropdown-divider"></div>
        <div style="display: flex;">
          <a href="{{url('database-backup')}}" class="dropdown-item">
            <i class="fa fa-database    mr-2"></i>{{ __('label.Data Backup') }}
          </a>
        </div>
         @endcan
        
              
              
      </li>
      
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-user"></i>
          
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <span class="dropdown-item dropdown-header">User Name :  <b>{{Auth::user()->name ?? '' }}</b></span>
          <div class="dropdown-divider"></div>
          <div class="text-center">
             <a href="{{url('user-profile')}}">{{__('label.profile')}}</a>
          </div>
        <div class="dropdown-divider"></div>
                <a class="dropdown-item text-center" 
                        href="{{ route('logout') }}"
                        onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                  {{ __('Logout') }}
                </a>


                  <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                  </form>
             
          <div class="dropdown-divider"></div>
          
        </div>
      </li>
      <li class="nav-item">
        <a  class="nav-link full_screen_show" data-widget="fullscreen" href="#" role="button" >
          <i class="fas fa-expand-arrows-alt"></i>
        </a>
      </li>
    </ul>
  </nav>