<?php

/**
 *
 * Map Forum Users. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2017, James Myers, myersware.com
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */
namespace myersware\mapusers\console\command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Map Forum Users console command.
 */
class mapusers extends \phpbb\console\command\command {
	/** @var \phpbb\user */
	protected $user;
	
	/**
	 * Constructor
	 *
	 * @param \phpbb\user $user
	 *        	User instance (mostly for translation)
	 */
	public function __construct(\phpbb\user $user) {
		parent::__construct ( $user );
		
		// Set up additional properties here
	}
	
	/**
	 * Configures the current command.
	 */
	protected function configure() {
		$this->user->add_lang_ext ( 'myersware/mapusers', 'cli' );
		$this->setName ( 'myersware:mapusers' )->setDescription ( $this->user->lang ( 'CLI_MAPUSERS' ) );
	}
	
	/**
	 * Executes the command myersware:mapusers.
	 *
	 * @param InputInterface $input
	 *        	An InputInterface instance
	 * @param OutputInterface $output
	 *        	An OutputInterface instance
	 */
	protected function execute(InputInterface $input, OutputInterface $output) {
		$output->writeln ( $this->user->lang ( 'CLI_MAPUSERS_HELLO' ) );
	}
}
