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
	$project = new Task();
	
	$project->setPre(array(new Command(), new Command(), new Command()));
	$this->assertEquals($project->getPre(), array(new Command(), new Command(), new Command()));
	
	$project->setPost(array(new Command(), new Command(), new Command()));
	$this->assertEquals($project->getPost(), array(new Command(), new Command(), new Command()));
	
	
    }

}


