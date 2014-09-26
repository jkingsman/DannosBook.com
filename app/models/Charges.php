<?php

class Charge extends Eloquent {
	protected $fillable = array('bookee_id', 'charge', 'description', 'type', 'bail', 'sentencetime', 'auth');
	

	public function bookee()
	{
	    return $this->belongsTo('Bookee', 'id');
	}
}