<?php

/*
 * This file is part of Nespresso.
 *
 * (c) Gerard TOKO <gerard.toko@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nespresso\Manager;

/**
 * Description of Manager
 *
 * @author gerardtoko
 */
class Connection
{

    private $ssh;


    public function __construct($user, $domain, $port, $key_file, $output = null)
    {

	if (!is_null($output)) {
	    $output->writeln("<comment>Connecting on the server</comment> <info>$domain</info>");
	    $output->writeln("	    - User: <info>$user</info>");
	    $output->writeln("	    - Port: <info>$port</info>");
	}

	try {
	    $ssh = new \Net_SSH2($domain, $port);
	    $key = new \Crypt_RSA();
	    $key->loadKey(file_get_contents($key_file));
	    if (!$ssh->login($user, $key)) {
		throw new \Exception('Login Failed');
	    }
	} catch (\Exception $exc) {
	    throw new \Exception('Login Failed');
	}

	$this->ssh = $ssh;
    }


    /**
     * 
     * @param type $command
     * @return type
     */
    public function exec($command)
    {
	return $this->ssh->exec($command);
    }

}