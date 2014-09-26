@extends('web.components.boilerplate') 

@section('title')
Alert List
@stop

@section('content')
<div class="container">
      <div class="row">
	    <div class="col-md-12">
		<h1>Currently Active Alerts</h1>
		  
		  <hr />
	    </div>
	    <div class="col-md-12">
	    @foreach($alerts as $alert)
		  <h2>{{ $alert->alertname }} (to {{$alert->phonenumber }}) (<a href="{{ URL::action('SearchController@getDelalert', array('id'=>$alert->id)) }}">Cancel Alert</a>)</h2>
		  <table class="table table-bordered table-striped" id="suspectdata">
			<tbody>
			      <tr>
				    <td><strong>Name contains:</strong></td>
				    <td id="name">{{ $alert->name }}</td>
				    <td><strong>Gender contains (M/F):</strong></td>
				    <td id="gender">{{ $alert->gender }}</td>
				    
			      </tr>
			      <tr>
				    <td><strong>Hair Color contains:</strong></td>
				    <td id="haircolor">{{ $alert->haircolor }}</td>
				    <td><strong>Eye Color contains:</strong></td>
				    <td id="eyecolor">{{ $alert->eyecolor }}</td>
			      </tr>
			      <tr>
				    <td><strong>Min Weight (lbs):</strong></td>
				    <td id="minweight">{{ $alert->minweight }}</td>
				    <td><strong>Max Weight (lbs):</strong></td>
				    <td id="maxweight">{{ $alert->maxweight }}</td>
			      </tr>
			      <tr>
				    <td><strong>Min DOB (YYYY-MM-DD):</strong></td>
				    <td id="minage">{{ $alert->mindob }}</td>
				    <td><strong>Max DOB (YYYY-MM-DD):</strong></td>
				    <td id="maxage">{{ $alert->maxdob }}</td>
			      </tr>
			      <tr>	
				    <td><strong>Home Address contains: </strong></td>
				    <td id="address">{{ $alert->address }}</td>
				    <td><strong>Occupation contains:</strong></td>
				    <td id="occupation">{{ $alert->occupation }}</td>
			      </tr>
			      <tr>
				    <td><strong>Arrest Agency contains:</strong></td>
				    <td id="arrestagency">{{ $alert->arrestagency }}</td>
				    <td><strong>Arrest Location contains:</strong></td>
				    <td id="arrestlocation">{{ $alert->arrestloc }}</td>
			      </tr>
			      <tr>
				    <td><strong>Charge ID (e.g. '664 484(A) PC') contains:</strong></td>
				    <td id="maxarrestdate">{{ $alert->chargeid }}</td>
				    <td><strong>Type (e.g. 'F', 'M', 'I', etc.) contains:</strong></td>
				    <td id="type">{{ $alert->type }}</td>
				    
				    
			      </tr>
			      <tr>
				    <td><strong>Description contains:</strong></td>
				    <td id="minarrestdate">{{ $alert->description }}</td>
				    <td><strong>Authority contains:</strong></td>
				    <td id="arrestagency">{{ $alert->authority }}</td>
				    
			      </tr>
			      <tr>
				    <td><strong>Min Bail:</strong></td>
				    <td id="maxbookingdate">{{ $alert->minbail }}</td>
				    <td><strong>Max Bail:</strong></td>
				    <td id="minbookingdate">{{ $alert->maxbail }}</td>
			      </tr>
			</tbody>
		  </table>
		  @endforeach
		  <hr />
	    </div>
      </div>
</div>
      
@stop