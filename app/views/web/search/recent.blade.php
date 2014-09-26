@extends('web.components.boilerplate')

@section('title')
Recent Arrests     
@stop

@section('content')

<div class="container">
      <div class="row">
        <div class="col-md-12">
	<h1>Recent Arrests</h2>
        </div>
      </div>
	    <div class="row">
             <div class="col-md-12">
		  <h3>Most Recent Charges</h3>
			<div class="table-responsive">
			      <table class="table">
				    <tbody>
					  @foreach($recents as $recent)
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
</div>
@stop