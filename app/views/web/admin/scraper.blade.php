@extends('web.components.boilerplate')

@section('title')
Admin     
@stop

@section('js')
getStatus();

var repeating = setInterval(function(){getStatus()}, 5000);

function getStatus()
{
   $.ajax({

     type: "GET",
     url: '/dashboard/scraperstatus',
     success: function(data) {
          $('#scraperstatus').html(data);
     }
   });

}
@stop

@section('content')
<div class="container">
      <div class="row">
	    <div class="col-md-12">
		  <h1>Scraper Admin</h1>
		  <p>Raw cache  objects are the raw elements in the (cached) HTML scrape. Loaded objects have been parsed into the database.</p>
		
	    <div id="scraperstatus">
		 Loading...
	    </div>	
		  
		  <hr />
	    </div>
      </div>
      <div class="row">
	    <div class="col-md-4">
		  <h3>Auto Loading</h3><hr />
		  <?php $autodisable = Cache::get('automated', false) ? 'disabled' : '';
		  $inverseautodisable = Cache::get('automated', false) ? '' : 'disabled';
		  ?>
		  {{ HTML::link(URL::action('AdminController@getLoadCommand'), 'Trigger Load Command', array('class'=>'btn btn-success btn-block', $autodisable)) }}
		  {{ HTML::link(URL::action('AdminController@getReset'), 'Cancel Load Command', array('class'=>'btn btn-warning btn-block', $inverseautodisable)) }}
		  <hr />
		  <h3>Manual Loading</h3>
		  <?php $autodisable = Cache::get('automated', false) ? 'disabled' : '' ?>
		  {{ HTML::link(URL::action('AdminController@getLoadCache'), '(1a) Load Raw Cache (Production; from website)', array('class'=>'btn btn-primary btn-block', $autodisable)) }}
		  {{ HTML::link(URL::action('AdminController@getLoadCache', array('test'=>1)), '(1b) Load Raw Cache (Dev; from file)', array('class'=>'btn btn-info btn-block', $autodisable)) }}
		  {{ HTML::link(URL::action('AdminController@getLoadDB'), '(2) Load DB from Raw (slow)', array('class'=>'btn btn-danger btn-block', $autodisable)) }}
	    </div>
	    <div class="col-md-4">
		  <h3>Viewing</h3><hr />
		  {{ HTML::link(URL::action('AdminController@getBookees'), 'View Loaded Bookees', array('class'=>'btn btn-default btn-block')) }}
		  </div>
	    <div class="col-md-4">
	    <h3>Clear & Reset</h3><hr />
		  {{ HTML::link(URL::action('AdminController@getForget'), 'Flush Cache', array('class'=>'btn btn-success btn-block', $autodisable)) }}
		  {{ HTML::link(URL::action('AdminController@getForget', array('flushdb'=>1)), 'Flush Cache & DB', array('class'=>'btn btn-danger btn-block', $autodisable)) }}
	    </div>
      </div>
      <br /><br />
      @if(isset($data))
	    <hr />
      <pre>{{ $data }}</pre>
	    <hr />
      @endif
</div>
      
@stop