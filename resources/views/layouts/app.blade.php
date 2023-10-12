<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <!-- Required meta tags -->
    <title>@yield('title')</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

      <!-- Latest compiled and minified CSS -->
    
    <link href="{{asset('backend/bootstrap.min.css')}}" />
    <link href="{{asset('backend/bootstrap-theme.min.css')}}" />
  
    

   
  </head>
  <body class="">
   
   <div class="mainSection page_bg_image" >
       @yield('content')
   </div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<!-- Latest compiled and minified JavaScript -->


<script src="{{asset('backend/jquery.min.js')}}" ></script>
<script src="{{asset('backend/bootstrap.min.js')}}" ></script>
<script type="text/javascript">
   
</script>
@yield('script')
  </body>
</html>