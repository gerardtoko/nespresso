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

use Nespresso\Git;
use Nespresso\Rsync;
use Nespresso\Command\Command;
use Nespresso\Builder\ProjectBuilder;
use Nespresso\Builder\ConfigBuilder;
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

	$this->getContainer()->get("io")->init($input, $output);
	$output->writeln("<info>Starting nespresso...</info>");

	//get Data from the request
	$project = $this->getProjectArg("project", $input);
	$repository = $this->getRepositoryArg("project", $input);
	$commit = $input->getOption('commit');
	$tag = $input->getOption('tag');
	$branch = $input->getOption('branch');
	$group = $input->getOption('group');

	if ($repository == NULL) {
	    throw new \Exception("repository undefined");
	}

	//validation json schema
	$output->writeln("validation <info>$project</info> project");
	$this->jsonValidation($input);
	$projectFromJson = json_decode($this->getJsonProject("project", $input));

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
	$releaseController = new ReleaseController($this->container, $output);
	$releaseController->controlAction();
	$newRelease = $releaseController->createNewReleaseAction();

	//control shared
	$sharedController = new SharedController($this->container, $output, $newRelease);
	$sharedController->controlAction();

	$taskController = new TaskController($this->container, $output, $newRelease);
	$taskController->executePreCommand();

	$commitCheckout = $this->Checkout($git, $commit, $tag, $branch);

	//deployement
	$rsync = new Rsync($this->container, $output, $newRelease);
	$rsync->deploy();

	//task post deployement
	$taskController->executePostCommand();

	$releaseController->pushCommitFileAction($commitCheckout);
	$releaseController->updateSymbolinkAction();
	$git->removeCloneGit();
	$releaseController->ckeckRelease();
	
	$output->writeln("<info>Deployement finish!</info>");
    }


    public function Checkout($git, $commit = null, $tag = null, $branch = null)
    {
	//from commit
	if (NULL != $commit && $git->isCommitExist($commit)) {
	    return $git->ckeckout($commit, "commit");
	}

	//from tag
	if ($tag != NULL && $git->isTagExist($tag)) {
	    return $git->ckeckout($tag, "tag");
	}

	//from branch
	if ($branch != NULL && $git->isBranchExist($branch)) {
	    return $git->ckeckout($branch, "branch");
	}

	return $git->ckeckout("master", "branch");
    }

}