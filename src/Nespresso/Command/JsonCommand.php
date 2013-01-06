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
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Description of JsonCommand
 *
 * @author gerardtoko
 */
class JsonCommand extends Command
{


    protected function configure()
    {
	$this->setName('json')
		->setDescription('control json on a project specific')
		->addArgument(
			'project', InputArgument::REQUIRED, 'specific project, example nespresso'
		)
	;
    }


    /**
     * 
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @return type
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
	$output->writeln("<info>Starting nespresso...</info>");
	$this->validJson($input, $output);
	return $output->writeln("<info>project is correct!</info>");
    }

}