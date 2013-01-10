<?php

namespace Nespresso\Test\Builder;

use Nespresso\Builder\ConfigBuilder as Builder;

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
class ConfigBuilderTest extends \PHPUnit_Framework_TestCase
{


    public function testBuild()
    {
	$builder = new Builder();
	$builder->build();

	$file = __DIR__ . '/../../../config.min.json';
	$builder->setConfigFile($file);
	$object = $builder->build();
	$this->assertEquals($object->getKey(), "/Users/gerardtoko/.ssh/id_rsa");

	$file = __DIR__ . '/../../../config.person.json';
	$builder->setConfigFile($file);
	$builder->build();
	$this->assertEquals($object->getKey(), "/Users/gerardtoko/.ssh/id_rsa");
	$this->assertEquals($object->getTmp(), "/tmp");

	$file = __DIR__ . '/../../../config.full.json';
	$builder->setConfigFile($file);
	$builder->build();
	$this->assertEquals($object->getKey(), "/Users/gerardtoko/.ssh/id_rsa");
	$this->assertEquals($object->getTmp(), "/tmp");
	$this->assertEquals($object->getOptionRsync(), "az");

	try {
	    $file = __DIR__ . '/../../../config.error.json';
	    $builder->setConfigFile($file);
	    $builder->build();
	} catch (\Exception $exc) {
	    
	}

	try {
	    $file = __DIR__ . '/../../../config.full.jsons';
	    $builder->setConfigFile($file);
	    $builder->build();
	} catch (\Exception $exc) {
	    
	}
    }

}


