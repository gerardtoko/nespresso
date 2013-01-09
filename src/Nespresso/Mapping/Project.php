<?php

/*
 * This file is part of Nespresso.
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
  "user": "gerardtoko" ,
  "domain": "gerardtoko.com",
  "port": "3041",
  "deploy_to": "/home/gerardtoko/testing/nespresso-test",
  "tasks": {
  "pre": [ "cd web && git add . && git checkouf -f", "rm -rf cache/*","chmod -R 777 cache" ],
  "post": ["cd web","rm -rf cache/*","chmod -R 777 cache"]
  }
  }
  },
  "source" : {
  "type" : "git",
  "scm": "git@github.com:gerardtoko/nespresso-test.git"
  },
  "keep_release": "3",
  "shared_directory": [ "var","web/uploads","app/logs","app/session", "app/lib/symfony/config/yaml/dumper"],
  "shared_file": [ "var","web/uploads","app/logs","app/session", "app/lib/symfony/config/yaml/dumper"],
  "cache" : "app/cache",
  "cache_mode" : "777",
  "common_tasks": {
  "pre": [ "cd web && git add . && git checkouf -f", "rm -rf cache/*","chmod -R 777 cache" ],
  "post": ["cd web","rm -rf cache/*","chmod -R 777 cache"]
  },
  "groups":  {
  "testing_cluster": ["testing"]
  }
  }
 */

namespace Nespresso\Mapping;

use Nespresso\Mapping\Project\Source;
use Nespresso\Mapping\Project\Common\Task;

/**
 * Description of Repository
 *
 * @author gerardtoko
 */
class Project
{

    protected $source;
    protected $keepRelease;
    protected $repositories;
    protected $cache;
    protected $cacheMode;
    protected $sharedDirectory;
    protected $sharedFile;
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
     * @param type $source
     * @return \Nespresso\Mapping\Project
     */
    public function setSource(Source $source)
    {
	$this->source = $source;
	return $this;
    }


    /**
     * 
     * @return type
     */
    public function getSource()
    {
	return $this->source;
    }


    /**
     * 
     * @param type $keepRelease
     * @return \Nespresso\Mapping\Project
     */
    public function setKeepRelease($keepRelease = 5)
    {
	$this->keepRelease = (int) $keepRelease;
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
    public function setCacheMode($cache = 777)
    {
	$this->cacheMode = (int) $cache;
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
     * @param type $sharedDirectory
     * @return \Nespresso\Mapping\Project
     */
    public function setSharedDirectory($sharedDirectory)
    {
	$this->sharedDirectory = $sharedDirectory;
	return $this;
    }


    /**
     * 
     * @return type
     */
    public function isSharedDirectory()
    {
	return !empty($this->sharedDirectory) ? TRUE : FALSE;
    }


    /**
     * 
     * @return type
     */
    public function getSharedDirectory()
    {
	return $this->sharedDirectory;
    }


    /**
     * 
     * @param type $sharedFile
     * @return \Nespresso\Mapping\Project
     */
    public function setSharedFile($sharedFile)
    {
	$this->sharedFile = $sharedFile;
	return $this;
    }


    /**
     * 
     * @return type
     */
    public function isSharedFile()
    {
	return !empty($this->sharedFile) ? TRUE : FALSE;
    }


    /**
     * 
     * @return type
     */
    public function getSharedFile()
    {
	return $this->sharedFile;
    }


    /**
     * 
     * @param type $commonTask
     * @return \Nespresso\Mapping\Project
     */
    public function setCommonTasks(Task $commonTask)
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