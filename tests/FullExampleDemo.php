<?php

use BounceMailHandler\BounceMailHandler;

/**
 * FullExampleDemo: process the mail-bounce analyse via cron-job
 */
class FullExampleDemo
{
  /**
   * @var string
   */
  protected $debug = '';

  /**
   * get the debug-string
   *
   * @return string
   */
  public function getDebug()
  {
    return $this->debug;
  }

  /**
   * run the bounce-handler
   */
  public function runBounceHandler()
  {
    $bmh = new BounceMailHandler();

    $bmh->actionFunction = array($this, 'callbackActionBounceHandler');

    // //BounceMailHandler::VERBOSE_SIMPLE;
    // //BounceMailHandler::VERBOSE_REPORT;
    // //BounceMailHandler::VERBOSE_DEBUG;
    // //BounceMailHandler::VERBOSE_QUIET;
    // // default is BounceMailHandler::VERBOSE_SIMPLE
    $bmh->verbose = BounceMailHandler::VERBOSE_DEBUG;

    //$bmh->useFetchStructure  = true; // true is default, no need to specify
    //$bmh->testMode           = false; // false is default, no need to specify
    //$bmh->debugBodyRule      = false; // false is default, no need to specify
    //$bmh->debugDsnRule       = false; // false is default, no need to specify
    //$bmh->purgeUnprocessed   = false; // false is default, no need to specify
    $bmh->disableDelete      = true; // false is default, no need to specify

    /*
     * for remote mailbox
     */
    $bmh->mailhost = '127.0.0.1'; // your mail server
    $bmh->mailboxUserName = 'testuser'; // your mailbox username
    $bmh->mailboxPassword = 'applesauce!'; // your mailbox password
    $bmh->port = 993; // the port to access your mailbox, default is 143
    $bmh->service = 'imap'; // the service to use (imap or pop3), default is 'imap'
    $bmh->serviceOption = 'ssl/novalidate-cert'; // the service options (none, tls, notls, ssl, etc.), default is 'notls'
    $bmh->boxname = 'INBOX'; // the mailbox to access, default is 'INBOX'

    $bmh->moveHard           = true; // default is false
    $bmh->hardMailbox        = 'INBOX.hardtest'; // default is 'INBOX.hard' - NOTE: must start with 'INBOX.'
    $bmh->moveSoft           = true; // default is false
    $bmh->softMailbox        = 'INBOX.softtest'; // default is 'INBOX.soft' - NOTE: must start with 'INBOX.'
    $bmh->deleteMsgDate      = date('Y-m-d', strtotime('last month')); // format must be as 'yyyy-mm-dd'

    /*
     * rest used regardless what type of connection it is
     */
    $bmh->openMailbox();
    $bmh->processMailbox();
  }

