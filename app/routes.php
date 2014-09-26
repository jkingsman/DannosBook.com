<?php

Route::get('/', function()
{
	return Redirect::to('/dashboard');
});

Route::controller('/users', 'UsersController');
Route::controller('/altdata', 'AltdataController');
	
Route::get('/cya/{name?}', function($name = "terms")
{
	return View::make('web.cya.' . $name);
});

Route::get('/lastloadtime', function()
{
	return Cache::get('lastloadtime');
});

Route::group(array('before' => 'auth'), function()
{
	Route::controller('/search', 'SearchController');
	Route::controller('/analysis', 'AnalysisController');
	Route::controller('/dashboard', 'DashboardController');
	Route::controller('/suspect', 'SuspectController');
	
	Route::get('/twiliotest/{phone?}', function($name = "+14153282987")
	{
		Twilio::message($name, 'Twilio is working.');
		return Redirect::to('/dashboard')->with('success', 'Test message sent');
	});
});

Route::group(array('before' => 'admin'), function() {
	Route::controller('/admin', 'AdminController');
});

