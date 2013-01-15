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

use Nespresso\Source\GitSource;
use Nespresso\Source\MercurialSource;

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
    public function __construct($container, $scm)
    {

	if (empty($scm)) {
	    throw new \Exception("source is undefined");
	}

	if (!in_array($scm::SOURCE, $this->sources)) {
	    throw new \Exception("source $scm is inconning");
	}


	$class = sprintf("Nespresso\\Source\\%sSource", $scm::SOURCE);
	$this->source = new $class();
	$this->output = $container->get("IO")->output();
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
	$code = null;
	$output = null;
	$this->uniqid = uniqid();
	$this->local = sprintf("%s/%s", $tmp, $this->uniqid);

	$this->output->writeln("<comment>Cloning project...</comment> [<info>$scm</info>]");
	$command = $this->source->cloneScmCommand($scm, $this->uniqid);

	exec(sprintf("cd %s && %s 2>%s/nespresso.log", $tmp, $command, $tmp), $output, $code);
	$this->ckeckReturn($code);
	$this->isCloned = TRUE;
	$this->output->writeln(sprintf("Project cloned in [<info>%s</info>]", $this->local));

	return $this->isCloned;
    }


    /**
     * 
     * @param type $output
     */
    public function removeScm()
    {
	if ($this->isCloned()) {
	    $this->output->writeln(sprintf("<comment>remove clone...</comment> [<info>%s</info>]", $this->getLocal()));
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
	    $command = $this->source->hasCommitCommand($commit);
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
	    $command = $this->source->hasTagCommand($tag);
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
	    $command = $this->source->hasBranchCommand($branch);
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
    public function checkoutCommit($commit)
    {
	if ($this->isCloned()) {
	    $command = $this->source->checkoutCommit($commit);
	    $this->exec($command);
	    return $this->getLastCommit();
	}
    }


    /**
     * 
     * @param type $tag
     * @param type $type
     */
    public function checkoutTag($tag)
    {
	if ($this->isCloned()) {
	    $command = $this->source->checkoutTag($tag);
	    $this->exec($command);
	    return $this->getLastCommit();
	}
    }


    /**
     * 
     * @param type $branch
     * @param type $type
     */
    public function checkoutBranch($branch)
    {
	if ($this->isCloned()) {
	    $command = $this->source->checkoutBranch($branch);
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
	return !empty($this->isCloned) ? TRUE : FALSE;
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
	    $excludeFile = $this->source->getExclude();
	    return file_exists(sprintf("%s/%s", $this->local, $excludeFile)) ? TRUE : FALSE;
	}
    }


    /**
     * 
     * @return type
     */
    public function getExclude()
    {

	$excluded = array();
	if ($this->isCloned() && $this->hasExclude()) {
	    $excludeFile = $this->source->getExclude();
	    $excludeData = explode("\n", file_get_contents($excludeFile));
	    foreach ($excludeData as $exclude) {
		$exclude = trim($exclude);
		if (!preg_match("#^\##", $exclude)) {
		    $excluded[] = $exclude;
		}
	    }
	}
	return $excluded;
    }


    /**
     * 
     * @param type $local
     * @return \Nespresso\Source
     */
    public function setLocal($local)
    {
	$this->local = $local;
	return $this;
    }


    /**
     * 
     * @return type
     */
    public function getLocal()
    {
	return $this->local;
    }

}