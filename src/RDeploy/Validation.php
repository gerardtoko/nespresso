<?php

/*
 * This file is part of Composer.
 *
 * (c) Gerard TOKO <gerard.toko@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RDeploy;

use Seld\JsonLint\JsonParser;
use RDeploy\Validation\EnvValidation;
use RDeploy\Validation\OptionValidation;
use RDeploy\Validation\NodeValidation;

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
    public function validJson($json)
    {

	$parser = new JsonParser();
	$validator = new \JsonSchema\Validator();
	$validation_env = new EnvValidation();
	$validation_node = new NodeValidation();
	$validation_option = new OptionValidation();

	//errors
	$errors = "";

	//parse file syntax
	$parser->parse($json);

	//check file
	$schema_file = $this->getSchemaValidation();
	if (!file_exists($schema_file)) {
	    $basename = basename($schema_file);
	    throw new \Exception("<error>shema $basename no exist in app directory");
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

	//validation nodes and env
	$validation_env->valid($obj_json);
	$validation_node->valid($obj_json);
	
	//validation option
	$validation_option->valid();
    }


    public function getSchemaValidation()
    {
	return __DIR__ . '/../../app/rdeploy-schema.json';
    }

}