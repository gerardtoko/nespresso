<?php

/*
 * This file is part of Nespresso.
 *
 * (c) Gerard TOKO <gerard.toko@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nespresso\Source;

/**
 *
 * @author gerardtoko
 */
interface SourceInterface
{


    /**
     * 
     * @return type
     */
    public function cloneScmCommand();


    /**
     * 
     * @param type $commit
     * @return boolean
     */
    public function hasCommitCommand($commit);


    /**
     * 
     * @param type $tag
     * @return boolean
     */
    public function hasTagCommand($tag);


    /**
     * 
     * @param type $branch
     * @return boolean
     */
    public function hasBranchCommand($branch);


    /**
     * 
     * @param type $commit
     * @param type $type
     */
    public function checkoutCommit($commit);


    /**
     * 
     * @param type $tag
     * @param type $type
     */
    public function checkoutTag($tag);


    /**
     * 
     * @param type $branch
     * @param type $type
     */
    public function checkoutBranch($branch);


    /**
     * 
     * @param type $commit
     * @param type $type
     */
    public function getLastCommit();


    /**
     * 
     * @return type
     */
    public function getExclude();

}


