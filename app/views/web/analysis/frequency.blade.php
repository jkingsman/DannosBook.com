@extends('web.components.boilerplate')

@section('title')
Frequency Analysis     
@stop

@section('js')
$(function () {
    $('#hourlyavgcontainer').highcharts({
        data: {
            table: document.getElementById('hourlyavgdat')
        },
        chart: {
            type: 'column'
        },
        title: {
            text: ''
        },
        yAxis: {
            allowDecimals: false,
            title: {
                text: 'Arrests'
            }
        },
        tooltip: {
            formatter: function () {
                return '<b>' + this.series.name + '</b><br/>' +
                    this.point.y + ' ' + this.point.name.toLowerCase();
            }
        }
    });
});
$(function () {
    $('#weekdailycontainer').highcharts({
        data: {
            table: document.getElementById('weekdailydat')
        },
        chart: {
            type: 'column'
        },
        title: {
            text: ''
        },
        yAxis: {
            allowDecimals: false,
            title: {
                text: 'Arrests'
            }
        },
        tooltip: {
            formatter: function () {
                return '<b>' + this.series.name + '</b><br/>' +
                    this.point.y + ' ' + this.point.name.toLowerCase();
            }
        }
    });
});
$(function () {
    $('#dailycontainer').highcharts({
        data: {
            table: document.getElementById('dailydat')
        },
        chart: {
            type: 'areaspline'
        },
        title: {
            text: ''
        },
        yAxis: {
            allowDecimals: false,
            title: {
                text: 'Arrests'
            }
        },
        tooltip: {
            formatter: function () {
                return '<b>' + this.series.name + '</b><br/>' +
                    this.point.y + ' ' + this.point.name.toLowerCase();
            }
        }
    });
});
@stop

@section('content')
@if($highcharts=1)@endif
<div class="container">
      <div class="row">
        <div class="col-md-12">
	<h1>Frequency Analysis</h2>
        </div>
      </div>
      <div class="row">
	    <div class="col-md-12">
		  <h3>Breakdown by Weekday</h3>
			<div id="weekdailycontainer"></div>
		  <h3>Breakdown by Hour (Avg)</h3>
			<div id="hourlyavgcontainer"></div>
		  <h3>Daily Bookings (50 day running)</h3>
			This is going to display weird until the scraper has been running for 50 days.
			<div id="dailycontainer"></div>
		       
	    </div>
      </div>
</div>



<table id="hourlyavgdat" style="display: none;">
      <thead>
	      <tr>
		      <th></th>
		      <th>Charges per Hour (as in 24hr time)</th>
	      </tr>
      </thead>
      <tbody>
      	  @foreach($hourly as $hour)
	      <tr>
		      <td>{{ $hour->ahour }}</td>
		      <td>{{ $hour->count }}</td>
	      </tr>
	  @endforeach
      </tbody>
</table>
<table id="weekdailydat" style="display: none;">
      <thead>
	      <tr>
		      <th></th>
		      <th>Charges per Day</th>
	      </tr>
      </thead>
      <tbody>
	  @foreach($weekdaily as $weekday)
	      <tr>
		      <td>{{ $weekday->day }}</}}</>
		      <td>{{ $weekday->count }}</td>
	      </tr>
	  @endforeach
      </tbody>
</table>
<table id="dailydat" style="display: none;">
      <thead>
	      <tr>
		      <th></th>
		      <th>Charges per Day</th>
	      </tr>
      </thead>
      <tbody>
	  @foreach($daily as $day)
	      <tr>
		      <td>{{ $day->day }}</td>
		      <td>{{ $day->count }}</td>
	      </tr>
	  @endforeach
      </tbody>
</table>
@stop