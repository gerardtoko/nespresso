<?php

namespace Nespresso\Test\Validation;

use Nespresso\Validation\ConfigValidation as Validation;
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
class ConfigValidationTest extends \PHPUnit_Framework_TestCase
{

    public function testValid()
    {
	$validation = new Validation();

	$json = __DIR__ . '/../../../config.min.json';
	$this->assertNull($validation->valid($json));

	$json = __DIR__ . '/../../../config.person.json';
	$this->assertNull($validation->valid($json));

	$json = __DIR__ . '/../../../config.full.json';
	$this->assertNull($validation->valid($json));
    }

}


