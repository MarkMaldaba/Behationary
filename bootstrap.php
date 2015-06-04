<?php

// Include project autoloader, for our project dependencies.
include_once 'vendor/autoload.php';

// Load config file.
include_once 'MeadSteve/Behationary/Config.php';

$configPath = __DIR__ . "/config.php";
\MeadSteve\Behationary\Config::setPath($configPath);

$config = \MeadSteve\Behationary\Config::get();

// Setup Behat autoloader, to load files from the specified local behat installation.
$behatRootPath = $config->getBehatRootPath();
if ($behatRootPath != "") {
	include_once $behatRootPath . '/vendor/autoload.php';
}

// Load the Behationary classes.  We don't use an autoloader for this currently.
include_once 'MeadSteve/Behationary/Behationary.php';
include_once 'MeadSteve/Behationary/IndexedContext.php';
include_once 'MeadSteve/Behationary/StepPrettyfier.php';
