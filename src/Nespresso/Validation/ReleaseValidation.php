<?php

/*
 * This file is part of Nespresso.
 *
 * (c) Gerard TOKO <gerard.toko@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nespresso\Validation;

use Nespresso\Validation\ValidationInterface;

/**
 * Description of Projet
 *
 * @author gerardtoko
 */
class ReleaseValidation implements ValidationInterface
{


    /**
     * 
     * @param type $release
     * @return boolean
     */
    public function valid($release)
    {
	return preg_match("#^[0-9]{2}([-][0-9]{2}){5}$#", $release);
    }

}