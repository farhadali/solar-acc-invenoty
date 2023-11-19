
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
        @can('import-material-receive-list')
         <div style="display: flex;">
         <a href="{{route('import-material-receive.index')}}" class="dropdown-item">
            <i class="fa fa-fax mr-2" aria-hidden="true"></i> {{ __('label.import-material-receive') }}
          </a>
          <a  href="{{route('import-material-receive.create')}}" class="dropdown-item text-right">
            <i class="nav-icon fas fa-plus"></i>
          </a>
        </div>
        @endcan
      </li>
    @endcan