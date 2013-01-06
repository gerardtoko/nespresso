<?php

/*
 * This file is part of Composer.
 *
 * (c) Gerard TOKO <gerard.toko@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nespresso\Script\Project;

/**
 * Description of Repository
 *
 * @author gerardtoko
 */
class Repository
{

    protected $user;
    protected $domain;
    protected $deployTo;
    protected $port;
    protected $tasks;


    /**
     * 
     * @param type $user
     * @return \Nespresso\Project\Repository
     */
    public function setUser($user)
    {
	$this->user = $user;
	return $this;
    }


    /**
     * 
     * @return type
     */
    public function getUser()
    {
	return $this->user;
    }


    /**
     * 
     * @param type $domain
     * @return \Nespresso\Project\Repository
     */
    public function setDomain($domain)
    {
	$this->domain = $domain;
	return $this;
    }


    /**
     * 
     * @return type
     */
    public function getDomain()
    {
	return $this->domain;
    }


    /**
     * 
     * @param type $deployTo
     * @return \Nespresso\Project\Repository
     */
    public function setDeployTo($deployTo)
    {
	$this->deployTo = $deployTo;
	return $this;
    }


    /**
     * 
     * @return type
     */
    public function getDeployTo()
    {
	return $this->deployTo;
    }


    /**
     * 
     * @param type $tasks
     * @return \Nespresso\Project\Repository
     */
    public function setTasks($tasks)
    {
	$this->tasks = $tasks;
	return $this;
    }


    /**
     * 
     * @return type
     */
    public function getTasks()
    {
	return $this->tasks;
    }


    /**
     * 
     * @param type $port
     * @return \Nespresso\Project\Repository
     */
    public function setPort($port)
    {
	$this->port = $port;
	return $this;
    }


    /**
     * 
     * @return type
     */
    public function getPort()
    {
	return $this->port;
    }

}