<?php

/*
 * This file is part of Nespresso.
 *
 * (c) Gerard TOKO <gerard.toko@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

	
use Nespresso\Command\DeployCommand;
use Symfony\Component\Console\Tester\CommandTester;

class DeployCommandTest extends \PHPUnit_Framework_TestCase
{


    public function testNameIsOutput()
    {		
	$command = new DeployCommand();
	$commandTester = new CommandTester($command);
	$commandTester->execute(
		array('project' => 'nespresso:testing')
	);

	$commandTester->getDisplay();
    }

}