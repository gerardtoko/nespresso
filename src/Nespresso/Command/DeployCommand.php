<?php

/*
 * This file is part of Nespresso.
 *
 * (c) Gerard TOKO <gerard.toko@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nespresso\Command;

use Nespresso\Rsync;
use Nespresso\Command\Command;
use Nespresso\Controller\TaskController;
use Nespresso\Controller\ReleaseController;
use Nespresso\Controller\SharedController;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Description of DeployCommand
 *
 * @author gerardtoko
 */
class DeployCommand extends Command
{


    /**
     * 
     * @param type $name
     */
    public function __construct($name = null)
    {
	parent::__construct(null);
    }


    protected function configure()
    {
	$this->setName('deploy')
		->setDescription('Deploy a project specific')
		->addArgument(
			'project', InputArgument::REQUIRED, 'Specific project with a repository or group repository. Example nespresso:production'
		)
		->addOption(
			'branch', null, InputOption::VALUE_REQUIRED, 'Deploy from a branch. Example --backup=master'
		)
		->addOption(
			'tag', null, InputOption::VALUE_REQUIRED, 'Deploy from a tag. Example --tag=v1.2'
		)
		->addOption(
			'commit', null, InputOption::VALUE_REQUIRED, 'Deploy from a commit. Example --commit=b0a1b9a3adc5e583'
		)
		->addOption(
			'group', null, InputOption::VALUE_NONE, 'if you want deployed on a group of directory, add this attribute'
		)
	;
    }


    /**
     * 
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {

	$this->getContainer()->get("IO")->init($input, $output);

	$commit = $input->getOption('commit');
	$tag = $input->getOption('tag');
	$branch = $input->getOption('branch');

	$source = $this->getManager()->getSource();
	$source->cloneScm();
	$commitCheckout = $this->checkout($source, $commit, $tag, $branch);

	$releaseController = new ReleaseController($this->container);
	$releaseController->controlAction();
	$newRelease = $releaseController->createNewReleaseAction();

	$this->deploy($newRelease);

	$releaseController->pushCommitFileAction($commitCheckout);
	$releaseController->updateSymbolinkAction();
	$source->removeScm();
	$releaseController->checkKeepRelease();

	$output->writeln("<info>Deployement finish!</info>");
    }


    /**
     * 
     * @param type $release
     */
    public function deploy($release)
    {	
	$sharedController = new SharedController($this->container, $release);
	$taskController = new TaskController($this->container, $release);
	$rsync = new Rsync($this->container, $release);
		
	$taskController->executePreCommand();	
	$sharedController->sharedAction();	
	$rsync->deploy();
	$taskController->executePostCommand();
    }


    /**
     * 
     * @param type $source
     * @param type $commit
     * @param type $tag
     * @param type $branch
     * @return type
     */
    public function checkout($source, $commit = null, $tag = null, $branch = null)
    {
	//from commit
	if (NULL != $commit && $source->hasCommit($commit)) {
	    return $source->checkoutCommit($commit);
	}

	//from tag
	if ($tag != NULL && $source->hasTag($tag)) {
	    return $source->checkoutTag($tag);
	}

	//from branch
	if ($branch != NULL && $source->hasBranch($branch)) {
	    return $source->checkoutBranch($branch);
	}

	return $source->checkoutBranch("master");
    }

}