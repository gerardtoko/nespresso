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
use Nespresso\Controller\Controller as BaseController;

/**
 * Description of SharedController
 *
 * @author gerardtoko
 */
class SharedController extends BaseController implements ControllerInterface
{

    protected $container;
    protected $output;
    protected $releaseId;
    protected $performanceShared;


    public function __construct($container, $output, $releaseId)
    {
	$this->container = $container;
	$this->output = $output;
	$this->releaseId = $releaseId;
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

		$connection = $this->getConnection($repository);
		$this->output->writeln(sprintf("Control shared of repository <info>%s</info> [<comment>Release:%s</comment>]", $repository->getName(), $this->releaseId));
		$deployTo = $repository->getDeployTo();

		// control releases directory
		$outputSsh = trim($connection->exec(sprintf("cd %s/shared", $deployTo)));
		if ($this->isError($outputSsh)) {

		    if (!$this->performanceShared) {
			$this->output->writeln(sprintf("<comment>For the reasons of performance, used the shared directory</comment>", $repository->getName()));
			$this->performanceShared = TRUE;
		    }

		    $outputSsh = trim($connection->exec(sprintf("mkdir -p %s/shared", $deployTo)));
		    $this->isError($outputSsh);
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
			if ($this->isError($outputSsh)) {
			    $outputSsh = trim($connection->exec(sprintf("mkdir -p %s/shared/%s", $deployTo, $sharedDirectoryClean)));
			    $this->isError($outputSsh);
			}

			if ($prefixDirectory != FALSE) {
			    //check sub directory
			    $outputSsh = trim($connection->exec(sprintf("cd %s/releases/%s/%s", $deployTo, $this->releaseId, $prefixDirectory)));
			    if ($this->isError($outputSsh)) {
				$outputSsh = trim($connection->exec(sprintf("mkdir -p %s/releases/%s/%s", $deployTo, $this->releaseId, $prefixDirectory)));
				$this->isError($outputSsh);
			    }
			}

			$outputSsh = trim($connection->exec(sprintf("rm -rf %s/releases/%s/%s", $deployTo, $this->releaseId, $sharedDirectoryClean)));
			if (!$this->isError($outputSsh)) {
			    //create symbolink
			    if ($prefixDirectory == NULL) {
				$outputSsh = trim($connection->exec(sprintf("cd %s/releases/%s && ln -s %s/shared/%s %s", $deployTo, $this->releaseId, $deployTo, $sharedDirectoryClean, $directory)));
			    } else {
				$outputSsh = trim($connection->exec(sprintf("cd %s/releases/%s/%s && ln -s %s/shared/%s %s", $deployTo, $this->releaseId, $prefixDirectory, $deployTo, $sharedDirectoryClean, $directory)));
			    }
			    $this->isError($outputSsh);
			}
		    }
		}
	    }
	}
    }

}