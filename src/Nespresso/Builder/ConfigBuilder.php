<?php

/*
 * This file is part of the rdeploy package.
 *
 * (c) Gerard TOKO <gerard.toko@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nespresso\Builder;

use Nespresso\Mapping\Config as ConfigMapping;
use Nespresso\Builder\BuilderInterface;

/**
 * Description of Deploy
 *
 * @author gerardtoko
 */
class ConfigBuilder implements BuilderInterface
{

    protected $optionFile;
    protected $repository;
    protected $group;


    /**
     * 
     * @return \Nespresso\Mapping\Config
     * @throws \Exception
     */
    public function build()
    {
	$optionFile = $this->getConfig();
	if (!file_exists($optionFile)) {
	    $basename = basename($optionFile);
	    throw new \Exception("schema $basename no exist");
	}

	$configJson = json_decode(file_get_contents($optionFile));
	$optionObject = new ConfigMapping();

	if (!empty($configJson->key)) {
	    $optionObject->setKey($configJson->key);
	}else{
	    throw new \Exception("key in config.json is undefined or incorrect");
	}
	
	$tmp = !empty($this->option->tmp) ? rtrim($this->option->port, "/") : "/tmp";
	$optionObject->setTmp($tmp);

	return $optionObject;
    }


    /**
     * 
     * @return type
     */
    private function getConfig()
    {
	return __DIR__ . '/../../../tests/config.json';
    }

}