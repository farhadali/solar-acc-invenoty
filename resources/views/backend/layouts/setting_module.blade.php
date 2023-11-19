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