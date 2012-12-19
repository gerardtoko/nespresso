<?php

require __DIR__ . '/vendor/autoload.php';

use Symfony\Component\Console\Application;

$application = new Application();
$application->add(new \Command\CkeckCommand);
$application->add(new \Command\CleanupCommand);
$application->add(new \Command\DeployCommand);
$application->add(new \Command\DiffCommand);
$application->add(new \Command\JsonCommand);
$application->add(new \Command\ReleaseCommand);
$application->add(new \Command\RevertCommand);
$application->add(new \Command\RollbackCommand);
$application->run();