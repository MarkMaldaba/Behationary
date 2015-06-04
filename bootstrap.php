<?php

// Include project autoloader, for our project dependencies.
include_once 'vendor/autoload.php';

// Load config file.
include_once 'MeadSteve/Behationary/Config.php';

$configPath = __DIR__ . "/config.php";
\MeadSteve\Behationary\Config::setPath($configPath);

// Load the Behationary classes.  We don't use an autoloader for this currently.
include_once 'MeadSteve/Behationary/Behationary.php';
include_once 'MeadSteve/Behationary/IndexedContext.php';
include_once 'MeadSteve/Behationary/StepPrettyfier.php';

// Load the test classes.
include_once 'Tests/MeadSteve/Behationary/FakeContext.php';