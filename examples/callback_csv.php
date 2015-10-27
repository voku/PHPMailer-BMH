<?php

/* This is a sample callback function for PHPMailer-BMH (Bounce Mail Handler).
 * This callback function will echo the results of the BMH processing.
 */

/**
 * Callback (action) function
 *
 * @param int            $msgnum              the message number returned by Bounce Mail Handler
 * @param string         $bounceType          the bounce type:
 *                                            'antispam','autoreply','concurrent','content_reject','command_reject','internal_error','defer','delayed'
 *                                            =>
 *                                            array('remove'=>0,'bounce_type'=>'temporary'),'dns_loop','dns_unknown','full','inactive','latin_only','other','oversize','outofoffice','unknown','unrecognized','user_reject','warning'
 * @param string         $email               the target email address
 * @param string         $subject             the subject, ignore now
 * @param string         $xheader             the XBounceHeader from the mail
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
function callbackAction($msgnum, $bounceType, $email, $subject, $xheader, $remove, $ruleNo = false, $ruleCat = false, $totalFetched = 0, $body = '', $headerFull = '', $bodyFull = '')
{
  $currentTime = date('Y-m-d H:i:s', time());

  $displayData = prepData($email, $bounceType, $remove);
  $bounceType = $displayData['bounce_type'];
  $emailName = $displayData['emailName'];
  $emailAddy = $displayData['emailAddy'];
  $remove = $displayData['remove'];
  $removeraw = $displayData['removestat'];

  $msg = $msgnum . ',' . $currentTime . ',' . $ruleNo . ',' . $ruleCat . ',' . $bounceType . ',' . $removeraw . ',' . $email . ',' . $subject;

  $filename = 'logs/bouncelog_' . date('m') . date('Y') . '.csv';
  if (!file_exists($filename)) {
    $tmsg = 'Msg#,Current Time,Rule Number,Rule Category,Bounce Type,Status,Email,Subject' . "\n" . $msg;
  } else {
    $fileContents = file_get_contents($filename);

    if (stripos($fileContents, "\n" . $msgnum . ',') !== false) {
      $doPutFile = false;
    }
    
    $tmsg = $msg;
  }

  $handle = fopen($filename, 'a');
  if ($handle) {
    if (fwrite($handle, $tmsg . "\n") === false) {
      echo 'Cannot write message<br />';
    }
    fclose($handle);
  } else {
    echo 'Cannot open file to append<br />';
  }

  echo $msgnum . ': ' . $ruleNo . ' | ' . $ruleCat . ' | ' . $bounceType . ' | ' . $remove . ' | ' . $email . ' | ' . $subject . "<br />\n";

  return true;
}

/**
 * Function to clean the data from the Callback Function for optimized display
 *
 * @param $email
 * @param $bounce_type
 * @param $remove
 *
 * @return mixed
 */
function prepData($email, $bounce_type, $remove)
{
  $data['bounce_type'] = trim($bounce_type);
  $data['email'] = '';
  $data['emailName'] = '';
  $data['emailAddy'] = '';
  $data['remove'] = '';
  if (strpos($email, '<') !== false) {
    $pos_start = strpos($email, '<');
    $data['emailName'] = trim(substr($email, 0, $pos_start));
    $data['emailAddy'] = substr($email, $pos_start + 1);
    $pos_end = strpos($data['emailAddy'], '>');
    if ($pos_end) {
      $data['emailAddy'] = substr($data['emailAddy'], 0, $pos_end);
    }
  }

  // replace the < and > able so they display on screen
  // replace the < and > able so they display on screen
  $email = str_replace(array('<', '>'), array('&lt;', '&gt;'), $email);

  // replace the "TO:<" with nothing
  $email = str_ireplace('TO:<', '', $email);

  $data['email'] = $email;

  // account for legitimate emails that have no bounce type
  if (trim($bounce_type) == '') {
    $data['bounce_type'] = 'none';
  }

  // change the remove flag from true or 1 to textual representation
  if (stripos($remove, 'moved') !== false && stripos($remove, 'hard') !== false) {
    $data['removestat'] = 'moved (hard)';
    $data['remove'] = '<span style="color:red;">' . 'moved (hard)' . '</span>';
  } elseif (stripos($remove, 'moved') !== false && stripos($remove, 'soft') !== false) {
    $data['removestat'] = 'moved (soft)';
    $data['remove'] = '<span style="color:gray;">' . 'moved (soft)' . '</span>';
  } elseif ($remove == true || $remove == '1') {
    $data['removestat'] = 'deleted';
    $data['remove'] = '<span style="color:red;">' . 'deleted' . '</span>';
  } else {
    $data['removestat'] = 'not deleted';
    $data['remove'] = '<span style="color:gray;">' . 'not deleted' . '</span>';
  }

  return $data;
}
