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

    protected $configFile;
    protected $repository;
    protected $group;


    /**
     * 
     * @return \Nespresso\Mapping\Config
     * @throws \Exception
     */
    public function build()
    {
	$configFile = $this->getConfig();
	if (!file_exists($configFile)) {
	    $basename = basename($configFile);
	    throw new \Exception("schema $basename no exist");
	}

	$configJson = json_decode(file_get_contents($configFile));
	$configObject = new ConfigMapping();

	if (!empty($configJson->key)) {
	    $configObject->setKey($configJson->key);
	} else {
	    throw new \Exception("key in config.json is undefined or incorrect");
	}

	$optionRsync = !empty($configJson->option_rsync) ? trim($configJson->option_rsync, "-") : "azv";
	$configObject->setOptionRsync($optionRsync);

	$tmp = !empty($configJson->tmp) ? rtrim($configJson->tmp, "/") : "/tmp";
	$configObject->setTmp($tmp);

	return $configObject;
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