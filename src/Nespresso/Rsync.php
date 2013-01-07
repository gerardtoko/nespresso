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


    
    /**
     * 
     * @return boolean
     */
    public function deploy()
    {

	$manager = $this->container->get("nespresso.manager");
	$repositories = $manager->getProject()->getRepositories();
	$connection = null;

	$this->output->writeln("Control repositories");
	foreach ($repositories as $repository) {
	    $connection = $this->getConnection($repository);
	    $rsyncCopyReleaseBuilder = new RsyncCopyReleaseBuilder($this->container, $repository, $this->releaseId);
	    $command = $rsyncCopyReleaseBuilder->build();
	    
	    $this->output->writeln("<comment>Deployement on</comment> <info>$repository</info><comment>...</comment>");
	    $outputSsh = trim($connection->exec($command));
	    $this->isError($outputSsh);
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