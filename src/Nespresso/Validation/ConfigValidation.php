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

    /**
     * 
     * @param type $projet
     * @throws \Exception
     */
    public function valid($config = null)
    {

	$validator = new \JsonSchema\Validator();
	//errors
	$errors = "";
	
	//check file
	$schemaFile = $this->getOptionSchemaValidation();
	if (!file_exists($schemaFile)) {
	    $basename = basename($schemaFile);
	    throw new \Exception("schema $basename no exist in app directory");
	}

	$configFile = $this->getOption();
	if (!file_exists($configFile)) {
	    $basename = basename($configFile);
	    throw new \Exception("schema $basename no exist");
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
     * @return type
     */
    public function getOptionSchemaValidation()
    {
	return __DIR__ . '/../../../schema/config-schema.json';
    }


    /**
     * 
     * @return type
     */
    public function getOption()
    {
	return __DIR__ . '/../../../tests/config.json';
    }

}