<?php

namespace RDeploy\Script;

/*
 * This file is part of the rdeploy package.
 *
 * (c) Gerard TOKO <gerard.toko@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Description of Project
 *
 * @author gerardtoko
 */
class Project
{

    private $envs;
    private $nodes;
    private $excludes;


    /**
     * 
     * @return type
     */
    public function getNodes()
    {
	return $this->nodes;
    }


    /**
     * 
     * @param type $nodes
     */
    public function setNodes($nodes)
    {
	$this->nodes = $nodes;
    }


    /**
     * 
     * @return type
     */
    public function getEnvs()
    {
	return $this->envs;
    }


    /**
     * 
     * @param type $envs
     */
    public function setEnvs($envs)
    {
	$this->envs = $envs;
    }


    /**
     * 
     * @return type
     */
    public function getExcludes()
    {
	return $this->excludes;
    }


    /**
     * 
     * @param type $excludes
     */
    public function setExcludes($excludes)
    {
	$this->excludes = $excludes;
    }

}


