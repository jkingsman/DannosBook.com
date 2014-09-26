@extends('web.components.boilerplate') 

@section('title')
Search
@stop

@section('content')
<div class="container">
      <div class="row">
	    <div class="col-md-12">
		<h1>Search Criteria</h1>
	    </div>
	    {{ Form::open(array('url'=>'/search/results')) }}
	    <div class="col-md-12">
		<h2>Suspect Details</h2>
		  <h3>Personal Details</h3>
		  <table style="margin-left:20px;" class="table table-bordered table-striped" id="suspectdata">
			<tbody>
			<tr>
			      <td colspan="5">
				    <h4>Basics</h4>
			      </td>
			</tr>
			<tr>
			      <td></td>
			      <td><strong>Name contains:</strong></td>
			      <td id="name">{{ Form::text('name') }}</td>
			      <td><strong>Gender contains (M/F):</strong></td>
			      <td id="gender">{{ Form::text('gender') }}</td>
			      
			</tr>
			<tr>
			      <td colspan="5">
				    <h4>Appearance</h4>
			      </td>
			</tr>
			<tr>
			      <td></td>
			      <td><strong>Hair Color contains:</strong></td>
			      <td id="haircolor">{{ Form::text('haircolor') }}</td>
			      <td><strong>Eye Color contains:</strong></td>
			      <td id="haircolor">{{ Form::text('eyecolor') }}</td>
			</tr>
			<tr>
			      <td></td>
			      <td><strong>Min Weight (lbs):</strong></td>
			      <td id="minweight">{{ Form::text('minweight', 0) }}</td>
			      <td><strong>Max Weight (lbs):</strong></td>
			      <td id="maxweight">{{ Form::text('maxweight', 2000) }}</td>
			</tr>
			<tr>
			      <td colspan="5">
				    <h4>Dates</h4>
			      </td>
			</tr>
			<tr>
			      <td></td>
			      <td><strong>Min DOB (YYYY-MM-DD):</strong></td>
			      <td id="minage">{{ Form::text('mindob', '1800-01-01') }}</td>
			      <td><strong>Max DOB (YYYY-MM-DD):</strong></td>
			      <td id="maxage">{{ Form::text('maxdob', date('Y-m-d', strtotime('+1 day'))) }}</td>
			</tr>
			<tr>	
			      <td colspan="5">
				    <h4>Other</h4>
			      </td>
			</tr>
			<tr>
			      <td></td>	
			      <td><strong>Home Address contains: </strong></td>
			      <td id="address">{{ Form::text('address') }}</td>
			      <td><strong>Occupation contains:</strong></td>
			      <td id="occupation">{{ Form::text('occupation') }}</td>
			</tr>
			</tbody>
		  </table>
		  <h3>Arrest Details</h4>
		  <table style="margin-left:20px;" class="table table-bordered table-striped" id="arrestdata">
			<tbody>
			      <tr>
				    <td><strong>Min Arrest Date (YYYY-MM-DD):</strong></td>
				    <td id="minarrestdate">{{ Form::text('minarrest', '1800-01-01') }}</td>
				    <td><strong>Max Arrest Date (YYYY-MM-DD):</strong></td>
				    <td id="maxarrestdate">{{ Form::text('maxarrest', date('Y-m-d', strtotime('+1 day'))) }}</td>
			      </tr>
			      <tr>
				    <td><strong>Min Latest Charge Date (YYYY-MM-DD):</strong></td>
				    <td id="minchargedate">{{ Form::text('minlatestcharge', '1800-01-01') }}</td>
				    <td><strong>Max Latest Charge Date (YYYY-MM-DD):</strong></td>
				    <td id="maxchargedate">{{ Form::text('maxlatestcharge', date('Y-m-d', strtotime('+1 day'))) }}</td>
			      </tr>
			      <tr>
				    <td><strong>Min Booking Date (YYYY-MM-DD):</strong></td>
				    <td id="minbookingdate">{{ Form::text('minbooking', '1800-01-01') }}</td>
				    <td><strong>Max Booking Date (YYYY-MM-DD):</strong></td>
				    <td id="maxbookingdate">{{ Form::text('maxbooking', date('Y-m-d', strtotime('+1 day'))) }}</td>
			      </tr>
			      <tr>
				    <td><strong>Arrest Agency contains:</strong></td>
				    <td id="arrestagency">{{ Form::text('arrestagency') }}</td>
				    <td><strong>Arrest Location contains:</strong></td>
				    <td id="arrestlocation">{{ Form::text('arrestloc') }}</td>
			      </tr>
			</tbody>
		  </table>
		  <hr />
	    </div>
            <div class="col-md-12">
		  <h2>Charge Details</h2>
		  <table style="margin-left:20px;" class="table table-bordered table-striped" id="arrestdata">
			<tbody>
			      <tr>
				    <td><strong>Charge ID (e.g. '664 484(A) PC') contains:</strong></td>
				    <td id="maxarrestdate">{{ Form::text('chargeid') }}</td>
				    <td><strong>Type (e.g. 'F', 'M', 'I', etc.) contains:</strong></td>
				    <td id="type">{{ Form::text('type') }}</td>
			      </tr>
			      <tr>
				    <td><strong>Description contains:</strong></td>
				    <td id="minarrestdate">{{ Form::text('description') }}</td>
				    <td><strong>Authority contains:</strong></td>
				    <td id="arrestagency">{{ Form::text('authority') }}</td>
			      </tr>
			      <tr>
				    <td><strong>Min Bail:</strong></td>
				    <td id="maxbookingdate">{{ Form::text('minbail', '0') }}</td>
				    <td><strong>Max Bail:</strong></td>
				    <td id="minbookingdate">{{ Form::text('maxbail', 100000000) }}</td>
			      </tr>
			</tbody>
		  </table>
		  <hr />	
	    </div>
	    
	    <div class="col-md-12">
	    All "XXXXX contains:" fields are run on the database as "WHERE column LIKE '%query%'", where query is the given string (therefore empty fields will return all).
	    All date & number fields are standard comparisons (therefore fields are autopopulated with appropriate max and min values to encompass all data.
		  <hr />
		  <div class="row">
			<div class="col-md-4">
			      {{ HTML::link(URL::action('SearchController@getIndex'), 'Reset Search', array('class'=>'btn btn-large btn-primary btn-block')) }}		      
			</div>
			<div class="col-md-4">
			      {{ Form::submit('Search', array('class'=>'btn btn-large btn-success btn-block', 'name'=>'submit'))}}
			</div>
			<div class="col-md-4">		      
			      {{ Form::submit('Print', array('class'=>'btn btn-large btn-default btn-block', 'name'=>'submit'))}}</div>
			</div>
		  </div>
		  <hr />
	    </div>
	    {{ Form::close() }}
      </div>
    </div>
      
@stop