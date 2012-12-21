<?php

/*
 * This file is part of the rdeploy package.
 *
 * (c) Gerard TOKO <gerard.toko@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RDeploy\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use RDeploy\Validation;

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
			'project', InputArgument::REQUIRED, 'specific node, example projectname'
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

	$output->writeln("Starting rdeploy...");
	//sleep(1);

	$project_name = $input->getArgument("project");
	$project_file = $this->getDirectoryProject() . $project_name . '.json';

	//check file
	if (!file_exists($project_file)) {
	    $basename = basename($project_file);
	    return $output->writeln("<error>file $basename does not exist </error>");
	}

	try {
	    $project_json = file_get_contents($project_file);
	    $validation = new Validation();
	    $validation->validJson($project_json);
	} catch (\Exception $exc) {
	    $message = $exc->getMessage();
	    return $output->writeln("<error>Error file json: $message </error>");
	}

	$output->writeln($project_json);

	sleep(0.5);
	return $output->writeln("<info>project json $project_name is correct!</info>");
    }


    /**
     * 
     * @return type
     */
    public function getDirectoryProject()
    {
	return __DIR__ . '/../../../tests/';
    }

}