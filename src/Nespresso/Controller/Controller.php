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

use Nespresso\Manager\Connection;

/**
 * Description of Controller
 *
 * @author gerardtoko
 */
class Controller
{

    protected $container;
    protected $output;


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
     * @param type $output
     * @return boolean
     */
    protected function ckeckReturn($output)
    {
	if ($output) {	    	    
	    $this->output->writeln("<error>Error Ssh processing $output</error>");
	    return TRUE;
	} else {
	    return FALSE;
	}
    }

}