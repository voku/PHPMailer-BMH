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

  // sample mysql code
  if ( $remove == true || $remove == '1' ) {
    echo "note: sample code would have set the database to allowed='false'<br />";
    /*
    $conn = mysql_connect("localhost","username","password");
    $sql = "SELECT id FROM mailinglist WHERE email = '" . $email . "'";
    $result = mysql_query($sql);
    if ( $result ) {
      while($row = mysql_fetch_array($result)) {
        $sql_update = "UPDATE mailinglist SET allowed='false' WHERE email = '" . $email . "'";
        $result_update = mysql_query($sql_update);
      }
    }
    mysql_close($conn);
    */
  }

  $displayData = prepData($email, $bounce_type, $remove);
  $bounce_type = $displayData['bounce_type'];
  $emailName   = $displayData['emailName'];
  $emailAddy   = $displayData['emailAddy'];
  $remove      = $displayData['remove'];

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
    $pos_end   = strpos($data['emailAddy'],'>');
    if ( $pos_end ) {
      $data['emailAddy'] = substr($data['emailAddy'],0,$pos_end);
    }
  }

  // replace the < and > able so they display on screen
  $email = str_replace('<','&lt;',$email);
  $email = str_replace('>','&gt;',$email);
  $data['email']     = $email;

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
