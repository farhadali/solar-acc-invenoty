<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav" >
      
     
    <li class="nav-item">
        <a class="nav-link " data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
     
 <!-- Messages Dropdown Menu -->
     @include('backend.layouts.hrm_module')
     @include('backend.layouts.account_module')
     @include('backend.layouts.rlp_module')
     
      <!-- Messages Dropdown Menu -->
      <li class="nav-item dropdown"> </li>
      @include('backend.layouts.inventory_module')
   <li class="nav-item " style="margin-top:5px;">
           <a  href="{{url('report-panel')}}" class="dropdown-item custom_nav_item" >
            <span >{{__('label.report_panel')}} </span>
          </a>
         
       </li>
   @include('backend.layouts.master_module')   
   @include('backend.layouts.setting_module')   
     
      
      
      
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