<?php

class Invitation extends Eloquent {

	protected $fillable = array('code', 'note', 'claimed', 'claimedemail');

}