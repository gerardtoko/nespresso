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

use Nespresso\Git;
use Nespresso\Mercurial;
use Nespresso\Mapping\Project;
use Symfony\Component\Console\Command\Command as BaseCommand;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Console\Input\InputInterface;
use Nespresso\Source;
use Nespresso\Builder\ProjectBuilder;
use Nespresso\Builder\ConfigBuilder;

/**
 * Description of JsonCommand
 *
 * @author gerardtoko
 */
class Command extends BaseCommand
{

    protected $container;
    protected $directoryApp;
    protected $directoryProjet;


    public function __construct($name = null)
    {
	parent::__construct(null);
	$this->directoryProjet = 'projects/';			
	$this->directoryApp = __DIR__ . '/../../../app/';	
    }


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
    public function getJsonProject($arg, InputInterface $input)
    {

	$this->checkProjectNotationArg($arg);
	$project_name = $this->getProjectArg($arg, $input);
	$project_file = $this->getDirectoryProject() . $project_name . '.json';

	//check file
	if (!file_exists(realpath($project_file))) {
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
    public function getRepositoryArg($arg, InputInterface $input)
    {

	$arg_project = $input->getArgument($arg);
	$this->checkProjectNotationArg($arg_project);
	if (!preg_match("#.+:.+#", $arg_project)) {
	    throw new \Exception("Arg $arg_project error parsing, no found repository data. Example : nespresso:production");
	}
	return substr(strstr($arg_project, ':'), 1);
    }


    /**
     * 
     * @param type $arg
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @return type
     */
    public function getProjectArg($arg, InputInterface $input)
    {
	$arg_project = $input->getArgument($arg);
	//validation data
	$this->checkProjectNotationArg($arg_project);
	return preg_match("#.+:.+#", $arg_project) ? strstr($arg_project, ':', true) : $arg_project;
    }


    /**
     * 
     * @param type $arg_project
     * @throws \Exception
     */
    public function checkProjectNotationArg($arg_project)
    {
	if (substr_count($arg_project, ':') > 1) {
	    throw new \Exception("Arg $arg_project error parsing, Example : nespresso:production");
	}
    }


    /**
     * 
     * @return type
     */
    public function jsonValidation(InputInterface $input)
    {

	$project_json = $this->getJsonProject("project", $input);
	$this->getContainer()->get("validation")->valid($project_json);
    }


    /**
     * 
     * @param type $dir
     * @return \Nespresso\Command\Command
     */
    public function setDirectoryProject($dir)
    {
	$this->directoryProjet = $dir;
	return $this;
    }


    /**
     * 
     * @param type $dir
     * @return \Nespresso\Command\Command
     */
    public function setDirectoryApp($dir)
    {
	$this->directoryApp = $dir;
	return $this;
    }


    /**
     * 
     * @return type
     */
    public function getDirectoryProject()
    {
	return $this->directoryProjet;
    }


    /**
     * 
     * @return type
     */
    public function getDirectoryApp()
    {
	return $this->directoryApp;
    }


    /**
     * 
     * @param \Nespresso\Mapping\Project $projectObject
     * @return \Nespresso\Git
     */
    public function getScm(Project $projectObject)
    {
	switch (strtolower($projectObject->getSource()->getType())) {
	    case "git":
		$scm = new Git();
		break;
	    case "mercurial":
		$scm = new Mercurial();
		break;
	    default :
		$scm = new Git();
		break;
	}
	return $scm;
    }


    /**
     * 
     * @return type
     * @throws \Exception
     */
    public function getManager()
    {
	//validation json schema
	$input = $this->getContainer()->get("IO")->input();
	$output = $this->getContainer()->get("IO")->output();
	$project = $this->getProjectArg("project", $input);
	$repository = $this->getRepositoryArg("project", $input);
	$group = $input->getOption('group');

	$output->writeln("<info>Startup nespresso...</info>");

	if ($repository == NULL) {
	    throw new \Exception("Repository undefined");
	}

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
	return $manager;
    }


    public function initManager()
    {
	$this->getManager();
    }

}