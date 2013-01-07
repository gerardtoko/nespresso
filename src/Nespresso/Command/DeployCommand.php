<?php

/*
 * This file is part of the rdeploy package.
 *
 * (c) Gerard TOKO <gerard.toko@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nespresso\Command;

use Nespresso\Task;
use Nespresso\Git;
use Nespresso\Command\Command;
use Nespresso\Builder\ProjectBuilder;
use Nespresso\Builder\ConfigBuilder;
use Nespresso\Controller\RepositoryController;
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

	$output->writeln("<info>Starting nespresso...</info>");

	//get Data from the request
	$project = $this->getProjectByArg("project", $input);
	$repository = $this->getRepositoryByArg("project", $input);
	$commit = $input->getOption('commit');
	$tag = $input->getOption('tag');
	$branch = $input->getOption('branch');
	$group = $input->getOption('group');

	if (NULL == $repository || (NULL == $commit && NULL == $tag && NULL == $branch)) {
	    throw new \Exception("option undefined");
	}

	//validation json schema
	$output->writeln("validation <info>$project</info> project");
	$this->validJson($input, $output);
	$projectFromJson = json_decode($this->getJsonProjectByArg("project", $input));

	//Builder

	$builderOption = new ConfigBuilder();
	$optionObject = $builderOption->build();

	$builderProject = new ProjectBuilder($projectFromJson, $repository, $group);
	$projectObject = $builderProject->build();

	//git init
	$git = new Git($this->container, $output);

	//manager service
	$manager = $this->getContainer()->get("nespresso.manager");
	$manager->setProject($projectObject);
	$manager->setConfig($optionObject);
	$manager->setGit($git);

	//cloning git
	$git->cloneGit();

	//control repositories
	$controllerRepository = new RepositoryController($this->container, $output);
	$controllerRepository->controlAction();
	$releaseId = $controllerRepository->createNewReleaseAction();

	//control shared
	$controllerShared = new SharedController($this->container, $output, $releaseId);
	$controllerShared->controlAction();

	$task = new Task($this->container, $output, $releaseId);
	$task->executePreCommand();

	//from commit
	if (NULL != $commit && $git->isCommitExist($commit)) {
	    $git->ckeckout($commit, "commit");
	    $tag = NULL;
	    $branch == NULL;
	}

	//from tag
	if (NULL != $tag && $git->isTagExist($tag)) {
	    $git->ckeckout($tag, "tag");
	    $branch == NULL;
	}

	//from branch
	if (NULL != $branch && $git->isBranchExist($branch)) {
	    $git->ckeckout($branch, "branch");
	}else{
	    $git->ckeckout("master", "branch");
	}

	//deployement
	$rsync = new Rsync($this->container, $output, $releaseId);
	$rsync->deploy();

	$task->executePostCommand();

	//remove clone git
	$git->removeCloneGit();
	//$controllerRepository->ckeckRelease();
    }

}