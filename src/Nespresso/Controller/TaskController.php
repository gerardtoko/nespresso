<?php

/*
 * This file is part of Nespresso.
 *
 * (c) Gerard TOKO <gerard.toko@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nespresso\Controller;

use Nespresso\Controller\Controller as BaseController;

/**
 * Description of Task
 *
 * @author gerardtoko
 */
class TaskController extends BaseController
{

    protected $container;
    protected $output;
    protected $releaseId;


    /**
     * 
     * @param type $container
     * @param type $output
     * @param type $releaseId
     */
    public function __construct($container, $releaseId)
    {
	$this->container = $container;
	$this->output = $container->get("IO")->output();
	$this->releaseId = $releaseId;
    }


    public function executePreCommand()
    {
	$manager = $this->container->get("nespresso.manager");
	$project = $manager->getProject();
	$repositories = $project->getRepositories();
	$connection = null;

	if ($project->hasCommonTasks()) {
	    
	    $this->output->writeln("Executing the tasks before the deployment");
	}

	foreach ($repositories as $repository) {

	    $connection = $this->getConnection($repository);
	    $deployTo = $repository->getDeployTo();

	    //executing common command
	    if ($project->hasCommonTasks()) {
		$commonTasks = $manager->getProject()->getCommonTasks();
		if ($commonTasks->hasPre()) {
		    $commonTasksPre = $commonTasks->getPre();
		    foreach ($commonTasksPre as $commonCommandTaskPre) {
			$command = $commonCommandTaskPre->getCommand();
			$outputSsh = trim($connection->exec(sprintf("cd %s/releases/%s && %s", $deployTo, $this->releaseId, $command)));
			$this->output->writeln(sprintf("        - Task <comment>%s</comment> <info>%s</info> --> %s", $repository->getName(), $command, $outputSsh));			    
		    }
		}
	    }

	    //executing command specific
	    if ($repository->hasTasks()) {
		$tasks = $repository->getTasks();
		if ($tasks->hasPre()) {
		    $tasksPre = $tasks->getPre();
		    foreach ($tasksPre as $commandTaskPre) {
			$command = $commandTaskPre->getCommand();
			$outputSsh = trim($connection->exec(sprintf("cd %s/releases/%s && %s", $deployTo, $this->releaseId, $command)));
			$this->output->writeln(sprintf("	- Task <comment>%s</comment> <info>%s</info> --> %s", $repository->getName(), $command, $outputSsh));
		    }
		}
	    }
	}
    }


    public function executePostCommand()
    {

	$manager = $this->container->get("nespresso.manager");
	$project = $manager->getProject();
	$repositories = $project->getRepositories();
	$connection = null;

	if ($project->hasCommonTasks()) {
	    
	    $this->output->writeln("Executing the tasks after the deployment");
	}

	foreach ($repositories as $repository) {

	    $connection = $this->getConnection($repository);
	    $deployTo = $repository->getDeployTo();

	    //executing common command
	    if ($project->hasCommonTasks()) {
		$commonTasks = $manager->getProject()->getCommonTasks();
		if ($commonTasks->hasPost()) {
		    $commonTasksPost = $commonTasks->getPost();
		    foreach ($commonTasksPost as $commonCommandTaskPost) {
			$command = $commonCommandTaskPost->getCommand();
			$outputSsh = trim($connection->exec(sprintf("cd %s/releases/%s && %s", $deployTo, $this->releaseId, $command)));
			$this->output->writeln(sprintf("	- Task <comment>%s</comment> <info>%s</info> --> %s", $repository->getName(), $command, $outputSsh));
		    }
		}
	    }

	    //executing command specific
	    if ($repository->hasTasks()) {
		$tasks = $repository->getTasks();
		if ($tasks->hasPost()) {
		    $tasksPost = $tasks->getPost();
		    foreach ($tasksPost as $commandTaskPost) {
			$command = $commandTaskPost->getCommand();
			$outputSsh = trim($connection->exec(sprintf("cd %s/releases/%s && %s", $deployTo, $this->releaseId, $command)));
			$this->output->writeln(sprintf("	- Task <comment>%s</comment> <info>%s</info> --> %s", $repository->getName(), $command, $outputSsh));
		    }
		}
	    }
	}
    }

}