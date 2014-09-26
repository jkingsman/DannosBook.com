<?php

class Alert extends Eloquent {
	protected $fillable = array('owner', 'alertname', 'phonenumber', 'name', 'gender', 'haircolor', 'eyecolor',  'minweight', 'maxweight', 'mindob', 'maxdob', 'address', 'occupation', 'chargeid', 'type', 'description', 'authority', 'minbail', 'maxbail', 'arrestlocation', 'arrestagency',);
}