  /**
   * Callback (action) function
   *
   * This is a sample callback function for PHPMailer-BMH (Bounce Mail Handler).
   * This callback function will echo the results of the BMH processing.
   *
   * @param int            $msgnum              the message number returned by Bounce Mail Handler
   * @param string         $bounceType          the bounce type:
   *                                            'antispam','autoreply','concurrent','content_reject','command_reject','internal_error','defer','delayed'
   *                                            =>
   *                                            array('remove'=>0,'bounce_type'=>'temporary'),'dns_loop','dns_unknown','full','inactive','latin_only','other','oversize','outofoffice','unknown','unrecognized','user_reject','warning'
   * @param string         $email               the target email address
   * @param string         $subject             the subject, ignore now
   * @param string         $header              the XBounceHeader from the mail
   * @param boolean        $remove              remove status, 1 means removed, 0 means not removed
   * @param string|boolean $ruleNo              Bounce Mail Handler detect rule no.
   * @param string|boolean $ruleCat             Bounce Mail Handler detect rule category.
   * @param int            $totalFetched        total number of messages in the mailbox
   * @param string         $body                Bounce Mail Body
   * @param string         $headerFull          Bounce Mail Header
   * @param string         $bodyFull            Bounce Mail Body (full)
   *
   * @return boolean
   */
  public function callbackActionBounceHandler($msgnum, $bounceType, $email, $subject, /** @noinspection PhpUnusedParameterInspection */ $header, $remove, $ruleNo = false, $ruleCat = false, /** @noinspection PhpUnusedParameterInspection */ $totalFetched = 0, /** @noinspection PhpUnusedParameterInspection */ $body = '', /** @noinspection PhpUnusedParameterInspection */ $headerFull = '', /** @noinspection PhpUnusedParameterInspection */ $bodyFull = '')
  {
    // init
    //$db = DB::getInstance();

    $displayData = $this->prepDataForCallbackActionBounceHandler($email, $bounceType, $remove);
    $bounceType = $displayData['bounce_type'];
    //$emailName = $displayData['emailName'];
    //$emailAddy = $displayData['emailAddy'];
    $remove = $displayData['remove'];

    if ($bounceType == 'hard') {

      // increase hard-bounce counter for this recipient
      /*
      $sqlUpdate = "UPDATE pmaauto_recipients
        SET
          recipient_hardbounce_counter = recipient_hardbounce_counter+1,
          recipient_hardbounce_date = NOW()
        WHERE recipient_email = '" . $db->escape($email) . "'
      ";
      $db->query($sqlUpdate);
      */
    }

    // debug-output
    $this->debug .= $msgnum . ': ' . $ruleNo . ' | ' . $ruleCat . ' | ' . $bounceType . ' | ' . $remove . ' | ' . $email . ' | ' . $subject . "<br />\n";

    return true;
  }

  /**
   * Function to clean the data from the Callback Function for optimized display
   *
   * @param string $email
   * @param string $bounceType
   * @param string $remove
   *
   * @return array
   */
  protected function prepDataForCallbackActionBounceHandler($email, $bounceType, $remove)
  {
    // init
    $data = array();

    $data['bounce_type'] = trim($bounceType);
    $data['email'] = '';
    $data['emailName'] = '';
    $data['emailAddy'] = '';
    $data['remove'] = '';

    // fallback to 'emailAddy'
    $pos_start = strpos($email, '<');
    if ($pos_start !== false) {
      $data['emailName'] = trim(substr($email, 0, $pos_start));
      $data['emailAddy'] = substr($email, $pos_start + 1);
      $pos_end = strpos($data['emailAddy'], '>');
      if ($pos_end) {
        $data['emailAddy'] = substr($data['emailAddy'], 0, $pos_end);
      }
    }

    // replace the < and > able so they display on screen
    $email = str_replace(array('<', '>'), array('&lt;', '&gt;'), $email);
    $data['email'] = $email;

    // account for legitimate emails that have no bounce type
    if (trim($bounceType) == '') {
      $data['bounce_type'] = 'none';
    }

    // change the remove flag from true or 1 to textual representation
    if (
        stripos($remove, 'moved') !== false
        &&
        stripos($remove, 'hard') !== false
    ) {

      $data['removestat'] = 'moved (hard)';
      $data['remove'] = '<span style="color:red;">' . 'moved (hard)' . '</span>';

    } elseif (
        stripos($remove, 'moved') !== false
        &&
        stripos($remove, 'soft') !== false
    ) {

      $data['removestat'] = 'moved (soft)';
      $data['remove'] = '<span style="color:gray;">' . 'moved (soft)' . '</span>';

    } elseif (
        $remove == true
        ||
        $remove == '1'
    ) {

      $data['removestat'] = 'deleted';
      $data['remove'] = '<span style="color:red;">' . 'deleted' . '</span>';

    } else {

      $data['removestat'] = 'not deleted';
      $data['remove'] = '<span style="color:gray;">' . 'not deleted' . '</span>';

    }

    return $data;
  }
}