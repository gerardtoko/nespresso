<?php

/*
 * This file is part of Composer.
 *
 * (c) Gerard TOKO <gerard.toko@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nespresso\Manager;

/**
 *
 * @author gerardtoko
 */
interface ManagerInterface
{


    public function getOption();


    public function setOption($option);


    public function getProject();


    public function setProject($project);

}