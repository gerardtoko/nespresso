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

/**
 * Description of Manager
 *
 * @author gerardtoko
 */
class Manager implements Manager\ManagerInterface
{

    protected $project;
    private $config;
    private $git;


    /**
     * 
     * @param type $project
     * @return \Nespresso\Manager
     */
    public function setProject($project)
    {
	$this->project = $project;
	return $this;
    }


    /**
     * 
     * @return type
     */
    public function getProject()
    {
	return $this->project;
    }


    /**
     * 
     * @param type $config
     * @return \Nespresso\Manager
     */
    public function setConfig($config)
    {
	$this->config = $config;
	return $this;
    }


    /**
     * 
     * @return type
     */
    public function getConfig()
    {
	return $this->config;
    }


    /**
     * 
     * @param type $git
     * @return \Nespresso\Manager
     */
    public function setGit($git)
    {
	$this->git = $git;
	return $this;
    }


    /**
     * 
     * @return type
     */
    public function getGit()
    {
	return $this->git;
    }

}