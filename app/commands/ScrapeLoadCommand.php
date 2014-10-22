<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ScrapeLoadCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'danno:scrapeloadcommand';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Run the screen scraping script and load the data into the database.';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		if(Cache::get('automated', false)){
			//it's already running; cancel it. we're going to start again.
			//Note that this requires your cron (or however you intervalize this command)
			//to give the scrap command enough time to run before you call it again.
			Artisan::call('danno:resetcommand');
		}

		/*
		 * START TIMER
		 */ 
		list($usec, $sec) = explode(" ", microtime());
		$time_start = ((float)$usec + (float)$sec);
		
		/*
		 * START COMMAND
		 */ 
		$this->line('Beginning...');
		Cache::forever('status', 'Command started.');
		Cache::forever('automated', true);
		
		/*
		 * LOAD CACHE
		 */ 
		$startdate = date_create();
		Cache::forever('status', 'Loading raw cache at ' . date_format($startdate, 'Y-m-d H:i:s') . '...');
		$this->line('Loading raw cache at ' . date_format($startdate, 'Y-m-d H:i:s'));
		
		App::make('AdminController')->getLoadCache();
		$this->line('Done.');
		
		/*
		 * LOAD DB
		 */ 
		$date = date_create();
		Cache::forever('status', 'Loading database at ' . date_format($date, 'Y-m-d H:i:s') . '...');
		$this->line('Loading database at ' . date_format($date, 'Y-m-d H:i:s'));
		App::make('AdminController')->getLoadDB($cmd=1);
		$this->line('Done.');
		
		/*
		 * FIRING ALERT COMMAND
		 */
		Queue::push(function($job)
			{
			    Artisan::call('danno:alertsms');
			    $job->delete();
			});
		
		
		list($usec, $sec) = explode(" ", microtime());
		$time_end = ((float)$usec + (float)$sec);
		$time = round(($time_end - $time_start)/60);

		Cache::forever('automated', false);
		Cache::forever('lastloadtime', time());
		$date = date_create();
		$this->line('Completed full load at <span id="completetime">' . date_format($date, 'Y-m-d H:i:s') . '</span> (started at ' . date_format($startdate, 'Y-m-d H:i:s') . ')');
		Cache::forever('status', 'Completed full load in ' . $time . ' minutes at ' . date_format($date, 'Y-m-d H:i:s'));
	}
}
