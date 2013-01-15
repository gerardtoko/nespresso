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

use Nespresso\Git;
use Nespresso\Source\GitSource;

class GitSourceTest extends \PHPUnit_Framework_TestCase
{


//    const SOURCE = "Git";
//    const CLONE_SCM = "git clone %s %s";
//    const HAS_COMMIT = "git show %s";
//    const HAS_TAG = "git ls-tree %s";
//    const HAS_BRANCH = "git show-branch origin/%s";
//    const CHECKOUT_COMMIT = "git checkout %s";
//    const CHECKOUT_TAG = "git checkout %s";
//    const CHECKOUT_BRANCH = "git checkout %s";
//    const EXCLUDE = ".gitignore";
//    const LAST_COMMIT = 'git log -1 --format="%H"';


    public function testGit()
    {
	$gitSource = new GitSource();
	$this->assertEquals(Git::SOURCE, "Git");
	$this->assertEquals($gitSource->cloneScmCommand("git@github.com:gerardtoko/nespresso-test.git", "2sf092s"), "git clone git@github.com:gerardtoko/nespresso-test.git 2sf092s");
	$this->assertEquals($gitSource->checkoutBranch("master"), "git checkout master");
	$this->assertEquals($gitSource->checkoutCommit("2sf092s3344q24"), "git checkout 2sf092s3344q24");
	$this->assertEquals($gitSource->checkoutTag("v2.1"), "git checkout v2.1");
	$this->assertEquals($gitSource->hasBranchCommand("master"), "git show-branch origin/master");
	$this->assertEquals($gitSource->hasCommitCommand("2sf092s3344q24"), "git show 2sf092s3344q24");
	$this->assertEquals($gitSource->hasTagCommand("v2.1"), "git ls-tree v2.1");
	$this->assertEquals($gitSource->getExclude(), ".gitignore");
	$this->assertEquals($gitSource->getLastCommit(), 'git log -1 --format="%H"');
    }

}