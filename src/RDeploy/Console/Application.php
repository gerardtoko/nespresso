<?php

/*
 * This file is part of the rdeploy package.
 *
 * (c) Gerard TOKO <gerard.toko@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RDeploy\Console;

use Symfony\Component\Console\Application as BaseApplication;
use \RDeploy\Command\CkeckCommand;
use \RDeploy\Command\CleanupCommand;
use \RDeploy\Command\DeployCommand;
use \RDeploy\Command\DiffCommand;
use \RDeploy\Command\JsonCommand;
use \RDeploy\Command\ReleaseCommand;
use \RDeploy\Command\RevertCommand;
use \RDeploy\Command\RollbackCommand;

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
	$commands[] = new CkeckCommand();
	$commands[] = new DeployCommand();
	$commands[] = new DiffCommand();
	$commands[] = new JsonCommand();
	$commands[] = new ReleaseCommand();
	$commands[] = new RevertCommand();
	$commands[] = new RevertCommand();
	$commands[] = new RollbackCommand();
	$commands[] = new CleanupCommand();

	return $commands;
    }


    /**
     * 
     * @return type
     */
    public function run()
    {
	$application = new BaseApplication();
	$application->addCommands($this->getCommands());
	return $application->run();
    }

}