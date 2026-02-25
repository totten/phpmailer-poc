<?php
declare(strict_types = 1);

namespace Civi\PHPMailer;

use Civi\FlexMailer\FlexMailerTask;
use CRM_Phpmailer_ExtensionUtil as E;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * @service phpmailer.sender
 */
class PHPMailerSender extends BasicSender implements EventSubscriberInterface {

  public static function getSubscribedEvents(): array {
    return [
      'civi.flexmailer.send' => ['onSend', 0],
    ];
  }

  /**
   * @param \Civi\FlexMailer\FlexMailerTask $task
   * @return mixed
   */
  public function sendMessage(FlexMailerTask $task): mixed {
    $message = PHPMailerUtil::convertMailParamsToMailer($task->getMailParams());
    try {
      $message->send();
      return static::createOutcomeOk();
    }
    catch (\Throwable $e) {
      // FIXME: This pattern is probably wrong for PHPMailer. Sketch based on PEAR Mail.
      $smtpCode = $smtpMessage = '';
      if (preg_match('/ \(code: (.+), response: /', $e->getMessage(), $matches)) {
        $smtpCode = $matches[1];
        $smtpMessage = $matches[2];
      }

      return static::createOutcomeError($e, $smtpCode, $smtpMessage);
    }
  }

}
