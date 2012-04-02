<?php
/*~ index.php
.---------------------------------------------------------------------------.
|  Software: PHPMailer-BMH (Bounce Mail Handler)                            |
|   Version: 5.2-dev                                                        |
|   Contact: codeworxtech@users.sourceforge.net                             |
|      Info: http://phpmailer.codeworxtech.com                              |
| ------------------------------------------------------------------------- |
|    Author: Andy Prevost andy.prevost@worxteam.com (admin)                 |
| Copyright (c) 2002-2009, Andy Prevost. All Rights Reserved.               |
| ------------------------------------------------------------------------- |
|   License: Distributed under the General Public License (GPL)             |
|            (http://www.gnu.org/licenses/gpl.html)                         |
| This program is distributed in the hope that it will be useful - WITHOUT  |
| ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or     |
| FITNESS FOR A PARTICULAR PURPOSE.                                         |
| ------------------------------------------------------------------------- |
| This is a update of the original Bounce Mail Handler script               |
| http://sourceforge.net/projects/bmh/                                      |
| The script has been renamed from Bounce Mail Handler to PHPMailer-BMH     |
| ------------------------------------------------------------------------- |
| We offer a number of paid services:                                       |
| - Web Hosting on highly optimized fast and secure servers                 |
| - Technology Consulting                                                   |
| - Oursourcing (highly qualified programmers and graphic designers)        |
'---------------------------------------------------------------------------'
Last updated: January 21 2009 13:38 EST

/*
 * This is an example script to work with PHPMailer-BMH (Bounce Mail Handler).
 */

$time_start = microtime_float();

// Use ONE of the following -- all echo back to the screen
require_once('callback samples/callback_echo.php');
//require_once('callback samples/callback_database.php'); // NOTE: Requires modification to insert your database settings
//require_once('callback samples/callback_csv.php'); // NOTE: Requires creation of a 'logs' directory and making writable

// determine the current directory
$dirTmp = getcwd();
// define the "base" directory of the application
if (!defined('_PATH_BMH')) {
  $dirTmp = $_SERVER['DOCUMENT_ROOT'] . '/' . $dirTmp;
  if ( strlen( substr($dirTmp,strlen($_SERVER['DOCUMENT_ROOT']) + 1) ) > 0 ) {
    define('_PATH_BMH', substr($dirTmp,strlen($_SERVER['DOCUMENT_ROOT']) + 1) . "/");
  } else {
    define('_PATH_BMH', '');
  }
}
// END determine the current directory

include(_PATH_BMH . 'class.phpmailer-bmh.php');

// testing examples
$bmh = new BounceMailHandler();
//$bmh->action_function    = 'callbackAction'; // default is 'callbackAction'
//$bmh->verbose            = VERBOSE_SIMPLE; //VERBOSE_REPORT; //VERBOSE_DEBUG; //VERBOSE_QUIET; // default is VERBOSE_SIMPLE
//$bmh->use_fetchstructure = true; // true is default, no need to speficy
//$bmh->testmode           = false; // false is default, no need to specify
//$bmh->debug_body_rule    = false; // false is default, no need to specify
//$bmh->debug_dsn_rule     = false; // false is default, no need to specify
//$bmh->purge_unprocessed  = false; // false is default, no need to specify
//$bmh->disable_delete     = false; // false is default, no need to specify

/*
 * for local mailbox (to process .EML files)
 */
//$bmh->openLocal('/home/email/temp/mailbox');
//$bmh->processMailbox();

/*
 * for remote mailbox
 */
$bmh->mailhost           = ''; // your mail server
$bmh->mailbox_username   = ''; // your mailbox username
$bmh->mailbox_password   = ''; // your mailbox password
//$bmh->port               = 143; // the port to access your mailbox, default is 143
//$bmh->service            = 'imap'; // the service to use (imap or pop3), default is 'imap'
//$bmh->service_option     = 'notls'; // the service options (none, tls, notls, ssl, etc.), default is 'notls'
//$bmh->boxname            = 'INBOX'; // the mailbox to access, default is 'INBOX'
//$bmh->moveHard           = true; // default is false
//$bmh->hardMailbox        = 'INBOX.hardtest'; // default is 'INBOX.hard' - NOTE: must start with 'INBOX.'
//$bmh->moveSoft           = true; // default is false
//$bmh->softMailbox        = 'INBOX.softtest'; // default is 'INBOX.soft' - NOTE: must start with 'INBOX.'
//$bmh->deleteMsgDate      = '2009-01-05'; // format must be as 'yyyy-mm-dd'

/*
 * rest used regardless what type of connection it is
 */
$bmh->openMailbox();
$bmh->processMailbox();

echo '<hr style="width:200px;" />';
$time_end = microtime_float();
$time     = $time_end - $time_start;
echo "Seconds to process: " . $time . "<br />";

function microtime_float() {
  list($usec, $sec) = explode(" ", microtime());
  return ((float)$usec + (float)$sec);
}
