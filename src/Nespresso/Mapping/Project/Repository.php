<?php

/*
 * This file is part of Nespresso.
 *
 * (c) Gerard TOKO <gerard.toko@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nespresso\Mapping\Project;

/**
 * Description of Repository
 *
 * @author gerardtoko
 */
class Repository
{

    protected $user;
    protected $name;
    protected $domain;
    protected $deployTo;
    protected $port;
    protected $tasks;
    protected $connection;
   protected $symbolicLink;

    /**
     * 
     * @param $symbolicLink
     * @return \Nespresso\Mapping\Project
     */
    public function setSymbolicLink($symbolicLink = "current")
    {
	$this->symbolicLink = $symbolicLink;
	return $this;
    }


    /**
     * 
     * @return type
     */
    public function getSymbolicLink()
    {
	return $this->symbolicLink;
    }


    /**
     * 
     * @param type $name
     * @return \Nespresso\Mapping\Project\Repository
     */
    public function setName($name)
    {
	$this->name = $name;
	return $this;
    }


    /**
     * 
     * @return type
     */
    public function getName()
    {
	return $this->name;
    }


    /**
     * 
     * @param type $user
     * @return \Nespresso\Mapping\Project\Repository
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
     * @return \Nespresso\Mapping\Project\Repository
     */
    public function setDomain($domain)
    {
	$this->domain = trim($domain, ":");
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
     * @return \Nespresso\Mapping\Project\Repository
     */
    public function setDeployTo($deployTo)
    {
	$this->deployTo = rtrim($deployTo, "/");
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
     * @return \Nespresso\Mapping\Project\Repository
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
     * @return type
     */
    public function hasTasks()
    {
	return !empty($this->tasks) ? TRUE : FALSE;
    }


    /**
     * 
     * @param type $port
     * @return \Nespresso\Mapping\Project\Repository
     */
    public function setPort($port = 22)
    {
	$this->port = (int) $port;
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


    /**
     * 
     * @param type $connection
     * @return \Nespresso\Mapping\Project\Repository
     */
    public function setConnection($connection)
    {
	$this->connection = $connection;
	return $this;
    }


    /**
     * 
     * @return type
     */
    public function getConnection()
    {
	return $this->connection;
    }


    /**
     * 
     * @return type
     */
    public function hasConnection()
    {
	return !empty($this->connection) ? TRUE : FALSE;
    }

}