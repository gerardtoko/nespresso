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
			'node', InputArgument::REQUIRED, 'specific node, example projectname:on_production'
		)
		->addOption(
			'backup', null, InputOption::VALUE_REQUIRED, 'attribute backup (booleen value) example --backup=true'
		)
		->addOption(
			'confirm', null, InputOption::VALUE_REQUIRED, 'attribute confirmation (booleen value), example --confirm=true'
		)
	;
    }


    protected function execute(InputInterface $input, OutputInterface $output)
    {
	$name = $input->getArgument('name');
	if ($name) {
	    $text = 'Hello ' . $name;
	} else {
	    $text = 'Hello';
	}

	if ($input->getOption('yell')) {
	    $text = strtoupper($text);
	}

	$output->writeln($text);
    }

}