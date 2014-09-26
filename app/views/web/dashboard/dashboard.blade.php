@extends('web.components.boilerplate')

@section('title')
Dashboard     
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
	<h1>Dashboard</h2>
        </div>
      </div>
	    <div class="row">
             <div class="col-md-12">
		  <h3>Most Recent Charges <a href="http://dannosbook.com/search/recent">View Last 50</a></h3>
			<div class="table-responsive">
			      <table class="table">
				    <tbody>
					  @foreach($tenrecents as $recent)
						<tr class="info">
						<?php
						      $birthday = new DateTime($recent->birthdate);
						      $interval = $birthday->diff(new DateTime);
						      $yearsold = $interval->y;
						?>
						    <td>{{ $recent->arrestdate }}</td>
						    <td><a href="{{ URL::action('SuspectController@getView', array('id'=>$recent->id)) }}">{{ Privacylib::privName($recent->name) }}</a> ({{ $yearsold }} years old)</td>
						    <td>{{ Privacylib::stdCase($recent->occupation) }}</td>
						    <td colspan="2">Arrested at {{ Privacylib::stdCase($recent->arrestlocation) }}</td>
						</tr>
						@foreach($recent->charge as $singlecharge)
						      <tr>
							    <td></td>
							    <td colspan="2">{{ $singlecharge->description }}</td>
							    <td class="{{ Privacylib::crimeTypeToBootstrap($singlecharge->type) }}">{{ Privacylib::crimeType($singlecharge->type) }}</td>
							    <td>{{ Privacylib::bailFormat($singlecharge->bail) }}</td>
						      </tr>
						@endforeach
					  @endforeach
				    </tbody>
			      </table>
			</div>
			
	     </div>
    </div>
      <div class="row">
	    <div class="col-md-6">
		 <h3>Weighted Worst Alleged Offenders <a href="{{ URL::action('AnalysisController@getCounts') }}">View All</a>	</h3>
		       <hr />
		       <table class="table table-striped">
			     <thead>
				   <tr class="info"> 
					 <td>Name</td>
					 <td>Felonies</td>
					 <td>Misdemeanor</td>
					 <td>Infractions</td>
				   </tr>
			     </thead>
			     <tbody>
			     @foreach($worstoffenders as $offender)
				   <tr> 
					 <td><a href="{{ URL::action('SuspectController@getView', array('id'=>$offender->bookee_id)) }}">{{ Privacylib::privName($offender->name) }}</a></td>
					 <td>{{ $offender->felonies }}</td>
					 <td>{{ $offender->misdemeanors }}</td>
					 <td>{{ $offender->infractions }}</td>
				   </tr>
			     @endforeach
			     </tbody>
			   </table>
		       
	    </div>
	    <div class="col-md-6">
		  <div class="row">
			<div class="col-md-12">
			      <h3>Extremes</h3>
				    <hr />
				    <table class="table table-striped">
					  <tbody>
						<tr> 
						      <td><strong>Tallest</strong></td>
						      <td><a href="{{ URL::action('SuspectController@getView', array('id'=>$tallest->id)) }}">{{ Privacylib::privName($tallest->name) }}</a></td>
						      <td>{{ Privacylib::inchesToString($tallest->ht) }}</td>
						</tr>
						<tr> 
						      <td><strong>Shortest</strong></td>
						      <td><a href="{{ URL::action('SuspectController@getView', array('id'=>$shortest->id)) }}">{{ Privacylib::privName($shortest->name) }}</a></td>
						      <td>{{ Privacylib::inchesToString($shortest->ht) }}</td>
						</tr>
						<tr> 
						      <td><strong>Lightest</strong></td>
						      <td><a href="{{ URL::action('SuspectController@getView', array('id'=>$lightest->id)) }}">{{ Privacylib::privName($lightest->name) }}</a></td>
						      <td>{{ $lightest->weight }} lbs</td>
						</tr>
						<tr> 
						      <td><strong>Heaviest</strong></td>
						      <td><a href="{{ URL::action('SuspectController@getView', array('id'=>$heaviest->id)) }}">{{ Privacylib::privName($heaviest->name) }}</a></td>
						      <td>{{ $heaviest->weight }} lbs</td>
						</tr>
						<tr>
						      <td colspan="3"><strong>Top Bails</strong></td>
						</tr>
						@foreach($topbails as $topbail)
						<tr>
						      <td></td>
						      <td><a href="{{ URL::action('SuspectController@getView', array('id'=>$topbail->bookee_id)) }}">{{ Privacylib::privName($topbail->name) }}</a></td>
						      <td>{{ Privacylib::bailFormat($topbail->totbail) }}</td>
						</tr>
						@endforeach
					  </tbody>
					</table>
				    
			 </div>
		  </div>
		  <div class="row">
			<div class="col-md-12">
			      <h3>Live Update Status</h3>
				    <div id="scraperstatus">Loading...</div>
				    
			 </div>
			<div class="col-md-12">
			      <h3>Alternate Data Formats</h3>
			      <div class="row">
				    <div class="col-md-12">
					  <center>
						Please rate limit yourself by only refreshing your JSON after the database has updated (Unix timestamp of last update is avilable <a href="/lastloadtime">here</a>; no auth required).
					  </center>
						<hr />
				    <div class="col-md-6">
					  <center>
						<a href="/altdata/rss">
						      <img style="width: 70px;" src="/assets/images/rss.jpg"><br />RSS (last 30 arrests)
						</a>
					  </center>
				    </div>
				    <div class="col-md-6">
					  <center>
						<a href="/altdata/json">
						      <img style="width: 70px;" src="http://upload.wikimedia.org/wikipedia/commons/thumb/c/c9/JSON_vector_logo.svg/160px-JSON_vector_logo.svg.png"><br />JSON (full DB dump)
						</a>
					  </center>
				    </div>
			      </div>
			</div>
		  </div>
	    </div>
      </div>
@stop