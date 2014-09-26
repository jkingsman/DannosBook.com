<?php

class Bookee extends Eloquent {

	protected $fillable = array('jail_id', 'name', 'bookingdate', 'address', 'latestchargedate', 'gender', 'arrestdate',
				    'birthdate', 'arrestagency', 'occupation', 'arrestlocation', 'height', 'haircolor', 'weight',
				    'eyecolor', 'courtlink');
	
	public function charge()
	{
	    return $this->hasMany('Charge', 'bookee_id');
	}
}