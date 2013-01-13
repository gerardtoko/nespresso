<?php

namespace Nespresso\Test\Mapping;

use Nespresso\Mapping\Config;
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
class ConfigTest extends \PHPUnit_Framework_TestCase
{

    public function testMapping()
    {
	$config = new Config();
	
	$config->setKey("/home/gerardtoko/.ssh/id_key.pub");
	$this->assertEquals($config->getKey(), "/home/gerardtoko/.ssh/id_key.pub");
	
	$config->setOptionRsyncDeploy();
	$this->assertEquals($config->getOptionRsyncDeploy(), "az");
	
	$config->setOptionRsyncDeploy("azs");
	$this->assertEquals($config->getOptionRsyncDeploy(), "azs");
	
	$config->setTmp();
	$this->assertEquals($config->getTmp(), "/tmp");
	
	$config->setTmp("etc/var/code");
	$this->assertEquals($config->getTmp(), "etc/var/code");

    }

}


