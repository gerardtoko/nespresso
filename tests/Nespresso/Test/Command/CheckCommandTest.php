<?php

/*
 * This file is part of Nespresso.
 *
 * (c) Gerard TOKO <gerard.toko@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nespresso\Test\Command;

use Nespresso\Command\CheckCommand;
use Symfony\Component\Console\Tester\CommandTester;

class CheckCommandTest extends \PHPUnit_Framework_TestCase
{


    public function testCommand()
    {
	$command = new CheckCommand();
	$commandTester = new CommandTester($command);
	$commandTester->execute(array('project' => 'nespresso:testing'));
    }

}