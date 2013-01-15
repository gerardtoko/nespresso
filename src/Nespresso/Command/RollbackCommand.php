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

use Nespresso\Command\Command;
use Nespresso\Controller\ReleaseController;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Description of RollBackCommand
 *
 * @author gerardtoko
 */
class RollBackCommand extends Command
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
	$this->setName('rollback')
		->setDescription('RollBack a project specific')
		->addArgument(
			'project', InputArgument::REQUIRED, 'Specific project with a repository or group repository. Example nespresso:production'
		)
		->addOption(
			'release', null, InputOption::VALUE_REQUIRED, 'Select release for the pointer (integer value), example --release=1'
		)
		->addOption(
			'group', null, InputOption::VALUE_NONE, 'If you want rollback on a group of directory, add this attribute'
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
	$release = $input->getOption('release') != NULL ? $input->getOption('release') : 0;
	$this->initManager();

	//control repositories
	$releaseController = new ReleaseController($this->container);
	$releaseController->checkoutAction($release);

	$output->writeln("<info>RollBack finish!</info>");
    }

}