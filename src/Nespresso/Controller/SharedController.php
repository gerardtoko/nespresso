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
    public function __construct($container, $output, $releaseId)
    {
	$this->container = $container;
	$this->output = $output;
	$this->newRelease = $releaseId;
    }


    public function controlAction()
    {
	$manager = $this->container->get("nespresso.manager");
	$repositories = $manager->getProject()->getRepositories();
	$connection = null;

	if ($manager->getProject()->isShared()) {

	    $shared = $manager->getProject()->getShared();

	    $this->output->writeln("Control shared");
	    foreach ($repositories as $repository) {

		$name = $repository->getName();
		$newRelease = $this->newRelease;
		$connection = $this->getConnection($repository);
		$this->output->writeln("Control shared of repository <info>$name</info> [<comment>Release:$newRelease</comment>]");
		$deployTo = $repository->getDeployTo();

		// control releases directory
		$outputSsh = trim($connection->exec("cd $deployTo/shared"));
		if ($this->ckeckReturn($outputSsh)) {

		    if (!$this->performanceShared) {
			$this->output->writeln("<comment>For the reasons of performance, used the shared directory</comment>");
			$this->performanceShared = TRUE;
		    }

		    $outputSsh = trim($connection->exec("mkdir -p $deployTo/shared"));
		    $this->ckeckReturn($outputSsh);
		}

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
			    $outputSsh = trim($connection->exec(sprintf("mkdir -p %s/releases/%s/%s", $deployTo, $newRelease, $prefixDirectory)));
			    $this->ckeckReturn($outputSsh);
			}

			$outputSsh = trim($connection->exec(sprintf("rm -rf %s/releases/%s/%s", $deployTo, $newRelease, $sharedDirectoryClean)));
			if (!$this->ckeckReturn($outputSsh)) {
			    //create symbolink
			    if ($prefixDirectory == NULL) {
				$outputSsh = trim($connection->exec(sprintf("cd %s/releases/%s && ln -s %s/shared/%s %s", $deployTo, $newRelease, $deployTo, $sharedDirectoryClean, $directory)));
			    } else {
				$outputSsh = trim($connection->exec(sprintf("cd %s/releases/%s/%s && ln -s %s/shared/%s %s", $deployTo, $newRelease, $prefixDirectory, $deployTo, $sharedDirectoryClean, $directory)));
			    }
			    $this->ckeckReturn($outputSsh);
			}
		    }
		}
	    }
	}
    }

}