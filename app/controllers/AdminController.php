<?php
 
class AdminController extends BaseController {
    
    public function __construct() {
	$this->beforeFilter('csrf', array('on'=>'post'));
    }
    
    public function getIndex() {
	return Redirect::to('/admin/scraper');
    }
    
    public function getScraper() {
	return View::make('web.admin.scraper');
    }
    
    public function getLoadCommand($cmd=0) {
	set_time_limit(0);
	
	Queue::push(function($job)
	{
	    Artisan::call('danno:scrapeloadcommand');
	    $job->delete();
	});
	
	return Redirect::to('/admin/scraper')->with('success', 'Loader command triggered.');
    }
    
    public function getLoadCache($test=0) {
	set_time_limit(0);
	$booking = new BookingList;
	
	if($test){
	    Debugbar::addMessage('Filling with dev... ', 'Preprocessing');
	    $booking->loadFromFile(Config::get('scrape.devurl'));
	}else{
	    Debugbar::addMessage('Filling with production...', 'Preprocessing');
	    $booking->loadFromURL(Config::get('scrape.url'));
	}
	
	$namelist = $booking->saveToCache();
	$count = $booking->getNamesFromLinks();
	Cache::forever('cachecount', count($count));
	
	if($test){
	    return Redirect::to('/admin/scraper')->with('success', 'Dev booking list primed to cache.');	    
	}
	else{
	    return Redirect::to('/admin/scraper')->with('success', 'Booking list primed to cache.');
	}
    }
    
    public function getLoadDB($cmd=0) {
	set_time_limit(0);
	
	$booking = new BookingList;
	$booking->loadFromCache();
	
	$namelist = $booking->getNamesFromLinks();
	
	$iscompleted = 0;
	$processed = 0;
	while($iscompleted == 0){
	    $name = array_shift($namelist);
	    Log::info('Loading ' . $name . ' from cache to array (number ' . $processed . ').');	    
	    
	    $datarray = $booking->getDataByName($name);	    
	    
	    Log::info('Loading ' . $name . ' from from array to DB (number ' . $processed . ').');
	    App::make('DataEntryController')->enterData($datarray);
	    $processed++;
	    Log::info('Completed loading ' . $datarray['person']['name']);
	    
	    Cache::forever('loadedcount', (Cache::get('loadedcount', 0) + 1));
	    Cache::forever('cachecount', (Cache::get('cachecount', 0) - 1));
	    
	    if(Cache::get('cachecount', 0) < 1){
		$iscompleted = 1;
		Log::info('Load compelted or cancelled.');
	    }
	}
	
	Cache::forever('cachecount', 0);
	Cache::forever('loadedcount', 0);
	Cache::forever('loadedarray', '');

	$booking->forgetCache();
	
	Debugbar::addMessage('Complete; cache cleared.', 'Processing');
	Session::put('success', $processed . ' record(s) processed to array.');
	if(!$cmd){
	    return View::make('web.admin.scraper');
	}
    }
    
    public function getBookees() {
	$booking = new BookingList;
	$booking->loadFromCache();
	
	$existing = Bookee::orderBy('name', 'desc')
		    ->get()
		    ->lists('name', 'id');
	
	return View::make('web.admin.scraper', array(
	    'data' => var_export($existing, true)
	));
    }
    
    public function getForget($flushdb = 0) {
	$booking = new BookingList;
	
	$booking->forgetCache();
	Cache::forever('cachecount', 0);
	Cache::forever('loadedcount', 0);
	
	if($flushdb){
	    Bookee::truncate();
	    Charge::truncate();
	    Session::put('success', 'Everything (including DB) flushed.');
	    Debugbar::addMessage('Everything (including DB) flushed.', 'Maintenance');
	}else{
	    Session::put('success', 'Cache cleared.');
	    Debugbar::addMessage('Cache cleared.', 'Maintenance');
	}
	
	//return View::make('web.scraper.rawtest');
	return View::make('web.admin.scraper');
    }
    
    public function getReset() {
	Artisan::call('danno:resetcommand');
	Session::put('success', 'App reset cleared.');
	return View::make('web.admin.scraper');
    }
    
    public function getInvitations() {
	$claimed = Invitation::where('claimed', '=', '1')
		->get();
	
	$unclaimed = Invitation::where('claimed', '=', '0')
		->get();
	
	return View::make('web.admin.invitation', array(
	    'claimed' => $claimed,
	    'unclaimed' => $unclaimed,
	));
    }
    
    public function postInvitations() {
	$invitation = new Invitation;
	$invitation->code = Input::get('code');
	$invitation->note = Input::get('note');
	$invitation->save();
	
	return Redirect::to('/admin/invitations')->with('success', 'Invitation created: You can make an account at http://dannosbook.com/users/register. Your invite code is "' . $invitation->code . '" (with no quotes).');
    }
    
    public function getInvitationDel($id) {
	$invitation = Invitation::find($id);
	$invitation->delete();
	
	return Redirect::to('/admin/invitations')->with('success', 'Invitation deleted.');
    }
    
    public function getUsers() {
	$users = User::all();
	
	return View::make('web.admin.users', array(
	    'users' => $users,
	));
    }
    
    public function getUserdel($id) {
	$user = User::find($id);
	$user->delete();
	
	return Redirect::to('/admin/users')->with('success', 'User deleted.');
    }
    
}
?>
