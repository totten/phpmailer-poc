<?php

namespace Civi\PHPMailer;

use PHM7\PHPMailer\PHPMailer\DSNConfigurator;

/**
 *
 */
class PearAdapter extends \Mail {

  /**
   * @var array
   */
  protected $params;

  public function __construct($params) {
    if (empty($params['dsn'])) {
      throw new \CRM_Core_Exception("Failed to initialize PearPhpMailer. No DSN provided.");
    }
    $this->params = $params;
  }

  public function send($recipients, $headers, $body) {

    /**
     * @var \PHM7\PHPMailer\PHPMailer\PHPMailer $mail
     */
    $mail = DSNConfigurator::mailer($this->params['dsn']);

    // FUDGED: It sounds like PhpMailer will re-interpret the 'To:', 'CC:', 'BCC:'
    // It might be good to validate the $recipients matches the inferred recipients.
    // $recipients = $this->parseRecipients($recipients);

    PhpMailerUtil::applyHeaders($mail, $headers);

    // FIXME: Extract HTML and text content from $body
    // $fakeMessage = 'Mime-Version: 1.0\r\nContent-Type: ' . $headers['Content-Type'] . "\r\n\r\n" . $body;
    // if (!class_exists('Mail_mimeDecode')) {
    //   require_once 'Mail/mimeDecode.php';
    // }
    // $decoder = new \Mail_mimeDecode($fakeMessage);
    // $decoded = $decoder->decode();
    // $parser = new \ezcMailParser();
    // $parsed = $parser->parseMail(new \ezcMailVariableSet($fakeMessage));
    $mail->AltBody = 'TODO';
    $mail->msgHTML('<p>TODO</p>');
    $mail->send();

    // FIXME: But we also need to decode $body, because PhpMailer wants to do its own encoding.

    \Civi::log()->info('Unimplemented: ' . __METHOD__, [
      'recipients' => $recipients,
      'headers' => $headers,
      'body' => $body,
    ]);
  }

}
