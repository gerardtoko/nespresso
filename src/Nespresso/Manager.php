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

use Nespresso\Builder\OptionBuilder;
/**
 * Description of Manager
 *
 * @author gerardtoko
 */
class Manager implements Manager\ManagerInterface
{

    protected $project;
    private $option;
    private $repositoryGit;
    private $sshManagerConnection;


    public function __construct()
    {

	$builderOption = new OptionBuilder();
	$optionObject = $builderOption->build();
	$this->option = $optionObject;
	$this->sshManagerConnection = new Manager\Connection(
			$optionObject->getUser(),
			'127.0.0.1',
			$optionObject->getPort(),
			$optionObject->getKey());
    }


    /**
     * 
     * @param type $ouput
     */
    public function cloneGit($output)
    {
	$scm = $this->project->getGit();
	$tmp = $this->option->getTmp();
	$this->repositoryGit = uniqid();
	$ssh = $this->sshManagerConnection;

	$output->writeln("<comment>Cloning project...</comment> [<info>$scm</info>]");
	$output_ssh = $ssh->exec(sprintf("cd %s && git clone %s %s", $tmp, $scm, $this->repositoryGit));
	$output->writeln(trim($output_ssh));
	$output->writeln(sprintf("Project cloned in [<info>%s/%s</info>]", $tmp, $this->repositoryGit));
    }


    /**
     * 
     * @param type $output
     */
    public function removeCloneGit($output)
    {
	$tmp = $this->option->getTmp();
	$gitRepo = $this->repositoryGit;
	$ssh = $this->sshManagerConnection;
	$output->writeln(sprintf("<comment>Remove clone...</comment> [<info>%s/%s</info>]", $tmp, $gitRepo));
	$output_ssh = $ssh->exec(sprintf("rm -rf %s/%s", $tmp, $gitRepo));
	if ($output_ssh != NULL) {
	    $output->writeln("<error>$output_ssh</error>");
	}
	$output->writeln("<comment>Clone removed!</comment>");
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
     * @param type $option
     * @return \Nespresso\Manager
     */
    public function setOption($option)
    {
	$this->option = $option;
	return $this;
    }


    /**
     * 
     * @return type
     */
    public function getOption()
    {
	return $this->option;
    }

}