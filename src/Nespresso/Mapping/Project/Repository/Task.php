<?php

/*
 * This file is part of Nespresso.
 *
 * (c) Gerard TOKO <gerard.toko@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nespresso\Mapping\Project\Repository;

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
     * @param array $pre
     * @return \Nespresso\Mapping\Project\Repository\Task
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
     * @return type
     */
    public function hasPre()
    {
	return !empty($this->pre) ? TRUE : FALSE;
    }


    /**
     * 
     * @param array $post
     * @return \Nespresso\Mapping\Project\Repository\Task
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


    /**
     * 
     * @return type
     */
    public function hasPost()
    {
	return !empty($this->post) ? TRUE : FALSE;
    }

}