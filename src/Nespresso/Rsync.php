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

use Nespresso\Builder\RsyncDeployBuilder;
/**
 * Description of Task
 *
 * @author gerardtoko
 */
class Rsync
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


    /**
     * 
     * @return boolean
     */
    public function deploy()
    {
	$manager = $this->container->get("nespresso.manager");
	$repositories = $manager->getProject()->getRepositories();
	$tmp = $manager->getConfig()->getTmp();
	$code = null;
	$outputExec = null;
	
	$this->output->writeln("Control repositories");
	foreach ($repositories as $repository) {
	    $rsyncReleaseDeploy = new RsyncDeployBuilder($this->container, $repository, $this->releaseId);
	    $command = $rsyncReleaseDeploy->build();
	    $name = $repository->getName();
	    $this->output->writeln("<comment>Deployement on</comment> <info>$name</info><comment>...</comment>");
	    exec(sprintf("%s 2>%s/nespresso.log", $command, $tmp), $outputExec, $code);
	    $this->ckeckReturn($code);
	}
	return true;
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
     * @param type $code
     * @throws \Exception
     */
    protected function ckeckReturn($code)
    {
	if ($code) {

	    $manager = $this->container->get("nespresso.manager");
	    $tmp = $manager->getConfig()->getTmp();
	    $log = file_get_contents($tmp . "/nespresso.log");

	    if ($this->repositoryGit) {
		$this->removeCloneGit();
	    }
	    throw new \Exception("Error Rsync processing... code($code) \n $log");
	}
    }

}