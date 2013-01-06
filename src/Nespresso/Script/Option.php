<?php

/*
 * This file is part of Composer.
 *
 * (c) Gerard TOKO <gerard.toko@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nespresso\Script;

/**
 * Description of Key
 *
 * @author gerardtoko
 */
class Option
{

    protected $user;
    protected $key;
    protected $port;
    protected $tmp;



    /**
     * 
     * @param type $user
     * @return \Nespresso\Script\Option
     */
    public function setUser($user)
    {
	$this->user = $user;
	return $this;
    }


    /**
     * 
     * @return type
     */
    public function getUser()
    {
	return $this->user;
    }


    /**
     * 
     * @param type $key
     * @return \Nespresso\Script\Option
     */
    public function setKey($key)
    {
	$this->key = $key;
	return $this;
    }


    /**
     * 
     * @return type
     */
    public function getKey()
    {
	return $this->key;
    }


    /**
     * 
     * @param type $tmp
     * @return \Nespresso\Script\Option
     */
    public function setTmp($tmp)
    {
	$this->tmp = $tmp;
	return $this;
    }


    /**
     * 
     * @return type
     */
    public function getTmp()
    {
	return $this->tmp;
    }


    /**
     * 
     * @param type $port
     * @return \Nespresso\Script\Option
     */
    public function setPort($port = "22")
    {
	$this->port = $port;
	return $this;
    }


    /**
     * 
     * @return type
     */
    public function getPort()
    {
	return $this->port;
    }


    

}