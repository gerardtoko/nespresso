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
class RsyncDeployBuilder extends RsyncBuilder implements BuilderInterface
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
	return $this->getCommandBuild($manager->getConfig()->getOptionRsyncDeploy());
    }

}