<?php

/*
 * This file is part of Nespresso.
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


    public function __construct()
    {
	$this->configFile = __DIR__ . '/../../../tests/config.json';
    }


    /**
     * 
     * @return \Nespresso\Mapping\Config
     * @throws \Exception
     */
    public function build()
    {
	$configFile = $this->getConfigFile();
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

	$optionRsyncDeploy = !empty($configJson->option_rsync_deploy) ? trim($configJson->option_rsync_deploy, "-") : "az";
	$configObject->setOptionRsyncDeploy($optionRsyncDeploy);
	
	$optionRsyncDiff = !empty($configJson->option_rsync_diff) ? trim($configJson->option_rsync_diff, "-") : "avhn --delete";
	$configObject->setOptionRsyncDiff($optionRsyncDiff);

	$tmp = !empty($configJson->tmp) ? rtrim($configJson->tmp, "/") : "/tmp";
	$configObject->setTmp($tmp);

	return $configObject;
    }


    /**
     * 
     * @param type $file
     * @return \Nespresso\Builder\ConfigBuilder
     */
    public function setConfigFile($file)
    {
	$this->configFile = $file;
	return $this;
    }


    /**
     * 
     * @return type
     */
    private function getConfigFile()
    {
	return $this->configFile;
    }

}