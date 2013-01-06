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
 * Description of SharedController
 *
 * @author gerardtoko
 */
class SharedController implements ControllerInterface
{

    protected $container;
    protected $output;
    protected $releaseId;


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
	    
	    $this->output->writeln("Control shared");
	    foreach ($repositories as $repository) {

		$connection = $this->getConnection($repository);
		$this->output->writeln(sprintf("Control shared of repository <info>%s</info> [<comment>Release:%s</comment>]", $repository->getName(), $this->releaseId));
		$deployTo = $repository->getDeployTo();

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