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
 * Description of Task
 *
 * @author gerardtoko
 */
class Source
{

    protected $source;
    protected $local;
    protected $container;
    protected $output;
    protected $isCloned;
    protected $uniqid;
    protected $sources = array("Git", "Mercurial");


    /**
     * 
     * @param type $container
     */
    public function __construct($container, $scm, $output)
    {
	if (empty($scm::$source)) {
	    throw new Exception("source is undefined in $scm");
	}

	if (!in_array($scm::$source, $this->source)) {
	    throw new Exception("source $scm is inconning");
	}

	$class = $scm::$SOURCE;
	$this->source = new $class();
	$this->output = $output;
	$this->container = $container;
    }


    /**
     * 
     * @return type
     */
    public function cloneScm()
    {

	$manager = $this->container->get("nespresso.manager");
	$scm = $manager->getProject()->getSource()->getScm();
	$tmp = $manager->getConfig()->getTmp();
	$this->uniqid = uniqid();
	$this->local = sprintf("%s/%s", $tmp, $this->uniqid);

	$this->output->writeln("<comment>Cloning project...</comment> [<info>$scm</info>]");
	$command = $this->source->cloneScmCommand($this->uniqid);
	$this->exec($command);

	$this->output->writeln(sprintf("Project cloned in [<info></info>]", $this->local));
	$this->isCloned = TRUE;

	return $this->isCloned;
    }


    /**
     * 
     * @param type $output
     */
    public function removeScm()
    {
	if ($this->isCloned()) {
	    $this->output->writeln(sprintf("<comment>remove clone...</comment> [<info>%s</info>]", $this->getTmpGit()));
	    $this->exec(sprintf("rm -rf %s", $this->local));
	    $this->output->writeln("<comment>Clone removed!</comment>");
	    $this->local = NULL;
	    return TRUE;
	}
    }


    /**
     * 
     * @param type $commit
     * @return boolean
     */
    public function hasCommit($commit)
    {
	if ($this->isCloned()) {
	    $command = $this->source->hasCommitCommand($commit, $this->local);
	    return $this->exec($command);
	}
    }


    /**
     * 
     * @param type $tag
     * @return boolean
     */
    public function hasTag($tag)
    {
	if ($this->isCloned()) {
	    $command = $this->source->hasTagCommand($tag, $this->local);
	    return $this->exec($command);
	}
    }


    /**
     * 
     * @param type $branch
     * @return boolean
     */
    public function hasBranch($branch)
    {
	if ($this->isCloned()) {
	    $command = $this->source->hasBranchCommand($branch, $this->local);
	    return $this->exec($command);
	}
    }


    /**
     * 
     * @param type $command
     * @return boolean
     */
    protected function exec($command)
    {
	if ($this->isCloned()) {
	    $manager = $this->container->get("nespresso.manager");
	    $tmp = $manager->getConfig()->getTmp();
	    $code = null;
	    $output = null;

	    exec(sprintf("cd %s && %s 2>%s/nespresso.log", $this->local, $command, $tmp), $output, $code);
	    $this->ckeckReturn($code);
	    return true;
	}
    }


    /**
     * 
     * @param type $commit
     * @param type $type
     */
    public function ckeckoutCommit($commit)
    {
	if ($this->isCloned()) {
	    $command = $this->source->ckeckoutCommit($commit, $this->local);
	    $this->exec($command);
	    return $this->getLastCommit();
	}
    }


    /**
     * 
     * @param type $tag
     * @param type $type
     */
    public function ckeckoutTag($tag)
    {
	if ($this->isCloned()) {
	    $command = $this->source->ckeckoutTag($tag, $this->local);
	    $this->exec($command);
	    return $this->getLastCommit();
	}
    }


    /**
     * 
     * @param type $branch
     * @param type $type
     */
    public function ckeckoutBranch($branch)
    {
	if ($this->isCloned()) {
	    $command = $this->source->ckeckoutBranch($branch, $this->local);
	    $this->exec($command);
	    return $this->getLastCommit();
	}
    }


    /**
     * 
     * @param type $commit
     * @param type $type
     */
    protected function getLastCommit()
    {
	if ($this->isCloned()) {
	    $manager = $this->container->get("nespresso.manager");
	    $tmp = $manager->getConfig()->getTmp();

	    $command_log = $this->source->getLastCommit($this->local);
	    $this->exec($command_log);
	    return file_get_contents($tmp . "/nespresso.log");
	}
    }


    /**
     * 
     * @return boolean
     * @throws \Exception
     */
    protected function isCloned()
    {
	if (empty($this->isCloned)) {
	    throw new \Exception("Repository uncloned");
	} else {
	    return TRUE;
	}
    }


    /**
     * 
     * @param type $code
     * @throws \Exception
     */
    protected function ckeckReturn($code)
    {
	if ($code) {
	    $manager = $this->container->get("nespresso.manager");
	    $logFile = sprintf("%s/nespresso.log", $manager->getConfig()->getTmp());
	    $log = file_exists($logFile) ? file_get_contents($logFile) : NULL;
	    if ($this->local) {
		$this->removeScm();
	    }
	    throw new \Exception("Error processing... code($code) \n $log");
	}
    }


    /**
     * 
     * @return type
     */
    public function hasExclude()
    {
	if ($this->isCloned()) {
	    return $this->source->hasExclude($this->local);
	}
    }


    /**
     * 
     * @return type
     */
    public function getExclude()
    {

	$excluded = array();
	if ($this->isCloned()) {
	    $excludeFile = $this->source->hasExclude($this->local);
	    if (file_exists($excludeFile)) {
		$excludeData = explode("\n", file_get_contents($excludeFile));
		foreach ($excludeData as $exclude) {
		    $exclude = trim($exclude);
		    if (!preg_match("#^\##", $exclude)) {
			$excluded[] = $exclude;
		    }
		}
	    }
	}
	return $excluded;
    }

}