<?php

/*
 * This file is part of Composer.
 *
 * (c) Gerard TOKO <gerard.toko@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nespresso;

/**
 * Description of Task
 *
 * @author gerardtoko
 */
class Task
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
    public function __construct($container, $output, $releaseId)
    {
	$this->container = $container;
	$this->output = $output;
	$this->releaseId = $releaseId;
    }


    public function executePreCommand()
    {
	$manager = $this->container->get("nespresso.manager");
	$project = $manager->getProject();
	$repositories = $project->getRepositories();
	$connection = null;
	$this->output->writeln("<comment>Executing tasks pre...</comment>");


	foreach ($repositories as $repository) {

	    $connection = $this->getConnection($repository);
	    $deployTo = $repository->getDeployTo();

	    //executing common command
	    if ($project->hasCommonTasks()) {
		$commonTasks = $manager->getProject()->getCommonTasks();
		if ($commonTasks->hasPre()) {
		    $this->output->writeln("Executing common tasks");
		    $commonTasksPre = $commonTasks->getPre();
		    foreach ($commonTasksPre as $commonCommandTaskPre) {
			$command = $commonCommandTaskPre->getCommand();

			$outputSsh = trim($connection->exec(sprintf("cd %s/releases/%s && %s", $deployTo, $this->releaseId, $command)));
			$this->output->writeln(sprintf("[<info>%s</info>][<comment>%s</comment>] <error>%s</error>", $repository->getName(), $command, $outputSsh));
		    }
		}
	    }

	    //executing command specific
	    if ($repository->hasTasks()) {
		$tasks = $repository->getTasks();
		if ($tasks->hasPre()) {
		    $tasksPre = $tasks->getPre();
		    $this->output->writeln("Executing specific tasks ");
		    foreach ($tasksPre as $commandTaskPre) {
			$command = $commandTaskPre->getCommand();
			$outputSsh = trim($connection->exec(sprintf("cd %s/releases/%s && %s", $deployTo, $this->releaseId, $command)));
			$this->output->writeln(sprintf("[<info>%s</info>][<comment>%s</comment>] <error>%s</error>", $repository->getName(), $command, $outputSsh));
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
	$this->output->writeln("<comment>Executing tasks post...");


	foreach ($repositories as $repository) {

	    $connection = $this->getConnection($repository);
	    $deployTo = $repository->getDeployTo();

	    //executing common command
	    if ($project->hasCommonTasks()) {
		$commonTasks = $manager->getProject()->getCommonTasks();
		if ($commonTasks->hasPost()) {
		    $this->output->writeln("Executing common tasks");
		    $commonTasksPost = $commonTasks->getPost();
		    foreach ($commonTasksPost as $commonCommandTaskPost) {
			$command = $commonCommandTaskPost->getCommand();

			$outputSsh = trim($connection->exec(sprintf("cd %s/releases/%s && %s", $deployTo, $this->releaseId, $command)));
			$this->output->writeln(sprintf("[<info>%s</info>][<comment>%s</comment>] <error>%s</error>", $repository->getName(), $command, $outputSsh));
		    }
		}
	    }

	    //executing command specific
	    if ($repository->hasTasks()) {
		$tasks = $repository->getTasks();
		if ($tasks->hasPost()) {
		    $tasksPost = $tasks->getPost();
		    $this->output->writeln("Executing specific tasks ");
		    foreach ($tasksPost as $commandTaskPost) {
			$command = $commandTaskPost->getCommand();
			$outputSsh = trim($connection->exec(sprintf("cd %s/releases/%s && %s", $deployTo, $this->releaseId, $command)));
			$this->output->writeln(sprintf("[<info>%s</info>][<comment>%s</comment>] <error>%s</error>", $repository->getName(), $command, $outputSsh));
		    }
		}
	    }
	}
    }


    /**
     * 
     * @param type $repository
     * @return \Nespresso\Manager\Connection
     */
    protected function getConnection($repository)
    {
	if ($repository->hasConnection()) {
	    $connection = $repository->getConnection();
	} else {
	    $connection = new Connection(
			    $repository->getUser(),
			    $repository->getDomain(),
			    $repository->getPort(),
			    $this->container->get("nespresso.manager")->getConfig()->getKey(), $this->output);
	    $repository->setConnection($connection);
	}
	return $connection;
    }


    /**
     * 
     * @param type $outputSsh
     * @return boolean
     */
    protected function isError($outputSsh)
    {
	if ($outputSsh) {
	    $this->output->writeln("<error>Error: $outputSsh</error>");
	    return true;
	} else {
	    return false;
	}
    }

}