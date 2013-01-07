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
 * Description of Task
 *
 * @author gerardtoko
 */
class Git
{

    protected $container;
    protected $output;
    private $repositoryGit;


    /**
     * 
     * @param type $container
     * @param type $output
     * @param type $releaseId
     */
    public function __construct($container, $output)
    {
	$this->container = $container;
	$this->output = $output;
    }


    /**
     * 
     * @param type $ouput
     */
    public function cloneGit()
    {
	$manager = $this->container->get("nespresso.manager");
	$scm = $manager->getProject()->getGit();
	$tmp = $manager->getConfig()->getTmp();
	$this->repositoryGit = uniqid();

	$this->output->writeln("<comment>cloning project...</comment> [<info>$scm</info>]");
	$code = null;
	$outputExec = null;
	exec(sprintf("cd %s && git clone %s %s 2>&1", $tmp, $scm, $this->repositoryGit), $outputExec, $code);
	$this->isError($code);
	$this->output->writeln(sprintf("Project cloned in [<info>%s/%s</info>]", $tmp, $this->repositoryGit));
    }


    /**
     * 
     * @param type $output
     */
    public function removeCloneGit()
    {
	$this->IsInitialized();
	$manager = $this->container->get("nespresso.manager");
	$tmp = $manager->getConfig()->getTmp();
	$gitRepo = $this->repositoryGit;
	$this->output->writeln(sprintf("<comment>remove clone...</comment> [<info>%s/%s</info>]", $tmp, $gitRepo));

	$code = null;
	$outputExec = null;
	exec(sprintf("rm -rf %s/%s", $tmp, $gitRepo), $outputExec, $code);
	$this->isError($code);

	if ($outputExec != NULL) {
	    $this->output->writeln("<error>$outputExec</error>");
	}
	$this->output->writeln("<comment>Clone removed!</comment>");
	$this->repositoryGit = NULL;
    }


    /**
     * 
     * @param type $commit
     * @return boolean
     */
    public function isCommitExist($commit)
    {
	$this->IsInitialized();
	return $this->execExecute("show $commit");
    }


    /**
     * 
     * @param type $branch
     * @return boolean
     */
    public function isBranchExist($branch)
    {
	$this->IsInitialized();
	return $this->execExecute("show-branch origin/$branch");
    }


    /**
     * 
     * @param type $tag
     * @return boolean
     */
    public function isTagExist($tag)
    {
	$this->IsInitialized();
	return $this->execExecute("ls-tree $tag");
    }


    /**
     * 
     * @param type $command
     * @return boolean
     */
    protected function execExecute($command)
    {
	$this->IsInitialized();

	$code = null;
	$outputExec = null;
	$manager = $this->container->get("nespresso.manager");
	$tmp = $manager->getConfig()->getTmp();
	$gitRepo = $this->repositoryGit;
	exec(sprintf("cd %s/%s && git %s 2>%s/nespresso.log", $tmp, $gitRepo, $command, $tmp), $outputExec, $code);
	$this->isError($code);
	return true;
    }


    /**
     * 
     * @throws Exception
     */
    protected function IsInitialized()
    {
	if (empty($this->repositoryGit)) {
	    throw new \Exception("Repository Git uncloned");
	}
    }


    /**
     * 
     * @param type $commit
     * @param type $type
     */
    public function ckeckout($commit, $type = null)
    {
	$this->IsInitialized();
	$type = $type == null ? NULL : "the " . $type;
	$this->output->writeln("<comment>Ckeckout on $type</comment> <info>$commit</info><comment>...</comment>");
	$code = null;
	$outputExec = null;
	$manager = $this->container->get("nespresso.manager");
	$tmp = $manager->getConfig()->getTmp();
	$gitRepo = $this->repositoryGit;
	exec(sprintf("cd %s/%s && git checkout %s 2>&1", $tmp, $gitRepo, $commit), $outputExec, $code);
	$this->isError($code);
    }


    /**
     * 
     * @param type $code
     * @throws \Exception
     */
    protected function isError($code)
    {
	if ($code) {

	    $manager = $this->container->get("nespresso.manager");
	    $tmp = $manager->getConfig()->getTmp();
	    $log = file_get_contents($tmp . "/nespresso.log");

	    if ($this->repositoryGit) {
		$this->removeCloneGit();
	    }
	    throw new \Exception("Error Git processing. code($code) \n $log");
	}
    }


    /**
     * 
     * @return type
     */
    public function hasGitignore()
    {
	$this->IsInitialized();
	$manager = $this->container->get("nespresso.manager");
	$tmp = $manager->getConfig()->getTmp();
	$gitRepo = $this->repositoryGit;
	$gitignoreFile = sprintf("%s/%s/.gitignore", $tmp, $gitRepo);
	return file_exists($gitignoreFile) ? TRUE : FALSE;
    }


    /**
     * 
     * @return type
     */
    public function getGitignore()
    {
	$this->IsInitialized();
	$excluded = array();
	$manager = $this->container->get("nespresso.manager");
	$tmp = $manager->getConfig()->getTmp();
	$gitRepo = $this->repositoryGit;
	$gitignoreFile = sprintf("%s/%s/.gitignore", $tmp, $gitRepo);
	if (file_exists($gitignoreFile)) {
	    $excludeData = explode("\n", file_get_contents($gitignoreFile));
	    foreach ($excludeData as $exclude) {
		$exclude = trim($exclude);
		if (!preg_match("#^\##", $exclude)) {
		    $excluded[] = $exclude;
		}
	    }
	}
	return $excluded;
    }

}