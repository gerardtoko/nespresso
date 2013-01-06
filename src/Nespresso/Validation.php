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
use Nespresso\Validation\ConfigValidation;
use Nespresso\Validation\ReleaseValidation;

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
	$validationRepository = new RepositoryValidation();
	$validationConfig = new ConfigValidation();

	//errors
	$errors = "";

	//parse file syntax
	$parser->parse($json);

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

	$validator->check($objJson, $objSchema);
	if (!$validator->isValid()) {
	    foreach ($validator->getErrors() as $error) {
		if (isset($error["property"]) && isset($error["message"])) {
		    $errors .= sprintf("%s: %s\n", $error["property"], $error["message"]);
		}
	    }
	    throw new \Exception("JSON projet does not validate. Violations:\n $errors");
	}

	//validation repository
	$validationRepository->valid($objJson);
	//validation option
	$validationConfig->valid();
    }


    public function getProjectSchemaValidation()
    {
	return __DIR__ . '/../../app/project-schema.json';
    }


    /**
     * 
     * @param type $json
     */
    public function isValidRelease($release)
    {
	$releaseValidation = new ReleaseValidation();
	return $releaseValidation->valid($release);
    }

}