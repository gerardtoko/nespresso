<?php

/*
 * This file is part of Composer.
 *
 * (c) Gerard TOKO <gerard.toko@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nespresso;

use Seld\JsonLint\JsonParser;
use Nespresso\Validation\RepositoryValidation;
use Nespresso\Validation\OptionValidation;

/**
 * Description of Validation
 *
 * @author gerardtoko
 */
class Validation
{


    /**
     * 
     * @param type $json
     */
    public function valid($json)
    {

	$parser = new JsonParser();
	$validator = new \JsonSchema\Validator();
	$validation_repository = new RepositoryValidation();
	$validation_option = new OptionValidation();

	//errors
	$errors = "";

	//parse file syntax
	$parser->parse($json);

	//check file
	$schema_file = $this->getProjectSchemaValidation();
	if (!file_exists($schema_file)) {
	    $basename = basename($schema_file);
	    throw new \Exception("schema $basename no exist in app directory");
	}

	//shema valid
	$schema_json = file_get_contents($schema_file);
	$obj_json = json_decode($json);
	$obj_schema = json_decode($schema_json);

	$validator->check($obj_json, $obj_schema);
	if (!$validator->isValid()) {
	    foreach ($validator->getErrors() as $error) {
		if (isset($error["property"]) && isset($error["message"])) {
		    $errors .= sprintf("%s: %s\n", $error["property"], $error["message"]);
		}
	    }
	    throw new \Exception("JSON projet does not validate. Violations:\n $errors");
	}

	//validation repository
	$validation_repository->valid($obj_json);
	//validation option
	$validation_option->valid();
    }


    public function getProjectSchemaValidation()
    {
	return __DIR__ . '/../../app/project-schema.json';
    }

}