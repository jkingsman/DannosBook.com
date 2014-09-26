@extends('web.components.boilerplate') 

@section('title')
Search Results
@stop

@section('js')
$(document).ready(function() {
    $('#results').dataTable({
      "iDisplayLength": 25,
      "order": [[ 3, "desc" ]]
    });
} );
@stop

@section('content')
@if($datables=true)@endif
<div class="container">
      <div class="row">
	    <div class="col-md-12">
		<h1>Search ({{ $count }} results)</h1>
		  
		  <hr />
	    </div>
	    <div id="filters">
	    <div class="col-md-12">
		<h2>Search Criteria <button type="button" class="btn btn-primary btn-xs" onClick='$("#criteria").toggle("fast");'>Toggle Criteria Display</button></h3>
		  <div style="display: none;" id="criteria">
		  <table class="table table-bordered table-striped" id="suspectdata">
			<tbody>
			<tr>
			      <td><strong>Name contains:</strong></td>
			      <td id="name">{{ Input::old('name') }}</td>
			      <td><strong>Gender contains (M/F):</strong></td>
			      <td id="gender">{{ Input::old('gender') }}</td>
			      
			</tr>
			<tr>
			      <td><strong>Hair Color contains:</strong></td>
			      <td id="haircolor">{{ Input::old('haircolor') }}</td>
			      <td><strong>Eye Color contains:</strong></td>
			      <td id="haircolor">{{ Input::old('eyecolor') }}</td>
			</tr>
			<tr>
			      <td><strong>Min Weight (lbs):</strong></td>
			      <td id="minweight">{{ Input::old('minweight', 0) }}</td>
			      <td><strong>Max Weight (lbs):</strong></td>
			      <td id="maxweight">{{ Input::old('maxweight', 2000) }}</td>
			</tr>
			<tr>
			      <td><strong>Min DOB (YYYY-MM-DD):</strong></td>
			      <td id="minage">{{ Input::old('mindob', '1800-01-01') }}</td>
			      <td><strong>Max DOB (YYYY-MM-DD):</strong></td>
			      <td id="maxage">{{ Input::old('maxdob') }}</td>
			</tr>
			<tr>	
			      <td><strong>Home Address contains: </strong></td>
			      <td id="address">{{ Input::old('address') }}</td>
			      <td><strong>Occupation contains:</strong></td>
			      <td id="occupation">{{ Input::old('occupation') }}</td>
			</tr>
			</tbody>
		  </table>
		  <table class="table table-bordered table-striped" id="arrestdata">
			<tbody>
			      <tr>
				    <td><strong>Min Arrest Date (YYYY-MM-DD):</strong></td>
				    <td id="minarrestdate">{{ Input::old('minarrest') }}</td>
				    <td><strong>Max Arrest Date (YYYY-MM-DD):</strong></td>
				    <td id="maxarrestdate">{{ Input::old('maxarrest') }}</td>
			      </tr>
			      <tr>
				    <td><strong>Min Latest Charge Date (YYYY-MM-DD):</strong></td>
				    <td id="minchargedate">{{ Input::old('minlatestcharge') }}</td>
				    <td><strong>Max Latest Charge Date (YYYY-MM-DD):</strong></td>
				    <td id="maxchargedate">{{ Input::old('maxlatestcharge') }}</td>
			      </tr>
			      <tr>
				    <td><strong>Min Booking Date (YYYY-MM-DD):</strong></td>
				    <td id="minbookingdate">{{ Input::old('minbooking', '1800-01-01') }}</td>
				    <td><strong>Max Booking Date (YYYY-MM-DD):</strong></td>
				    <td id="maxbookingdate">{{ Input::old('maxbooking') }}</td>
			      </tr>
			      <tr>
				    <td><strong>Arrest Agency contains:</strong></td>
				    <td id="arrestagency">{{ Input::old('arrestagency') }}</td>
				    <td><strong>Arrest Location contains:</strong></td>
				    <td id="arrestlocation">{{ Input::old('arrestloc') }}</td>
			      </tr>
			</tbody>
		  </table>
		  <table class="table table-bordered table-striped" id="arrestdata">
			<tbody>
			      <tr>
				    <td><strong>Charge ID (e.g. '664 484(A) PC') contains:</strong></td>
				    <td id="maxarrestdate">{{ Input::old('chargeid') }}</td>
				    <td><strong>Type (e.g. 'F', 'M', 'I', etc.) contains:</strong></td>
				    <td id="type">{{ Input::old('type') }}</td>
				    
				    
			      </tr>
			      <tr>
				    <td><strong>Description contains:</strong></td>
				    <td id="minarrestdate">{{ Input::old('description') }}</td>
				    <td><strong>Authority contains:</strong></td>
				    <td id="arrestagency">{{ Input::old('authority') }}</td>
				    
			      </tr>
			      <tr>
				    <td><strong>Min Bail:</strong></td>
				    <td id="maxbookingdate">{{ Input::old('minbail') }}</td>
				    <td><strong>Max Bail:</strong></td>
				    <td id="minbookingdate">{{ Input::old('maxbail') }}</td>
			      </tr>
			</tbody>
		  </table>
		  <hr />
		  </div>
	    </div>
	    </div>
	    <div class="col-md-12">
	    <h2>Results</h2>
		  <table class="table-bordered table-striped" id="results">
			<thead>
			      <tr> 
				    <td>Name</td>
				    <td>Address</td>
				    <td>Booking Date</td>
				    <td>Latest Charge Date</td>
			      </tr>
			</thead>
			<tbody>
			@foreach($results as $result)
			      <tr> 
				    <td><a href="{{ URL::action('SuspectController@getView', array('id'=>$result->id)) }}">{{ Privacylib::privName($result->name) }}</a></td>
				    <td>{{ $result->address }}</td>
				    <td>{{ $result->bookingdate }}</td>
				    <td>{{ $result->latestchargedate }}</td>
			      </tr>
			@endforeach
			</tbody>
		  </table>
		
	    </div>
	    </div>
      </div>
    </div>
      
@stop