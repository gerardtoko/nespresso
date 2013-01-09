<?php

namespace Nespresso\Test\Mapping\Project; 

use Nespresso\Mapping\Project\Repository;
use Nespresso\Mapping\Project\Repository\Task;
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
class RepositoryTest extends \PHPUnit_Framework_TestCase
{

    public function testMapping()
    {
	$project = new Repository();
	
	$project->setDeployTo("/var/nespresso");
	$this->assertEquals($project->getDeployTo(), "/var/nespresso");
	
	$project->setDomain("google.fr");
	$this->assertEquals($project->getDomain(), "google.fr");
	
	$project->setName("testing");
	$this->assertEquals($project->getName(), "testing");
	
	$project->setPort();
	$this->assertEquals($project->getPort(), 22);
	
	$project->setPort(234);
	$this->assertEquals($project->getPort(), 234);
	
	$project->setTasks(new Task());
	$this->assertEquals($project->getTasks(), new Task());
	
	$project->setUser("gerardtoko");
	$this->assertEquals($project->getUser(), "gerardtoko");
	
    }

}


