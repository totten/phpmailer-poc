<?php
declare(strict_types = 1);

// phpcs:disable PSR1.Files.SideEffects
require_once 'phpmailer.civix.php';

// phpcs:enable

use CRM_Phpmailer_ExtensionUtil as E;

/**
 * Implements hook_civicrm_config().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_config/
 */
function phpmailer_civicrm_config(\CRM_Core_Config $config): void {
  _phpmailer_civix_civicrm_config($config);
  require_once __DIR__ . '/packages/autoload.php';
}

/**
 * Implements hook_civicrm_install().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_install
 */
function phpmailer_civicrm_install(): void {
  _phpmailer_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_enable
 */
function phpmailer_civicrm_enable(): void {
  _phpmailer_civix_civicrm_enable();
}

/**
 * @return void
 * @see \CRM_Utils_Hook::alterMailer()
 */
function phpmailer_civicrm_alterMailer(&$mailer): void {
  // This is potentially useful as a bridge to extensions that rely on PEAR Mail.
  // However, the bridge will require encoding/decoding/re-encoding. At the moment,
  // that complexity distracts from the current inquiry. But if we were doing this
  // extension for real, then it would be worth looking at.

  // if ($dsn = _phpmailer_dsn()) {
  //   $mailer = new \Civi\PhpMailer\PearPhpMailer([
  //     'dsn' => $dsn,
  //   ]);
  // }
}

function _phpmailer_dsn(): ?string {
  return CRM_Utils_Constant::value('CIVICRM_PHPMAILER_DSN');
}
