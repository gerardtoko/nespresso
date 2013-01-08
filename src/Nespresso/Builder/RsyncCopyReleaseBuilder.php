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
    protected $lastReleaseId;


    public function __construct($container, $repository, $releaseId, $lastReleaseId)
    {
	$this->container = $container;
	$this->repository = $repository;
	$this->releaseId = $releaseId;
	$this->lastReleaseId = $lastReleaseId;
    }


    /**
     * 
     * @return \Nespresso\Mapping\Config
     * @throws \Exception
     */
    public function build()
    {
	$subCommand = sprintf("%s/releases/%s/ %s/releases/%s/", $this->repository->getDeployTo(), $this->lastReleaseId, $this->repository->getDeployTo(), $this->releaseId);
	$project = $this->container->get("nespresso.manager")->getProject();
	
	if ($project->hasCache()) {
	    $cache = $project->getCache();
	    $command = sprintf('rsync -az --exclude="%s" %s', $cache, $subCommand);
	} else {
	    $command = sprintf('rsync -az %s', $subCommand);
	}
	
	return $command;
    }

}