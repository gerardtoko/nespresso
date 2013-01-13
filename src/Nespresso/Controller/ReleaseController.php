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

use Nespresso\Builder\RsyncCopyReleaseBuilder;
use Nespresso\Controller\Controller as BaseController;

/**
 * Description of ReleaseController
 *
 * @author gerardtoko
 */
class ReleaseController extends BaseController
{

    protected $container;
    protected $output;
    protected $newRelease;


    /**
     * 
     * @param type $container
     * @param type $output
     */
    public function __construct($container)
    {
	$this->container = $container;
	$this->output = $container->get("IO")->output();
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
	    $outputSsh = trim($connection->exec("cd $deployTo/releases"));
	    if ($this->ckeckReturn($outputSsh)) {
		$outputSsh = trim($connection->exec(sprintf("mkdir -p %s/releases", $deployTo)));
		$this->ckeckReturn($outputSsh);
	    }

	    // control current symbolink
	    $this->ckeckReturn(trim($connection->exec(sprintf("cd %s/current", $deployTo))));
	}
    }


    public function updateSymbolinkAction()
    {
	$manager = $this->container->get("nespresso.manager");
	$repositories = $manager->getProject()->getRepositories();
	$connection = null;
	$this->output->writeln("Toggle release");

	foreach ($repositories as $repository) {

	    $deployTo = $repository->getDeployTo();
	    $connection = $this->getConnection($repository);
	    $this->output->writeln(sprintf("toggle <info>%s</info> on <comment>%s</comment>", $repository->getName(), $this->newRelease));
	    $this->ckeckReturn(trim($connection->exec(sprintf("rm -rf %s/current", $deployTo))));
	    $this->ckeckReturn(trim($connection->exec(sprintf("cd %s && ln -s %s/releases/%s current", $deployTo, $deployTo, $this->newRelease))));
	}
    }


    /**
     * 
     * @return type
     */
    public function createNewReleaseAction()
    {
	$manager = $this->container->get("nespresso.manager");
	$project = $manager->getProject();
	$repositories = $project->getRepositories();
	$connection = null;

	$dateTime = new \DateTime();
	$releaseId = $dateTime->format('y-m-d-H-i-s');
	$this->newRelease = $releaseId;

	foreach ($repositories as $repository) {

	    $name = $repository->getName();
	    $deployTo = $repository->getDeployTo();
	    $connection = $this->getConnection($repository);

	    $this->output->writeln("Create new release <info>$releaseId</info> on <info>$name</info>...");
	    $outputSsh = trim($connection->exec(sprintf("mkdir -p %s/releases/%s", $deployTo, $releaseId)));
	    $this->ckeckReturn($outputSsh);

	    if ($project->hasCache()) {
		$outputSsh = trim($connection->exec(sprintf("mkdir -p %s/releases/%s/%s", $deployTo, $releaseId, $project->getCache())));
		if (!$this->ckeckReturn($outputSsh)) {
		    $outputSsh = trim($connection->exec(sprintf("chmod -R %s %s/releases/%s/%s", $project->getCacheMode(), $deployTo, $releaseId, $project->getCache())));
		    $this->ckeckReturn($outputSsh);
		}
	    }
	}

	return $releaseId;
    }


    /**
     * 
     * @param type $commit
     */
    public function pushCommitFileAction($commit)
    {
	$manager = $this->container->get("nespresso.manager");
	$project = $manager->getProject();
	$repositories = $project->getRepositories();
	$connection = null;

	foreach ($repositories as $repository) {
	    $deployTo = $repository->getDeployTo();
	    $connection = $this->getConnection($repository);
	    $outputSsh = trim($connection->exec(sprintf("echo %s > %s/releases/%s/nespresso.lock", $commit, $deployTo, $this->newRelease)));
	    $this->ckeckReturn($outputSsh);
	}
    }


    /**
     * 
     * @return type
     */
    public function updateAction()
    {
	$manager = $this->container->get("nespresso.manager");
	$repositories = $manager->getProject()->getRepositories();
	$connection = null;

	$dateTime = new \DateTime();
	$releaseId = $dateTime->format('y-m-d-H-i-s');
	$this->newRelease = $releaseId;

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
		$this->ckeckReturn($outputSsh);

		$project = $this->container->get("nespresso.manager")->getProject();
		if ($project->hasCache()) {
		    $outputSsh = trim($connection->exec(sprintf("mkdir -p %s/releases/%s/%s", $deployTo, $releaseId, $project->getCache())));
		    if (!$this->ckeckReturn($outputSsh)) {
			$outputSsh = trim($connection->exec(sprintf("chmod -R %s %s/releases/%s/%s", $project->getCacheMode(), $deployTo, $releaseId, $project->getCache())));
			$this->ckeckReturn($outputSsh);
		    }
		}
	    } else {
		$outputSsh = trim($connection->exec(sprintf("mkdir -p %s/releases/%s", $deployTo, $releaseId)));
		$this->ckeckReturn($outputSsh);
	    }
	}

	return $releaseId;
    }


    /**
     * 
     * @param type $repository
     * @return type
     */
    public function getLastRelease($repository)
    {

	$connection = $this->getConnection($repository);
	$deployTo = $repository->getDeployTo();
	$outputSsh = trim($connection->exec(sprintf("ls -l %s/current | cut -d' ' -f11", $deployTo)));

	$release = basename($outputSsh);
	$validation = $this->container->get("validation");
	if (!$validation->isValidRelease($release)) {
	    throw new \Exception("Release $release is invalid");
	}

	return $release;
    }


    /**
     * 
     * @param type $repository
     * @return type
     */
    public function getAllRelease($repository)
    {

	$connection = $this->getConnection($repository);
	$deployTo = $repository->getDeployTo();
	$outputSsh = trim($connection->exec(sprintf("ls %s/releases", $deployTo)));
	$releases = array_reverse(explode("\n", $outputSsh));
	$validation = $this->container->get("validation");
	$releasesValid = array();


	//validation release
	foreach ($releases as $release) {
	    if ($validation->isValidRelease($release)) {
		$releasesValid[] = $release;
	    }
	}

	return $releasesValid;
    }


    /**
     * 
     * @return null
     */
    public function checkKeepRelease()
    {

	$manager = $this->container->get("nespresso.manager");
	$repositories = $manager->getProject()->getRepositories();
	$keepRelease = $manager->getProject()->getKeepRelease();
	$connection = null;


	//$this->output->writeln("Control repositories");
	foreach ($repositories as $repository) {

	    $this->output->writeln(sprintf("Checking releases on <info>%s</info>", $repository->getName()));

	    $connection = $this->getConnection($repository);
	    $deployTo = $repository->getDeployTo();
	    $outputSsh = trim($connection->exec(sprintf("ls %s/releases", $deployTo)));
	    $releases = array_reverse(explode("\n", $outputSsh));
	    $validation = $this->container->get("validation");
	    $AllReleases = array();

	    //validation release
	    foreach ($releases as $release) {
		if ($validation->isValidRelease($release)) {
		    if (!empty($this->newRelease)) {
			if ($this->newRelease != $release) {
			    $AllReleases[] = $release;
			}
		    } else {
			$AllReleases[] = $release;
		    }
		}
	    }

	    if (count($AllReleases) > $keepRelease) {
		$releasesRemove = array_slice($AllReleases, $keepRelease);
		foreach ($releasesRemove as $removing) {
		    $this->output->writeln(sprintf("<comment>deleting release<comment> <info>%s</info> <comment>on<comment> <info>%s</info><comment>...<comment>", $removing, $repository->getName()));
		    $outputSsh = trim($connection->exec(sprintf("rm -rf %s/releases/%s", $deployTo, $removing)));
		    $this->ckeckReturn($outputSsh);
		}
	    }
	}
    }


    /**
     * 
     * @param type $release
     */
    public function checkoutAction($release)
    {

	$manager = $this->container->get("nespresso.manager");
	$validation = $this->container->get("validation");
	$repositories = $manager->getProject()->getRepositories();
	$this->output->writeln("Control repositories");
	foreach ($repositories as $repository) {

	    $lastCommit = $this->getLastRelease($repository);
	    $releases = $this->getAllRelease($repository);
	    if ($validation->isValidRelease($release)) {
		$this->newRelease = $release;
		return $this->updateSymbolinkAction();
	    } else {

		$positionLastcommit = (int) array_search($lastCommit, $releases);
		if ($release == 0) {
		    $this->newRelease = $releases[0];
		    return $this->updateSymbolinkAction();
		} else {
		    $newPosition = $positionLastcommit - $release;

		    if (empty($releases[$newPosition])) {
			throw new \Exception("Undefined position release");
		    }

		    $this->newRelease = $releases[$newPosition];
		    return $this->updateSymbolinkAction();
		}
	    }
	}
    }


    public function cleanupAction()
    {

	$manager = $this->container->get("nespresso.manager");
	$repositories = $manager->getProject()->getRepositories();
	$connection = null;

	$this->output->writeln("cleanup repositories");
	foreach ($repositories as $repository) {

	    $deployTo = $repository->getDeployTo();
	    $lastCommit = $this->getLastRelease($repository);
	    $releases = $this->getAllRelease($repository);

	    if (($key = array_search($lastCommit, $releases)) !== false) {
		unset($releases[$key]);
	    } else {
		throw new \Exception("Undefined symbolic current");
	    }

	    foreach ($releases as $removing) {
		$this->output->writeln(sprintf("<comment>deleting release<comment> <info>%s</info> <comment>on<comment> <info>%s</info><comment>...<comment>", $removing, $repository->getName()));
		$outputSsh = trim($connection->exec(sprintf("rm -rf %s/releases/%s", $deployTo, $removing)));
		$this->ckeckReturn($outputSsh);
	    }
	}
    }


    public function checkAction()
    {

	$manager = $this->container->get("nespresso.manager");
	$repositories = $manager->getProject()->getRepositories();

	$this->output->writeln("cleanup repositories");
	foreach ($repositories as $repository) {

	    $lastCommit = $this->getLastRelease($repository);
	    $releases = $this->getAllRelease($repository);

	    foreach ($releases as $key => $release) {
		if ($release == $lastCommit) {
		    $this->output->writeln(sprintf("[%s] %s <comment>(current)</comment>", $key, $release));
		} else {
		    $this->output->writeln(sprintf("[%s] %s", $key, $release));
		}
	    }
	}
    }



}