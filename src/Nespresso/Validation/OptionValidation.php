<?php

/*
 * This file is part of Composer.
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
class OptionValidation implements ValidationInterface
{

    /**
     * 
     * @param type $projet
     * @throws \Exception
     */
    public function valid($option = null)
    {

	$validator = new \JsonSchema\Validator();
	//errors
	$errors = "";
	
	//check file
	$schema_file = $this->getOptionSchemaValidation();
	if (!file_exists($schema_file)) {
	    $basename = basename($schema_file);
	    throw new \Exception("schema $basename no exist in app directory");
	}

	$option_file = $this->getOption();
	if (!file_exists($option_file)) {
	    $basename = basename($option_file);
	    throw new \Exception("schema $basename no exist");
	}

	//shema valid
	$schema_json = file_get_contents($schema_file);
	$option_json = file_get_contents($option_file);

	$obj_schema = json_decode($schema_json);
	$obj_json = json_decode($option_json);

	$validator->check($obj_json, $obj_schema);
	if (!$validator->isValid()) {
	    foreach ($validator->getErrors() as $error) {
		if (isset($error["property"]) && isset($error["message"])) {
		    $errors .= sprintf("%s: %s\n", $error["property"], $error["message"]);
		}
	    }
	    throw new \Exception("JSON option does not validate. Violations:\n $errors");
	}
	
    }
    


    /**
     * 
     * @return type
     */
    public function getOptionSchemaValidation()
    {
	return __DIR__ . '/../../../app/option-schema.json';
    }


    /**
     * 
     * @return type
     */
    public function getOption()
    {
	return __DIR__ . '/../../../tests/option.json';
    }

}