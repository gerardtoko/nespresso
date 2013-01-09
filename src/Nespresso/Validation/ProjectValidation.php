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

use Nespresso\Validation\RepositoryValidation;
use Nespresso\Validation\ValidationInterface;

/**
 * Description of Projet
 *
 * @author gerardtoko
 */
class ProjectValidation implements ValidationInterface
{

    protected $schema;


    public function __construct()
    {
	$this->schema = __DIR__ . '/../../../schema/project-schema.json';
    }


    /**
     * 
     * @param type $projet
     * @throws \Exception
     */
    public function valid($json)
    {

	$jsonSchemaValidator = new \JsonSchema\Validator();
	$repositoryValidation = new RepositoryValidation();

	//errors
	$errors = "";

	//check file
	$schemaFile = $this->getProjectSchemaValidation();
	if (!file_exists($schemaFile)) {
	    $basename = basename($schemaFile);
	    throw new \Exception("schema $basename no exist in app directory");
	}


	//shema valid
	$schemaJson = file_get_contents($schemaFile);
	$objJson = json_decode($json);
	$objSchema = json_decode($schemaJson);

	$jsonSchemaValidator->check($objJson, $objSchema);
	if (!$jsonSchemaValidator->isValid()) {
	    foreach ($jsonSchemaValidator->getErrors() as $error) {
		if (isset($error["property"]) && isset($error["message"])) {
		    $errors .= sprintf("%s: %s\n", $error["property"], $error["message"]);
		}
	    }
	    throw new \Exception("JSON projet does not validate. Violations:\n $errors");
	}

	//validation repository
	$repositoryValidation->valid($objJson);
	return true;
    }


    /**
     * 
     * @param type $schema
     * @return \Nespresso\Validation\ProjectValidation
     */
    public function setProjectSchemaValidation($schema)
    {
	$this->schema = $schema;
	return $this;
    }


    /**
     * 
     * @return type
     */
    public function getProjectSchemaValidation()
    {
	return $this->schema;
    }

}


