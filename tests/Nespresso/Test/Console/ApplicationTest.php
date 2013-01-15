<?php

namespace Nespresso\Test\Console;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\ApplicationTester;
use Symfony\Component\Console\Input\ArrayInput;
use Nespresso\Command\CheckCommand;
use Nespresso\Command\CleanupCommand;
use Nespresso\Command\DeployCommand;
use Nespresso\Command\DiffCommand;
use Nespresso\Command\JsonCommand;
use Nespresso\Command\UpdateCommand;
use Nespresso\Command\RollbackCommand;
use Nespresso\Command\SetupCommand;

/*
 * This file is part of Nespresso.
 *
 * (c) Gerard TOKO <gerard.toko@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Description of ConfigValidationTest
 *
 * @author gerardtoko
 */
class ApplicationTest extends \PHPUnit_Framework_TestCase
{


    public function testBuild()
    {
	$application = new Application();
	$application->addCommands($this->getCommands());
	$tester = new ApplicationTester($application);

	$arguments = array(
	    'command' => 'deploy',
	    'project' => 'nespresso:testing'
	);

	//$tester->run($arguments);
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
}


