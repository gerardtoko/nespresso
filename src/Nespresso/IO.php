<?php

/*
 * This file is part of Nespresso.
 *
 * (c) Gerard TOKO <gerard.toko@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nespresso;

/**
 * Description of IO
 *
 * @author gerardtoko
 */
class IO
{

    protected $output;
    protected $input;


    /**
     * 
     * @param type $output
     * @param type $input
     */
    public function init($input, $output)
    {
	$this->output = $output;
	$this->input = $input;
    }


    /**
     * 
     * @return type
     */
    public function input()
    {
	return $this->input;
    }


    /**
     * 
     * @return type
     */
    public function output()
    {
	return $this->output;
    }

}