<?php
 
class AnalysisController extends BaseController {
    
    public function __construct() {
	$this->beforeFilter('csrf', array('on'=>'post'));
    }
    
    public function getIndex() {
	return "Not implemented.";
    }
    
    public function getCounts() {
	/*
	 * Get worst offenders
	 */
	$offenders = Charge::select(array('bookee_id',
					       'bookees.name',
					DB::raw('count(IF(type = "F", 1, NULL)) as felonies, 
						count(IF(type = "M", 1, NULL)) as misdemeanors, 
						count(IF(type = "I", 1, NULL)) as infractions,
						count(IF(TYPE = "F", 1, NULL))*8 + count(IF(TYPE = "M", 2, NULL))*4 + count(IF(TYPE = "I", 1, NULL))*2 AS score')
					))
			->join('bookees', 'charges.bookee_id', '=', 'bookees.id')
			->groupBy('bookee_id')
			->orderBy('score', 'desc')
			->get();
	
	return View::make('web.analysis.counts', array(
	    'offenders' => $offenders,
	));
	
    }
    
    public function getFrequency() {
	$hourly = DB::table('bookees')
			->select(DB::raw('count(*) as count, 
					HOUR(arrestdate) as ahour'
					))
			->groupBy(DB::raw('HOUR(arrestdate)'))
			->get();
			
	$dailyFM = DB::select("
		    SELECT m.day, m.m, f.f FROM
			(SELECT
			    count(*) as m, DAYNAME(sentencetime) as day
			FROM
			    `charges`
			WHERE
			    type = 'M'
			GROUP BY day) m
			
			JOIN(SELECT
			    count(*) as f, DAYNAME(sentencetime) as day
			FROM
			    `charges`
			WHERE
			    type = 'F'
			
			GROUP BY day) f
			
			on f.day = m.day
			ORDER BY FIELD(m.day, 'SUNDAY', 'MONDAY', 'TUESDAY', 'WEDNESDAY', 'THURSDAY', 'FRIDAY', 'SATURDAY')	 
		");	
			
	$daily = DB::table('bookees')
			->select(DB::raw('count(*) as count, 
					DATE_FORMAT(latestchargedate, "%m/%d") as day
					'))
			->whereRaw('DATE(latestchargedate) > DATE_SUB(NOW(), INTERVAL 60 day)')
			->groupBy(DB::raw('DATE(latestchargedate)'))
			->orderBy(DB::raw('DATE(latestchargedate)'), 'asc')
			->get();
	
	return View::make('web.analysis.frequency', array(
	    'hourly' => $hourly,
	    'dailyFM' => $dailyFM,
	    'daily' => $daily,
	));
	
    }
    
    public function getCrimes() {
	$topCrimes = DB::table('charges')
			->select(DB::raw('count(*) as crimeCount, 
					charge,
					description
					'))
			->groupBy('charge')
			->orderBy('crimeCount', 'desc')
			->take(10)
			->get();
			
			
	$crimeOccuptations = DB::table('bookees')
			->select(DB::raw('count(*) as count,
					 occupation
					'))
			->where('occupation', '!=', '')
			->groupBy('occupation')
			->orderBy('count', 'desc')
			->take(10)
			->get();
			
	$location24Hr = DB::table('bookees')
			->select(DB::raw('count(*) as count,
					 arrestlocation
					'))
			->whereRaw('bookingdate >= now() - INTERVAL 1 DAY')
			->groupBy('bookingdate')
			->orderBy('count', 'desc')
			->get();
	
	$locationTop = DB::table('bookees')
			->select(DB::raw('count(*) as count,
					 arrestlocation
					'))
			->whereRaw('arrestlocation NOT LIKE "%jail%"')
			->whereRaw('arrestlocation NOT LIKE "%mcj%"')
			->whereRaw('arrestlocation NOT LIKE "%rccc%"')
			->whereRaw('arrestlocation NOT LIKE "%remand%"')
			->whereRaw('arrestlocation != ""')
			->groupBy('arrestlocation')
			->orderBy('count', 'desc')
			->take(10)
			->get();

	$randomOneOffs = DB::table('charges')
			->select(DB::raw('count(*) as count,
					 charge,
					 description,
					 bookee_id
					'))
			->groupBy('charge')
			->orderBy(DB::raw('count ASC, RAND()'))
			->take(10)
			->get();
			
	$locationHome = DB::table('bookees')
			->select(DB::raw('count(*) as count,
					 address
					'))
			->groupBy('address')
			->orderBy('count', 'desc')
			->take(10)
			->get();
	
	return View::make('web.analysis.crimes', array(
	    'topCrimes' => $topCrimes,
	    'crimeOccupations' => $crimeOccuptations,
	    'location24Hr' => $location24Hr,
	    'locationTop' => $locationTop, 
	    'randomOneOffs' => $randomOneOffs,
	    'locationHome' => $locationHome,
	));
	
    }
}
?>
