<?php
namespace PHM7;

/**
 * Dummy Class Loader
 *
 * We generally don't want to touch the '.phar' unless someone tries to load the "OAS2" namespace.
 * We'll install a placeholder. If anyone tries to use "OAS2", then we'll get the real thing.
 */
function dummyloader(string $class) {
  if (substr($class, 0, 5) === 'PHM7\\') {
    // spl_autoload_unregister('_oauth_server_class_loader');
    $l = require 'phar://' . __DIR__ . '/vendor.phar/vendor/autoload.php';
    $l->loadClass($class);
  }
}

spl_autoload_register('\PHM7\dummyloader');
