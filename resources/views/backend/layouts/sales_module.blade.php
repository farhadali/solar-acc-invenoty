 @can('inventory-menu') 
      <li class="nav-item dropdown remove_from_header">
        <a class="nav-link" data-toggle="dropdown" href="#">
          
          {{ __('label.entry') }} <i class="right fas fa-angle-down"></i>
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
            <i class="fa fa-shopping-cart mr-2" aria-hidden="true"></i> {{ __('label.sales-order') }}
          </a>
          <a  href="{{ route('sales-order.create') }}" 
          class="dropdown-item text-right  "> 
            <i class="nav-icon fas fa-plus"></i> </a>

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
       
        
       
          @can('transfer-list')
         <div class="dropdown-divider"></div>  
        <div style="display: flex;">
           <a href="{{url('transfer')}}" class="dropdown-item">
            <i class="fa fa-recycle mr-2" aria-hidden="true"></i> {{ __('label.transfer') }}
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