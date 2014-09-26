<?php
 
class AltdataController extends BaseController {
    
    public function __construct() {
	$this->beforeFilter('csrf', array('on'=>'post'));
    }
    
    public function getIndex() {
	return Redirect::to('/dashboard');
    }
    
    public function getJson() {
	$bookees = Bookee::with('charge')->get();
	return Response::json($bookees);
    }
    
    public function getRss() {
	// creating rss feed with our most recent 30 suspects
	$suspects = Bookee::orderBy('bookingdate', 'desc')
		->take(30)
		->with('charge')
		->get();
	
	$firstpost = Bookee::orderBy('bookingdate', 'desc')
		->take(1)
		->first();
    
	$feed = Feed::make();
    
	// set feed's title, description, link, pubdate and language
	$feed->title = 'Danno\'s Book Live Charge Feed';
	$feed->description = 'Updated every other hour with arrests in Marin County.';
	$feed->logo = 'http://dannosbook.com/apple-touch-icon-144x144.png';
	$feed->link = URL::to('feed');
	$feed->pubdate = $firstpost->created_at;
	$feed->lang = 'en';
    
	foreach ($suspects as $suspect)
	{
	    foreach($suspect->charge as $singlecharge){
		$chargestring = $singlecharge->description . " (" . $singlecharge->charge . "; " . Privacylib::crimeType($singlecharge->type) . ") -- Bail at " . Privacylib::bailFormat($singlecharge->bail) . "; ";
	    }	    
	    
	    // set item's title, author, url, pubdate, description and content
	    $feed->add(Privacylib::privName($suspect->name),
		       "Marin Country Jail",
		       URL::action('SuspectController@getView',	array('id'=>$suspect->id)),
		       $suspect->created_at,
		       Privacylib::privName($suspect->name) . " (occupation: " . Privacylib::stdCase($suspect->occupation) . "), arrested at " . Privacylib::stdCase($suspect->arrestlocation),
		       $chargestring);
	}
    
	// show feed (options: 'atom' (recommended) or 'rss')
	return $feed->render('atom');
    
	// show feed with cache for 60 minutes
	return $feed->render('atom', 60);
    
	// to return feed as a string set second param to -1
	$xml = $feed->render('atom', -1);
	
	return $xml;
    }
}
?>
