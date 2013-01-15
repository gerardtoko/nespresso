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

use Nespresso\Source;
use Nespresso\Git;
use Nespresso\Mercurial;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Nespresso\Builder\ConfigBuilder;
use Nespresso\Builder\ProjectBuilder;

class SourceTest extends \PHPUnit_Framework_TestCase
{

    protected $container;


    public function testSource()
    {

	$json = __DIR__ . '/../../nespresso.json';
	$project_json = json_decode(file_get_contents($json));

	$container = $this->getContainer();
	$builderOption = new ConfigBuilder();
	$optionObject = $builderOption->build();

	$builderProject = new ProjectBuilder($project_json, "testing", false);
	$projectObject = $builderProject->build();

	//manager service
	$manager = $this->getContainer()->get("nespresso.manager");
	$manager->setProject($projectObject);
	$manager->setConfig($optionObject);
	
	$source = new Source($container, new Git());
	$manager->setSource($source);
	
	//$this->assertNull($source->cloneScm());
//	$this->assertTrue($source->hasBranch("master"));
//	$this->assertFalse($source->hasBranch("v2.2"));
//	$this->assertTrue($source->hasTag("v2.1"));
//	$this->assertFalse($source->hasTag("v2"));
//	$this->assertTrue($source->hasCommit("2e1a84"));
//	$this->assertTrue($source->checkoutBranch("master"));
//	$this->assertTrue($source->checkoutTag("v2.1"));
//	$this->assertTrue($source->checkoutCommit("2e1a84"));

//	$source = new Source($container, new Mercurial());
//	$this->assertNull($source->cloneScm());
//	$this->assertTrue($source->hasBranch("master"));
//	$this->assertTrue($source->hasTag("v2.1"));
//	$this->assertFalse($source->hasTag("v2"));
//	$this->assertTrue($source->hasCommit("2e1a84"));
//	$this->assertTrue($source->checkoutBranch("master"));
//	$this->assertTrue($source->checkoutTag("v2.1"));
//	$this->assertTrue($source->checkoutCommit("2e1a84"));
    }


    /**
     * Singleton Pattern
     * @return type
     */
    public function getContainer()
    {
	if (is_null($this->container)) {
	    $container = new ContainerBuilder();
	    $loader = new YamlFileLoader($container, new FileLocator(__DIR__));
	    $loader->load(__DIR__ . '/../../../app/services.yml');
	    $this->container = $container;
	}

	return $this->container;
    }

}