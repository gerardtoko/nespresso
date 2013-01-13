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
use Nespresso\Source;
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
 * Description of DiffCommand
 *
 * @author gerardtoko
 */
class DiffCommand extends Command
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
	$this->setName('diff')
		->setDescription('Diff a project specific')
		->addArgument(
			'project', InputArgument::REQUIRED, 'Specific project with a repository or group repository. Example nespresso:production'
		)
		->addOption(
			'branch', null, InputOption::VALUE_REQUIRED, 'Diff from a branch. Example --backup=master'
		)
		->addOption(
			'tag', null, InputOption::VALUE_REQUIRED, 'Diff from a tag. Example --tag=v1.2'
		)
		->addOption(
			'commit', null, InputOption::VALUE_REQUIRED, 'Diff from a commit. Example --commit=b0a1b9a3adc5e583'
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

	// init source
	$scm = $this->getScm($projectObject);
	$source = new Source($this->getContainer(), $scm);

	//manager service
	$manager = $this->getContainer()->get("nespresso.manager");
	$manager->setProject($projectObject);
	$manager->setConfig($optionObject);
	$manager->setSource($source);

	//cloning 
	//control repositories
	$releaseController = new ReleaseController($this->container);
	$releaseController->controlAction();

	$source->cloneScm();
	$this->checkout($source, $commit, $tag, $branch);

	$rsync = new Rsync($this->container);
	$rsync->diff($releaseController);

	$source->removeScm();
	$output->writeln("<info>Diff finish!</info>");
    }


    public function checkout($source, $commit = null, $tag = null, $branch = null)
    {
	//from commit
	if (NULL != $commit && $source->hasCommit($commit)) {
	    return $source->checkoutCommit($commit);
	}

	//from tag
	if ($tag != NULL && $source - hasTag($tag)) {
	    return $source->checkoutTag($tag);
	}

	//from branch
	if ($branch != NULL && $source->hasBranch($branch)) {
	    return $source->checkoutBranch($branch);
	}

	return $source->checkoutBranch("master");
    }

}