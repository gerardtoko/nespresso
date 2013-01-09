<?php

namespace Nespresso\Test\Mapping\Project\Repository\Task;

use Nespresso\Mapping\Project\Repository\Task\Command;

/*
 * This file is part of Nespresso.
 *
 * (c) Gerard TOKO <gerard.toko@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Description of ConfigValidationTest
 *
 * @author gerardtoko
 */
class CommandTest extends \PHPUnit_Framework_TestCase
{


    public function testMapping()
    {
	$project = new Command();

	$project->setCommand("rm -rf app/cache/*");
	$this->assertEquals($project->getCommand(), "rm -rf app/cache/*");
    }

}


