<?php

namespace Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ReleaseCommand extends Command
{


    protected function configure()
    {
	$this->setName('release')
		->setDescription('create new release on a projet specific')
		->addArgument(
			'node', InputArgument::REQUIRED, 'specific node, example projetname:on_production'
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