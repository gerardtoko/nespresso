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
 * Description of Node
 *
 * @author gerardtoko
 */
class NodeValidation
{


    /**
     * 
     * @param type $projet
     * @throws \Exception
     */
    public function valid($projet)
    {

	foreach ($projet->nodes as $node) {

	    $nodes = explode(":", trim($node, ":"));
	    $envs = array_keys((array) $projet->envs);

	    if (!in_array($nodes[0], $envs)) {
		throw new \Exception("env $nodes[0] no exist");
	    }

	    if (!in_array($nodes[1], $envs)) {
		throw new \Exception("env $nodes[1] no exist");
	    }
	}
    }

}


