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
    public function cloneScmCommand()
    {
	
    }


    /**
     * 
     * @param type $commit
     * @return boolean
     */
    public function hasCommitCommand($commit, $local)
    {
	
    }


    /**
     * 
     * @param type $tag
     * @return boolean
     */
    public function hasTagCommand($tag, $local)
    {
	
    }


    /**
     * 
     * @param type $branch
     * @return boolean
     */
    public function hasBranchCommand($branch, $local)
    {
	
    }


    /**
     * 
     * @param type $commit
     * @param type $type
     */
    public function ckeckoutCommit($commit, $local)
    {
	
    }


    /**
     * 
     * @param type $tag
     * @param type $type
     */
    public function ckeckoutTag($tag, $local)
    {
	
    }


    /**
     * 
     * @param type $branch
     * @param type $type
     */
    public function ckeckoutBranch($branch, $local)
    {
	
    }


    /**
     * 
     * @param type $commit
     * @param type $type
     */
    public function getLastCommit()
    {
	
    }


    /**
     * 
     * @return type
     */
    public function hasExclude($local)
    {
	
    }


    /**
     * 
     * @return type
     */
    public function getExclude()
    {
	
    }

}