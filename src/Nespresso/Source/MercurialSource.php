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

use Nespresso\Mercurial;

/**
 * Description of Mercurial
 *
 * @author gerardtoko
 */
class Mercurial implements SourceInterface
{


    /**
     * 
     * @return type
     */
    public function cloneScmCommand($local)
    {
	return sprintf(Mercurial::CLONE_SCM, $local);
    }


    /**
     * 
     * @param type $commit
     * @return boolean
     */
    public function hasCommitCommand($commit)
    {
	return sprintf(Mercurial::HAS_COMMIT, $commit);
    }


    /**
     * 
     * @param type $tag
     * @return boolean
     */
    public function hasTagCommand($tag)
    {
	return sprintf(Mercurial::HAS_TAG, $tag);
    }


    /**
     * 
     * @param type $branch
     * @return boolean
     */
    public function hasBranchCommand($branch)
    {
	return sprintf(Mercurial::HAS_BRANCH, $branch);
    }


    /**
     * 
     * @param type $commit
     * @param type $type
     */
    public function checkoutCommit($commit)
    {
	return sprintf(Mercurial::CHECKOUT_COMMIT, $commit);
    }


    /**
     * 
     * @param type $tag
     * @param type $type
     */
    public function checkoutTag($tag)
    {
	return sprintf(Mercurial::CHECKOUT_TAG, $tag);
    }


    /**
     * 
     * @param type $branch
     * @param type $type
     */
    public function checkoutBranch($branch)
    {
	return sprintf(Mercurial::CHECKOUT_BRANCH, $branch);
    }


    /**
     * 
     * @param type $commit
     * @param type $type
     */
    public function getLastCommit()
    {
	return sprintf(Mercurial::LAST_COMMIT);
    }


    /**
     * 
     * @return type
     */
    public function getExclude()
    {
	return Mercurial::EXCLUDE;
    }

}