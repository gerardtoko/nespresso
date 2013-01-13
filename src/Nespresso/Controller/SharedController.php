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
 * Description of SharedController
 *
 * @author gerardtoko
 */
class SharedController extends BaseController
{

    protected $container;
    protected $output;
    protected $newRelease;
    protected $performanceShared;


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
	$this->newRelease = $releaseId;
    }


    public function controlAction()
    {
	$manager = $this->container->get("nespresso.manager");
	$repositories = $manager->getProject()->getRepositories();


	foreach ($repositories as $repository) {
	    $name = $repository->getName();
	    $connection = $this->getConnection($repository);
	    $this->output->writeln(sprintf("Control shared of repository <info>%s</info> [<comment>Release:%s</comment>]", $name, $this->newRelease));
	    $deployTo = $repository->getDeployTo();

	    // control releases directory
	    $outputSsh = trim($connection->exec(sprintf("cd %s/shared", $deployTo)));
	    if ($this->ckeckReturn($outputSsh)) {

		if (!$this->performanceShared) {
		    $this->output->writeln(sprintf("<comment>For the reasons of performance, used the shared directory</comment>", $repository->getName()));
		    $this->performanceShared = TRUE;
		}

		$outputSsh = trim($connection->exec(sprintf("mkdir -p %s/shared", $deployTo)));
		$this->ckeckReturn($outputSsh);
	    }
	}

	if ($manager->getProject()->hasSharedFile()) {
	    $this->output->writeln("Control shared File");
	    $shared = $manager->getProject()->getSharedFile();
	    $this->sharedFile($repositories, $shared);
	}

	if ($manager->getProject()->hasSharedDirectory()) {

	    $this->output->writeln("Control shared Directory");
	    $shared = $manager->getProject()->getSharedDirectory();
	    $this->sharedDirectory($repositories, $shared);
	}
    }


    /**
     * 
     * @param type $repositories
     * @param type $shared
     */
    public function sharedDirectory($repositories, $shared)
    {

	$connection = null;
	foreach ($repositories as $repository) {

	    $name = $repository->getName();
	    $connection = $this->getConnection($repository);
	    $this->output->writeln(sprintf("Control shared of repository <info>%s</info> [<comment>Release:%s</comment>]", $name, $this->newRelease));
	    $deployTo = $repository->getDeployTo();

	    //create directory shared
	    foreach ($shared as $sharedDirectory) {

		$sharedDirectoryClean = trim($sharedDirectory, "/");
		if (!empty($sharedDirectoryClean)) {

		    $pos = strrpos($sharedDirectoryClean, '/');

		    $directory = $pos == FALSE ? $sharedDirectoryClean : substr($sharedDirectoryClean, $pos + 1);
		    $prefixDirectory = $pos == FALSE ? NULL : substr($sharedDirectoryClean, 0, $pos);

		    // control releases directory
		    $outputSsh = trim($connection->exec(sprintf("cd %s/shared/%s", $deployTo, $sharedDirectoryClean)));
		    if ($this->ckeckReturn($outputSsh)) {
			$outputSsh = trim($connection->exec(sprintf("mkdir -p %s/shared/%s", $deployTo, $sharedDirectoryClean)));
			$this->ckeckReturn($outputSsh);
		    }

		    if ($prefixDirectory != FALSE) {
			//check sub directory
			$outputSsh = trim($connection->exec(sprintf("mkdir -p %s/releases/%s/%s", $deployTo, $this->newRelease, $prefixDirectory)));
			$this->ckeckReturn($outputSsh);
		    }

		    $outputSsh = trim($connection->exec(sprintf("rm -rf %s/releases/%s/%s", $deployTo, $this->newRelease, $sharedDirectoryClean)));
		    if (!$this->ckeckReturn($outputSsh)) {
			//create symbolink
			if ($prefixDirectory == NULL) {
			    $outputSsh = trim($connection->exec(sprintf("cd %s/releases/%s && ln -s %s/shared/%s %s", $deployTo, $this->newRelease, $deployTo, $sharedDirectoryClean, $directory)));
			} else {
			    $outputSsh = trim($connection->exec(sprintf("cd %s/releases/%s/%s && ln -s %s/shared/%s %s", $deployTo, $this->newRelease, $prefixDirectory, $deployTo, $sharedDirectoryClean, $directory)));
			}
			$this->ckeckReturn($outputSsh);
		    }
		}
	    }
	}
    }


    /**
     * 
     * @param type $repositories
     * @param type $shared
     */
    public function sharedFile($repositories, $shared)
    {


	$connection = null;
	foreach ($repositories as $repository) {

	    $name = $repository->getName();
	    $connection = $this->getConnection($repository);
	    $this->output->writeln(sprintf("Control shared of repository <info>%s</info> [<comment>Release:%s</comment>]", $name, $this->newRelease));
	    $deployTo = $repository->getDeployTo();

	    //create directory shared
	    foreach ($shared as $sharedDirectory) {

		$sharedDirectoryClean = trim($sharedDirectory, "/");
		if (!empty($sharedDirectoryClean)) {

		    $pos = strrpos($sharedDirectoryClean, '/');

		    $file = $pos == FALSE ? $sharedDirectoryClean : substr($sharedDirectoryClean, $pos + 1);
		    $prefixDirectory = $pos == FALSE ? NULL : substr($sharedDirectoryClean, 0, $pos);

		    if ($prefixDirectory != FALSE) {
			//check sub directory
			$outputSsh = trim($connection->exec(sprintf("mkdir -p %s/releases/%s/%s", $deployTo, $this->newRelease, $prefixDirectory)));
			$this->ckeckReturn($outputSsh);
		    }

		    // control releases directory
		    $outputSsh = trim($connection->exec(sprintf("touch %s/shared/%s", $deployTo, $sharedDirectoryClean)));
		    if ($this->ckeckReturn($outputSsh)) {
			$outputSsh = trim($connection->exec(sprintf("touch %s/shared/%s", $deployTo, $sharedDirectoryClean)));
			$this->ckeckReturn($outputSsh);
		    }

		    $outputSsh = trim($connection->exec(sprintf("rm -rf %s/releases/%s/%s", $deployTo, $this->newRelease, $sharedDirectoryClean)));
		    if (!$this->ckeckReturn($outputSsh)) {
			//create symbolink
			if ($prefixDirectory == NULL) {
			    $outputSsh = trim($connection->exec(sprintf("cd %s/releases/%s && ln -s %s/shared/%s %s", $deployTo, $this->newRelease, $deployTo, $sharedDirectoryClean, $file)));
			} else {
			    $outputSsh = trim($connection->exec(sprintf("cd %s/releases/%s/%s && ln -s %s/shared/%s %s", $deployTo, $this->newRelease, $prefixDirectory, $deployTo, $sharedDirectoryClean, $file)));
			}
			$this->ckeckReturn($outputSsh);
		    }
		}
	    }
	}
    }

}


