<?php
 
class DashboardController extends BaseController {
    
    public function __construct() {
	$this->beforeFilter('csrf', array('on'=>'post'));
    }
    
    public function getScraperstatus() {
	return View::make('web.dashboard.statusfragment');
    }
    
    public function getIndex() {
	
	/*
	 * Get extremes offenders
	 */
	
	$heaviest = Bookee::orderBy('weight', 'desc')
		->with('charge')
		->first();
	
	$lightest = Bookee::orderBy('weight', 'asc')
		->with('charge')
		->first();
		
	$tallest = Bookee::select(array('id',
					'name',
					DB::raw("SUBSTRING(height,LOCATE('\"', height)-2,2)+(12*SUBSTRING(height,LOCATE('\'', height)-1,1)) as ht")
					))
			->orderBy('ht', 'desc')
			->first();
			
	$shortest = Bookee::select(array('id',
					'name',
					DB::raw("SUBSTRING(height,LOCATE('\"', height)-2,2)+(12*SUBSTRING(height,LOCATE('\'', height)-1,1)) as ht")
					))
			->orderBy('ht', 'asc')
			->first();

	/*
	 * Get worst offenders
	 */
	$worstoffenders = Charge::select(array('bookee_id',
					       'bookees.name',
					DB::raw('count(IF(type = "F", 1, NULL)) as felonies, 
						count(IF(type = "M", 1, NULL)) as misdemeanors, 
						count(IF(type = "I", 1, NULL)) as infractions,
						count(IF(TYPE = "F", 1, NULL))*8 + count(IF(TYPE = "M", 2, NULL))*4 + count(IF(TYPE = "I", 1, NULL))*2 AS score')
					))
			->join('bookees', 'charges.bookee_id', '=', 'bookees.id')
			->groupBy('bookee_id')
			->orderBy('score', 'desc')
			->with('bookee')
			->take(18)
			->get();
			
	$topbails = Charge::select(array('bookee_id',
					 'bookees.name',
					DB::raw('sum(bail) as totbail')
					))
			->groupBy('bookee_id')
			->orderBy('totbail', 'desc')
			->join('bookees', 'charges.bookee_id', '=', 'bookees.id')
			->take(5)
			->get();

	
	/*
	 * Get recent Bookings
	 */ 
	$tenrecents = Bookee::orderBy('bookingdate', 'desc')
		->take(5)
		->with('charge')
		->get();
		
	$tenrecents = Bookee::orderBy('bookingdate', 'desc')
		->take(5)
		->with('charge')
		->get();
		
	$bookeeCount = Bookee::count();
	$chargeCount = Charge::count();
	
	return View::make('web.dashboard.dashboard', array(
	    'tenrecents' => $tenrecents,
	    'worstoffenders' => $worstoffenders,
	    
	    'heaviest' => $heaviest,
	    'lightest' => $lightest,
	    'tallest' => $tallest,
	    'shortest' => $shortest,
	    'topbails' => $topbails,
	    
	    'bookeeCount' => $bookeeCount,
	    'chargeCount' => $chargeCount,
	));
	
    }
}
?>
