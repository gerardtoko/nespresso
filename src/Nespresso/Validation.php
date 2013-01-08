<?php

/*
 * This file is part of Nespresso.
 *
 * (c) Gerard TOKO <gerard.toko@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nespresso;

use Seld\JsonLint\JsonParser;
use Nespresso\Validation\ConfigValidation;
use Nespresso\Validation\ReleaseValidation;
use Nespresso\Validation\ProjectValidation;

/**
 * Description of Validation
 *
 * @author gerardtoko
 */
class Validation
{


    /**
     * Validation project and configuration
     * @param type $json
     */
    public function valid($json)
    {

	$parser = new JsonParser();
	$projectValidation = new ProjectValidation();
	$configValidation = new ConfigValidation();

	//parse file syntax
	$parser->parse($json);

	$projectValidation->valid($json);
	$configValidation->valid();
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