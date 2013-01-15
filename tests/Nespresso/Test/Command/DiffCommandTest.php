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

use Nespresso\Command\DiffCommand;
use Symfony\Component\Console\Tester\CommandTester;

class DiffCommandTest extends \PHPUnit_Framework_TestCase
{


    public function testCommand()
    {
	$command = new DiffCommand();
	$commandTester = new CommandTester($command);
	$commandTester->execute(array('project' => 'nespresso:testing'), array('branch' => "v2"));
	$commandTester->execute(array('project' => 'nespresso:testing'), array('tag' => "v2.1"));
	$commandTester->execute(array('project' => 'nespresso:testing'), array('commit' => "fcb9c2170b9d54c47cde76aa84f12f7940f4268c"));
    }

}