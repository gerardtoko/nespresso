<?php

/*
 * This file is part of the rdeploy package.
 *
 * (c) Gerard TOKO <gerard.toko@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nespresso\Builder;

use Nespresso\Mapping\Config as ConfigMapping;
use Nespresso\Builder\BuilderInterface;

/**
 * Description of Deploy
 *
 * @author gerardtoko
 */
class RsyncCopyReleaseBuilder implements BuilderInterface
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
     * @return \Nespresso\Mapping\Config
     * @throws \Exception
     */
    public function build()
    {
	$manager = $this->container->get("nespresso.manager");
	$rsync = sprintf('rsync -%s -e ssh', $manager->getConfig()->getOptionRsync());
	$repoGit = sprintf("%s/%s/", $manager->getConfig()->getTmp(), $manager->getProject()->getGit());
	$repoRemote = sprintf("%s@%s:%/releases/%s/", $this->repository->getUser(), $this->repository->getDomain(), $this->repository->getDeployTo(), $this->lastReleaseId);
	return sprintf("%s %s %s %s", $rsync, $this->getExclude(), $repoGit, $repoRemote);
    }


    public function getExclude()
    {
	$manager = $this->container->get("nespresso.manager");
	$excludes = " --exclude='.git' ";
	$excludes .= " --exclude='.gitignore' ";

	if ($manager->getProject()->isShared()) {
	    $shared = $manager->getProject()->getShared();
	    foreach ($shared as $value) {
		$excludes .= sprintf(" --exclude='%s' ", $value);
	    }
	}

	if ($manager->getGit()->hasGitignore()) {
	    $gitignoreArray = $manager->getGit()->getGitignore();
	    foreach ($gitignoreArray as $value) {
		$excludes .= sprintf(" --exclude='%s' ", $value);
	    }
	}
	return $excludes;
    }

}