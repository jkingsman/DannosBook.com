<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ResetCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'danno:resetcommand';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Reset the automated variable.';

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
		Cache::forever('automated', false);
		$date = date_create();
		Cache::forever('status', 'Reset at ' . date_format($date, 'Y-m-d H:i:s'));

	}
}
