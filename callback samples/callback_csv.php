<?php

/* This is a sample callback function for PHPMailer-BMH (Bounce Mail Handler).
 * This callback function will echo the results of the BMH processing.
 */

/* Callback (action) function
 * @param int     $msgnum        the message number returned by Bounce Mail Handler
 * @param string  $bounce_type   the bounce type: 'antispam','autoreply','concurrent','content_reject','command_reject','internal_error','defer','delayed'        => array('remove'=>0,'bounce_type'=>'temporary'),'dns_loop','dns_unknown','full','inactive','latin_only','other','oversize','outofoffice','unknown','unrecognized','user_reject','warning'
 * @param string  $email         the target email address
 * @param string  $subject       the subject, ignore now
 * @param string  $xheader       the XBounceHeader from the mail
 * @param boolean $remove        remove status, 1 means removed, 0 means not removed
 * @param string  $rule_no       Bounce Mail Handler detect rule no.
 * @param string  $rule_cat      Bounce Mail Handler detect rule category.
 * @param int     $totalFetched  total number of messages in the mailbox
 * @return boolean
 */
function callbackAction ($msgnum, $bounce_type, $email, $subject, $xheader, $remove, $rule_no=false, $rule_cat=false, $totalFetched=0) {

  $currentTime  = date( 'Y-m-d H:i:s', time());

  $displayData = prepData($email, $bounce_type, $remove);
  $bounce_type = $displayData['bounce_type'];
  $emailName   = $displayData['emailName'];
  $emailAddy   = $displayData['emailAddy'];
  $remove      = $displayData['remove'];
  $removeraw   = $displayData['removestat'];

  $msg      = $msgnum . ',' . $currentTime . ',' . $rule_no . ',' . $rule_cat . ',' . $bounce_type . ',' . $removeraw . ',' . $email . ',' . $subject;

  $filename = 'logs/bouncelog_' . date('m') . date('Y') . '.csv';
  if ( !file_exists($filename) ) {
    $tmsg = 'Msg#,Current Time,Rule Number,Rule Category,Bounce Type,Status,Email,Subject' . "\n" . $msg;
  } else {
    $fileContents = file_get_contents($filename);
    if ( stristr($fileContents, "\n" . $msgnum . ',') ) {
      $doPutFile = false;
    } else {
    }
    $tmsg = $msg;
  }
  if ( $handle = fopen($filename, 'a') ) {
    if (fwrite($handle, $tmsg . "\n") === FALSE) {
      echo 'Cannot write message<br />';
    }
    fclose($handle);
  } else {
    echo 'Cannot open file to append<br />';
  }

  echo $msgnum . ': '  . $rule_no . ' | '  . $rule_cat . ' | '  . $bounce_type . ' | '  . $remove . ' | ' . $email . ' | '  . $subject . "<br />\n";

  return true;
}

/* Function to clean the data from the Callback Function for optimized display */
function prepData($email, $bounce_type, $remove) {
  $data['bounce_type'] = trim($bounce_type);
  $data['email']       = '';
  $data['emailName']   = '';
  $data['emailAddy']   = '';
  $data['remove']      = '';
  if ( strstr($email,'<') ) {
    $pos_start = strpos($email,'<');
    $data['emailName'] = trim(substr($email,0,$pos_start));
    $data['emailAddy'] = substr($email,$pos_start + 1);
    $pos_end = strpos($data['emailAddy'],'>');
    if ( $pos_end ) {
      $data['emailAddy'] = substr($data['emailAddy'],0,$pos_end);
    }
  }

  // replace the < and > able so they display on screen
  $email = str_replace('<','&lt;',$email);
  $email = str_replace('>','&gt;',$email);
  $data['email'] = $email;

  // account for legitimate emails that have no bounce type
  if ( trim($bounce_type) == '' ) {
    $data['bounce_type'] = 'none';
  }

  // change the remove flag from true or 1 to textual representation
  if ( stristr($remove,'moved') && stristr($remove,'hard') ) {
    $data['removestat'] = 'moved (hard)';
    $data['remove'] = '<span style="color:red;">' . 'moved (hard)' . '</span>';
  } elseif ( stristr($remove,'moved') && stristr($remove,'soft') ) {
    $data['removestat'] = 'moved (soft)';
    $data['remove'] = '<span style="color:gray;">' . 'moved (soft)' . '</span>';
  } elseif ( $remove == true || $remove == '1' ) {
    $data['removestat'] = 'deleted';
    $data['remove'] = '<span style="color:red;">' . 'deleted' . '</span>';
  } else {
    $data['removestat'] = 'not deleted';
    $data['remove'] = '<span style="color:gray;">' . 'not deleted' . '</span>';
  }

  return $data;
}
