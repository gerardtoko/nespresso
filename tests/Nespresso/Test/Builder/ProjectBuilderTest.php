<?php

namespace Nespresso\Test\Builder;

use Nespresso\Builder\ProjectBuilder as Builder;

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
class ProjectBuilderTest extends \PHPUnit_Framework_TestCase
{


    public function testBuild()
    {

	$file = __DIR__ . '/../../../nespresso.min.json';
	$project_json = file_get_contents($file);
	$objJson = json_decode($project_json);
	$builder = new Builder($objJson, "testing", null);
	$object = $builder->build();
	
	$this->assertEquals($object->getKeepRelease(), 5);
	

	$file = __DIR__ . '/../../../nespresso.person.json';
	$project_json = file_get_contents($file);
	$objJson = json_decode($project_json);
	$builder = new Builder($objJson, "testing", null);
	$object = $builder->build();

	$this->assertEquals($object->getCache(), "app/cache");
	$this->assertEquals($object->getCacheMode(), 777);
	$this->assertEquals($object->getKeepRelease(), 5);
	

	$file = __DIR__ . '/../../../nespresso.full.json';
	$project_json = file_get_contents($file);
	$objJson = json_decode($project_json);
	$builder = new Builder($objJson, "testing", null);
	$object = $builder->build();

	$this->assertEquals($object->getCache(), "app/cache");
	$this->assertEquals($object->getCacheMode(), 777);
	$this->assertEquals($object->getKeepRelease(), 3);	
	
	try {
	    $file = __DIR__ . '/../../../nespresso.full.json';
	    $project_json = file_get_contents($file);
	    $objJson = json_decode($project_json);
	    $builder = new Builder($objJson, "testings", "testing", true);
	    $builder->build();
	} catch (\Exception $exc) {
	    
	}

	$file = __DIR__ . '/../../../nespresso.full.json';
	$project_json = file_get_contents($file);
	$objJson = json_decode($project_json);
	$builder = new Builder($objJson, "testing_cluster", true);
	$builder->build();


	try {
	    $file = __DIR__ . '/../../../nespresso.error.json';
	    $project_json = file_get_contents($file);
	    $objJson = json_decode($project_json);
	    $builder = new Builder($objJson, "testings", "testing", true);
	    $builder->build();
	} catch (\Exception $exc) {
	    
	}
    }

}


