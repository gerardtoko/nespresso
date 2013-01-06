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
  "no_copy" : ["var/logs", "var/cache"],
  "groups":  {
  "testing_cluster": ["testing"]
  }
  }
 */

namespace Nespresso\Script;

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
    protected $noCopy;


    /**
     * 
     * @param array $repositories
     * @return \Nespresso\Manager
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
     * @param type $domain
     * @return \Nespresso\Project\Repository
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
     * @return \Nespresso\Project\Repository
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
     * @param type $noCopy
     * @return \Nespresso\Project\Repository
     */
    public function setNoCopy($noCopy)
    {
	$this->noCopy = $noCopy;
	return $this;
    }


    /**
     * 
     * @return type
     */
    public function getNoCopy()
    {
	return $this->noCopy;
    }


    /**
     * 
     * @return type
     */
    public function hasNoCopy()
    {
	return !empty($this->noCopy) ? TRUE : FALSE;
    }

}