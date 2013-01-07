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
use Nespresso\Builder\RsyncCopyReleaseBuilder;
use Nespresso\Controller\Controller as BaseController;

/**
 * Description of RepositoryControl
 *
 * @author gerardtoko
 */
class RepositoryController extends BaseController implements ControllerInterface
{

    protected $container;
    protected $output;


    /**
     * 
     * @param type $container
     * @param type $output
     */
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
	}
    }


    public function createNewReleaseAction()
    {
	$manager = $this->container->get("nespresso.manager");
	$repositories = $manager->getProject()->getRepositories();
	$connection = null;

	$dateTime = new \DateTime();
	$releaseId = $dateTime->format('y-m-d-H-i-s');

	//$this->output->writeln("Control repositories");
	foreach ($repositories as $repository) {

	    $name = $repository->getName();
	    $LastReleaseId = $this->getLastRelease($repository);
	    $deployTo = $repository->getDeployTo();
	    $connection = $this->getConnection($repository);

	    if (!empty($LastReleaseId)) {
		$this->output->writeln("Create new release <info>$releaseId</info> from <comment>$LastReleaseId</comment> on <info>$name</info>...");
		$rsyncCopyReleaseBuilder = new RsyncCopyReleaseBuilder($this->container, $repository, $releaseId, $LastReleaseId);
		$command = $rsyncCopyReleaseBuilder->build();

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
	    } else {
		$outputSsh = trim($connection->exec(sprintf("mkdir -p %s/releases/%s", $deployTo, $releaseId)));
		$this->isError($outputSsh);
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

}