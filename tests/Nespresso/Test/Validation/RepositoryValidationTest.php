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

use Nespresso\Validation\RepositoryValidation as Validation;

class RepositoryValidationTest extends \PHPUnit_Framework_TestCase
{


    public function testValid()
    {
	$validation = new Validation();
	
	$json = __DIR__ . '/../../../nespresso.min.json';
	$project_json = file_get_contents($json);
	$objJson = json_decode($project_json);
	$this->assertNull($validation->valid($objJson));

	$json = __DIR__ . '/../../../nespresso.person.json';
	$project_json = file_get_contents($json);
	$objJson = json_decode($project_json);
	$this->assertNull($validation->valid($objJson));

	$json = __DIR__ . '/../../../nespresso.full.json';
	$project_json = file_get_contents($json);
	$objJson = json_decode($project_json);
	$this->assertNull($validation->valid($objJson));
	
	$validation->setRepositorySchemaValidation(array("fooshareddirectory.json"));
	$this->assertEquals($validation->getRepositorySchemaValidation(), array("fooshareddirectory.json"));
    }

}