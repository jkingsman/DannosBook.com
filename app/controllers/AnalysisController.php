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
			
	$weekdaily = DB::table('bookees')
			->select(DB::raw('count(*) as count, 
					DAYNAME(arrestdate) as day'
					))
			->groupBy(DB::raw('DAYNAME(arrestdate)'))
			->get();
			
	$daily = DB::table('bookees')
			->select(DB::raw('count(*) as count, 
					DATE_FORMAT(latestchargedate, "%m/%d") as day
					'))
			->whereRaw('DATE(latestchargedate) > DATE_SUB(NOW(), INTERVAL 30 day)')
			->groupBy(DB::raw('DATE(latestchargedate)'))
			->orderBy(DB::raw('DATE(latestchargedate)'), 'asc')
			->get();
	
	return View::make('web.analysis.frequency', array(
	    'hourly' => $hourly,
	    'weekdaily' => $weekdaily,
	    'daily' => $daily,
	));
	
    }
}
?>
