<?php

/**
 * Auto-register "packages/vendor.phar".
 *
 * @mixinName vendor-phar
 * @mixinVersion 1.0.0
 *
 * @param CRM_Extension_MixInfo $mixInfo
 *   On newer deployments, this will be an instance of MixInfo. On older deployments, Civix may polyfill with a work-a-like.
 * @param \CRM_Extension_BootCache $bootCache
 *   On newer deployments, this will be an instance of MixInfo. On older deployments, Civix may polyfill with a work-a-like.
 *
 * @since TODO
 *
 */
return function ($mixInfo, $bootCache) {
  if (!$mixInfo->isActive()) {
    return;
  }

  $pharId = $mixInfo->longName . '-vendor';
  $pharFile = $mixInfo->getPath('packages/vendor.phar');
  $pathload = pathload(0);
  if (!file_exists($pharFile) || isset($pathload->loadedPackages[$pharId])) {
    return;
  }

  $pharVer = $bootCache->define($mixInfo->longName . '-vendor-ver', function () use ($mixInfo) {
    $info = CRM_Extension_Info::loadFromFile($mixInfo->getPath('info.xml'));
    return $info->version;
  });
  $pharNs = $bootCache->define($mixInfo->longName . '-vendor-ns', function () use ($mixInfo) {
    $scoper = require $mixInfo->getPath('packages/scoper.inc.php');
    return $scoper['prefix'];
  });
  $pathload->addSearchItem($pharId, $pharVer, $pharFile, 'phar');
  $pathload->addNamespace($pharId . '@' . explode('.', $pharVer)[0], $pharNs . '\\');
};
