 @can('hrm-module') 
      <li class="nav-item dropdown remove_from_header ">
        <a class="nav-link" data-toggle="dropdown" href="#">
           {{ __('label.hrm') }} <i class="right fas fa-angle-down"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
       <p style="padding-left: 20px;"><b>{{__('label.leave')}}</b></p>
       <div class="dropdown-divider"></div>
       @can('hrm-attandance-list')
        <div style="display: flex;">
         <a href="{{url('attandance')}}" class="dropdown-item">
            <i class="fa fa-fax mr-2" aria-hidden="true"></i> {{ __('label.hrm-attandance') }}
          </a>
          <a  href="{{route('attandance.create')}}" class="dropdown-item text-right">
            <i class="nav-icon fas fa-plus"></i>
          </a>
        </div>
        @endcan
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
        @can('monthly-salary-structure-list')
        <div style="display: flex;">
         <a href="{{url('monthly-salary-structure')}}" class="dropdown-item">
            <i class="fa fa-fax mr-2" aria-hidden="true"></i> {{ __('label.monthly-salary-structure') }}
          </a>
          <a  href="{{route('initial-salary-structure.create')}}" class="dropdown-item text-right">
            <i class="nav-icon fas fa-plus"></i>
          </a>
        </div>
        @endcan
        @can('initial-salary-structure-list')
        <div style="display: flex;">
         <a href="{{url('initial-salary-structure')}}" class="dropdown-item">
            <i class="fa fa-fax mr-2" aria-hidden="true"></i> {{ __('label.initial-salary-structure') }}
          </a>
          <a  href="{{route('initial-salary-structure.create')}}" class="dropdown-item text-right">
            <i class="nav-icon fas fa-plus"></i>
          </a>
        </div>
        @endcan
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
    @endcan
      