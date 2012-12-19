<?php

namespace Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class DeployCommand extends Command
{


    protected function configure()
    {
	$this->setName('deploy')
		->setDescription('Deploy a project specific')
		->addArgument(
			'node', InputArgument::REQUIRED, 'specific node, example projetname:on_production'
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