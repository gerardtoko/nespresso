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

use Nespresso\Script\Option as OptionObject;

/**
 * Description of Deploy
 *
 * @author gerardtoko
 */
class OptionBuilder
{

    protected $optionFile;
    protected $repository;
    protected $group;



   /**
    * 
    * @return \Nespresso\Script\Option
    * @throws \Exception
    */
    public function build()
    {
	$optionFile = $this->getOption();
	if (!file_exists($optionFile)) {
	    $basename = basename($optionFile);
	    throw new \Exception("schema $basename no exist");
	}

	$option_json = json_decode(file_get_contents($optionFile));		
	$optionObject = new OptionObject;
	$optionObject->setUser($option_json->user);
	$optionObject->setKey($option_json->key);
	
	$tmp = !empty($this->option->tmp) ? rtrim($this->option->port, "/") :"/tmp";
	$optionObject->setTmp($tmp);
	
	$port = !empty($this->option->port) ? trim($this->option->port) : "22";
	$optionObject->setPort($port);

	return $optionObject;
    }


    /**
     * 
     * @return type
     */
    private function getOption()
    {
	return __DIR__ . '/../../../tests/option.json';
    }

}