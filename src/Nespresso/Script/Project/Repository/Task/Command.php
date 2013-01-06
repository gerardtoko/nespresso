<?php

/*
 * This file is part of Composer.
 *
 * (c) Gerard TOKO <gerard.toko@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nespresso\Script\Project\Repository\Task;

/**
 * Description of Repository
 *
 * @author gerardtoko
 */
class Command
{

    protected $command;


    /**
     * 
     * @param type $command
     * @return \Nespresso\Project\Repository\Task
     */
    public function setCommand($command)
    {
	$this->command = $command;
	return $this;
    }


    /**
     * 
     * @return type
     */
    public function getCommand()
    {
	return $this->command;
    }
}