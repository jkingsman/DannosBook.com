<div class="collapse navbar-collapse">
  <ul class="nav navbar-nav">
    @if(Auth::check())
  <li class="{{ strpos(Route::getCurrentRoute()->getPath(), 'dashboard') !== FALSE ? 'active' : 'nonactive' }}">
      {{ HTML::link(URL::action('DashboardController@getIndex'), 'Dashboard') }}
    </li>
    <li class="{{strpos(Route::getCurrentRoute()->getPath(), 'search') !== FALSE ? 'active' : 'nonactive' }} dropdown">
      <a href="#" class="dropdown-toggle" data-toggle="dropdown">Search <span class="caret"></span></a>
      <ul class="dropdown-menu">
	<li>
	  {{ HTML::link(URL::action('SearchController@getIndex'), 'Run Search') }}
	</li>
	   <li class="divider"></li>
	<li>
	  {{ HTML::link(URL::action('SearchController@getAlert'), 'Create Alert') }}
	</li>
	<li>
	  {{ HTML::link(URL::action('SearchController@getAlertlist'), 'List Alerts') }}
	</li>
	   <li class="divider"></li>
	<li>
	  {{ HTML::link(URL::action('SearchController@getRecent'), 'Recent Arrests') }}
	</li>
      </ul>
    </li>
    <li class="{{strpos(Route::getCurrentRoute()->getPath(), 'analysis') !== FALSE ? 'active' : 'nonactive' }} dropdown">
      <a href="#" class="dropdown-toggle" data-toggle="dropdown">Analysis <span class="caret"></span></a>
      <ul class="dropdown-menu">
	<li>
	  {{ HTML::link(URL::action('AnalysisController@getCounts'), 'Count Analysis') }}
	</li>
	<li>
	  {{ HTML::link(URL::action('AnalysisController@getFrequency'), 'Frequency Analysis') }}
	</li>
      </ul>
    </li>      
    </li>
	@if(Auth::user()->admin)
	<li class="{{strpos(Route::getCurrentRoute()->getPath(), 'admin') !== FALSE ? 'active' : 'nonactive' }} dropdown">
	<a href="#" class="dropdown-toggle" data-toggle="dropdown">Admin <span class="caret"></span></a>
	  <ul class="dropdown-menu">
	    <li>
	      {{ HTML::link(URL::action('AdminController@getScraper'), 'Scraper') }}
	    </li>
	      <li>
	      {{ HTML::link(URL::action('AdminController@getInvitations'), 'Invitations') }}
	    </li>
	    <li>
	      {{ HTML::link(URL::action('AdminController@getUsers'), 'Users') }}
	    </li>
	  </ul>
	    
	</li>
	@endif
  </ul>
  <ul class="nav navbar-nav navbar-right">
<li><a href="/users/logout" class="button button-xs button-warning"><i class="glyphicon glyphicon-remove-circle"></i> Logout {{Auth::user()->username}}</a></li>
        @else
    <li class="{{strpos(Route::getCurrentRoute()->getPath(), 'login') !== FALSE ? 'active' : 'nonactive' }} dropdown">
      {{ HTML::link(URL::action('UsersController@getLogin'), 'Login') }}
    </li>
    <li class="{{strpos(Route::getCurrentRoute()->getPath(), 'register') !== FALSE ? 'active' : 'nonactive' }} dropdown">
      {{ HTML::link(URL::action('UsersController@getRegister'), 'Register') }}
    </li>
	@endif
    </ul>

  </div>


<!--/.nav-collapse -->