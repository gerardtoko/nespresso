<?php

namespace Nespresso\Test\Console;

use Nespresso\Console\Application as Application;

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
class ApplicationTest extends \PHPUnit_Framework_TestCase
{


    public function testBuild()
    {
	try {
	    $application = new Application();
	   // $this->assertTrue($application->run());
	} catch (Exception $exc) {
	    
	}
    }

}


