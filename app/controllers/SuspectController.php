<?php
 
class SuspectController extends BaseController {
    
    public function __construct() {
	$this->beforeFilter('csrf', array('on'=>'post'));
    }
    
    public function getIndex() {
	return View::make('web.dashboard.dashboard');
	
    }

    public function getView($id=1) {
	$suspect = Bookee::find($id);
	$charges = Charge::where('bookee_id', '=', $id)->get();
	
	if(!$suspect || !$charges->count()){
	    return Redirect::to('/dashboard')->with('failure', 'No suspect with that ID.');
	}
	
	
	$birthday = new DateTime($suspect->birthdate);
	$interval = $birthday->diff(new DateTime);
	$yearsold = $interval->y;
	
	return View::make('web.suspect.view', array(
	    'suspect' => $suspect,
	    'charges' => $charges,
	    'yearsold' => $yearsold,
	));
    }
    
    public function getDeletecharge($id) {
	if(!Auth::user()->admin){
	    return Redirect::to('/suspect/view/' . $id)->with('failure', 'Must be administrator to delete charges.');
	}
	$charge = Charge::find($id);

	if(!$charge){
	    return Redirect::to('/dashboard')->with('failure', 'No charge with that ID.');
	}
	else{
	    $suspect=$charge->bookee_id;
	    $charge->delete();
	    return Redirect::to('/suspect/view/' . $suspect)->with('success', 'Charge deleted.');
	}
    }
    
    public function getDeletesuspect($id) {
	if(!Auth::user()->admin){
	   return Redirect::to('/suspect/view/' . $id)->with('failure', 'Must be administrator to delete suspects.');
	}
	
	$suspect = Bookee::find($id);
	
	if(!$suspect){
	    return Redirect::to('/dashboard')->with('failure', 'No suspect with that ID.');
	}
	else{
	    $suspect->delete();
	    $charges = Charge::where('bookee_id', '=', $id)->delete();
	
	    return Redirect::to('/dashboard')->with('success', 'Suspect deleted.');
	}
    }
}
?>
