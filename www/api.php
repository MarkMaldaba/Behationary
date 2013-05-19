<?php
require_once __DIR__ . "/../bootstrap.php";
$configPath = __DIR__ . "/../config.php";
if (file_exists($configPath)) {
    include_once $configPath;
}

$app = new Bullet\App();
$request = new Bullet\Request();

// 'steps' subdirectory
$app->path('steps', function($request) use($app) {
    $behationary = new \MeadSteve\Behationary\Behationary();
    // If a get contexts functions exists (should have been defined in the
    // config file. Then call this as it will return all the contexts that
    // need testing.
    if (function_exists('\Behationary\getContexts')) {
        $contexts = \Behationary\getContexts();
        $behationary->addContexts($contexts);
    }

    // GetAll
    $app->get(function() use ($behationary) {
        return getJsonReadySteps($behationary->getAllSteps());
    });

    // Filter
    $app->path('query', function($request) use($app, $behationary) {
        $app->get(function() use ($behationary, $request) {
            $searchTerm = $request->query('term', "");
            $steps = $behationary->getAllSteps();
            $steps = getJsonReadySteps($steps);
            if ($searchTerm !== "") {
                $steps = array_values(array_filter(
                    $steps,
                    getStepFilterer($searchTerm)
                ));
                return $steps;
            }
            else {
                return $steps;
            }
        });
    });
});

$app->on('Exception',
    function($request, $response, \Exception $e) use($app) {
        $response->content(array(
            'exception' => get_class($e),
            'message' => $e->getMessage()
        ));
    }
);

echo $app->run($request);

function getStepFilterer($searchTerm) {
    return function($stepData) use ($searchTerm) {
        return (
            strpos(
                strtolower($stepData['step']),
                strtolower($searchTerm)
            ) !== false
        );
    };
}

function getJsonReadySteps($steps) {
    return array_map('getJsonReadyStep', array_keys($steps), $steps);
}

function getJsonReadyStep($step, $method) {
    return array(
        'step'      => $step,
        'method'    => $method
    );
}