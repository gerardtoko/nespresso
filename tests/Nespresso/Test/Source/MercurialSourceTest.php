<?php

/*
 * This file is part of Nespresso.
 *
 * (c) Gerard TOKO <gerard.toko@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nespresso\Test\Source;

use Nespresso\Mercurial;
use Nespresso\Source\MercurialSource;

class MercurialTest extends \PHPUnit_Framework_TestCase
{


//    const SOURCE = "Mercurial";
//    const CLONE_SCM = "hg clone %s %s";
//    const HAS_COMMIT = "hg show %s";
//    const HAS_TAG = "hg ls-tree %s";
//    const HAS_BRANCH = "hg show-branch origin/%s";
//    const CHECKOUT_COMMIT = "hg checkout %s";
//    const CHECKOUT_TAG = "hg checkout %s";
//    const CHECKOUT_BRANCH = "hg checkout %s";
//    const EXCLUDE = ".hgignore";
//    const LAST_COMMIT = 'hg log -1 --format="%H"';


    public function testMercurial()
    {
	$hgSource = new MercurialSource();
	$this->assertEquals(Mercurial::SOURCE, "Mercurial");
	$this->assertEquals($hgSource->cloneScmCommand("hg@hghub.com:gerardtoko/nespresso-test.hg", "2sf092s"), "hg clone hg@hghub.com:gerardtoko/nespresso-test.hg 2sf092s");
	$this->assertEquals($hgSource->checkoutBranch("master"), "hg checkout master");
	$this->assertEquals($hgSource->checkoutCommit("2sf092s3344q24"), "hg checkout 2sf092s3344q24");
	$this->assertEquals($hgSource->checkoutTag("v2.1"), "hg checkout v2.1");
	$this->assertEquals($hgSource->hasBranchCommand("master"), "hg show master");
	$this->assertEquals($hgSource->hasCommitCommand("2sf092s3344q24"), "hg show 2sf092s3344q24");
	$this->assertEquals($hgSource->hasTagCommand("v2.1"), "hg ls-tree v2.1");
	$this->assertEquals($hgSource->getExclude(), ".hggnore");
	$this->assertEquals($hgSource->getLastCommit(), 'hg log -1 --format="%H"');
    }

}