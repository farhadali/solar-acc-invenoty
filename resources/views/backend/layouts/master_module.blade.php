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
          
           <a  href="{{route('account-ledger.create')}}" class="dropdown-item text-right "  >
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
        @can('mother-vessel-info-list')
         <div class="dropdown-divider"></div> 
        <div style="display: flex;">
           <a href="{{url('mother-vessel-info')}}" class="dropdown-item">
            <i class="fa fa-list-alt mr-2" aria-hidden="true"></i>{{ __('label.mother-vessel-info') }}
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