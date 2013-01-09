<?php

/*
 * This file is part of Nespresso.
 *
 * (c) Gerard TOKO <gerard.toko@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nespresso\Test\Validation;

use Nespresso\Validation\ProjectValidation as Validation;

class ProjectValidationTest extends \PHPUnit_Framework_TestCase
{


    public function testValid()
    {
	$validation = new Validation();

	$json = __DIR__ . '/../../../nespresso.min.json';
	$project_json = file_get_contents($json);
	$this->assertTrue($validation->valid($project_json));

	$json = __DIR__ . '/../../../nespresso.person.json';
	$project_json = file_get_contents($json);
	$this->assertTrue($validation->valid($project_json));

	$json = __DIR__ . '/../../../nespresso.full.json';
	$project_json = file_get_contents($json);
	$this->assertTrue($validation->valid($project_json));

	try {
	    $json = __DIR__ . '/../../../nespresso.error.json';
	    $project_json = file_get_contents($json);
	    $this->assertTrue($validation->valid($project_json));
	} catch (\Exception $exc) {
	    
	}

	try {
	    $validation->setProjectSchemaValidation(array("fooshareddirectory.json"));
	    $json = __DIR__ . '/../../../nespresso.full.json';
	    $project_json = file_get_contents($json);
	    $this->assertTrue($validation->valid($project_json));
	} catch (\Exception $exc) {
	    
	}

	$validation->setProjectSchemaValidation(array("fooshareddirectory.json"));
	$this->assertEquals($validation->getProjectSchemaValidation(), array("fooshareddirectory.json"));
    }

}