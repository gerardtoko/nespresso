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

/**
 * Description of Projet
 *
 * @author gerardtoko
 */
class RepositoryValidation
{


    /**
     * 
     * @param type $projet
     * @throws \Exception
     */
    public function valid($projet)
    {

	$validator = new \JsonSchema\Validator();
	//errors
	$errors = "";

	foreach ($projet->repositories as $key_repository => $value_repository) {

	    //check file
	    $schema_file = $this->getRepositorySchemaValidation();
	    if (!file_exists($schema_file)) {
		$basename = basename($schema_file);
		throw new \Exception("schema $basename no exist in app directory");
	    }

	    //shema valid
	    $schema_json = file_get_contents($schema_file);
	    $obj_schema = json_decode($schema_json);
	    $validator->check($value_repository, $obj_schema);
	    if (!$validator->isValid()) {
		foreach ($validator->getErrors() as $error) {
		    if (isset($error["property"]) && isset($error["message"])) {
			$errors .= sprintf("%s: %s\n", $error["property"], $error["message"]);
		    }
		}
		throw new \Exception("JSON of $key_repository does not validate. Violations:\n $errors");
	    }
	}
    }


    public function getRepositorySchemaValidation()
    {
	return __DIR__ . '/../../../app/repository-schema.json';
    }

}


