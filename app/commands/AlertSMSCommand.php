<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class AlertSMSCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'danno:alertsms';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Process SMS alerts.';

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
		$alerts = Alert::get();
		
		foreach($alerts as $alert){
			$ids = App::make('SearchController')->getSearchalerts($alert->id);
			
			foreach($ids as $id){
				Twilio::message('+1' . $alert->phonenumber, 'A suspect has just been booked matching your ' . $alert->alertname . ' alert: http://dannosbook.com/suspect/view/' . $id);
			}
			
			$alert->touch();
			$alert->save();
		}
	}
}
