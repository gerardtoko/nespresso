<?php

/*
 * This file is part of Nespresso.
 *
 * (c) Gerard TOKO <gerard.toko@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nespresso\Builder;

use Nespresso\Mapping\Project as ProjectMapping;
use Nespresso\Mapping\Project\Repository as RepositoryMapping;
use Nespresso\Mapping\Project\Repository\Task as TaskRepositoryMapping;
use Nespresso\Mapping\Project\Repository\Task\Command as CommandTaskRepositoryMapping;
use Nespresso\Mapping\Project\Common\Task as CommonTaskMapping;
use Nespresso\Mapping\Project\Common\Task\Command as CommonCommandTaskMapping;
use Nespresso\Builder\BuilderInterface;
use Nespresso\Mapping\Project\Source as SourceMapping;

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
     * @return \Nespresso\Mapping\Project
     */
    public function build()
    {
	$projectFromJson = $this->projectFromJson;
	$repository = $this->repository;
	$group = $this->group;

	$projectObject = new ProjectMapping();
	if (!empty($projectFromJson->source)) {
	    $SourceObject = $this->getSource($projectFromJson->source);
	    $projectObject->setSource($SourceObject);
	} else {
	    throw new \Exception("repository source is undefined or incorrect");
	}

	//repositories
	$repositories = $this->getRepositories($projectFromJson, $repository, $group);
	$projectObject->setRepositories($repositories);

	//common tasks
	if (!empty($projectFromJson->common_tasks)) {
	    $taskObject = $this->getCommandTasks($projectFromJson);
	    $projectObject->setCommonTasks($taskObject);
	}

	//other
	if (!empty($projectFromJson->keep_release)) {
	    $projectObject->setKeepRelease($projectFromJson->keep_release);
	} else {
	    $projectObject->setKeepRelease(5);
	}

	if (!empty($projectFromJson->shared_directory)) {
	    $projectObject->setSharedDirectory($projectFromJson->shared_directory);
	}

	if (!empty($projectFromJson->shared_file)) {
	    $projectObject->setSharedFile($projectFromJson->shared_file);
	}

	if (!empty($projectFromJson->cache)) {
	    $projectObject->setCache($projectFromJson->cache);
	    if (!empty($projectFromJson->cache_mode)) {
		$projectObject->setCacheMode($projectFromJson->cache_mode);
	    } else {
		$projectObject->setCacheMode("777");
	    }
	}

	return $projectObject;
    }


    /**
     * 
     * @param type $projectFromJson
     * @return \Nespresso\Mapping\Project\Common\Task|null
     */
    protected function getCommandTasks($projectFromJson)
    {
	$taskObject = New CommonTaskMapping();
	if (!empty($projectFromJson->common_tasks->pre)) {
	    foreach ($projectFromJson->common_tasks->pre as $command) {
		$commandPre[] = $this->getCommonCommandTaskMapping($command);
	    }
	    $taskObject->setPre($commandPre);
	}

	if (!empty($projectFromJson->common_tasks->post)) {
	    foreach ($projectFromJson->common_tasks->post as $command) {
		$commandPost[] = $this->getCommonCommandTaskMapping($command);
	    }
	    $taskObject->setPost($commandPost);
	}
	return $taskObject;
    }


    /**
     * 
     * @param type $command
     * @return \Nespresso\Mapping\Project\Common\Task\Command
     */
    public function getCommonCommandTaskMapping($command)
    {
	$commandObject = New CommonCommandTaskMapping();
	$commandObject->setCommand($command);
	return $commandObject;
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
		    if (!empty($projectObject->repositories->$repository)) {
			$repo = $projectObject->repositories->$repository;
			$repositoryObject = $this->getRepositoryObject($repo);
			$repositoryObject->setName($repository);
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
		$repositoryObject->setName($var);
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
     * @return \Nespresso\Mapping\Project\Repository
     */
    protected function getRepositoryObject($repo)
    {
	$repositoryObject = New RepositoryMapping();
	$repositoryObject->setUser($repo->user);
	$repositoryObject->setDomain($repo->domain);
	$repositoryObject->setDeployTo($repo->deploy_to);

	if (!empty($repo->port)) {
	    $repositoryObject->setPort($repo->port);
	} else {
	    $repositoryObject->setPort("22");
	}

	if (!empty($repo->tasks)) {
	    $taskObject = New TaskRepositoryMapping();
	    if (!empty($repo->tasks->pre)) {
		$commands = $this->getCommandTaskRepositoryMapping($repo->tasks->pre);
		$taskObject->setPre($commands);
	    }

	    if (!empty($repo->tasks->post)) {
		$commands = $this->getCommandTaskRepositoryMapping($repo->tasks->post);
		$taskObject->setPost($commands);
	    }
	    $repositoryObject->setTasks($taskObject);
	}

	return $repositoryObject;
    }


    /**
     * 
     * @param type $data
     * @return \Nespresso\Mapping\Project\Repository\Task\Command
     */
    public function getCommandTaskRepositoryMapping($data)
    {
	$commands = array();
	foreach ($data as $command) {
	    $commandObject = New CommandTaskRepositoryMapping();
	    $commandObject->setCommand($command);
	    $commands[] = $commandObject;
	}

	return $commands;
    }


    /**
     * 
     * @param type $json
     * @return \Nespresso\Mapping\Project\Source
     */
    public function getSource($json)
    {
	$sourceObject = new SourceMapping();
	$sourceObject->setScm($json->scm);
	$sourceObject->setType($json->type);
	return $sourceObject;
    }

}