 @can('account-menu') 
      <li class="nav-item dropdown remove_from_header">
        <a class="nav-link" data-toggle="dropdown" href="#">
           {{ __('label.receive_payment') }} <i class="right fas fa-angle-down"></i>
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