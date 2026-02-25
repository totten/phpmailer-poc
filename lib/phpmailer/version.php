<?php

// Return the current version number for this library.

// For this phpmailer@7.X.X.phar, we will copy the version-number from the eponymous phpmailer/phpmailer and append the hour.

$installed = json_decode(file_get_contents(
  __DIR__ . '/vendor/composer/installed.json'
), TRUE);

foreach ($installed['packages'] ?? [] as $package) {
  if ($package['name'] === 'phpmailer/phpmailer') {
     return $package['version_normalized'] . '.' . gmdate('YmdH');
  }
}

throw new \Exception("Failed to determine library version.");
