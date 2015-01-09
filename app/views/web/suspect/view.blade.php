@extends('web.components.boilerplate')

@section('title')
{{ Privacylib::privName($suspect->name) }} Data
@stop

@section('content')

<div class="container">
      <div class="row">
	    <div class="col-md-12"><img
		  @if($suspect->gender == "M")
			src="/assets/images/avatar_male.png"
		  @elseif($suspect->gender == "F")
			src="/assets/images/avatar_female.png"
		  @else
			src="/assets/images/avatar_unknown.png"
		  @endif
	      
	      
	      class="img-circle pull-left" style="width: 100px;">
		  <h2>{{ Privacylib::privName($suspect->name) }}</h2>
		  </h4>(Jail ID# {{ $suspect->jail_id }})</h4>
		  @if(Auth::user()->admin)
			<br /><a href="/suspect/deletesuspect/{{ $suspect->id }}" class="btn btn-danger btn-xs"><i class="glyphicon glyphicon-trash"></i> Delete Suspect</a>
		  @endif
	    </div>
	    <div class="col-md-12">
	      <h4>Personal Data</h4>
		  <table class="table table-condensed" id="suspectdata">
		    <tbody>
		      <tr>
			<td class="info">Gender:</td>
			<td id="gender">{{ $suspect->gender }}</td>
			<td class="info">Weight:</td>
			<td id="weightl">{{ $suspect->weight }} lbs</td>
			<td class="info">Occupation: </td>
			<td id="occupation">{{ Privacylib::stdCase($suspect->occupation) }}</td>
		      </tr>
		      <tr>
			<td class="info">Height:</td>
			<td id="height">{{ $suspect->height }}</td>
			<td class="info">Hair/Eye color:</td>
			<td id="haireyecolor">{{ $suspect->haircolor }}/{{ $suspect->eyecolor }}</td>
			<td class="info">Age:</td>
			<td id="birthdate">{{ $yearsold }} (DOB: {{$suspect->birthdate }}) </td>
		      </tr>
			<tr>		
			<td class="info">Address: </td>
			<td id="address" colspan="5">{{ Privacylib::stdCase($suspect->address) }}</td>
			</tr>
		    </tbody>
		  </table>
	    </div>
	    
	    <div class="col-md-12">
	    <h4>Arrest Data</h4>
		  <table class="table table-condensed" id="suspectdata">
		    <tbody>
		      <tr>
			<td class="warning">Arrest Date:</td>
			<td id="arrestdate">{{ $suspect->arrestdate }}</td>
			<td class="warning">Arrest Agency:</td>
			<td id="arrestagency">{{ $suspect->arrestagency }}</td>
			<td class="warning">Arrest Location: </td>
			<td id="arrestlocation">{{ Privacylib::stdCase($suspect->arrestlocation) }}</td>
		      </tr>
		      <tr>
			<td class="warning">Latest Charge Date:</td>
			<td id="chargedate">{{ $suspect->latestchargedate }}</td>
			<td class="warning">Booking Date:</td>
			<td id="bookingdate">{{ $suspect->bookingdate }}</td>
			<td class="warning">CourtLink Court Dates:</td>
			<td id="courtlink"><a href="{{ $suspect->courtlink }}"><i class="glyphicon glyphicon-share-alt"></i>Go to Marin County Courts' Site</a></td>
		      </tr>
		    </tbody>
		  </table>
	    </div>
		  
	    <div class="col-md-12">
	    <h4>Charges</h4>
		  <table class="table table-condensed" id="suspectdata">
		    <tbody>
			<?php 
			      $prevdate="";
			?>
			  
		  @foreach($charges as $charge)
			@if($charge->sentencetime != $prevdate)
			      <?php $prevdate=$charge->sentencetime; ?>
			      <tr>
				    <td colspan="7" style="border: 0px;">Charges as of {{ $charge->sentencetime }}</td>
			      </tr>
			@endif
			      <tr>
			        <td style="border: 0px;"></td>
				<td class="{{ Privacylib::crimeTypeToBootstrap($charge->type) }}">{{ Privacylib::codeToLink($charge->charge) }}</td>
				<td class="{{ Privacylib::crimeTypeToBootstrap($charge->type) }}">{{ $charge->description }}</td>
				<td class="{{ Privacylib::crimeTypeToBootstrap($charge->type) }}">{{ Privacylib::crimeType($charge->type) }}</td>
			        <td class="{{ Privacylib::crimeTypeToBootstrap($charge->type) }}">{{ Privacylib::bailFormat($charge->bail) }}</td>
			        <td class="{{ Privacylib::crimeTypeToBootstrap($charge->type) }}">{{ $charge->auth }}</td>
			        <td class="{{ Privacylib::crimeTypeToBootstrap($charge->type) }}">
				    @if(Auth::user()->admin)
					  <a href="/suspect/deletecharge/{{ $charge->id }}" class="btn btn-danger btn-xs"><i class="glyphicon glyphicon-trash"></i> Delete Charge</a>
				    @endif
			        </td>
			
			      </tr>


		  @endforeach
		    </tbody>
		  </table>
	    </div>
      </div>
</div>
      
@stop