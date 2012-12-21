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

use Symfony\Component\Console\Command\Command as BaseCommand;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Console\Input\InputInterface;

/**
 * Description of JsonCommand
 *
 * @author gerardtoko
 */
class Command extends BaseCommand
{

    protected $container;


    /**
     * Singleton Pattern
     * @return type
     */
    public function getContainer()
    {
	if (is_null($this->container)) {
	    $container = new ContainerBuilder();
	    $loader = new YamlFileLoader($container, new FileLocator(__DIR__));
	    $loader->load($this->getDirectoryApp() . 'services.yml');
	    $this->container = $container;
	}

	return $this->container;
    }


    /**
     * 
     * @param type $arg
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @return type
     * @throws \Exception
     */
    public function getJsonProjectByArg($arg, InputInterface $input)
    {

	$this->validProjectNode($arg);
	$project_name = $this->getProjectByArg($arg, $input);
	$project_file = $this->getDirectoryProject() . $project_name . '.json';

	//check file
	if (!file_exists($project_file)) {
	    $basename = basename($project_file);
	    throw new \Exception("file $basename does not exist");
	}

	$project_json = file_get_contents($project_file);

	return $project_json;
    }


    /**
     * 
     * @param type $arg
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @return type
     * @throws \Exception
     */
    public function getNodeByArg($arg, InputInterface $input)
    {

	$arg_project = $input->getArgument($arg);
	$this->validProjectNode($arg_project);
	if (!preg_match("#.+:.+#", $arg_project)) {
	    throw new \Exception("Arg $arg_project error parsing, no found node data. Example : nameProject:nameNode");
	}
	return strstr($arg_project, ':');
    }


    /**
     * 
     * @param type $arg
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @return type
     */
    public function getProjectByArg($arg, InputInterface $input)
    {
	$arg_project = $input->getArgument($arg);
	//validation data
	$this->validProjectNode($arg_project);
	return preg_match("#.+:.+#", $arg_project) ? strstr($arg_project, ':', true) : $arg_project;
    }


    /**
     * 
     * @param type $arg_project
     * @throws \Exception
     */
    public function validProjectNode($arg_project)
    {
	if (substr_count($arg_project, ':') > 1) {
	    throw new \Exception("Arg $arg_project error parsing, Example : nameProject:nameNode");
	}
    }


    /**
     * 
     * @return type
     */
    public function getDirectoryProject()
    {
	return __DIR__ . '/../../../tests/';
    }


    /**
     * 
     * @return type
     */
    public function getDirectoryApp()
    {
	return __DIR__ . '/../../../app/';
    }

}