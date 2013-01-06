<?php

/*
 * This file is part of Composer.
 *
 * (c) Gerard TOKO <gerard.toko@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nespresso;

/**
 * Description of Manager
 *
 * @author gerardtoko
 */
class Manager implements Manager\ManagerInterface
{

    protected $project;
    private $config;
    private $repositoryGit;


    /**
     * 
     * @param type $ouput
     */
    public function cloneGit($output)
    {
	$scm = $this->project->getGit();
	$tmp = $this->config->getTmp();
	$this->repositoryGit = uniqid();

	$output->writeln("<comment>cloning project...</comment> [<info>$scm</info>]");
	$outputExec = exec(sprintf("cd %s && git clone %s %s", $tmp, $scm, $this->repositoryGit));
	$output->writeln(trim($outputExec));
	$output->writeln(sprintf("Project cloned in [<info>%s/%s</info>]", $tmp, $this->repositoryGit));
    }


    /**
     * 
     * @param type $output
     */
    public function removeCloneGit($output)
    {
	if (empty($this->repositoryGit)) {
	    throw new Exception("Repository Git uncloned");
	}

	$tmp = $this->config->getTmp();
	$gitRepo = $this->repositoryGit;
	$output->writeln(sprintf("<comment>remove clone...</comment> [<info>%s/%s</info>]", $tmp, $gitRepo));
	$outputExec = exec(sprintf("rm -rf %s/%s", $tmp, $gitRepo));
	if ($outputExec != NULL) {
	    $output->writeln("<error>$outputExec</error>");
	}
	$output->writeln("<comment>Clone removed!</comment>");
	$this->repositoryGit = NULL;
    }


    /**
     * 
     * @param type $project
     * @return \Nespresso\Manager
     */
    public function setProject($project)
    {
	$this->project = $project;
	return $this;
    }


    /**
     * 
     * @return type
     */
    public function getProject()
    {
	return $this->project;
    }


    /**
     * 
     * @param type $config
     * @return \Nespresso\Manager
     */
    public function setConfig($config)
    {
	$this->config = $config;
	return $this;
    }


    /**
     * 
     * @return type
     */
    public function getConfig()
    {
	return $this->config;
    }

}