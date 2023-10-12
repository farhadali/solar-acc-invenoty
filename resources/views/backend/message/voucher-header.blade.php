              
                
                @can('voucher-create')
               
                        <a title="Add New" class="btn  btn-sm btn-default" href="{{ route('voucher.create') }}"> <i class="nav-icon fas fa-plus"></i> Voucher </a>
                        
                @endcan 
                @can('cash-receive')
                   
                        <a title="Add New" class="btn  btn-sm btn-default" href="{{ url('cash-receive') }}"> <i class="nav-icon fas fa-plus"></i> CR </a>
                  
                @endcan 
                @can('cash-payment')
                   
                        <a title="Add New" class="btn  btn-sm btn-default" href="{{ url('cash-payment') }}"> <i class="nav-icon fas fa-plus"></i> CP </a>
                   
                @endcan 
                @can('bank-receive')
                    
                        <a title="Add New" class="btn  btn-sm btn-default" href="{{ url('bank-receive') }}"> <i class="nav-icon fas fa-plus"></i> BR </a>
                   
                @endcan 
                @can('bank-payment')
                     
                        <a title="Add New" class="btn  btn-sm btn-default" href="{{ url('bank-payment') }}"> <i class="nav-icon fas fa-plus"></i> BP </a>
                   
                @endcan 
                
                
                