<?php

// +---------------------------------------------------------------------------+
// | An absolute filesystem path to the agavi/agavi.php script.                |
// +---------------------------------------------------------------------------+
require('/usr/share/pear/agavi/agavi.php');

// +---------------------------------------------------------------------------+
// | An absolute filesystem path to our app/config.php script.                 |
// +---------------------------------------------------------------------------+
require('../app/config.php');

require_once '../app/lib/propel/build/conf/config.php';

// +---------------------------------------------------------------------------+
// | Initialize the framework. You may pass an environment name to this method.|
// | By default the 'development' environment sets Agavi into a debug mode.    |
// | In debug mode among other things the cache is cleaned on every request.   |
// +---------------------------------------------------------------------------+
Agavi::bootstrap('site');

// +---------------------------------------------------------------------------+
// | Call the controller's dispatch method on the default context              |
// +---------------------------------------------------------------------------+
AgaviContext::getInstance('web')->getController()->dispatch();

?>
