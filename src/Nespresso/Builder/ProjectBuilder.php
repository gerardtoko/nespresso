<?php

/*
 * This file is part of the rdeploy package.
 *
 * (c) Gerard TOKO <gerard.toko@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nespresso\Builder;

use Nespresso\Script\Project as ProjectObject;
use Nespresso\Script\Project\Repository as RepositoryObject;
use Nespresso\Script\Project\Repository\Task as TaskObject;
use Nespresso\Script\Project\Repository\Task\Command as CommandObject;
use Nespresso\Builder\BuilderInterface;

/**
 * Description of Deploy
 *
 * @author gerardtoko
 */
class ProjectBuilder implements BuilderInterface
{

    protected $projectFromJson;
    protected $repository;
    protected $group;
    protected $no_copy;


    //put your code here

    public function __construct($projectFromJson, $repository, $group)
    {
	$this->projectFromJson = $projectFromJson;
	$this->repository = $repository;
	$this->group = $group;
    }


    /**
     * 
     * @return \Nespresso\Script\Builder\ProjectObject
     */
    public function build()
    {
	$projectFromJson = $this->projectFromJson;
	$repository = $this->repository;
	$group = $this->group;

	$projectObject = new ProjectObject();
	$projectObject->setGit($projectFromJson->git);

	$repositories = $this->getRepositories($projectFromJson, $repository, $group);
	$projectObject->setRepositories($repositories);

	if (!empty($projectFromJson->no_copy)) {
	    $projectObject->setNoCopy($projectFromJson->no_copy);
	}
	if (!empty($projectFromJson->keepRelease)) {
	    $projectObject->setKeepRelease($projectFromJson->keepRelease);
	}

	return $projectObject;
    }


    /**
     * 
     * @param type $projectObject
     * @param type $var
     * @param type $group
     * @return type
     * @throws \Exception
     */
    protected function getRepositories($projectObject, $var, $group)
    {

	$repositories = array();
	if ($group != null) {

	    if (empty($projectObject->groups)) {
		throw new \Exception("groups is undefined in the project");
	    }

	    $groups = $projectObject->groups;
	    if (empty($groups->$var)) {
		throw new \Exception("group $var is undefined");
	    } else {
		$repositories_group = $groups->$var;

		foreach ($repositories_group as $repository) {
		    $repositoryObject = New RepositoryObject();
		    if (!empty($projectObject->repositories->$repository)) {
			$repo = $projectObject->repositories->$repository;
			$repositoryObject = $this->getRepositoryObject($repo);
		    } else {
			throw new \Exception("repository $repository inconning");
		    }
		    $repositories[] = $repositoryObject;
		}
	    }
	} else {

	    if (!empty($projectObject->repositories->$var)) {
		$repo = $projectObject->repositories->$var;
		$repositoryObject = $this->getRepositoryObject($repo);
	    } else {
		throw new \Exception("repository $var inconning");
	    }
	    $repositories[] = $repositoryObject;
	}
	return $repositories;
    }


    /**
     * 
     * @param type $repo
     * @return \Nespresso\Script\Project\Repository
     */
    protected function getRepositoryObject($repo)
    {
	$repositoryObject = New RepositoryObject();
	$repositoryObject->setUser($repo->user);
	$repositoryObject->setDomain($repo->domain);
	$repositoryObject->setDeployTo($repo->deploy_to);

	if (!empty($repo->port)) {
	    $repositoryObject->setPort($repo->port);
	} else {
	    $repositoryObject->setPort("22");
	}
	if (!empty($repo->tasks)) {

	    $taskObject = New TaskObject();

	    if (!empty($repo->tasks->pre)) {
		$commandPre = array();
		foreach ($repo->tasks->pre as $command) {
		    $commandObject = New CommandObject();
		    $commandObject->setCommand($command);
		    $commandPre[] = $commandObject;
		}
		$taskObject->setPre($commandPre);
	    }

	    if (!empty($repo->tasks->post)) {
		$commandPost = array();
		foreach ($repo->tasks->pre as $command) {
		    $commandObject = New CommandObject();
		    $commandObject->setCommand($command);
		    $commandPost[] = $commandObject;
		}
		$taskObject->setPost($commandPost);
	    }
	    $repositoryObject->setTasks($taskObject);
	}

	return $repositoryObject;
    }

}