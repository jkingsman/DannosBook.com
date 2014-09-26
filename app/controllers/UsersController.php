<?php
 
class UsersController extends BaseController {
    
    public function __construct() {
	$this->beforeFilter('csrf', array('on'=>'post'));
    }
    
    public function getIndex() {
	return Redirect::to('/users/login/');
    }
    	
    public function getRegister() {
	return View::make('web.users.register');
    }
    
    public function postRegister() {	
        $validator = Validator::make(Input::all(), User::$rules);

	$invitation = Invitation::where('claimed', '=', '0')
			->where('code', '=', Input::get('invitation'))
			->first();
	
	if(!$invitation){
	    return Redirect::to('/users/register')->with('failure', 'Invitation code invalid or already claimed.');
	}	
 
	if ($validator->passes()) {
	    $user = new User;
	    $user->username = Input::get('username');
	    $user->email = Input::get('email');
	    $user->password = Hash::make(Input::get('password'));
	    $user->pb64 = "";
	    $user->save();
	    
	    Auth::login($user);
	    
	    $invitation->claimed = 1;
	    $invitation->claimedemail = Input::get('email');
	    $invitation->save();
	    
	    return Redirect::to('/dashboard')->with('success', 'Thanks for registering!');
	} else {
	    return Redirect::to('/users/register')->withErrors($validator)->withInput();
	}

    }
    
    public function getLogin() {
	if (Auth::check()){
		return Redirect::to('/dashboard');
	}else{
		return View::make('web.users.login')->with('message', 'Welcome!');
	}
    }
    
    public function postLogin() {
             if (Auth::attempt(array('username'=>Input::get('username'), 'password'=>Input::get('password')), true)) {		
		$user = User::find(Auth::user()->id);
		$user->touch();
		$user->save();
		
		return Redirect::intended('dashboard')->with('success', 'You are now logged in.');
	    } else {
		return Redirect::to('users/login')
		    ->with('failure', 'Your username/password combination was incorrect')
		    ->withInput();
	    }
    }
    
    
    
    public function getLogout() {
	Session::flush();
	Auth::logout();
	return Redirect::to('/users/login')->with('success', 'You are now logged out!');
    }
    
}
?>
