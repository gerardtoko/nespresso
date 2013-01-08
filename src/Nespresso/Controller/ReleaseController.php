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
	    $outputSsh = trim($connection->exec("cd $deployTo/releases"));
	    if ($this->ckeckReturn($outputSsh)) {
		$outputSsh = trim($connection->exec("mkdir -p $deployTo/releases"));
		$this->ckeckReturn($outputSsh);
	    }

	    // control current symbolink
	    $this->ckeckReturn(trim($connection->exec("cd $deployTo/current")));
	}
    }


    
    public function updateSymbolinkAction()
    {
	$manager = $this->container->get("nespresso.manager");
	$repositories = $manager->getProject()->getRepositories();
	$connection = null;
	$this->output->writeln("Toggle release");
	$newRelease = $this->newRelease;
	foreach ($repositories as $repository) {
	    $name = $repository->getName();
	    $deployTo = $repository->getDeployTo();
	    $connection = $this->getConnection($repository);
	    $this->output->writeln("toggle <info>$name</info> on <comment>$newRelease</comment>");
	    $this->ckeckReturn(trim($connection->exec("rm -rf $deployTo/current", $deployTo)));
	    $this->ckeckReturn(trim($connection->exec("cd $deployTo && ln -s $deployTo/releases/$newRelease current")));
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
	$newRelease = $dateTime->format('y-m-d-H-i-s');
	$this->newRelease = $newRelease;

	foreach ($repositories as $repository) {

	    $name = $repository->getName();
	    $deployTo = $repository->getDeployTo();
	    $connection = $this->getConnection($repository);

	    $this->output->writeln("Create new release <info>$newRelease</info> on <info>$name</info>...");
	    $outputSsh = trim($connection->exec("mkdir -p $deployTo/releases/$newRelease"));
	    $this->ckeckReturn($outputSsh);

	    if ($project->hasCache()) {
		$cache = $project->getCache();
		$outputSsh = trim($connection->exec("mkdir -p $deployTo/releases/$newRelease/$cache"));
		if (!$this->ckeckReturn($outputSsh)) {
		    $cacheMode = $project->getCacheMode();
		    $outputSsh = trim($connection->exec("chmod -R $cacheMode $deployTo/releases/$newRelease/$cache"));
		    $this->ckeckReturn($outputSsh);
		}
	    }
	}

	return $newRelease;
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
	    $release = $this->newRelease;
	    $outputSsh = trim($connection->exec("echo $commit > $deployTo/releases/$release/nespresso.lock"));
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
	$newRelease = $dateTime->format('y-m-d-H-i-s');
	$this->newRelease = $newRelease;

	//$this->output->writeln("Control repositories");
	foreach ($repositories as $repository) {

	    $name = $repository->getName();
	    $LastReleaseId = $this->getLastRelease($repository);
	    $deployTo = $repository->getDeployTo();
	    $connection = $this->getConnection($repository);

	    if (!empty($LastReleaseId)) {
		$this->output->writeln("Create new release <info>$newRelease</info> from <comment>$LastReleaseId</comment> on <info>$name</info>...");
		$rsyncCopyReleaseBuilder = new RsyncCopyReleaseBuilder($this->container, $repository, $newRelease, $LastReleaseId);
		$command = $rsyncCopyReleaseBuilder->build();

		$outputSsh = trim($connection->exec($command));
		$this->ckeckReturn($outputSsh);

		$project = $this->container->get("nespresso.manager")->getProject();
		if ($project->hasCache()) {
		    $cache = $project->getCache();
		    $outputSsh = trim($connection->exec("mkdir -p $deployTo/releases/$newRelease/$cache"));
		    if (!$this->ckeckReturn($outputSsh)) {
			$cacheMode = $project->getCacheMode();
			$outputSsh = trim($connection->exec("chmod -R $cacheMode $deployTo/releases/$newRelease/$cache"));
			$this->ckeckReturn($outputSsh);
		    }
		}
	    } else {
		$outputSsh = trim($connection->exec("mkdir -p $deployTo/releases/$newRelease"));
		$this->ckeckReturn($outputSsh);
	    }
	}

	return $newRelease;
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
	$outputSsh = trim($connection->exec("ls $deployTo/releases"));
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
     * @return null
     */
    public function ckeckRelease()
    {

	$manager = $this->container->get("nespresso.manager");
	$repositories = $manager->getProject()->getRepositories();
	$keepRelease = $manager->getProject()->getKeepRelease();
	$connection = null;


	//$this->output->writeln("Control repositories");
	foreach ($repositories as $repository) {

	    $name = $repository->getName();
	    $connection = $this->getConnection($repository);
	    $deployTo = $repository->getDeployTo();
	    $outputSsh = trim($connection->exec("ls $deployTo/releases"));
	    $releases = array_reverse(explode("\n", $outputSsh));
	    $validation = $this->container->get("validation");
	    $AllReleases = array();

	    $this->output->writeln("Checking releases on <info>$name</info>");
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
		    $this->output->writeln("<comment>deleting release<comment> <info>$removing</info> <comment>on<comment> <info>$name</info><comment>...<comment>");
		    $outputSsh = trim($connection->exec("rm -rf $deployTo/releases/$removing"));
		    $this->ckeckReturn($outputSsh);
		}
	    }
	}
    }

}