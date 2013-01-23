<?php

/*
 * This file is part of Nespresso.
 *
 * (c) Gerard TOKO <gerard.toko@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nespresso;

use Nespresso\Builder\RsyncDiffBuilder;
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
    protected $release;


    /**
     * 
     * @param type $container
     * @param type $output
     * @param type $releaseId
     */
    public function __construct($container, $releaseId = null)
    {
	$this->container = $container;
	$this->output = $container->get("IO")->output();
	$this->release = $releaseId;
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

	foreach ($repositories as $repository) {
	    $rsyncDeployBuilder = new RsyncDeployBuilder($this->container, $repository, $this->release);
	    $command = $rsyncDeployBuilder->build();
	    $name = $repository->getName();
	    $this->output->writeln("<comment>Deployment on</comment> <comment>$name</comment>");
	    exec(sprintf("%s 2>%s/nespresso.log", $command, $tmp), $outputExec, $code);
	    $this->ckeckReturn($code);
	}
	return true;
    }


    /**
     * 
     * @return boolean
     */
    public function diff($controller)
    {
	$manager = $this->container->get("nespresso.manager");
	$repositories = $manager->getProject()->getRepositories();
	$tmp = $manager->getConfig()->getTmp();
	$code = null;
	$output = null;

	foreach ($repositories as $repository) {

	    $this->release = $controller->getLastRelease($repository);
	    $this->output->writeln(sprintf("diff compare with <info>%s</info>", $this->release));

	    $rsyncDeployBuilder = new RsyncDiffBuilder($this->container, $repository, $this->release);
	    $command = $rsyncDeployBuilder->build();

	    $name = $repository->getName();
	    $this->output->writeln("<comment>Diff on</comment> <info>$name</info><comment></comment>");
	    exec(sprintf("%s 2>%s/nespresso.log", $command, $tmp), $output, $code);
	    $this->ckeckReturn($code);

	    foreach ($output as $value) {
		$this->output->writeln($value, 0);
		//if (preg_match("#^deleting#", $value) && !preg_match("#\/$#", $value)) {
		//    $this->output->writeln(substr($value, 9));
		//}
	    }
	}
	return true;
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
	    $manager->getSource()->removeScm();
	    throw new \Exception("Error Rsync processing code($code) \n $log");
	}
    }

}