<aside class="main-sidebar sidebar-dark-primary ">
    <!-- Brand Logo -->
    
@php
   $current_url = Route::current()->getName();
@endphp
    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar Menu -->
     
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <li>
            <a href="{{url('home')}}" class="brand-link">
      <img src="{{asset('/')}}{{$settings->logo ?? ''}}" alt="{{$settings->name ?? '' }}" class="brand-image  elevation-3" >
      <span class="brand-text font-weight-light"></span>
    </a>
          </li>
          <li class="nav-item ">
            <a href="{{url('home')}}" class="nav-link {{ ( $current_url=='home' ) ? 'nest_active' : '' }}"  >
              <i class="fa fa-credit-card nav-icon" aria-hidden="true"></i>
              <p>
                  {{ __('label.Dashboard') }}
              </p>
            </a>
          </li>
        @can('hrm-module') 
          <li class="nav-item {{ Route::is('week-work-day.*') ||  Route::is('holidays.*') ||  Route::is('leave-type.*')  ||  Route::is('hrm-employee.*')  ||  Route::is('initial-salary-structure.*')  ||  Route::is('pay-heads.*')  ||  Route::is('hrm-department.*')   ||  Route::is('hrm-grade.*') ||  Route::is('hrm-emp-location.*')  ||  Route::is('hrm-emp-category.*')   ||  Route::is('hrm-designation.*')  ? 'menu-is-opening menu-open' : '' }}">
            <a href="#" class="nav-link {{ Route::is('week-work-day.*') || Route::is('holidays.*') || Route::is('leave-type.*') || Route::is('hrm-employee.*') || Route::is('initial-salary-structure.*')  || Route::is('pay-heads.*')  || Route::is('hrm-department.*')    || Route::is('hrm-grade.*')   || Route::is('hrm-emp-location.*') ||  Route::is('hrm-emp-category.*')   ||  Route::is('hrm-designation.*')    ? 'main_nav_active' : '' }}">
              <i class="fa fa-users  nav-icon" aria-hidden="true"></i>
              <p>


                 {{ __('label.hrm') }}
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
            @can('week-work-day')
              <li class="nav-item" >
                  <a href="{{url('weekworkday')}}" class="nav-link " >
                  <i class="fa fa-arrow-right nav-icon"></i><p>{{ __('label.weekworkday') }}</p></a>
              </li>
              @endcan
            @can('holidays-list')
              <li class="nav-item" >
                  <a href="{{url('holidays')}}" class="nav-link " >
                  <i class="fa fa-arrow-right nav-icon"></i><p>{{ __('label.holidays') }}</p></a>
              </li>
              @endcan
            @can('leave-type-list')
              <li class="nav-item" >
                  <a href="{{url('leave-type')}}" class="nav-link " >
                  <i class="fa fa-arrow-right nav-icon"></i><p>{{ __('label.leave-type') }}</p></a>
              </li>
              @endcan
            @can('hrm-employee-list')
              <li class="nav-item" >
                  <a href="{{url('hrm-employee')}}" class="nav-link " >
                  <i class="fa fa-arrow-right nav-icon"></i><p>{{ __('label.hrm-employee') }}</p></a>
              </li>
              @endcan
            @can('monthly-salary-structure-list')
              <li class="nav-item" >
                  <a href="{{url('monthly-salary-structure')}}" class="nav-link " >
                  <i class="fa fa-arrow-right nav-icon"></i><p>{{ __('label.monthly-salary-structure') }}</p></a>
              </li>
              @endcan
            @can('initial-salary-structure-list')
              <li class="nav-item" >
                  <a href="{{url('initial-salary-structure')}}" class="nav-link " >
                  <i class="fa fa-arrow-right nav-icon"></i><p>{{ __('label.initial-salary-structure') }}</p></a>
              </li>
              @endcan
            @can('pay-heads-list')
              <li class="nav-item" >
                  <a href="{{url('pay-heads')}}" class="nav-link " >
                  <i class="fa fa-arrow-right nav-icon"></i><p>{{ __('label.pay-heads') }}</p></a>
              </li>
              @endcan
            @can('hrm-department-list')
              <li class="nav-item" >
                  <a href="{{url('hrm-department')}}" class="nav-link " >
                  <i class="fa fa-arrow-right nav-icon"></i><p>{{ __('label.hrm-department') }}</p></a>
              </li>
              @endcan
            @can('hrm-grade-list')
              <li class="nav-item" >
                  <a href="{{url('hrm-grade')}}" class="nav-link " >
                  <i class="fa fa-arrow-right nav-icon"></i><p>{{ __('label.hrm-grade') }}</p></a>
              </li>
              @endcan
            @can('hrm-emp-location-list')
              <li class="nav-item" >
                  <a href="{{url('hrm-emp-location')}}" class="nav-link " >
                  <i class="fa fa-arrow-right nav-icon"></i><p>{{ __('label.hrm-emp-location') }}</p></a>
              </li>
              @endcan
            @can('hrm-emp-category-list')
              <li class="nav-item" >
                  <a href="{{url('hrm-emp-category')}}" class="nav-link " >
                  <i class="fa fa-arrow-right nav-icon"></i><p>{{ __('label.hrm-emp-category') }}</p></a>
              </li>
              @endcan
            @can('hrm-designation-list')
              <li class="nav-item" >
                  <a href="{{url('hrm-designation')}}" class="nav-link " >
                  <i class="fa fa-arrow-right nav-icon"></i><p>{{ __('label.hrm-designation') }}</p></a>
              </li>
              @endcan
             
            </ul>
          </li>
          @endcan
          @can('account-menu') 
          <li class="nav-item {{ Route::is('voucher.*')  ? 'menu-is-opening menu-open' : '' }}">
            <a href="#" class="nav-link {{ Route::is('purchase-return.*')    ? 'main_nav_active' : '' }}">
              <i class="fa fa-coins nav-icon" aria-hidden="true"></i>
              <p>
                 {{ __('label.receive_payment') }}
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
            @can('cash-receive')
              <li class="nav-item" >
                  <a href="{{url('voucher')}}?_voucher_type=CR" class="nav-link " >
                  <i class="fa fa-arrow-right nav-icon"></i><p>{{ __('label.Cash Receive') }}</p></a>
              </li>
              @endcan
             @can('cash-payment')
              <li class="nav-item">
                <a href="{{url('voucher')}}?_voucher_type=CP" class="nav-link" ><i class="fa fa-arrow-right nav-icon"></i><p>{{ __('label.Cash Payment') }}</p></a>
              </li>
              @endcan
             @can('bank-receive')
              <li class="nav-item">
                <a href="{{url('voucher')}}?_voucher_type=BR" class="nav-link" ><i class="fa fa-arrow-right nav-icon"></i><p>{{ __('label.Bank Receive') }}</p></a>
              </li>
              @endcan
             @can('bank-payment')
              <li class="nav-item">
                <a href="{{url('voucher')}}?_voucher_type=BP" class="nav-link" ><i class="fa fa-arrow-right nav-icon"></i><p>{{ __('label.Bank Payment') }}</p></a>
              </li>
              @endcan
             
             @can('voucher-list')
              <li class="nav-item">
                <a href="{{url('voucher')}}" class="nav-link" ><i class="fa fa-arrow-right nav-icon"></i><p>{{ __('label.Voucher') }}</p></a>
              </li>
              @endcan
             @can('easy-voucher-list')
              <li class="nav-item">
                <a href="{{url('easy-voucher')}}" class="nav-link" ><i class="fa fa-arrow-right nav-icon"></i><p>{{ __('label.Easy Voucher') }}</p></a>
              </li>
              @endcan
             @can('inter-project-voucher-list')
              <li class="nav-item">
                <a href="{{url('inter-project-voucher')}}" class="nav-link" ><i class="fa fa-arrow-right nav-icon"></i><p>{{ __('label.inter-project-voucher') }}</p></a>
              </li>
              @endcan
            </ul>
          </li>
          @endcan

 @can('restaurant-module') 
          <li class="nav-item {{ Route::is('restaurant-sales.*') || Route::is('table-info.*')  || Route::is('steward-waiter.*')  || Route::is('musak-four-point-three.*')|| Route::is('kitchen.*')   ? 'menu-is-opening menu-open' : '' }} ">
            <a href="#" class="nav-link ">
              <i class="fa fa-coffee nav-icon" aria-hidden="true"></i>
              <p>
                {{ __('label.Restaurants') }} 
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
             
             @can('restaurant-pos')
              <li class="nav-item">
                <a href="{{url('restaurant-pos')}}" class="nav-link" ><i class="fa fa-arrow-right nav-icon"></i><p>{{ __('label.Restaurant POS') }}</p></a>
              </li>
              @endcan
             
            @can('restaurant-sales-list')
              <li class="nav-item">
                <a href="{{url('restaurant-sales')}}" class="nav-link {{ Route::is('restaurant-sales.*') ? 'nest_active' : '' }}" ><i class="fa fa-arrow-right nav-icon"></i><p> {{ __('label.Restaurant Sales') }}</p></a>
              </li>
              @endcan
            @can('table-info-menu')
              <li class="nav-item">
                <a href="{{url('table-info')}}"  class="nav-link {{ Route::is('table-info.*') ? 'nest_active' : '' }}" ><i class="fa fa-arrow-right nav-icon"></i><p> Table Information </p></a>
              </li>
              @endcan
              @can('steward-waiter-menu')
              <li class="nav-item">
                <a href="{{url('steward-waiter')}}"  class="nav-link {{ Route::is('steward-waiter.*') ? 'nest_active' : '' }}" ><i class="fa fa-arrow-right nav-icon"></i><p> Steward/Waiter Setup </p></a>
              </li>
              @endcan
              @can('musak-four-point-three-menu')
              <li class="nav-item">
                <a href="{{url('musak-four-point-three')}}" class="nav-link {{ Route::is('musak-four-point-three.*') ? 'nest_active' : '' }}" ><i class="fa fa-arrow-right nav-icon"></i><p> Item Wise Ingredients </p></a>
              </li>
              @endcan
             @can('kitchen-menu')
              <li class="nav-item">
                <a href="{{url('kitchen')}}" class="nav-link {{ Route::is('kitchen.*') ? 'nest_active' : '' }}" ><i class="fa fa-arrow-right nav-icon"></i><p>  Kitchen Panel </p></a>
              </li>
              @endcan
            
             
            
              
            </ul>
          </li>
          @endcan
          @can('rlp-module') 
          <li class="nav-item {{ Route::is('approval-chain.*') || Route::is('rlp.*')  || Route::is('import-purchase.*')  || Route::is('import-material-receive.*')  ? 'menu-is-opening menu-open' : '' }}">
            <a href="#" class="nav-link {{ Route::is('approval-chain.*') || Route::is('rlp.*') || Route::is('import-purchase.*')  || Route::is('import-material-receive.*')    ? 'main_nav_active' : '' }}">
              <i class="fa fa-flag nav-icon" aria-hidden="true"></i>
              <p>
                 {{ __('label.procurement') }}
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
             @can('approval-chain-list')
              <li class="nav-item">
                <a href="{{url('approval-chain')}}" class="mr-2  nav-link {{ ( $current_url=='approval-chain.index' ) ? 'nest_active' : '' }}" ><i class="fa fa-arrow-right nav-icon"></i><p>{{ __('label.approval-chain') }}</p></a>
              </li>
              @endcan
             @can('rlp-list')
              <li class="nav-item">
                <a href="{{url('rlp')}}" class="mr-2  nav-link {{ ( $current_url=='rlp.index' ) ? 'nest_active' : '' }}" ><i class="fa fa-arrow-right nav-icon"></i><p>{{ __('label.rlp') }}</p></a>
              </li>
              @endcan
             @can('import-purchase-list')
              <li class="nav-item">
                <a href="{{url('import-purchase')}}" class="mr-2  nav-link {{ ( $current_url=='import-purchase.index' ) ? 'nest_active' : '' }}" ><i class="fa fa-arrow-right nav-icon"></i><p>{{ __('label.import-purchase') }}</p></a>
              </li>
              @endcan
             @can('import-material-receive-list')
              <li class="nav-item">
                <a href="{{url('import-material-receive')}}" class="mr-2  nav-link {{ ( $current_url=='import-material-receive.index' ) ? 'nest_active' : '' }}" ><i class="fa fa-arrow-right nav-icon"></i><p>{{ __('label.import-material-receive') }}</p></a>
              </li>
              @endcan
              
              
            </ul>
          </li>
          @endcan 
 @can('purchase-order-list') 
          <li class="nav-item {{ Route::is('purchase-order.*') || Route::is('purchase.*')  || Route::is('purchase-return.*')  ? 'menu-is-opening menu-open' : '' }}">
            <a href="#" class="nav-link {{ Route::is('purchase-order.*') || Route::is('purchase.*') || Route::is('purchase-return.*')    ? 'main_nav_active' : '' }}">
              <i class="fa fa-cart-arrow-down nav-icon" aria-hidden="true"></i>
              <p>
                 {{ __('label.purchase') }}
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
             
             @can('purchase-order-create')
              <li class="nav-item">
                <a href="{{url('purchase-order/create')}}" class="mr-2 nav-link {{ ( $current_url=='purchase-order.create' ) ? 'nest_active' : '' }}"  ><i class="fa fa-plus nav-icon"></i><p>{{ __('label.Purchase Order Create') }} </p></a>
              </li>
              @endcan
             @can('purchase-order-list')
              <li class="nav-item">
                <a href="{{url('purchase-order')}}" class="mr-2  nav-link {{ ( $current_url=='purchase-order.index' ) ? 'nest_active' : '' }}" ><i class="fa fa-arrow-right nav-icon"></i><p>{{ __('label.Purchase Order List') }}</p></a>
              </li>
              @endcan
              @can('purchase-create')
              <li class="nav-item">
                <a href="{{url('purchase/create')}}" class=" mr-2 nav-link {{ ( $current_url=='purchase.create' ) ? 'nest_active' : '' }}"  ><i class="fa fa-plus nav-icon"></i><p>{{ __('label.Purchase Create') }} </p></a>
              </li>
              @endcan
             @can('purchase-list')
              <li class="nav-item">
                <a href="{{url('purchase')}}" class="mr-2  nav-link {{ ( $current_url=='purchase.index' ) ? 'nest_active' : '' }}" ><i class="fa fa-arrow-right nav-icon"></i><p>{{ __('label.Purchase List') }}</p></a>
              </li>
              @endcan
              @can('purchase-return-create')
              <li class="nav-item">
                <a href="{{url('purchase-return/create')}}" class="nav-link {{ ( $current_url=='purchase-return.create' ) ? 'nest_active' : '' }}"  ><i class="fa fa-plus nav-icon"></i><p>{{ __('label.purchase-return-create') }} </p></a>
              </li>
              @endcan
             @can('purchase-return-list')
              <li class="nav-item">
                <a href="{{url('purchase-return')}}" class="nav-link {{ ( $current_url=='purchase-return.index' ) ? 'nest_active' : '' }}" ><i class="fa fa-arrow-right nav-icon"></i><p>{{ __('label.purchase-return-list') }}</p></a>
              </li>
              @endcan
              
            </ul>
          </li>
          @endcan 

          

          @can('sales-order-list') 
          <li class="nav-item {{ Route::is('sales-order.*') || Route::is('sales.*') || Route::is('sales-return.*')  ? 'menu-is-opening menu-open' : '' }}">
            <a href="#" class="nav-link {{ Route::is('sales-order.*') || Route::is('sales.*') || Route::is('sales-return.*')    ? 'main_nav_active' : '' }}">
              <i class="fa fa-shopping-cart nav-icon" aria-hidden="true"></i>
              <p>
                 {{ __('label.sales') }}
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
             
             @can('sales-order-create')
              <li class="nav-item">
                <a href="{{url('sales-order/create')}}" class="nav-link {{ ( $current_url=='sales-order.create' ) ? 'nest_active' : '' }}"  ><i class="fa fa-arrow-right nav-icon"></i><p>{{ __('label.sales-order-create') }} </p></a>
              </li>
              @endcan
             @can('sales-order-list')
              <li class="nav-item">
                <a href="{{url('sales-order')}}" class="nav-link {{ ( $current_url=='sales-order.index' ) ? 'nest_active' : '' }}" ><i class="fa fa-arrow-right nav-icon"></i><p>{{ __('label.sales-order-list') }}</p></a>
              </li>
              @endcan
              @can('sales-create')
              <li class="nav-item">
                <a href="{{url('sales/create')}}" class="nav-link {{ ( $current_url=='sales.create' ) ? 'nest_active' : '' }}"  ><i class="fa fa-arrow-right nav-icon"></i><p>{{ __('label.sales-create') }} </p></a>
              </li>
              @endcan
             @can('sales-list')
              <li class="nav-item">
                <a href="{{url('sales')}}" class="nav-link {{ ( $current_url=='sales.index' ) ? 'nest_active' : '' }}" ><i class="fa fa-arrow-right nav-icon"></i><p>{{ __('label.sales-list') }}</p></a>
              </li>
              @endcan
              @can('sales-return-create')
              <li class="nav-item">
                <a href="{{url('sales-return/create')}}" class="nav-link {{ ( $current_url=='sales-return.create' ) ? 'nest_active' : '' }}"  ><i class="fa fa-arrow-right nav-icon"></i><p>{{ __('label.sales-return-create') }} </p></a>
              </li>
              @endcan
             @can('sales-return-list')
              <li class="nav-item">
                <a href="{{url('sales-return')}}" class="nav-link {{ ( $current_url=='sales-return.index' ) ? 'nest_active' : '' }}" ><i class="fa fa-arrow-right nav-icon"></i><p>{{ __('label.sales-return-list') }}</p></a>
              </li>
              @endcan
              
            </ul>
          </li>
          @endcan

          

         
          @can('damage-list') 
          <li class="nav-item {{ Route::is('damage.*')  ? 'menu-is-opening menu-open' : '' }}">
            <a href="#" class="nav-link {{ Route::is('damage.*')    ? 'main_nav_active' : '' }}">
              <i class="fa fa-file nav-icon" aria-hidden="true"></i>
              <p>
                 {{ __('label.damage') }}
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
             
             @can('damage-create')
              <li class="nav-item">
                <a href="{{url('damage/create')}}" class="nav-link {{ ( $current_url=='damage.create' ) ? 'nest_active' : '' }}"  ><i class="fa fa-arrow-right nav-icon"></i><p>{{ __('label.damage-create') }} </p></a>
              </li>
              @endcan
             @can('damage-list')
              <li class="nav-item">
                <a href="{{url('damage')}}" class="nav-link {{ ( $current_url=='damage.index' ) ? 'nest_active' : '' }}" ><i class="fa fa-arrow-right nav-icon"></i><p>{{ __('label.damage-list') }}</p></a>
              </li>
              @endcan
              
            </ul>
          </li>
          @endcan
          @can('transfer-list') 
          <li class="nav-item {{ Route::is('transfer.*')  ? 'menu-is-opening menu-open' : '' }}">
            <a href="#" class="nav-link {{ Route::is('transfer.*')    ? 'main_nav_active' : '' }}">
              <i class="fas fa-tree nav-icon" aria-hidden="true"></i>
              <p>
                 {{ __('label.transfer') }}
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
             
             @can('transfer-create')
              <li class="nav-item">
                <a href="{{url('transfer/create')}}" class="nav-link {{ ( $current_url=='transfer.create' ) ? 'nest_active' : '' }}"  ><i class="fa fa-arrow-right nav-icon"></i><p>{{ __('label.transfer-create') }} </p></a>
              </li>
              @endcan
             @can('transfer-list')
              <li class="nav-item">
                <a href="{{url('transfer')}}" class="nav-link {{ ( $current_url=='transfer.index' ) ? 'nest_active' : '' }}" ><i class="fa fa-arrow-right nav-icon"></i><p>{{ __('label.transfer-list') }}</p></a>
              </li>
              @endcan
              
            </ul>
          </li>
          @endcan
          @can('production-list') 
          <li class="nav-item {{ Route::is('production.*')  ? 'menu-is-opening menu-open' : '' }}">
            <a href="#" class="nav-link {{ Route::is('production.*')    ? 'main_nav_active' : '' }}">
              <i class="nav-icon fas fa-edit " aria-hidden="true"></i>
              <p>
                 {{ __('label.production') }}
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
             
             @can('production-create')
              <li class="nav-item">
                <a href="{{url('production/create')}}" class="nav-link {{ ( $current_url=='production.create' ) ? 'nest_active' : '' }}"  ><i class="fa fa-arrow-right nav-icon"></i><p>{{ __('label.production-create') }} </p></a>
              </li>
              @endcan
             @can('production-list')
              <li class="nav-item">
                <a href="{{url('production')}}" class="nav-link {{ ( $current_url=='production.index' ) ? 'nest_active' : '' }}" ><i class="fa fa-arrow-right nav-icon"></i><p>{{ __('label.production-list') }}</p></a>
              </li>
              @endcan
              
            </ul>
          </li>
          @endcan
          @can('third-party-service-list') 
          <li class="nav-item {{ Route::is('third-party-service.*')  ? 'menu-is-opening menu-open' : '' }}">
            <a href="#" class="nav-link {{ Route::is('third-party-service.*')    ? 'main_nav_active' : '' }}">
              <i class="fa fa-credit-card nav-icon" aria-hidden="true"></i>
              <p>
                 {{ __('label.third-party-service') }}
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
             
             @can('third-party-service-create')
              <li class="nav-item">
                <a href="{{url('third-party-service/create')}}" class="nav-link {{ ( $current_url=='third-party-service.create' ) ? 'nest_active' : '' }}"  ><i class="fa fa-arrow-right nav-icon"></i><p>{{ __('label.third-party-service-create') }} </p></a>
              </li>
              @endcan
             @can('third-party-service-list')
              <li class="nav-item">
                <a href="{{url('third-party-service')}}" class="nav-link {{ ( $current_url=='third-party-service.index' ) ? 'nest_active' : '' }}" ><i class="fa fa-arrow-right nav-icon"></i><p>{{ __('label.third-party-service-list') }}</p></a>
              </li>
              @endcan
              
            </ul>
          </li>
          @endcan

          @can('warranty-manage-list') 
          <li class="nav-item {{ Route::is('warranty-manage.*') || Route::is('individual-replacement.*') || Route::is('item-replace.*') ||  Route::is('individual-replacement.*') ||  Route::is('warranty-check')  ? 'menu-is-opening menu-open' : '' }}">
            <a href="#" class="nav-link {{ Route::is('warranty-manage.*') || Route::is('individual-replacement.*') || Route::is('item-replace.*') ||  Route::is('individual-replacement.*') ||  Route::is('warranty-check')   ? 'main_nav_active' : '' }}">
              <i class="fa fa-credit-card nav-icon" aria-hidden="true"></i>
              <p>
                 {{ __('label.warranty-manage') }}
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
             
             @can('warranty-check')
              <li class="nav-item">
                <a href="{{url('warranty-check')}}" class="nav-link {{ ( $current_url=='warranty-check' ) ? 'nest_active' : '' }}"  ><i class="fa fa-arrow-right nav-icon"></i><p>{{ __('label.warranty-check') }} </p></a>
              </li>
              @endcan
             @can('warranty-manage-create')
              <li class="nav-item">
                <a href="{{url('warranty-manage/create')}}" class="nav-link {{ ( $current_url=='warranty-manage.create' ) ? 'nest_active' : '' }}"  ><i class="fa fa-arrow-right nav-icon"></i><p>{{ __('label.warranty-manage-create') }} </p></a>
              </li>
              @endcan
             @can('warranty-manage-list')
              <li class="nav-item">
                <a href="{{url('warranty-manage')}}" class="nav-link {{ ( $current_url=='warranty-manage.index' ) ? 'nest_active' : '' }}" ><i class="fa fa-arrow-right nav-icon"></i><p>{{ __('label.warranty-manage-list') }}</p></a>
              </li>
              @endcan
              @can('item-replace-create')
              <li class="nav-item">
                <a href="{{url('item-replace/create')}}" class="nav-link {{ ( $current_url=='item-replace.create' ) ? 'nest_active' : '' }}"  ><i class="fa fa-arrow-right nav-icon"></i><p>{{ __('label.item-replace-create') }} </p></a>
              </li>
              @endcan
             @can('item-replace-list')
              <li class="nav-item">
                <a href="{{url('item-replace')}}" class="nav-link {{ ( $current_url=='item-replace.index' ) ? 'nest_active' : '' }}" ><i class="fa fa-arrow-right nav-icon"></i><p>{{ __('label.item-replace-list') }}</p></a>
              </li>
              @endcan
              @can('individual-replacement-create')
              <li class="nav-item">
                <a href="{{url('individual-replacement/create')}}" class="nav-link {{ ( $current_url=='individual-replacement.create' ) ? 'nest_active' : '' }}"  ><i class="fa fa-arrow-right nav-icon"></i><p>{{ __('label.individual-replacement-create') }} </p></a>
              </li>
              @endcan
             @can('individual-replacement-list')
              <li class="nav-item">
                <a href="{{url('individual-replacement')}}" class="nav-link {{ ( $current_url=='individual-replacement.index' ) ? 'nest_active' : '' }}" ><i class="fa fa-arrow-right nav-icon"></i><p>{{ __('label.individual-replacement-list') }}</p></a>
              </li>
              @endcan
             @can('w-item-receive-from-supp-list')
              <li class="nav-item">
                <a href="{{url('w-item-receive-from-supp')}}" class="nav-link {{ ( $current_url=='w-item-receive-from-supp.index' ) ? 'nest_active' : '' }}" ><i class="fa fa-arrow-right nav-icon"></i><p>{{ __('label.w-item-receive-from-supp-list') }}</p></a>
              </li>
              @endcan
              
            </ul>
          </li>
          @endcan
          
          

        <li class="nav-item ">
            <a href="{{url('report-panel')}}" class="nav-link {{ ( $current_url=='report-panel' ) ? 'nest_active' : '' }}"  >
              <i class="fas fa-chart-pie nav-icon" aria-hidden="true"></i>
              <p>
                  {{ __('label.report_panel') }}
              </p>
            </a>
          </li>

           @can('inventory-menu') 
          <li class="nav-item {{ Route::is('account-type.*') || Route::is('account-group.*') || Route::is('unit.*')  || Route::is('item-information.*')  || Route::is('lot-item-information.*')  || Route::is('account-ledger.*')  || Route::is('item-category.*')  || Route::is('warranty.*') || Route::is('transection_terms.*') || Route::is('vat-rules.*') || Route::is('labels-print') || Route::is('lot-item-information')  ? 'menu-is-opening menu-open' : '' }}">
            <a href="#" class="nav-link  Route::is('account-type.*') || Route::is('account-group.*')  || Route::is('unit.*')   || Route::is('item-information.*')    || Route::is('lot-item-information.*')   || Route::is('account-ledger.*')  || Route::is('item-category.*') || Route::is('warranty.*') || Route::is('transection_terms.*')  || Route::is('vat-rules.*')   || Route::is('labels-print')   || Route::is('lot-item-information')   ? 'active' : '' }}">
              <i class="fa fa-file nav-icon" aria-hidden="true"></i>
              <p>
               {{ __('label.Master') }}
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
             @can('account-type-list')
              <li class="nav-item">
                <a href="{{url('account-type')}}"  class="nav-link {{Route::is('account-type.*')   ? 'active' : '' }}" ><i class="fa fa-sitemap nav-icon"></i> <p>{{ __('label.Account Type') }}</p></a>
              </li>
              @endcan
             @can('account-group-list')
              <li class="nav-item">
                <a href="{{url('account-group')}}"  class="nav-link {{Route::is('account-group.*')   ? 'active' : '' }}" ><i class="fa fa-laptop nav-icon"></i> <p>{{ __('label.Account Group') }}</p></a>
              </li>
              @endcan
             @can('account-ledger-list')
              <li class="nav-item">
                <a href="{{url('account-ledger')}}"  class="nav-link {{Route::is('account-ledger.*')   ? 'active' : '' }}" ><i class="fa fa-laptop nav-icon"></i> <p>{{ __('label.Account Ledger') }}</p></a>
              </li>
              @endcan
             @can('item-category-list')
              <li class="nav-item">
                <a href="{{url('item-category')}}"  class="nav-link {{Route::is('item-category.*')   ? 'active' : '' }}" ><i class="fa fa-laptop nav-icon"></i> <p>{{ __('label.Item Category') }}</p></a>
              </li>
              @endcan

             @can('unit-list')
              <li class="nav-item">
                <a href="{{url('unit')}}"  class="nav-link {{Route::is('unit.*')   ? 'active' : '' }}" ><i class="fa fa-laptop nav-icon"></i> <p>{{ __('label.Unit Of Measurment') }}</p></a>
              </li>
              @endcan
             
             @can('warranty-list')
              <li class="nav-item">
                <a href="{{url('warranty')}}"  class="nav-link {{Route::is('warranty.*')   ? 'active' : '' }}" ><i class="fa fa-laptop nav-icon"></i> <p>{{ __('label.Warranty') }}</p></a>
              </li>
              @endcan

             @can('transection_terms-list')
              <li class="nav-item">
                <a href="{{url('transection_terms')}}"  class="nav-link {{Route::is('transection_terms.*')   ? 'active' : '' }}" ><i class="fa fa-laptop nav-icon"></i> <p>{{ __('label.Transection Terms') }}</p></a>
              </li>
              @endcan
             @can('vat-rules-list')
              <li class="nav-item">
                <a href="{{url('vat-rules')}}"  class="nav-link {{Route::is('vat-rules.*')   ? 'active' : '' }}" ><i class="fa fa-laptop nav-icon"></i> <p>{{ __('label.Vat Rules') }}</p></a>
              </li>
              @endcan
             @can('item-information-list')
              <li class="nav-item">
                <a href="{{url('item-information')}}"  class="nav-link {{Route::is('item-information.*')   ? 'active' : '' }}" ><i class="fa fa-laptop nav-icon"></i> <p>{{ __('label.Item Information') }}</p></a>
              </li>
              @endcan
             @can('lot-item-information')
              <li class="nav-item">
                <a href="{{url('lot-item-information')}}"  class="nav-link {{Route::is('lot-item-information')   ? 'active' : '' }}" ><i class="fa fa-laptop nav-icon"></i> <p>{{ __('label.Lot Item Information') }}</p></a>
              </li>
              @endcan
             @can('labels-print')
              <li class="nav-item">
                <a href="{{url('labels-print')}}"  class="nav-link {{Route::is('labels-print')   ? 'active' : '' }}" ><i class="fa fa-laptop nav-icon"></i> <p>{{ __('label.Labels Print') }}</p></a>
              </li>
              @endcan

             
            </ul>
          </li>
          @endcan
          <li class="nav-item {{ Route::is('roles.*') || Route::is('users.*') || Route::is('sms-send')  || Route::is('all-lock') || Route::is('invoice-prefix') || Route::is('database-backup') || Route::is('admin-settings') || Route::is('branch.*') || Route::is('social_media.*') || Route::is('cost-center.*') || Route::is('store-house.*')  ? 'menu-is-opening menu-open' : '' }}">
            <a href="#" class="nav-link {{ Route::is('roles.*') || Route::is('users.*') || Route::is('admin-settings') || Route::is('branch.*')  || Route::is('cost-center.*')|| Route::is('sms-send') || Route::is('invoice-prefix')  || Route::is('database-backup')  || Route::is('all-lock')  || Route::is('store-house.*')   ? 'active' : '' }}">
            
               <i class="fas fa-cog nav-icon"></i> 
              <p>
                {{ __('label.Settings') }}
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
               @can('admin-settings')
              <li class="nav-item">

                <a href="{{url('admin-settings')}}" class="nav-link {{Route::is('admin-settings')   ? 'active' : '' }}">
                  <i class="fa fa-asterisk nav-icon"></i> 
                  <p>{{ __('label.General Settings') }}</p>
                </a>
              </li>
              @endcan
            
               @can('bulk-sms')
              <li class="nav-item">

                <a href="{{url('sms-send')}}" class="nav-link {{Route::is('sms-send')   ? 'active' : '' }}">
                  <i class="fa fa-asterisk nav-icon"></i> 
                  <p>{{ __('label.SMS SEND') }}</p>
                </a>
              </li>
              @endcan
               @can('invoice-prefix')
              <li class="nav-item">

                <a href="{{url('invoice-prefix')}}" class="nav-link {{Route::is('invoice-prefix')   ? 'active' : '' }}">
                  <i class="fa fa-asterisk nav-icon"></i> 
                  <p>{{ __('label.Invoice Prefix') }}</p>
                </a>
              </li>
              @endcan
              @can('social_media-list')
              <li class="nav-item">

                <a href="{{url('social_media')}}" class="nav-link {{Route::is('social_media.*')   ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Social Media</p>
                </a>
              </li>
              @endcan
               @can('role-list')
              <li class="nav-item">
                <a href="{{url('roles')}}" class="nav-link {{Route::is('roles.*')   ? 'active' : '' }}">
                  <i class="fa fa-server nav-icon"></i>
                  <p>{{ __('label.Roles') }}</p>
                </a>
              </li>
              @endcan
              @can('user-list')
              <li class="nav-item">
                <a href="{{url('users')}}" class="nav-link {{Route::is('users.*')   ? 'active' : '' }}">
                  <i class="fas fa-users nav-icon"></i>
                  <p>{{ __('label.Users') }}</p>
                </a>
              </li>
              @endcan
              @can('branch-list')
              <li class="nav-item">
                <a href="{{url('branch')}}" class="nav-link {{Route::is('branch.*')   ? 'active' : '' }}">
                 <i class="fa fa-share-alt nav-icon"></i>
                  <p>{{ __('label.Branch') }} </p>
                </a>
              </li>
              @endcan
              @can('cost-center-list')
              <li class="nav-item">
                <a href="{{url('cost-center')}}" class="nav-link {{Route::is('cost-center.*')   ? 'active' : '' }}">
                  <i class="fa fa-adjust nav-icon"></i>
                  <p>{{ __('label.Cost center') }}</p>
                </a>
              </li>
              @endcan
              @can('store-house-list')
              <li class="nav-item">
                <a href="{{url('store-house')}}" class="nav-link {{Route::is('store-house.*')   ? 'active' : '' }}">
                  <i class="fas fa-store  nav-icon"></i>
                  <p>{{ __('label.Store House') }}</p>
                </a>
              </li>
              @endcan
              @can('lock-permission')
              <li class="nav-item">
                <a href="{{url('all-lock')}}" class="nav-link {{Route::is('all-lock')   ? 'active' : '' }}">
                  <i class="fas fa-store  nav-icon"></i>
                  <p>{{ __('label.Transection Lock System') }}</p>
                </a>
              </li>
              @endcan
              @can('database-backup')
              <li class="nav-item">
                <a href="{{url('database-backup')}}" class="nav-link {{Route::is('database-backup')   ? 'active' : '' }}">
                  <i class="fas fa-store  nav-icon"></i>
                  <p>{{ __('label.Data Backup') }}</p>
                </a>
              </li>
              @endcan
            </ul>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>