@extends('web.components.boilerplate')

@section('title')
Frequency Analysis     
@stop

@section('js')
$(function () {
    $('#weekdailycontainer').highcharts({
        chart: {
            type: 'column'
        },
        title: {
            text: 'Average arrests by day'
        },

        tooltip: {
            pointFormat: '<b>{point.y:,.0f}</b> arrests during {point.x}00'
        },
        xAxis: {
            categories: [
                 @foreach($weekdaily as $weekday)
		       "{{ $weekday->day }}",
		  @endforeach
            ]
        },
        series: [{
            name: 'Arrests',
            data: [@foreach($weekdaily as $weekday){{ $weekday->count }}, @endforeach]
        }]
    });
});
$(function () {
    $('#hourlyavgcontainer').highcharts({
        chart: {
            type: 'column'
        },
        title: {
            text: 'Average arrests by hour'
        },

        tooltip: {
            pointFormat: '<b>{point.y:,.0f}</b> arrests during {point.x}00'
        },
        xAxis: {
            categories: [
                @foreach($hourly as $hour){{ $hour->ahour }}, @endforeach
            ]
        },
        series: [{
            name: 'Arrests',
            data: [@foreach($hourly as $hour){{ $hour->count }}, @endforeach]
        }]
    });
});
$(function () {
    $('#dailycontainer').highcharts({
        chart: {
            type: 'areaspline'
        },
        title: {
            text: 'Arrests per day'
        },
        legend: {
            layout: 'vertical',
            align: 'left',
            verticalAlign: 'top',
            x: 150,
            y: 100,
            floating: true,
            borderWidth: 1,
            backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'
        },
        xAxis: {
            categories: [@foreach($daily as $day)'{{ $day->day }}', @endforeach]
        },
        yAxis: {
            title: {
                text: 'Arrests'
            }
        },
        tooltip: {
            shared: true,
            valueSuffix: ' arrests'
        },
        credits: {
            enabled: false
        },
        plotOptions: {
            areaspline: {
                fillOpacity: 0.5
            }
        },
        series: [{
            name: 'Arrests',
            data: [@foreach($daily as $day){{ $day->count }}, @endforeach]
        }]
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
		  <h3>Daily Bookings (60 day running)</h3>
			Note that uncharacteristic dips can be caused by a stalled scrape job missing a day or two. Should be rectified as of 10/22/14 (see <a href="https://github.com/jkingsman/DannosBook.com/commit/e870f481f3b7d0fdb3b99ef6998c0161b2107761?diff=unified">GitHub</a> for patch that fixes stalled jobs).
			<div id="dailycontainer"></div>
		       
	    </div>
      </div>
</div>      
@stop
