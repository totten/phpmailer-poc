<?php

namespace Civi\PHPMailer;

use PHM7\PHPMailer\PHPMailer\DSNConfigurator;
use PHM7\PHPMailer\PHPMailer\PHPMailer;

class PHPMailerUtil {

  public static function convertMailParamsToMailer(array $mailParams): PHPMailer {
    // The general assumption is that key-value pairs in $mailParams should
    // pass through as email headers, but there are several special-cases
    // (e.g. 'toName', 'toEmail', 'text', 'html', 'attachments', 'headers').

    /**
     * @var \PHM7\PHPMailer\PHPMailer\PHPMailer $mail
     */
    $mail = DSNConfigurator::mailer(_phpmailer_dsn(), TRUE);
    // FIXME: PHPMailer::setLanguage(...);

    // 1. Consolidate: 'toName' and 'toEmail' should be 'To'.
    $mail->addAddress($mailParams['toEmail'], $mailParams['toName'] ?? '');
    unset($mailParams['toName']);
    unset($mailParams['toEmail']);

    // 2. Apply the other fields.
    foreach ($mailParams as $key => $value) {
      if (empty($value)) {
        continue;
      }

      switch ($key) {
        case 'text':
          $mail->AltBody = $mailParams['text'];
          break;

        case 'html':
          $mail->msgHTML($mailParams['html']);
          break;

        case 'attachments':
          foreach ($mailParams['attachments'] as $fileID => $attach) {
            $mail->addAttachment($attach['fullPath'], $attach['cleanName'], PHPMailer::ENCODING_BASE64, $attach['mime_type']);
          }
          break;

        case 'headers':
          static::applyHeaders($mail, $value);
          break;

        default:
          static::applyHeaders($mail, [$key => $value]);
          break;
      }
    }

    $mail->addCustomHeader('X-CiviMail-Engine', 'PHPMailer'); // REVERT

    return $mail;
  }

  public static function applyHeaders(PHPMailer $mail, array $headers): void {
    foreach ($headers as $name => $value) {
      switch (strtolower($name)) {
        case 'mime-version':
          // Ignore
          break;

        case 'from':
          foreach (static::eachAddress($value) as $email => $name) {
            $mail->setFrom($email, $name);
          }
          break;

        case 'to':
          foreach (static::eachAddress($value) as $email => $name) {
            $mail->addAddress($email, $name);
          }
          break;

        case 'cc':
          foreach (static::eachAddress($value) as $email => $name) {
            $mail->addCC($email, $name);
          }
          break;

        case 'bcc':
          foreach (static::eachAddress($value) as $email => $name) {
            $mail->addBCC($email, $name);
          }
          break;

        case 'subject':
          $mail->Subject = $value;
          break;

        default:
          $mail->addCustomHeader($name, $value);
          break;
      }
    }
  }

  protected static function eachAddress(string $rfc822): iterable {
    yield from [];
    $addresses = \ezcMailTools::parseEmailAddresses($rfc822);
    foreach ($addresses as $address) {
      /** @var \ezcMailAddress $address */
      yield from [$address->email => $address->name];
    }
  }

}
