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

use Nespresso\Validation\ReleaseValidation as Validation;

class ReleaseValidationTest extends \PHPUnit_Framework_TestCase
{


    public function testValid()
    {
	$validation = new Validation();
	$this->assertEquals($validation->valid("12-23-23-23-34-34"), 1);
	$this->assertEquals($validation->valid("1223-23-23-34-34"), 0);
	$this->assertEquals($validation->valid("vendor/directory"), 0);
	$this->assertEquals($validation->valid("12-23-23-23-34-34"), 1);
	$this->assertEquals($validation->valid("2-23-23-23-34-34"), 0);
	$this->assertEquals($validation->valid("sd-Qd-qd-sd-sd-sd"), 0);
	$this->assertEquals($validation->valid(""), 0);
	$this->assertEquals($validation->valid("."), 0);
	$this->assertEquals($validation->valid(".."), 0);
	$this->assertEquals($validation->valid("72-23-23-23-34-34"), 1);
	$this->assertEquals($validation->valid("72-23-23-23-D4-3Z"), 0);
    }

}