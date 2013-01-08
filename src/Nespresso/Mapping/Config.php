<?php

/*
 * This file is part of Nespresso.
 *
 * (c) Gerard TOKO <gerard.toko@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nespresso\Mapping;

/**
 * Description of Key
 *
 * @author gerardtoko
 */
class Config
{

    protected $key;
    protected $tmp;
    protected $optionRsync;


    /**
     * 
     * @param type $key
     * @return \Nespresso\Mapping\Config
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
     * @return \Nespresso\Mapping\Config
     */
    public function setTmp($tmp = "/tmp")
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
     * @param type $tmp
     * @return \Nespresso\Mapping\Config
     */
    public function setOptionRsync($optionRsync = "az")
    {
	$this->optionRsync = $optionRsync;
	return $this;
    }


    /**
     * 
     * @return type
     */
    public function getOptionRsync()
    {
	return $this->optionRsync;
    }

}