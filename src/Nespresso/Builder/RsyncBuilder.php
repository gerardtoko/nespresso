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
class RsyncDeployBuilder implements BuilderInterface
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

	if ($manager->getProject()->hasSharedDirectory()) {
	    $shared = $manager->getProject()->getSharedDirectory();
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

}