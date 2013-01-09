<?php

namespace Nespresso\Test\Mapping\Project\Repository;

use Nespresso\Mapping\Project\Repository\Task;
use Nespresso\Mapping\Project\Repository\Task\Command;

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
class TaskTest extends \PHPUnit_Framework_TestCase
{


    public function testMapping()
    {
	$task = new Task();

	$task->setPre(array(new Command(), new Command(), new Command()));
	$this->assertEquals($task->getPre(), array(new Command(), new Command(), new Command()));

	$task->setPost(array(new Command(), new Command(), new Command()));
	$this->assertEquals($task->getPost(), array(new Command(), new Command(), new Command()));
	$this->assertTrue($task->hasPre());
	$this->assertTrue($task->hasPost());
    }

}


