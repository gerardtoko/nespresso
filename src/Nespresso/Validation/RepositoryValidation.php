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
class RepositoryValidation implements ValidationInterface
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

	foreach ($projet->repositories as $keyRepository => $valueRepository) {

	    //check file
	    $schemaFile = $this->getRepositorySchemaValidation();
	    if (!file_exists($schemaFile)) {
		$basename = basename($schemaFile);
		throw new \Exception("schema $basename no exist in app directory");
	    }

	    //shema valid
	    $schemaJson = file_get_contents($schemaFile);
	    $objSchema = json_decode($schemaJson);
	    $validator->check($valueRepository, $objSchema);
	    if (!$validator->isValid()) {
		foreach ($validator->getErrors() as $error) {
		    if (isset($error["property"]) && isset($error["message"])) {
			$errors .= sprintf("%s: %s\n", $error["property"], $error["message"]);
		    }
		}
		throw new \Exception("JSON of $keyRepository does not validate. Violations:\n $errors");
	    }
	}
    }


    public function getRepositorySchemaValidation()
    {
	return __DIR__ . '/../../../app/repository-schema.json';
    }

}


