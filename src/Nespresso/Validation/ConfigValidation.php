<?php

/*
 * This file is part of Nespresso.
 *
 * (c) Gerard TOKO <gerard.toko@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nespresso\Validation;

use Nespresso\Validation\ValidationInterface;

/**
 * Description of Projet
 *
 * @author gerardtoko
 */
class ConfigValidation implements ValidationInterface
{

    protected $configFile;
    protected $schema;


    public function __construct()
    {
	$this->configFile = 'config.json';
	$this->schema = __DIR__ . '/../../../schema/config-schema.json';
    }


    /**
     * 
     * @param type $projet
     * @throws \Exception
     */
    public function valid($configFile = null)
    {

	$validator = new \JsonSchema\Validator();
	$errors = "";

	//check file
	$schemaFile = $this->getOptionSchemaValidation();

	if (is_null($configFile)) {
	    $configFile = $this->getConfigFile();
	}
	$this->isExistFile($schemaFile);
		
	if (!file_exists(realpath($configFile))) {
	    throw new \Exception("$configFile no exist");
	}

	
	//shema valid
	$schemaJson = file_get_contents($schemaFile);
	$configJson = file_get_contents($configFile);
	

	$objSchema = json_decode($schemaJson);
	$objJson = json_decode($configJson);

	$validator->check($objJson, $objSchema);
	if (!$validator->isValid()) {
	    foreach ($validator->getErrors() as $error) {
		if (isset($error["property"]) && isset($error["message"])) {
		    $errors .= sprintf("%s: %s\n", $error["property"], $error["message"]);
		}
	    }
	    throw new \Exception("JSON config does not validate. Violations:\n $errors");
	}
    }


    /**
     * 
     * @param type $file
     * @throws \Exception
     */
    public function isExistFile($file)
    {
	if (!file_exists($file)) {
	    throw new \Exception("$file no exist");
	}
    }


    /**
     * 
     * @return type
     */
    public function setOptionSchemaValidation($schema)
    {
	$this->schema = $schema;
	return $this;
    }


    /**
     * 
     * @return type
     */
    public function getOptionSchemaValidation()
    {
	return $this->schema;
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
    public function getConfigFile()
    {
	return $this->configFile;
    }

}