<?php

/*
 * This file is part of Nespresso.
 *
 * (c) Gerard TOKO <gerard.toko@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nespresso\Builder;

/**
 * Description of Deploy
 *
 * @author gerardtoko
 */
class RsyncBuilder
{

    protected $container;
    protected $repository;
    protected $releaseId;


    public function __construct($container, $repository, $releaseId)
    {
	$this->container = $container;
	$this->repository = $repository;
	$this->releaseId = $releaseId;
    }


    /**
     * 
     * @return type
     */
    public function getExclude()
    {
	$manager = $this->container->get("nespresso.manager");
	$project = $manager->getProject();
	$source = $manager->getSource();
	$excludes = " --exclude='.git' ";
	$excludes .= " --exclude='.gitignore' ";
	$excludes .= " --exclude='.hgtignore' ";
	$excludes .= " --exclude='nespresso.lock' ";

	if ($manager->getProject()->hasSharedDirectory()) {
	    $shared = $manager->getProject()->getSharedDirectory();
	    foreach ($shared as $value) {
		$excludes .= sprintf(" --exclude='%s' ", $value);
	    }
	}


	if ($manager->getProject()->hasSharedFile()) {
	    $shared = $manager->getProject()->getSharedFile();
	    foreach ($shared as $value) {
		$excludes .= sprintf(" --exclude='%s' ", $value);
	    }
	}

	if ($project->hasCache()) {
	    $excludes .= sprintf(" --exclude='%s' ", $project->getCache());
	}

	if ($source->hasExclude()) {
	    $ExcludedArray = $source->getExclude();
	    foreach ($ExcludedArray as $value) {
		$excludes .= sprintf(" --exclude='%s' ", $value);
	    }
	}
	return $excludes;
    }


    /**
     * 
     * @param type $options
     * @return type
     */
    public function getCommandBuild($options)
    {
	$manager = $this->container->get("nespresso.manager");
	$rsync = sprintf("rsync -%s -e'ssh -p %s'", $options, $this->repository->getPort());
	$repoSource = sprintf("%s/", $manager->getSource()->getLocal());

	$repoRemote = sprintf("%s@%s:%s/releases/%s/", $this->repository->getUser(), $this->repository->getDomain(), $this->repository->getDeployTo(), $this->releaseId);
	return sprintf("%s %s %s %s", $rsync, $this->getExclude(), $repoSource, $repoRemote);
    }

}