# phpmailer

Rplaces the delivery backend for CiviCRM mailings -- switching from PEAR Mail to PHPMailer.  In theory, PHPMailer is
updated more frequently and may have more compatibility fixes.

This implementation was written circa Feb 2026 to help assess utility and feasibility.  Assume that it is incomplete.
(Remove this message if the status changes.)

_This is an [extension for CiviCRM](https://docs.civicrm.org/sysadmin/en/latest/customize/extensions/), licensed under [AGPL-3.0](LICENSE.txt)._

## Getting Started

* Install the extension
* In `civicrm.settings.php`, create a "DSN" string, e.g.

    ```php
    define('CIVICRM_PHPMAILER_DSN', 'smtp://localhost:25');
    define('CIVICRM_PHPMAILER_DSN', 'smtp://username:password@smtp.example.com:587?SMTPSecure=tls&SMTPAuth=true');
    define('CIVICRM_PHPMAILER_DSN', 'sendmail://');
    define('CIVICRM_PHPMAILER_DSN', 'mail://');
    ```

NOTE: If you do not configure `CIVICRM_PHPMAILER_DSN`, then the system will continue using PEAR Mail.

## Known Issues

* Only implements FlexMailer support (CiviMail/Mosaico).
* Need to detect SMTP error codes. (Without this, delivery tracking may be inaccurate.)
* Does not currently support other use-cases (e.g. transactional-emails). These other use-cases
  may have direct references to PEAR Mail. To convert them, we would need an adapter that
  allows PEAR Mail-consumers to delegate to PHPMailer. (There's a partial/draft implementation using hook_alterMailer.)
* The DSN requires extra setup. You could read `mailing_backend` and set this automatically.
* Sends an extra header (`X-CiviMail-Engine: SymfonyMailer`) which should probably be removed. But helps you confirm that SymfonyMailer was used.
