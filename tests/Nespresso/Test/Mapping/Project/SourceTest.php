<?php

namespace Nespresso\Test\Mapping\Project;

use Nespresso\Mapping\Project\Source;

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
class SourceTest extends \PHPUnit_Framework_TestCase
{


    public function testMapping()
    {
	$project = new Source();

	$project->setType("git");
	$this->assertEquals($project->getType(), "git");

	$project->setScm("git@github.com:gerardtoko/nespresso.git");
	$this->assertEquals($project->getScm(), "git@github.com:gerardtoko/nespresso.git");

	$project->setType("mercurial");
	$this->assertEquals($project->getType(), "mercurial");

	$project->setScm("git@github.com:gerardtoko/nespresso.hg");
	$this->assertEquals($project->getScm(), "git@github.com:gerardtoko/nespresso.hg");
    }

}


