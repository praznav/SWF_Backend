<?php

require_once '/var/www/vhosts/teamkevin.me/site/app/lib/propel/vendor/autoload.php';

$serviceContainer = \Propel\Runtime\Propel::getServiceContainer();
$serviceContainer->checkVersion('2.0.0-dev');
$serviceContainer->setAdapterClass('ShoppingWithFriends', 'mysql');
$manager = new \Propel\Runtime\Connection\ConnectionManagerSingle();
$manager->setConfiguration(array (
  'dsn' => 'mysql:host=localhost;dbname=shopping_with_friends',
  'user' => 's_w_f_user',
  'password' => 'KevinKevinCS2340P@$$w0rd',
));
$serviceContainer->setConnectionManager('ShoppingWithFriends', $manager);
$serviceContainer->setLoggerConfiguration('defaultLogger', array (
  'type' => 'stream',
  'path' => '/var/www/vhosts/teamkevin.me/logs/propel.log',
  'level' => '300',
));

$connection = array (
    'datasources' =>
        array (
            'ShoppingWithFriends' =>
                array (
                    'adapter' => 'mysql',
                    'connection' =>
                        array (
                            'dsn' => 'mysql:dbname=shopping_with_friends',
                            'user' => 's_w_f_user',
                            'password' => 'KevinKevinCS2340P@$$w0rd',
                ),
            ),
            'default' => 'ShoppingWithFriends',
        ),
    'generator_version' => '1.6.8',
);


return $connection;
