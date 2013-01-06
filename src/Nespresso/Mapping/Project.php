<?php

/*
 * This file is part of Composer.
 *
 * (c) Gerard TOKO <gerard.toko@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/*
  {
  "repositories":  {
  "testing":
  {
  "user": "www-data" ,
  "domain": "gerardtoko.com",
  "port": "22",
  "deployTo": "/home/www-data/testing/nespresso-test",
  "tasks": {
  "pre": [ "cd web && git add . && git checkouf -f", "rm -rf cache/*","chmod -R 777 cache" ],
  "post": ["cd web","rm -rf cache/*","chmod -R 777 cache"]
  }
  }
  },
  "git": "git@github.com:gerardtoko/nespresso-test.git",
  "keepRelease": "3",
  "cache" : "var/cache",
  "groups":  {
  "testing_cluster": ["testing"]
  }
  }
 */

namespace Nespresso\Mapping;

/**
 * Description of Repository
 *
 * @author gerardtoko
 */
class Project
{

    protected $git;
    protected $keepRelease;
    protected $repositories;
    protected $cache;
    protected $cacheMode;
    protected $shared;
    protected $commonTasks;


    /**
     * 
     * @param array $repositories
     * @return \Nespresso\Mapping\Project
     */
    public function setRepositories(array $repositories)
    {
	$this->repositories = $repositories;
	return $this;
    }


    /**
     * 
     * @return type
     */
    public function getRepositories()
    {
	return $this->repositories;
    }


    /**
     * 
     * @param type $git
     * @return \Nespresso\Mapping\Project
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


    /**
     * 
     * @param type $keepRelease
     * @return \Nespresso\Mapping\Project
     */
    public function setKeepRelease($keepRelease)
    {
	$this->keepRelease = $keepRelease;
	return $this;
    }


    /**
     * 
     * @return type
     */
    public function getKeepRelease()
    {
	return $this->keepRelease;
    }


    /**
     * 
     * @return type
     */
    public function hasKeepRelease()
    {
	return !empty($this->keepRelease) ? TRUE : FALSE;
    }


    /**
     * 
     * @param type $cache
     * @return \Nespresso\Mapping\Project
     */
    public function setCache($cache)
    {
	$this->cache = $cache;
	return $this;
    }


    /**
     * 
     * @return type
     */
    public function getCache()
    {
	return $this->cache;
    }


    /**
     * 
     * @return type
     */
    public function hasCache()
    {
	return !empty($this->cache) ? TRUE : FALSE;
    }


    /**
     * 
     * @param type $cache
     * @return \Nespresso\Mapping\Project
     */
    public function setCacheMode($cache)
    {
	$this->cacheMode = $cache;
	return $this;
    }


    /**
     * 
     * @return type
     */
    public function getCacheMode()
    {
	return $this->cacheMode;
    }


    /**
     * 
     * @param type $shared
     * @return \Nespresso\Mapping\Project
     */
    public function setShared($shared)
    {
	$this->shared = $shared;
	return $this;
    }


    /**
     * 
     * @return type
     */
    public function isShared()
    {
	return !empty($this->shared) ? TRUE : FALSE;
    }


    /**
     * 
     * @return type
     */
    public function getShared()
    {
	return $this->shared;
    }


    /**
     * 
     * @param type $commonTask
     * @return \Nespresso\Mapping\Project
     */
    public function setCommonTasks($commonTask)
    {
	$this->commonTasks = $commonTask;
	return $this;
    }


    /**
     * 
     * @return type
     */
    public function getCommonTasks()
    {
	return $this->commonTasks;
    }


    /**
     * 
     * @return type
     */
    public function hasCommonTasks()
    {
	return !empty($this->commonTasks) ? TRUE : FALSE;
    }

}