<?php

/*
 * This file is part of Nespresso.
 *
 * (c) Gerard TOKO <gerard.toko@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nespresso\Test;

use Nespresso\Validation;

class ValidationTest extends \PHPUnit_Framework_TestCase
{


    public function testValid()
    {
	$validation = new Validation();
	
	$json = __DIR__ . '/../../nespresso.min.json';
	$project_json = file_get_contents($json);
	$this->assertNull($validation->valid($project_json));

	$json = __DIR__ . '/../../nespresso.person.json';
	$project_json = file_get_contents($json);
	$this->assertNull($validation->valid($project_json));
	
	$json = __DIR__ . '/../../nespresso.full.json';
	$project_json = file_get_contents($json);
	$this->assertNull($validation->valid($project_json));

    }

}