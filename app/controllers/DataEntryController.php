<?php
 
class DataEntryController extends BaseController {
    
    public function enterData($datarray) {
	//Start by trying to retrieve the person, to see if we already have them
	$findExisting = Bookee::where('name' , '=', $datarray['person']['name'])
		->where('bookingdate' , '=', $datarray['person']['bookingdate'])
		->first();
		
	if($findExisting == NULL){
	    Log::info('Adding ' . $datarray['person']['name']);
	    $newBookee = Bookee::create(array(
			    'jail_id' => $datarray['person']['jail_id'],
			    'name' => $datarray['person']['name'],
			    'bookingdate' => $datarray['person']['bookingdate'],
			    'address' => $datarray['person']['address'],
			    'latestchargedate' => $datarray['person']['latestchargedate'],
			    'gender' => $datarray['person']['gender'],
			    'arrestdate' => $datarray['person']['arrestdate'],
			    'birthdate' => $datarray['person']['birthdate'],
			    'arrestagency' => $datarray['person']['arrestagency'],
			    'occupation' => $datarray['person']['occupation'],
			    'arrestlocation' => $datarray['person']['arrestlocation'],
			    'height' => $datarray['person']['height'],
			    'haircolor' => $datarray['person']['haircolor'],
			    'weight' => $datarray['person']['weight'],
			    'eyecolor' => $datarray['person']['eyecolor'],
			    'courtlink' => $datarray['person']['courtlink'],
			));
	    
	    /*
	     * it logically follows that if we don't have bookee record for them, we
	     * we won't have any arrest records either, so lets add those.
	     */
	    
	    Log::info('Adding charges for ' . $datarray['person']['name']);
	    
	    foreach($datarray['charges'] as $charge){
		Log::info('Adding charge ' .  $charge['desc']);
		$newCharge = Charge::create(array(
			    'bookee_id' => $newBookee->id,
			    'charge' => $charge['charge'],
			    'description' => $charge['desc'],
			    'type' => $charge['type'],
			    'bail' => $charge['bail'],
			    'auth' => $charge['auth'],
			    'sentencetime' => $datarray['person']['latestchargedate'],
			));
		Log::info('Added Charge ID ' . $newCharge->id . ' for ' . $datarray['person']['name']);
	    }
	    
	    Log::info('Done adding ' . $datarray['person']['name']);
	    
	    
	}else{
	    //So we've seen them before - let's compare arrestdates
	    Log::info($datarray['person']['name'] . ' exists.');
	    if($findExisting->latestchargedate == $datarray['person']['latestchargedate']){
		Log::info($datarray['person']['name'] . ' is up to date');
	    }
	    else{
		//we have new arrest records available
		Log::info($datarray['person']['name'] . ' has new arrest records available');
		
		Log::info('Adding new charges for ' . $datarray['person']['name']);
	    
		foreach($datarray['charges'] as $charge){
		    Log::info('Adding charge ' .  $charge['desc']);
		    $newCharge = Charge::create(array(
				'bookee_id' => $findExisting->id,
				'charge' => $charge['charge'],
				'description' => $charge['desc'],
				'type' => $charge['type'],
				'bail' => $charge['bail'],
				'auth' => $charge['auth'],
				'sentencetime' => $datarray['person']['latestchargedate'],
			    ));
		    Log::info('Added Charge ID ' . $newCharge->id . ' for ' . $datarray['person']['name']);
		}
		
		Log::info('Done adding new charges for ' . $datarray['person']['name']);
		$findExisting->latestchargedate = $datarray['person']['latestchargedate'];
		$findExisting->save();
	    }
	}
    }    
}
?>
