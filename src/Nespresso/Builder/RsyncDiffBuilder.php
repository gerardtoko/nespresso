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
use Nespresso\Builder\RsyncBuilder;

/**
 * Description of Deploy
 *
 * @author gerardtoko
 */
class RsyncBuilder extends RsyncBuilder implements BuilderInterface
{

    protected $container;
    protected $repository;
    protected $releaseId;


    public function __construct($container, $repository, $release)
    {
	$this->container = $container;
	$this->repository = $repository;
	$this->releaseId = $release;
    }


    /**
     * 
     * @return \Nespresso\Mapping\Config
     * @throws \Exception
     */
    public function build()
    {
	$manager = $this->container->get("nespresso.manager");
	$rsync = sprintf("rsync -%s -e'ssh -p %s'", $manager->getConfig()->getOptionRsyncDiff(), $this->repository->getPort());
	$repoSource = sprintf("%s/", $manager->getSource()->getLocal());

	$repoRemote = sprintf("%s@%s:%s/releases/%s/", $this->repository->getUser(), $this->repository->getDomain(), $this->repository->getDeployTo(), $this->releaseId);
	return sprintf("%s %s %s %s", $rsync, $this->getExclude(), $repoSource, $repoRemote);
    }

}