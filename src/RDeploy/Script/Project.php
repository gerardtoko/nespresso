<?php

/*
 * This file is part of the rdeploy package.
 *
 * (c) Gerard TOKO <gerard.toko@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RDeploy\Script;

/**
 * Description of Project
 *
 * @author gerardtoko
 */
class Project
{

    protected $data;
    protected $nodeCurrent;


    /**
     * 
     * @param type $data
     * @return \RDeploy\Script\Project
     */
    public function setData($data)
    {
	$this->data = $data;
	return $this;
    }


    /**
     * 
     * @return type
     */
    public function getData()
    {
	return $this->data;
    }


    /**
     * 
     * @param type $nodeCurrent
     * @return \RDeploy\Script\Project
     */
    public function setNodeCurrent($nodeCurrent)
    {
	$this->nodeCurrent = $nodeCurrent;
	return $this;
    }


    /**
     * 
     * @return type
     */
    public function getNodeCurrent()
    {
	return $this->nodeCurrent;
    }

}