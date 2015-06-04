<?php
/******************************************************************************
 * This is an example config file, which loads the test context that
 * ships with Behationary.  You can use it to test your installation is
 * working properly, or to run the Behationary's unit tests (which require
 * the FakeContext class to be loaded).
 *
 * To use, rename to config.php.
 *
 * If you run composer update with the require-dev flag then a local Behat
 * installation will be available in the vendor directory, and will be loaded
 * automatically as this is already defined in our standard autoloader.
 * If this is not available, or you want to test with a different version
 * of Behat, then set $behatRootPath to the appropriate path.
 ******************************************************************************
 */

namespace Behationary;

// Only required if Behat not present in the vendor directory.
// $behatRootPath = "/path/to/behat";

$projectName = "Behationary FakeContext";

function getContexts() {

	$BasePath = dirname(__FILE__);

	include_once $BasePath . '/Tests/MeadSteve/Behationary/FakeContext.php';

	return array(
		'MeadSteve\Behationary\Tests\FakeContext',
	);

}
