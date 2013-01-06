<?php

/*
 * This file is part of Composer.
 *
 * (c) Gerard TOKO <gerard.toko@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nespresso\Controller;

use Nespresso\Controller\ControllerInterface;

/**
 * Description of RepositoryControl
 *
 * @author gerardtoko
 */
class RepositoryController implements ControllerInterface
{

    protected $container;
    protected $output;

    public function __construct($container, $output)
    {
	$this->container = $container;
	$this->output = $output;
    }


    public function action()
    {
	$manager = $this->container->get("nespresso.manager");
	$repositories = $manager->getProject()->getRepositories();
	
	$this->output->writeln("<info>Control repositories</info>");
	foreach ($repositories as $repository) {
	    
	}
	$this->output->writeln("<info>project is correct!</info>");

    }

}


