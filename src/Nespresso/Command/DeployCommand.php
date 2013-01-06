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

use Nespresso\Command\Command;
use Nespresso\Builder\ProjectBuilder;
use Nespresso\Controller\RepositoryController;
use Nespresso\Command\CommandInterface;
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

	$output->writeln("validation <info>$project</info> project");
	$this->validJson($input, $output);

	if (NULL == $repository || (NULL == $commit && NULL == $tag && NULL == $branch)) {
	    throw new \Exception("option undefined");
	}

	$projectFromJson = json_decode($this->getJsonProjectByArg("project", $input));

	$builderProject = new ProjectBuilder($projectFromJson, $repository, $group);
	$projectObject = $builderProject->build();
	$manager = $this->getContainer()->get("nespresso.manager");
	$manager->setProject($projectObject);

	//control repository
	$controllerRepository = new RepositoryController($this->container, $output);
	$controllerRepository->action();
	
	$manager->cloneGit($output);

	//fram commit
	if (NULL != $commit) {
	    
	}

	$manager->removeCloneGit($output);
    }

}