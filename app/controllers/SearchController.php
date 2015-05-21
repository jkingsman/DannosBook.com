<?php
 
class SearchController extends BaseController {
    
    public function __construct() {
		$this->beforeFilter('csrf', array('on'=>'post'));
    }
    
    public function getIndex() {
		return View::make('web.search.search');
    }
    
    public function getRecent($count = 50) {
		$recents = Bookee::orderBy('bookingdate', 'desc')
			->take($count)
			->with('charge')
			->get();
		return View::make('web.search.recent', array(
			'recents' => $recents,
		));
    }
    
    public function getAlert() {
		return View::make('web.search.addalert');
    }
    
    public function getAlertlist() {
		$alerts = Alert::where('owner', '=', Auth::user()->id)
			->get();
		
		return View::make('web.search.listalert', array(
			'alerts' => $alerts,
			));
    }
    
    public function getDelalert($id) {
		$alert = Alert::find($id);
		
		if($alert->owner == Auth::user()->id){
			$alert->delete();
			return Redirect::to('/search/alertlist')->with('success', 'Alert deleted.');
		}
		else{
			return Redirect::to('/search/alertlist')->with('failure', 'Not permitted.');
		}
    }
    
    public function postAlert() {
	$rules = array(
	    'owner' => 'required|numeric|size:' . Auth::user()->id,
	    'alertname' => 'required|max:64',
	    'phonenumber' => 'required',
	    'name' => 'max:64',
	    'gender' => 'max:16',
	    'haircolor' => 'max:16',
	    'eyecolor' => 'max:16',
	    'minweight' => 'integer|max:9999',
	    'maxweight' => 'integer|max:9999',
	    'mindob' => 'date_format:Y-m-d',
	    'maxdod' => 'date_format:Y-m-d',
	    'address' => 'max:64',
	    'occupation' => 'max:64',
	    'chargeid' => 'max:16',
	    'type' => 'max:16',
	    'description' => 'max:128',
	    'authority' => 'max:128',
	    'minbail' => 'integer|max:900000000',
	    'maxbail' => 'integer|max:900000000',
	    'arrestagency' => 'max:128',
	    'arrestlocation' => 'max:128',
	);
	
	$validator = Validator::make(Input::all(), $rules);
	
	if ($validator->passes()) {
	    Alert::create(Input::all());
	    
	    return Redirect::to('/search/alertlist')->with('success', 'Alert "' . Input::get('alertname') . '" created.');
	} else {
	    return Redirect::to('/search/alert	')->withErrors($validator)->withInput();
	}
	if ($v->passes())
	{
	    Alert::create(Input::all());
	}
	
	
	return Redirect::to('/search/alertlist')->with('success', 'Your alert has been created.');
    }
    
    public function getSearchalerts($id) {
	$alert = Alert::find($id);
	
	$chargeresults = Charge::where('charge', 'like', '%' . $alert->chargeid . '%')
	    ->where('type', 'like', '%' . $alert->type . '%')
	    
	    ->where('description', 'like', '%' . $alert->description . '%')
	    ->where('auth', 'like', '%' . $alert->authority . '%')
	    
	    ->where('bail', '>=', $alert->minbail)
	    ->where('bail', '<=', $alert->maxbail)

	    ->get(array('bookee_id'));   
	    
	$oksuspectids = array();	
	foreach($chargeresults as $charge){
	    $oksuspectids[] = $charge->bookee_id;
	}
	
	$suspectresults = Bookee::whereIn('id', $oksuspectids)
	    ->where('name', 'like', '%' . $alert->name . '%')
	    ->where('gender', 'like', '%' . $alert->gender . '%')
	    
	    ->where('haircolor', 'like', '%' . $alert->haircolor . '%')
	    ->where('eyecolor', 'like', '%' . $alert->eyecolor . '%')
	    
	    ->where('weight', '>=', $alert->minweight)
	    ->where('weight', '<=', $alert->maxweight)
	    
	    ->where('birthdate', '>=', $alert->mindob)
	    ->where('birthdate', '<=', $alert->maxdob)
	    
	    ->where('latestchargedate', '>=', $alert->updated_at)
	    
	    ->where('occupation', 'like', '%' . $alert->occupation . '%')

	    ->where('arrestagency', 'like', '%' . $alert->arrestagency . '%')
	    ->where('arrestlocation', 'like', '%' . $alert->arrestlocation . '%')
	    
	    ->orderBy('name', 'asc')
	    ->get(array('id'));
	
	if($suspectresults->first()){
	    foreach ($suspectresults->toArray() as $key => $idgroup){ 
		$new_key = array_keys($idgroup); 
		$flattened[] = $idgroup[$new_key[0]]; 
	    }
	    
	    $alert->touch();
	    $alert->save();
	    return $flattened;
	}
	else{
	    return array();
	}
	
    }
    
    public function postResults() {
	$chargeresults = Charge::where('charge', 'like', '%' . Input::get('chargeid') . '%')
	    ->where('type', 'like', '%' . Input::get('type') . '%')
	    
	    ->where('description', 'like', '%' . Input::get('description') . '%')
	    ->where('auth', 'like', '%' . Input::get('authority') . '%')
	    
	    ->where('bail', '>=', Input::get('minbail'))
	    ->where('bail', '<=', Input::get('maxbail'))

	    ->get(array('bookee_id'));   
	    
	$oksuspectids = array();	
	foreach($chargeresults as $charge){
	    $oksuspectids[] = $charge->bookee_id;
	}
	
	$suspectresults = Bookee::whereIn('id', $oksuspectids)
	    ->where('name', 'like', '%' . Input::get('name') . '%')
	    ->where('gender', 'like', '%' . Input::get('gender') . '%')
	    
	    ->where('haircolor', 'like', '%' . Input::get('haircolor') . '%')
	    ->where('eyecolor', 'like', '%' . Input::get('eyecolor') . '%')
	    
	    ->where('weight', '>=', Input::get('minweight'))
	    ->where('weight', '<=', Input::get('maxweight'))
	    
	    ->where('birthdate', '>=', Input::get('mindob'))
	    ->where('birthdate', '<=', Input::get('maxdob'))
	    
	    ->where('occupation', 'like', '%' . Input::get('occupation') . '%')
	    
	    ->where('arrestdate', '>=', Input::get('minarrest'))
	    ->where('arrestdate', '<=', Input::get('maxarrest'))
	    
	    ->where('gender', 'like', '%' . Input::get('gender') . '%')
	    
	    ->where('bookingdate', '>=', Input::get('minbooking'))
	    ->where('bookingdate', '<=', Input::get('maxbooking'))
	    
	    ->where('latestchargedate', '>=', Input::get('minlatestcharge'))
	    ->where('latestchargedate', '<=', Input::get('maxlatestcharge'))
	    
	    ->where('arrestagency', 'like', '%' . Input::get('arrestagency') . '%')
	    ->where('arrestlocation', 'like', '%' . Input::get('arrestloc') . '%')
	    
	    ->orderBy('name', 'asc')
	    ->get();

	Input::flash();
	
	if(Input::get('submit')=="Print"){
	    return View::make('web.search.printresults', array(
		'results' => $suspectresults,
		'count' => count($suspectresults),
	    ));
	}
	else{
	    return View::make('web.search.results', array(
		'results' => $suspectresults,
		'count' => count($suspectresults),
	    ));
	}
	
    }
}
?>
