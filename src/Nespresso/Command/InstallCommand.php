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
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Description of JsonCommand
 *
 * @author gerardtoko
 */
class InstallCommand extends Command
{


    protected function configure()
    {
	$this->setName('install')->setDescription('install directory nespresso');
    }


    /**
     * 
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @return type
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
	$output->writeln("<info>Install nespresso...</info>");

	$config = <<<CONFIG
{
    "key":  "/Users/gerardtoko/.ssh/id_rsa" // key ssh for nespresso 
}
CONFIG;
	
	$start = <<<PROJECT
{
    "repositories": { //all repositories
	"testing": //example testing repository
	{    
	    "user": "gerardtoko" , // user system for nespresso connection
	    "domain": "gerardtoko.com",	//domain dns, ip   
	    "deploy_to": "/home/gerardtoko/testing/nespresso-test" //directory on the server
	}
    }, 
    "source" : {
	"type" : "git", // git, mercurial
	"scm": "git@github.com:gerardtoko/nespresso.git" //scm source
    }
}
PROJECT;
	
	exec("echo  '$config' > config.json");
	exec("mkdir projects");
	exec("echo '$start' >> projects/started.json");
	return $output->writeln("<info>nespresso installed!</info>");
    }

}