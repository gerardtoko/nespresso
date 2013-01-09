<?php

namespace Nespresso\Test\Mapping;

use Nespresso\Mapping\Project;
use Nespresso\Mapping\Project\Source;
use Nespresso\Mapping\Project\Common\Task;

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
class ProjectTest extends \PHPUnit_Framework_TestCase
{

    public function testMapping()
    {
	$project = new Project();
	
	$project->setCache("/app/cache");
	$this->assertEquals($project->getCache(), "/app/cache");	
	$this->assertEquals($project->hasCache(), TRUE);
	
	$project->setCacheMode();
	$this->assertEquals($project->getCacheMode(), 777);
	
	$project->setCacheMode(644);
	$this->assertEquals($project->getCacheMode(), 644);
		
	$this->assertFalse($project->hasCommonTasks());
	$project->setCommonTasks(new Task());
	$this->assertEquals($project->getCommonTasks(), new Task());
	$this->assertTrue($project->hasCommonTasks());
	
	$project->setKeepRelease();
	$this->assertEquals($project->getKeepRelease(), 5);
	
	$project->setKeepRelease(10);
	$this->assertEquals($project->getKeepRelease(), 10);	
	$this->assertEquals($project->hasKeepRelease(), TRUE);
	
	$project->setRepositories(array("foorepo"));
	$this->assertEquals($project->getRepositories(), array("foorepo"));
	
	$project->setSharedDirectory(array("fooshareddirectory"));
	$this->assertEquals($project->getSharedDirectory(), array("fooshareddirectory"));
	$this->assertEquals($project->isSharedDirectory(), TRUE);
	
	$project->setSharedFile(array("foosharedfile"));
	$this->assertEquals($project->getSharedFile(), array("foosharedfile"));
	$this->assertEquals($project->isSharedFile(), TRUE);
	
	$project->setSource(new Source());
	$this->assertEquals($project->getSource(), new Source());
	
    }

}


