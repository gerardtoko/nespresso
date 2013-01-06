<?php

/*
 * This file is part of Composer.
 *
 * (c) Gerard TOKO <gerard.toko@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nespresso\Script\Project\Repository;

/**
 * Description of Repository
 *
 * @author gerardtoko
 */
class Task
{

    protected $pre;
    protected $post;


    /**
     * 
     * @param type $pre
     * @return \Nespresso\Project\Repository\Task
     */
    public function setPre(array $pre)
    {
	$this->pre = $pre;
	return $this;
    }


    /**
     * 
     * @return type
     */
    public function getPre()
    {
	return $this->pre;
    }


    /**
     * 
     * @param type $post
     * @return \Nespresso\Project\Repository\Task
     */
    public function setPost(array $post)
    {
	$this->post = $post;
	return $this;
    }


    /**
     * 
     * @return type
     */
    public function getPost()
    {
	return $this->post;
    }

}