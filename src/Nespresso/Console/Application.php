<?php

/*
 * This file is part of Nespresso.
 *
 * (c) Gerard TOKO <gerard.toko@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nespresso\Console;

use Symfony\Component\Console\Application as BaseApplication;
use Nespresso\Command\CheckCommand;
use Nespresso\Command\CleanupCommand;
use Nespresso\Command\DeployCommand;
use Nespresso\Command\DiffCommand;
use Nespresso\Command\JsonCommand;
use Nespresso\Command\UpdateCommand;
use Nespresso\Command\RollbackCommand;
use Nespresso\Command\SetupCommand;

/**
 * Description of Application
 *
 * @author gerardtoko
 */
class Application
{

    public function __construct()
    {
	if (function_exists('ini_set')) {
	    ini_set('xdebug.show_exception_trace', false);
	    ini_set('xdebug.scream', false);
	}
	if (function_exists('date_default_timezone_set') && function_exists('date_default_timezone_get')) {
	    date_default_timezone_set(@date_default_timezone_get());
	}
    }


    /**
     * Initializes all the rdeploy commands
     */
    protected function getCommands()
    {
	$commands[] = new DeployCommand();
	$commands[] = new RollbackCommand();
	$commands[] = new UpdateCommand();
	$commands[] = new DiffCommand();
	$commands[] = new CleanupCommand();
	$commands[] = new CheckCommand();
	$commands[] = new JsonCommand();
	$commands[] = new SetupCommand();

	return $commands;
    }


    /**
     * 
     * @return type
     */
    public function run()
    {
	$application = new BaseApplication();
	$commands = $this->getCommands();
	$application->addCommands($commands);
	return $application->run();
    }

}