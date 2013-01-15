<?php

/*
 * This file is part of Nespresso.
 *
 * (c) Gerard TOKO <gerard.toko@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nespresso\Test;

use Nespresso\IO;

class IOTest extends \PHPUnit_Framework_TestCase
{


    public function testIO()
    {
	$IO = new IO();
	$IO->init("input", "output");
	$this->assertEquals($IO->input(), "input");
	$this->assertEquals($IO->output(), "output");
    }

}