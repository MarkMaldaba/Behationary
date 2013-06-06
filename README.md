Behationary
===========

Human readable/searchable step dictionary for behat contexts.
### Requirements
 - php >= 5.3
 - Composer

### Installation
1. Clone this repo
1. Run "composer update" in the root.
1. Point your webserver at the www subfolder.
1. Ensure any file requests with api/ at the start is forwarded to api.php (this is done already for apache with .htaccess)
1. In the repo root create config.php (this will be loaded automatically) with something like the following:

```php
<?php
namespace Behationary;

// todo: Include context files.

function getContexts() {
 // todo: Add all the feature contexts to this array
  return array(
		new \FeatureContext(array()),
		"AnotherFeatureContext"
	);
}
```
