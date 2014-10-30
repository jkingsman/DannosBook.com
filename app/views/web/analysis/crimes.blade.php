@extends('web.components.boilerplate')

@section('title')
Crime Analysis     
@stop

@section('js')
$(function () {
    $('#topcrimes').highcharts({
        chart: {
            type: 'column'
        },
        title: {
            text: 'Top Crimes on Record'
        },

        tooltip: {
            pointFormat: '<b>{point.y:,.0f}</b> arrests'
        },
        xAxis: {
            categories: [
                 @foreach($topCrimes as $topCrime)
		       "{{ $topCrime->charge }} ({{ $topCrime->description }})",
		  @endforeach
            ]
        },
        series: [{
            name: 'Arrests',
            data: [@foreach($topCrimes as $topCrime){{ $topCrime->crimeCount }}, @endforeach]
        }]
    });
});

$(function () {
    $('#crimeoccupations').highcharts({
        chart: {
            type: 'column'
        },
        title: {
            text: 'Crimes Commited by Occupation'
        },

        tooltip: {
            pointFormat: '<b>{point.y:,.0f}</b> arrests'
        },
        xAxis: {
            categories: [
                 @foreach($crimeOccupations as $crimeOccupation)
		       "{{ Privacylib::stdCase($crimeOccupation->occupation) }}",
		  @endforeach
            ]
        },
        series: [{
            name: 'Arrests',
            data: [@foreach($crimeOccupations as $crimeOccupation){{ $crimeOccupation->count }}, @endforeach]
        }]
    });
});

@stop
@section('content')
@if($highcharts=1)@endif
<div class="container">
      <div class="row">
        <div class="col-md-12">
	<h1>Crime Analysis</h2>
        </div>
      </div>
      <div class="row">
	    <div class="col-md-12">
                <h3>Top Crimes</h3>
                    <div id="topcrimes"></div>
                <h3>Crimes Commited by Occupation</h3>
                    <div id="crimeoccupations"></div>
	    </div>
      </div>
    <div class="row">
        <div class="col-md-6">
        <h3>Arrest Locations in the Last 24 hrs</h3>
            Locations where suspects have been arrested in the last 24 hours.
            <table class="table table-striped">
                <thead>
                    <tr class="info"> 
                        <td>Arrests</td>
                        <td>Location</td>
                    </tr>
                </thead>
                <tbody>
                @foreach($location24Hr as $location)
                    <tr>
                        <td>{{ $location->count }}</td>
                        <td><a href="http://maps.google.com/maps?q={{ $location->arrestlocation }}" target="_blank">{{ Privacylib::stdCase($location->arrestlocation) }}</a></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="col-md-6">
            <h3>Top 10 Arrest Locations</h3>
            Top arrest locations for all suspects on record (locations containing "jail", "mcj", "remand", and "rccc" are excluded).
            <table class="table table-striped">
                <thead>
                    <tr class="info"> 
                        <td>Arrests</td>
                        <td>Location</td>
                    </tr>
                </thead>
                <tbody>
                @foreach($locationTop as $location)
                    <tr>
                        <td>{{ $location->count }}</td>
                        <td><a href="http://maps.google.com/maps?q={{ $location->arrestlocation }}" target="_blank">{{ Privacylib::stdCase($location->arrestlocation) }}</a></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
        <h3>Random Unique Crimes</h3>
            Crimes in this list have only been committed once since this application began scraping.
            <table class="table table-striped">
                <thead>
                    <tr class="info"> 
                        <td>Charge</td>
                        <td>Description</td>
                        <td></td>
                    </tr>
                </thead>
                <tbody>
                @foreach($randomOneOffs as $randomOneOff)
                    <tr>
                        <td>{{ $randomOneOff->charge }}</td>
                        <td>{{ $randomOneOff->description }}</td>
                        <td><a href="/suspect/view/{{ $randomOneOff->bookee_id }}">View Suspect</a></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="col-md-6">
            <h3>Top Suspect Hometowns</h3>
            <table class="table table-striped">
                <thead>
                    <tr class="info"> 
                        <td>Arrests</td>
                        <td>Hometown</td>
                    </tr>
                </thead>
                <tbody>
                @foreach($locationHome as $home)
                    <tr>
                        <td>{{ $home->count }}</td>
                        <td>{{ Privacylib::stdCase($home->address) }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
      </div>
</div>      
@stop
