<!DOCTYPE html>
<html lang="en">
  
<head>
    <meta charset="utf-8">     
     
    @yield('viewportoverride', '<meta name="viewport" content="width=device-width, initial-scale=.7" />')
    @yield('addtlheaders', '')
    
    {{ HTML::style('/assets/css/bootstrap.min.css') }}
    @if(isset($datables))
      {{ HTML::style('/assets/css/dataTables.bootstrap.css') }}
      {{ HTML::style('/assets/css/jquery.dataTables.min.css') }}
    @endif
    
    <style>@yield('addtlcss', '')
    .bodypadding{padding-top: 65px;}
    </style>
    
    <meta name="description" content="Danno's Book is your resource for historical arrest records and background checks in Marin County.">
    <meta name="author" content="Danno's Book">

    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />

    <link rel="apple-touch-icon" href="/apple-touch-icon.png" />
    <link rel="apple-touch-icon" sizes="57x57" href="/apple-touch-icon-57x57.png" />
    <link rel="apple-touch-icon" sizes="72x72" href="/apple-touch-icon-72x72.png" />
    <link rel="apple-touch-icon" sizes="76x76" href="/apple-touch-icon-76x76.png" />
    <link rel="apple-touch-icon" sizes="114x114" href="/apple-touch-icon-114x114.png" />
    <link rel="apple-touch-icon" sizes="120x120" href="/apple-touch-icon-120x120.png" />
    <link rel="apple-touch-icon" sizes="144x144" href="/apple-touch-icon-144x144.png" />
    <link rel="apple-touch-icon" sizes="152x152" href="/apple-touch-icon-152x152.png" />
    
    
    <title>
      @yield('title') || Danno's Book
    </title>
    
    
  </head>
  <body style="padding-top: 65px;">
    <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a href="/dashboard"" class="navbar-brand"><i class="glyphicon glyphicon-user"></i> Danno's Book</a>
        </div>
	@include('web.components.menu')
	
      </div>
    </div>
    <div class="container">
        @if(Session::has('success'))
            <div class="alert alert-success alert-dismissable">{{ Session::get('success') }}
	      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">X</button></p>
	    {{ Session::forget('success') }}
	    </div>
        @endif
	
	  @if(count($errors))
	    <div class="alert alert-danger alert-dismissable">
	    	@foreach($errors->all() as $error)
			<li>{{ $error }}</li>
		@endforeach
	    
	  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">X</button></div>
	  @endif

	
	@if(Session::has('failure'))
	    <div class="alert alert-danger alert-dismissable">{{ Session::get('failure') }}
	  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">X</button></div>
	      {{ Session::forget('failure') }}
	@endif

	@yield('content')


      <center><footer>
      &nbsp;
      <hr>
        <p>
          Formatting & Display &copy; Danno's Book {{ date('Y') }} | <a href="/cya/terms">Terms and Conditions</a>
        </p>
      </footer></center>
    </div>
      
      	
    <!-- /container -->
    {{ HTML::script('/assets/js/jquery.min.js') }}
    {{ HTML::script('/assets/js/bootstrap.min.js') }}
    {{ HTML::script('/assets/js/jquery.detectmobile.js') }}
    @if(isset($datables))
      {{ HTML::script('/assets/js/jquery.dataTables.min.js') }}
      {{ HTML::script('/assets/js/dataTables.bootstrap.js') }}
    @endif
    @if(isset($highcharts))
      {{ HTML::script('/assets/js/highcharts.js') }}
      {{ HTML::script('//code.highcharts.com/modules/exporting.js') }}
      {{ HTML::script('//code.highcharts.com/modules/data.js') }}
    @endif
    
    <script type="text/javascript">@yield('js', '')</script>
  </body>

</html>