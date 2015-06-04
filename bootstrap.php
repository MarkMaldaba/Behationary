<?php
include_once 'vendor/autoload.php';

include_once 'MeadSteve/Behationary/Behationary.php';
include_once 'MeadSteve/Behationary/Config.php';

$configPath = __DIR__ . "/config.php";
\MeadSteve\Behationary\Config::setPath($configPath);

include_once 'MeadSteve/Behationary/IndexedContext.php';
include_once 'MeadSteve/Behationary/StepPrettyfier.php';

include_once 'Tests/MeadSteve/Behationary/FakeContext.php';