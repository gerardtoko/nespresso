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

use Nespresso\Source\SourceInterface;
use Nespresso\Git;

/**
 * Description of Git
 *
 * @author gerardtoko
 */
class Git implements SourceInterface
{


    /**
     * 
     * @return type
     */
    public function cloneScmCommand($local)
    {
	return sprintf(Git::CLONE_SCM, $local);
    }


    /**
     * 
     * @param type $commit
     * @return boolean
     */
    public function hasCommitCommand($commit)
    {
	return sprintf(Git::HAS_COMMIT, $commit);
    }


    /**
     * 
     * @param type $tag
     * @return boolean
     */
    public function hasTagCommand($tag)
    {
	return sprintf(Git::HAS_TAG, $tag);
    }


    /**
     * 
     * @param type $branch
     * @return boolean
     */
    public function hasBranchCommand($branch)
    {
	return sprintf(Git::HAS_BRANCH, $branch);
    }


    /**
     * 
     * @param type $commit
     * @param type $type
     */
    public function checkoutCommit($commit)
    {
	return sprintf(Git::CHECKOUT_COMMIT, $commit);
    }


    /**
     * 
     * @param type $tag
     * @param type $type
     */
    public function checkoutTag($tag)
    {
	return sprintf(Git::CHECKOUT_TAG, $tag);
    }


    /**
     * 
     * @param type $branch
     * @param type $type
     */
    public function checkoutBranch($branch)
    {
	return sprintf(Git::CHECKOUT_BRANCH, $branch);
    }


    /**
     * 
     * @param type $commit
     * @param type $type
     */
    public function getLastCommit()
    {
	return sprintf(Git::LAST_COMMIT);
    }


    /**
     * 
     * @return type
     */
    public function getExclude()
    {
	return Git::EXCLUDE;
    }

}