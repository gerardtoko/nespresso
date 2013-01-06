<?php

/*
 * This file is part of Composer.
 *
 * (c) Gerard TOKO <gerard.toko@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nespresso\Controller;

use Nespresso\Controller\ControllerInterface;
use Nespresso\Manager\Connection;
use Nespresso\Builder\RsyncCopyReleaseBuilder;

/**
 * Description of RepositoryControl
 *
 * @author gerardtoko
 */
class RepositoryController implements ControllerInterface
{

    protected $container;
    protected $output;


    public function __construct($container, $output)
    {
	$this->container = $container;
	$this->output = $output;
    }


    public function controlAction()
    {
	$manager = $this->container->get("nespresso.manager");
	$repositories = $manager->getProject()->getRepositories();
	$connection = null;
	$this->output->writeln("Control repositories");

	foreach ($repositories as $repository) {

	    $connection = $this->getConnection($repository);
	    $this->output->writeln(sprintf("Control repository <info>%s</info>", $repository->getName()));
	    $deployTo = $repository->getDeployTo();

	    // control releases directory
	    $outputSsh = trim($connection->exec(sprintf("cd %s/releases", $deployTo)));
	    if ($this->isError($outputSsh)) {
		$outputSsh = trim($connection->exec(sprintf("mkdir -p %s/releases", $deployTo)));
		$this->isError($outputSsh);
	    }

	    // control current symbolink
	    $this->isError(trim($connection->exec(sprintf("cd %s/current", $deployTo))));

	    if ($manager->getProject()->isShared()) {
		// control releases directory
		$outputSsh = trim($connection->exec(sprintf("cd %s/shared", $deployTo)));
		if ($this->isError($outputSsh)) {
		    $this->output->writeln(sprintf("<comment>For the reasons of performance, used the shared directory</comment>", $repository->getName()));
		    $outputSsh = trim($connection->exec(sprintf("mkdir -p %s/shared", $deployTo)));
		    $this->isError($outputSsh);
		}
	    }
	}
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


    public function createNewReleaseAction()
    {
	$manager = $this->container->get("nespresso.manager");
	$repositories = $manager->getProject()->getRepositories();
	$connection = null;

	$dateTime = new \DateTime();
	$releaseId = $dateTime->format('y-m-d-G-i-s');

	//$this->output->writeln("Control repositories");
	foreach ($repositories as $repository) {

	    $name = $repository->getName();
	    $LastReleaseId = $this->getLastRelease($repository);
	    $deployTo = $repository->getDeployTo();

	    $this->output->writeln("Create new release <info>$releaseId</info> from <comment>$LastReleaseId</comment> on <info>$name</info>...");
	    $rsyncCopyReleaseBuilder = new RsyncCopyReleaseBuilder($this->container, $repository, $releaseId, $LastReleaseId);
	    $command = $rsyncCopyReleaseBuilder->build();

	    $connection = $this->getConnection($repository);
	    $outputSsh = trim($connection->exec($command));
	    $this->isError($outputSsh);

	    $project = $this->container->get("nespresso.manager")->getProject();
	    if ($project->hasCache()) {
		$outputSsh = trim($connection->exec(sprintf("mkdir -p %s/releases/%s/%s", $deployTo, $releaseId, $project->getCache())));
		if (!$this->isError($outputSsh)) {
		    $outputSsh = trim($connection->exec(sprintf("chmod -R %s %s/releases/%s/%s", $project->getCacheMode(), $deployTo, $releaseId, $project->getCache())));
		    $this->isError($outputSsh);
		}
	    }
	}

	return $releaseId;
    }


    /**
     * 
     * @param type $repository
     * @return type
     */
    protected function getLastRelease($repository)
    {

	$connection = $this->getConnection($repository);
	$deployTo = $repository->getDeployTo();
	$outputSsh = trim($connection->exec(sprintf("ls %s/releases", $deployTo)));
	$releases = array_reverse(explode("\n", $outputSsh));
	$validation = $this->container->get("validation");
	$lastRelease = NULL;

	//validation release
	foreach ($releases as $release) {
	    if ($validation->isValidRelease($release)) {
		$lastRelease = $release;
		break;
	    }
	}
	return $lastRelease;
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

}