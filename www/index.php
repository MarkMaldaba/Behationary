<?php
require_once __DIR__ . "/../bootstrap.php";

$config = \MeadSteve\Behationary\Config::get();
$arrProjects = \MeadSteve\Behationary\Config::getProjects();

$errorMessage = "";
if (!$config->isValid()) {
	$errorMessage = "<code>config.php</code> could not be found, or is not "
				  . "readable.";
}
elseif (count($arrProjects) == 0) {
	$errorMessage = "<code>config.php</code> exists, but doesn't include any valid "
				  . "project defintions.";
}

?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>Behationary</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width">
        <link rel="stylesheet" href="css/normalize.css">
        <link rel="stylesheet" href="css/main.css">
        <script src="js/vendor/modernizr-2.6.2.min.js"></script>
    </head>
    <body>
        <!--[if lt IE 7]>
            <p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
        <![endif]-->

        <!-- Page Content -->

        <h1>Behationary</h1>

	<?php
	if ($errorMessage) {
	?>
			<p>No projects defined.</p>
			<p><?php print($errorMessage); ?></p>
	<?php
	}
	else {
	?>

        <input data-bind="value: filterTerm, valueUpdate: 'afterkeydown'" />

        <table>
			<thead>
				<tr>
					<th>Step</th>
					<th>Function</th>
					<th>Line Number</th>
				</tr>
			</thead>
			<tbody data-bind="foreach: formattedSteps">
				<tr>
					<td data-bind="html: step"></td>
					<td data-bind="html: method"></td>
					<td data-bind="html: lineNumber"></td>
				</tr>
			</tbody>
		</table>

        <!--<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>-->
        <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.9.1.min.js"><\/script>')</script>
        <script src="js/vendor/knockout-2.2.1.js"></script>
        <script src="js/MainViewModel.js"></script>

	<?php
	}
	?>

    </body>
</html>
