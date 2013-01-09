<?php

/*
 * This file is part of Nespresso.
 *
 * (c) Gerard TOKO <gerard.toko@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nespresso\Mapping\Project;

/**
 * Description of Repository
 *
 * @author gerardtoko
 */
class Source
{

    protected $type;
    protected $scm;


    /**
     * 
     * @param type $type
     * @return \Nespresso\Mapping\Project\Source
     */
    public function setType($type)
    {
	$this->type = $type;
	return $this;
    }


    /**
     * 
     * @return type
     */
    public function getType()
    {
	return $this->type;
    }


    /**
     * 
     * @param type $scm
     * @return \Nespresso\Mapping\Project\Source
     */
    public function setScm($scm)
    {
	$this->scm = $scm;
	return $this;
    }


    /**
     * 
     * @return type
     */
    public function getScm()
    {
	return $this->scm;
    }

}