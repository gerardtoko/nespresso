<?php


/*
 * This file is part of the rdeploy package.
 *
 * (c) Gerard TOKO <gerard.toko@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Description of CheckCommand
 *
 * @author gerardtoko
 */
class CkeckCommand extends Command
{


    protected function configure()
    {
	$this->setName('check')
		->setDescription('control total release on a node project')
		->addArgument(
			'node', InputArgument::REQUIRED, 'specific node, example projectname:node'
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