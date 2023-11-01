@extends('backend.layouts.app')
@section('title',$settings->name ?? '')
@section('content')
@php
$users = Auth::user();
@endphp
<!-- Content Header (Page header) -->


    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 _page_name">Dashboard </h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a class="_page_name" href="{{url('home')}}">Home</a></li>
              <li class="breadcrumb-item _page_name ">Dashboard </li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <style type="text/css">
      .card-title {
        float: none;
    text-align: center !important;

    font-size: 1.1rem;
    font-weight: 400;
    margin: 0;
    padding: 5px;
}
.card-body{
  background: #fff;
  padding:10px;
}
    </style>
    <!-- /.content-header -->
<div class="content" >
      <div class="container-fluid">
        <div class="row">
          @can('quick-link')
          <div class="col-md-6">
            <div class="card ">
              <div class="card-header border-0">
                <h3 class="card-title">Quick Link</h3>
                <div class="card-tools"></div>
              </div>
              <div class="card-body table-responsive p-0 info-box">
                  <table class="table table-striped table-valign-middle">
                    @can('voucher-list')
                    <tr>
                      <th>
                         
                          <div style="display: flex;">
                           <a href="{{url('voucher')}}" class="dropdown-item">
                              <i class="fa fa-fax mr-2" aria-hidden="true"></i> Voucher
                            </a>
                             <a  href="{{route('voucher.create')}}" class="dropdown-item text-right">
                              <i class="nav-icon fas fa-plus"></i>
                            </a>
                          </div>
                          
                      </th>
                  </tr>
                   @endcan 
                   @can('purchase-list')
                    <tr>
                      <th>
                        
                           <div style="display: flex;">
                           <a href="{{url('purchase')}}" class="dropdown-item">
                            <i class="fa fa-list-alt mr-2" aria-hidden="true"></i> {{__('label.material_receive')}}
                          </a>

                             <a  href="{{route('purchase.create')}}" class="dropdown-item text-right " > 
            <i class="nav-icon fas fa-plus"></i> </a>
                        </div>
                         
                      </th>
                  </tr>
                  @endcan
                   @can('purchase-return-list')
                    <tr>
                      <th>
                        
                            <div style="display: flex;">
                               <a href="{{url('purchase-return')}}" class="dropdown-item">
                                <i class="fa fa-list-alt mr-2" aria-hidden="true"></i>  {{__('label.material_return')}}
                              </a>
                              <a  href="{{route('purchase-return.create')}}" 
          class="dropdown-item text-right  " > 
            <i class="nav-icon fas fa-plus"></i> </a>
                               
                            </div>
                            
                      </th>
                  </tr>
                   @endcan
                  @can('sales-list')
                    <tr>
                      <th>
                         
                        <div style="display: flex;">
                           <a href="{{url('sales')}}" class="dropdown-item">
                            <i class="fa fa-shopping-cart mr-2" aria-hidden="true"></i> {{__('label.material_issue')}}
                          </a>
                           <a  href="{{route('sales.create')}}" class="dropdown-item text-right">
                            <i class="nav-icon fas fa-plus"></i>
                          </a>
                        </div>
                        
                      </th>
                  </tr>
                   @endcan 
                  @can('restaurant-sales-list')
                    <tr>
                      <th>
                         
                        <div style="display: flex;">
                           <a href="{{url('restaurant-sales')}}" class="dropdown-item">
                            <i class="fa fa-shopping-cart mr-2" aria-hidden="true"></i> Restaurant Sales
                          </a>
                           <a  href="{{route('restaurant-sales.create')}}" class="dropdown-item text-right">
                            <i class="nav-icon fas fa-plus"></i>
                          </a>
                        </div>
                        
                      </th>
                  </tr>
                   @endcan 
                    <tr>
                      <th>
                        @can('sales-return-list')
          
                        <div style="display: flex;">
                           <a href="{{url('sales-return')}}" class="dropdown-item">
                            <i class="fa fa-shopping-cart mr-2" aria-hidden="true"></i> {{__('label.issued_material_return')}}
                          </a>
                           <a  href="{{route('sales-return.create')}}" class="dropdown-item text-right">
                            <i class="nav-icon fas fa-plus"></i>
                          </a>
                        </div>
                          
                         @endcan  
                      </th>
                  </tr>
                  </table>
              </div>
            </div>
          </div>
          @endcan


    
</script>

@endsection