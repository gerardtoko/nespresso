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
    protected $release;
    protected $performanceShared;


    /**
     * 
     * @param type $container
     * @param type $output
     * @param type $release
     */
    public function __construct($container, $release)
    {
	$this->container = $container;
	$this->output = $container->get("IO")->output();
	$this->release = $release;
    }


    public function controlAction()
    {
	$manager = $this->container->get("nespresso.manager");
	$repositories = $manager->getProject()->getRepositories();


	foreach ($repositories as $repository) {
	    $name = $repository->getName();
	    $connection = $this->getConnection($repository);
	    $this->output->writeln(sprintf("Control shared of repository <info>%s</info> [<comment>Release:%s</comment>]", $name, $this->release));
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


    public function setupAction()
    {
	$manager = $this->container->get("nespresso.manager");
	$repositories = $manager->getProject()->getRepositories();

	foreach ($repositories as $repository) {
	    $connection = $this->getConnection($repository);
	    $deployTo = $repository->getDeployTo();

	    $outputSsh = trim($connection->exec(sprintf("mkdir -p %s/shared", $deployTo)));
	    $this->ckeckReturn($outputSsh);

	    if ($manager->getProject()->hasSharedFile()) {
		$shared = $manager->getProject()->getSharedFile();
		$this->setupSharedFile($repositories, $shared);
	    }

	    if ($manager->getProject()->hasSharedDirectory()) {

		$shared = $manager->getProject()->getSharedDirectory();
		$this->setupSharedDirectory($repositories, $shared);
	    }
	}
    }


    /**
     * 
     * @param type $repositories
     * @param type $shared
     */
    public function setupSharedDirectory($repositories, $shared)
    {

	foreach ($repositories as $repository) {

	    $connection = $this->getConnection($repository);
	    $deployTo = $repository->getDeployTo();

	    foreach ($shared as $sharedDirectory) {

		$sharedDirectoryClean = trim($sharedDirectory, "/");
		if (!empty($sharedDirectoryClean)) {

		    $pos = strrpos($sharedDirectoryClean, '/');

		    $directory = $pos == FALSE ? $sharedDirectoryClean : substr($sharedDirectoryClean, $pos + 1);
		    $prefixDirectory = $pos == FALSE ? NULL : substr($sharedDirectoryClean, 0, $pos);

		    // control releases directory
		    $output = trim($connection->exec(sprintf("mkdir -p %s/shared/%s", $deployTo, $sharedDirectoryClean)));
		    $this->ckeckReturn($output);


		    if ($prefixDirectory != FALSE) {
			//check sub directory
			$output = trim($connection->exec(sprintf("mkdir -p %s/releases/%s/%s", $deployTo, $this->release, $prefixDirectory)));
			$this->ckeckReturn($output);
		    }

		    //create symbolink
		    if ($prefixDirectory == NULL) {
			$output = trim($connection->exec(sprintf("cd %s/releases/%s && ln -s %s/shared/%s %s", $deployTo, $this->release, $deployTo, $sharedDirectoryClean, $directory)));
		    } else {
			$output = trim($connection->exec(sprintf("cd %s/releases/%s/%s && ln -s %s/shared/%s %s", $deployTo, $this->release, $prefixDirectory, $deployTo, $sharedDirectoryClean, $directory)));
		    }
		    $this->ckeckReturn($output);
		}
	    }
	}
    }


    /**
     * 
     * @param type $repositories
     * @param type $shared
     */
    public function sharedDirectory($repositories, $shared)
    {

	foreach ($repositories as $repository) {

	    $name = $repository->getName();
	    $connection = $this->getConnection($repository);
	    $this->output->writeln(sprintf("Control shared of repository <info>%s</info> [<comment>Release:%s</comment>]", $name, $this->release));
	    $deployTo = $repository->getDeployTo();

	    //create directory shared
	    foreach ($shared as $sharedDirectory) {

		$sharedDirectoryClean = trim($sharedDirectory, "/");
		if (!empty($sharedDirectoryClean)) {

		    $pos = strrpos($sharedDirectoryClean, '/');

		    $directory = $pos == FALSE ? $sharedDirectoryClean : substr($sharedDirectoryClean, $pos + 1);
		    $prefixDirectory = $pos == FALSE ? NULL : substr($sharedDirectoryClean, 0, $pos);

		    // control releases directory
		    $output = trim($connection->exec(sprintf("cd %s/shared/%s", $deployTo, $sharedDirectoryClean)));
		    if (!$this->ckeckReturn($output)) {
			$output = trim($connection->exec(sprintf("mkdir -p %s/shared/%s", $deployTo, $sharedDirectoryClean)));
			$this->ckeckReturn($output);
		    }

		    if ($prefixDirectory != FALSE) {
			//check sub directory
			$output = trim($connection->exec(sprintf("mkdir -p %s/releases/%s/%s", $deployTo, $this->release, $prefixDirectory)));
			$this->ckeckReturn($output);
		    }

		    $output = trim($connection->exec(sprintf("rm -rf %s/releases/%s/%s", $deployTo, $this->release, $sharedDirectoryClean)));
		    if (!$this->ckeckReturn($output)) {
			//create symbolink
			if ($prefixDirectory == NULL) {
			    $output = trim($connection->exec(sprintf("cd %s/releases/%s && ln -s %s/shared/%s %s", $deployTo, $this->release, $deployTo, $sharedDirectoryClean, $directory)));
			} else {
			    $output = trim($connection->exec(sprintf("cd %s/releases/%s/%s && ln -s %s/shared/%s %s", $deployTo, $this->release, $prefixDirectory, $deployTo, $sharedDirectoryClean, $directory)));
			}
			$this->ckeckReturn($output);
		    }
		}
	    }
	}
    }


    /**
     * 
     * @param type $repositories
     * @param type $files
     */
    public function sharedFile($repositories, $files)
    {

	foreach ($repositories as $repository) {

	    $connection = $this->getConnection($repository);
	    $this->output->writeln(sprintf("Control shared of repository <info>%s</info> [<comment>Release:%s</comment>]", $name, $this->release));
	    $deployTo = $repository->getDeployTo();

	    //create directory shared
	    foreach ($files as $file) {

		$sharedfileClean = trim($file, "/");
		if (!empty($sharedfileClean)) {

		    $pos = strrpos($sharedfileClean, '/');

		    $file = $pos == FALSE ? $sharedfileClean : substr($sharedfileClean, $pos + 1);
		    $prefixFile = $pos == FALSE ? NULL : substr($sharedfileClean, 0, $pos);

		    if ($prefixFile != NULL) {
			$output = trim($connection->exec(sprintf("mkdir -p %s/releases/%s/%s", $deployTo, $this->release, $prefixFile)));
			$this->ckeckReturn($output);

			$output = trim($connection->exec(sprintf("mkdir -p %s/shared/%s", $deployTo, $prefixFile)));
			$this->ckeckReturn($output);
		    }

		    $output = trim($connection->exec(sprintf("touch %s/shared/%s", $deployTo, $sharedfileClean)));
		    if ($this->ckeckReturn($output)) {
			$output = trim($connection->exec(sprintf("touch %s/shared/%s", $deployTo, $sharedfileClean)));
			$this->ckeckReturn($output);
		    }

		    $output = trim($connection->exec(sprintf("rm -rf %s/releases/%s/%s", $deployTo, $this->release, $sharedfileClean)));
		    if (!$this->ckeckReturn($output)) {
			if ($prefixFile == NULL) {
			    $output = trim($connection->exec(sprintf("cd %s/releases/%s && ln -s %s/shared/%s %s", $deployTo, $this->release, $deployTo, $sharedfileClean, $file)));
			} else {
			    $output = trim($connection->exec(sprintf("cd %s/releases/%s/%s && ln -s %s/shared/%s %s", $deployTo, $this->release, $prefixFile, $deployTo, $sharedfileClean, $file)));
			}
			$this->ckeckReturn($output);
		    }
		}
	    }
	}
    }


    /**
     * 
     * @param type $repositories
     * @param type $files
     */
    public function setupSharedFile($repositories, $files)
    {

	foreach ($repositories as $repository) {

	    $connection = $this->getConnection($repository);
	    $deployTo = $repository->getDeployTo();

	    //create directory shared
	    foreach ($files as $file) {

		$sharedfileClean = trim($file, "/");
		if (!empty($sharedfileClean)) {

		    $pos = strrpos($sharedfileClean, '/');

		    $file = $pos == FALSE ? $sharedfileClean : substr($sharedfileClean, $pos + 1);
		    $prefixFile = $pos == FALSE ? NULL : substr($sharedfileClean, 0, $pos);

		    if ($prefixFile != NULL) {
			$output = trim($connection->exec(sprintf("mkdir -p %s/releases/%s/%s", $deployTo, $this->release, $prefixFile)));
			$this->ckeckReturn($output);

			$output = trim($connection->exec(sprintf("mkdir -p %s/shared/%s", $deployTo, $prefixFile)));
			$this->ckeckReturn($output);
		    }

		    $output = trim($connection->exec(sprintf("touch %s/shared/%s", $deployTo, $sharedfileClean)));
		    $this->ckeckReturn($output);

		    if ($prefixFile == NULL) {
			$output = trim($connection->exec(sprintf("cd %s/releases/%s && ln -s %s/shared/%s %s", $deployTo, $this->release, $deployTo, $sharedfileClean, $file)));
		    } else {
			$output = trim($connection->exec(sprintf("cd %s/releases/%s/%s && ln -s %s/shared/%s %s", $deployTo, $this->release, $prefixFile, $deployTo, $sharedfileClean, $file)));
		    }
		    $this->ckeckReturn($output);
		}
	    }
	}
    }

}


