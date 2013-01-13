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
    protected $optionRsyncDeploy;
    protected $optionRsyncDiff;


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
    public function setOptionRsyncDeploy($optionRsyncDeploy = "az")
    {
	$this->optionRsyncDeploy = $optionRsyncDeploy;
	return $this;
    }


    /**
     * 
     * @return type
     */
    public function getOptionRsyncDeploy()
    {
	return $this->optionRsyncDeploy;
    }


    /**
     * 
     * @param type $tmp
     * @return \Nespresso\Mapping\Config
     */
    public function setOptionRsyncDiff($optionRsyncDiff = "avhn")
    {
	$this->optionRsyncDiff = $optionRsyncDiff;
	return $this;
    }


    /**
     * 
     * @return type
     */
    public function getOptionRsyncDiff()
    {
	return $this->optionRsyncDiff;
    }

}