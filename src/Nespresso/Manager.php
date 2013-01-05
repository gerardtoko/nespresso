<?php

/*
 * This file is part of Composer.
 *
 * (c) Gerard TOKO <gerard.toko@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nespresso;

/**
 * Description of Manager
 *
 * @author gerardtoko
 */
class Manager
{

    protected $project;
    private $connection;


    public function __construct()
    {

	$option_file = $this->getOption();
	if (!file_exists($option_file)) {
	    $basename = basename($option_file);
	    throw new \Exception("schema $basename no exist");
	}

	$option_json = file_get_contents($option_file);
	$obj_json = json_decode($option_json);

exit;
	$ssh = new \Net_SSH2('localhost');
	exit;
	$key = new \Crypt_RSA();
	$key->loadKey(file_get_contents($obj_json->key));
	if (!$ssh->login($obj_json->user, $key)) {
	    exit('Login Failed');
	}

	echo $ssh->exec('pwd');
	echo $ssh->exec('ls -la');
	print_r($obj_json);

	exit;
    }


    /**
     * 
     * @param type $project
     * @return \Nespresso\Manager
     */
    public function setProject($project)
    {
	$this->project = $project;
	return $this;
    }


    /**
     * 
     * @return type
     */
    public function getProject()
    {
	return $this->project;
    }


    /**
     * 
     * @return type
     */
    public function getOption()
    {
	return __DIR__ . '/../../tests/option.json';
    }

}