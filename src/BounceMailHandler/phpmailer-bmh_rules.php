<?php

/*~ phpmailer-bmh_rules.php
.---------------------------------------------------------------------------.
|  Software: PHPMailer-BMH (Bounce Mail Handler)                            |
|   Version: 6.0-dev                                                        |
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

/**
 * next rule number (BODY): 0257 <br />
 * default category:        unrecognized: <br />
 * default rule no.:        0000 <br />
 */

global $rule_categories;

$rule_categories = [
    'antispam'       => ['remove' => 0, 'bounce_type' => 'blocked'],
    'autoreply'      => ['remove' => 0, 'bounce_type' => 'autoreply'],
    'concurrent'     => ['remove' => 0, 'bounce_type' => 'soft'],
    'content_reject' => ['remove' => 0, 'bounce_type' => 'soft'],
    'command_reject' => ['remove' => 1, 'bounce_type' => 'hard'],
    'internal_error' => ['remove' => 0, 'bounce_type' => 'temporary'],
    'defer'          => ['remove' => 0, 'bounce_type' => 'soft'],
    'delayed'        => ['remove' => 0, 'bounce_type' => 'temporary'],
    'dns_loop'       => ['remove' => 1, 'bounce_type' => 'hard'],
    'dns_unknown'    => ['remove' => 1, 'bounce_type' => 'hard'],
    'full'           => ['remove' => 0, 'bounce_type' => 'soft'],
    'inactive'       => ['remove' => 1, 'bounce_type' => 'hard'],
    'latin_only'     => ['remove' => 0, 'bounce_type' => 'soft'],
    'other'          => ['remove' => 1, 'bounce_type' => 'generic'],
    'oversize'       => ['remove' => 0, 'bounce_type' => 'soft'],
    'outofoffice'    => ['remove' => 0, 'bounce_type' => 'soft'],
    'unknown'        => ['remove' => 1, 'bounce_type' => 'hard'],
    'unrecognized'   => ['remove' => 0, 'bounce_type' => false],
    'user_reject'    => ['remove' => 1, 'bounce_type' => 'hard'],
    'warning'        => ['remove' => 0, 'bounce_type' => 'soft'],
];

/*
 * var for new line ending
 */
$bmh_newline = "<br />\n";

/**
 * Defined bounce parsing rules for non-standard DSN
 *
 * @param string $body       body of the email
 * @param string $structure  message structure
 * @param bool   $debug_mode show debug info. or not
 *
 * @return array $result an array include the following fields: 'email', 'bounce_type','remove','rule_no','rule_cat'
 *               if we could NOT detect the type of bounce, return rule_no = '0000'
 */
function bmhBodyRules($body, /** @noinspection PhpUnusedParameterInspection */ $structure, $debug_mode = false): array
{
    global $rule_categories;
    global $bmh_newline;

    // initialize the result array
    $result = [
        'email'           => '',
        'bounce_type'     => false,
        'remove'          => 0,
        'rule_cat'        => 'unrecognized',
        'rule_no'         => '0000',
        'status_code'     => '',
        'action'          => '',
        'diagnostic_code' => '',
    ];

    // ======== rules =========

    /* rule: dns_unknown
     * sample:
     *   Technical details of permanent failure:
     *   DNS Error: Domain name not found
     */
    if (\preg_match("/domain\s+name\s+not\s+found/i", $body, $match)) {
        $result['rule_cat'] = 'dns_unknown';
        $result['rule_no'] = '0999';
    } /* rule: unknown
   * sample:
   *   xxxxx@yourdomain.com
   *   no such address here
   */
    elseif (\preg_match("/no\s+such\s+address\s+here/i", $body, $match)) {
        $result['rule_cat'] = 'unknown';
        $result['rule_no'] = '0237';
    } /* Gmail Bounce Error
   * rule: unknown
   * sample:
   *   Delivery to the following recipient failed permanently:
   *   xxxxx@yourdomain.com
   */
    elseif (
      \strpos($body, 'Technical details of permanent failure') === false // if there are technical details, try another test-case
      &&
      \preg_match("/Delivery to the following (?:recipient|recipients) failed permanently\X*?(\S+@\S+\w)/ui", $body, $match)
  ) {
        $result['rule_cat'] = 'unknown';
        $result['rule_no'] = '0998';
        $result['email'] = $match[1];
    } /*
   * rule: unknown
   * sample:
   * <xxxxx@yourdomain.com>: host mail-host[111.111.111.111]
    said: 550 5.1.1 This user does not exist
   */
    elseif (\preg_match("/user.+?not\s+exist/i", $body, $match)) {
        $result['rule_cat'] = 'unknown';
        $result['rule_no'] = '02361';
    } /* rule: unknown
   * sample:
   *   <xxxxx@yourdomain.com>:
   *   111.111.111.111 does not like recipient.
   *   Remote host said: 550 User unknown
   */
    elseif (\preg_match("/user\s+unknown/i", $body, $match)) {
        $result['rule_cat'] = 'unknown';
        $result['rule_no'] = '0236';
    } /* rule: unknown
   * sample:
   *
   */
    elseif (\preg_match("/unknown\s+user/i", $body, $match)) {
        $result['rule_cat'] = 'unknown';
        $result['rule_no'] = '0249';
    } /* rule: unknown
   * sample:
   *   <xxxxx@yourdomain.com>:
   *   Sorry, no mailbox here by that name. vpopmail (#5.1.1)
   */
    elseif (\preg_match("/no\s+mailbox/i", $body, $match)) {
        $result['rule_cat'] = 'unknown';
        $result['rule_no'] = '0157';
    } /* rule: unknown
   * sample:
   *   xxxxx@yourdomain.com<br>
   *   local: Sorry, can't find user's mailbox. (#5.1.1)<br>
   */
    elseif (\preg_match("/can't\s+find.*mailbox/i", $body, $match)) {
        $result['rule_cat'] = 'unknown';
        $result['rule_no'] = '0164';
    } /* rule: unknown
   * sample:
   *   ##########################################################
   *   #  This is an automated response from a mail delivery    #
   *   #  program.  Your message could not be delivered to      #
   *   #  the following address:                                #
   *   #                                                        #
   *   #      "|/usr/local/bin/mailfilt -u #dkms"               #
   *   #        (reason: Can't create output)                   #
   *   #        (expanded from: <xxxxx@yourdomain.com>)         #
   *   #                                                        #
   */
    elseif (\preg_match("/Can't\s+create\s+output.*<(\S+@\S+\w)>/is", $body, $match)) {
        $result['rule_cat'] = 'unknown';
        $result['rule_no'] = '0169';
        $result['email'] = $match[1];
    } /* rule: unknown
   * sample:
   *   ????????????????:
   *   xxxxx@yourdomain.com : ????, ?????.
   */
    elseif (\preg_match('/=D5=CA=BA=C5=B2=BB=B4=E6=D4=DA/i', $body, $match)) {
        $result['rule_cat'] = 'unknown';
        $result['rule_no'] = '0174';
    } /* rule: unknown
   * sample:
   *   xxxxx@yourdomain.com
   *   Unrouteable address
   */
    elseif (\preg_match("/Unrouteable\s+address/i", $body, $match)) {
        $result['rule_cat'] = 'unknown';
        $result['rule_no'] = '0179';
    } /* rule: unknown
   * sample:
   *   Delivery to the following recipients failed.
   *   xxxxx@yourdomain.com
   */
    elseif (\preg_match("/delivery[^\n\r]+failed\S*\s+(\S+@\S+\w)\s/i", $body, $match)) {
        $result['rule_cat'] = 'unknown';
        $result['rule_no'] = '0013';
        $result['email'] = $match[1];
    } /* rule: unknown
   * sample:
   *   A message that you sent could not be delivered to one or more of its
   *   recipients. This is a permanent error. The following address(es) failed:
   *
   *   xxxxx@yourdomain.com
   *   unknown local-part "xxxxx" in domain "yourdomain.com"
   */
    elseif (\preg_match("/unknown\s+local-part/i", $body, $match)) {
        $result['rule_cat'] = 'unknown';
        $result['rule_no'] = '0232';
    } /* rule: unknown
   * sample:
   *   <xxxxx@yourdomain.com>:
   *   111.111.111.11 does not like recipient.
   *   Remote host said: 550 Invalid recipient: <xxxxx@yourdomain.com>
   */
    elseif (\preg_match("/Invalid.*(?:alias|account|recipient|address|email|mailbox|user).*<(\S+@\S+\w)>/is", $body, $match)) {
        $result['rule_cat'] = 'unknown';
        $result['rule_no'] = '0233';
        $result['email'] = $match[1];
    } /* rule: unknown
   * sample:
   *   Sent >>> RCPT TO: <xxxxx@yourdomain.com>
   *   Received <<< 550 xxxxx@yourdomain.com... No such user
   *
   *   Could not deliver mail to this user.
   *   xxxxx@yourdomain.com
   *   *****************     End of message     ***************
   */
    elseif (\preg_match("/No\s+such.*(?:alias|account|recipient|address|email|mailbox|user).*<(\S+@\S+\w)>/is", $body, $match)) {
        $result['rule_cat'] = 'unknown';
        $result['rule_no'] = '0234';
        $result['email'] = $match[1];
    } /* rule: unknown
   * sample:
   *   Diagnostic-Code: X-Notes; Recipient user name info (a@b.c) not unique.  Several matches found in Domino Directory.
   */
    elseif (\preg_match('/not unique.\s+Several matches found/i', $body, $match)) {
        $result['rule_cat'] = 'unknown';
        $result['rule_no'] = '0254';
    } /* rule: full
   * sample 1:
   *   <xxxxx@yourdomain.com>:
   *   This account is over quota and unable to receive mail.
   *   sample 2:
   *   <xxxxx@yourdomain.com>:
   *   Warning: undefined mail delivery mode: normal (ignored).
   *   The users mailfolder is over the allowed quota (size). (#5.2.2)
   */
    elseif (\preg_match('/over.*quota/i', $body, $match)) {
        $result['rule_cat'] = 'full';
        $result['rule_no'] = '0182';
    } /* rule: full
   * sample:
   *   ----- Transcript of session follows -----
   *   mail.local: /var/mail/2b/10/kellen.lee: Disc quota exceeded
   *   554 <xxxxx@yourdomain.com>... Service unavailable
   */
    elseif (\preg_match("/quota\s+exceeded.*<(\S+@\S+\w)>/is", $body, $match)) {
        $result['rule_cat'] = 'full';
        $result['rule_no'] = '0126';
        $result['email'] = $match[1];
    } /* rule: full
   * sample:
   *   Hi. This is the qmail-send program at 263.domain.com.
   *   <xxxxx@yourdomain.com>:
   *   - User disk quota exceeded. (#4.3.0)
   */
    elseif (\preg_match("/quota\s+exceeded/i", $body, $match)) {
        $result['rule_cat'] = 'full';
        $result['rule_no'] = '0158';
    } /* rule: full
   * sample:
   *   xxxxx@yourdomain.com
   *   mailbox is full (MTA-imposed quota exceeded while writing to file /mbx201/mbx011/A100/09/35/A1000935772/mail/.inbox):
   */
    elseif (\preg_match('/mailbox.*full/i', $body, $match)) {
        $result['rule_cat'] = 'full';
        $result['rule_no'] = '0166';
    } /* rule: full
   * sample:
   *   The message to xxxxx@yourdomain.com is bounced because : Quota exceed the hard limit
   */
    elseif (\preg_match("/The message to (\S+@\S+\w)\s.*bounce.*Quota exceed/i", $body, $match)) {
        $result['rule_cat'] = 'full';
        $result['rule_no'] = '0168';
        $result['email'] = $match[1];
    } /* rule: full
   * sample:
   *   Message rejected. Not enough storage space in user's mailbox to accept message.
   */
    elseif (\preg_match("/not\s+enough\s+storage\s+space/i", $body, $match)) {
        $result['rule_cat'] = 'full';
        $result['rule_no'] = '0253';
    } /* rule: inactive
   * sample:
   *   xxxxx@yourdomain.com<br>
   *   553 user is inactive (eyou mta)
   */
    elseif (\preg_match('/user is inactive/i', $body, $match)) {
        $result['rule_cat'] = 'inactive';
        $result['rule_no'] = '0171';
    } /*
   * <xxxxx@xxx.xxx> is restricted
   */
    elseif (\preg_match("/(\S+@\S+\w).*n? is restricted/i", $body, $match)) {
        $result['rule_cat'] = 'inactive';
        $result['rule_no'] = '0201';
        $result['email'] = $match[1];
    } /* rule: inactive
   * sample:
   *   xxxxx@yourdomain.com [Inactive account]
   */
    elseif (\preg_match('/inactive account/i', $body, $match)) {
        $result['rule_cat'] = 'inactive';
        $result['rule_no'] = '0181';
    } /*
   *<xxxxxx@xxxx.xxx>: host mx3.HOTMAIL.COM said: 550
   * Requested action not taken: mailbox unavailable (in reply to RCPT TO command)
   */
    elseif (\preg_match("/<(\S+@\S+\w)>.*\n.*mailbox unavailable/i", $body, $match)) {
        $result['rule_cat'] = 'unknown';
        $result['rule_no'] = '124';
        $result['email'] = $match[1];
    } /*
   * rule: mailbox unknown;
   * sample:
   * xxxxx@yourdomain.com
   * 550-5.1.1 The email
   * account that you tried to reach does not exist. Please try 550-5.1.1
   * double-checking the recipient's email address for typos or 550-5.1.1
   * unnecessary spaces. Learn more at 550 5.1.1
   * http://support.google.com/mail/bin/answer.py?answer=6596 n7si4762785wiy.46
   * (in reply to RCPT TO command)
   */
    elseif (\preg_match("/<(\S+@\S+\w)>.*\n?.*\n?.*account that you tried to reach does not exist/i", $body, $match)) {
        $result['rule_cat'] = 'unknown';
        $result['rule_no'] = '7770';
        $result['email'] = $match[1];
    } /* rule: dns_unknown
   * sample1:
   *   Delivery to the following recipient failed permanently:
   *
   *     a@b.c
   *
   *   Technical details of permanent failure:
   *   TEMP_FAILURE: Could not initiate SMTP conversation with any hosts:
   *   [b.c (1): Connection timed out]
   * sample2:
   *   Delivery to the following recipient failed permanently:
   *
   *     a@b.c
   *
   *   Technical details of permanent failure:
   *   TEMP_FAILURE: Could not initiate SMTP conversation with any hosts:
   *   [pop.b.c (1): Connection dropped]
   */
    elseif (\preg_match('/Technical details of permanent failure:\s+TEMP_FAILURE: Could not initiate SMTP conversation with any hosts/i', $body, $match)) {
        $result['rule_cat'] = 'dns_unknown';
        $result['rule_no'] = '0251';
    } /* rule: delayed
   * sample:
   *   Delivery to the following recipient has been delayed:
   *
   *     a@b.c
   *
   *   Message will be retried for 2 more day(s)
   *
   *   Technical details of temporary failure:
   *   TEMP_FAILURE: Could not initiate SMTP conversation with any hosts:
   *   [b.c (50): Connection timed out]
   */
    elseif (\preg_match('/Technical details of temporary failure:\s+TEMP_FAILURE: Could not initiate SMTP conversation with any hosts/i', $body, $match)) {
        $result['rule_cat'] = 'delayed';
        $result['rule_no'] = '0252';
    } /* rule: delayed
   * sample:
   *   Delivery to the following recipient has been delayed:
   *
   *     a@b.c
   *
   *   Message will be retried for 2 more day(s)
   *
   *   Technical details of temporary failure:
   *   TEMP_FAILURE: The recipient server did not accept our requests to connect. Learn more at ...
   *   [b.c (10): Connection dropped]
   */
    elseif (\preg_match('/Technical details of temporary failure:\s+TEMP_FAILURE: The recipient server did not accept our requests to connect./i', $body, $match)) {
        $result['rule_cat'] = 'delayed';
        $result['rule_no'] = '0256';
    } /* rule: internal_error
   * sample:
   *   <xxxxx@yourdomain.com>:
   *   Unable to switch to /var/vpopmail/domains/domain.com: input/output error. (#4.3.0)
   */
    elseif (\preg_match("/input\/output error/i", $body, $match)) {
        $result['rule_cat'] = 'internal_error';
        $result['rule_no'] = '0172';
        $result['bounce_type'] = 'hard';
        $result['remove'] = 1;
    } /* rule: internal_error
   * sample:
   *   <xxxxx@yourdomain.com>:
   *   can not open new email file errno=13 file=/home/vpopmail/domains/fromc.com/0/domain/Maildir/tmp/1155254417.28358.mx05,S=212350
   */
    elseif (\preg_match('/can not open new email file/i', $body, $match)) {
        $result['rule_cat'] = 'internal_error';
        $result['rule_no'] = '0173';
        $result['bounce_type'] = 'hard';
        $result['remove'] = 1;
    } /* rule: defer
   * sample:
   *   <xxxxx@yourdomain.com>:
   *   111.111.111.111 failed after I sent the message.
   *   Remote host said: 451 mta283.mail.scd.yahoo.com Resources temporarily unavailable. Please try again later [#4.16.5].
   */
    elseif (\preg_match('/Resources temporarily unavailable/i', $body, $match)) {
        $result['rule_cat'] = 'defer';
        $result['rule_no'] = '0163';
    } /* rule: autoreply
   * sample:
   *   AutoReply message from xxxxx@yourdomain.com
   */
    elseif (\preg_match("/^AutoReply message from (\S+@\S+\w)/i", $body, $match)) {
        $result['rule_cat'] = 'autoreply';
        $result['rule_no'] = '0167';
        $result['email'] = $match[1];
    } /* rule: block
   * sample:
   *   Delivery to the following recipient failed permanently:
   *     a@b.c
   *   Technical details of permanent failure:
   *   PERM_FAILURE: SMTP Error (state 9): 550 5.7.1 Your message (sent through 209.85.132.244) was blocked by ROTA DNSBL. If you are not a spammer, open http://www.rota.lv/DNSBL and follow instructions or call +371 7019029, or send an e-mail message from another address to dz@ROTA.lv with the blocked sender e-mail name.
   */
    elseif (\preg_match("/Your message \([^)]+\) was blocked by/i", $body, $match)) {
        $result['rule_cat'] = 'antispam';
        $result['rule_no'] = '0250';
    } /* rule: content_reject
   * sample:
   *   Failed to deliver to '<a@b.c>'
   *   Messages without To: fields are not accepted here
   */
    elseif (\preg_match("/Messages\s+without\s+\S+\s+fields\s+are\s+not\s+accepted\s+here/i", $body, $match)) {
        $result['rule_cat'] = 'content_reject';
        $result['rule_no'] = '0248';
    }  /* rule: inactive
   * sample:
   *   <xxxxx@yourdomain.com>:
   *   This address no longer accepts mail.
   */
    elseif (\preg_match("/(?:alias|account|recipient|address|email|mailbox|user).*no\s+longer\s+accepts\s+mail/i", $body, $match)) {
        $result['rule_cat'] = 'inactive';
        $result['rule_no'] = '0235';
    } /* rule: western chars only
   * sample:
   *   <xxxxx@yourdomain.com>:
   *   The user does not accept email in non-Western (non-Latin) character sets.
   */
    elseif (\preg_match("/does not accept[^\r\n]*non-Western/i", $body, $match)) {
        $result['rule_cat'] = 'latin_only';
        $result['rule_no'] = '0043';
    } /* rule: unknown
   * sample:
   *   554 delivery error
   *   This user doesn't have a yahoo.com account
   */
    elseif (\preg_match("/554.*delivery error.*this user.*doesn't have.*account/is", $body, $match)) {
        $result['rule_cat'] = 'unknown';
        $result['rule_no'] = '0044';
    } /* rule: unknown
   * sample:
   *   550 hotmail.com
   */
    elseif (\preg_match('/550.*Requested.*action.*not.*taken:.*mailbox.*unavailable/is', $body, $match)) {
        $result['rule_cat'] = 'unknown';
        $result['rule_no'] = '0045';
    } /* rule: unknown
   * sample:
   *   550 5.1.1 aim.com
   */
    elseif (\preg_match("/550 5\.1\.1.*Recipient address rejected/is", $body, $match)) {
        $result['rule_cat'] = 'unknown';
        $result['rule_no'] = '0046';
    } /* rule: unknown
   * sample:
   *   550 .* (in reply to end of DATA command)
   */
    elseif (\preg_match('/550.*in reply to end of DATA command/i', $body, $match)) {
        $result['rule_cat'] = 'unknown';
        $result['rule_no'] = '0047';
    } /* rule: unknown
   * sample:
   *   550 .* (in reply to RCPT TO command)
   */
    elseif (\preg_match('/550.*in reply to RCPT TO command/i', $body, $match)) {
        $result['rule_cat'] = 'unknown';
        $result['rule_no'] = '0048';
    } /* rule: dns_unknown
   * sample:
   *    a@b.c:
   *      unrouteable mail domain "b.c"
   */
    elseif (\preg_match("/unrouteable\s+mail\s+domain/i", $body, $match)) {
        $result['rule_cat'] = 'dns_unknown';
        $result['rule_no'] = '0247';
    }

    if ($result['rule_no'] !== '0000' && $result['email'] === '') {
        $preBody = \substr($body, 0, \strpos($body, $match[0]));

        $count = \preg_match_all('/(\S+@\S+)/', $preBody, $match);
        if ($count) {
            $result['email'] = \trim($match[1][$count - 1], "'\"()<>.:; \t\r\n\0\x0B");
        }
    }

    if ($result['rule_no'] == '0000') {
        if ($debug_mode) {
            echo 'Body:' . $bmh_newline . $body . $bmh_newline;
            echo $bmh_newline;
        }
    } else {
        if ($result['bounce_type'] === false) {
            $result['bounce_type'] = $rule_categories[$result['rule_cat']]['bounce_type'];
            $result['remove'] = $rule_categories[$result['rule_cat']]['remove'];
        }
    }

    return $result;
}

/**
 * Defined bounce parsing rules for standard DSN (Delivery Status Notification)
 *
 * @param string $dsn_msg    human-readable explanation
 * @param string $dsn_report delivery-status report
 * @param bool   $debug_mode show debug info. or not
 *
 * @return array $result an array include the following fields: 'email', 'bounce_type','remove','rule_no','rule_cat'
 *               if we could NOT detect the type of bounce, return rule_no = '0000'
 */
function bmhDSNRules($dsn_msg, $dsn_report, $debug_mode = false): array
{
    global $rule_categories;
    global $bmh_newline;

    // initialize the result array
    $result = [
        'email'           => '',
        'bounce_type'     => false,
        'remove'          => 0,
        'rule_cat'        => 'unrecognized',
        'rule_no'         => '0000',
        'status_code'     => '',
        'action'          => '',
        'diagnostic_code' => '',
    ];
    $action = false;
    $status_code = false;
    $diag_code = false;

    // ======= parse $dsn_report ======
    // get the recipient email
    if (\preg_match('/Original-Recipient: rfc822;(.*)/i', $dsn_report, $match)) {
        $email = \trim($match[1], "<> \t\r\n\0\x0B");
        /** @noinspection PhpUsageOfSilenceOperatorInspection */
        $email_arr = @\imap_rfc822_parse_adrlist($email, 'default.domain.name');
        if (isset($email_arr[0]->host) && $email_arr[0]->host != '.SYNTAX-ERROR.' && $email_arr[0]->host != 'default.domain.name') {
            $result['email'] = $email_arr[0]->mailbox . '@' . $email_arr[0]->host;
        }
    } elseif (\preg_match('/Final-Recipient: rfc822;(.*)/i', $dsn_report, $match)) {
        $email = \trim($match[1], "<> \t\r\n\0\x0B");
        /** @noinspection PhpUsageOfSilenceOperatorInspection */
        $email_arr = @\imap_rfc822_parse_adrlist($email, 'default.domain.name');
        if (isset($email_arr[0]->host) && $email_arr[0]->host != '.SYNTAX-ERROR.' && $email_arr[0]->host != 'default.domain.name') {
            $result['email'] = $email_arr[0]->mailbox . '@' . $email_arr[0]->host;
        }
    }

    if (\preg_match('/Action: (.+)/i', $dsn_report, $match)) {
        $action = \strtolower(\trim($match[1]));
        $result['action'] = $action;
    }

    if (\preg_match("/Status: ([0-9\.]+)/i", $dsn_report, $match)) {
        $status_code = $match[1];
        $result['status_code'] = $status_code;
    }

    // Could be multi-line , if the new line is beginning with SPACE or HTAB
    if (\preg_match("/Diagnostic-Code:((?:[^\n]|\n[\t ])+)(?:\n[^\t ]|$)/i", $dsn_report, $match)) {
        $diag_code = $match[1];
    }

    // No Diagnostic-Code in email, use dsn message
    if (empty($diag_code)) {
        $diag_code = $dsn_msg;
    }

    $result['diagnostic_code'] = $diag_code;

    // ======= rules ======

    if (empty($result['email'])) {
        /* email address is empty
         * rule: full
         * sample:   DSN Message only
         * User quota exceeded: SMTP <xxxxx@yourdomain.com>
         */
        if (\preg_match("/quota exceed.*<(\S+@\S+\w)>/is", $dsn_msg, $match)) {
            $result['rule_cat'] = 'full';
            $result['rule_no'] = '0161';
            $result['email'] = $match[1];
        }
    } else {
        /* action could be one of them as RFC:1894
         * "failed" / "delayed" / "delivered" / "relayed" / "expanded"
         */
        switch ($action) {
      case 'failed':
        /* rule: full
         * sample:
         *   Diagnostic-Code: X-Postfix; me.domain.com platform: said: 552 5.2.2 Over
         *     quota (in reply to RCPT TO command)
         */
        if (\preg_match('/over.*quota/is', $diag_code)) {
            $result['rule_cat'] = 'full';
            $result['rule_no'] = '0105';
        } /* rule: full
         * sample:
         *   Diagnostic-Code: SMTP; 552 Requested mailbox exceeds quota.
         */
        elseif (\preg_match('/exceed.*quota/is', $diag_code)) {
            $result['rule_cat'] = 'full';
            $result['rule_no'] = '0129';
        } /* rule: full
         * sample 1:
         *   Diagnostic-Code: smtp;552 5.2.2 This message is larger than the current system limit or the recipient's mailbox is full. Create a shorter message body or remove attachments and try sending it again.
         * sample 2:
         *   Diagnostic-Code: X-Postfix; host mta5.us4.domain.com.int[111.111.111.111] said:
         *     552 recipient storage full, try again later (in reply to RCPT TO command)
         * sample 3:
         *   Diagnostic-Code: X-HERMES; host 127.0.0.1[127.0.0.1] said: 551 bounce as<the
         *     destination mailbox <xxxxx@yourdomain.com> is full> queue as
         *     100.1.ZmxEL.720k.1140313037.xxxxx@yourdomain.com (in reply to end of
         *     DATA command)
         */
        elseif (\preg_match('/(?:alias|account|recipient|address|email|mailbox|user).*full/is', $diag_code)) {
            $result['rule_cat'] = 'full';
            $result['rule_no'] = '0145';
        } /* rule: full
         * sample:
         *   Diagnostic-Code: SMTP; 452 Insufficient system storage
         */
        elseif (\preg_match('/Insufficient system storage/i', $diag_code)) {
            $result['rule_cat'] = 'full';
            $result['rule_no'] = '0134';
        } /* rule: full
         * sample:
         *   Diagnostic-Code: SMTP; 422 Benutzer hat zuviele Mails auf dem Server
         */
        elseif (\preg_match('/Benutzer hat zuviele Mails auf dem Server/i', $diag_code)) {
            $result['rule_cat'] = 'full';
            $result['rule_no'] = '0998';
        } /* rule: full
         * sample:
         *   Diagnostic-Code: SMTP; 422 exceeded storage allocation
         */
        elseif (\preg_match('/exceeded storage allocation/i', $diag_code)) {
            $result['rule_cat'] = 'full';
            $result['rule_no'] = '0997';
        } /* rule: full
         * sample:
         *   Diagnostic-Code: SMTP; 422 Mailbox quota usage exceeded
         */
        elseif (\preg_match('/Mailbox quota usage exceeded/i', $diag_code)) {
            $result['rule_cat'] = 'full';
            $result['rule_no'] = '0996';
        } /* rule: full
         * sample:
         *   Diagnostic-Code: SMTP; 422 User has exhausted allowed storage space
         */
        elseif (\preg_match('/User has exhausted allowed storage space/i', $diag_code)) {
            $result['rule_cat'] = 'full';
            $result['rule_no'] = '0995';
        } /* rule: full
         * sample:
         *   Diagnostic-Code: SMTP; 422 User mailbox exceeds allowed size
         */
        elseif (\preg_match('/User mailbox exceeds allowed size/i', $diag_code)) {
            $result['rule_cat'] = 'full';
            $result['rule_no'] = '0994';
        } /* rule: full
         * sample:
         *   Diagnostic-Code: smpt; 552 Account(s) <a@b.c> does not have enough space
         */
        elseif (\preg_match("/not.*enough\s+space/i", $diag_code)) {
            $result['rule_cat'] = 'full';
            $result['rule_no'] = '0246';
        } /* rule: full
         * sample 1:
         *   Diagnostic-Code: X-Postfix; cannot append message to destination file
         *     /var/mail/dale.me89g: error writing message: File too large
         * sample 2:
         *   Diagnostic-Code: X-Postfix; cannot access mailbox /var/spool/mail/b8843022 for
         *     user xxxxx. error writing message: File too large
         */
        elseif (\preg_match('/File too large/i', $diag_code)) {
            $result['rule_cat'] = 'full';
            $result['rule_no'] = '0192';
        } /* rule: oversize
         * sample:
         *   Diagnostic-Code: smtp;552 5.2.2 This message is larger than the current system limit or the recipient's mailbox is full. Create a shorter message body or remove attachments and try sending it again.
         */
        elseif (\preg_match('/larger than.*limit/is', $diag_code)) {
            $result['rule_cat'] = 'oversize';
            $result['rule_no'] = '0146';
        } /* rule: unknown
         * sample:
         *   Diagnostic-Code: X-Notes; User xxxxx (xxxxx@yourdomain.com) not listed in public Name & Address Book
         */
        elseif (\preg_match('/(?:alias|account|recipient|address|email|mailbox|user)(.*)not(.*)list/is', $diag_code)) {
            $result['rule_cat'] = 'unknown';
            $result['rule_no'] = '0103';
        } /* rule: unknown
         * sample:
         *   Diagnostic-Code: smtp; 450 user path no exist
         */
        elseif (\preg_match('/user path no exist/i', $diag_code)) {
            $result['rule_cat'] = 'unknown';
            $result['rule_no'] = '0106';
        } /* rule: unknown
         * sample 1:
         *   Diagnostic-Code: SMTP; 550 Relaying denied.
         * sample 2:
         *   Diagnostic-Code: SMTP; 554 <xxxxx@yourdomain.com>: Relay access denied
         * sample 3:
         *   Diagnostic-Code: SMTP; 550 relaying to <xxxxx@yourdomain.com> prohibited by administrator
         */
        elseif (\preg_match('/Relay.*(?:denied|prohibited)/is', $diag_code)) {
            $result['rule_cat'] = 'unknown';
            $result['rule_no'] = '0108';
        } /* rule: unknown
         * sample:
         *   Diagnostic-Code: SMTP; 554 qq Sorry, no valid recipients (#5.1.3)
         */
        elseif (\preg_match('/no.*valid.*(?:alias|account|recipient|address|email|mailbox|user)/is', $diag_code)) {
            $result['rule_cat'] = 'unknown';
            $result['rule_no'] = '0185';
        } /* rule: unknown
         * sample 1:
         *   Diagnostic-Code: SMTP; 550 «Dªk¦a§} - invalid address (#5.5.0)
         * sample 2:
         *   Diagnostic-Code: SMTP; 550 Invalid recipient: <xxxxx@yourdomain.com>
         * sample 3:
         *   Diagnostic-Code: SMTP; 550 <xxxxx@yourdomain.com>: Invalid User
         */
        elseif (\preg_match('/Invalid.*(?:alias|account|recipient|address|email|mailbox|user)/is', $diag_code)) {
            $result['rule_cat'] = 'unknown';
            $result['rule_no'] = '0111';
        } /* rule: unknown
         * sample:
         *   Diagnostic-Code: SMTP; 554 delivery error: dd Sorry your message to xxxxx@yourdomain.com cannot be delivered. This account has been disabled or discontinued [#102]. - mta173.mail.tpe.domain.com
         */
        elseif (\preg_match('/(?:alias|account|recipient|address|email|mailbox|user).*(?:disabled|discontinued)/is', $diag_code)) {
            $result['rule_cat'] = 'unknown';
            $result['rule_no'] = '0114';
        } /* rule: unknown
         * sample:
         *   Diagnostic-Code: SMTP; 554 delivery error: dd This user doesn't have a domain.com account (www.xxxxx@yourdomain.com) [0] - mta134.mail.tpe.domain.com
         */
        elseif (\preg_match("/user doesn't have.*account/is", $diag_code)) {
            $result['rule_cat'] = 'unknown';
            $result['rule_no'] = '0127';
        } /* rule: unknown
         * sample:
         *   Diagnostic-Code: SMTP; 550 5.1.1 unknown or illegal alias: xxxxx@yourdomain.com
         */
        elseif (\preg_match('/(?:unknown|illegal).*(?:alias|account|recipient|address|email|mailbox|user)/is', $diag_code)) {
            $result['rule_cat'] = 'unknown';
            $result['rule_no'] = '0128';
        } /* rule: unknown
         * sample 1:
         *   Diagnostic-Code: SMTP; 450 mailbox unavailable.
         * sample 2:
         *   Diagnostic-Code: SMTP; 550 5.7.1 Requested action not taken: mailbox not available
         */
        elseif (\preg_match("/(?:alias|account|recipient|address|email|mailbox|user).*(?:un|not\s+)available/is", $diag_code)) {
            $result['rule_cat'] = 'unknown';
            $result['rule_no'] = '0122';
        } /* rule: unknown
         * sample:
         *   Diagnostic-Code: SMTP; 553 sorry, no mailbox here by that name (#5.7.1)
         */
        elseif (\preg_match('/no (?:alias|account|recipient|address|email|mailbox|user)/i', $diag_code)) {
            $result['rule_cat'] = 'unknown';
            $result['rule_no'] = '0123';
        } /* rule: unknown
         * sample 1:
         *   Diagnostic-Code: SMTP; 550 User (xxxxx@yourdomain.com) unknown.
         * sample 2:
         *   Diagnostic-Code: SMTP; 553 5.3.0 <xxxxx@yourdomain.com>... Addressee unknown, relay=[111.111.111.000]
         */
        elseif (\preg_match('/(?:alias|account|recipient|address|email|mailbox|user).*unknown/is', $diag_code)) {
            $result['rule_cat'] = 'unknown';
            $result['rule_no'] = '0125';
        } /* rule: unknown
         * sample 1:
         *   Diagnostic-Code: SMTP; 550 user disabled
         * sample 2:
         *   Diagnostic-Code: SMTP; 452 4.2.1 mailbox temporarily disabled: xxxxx@yourdomain.com
         */
        elseif (\preg_match('/(?:alias|account|recipient|address|email|mailbox|user).*disabled/is', $diag_code)) {
            $result['rule_cat'] = 'unknown';
            $result['rule_no'] = '0133';
        } /* rule: unknown
         * sample:
         *   Diagnostic-Code: SMTP; 550 <xxxxx@yourdomain.com>: Recipient address rejected: No such user (xxxxx@yourdomain.com)
         */
        elseif (\preg_match('/No such (?:alias|account|recipient|address|email|mailbox|user)/i', $diag_code)) {
            $result['rule_cat'] = 'unknown';
            $result['rule_no'] = '0143';
        } /* rule: unknown
         * sample 1:
         *   Diagnostic-Code: SMTP; 550 MAILBOX NOT FOUND
         * sample 2:
         *   Diagnostic-Code: SMTP; 550 Mailbox ( xxxxx@yourdomain.com ) not found or inactivated
         */
        elseif (\preg_match('/(?:alias|account|recipient|address|email|mailbox|user).*NOT FOUND/is', $diag_code)) {
            $result['rule_cat'] = 'unknown';
            $result['rule_no'] = '0136';
        } /* rule: unknown
         * sample:
         *    Diagnostic-Code: X-Postfix; host m2w-in1.domain.com[111.111.111.000] said: 551
         *    <xxxxx@yourdomain.com> is a deactivated mailbox (in reply to RCPT TO
         *    command)
         */
        elseif (\preg_match('/deactivated (?:alias|account|recipient|address|email|mailbox|user)/i', $diag_code)) {
            $result['rule_cat'] = 'unknown';
            $result['rule_no'] = '0138';
        } /* rule: unknown
         * sample:
         *   Diagnostic-Code: SMTP; 550 <xxxxx@yourdomain.com> recipient rejected
         *   ...
         *   <<< 550 <xxxxx@yourdomain.com> recipient rejected
         *   550 5.1.1 xxxxx@yourdomain.com... User unknown
         */
        elseif (\preg_match('/(?:alias|account|recipient|address|email|mailbox|user).*reject/is', $diag_code)) {
            $result['rule_cat'] = 'unknown';
            $result['rule_no'] = '0148';
        } /* rule: unknown
         * sample:
         *   Diagnostic-Code: smtp; 5.x.0 - Message bounced by administrator  (delivery attempts: 0)
         */
        elseif (\preg_match('/bounce.*administrator/is', $diag_code)) {
            $result['rule_cat'] = 'unknown';
            $result['rule_no'] = '0151';
        } /* rule: unknown
         * sample:
         *   Diagnostic-Code: SMTP; 550 <maxqin> is now disabled with MTA service.
         */
        elseif (\preg_match('/<.*>.*disabled/is', $diag_code)) {
            $result['rule_cat'] = 'unknown';
            $result['rule_no'] = '0152';
        } /* rule: unknown
         * sample:
         *   Diagnostic-Code: SMTP; 551 not our customer
         */
        elseif (\preg_match('/not our customer/i', $diag_code)) {
            $result['rule_cat'] = 'unknown';
            $result['rule_no'] = '0154';
        } /* rule: unknown
         * sample:
         *   Diagnostic-Code: smtp; 5.1.0 - Unknown address error 540-'Error: Wrong recipients' (delivery attempts: 0)
         */
        elseif (\preg_match('/Wrong (?:alias|account|recipient|address|email|mailbox|user)/i', $diag_code)) {
            $result['rule_cat'] = 'unknown';
            $result['rule_no'] = '0159';
        } /* rule: unknown
         * sample:
         *   Diagnostic-Code: smtp; 5.1.0 - Unknown address error 540-'Error: Wrong recipients' (delivery attempts: 0)
         * sample 2:
         *   Diagnostic-Code: SMTP; 501 #5.1.1 bad address xxxxx@yourdomain.com
         */
        elseif (\preg_match('/(?:unknown|bad).*(?:alias|account|recipient|address|email|mailbox|user)/is', $diag_code)) {
            $result['rule_cat'] = 'unknown';
            $result['rule_no'] = '0160';
        } /* rule: unknown
         * sample:
         *   Status: 5.1.1 (bad destination mailbox address)
         */
        elseif (\preg_match('/(?:unknown|bad).*(?:alias|account|recipient|address|email|mailbox|user)/is', $status_code)) {
            $result['rule_cat'] = 'unknown';
            $result['rule_no'] = '01601';
        } /* rule: unknown
         * sample:
         *   Diagnostic-Code: SMTP; 550 Command RCPT User <xxxxx@yourdomain.com> not OK
         */
        elseif (\preg_match('/(?:alias|account|recipient|address|email|mailbox|user).*not OK/is', $diag_code)) {
            $result['rule_cat'] = 'unknown';
            $result['rule_no'] = '0186';
        } /* rule: unknown
         * sample:
         *   Diagnostic-Code: SMTP; 550 5.7.1 Access-Denied-XM.SSR-001
         */
        elseif (\preg_match('/Access.*Denied/is', $diag_code)) {
            $result['rule_cat'] = 'unknown';
            $result['rule_no'] = '0189';
        } /* rule: unknown
         * sample:
         *   Diagnostic-Code: SMTP; 550 5.1.1 <xxxxx@yourdomain.com>... email address lookup in domain map failed
         */
        elseif (\preg_match('/(?:alias|account|recipient|address|email|mailbox|user).*lookup.*fail/is', $diag_code)) {
            $result['rule_cat'] = 'unknown';
            $result['rule_no'] = '0195';
        } /* rule: unknown
         * sample:
         *   Diagnostic-Code: SMTP; 550 User not a member of domain: <xxxxx@yourdomain.com>
         */
        elseif (\preg_match('/(?:recipient|address|email|mailbox|user).*not.*member of domain/is', $diag_code)) {
            $result['rule_cat'] = 'unknown';
            $result['rule_no'] = '0198';
        } /* rule: unknown
         * sample:
         *   Diagnostic-Code: SMTP; 550-"The recipient cannot be verified.  Please check all recipients of this
         */
        elseif (\preg_match('/(?:alias|account|recipient|address|email|mailbox|user).*cannot be verified/is', $diag_code)) {
            $result['rule_cat'] = 'unknown';
            $result['rule_no'] = '0202';
        } /* rule: unknown
         * sample:
         *   Diagnostic-Code: SMTP; 550 Unable to relay for xxxxx@yourdomain.com
         */
        elseif (\preg_match('/Unable to relay/i', $diag_code)) {
            $result['rule_cat'] = 'unknown';
            $result['rule_no'] = '0203';
        } /* rule: unknown
         * sample 1:
         *   Diagnostic-Code: SMTP; 550 xxxxx@yourdomain.com:user not exist
         * sample 2:
         *   Diagnostic-Code: SMTP; 550 sorry, that recipient doesn't exist (#5.7.1)
         */
        elseif (\preg_match("/(?:alias|account|recipient|address|email|mailbox|user).*(?:n't|not) exist/is", $diag_code)) {
            $result['rule_cat'] = 'unknown';
            $result['rule_no'] = '0205';
        } /* rule: unknown
         * sample:
         *   Diagnostic-Code: SMTP; 550-I'm sorry but xxxxx@yourdomain.com does not have an account here. I will not
         */
        elseif (\preg_match('/not have an account/i', $diag_code)) {
            $result['rule_cat'] = 'unknown';
            $result['rule_no'] = '0207';
        } /* rule: unknown
         * sample:
         *   Diagnostic-Code: SMTP; 550 This account is not allowed...xxxxx@yourdomain.com
         */
        elseif (\preg_match('/(?:alias|account|recipient|address|email|mailbox|user).*is not allowed/is', $diag_code)) {
            $result['rule_cat'] = 'unknown';
            $result['rule_no'] = '0220';
        } /* rule: unknown
         * sample:
         *   Diagnostic-Code: X-Notes; Recipient user name info (a@b.c) not unique.  Several matches found in Domino Directory.
         */
        elseif (\preg_match('/not unique.\s+Several matches found/i', $diag_code)) {
            $result['rule_cat'] = 'unknown';
            $result['rule_no'] = '0255';
        } /* rule: inactive
         * sample:
         *   Diagnostic-Code: SMTP; 550 <xxxxx@yourdomain.com>: inactive user
         */
        elseif (\preg_match('/inactive.*(?:alias|account|recipient|address|email|mailbox|user)/is', $diag_code)) {
            $result['rule_cat'] = 'inactive';
            $result['rule_no'] = '0135';
        } /* rule: inactive
         * sample:
         *   Diagnostic-Code: SMTP; 550 xxxxx@yourdomain.com Account Inactive
         */
        elseif (\preg_match('/(?:alias|account|recipient|address|email|mailbox|user).*Inactive/is', $diag_code)) {
            $result['rule_cat'] = 'inactive';
            $result['rule_no'] = '0155';
        } /* rule: inactive
         * sample:
         *   Diagnostic-Code: SMTP; 550 <xxxxx@yourdomain.com>: Recipient address rejected: Account closed due to inactivity. No forwarding information is available.
         */
        elseif (\preg_match('/(?:alias|account|recipient|address|email|mailbox|user) closed due to inactivity/i', $diag_code)) {
            $result['rule_cat'] = 'inactive';
            $result['rule_no'] = '0170';
        } /* rule: inactive
         * sample:
         *   Diagnostic-Code: SMTP; 550 <xxxxx@yourdomain.com>... User account not activated
         */
        elseif (\preg_match('/(?:alias|account|recipient|address|email|mailbox|user) not activated/i', $diag_code)) {
            $result['rule_cat'] = 'inactive';
            $result['rule_no'] = '0177';
        } /* rule: inactive
         * sample 1:
         *   Diagnostic-Code: SMTP; 550 User suspended
         * sample 2:
         *   Diagnostic-Code: SMTP; 550 account expired
         */
        elseif (\preg_match('/(?:alias|account|recipient|address|email|mailbox|user).*(?:suspend|expire)/is', $diag_code)) {
            $result['rule_cat'] = 'inactive';
            $result['rule_no'] = '0183';
        } /* rule: inactive
         * sample:
         *   Diagnostic-Code: SMTP; 553 5.3.0 <xxxxx@yourdomain.com>... Recipient address no longer exists
         */
        elseif (\preg_match('/(?:alias|account|recipient|address|email|mailbox|user).*no longer exist/is', $diag_code)) {
            $result['rule_cat'] = 'inactive';
            $result['rule_no'] = '0184';
        } /* rule: inactive
         * sample:
         *   Diagnostic-Code: SMTP; 553 VS10-RT Possible forgery or deactivated due to abuse (#5.1.1) 111.111.111.211
         */
        elseif (\preg_match('/(?:forgery|abuse)/i', $diag_code)) {
            $result['rule_cat'] = 'inactive';
            $result['rule_no'] = '0196';
        } /* rule: inactive
         * sample:
         *   Diagnostic-Code: SMTP; 553 mailbox xxxxx@yourdomain.com is restricted
         */
        elseif (\preg_match('/(?:alias|account|recipient|address|email|mailbox|user).*restrict/is', $diag_code)) {
            $result['rule_cat'] = 'inactive';
            $result['rule_no'] = '0209';
        } /* rule: inactive
         * sample:
         *   Diagnostic-Code: SMTP; 550 <xxxxx@yourdomain.com>: User status is locked.
         */
        elseif (\preg_match('/(?:alias|account|recipient|address|email|mailbox|user).*locked/is', $diag_code)) {
            $result['rule_cat'] = 'inactive';
            $result['rule_no'] = '0228';
        } /* rule: user_reject
         * sample:
         *   Diagnostic-Code: SMTP; 553 User refused to receive this mail.
         */
        elseif (\preg_match('/(?:alias|account|recipient|address|email|mailbox|user) refused/i', $diag_code)) {
            $result['rule_cat'] = 'user_reject';
            $result['rule_no'] = '0156';
        } /* rule: user_reject
         * sample:
         *   Diagnostic-Code: SMTP; 501 xxxxx@yourdomain.com Sender email is not in my domain
         */
        elseif (\preg_match('/sender.*not/is', $diag_code)) {
            $result['rule_cat'] = 'user_reject';
            $result['rule_no'] = '0206';
        } /* rule: command_reject
         * sample:
         *   Diagnostic-Code: SMTP; 554 Message refused
         */
        elseif (\preg_match('/Message refused/i', $diag_code)) {
            $result['rule_cat'] = 'command_reject';
            $result['rule_no'] = '0175';
        } /* rule: command_reject
         * sample:
         *   Diagnostic-Code: SMTP; 550 5.0.0 <xxxxx@yourdomain.com>... No permit
         */
        elseif (\preg_match('/No permit/i', $diag_code)) {
            $result['rule_cat'] = 'command_reject';
            $result['rule_no'] = '0190';
        } /* rule: command_reject
         * sample:
         *   Diagnostic-Code: SMTP; 553 sorry, that domain isn't in my list of allowed rcpthosts (#5.5.3 - chkuser)
         */
        elseif (\preg_match("/domain isn't in.*allowed rcpthost/is", $diag_code)) {
            $result['rule_cat'] = 'command_reject';
            $result['rule_no'] = '0191';
        } /* rule: command_reject
         * sample:
         *   Diagnostic-Code: SMTP; 553 AUTH FAILED - xxxxx@yourdomain.com
         */
        elseif (\preg_match('/AUTH FAILED/i', $diag_code)) {
            $result['rule_cat'] = 'command_reject';
            $result['rule_no'] = '0197';
        } /* rule: command_reject
         * sample 1:
         *   Diagnostic-Code: SMTP; 550 relay not permitted
         * sample 2:
         *   Diagnostic-Code: SMTP; 530 5.7.1 Relaying not allowed: xxxxx@yourdomain.com
         */
        elseif (\preg_match('/relay.*not.*(?:permit|allow)/is', $diag_code)) {
            $result['rule_cat'] = 'command_reject';
            $result['rule_no'] = '0241';
        } /* rule: command_reject
         * sample:
         *   Diagnostic-Code: SMTP; 550 not local host domain.com, not a gateway
         */
        elseif (\preg_match('/not local host/i', $diag_code)) {
            $result['rule_cat'] = 'command_reject';
            $result['rule_no'] = '0204';
        } /* rule: command_reject
         * sample:
         *   Diagnostic-Code: SMTP; 500 Unauthorized relay msg rejected
         */
        elseif (\preg_match('/Unauthorized relay/i', $diag_code)) {
            $result['rule_cat'] = 'command_reject';
            $result['rule_no'] = '0215';
        } /* rule: command_reject
         * sample:
         *   Diagnostic-Code: SMTP; 554 Transaction failed
         */
        elseif (\preg_match('/Transaction.*fail/is', $diag_code)) {
            $result['rule_cat'] = 'command_reject';
            $result['rule_no'] = '0221';
        } /* rule: command_reject
         * sample:
         *   Diagnostic-Code: smtp;554 5.5.2 Invalid data in message
         */
        elseif (\preg_match('/Invalid data/i', $diag_code)) {
            $result['rule_cat'] = 'command_reject';
            $result['rule_no'] = '0223';
        } /* rule: command_reject
         * sample:
         *   Diagnostic-Code: SMTP; 550 Local user only or Authentication mechanism
         */
        elseif (\preg_match('/Local user only/i', $diag_code)) {
            $result['rule_cat'] = 'command_reject';
            $result['rule_no'] = '0224';
        } /* rule: command_reject
         * sample:
         *   Diagnostic-Code: SMTP; 550-ds176.domain.com [111.111.111.211] is currently not permitted to
         *   relay through this server. Perhaps you have not logged into the pop/imap
         *   server in the last 30 minutes or do not have SMTP Authentication turned on
         *   in your email client.
         */
        elseif (\preg_match('/not.*permit.*to/is', $diag_code)) {
            $result['rule_cat'] = 'command_reject';
            $result['rule_no'] = '0225';
        } /* rule: content_reject
         * sample:
         *   Diagnostic-Code: SMTP; 550 Content reject. FAAAANsG60M9BmDT.1
         */
        elseif (\preg_match('/Content reject/i', $diag_code)) {
            $result['rule_cat'] = 'content_reject';
            $result['rule_no'] = '0165';
        } /* rule: content_reject
         * sample:
         *   Diagnostic-Code: SMTP; 552 MessageWall: MIME/REJECT: Invalid structure
         */
        elseif (\preg_match("/MIME\/REJECT/i", $diag_code)) {
            $result['rule_cat'] = 'content_reject';
            $result['rule_no'] = '0212';
        } /* rule: content_reject
         * sample:
         *   Diagnostic-Code: smtp; 554 5.6.0 Message with invalid header rejected, id=13462-01 - MIME error: error: UnexpectedBound: part didn't end with expected boundary [in multipart message]; EOSToken: EOF; EOSType: EOF
         */
        elseif (\preg_match('/MIME error/i', $diag_code)) {
            $result['rule_cat'] = 'content_reject';
            $result['rule_no'] = '0217';
        } /* rule: content_reject
         * sample:
         *   Diagnostic-Code: SMTP; 553 Mail data refused by AISP, rule [169648].
         */
        elseif (\preg_match('/Mail data refused.*AISP/is', $diag_code)) {
            $result['rule_cat'] = 'content_reject';
            $result['rule_no'] = '0218';
        } /* rule: dns_unknown
         * sample:
         *   Diagnostic-Code: SMTP; 550 Host unknown
         */
        elseif (\preg_match('/Host unknown/i', $diag_code)) {
            $result['rule_cat'] = 'dns_unknown';
            $result['rule_no'] = '0130';
        } /* rule: dns_unknown
         * sample:
         *   Diagnostic-Code: SMTP; 553 Specified domain is not allowed.
         */
        elseif (\preg_match('/Specified domain.*not.*allow/is', $diag_code)) {
            $result['rule_cat'] = 'dns_unknown';
            $result['rule_no'] = '0180';
        } /* rule: dns_unknown
         * sample:
         *   Diagnostic-Code: X-Postfix; delivery temporarily suspended: connect to
         *   111.111.11.112[111.111.11.112]: No route to host
         */
        elseif (\preg_match('/No route to host/i', $diag_code)) {
            $result['rule_cat'] = 'dns_unknown';
            $result['rule_no'] = '0188';
        } /* rule: dns_unknown
         * sample:
         *   Diagnostic-Code: SMTP; 550 unrouteable address
         */
        elseif (\preg_match('/unrouteable address/i', $diag_code)) {
            $result['rule_cat'] = 'dns_unknown';
            $result['rule_no'] = '0208';
        } /* rule: dns_unknown
         * sample:
         *   Diagnostic-Code: X-Postfix; Host or domain name not found. Name service error
         *     for name=aaaaaaaaaaa type=A: Host not found
         */
        elseif (\preg_match('/Host or domain name not found/i', $diag_code)) {
            $result['rule_cat'] = 'dns_unknown';
            $result['rule_no'] = '0238';
        } /* rule: dns_loop
         * sample:
         *   Diagnostic-Code: X-Postfix; mail for mta.example.com loops back to myself
         */
        elseif (\preg_match('/loops back to myself/i', $diag_code)) {
            $result['rule_cat'] = 'dns_loop';
            $result['rule_no'] = '0245';
        } /* rule: defer
         * sample:
         *   Diagnostic-Code: SMTP; 451 System(u) busy, try again later.
         */
        elseif (\preg_match('/System.*busy/is', $diag_code)) {
            $result['rule_cat'] = 'defer';
            $result['rule_no'] = '0112';
        } /* rule: defer
         * sample:
         *   Diagnostic-Code: SMTP; 451 mta172.mail.tpe.domain.com Resources temporarily unavailable. Please try again later.  [#4.16.4:70].
         */
        elseif (\preg_match('/Resources temporarily unavailable/i', $diag_code)) {
            $result['rule_cat'] = 'defer';
            $result['rule_no'] = '0116';
        } /* rule: antispam, deny ip
         * sample:
         *   Diagnostic-Code: SMTP; 554 sender is rejected: 0,mx20,wKjR5bDrnoM2yNtEZVAkBg==.32467S2
         */
        elseif (\preg_match('/sender is rejected/i', $diag_code)) {
            $result['rule_cat'] = 'antispam';
            $result['rule_no'] = '0101';
        } /* rule: antispam, deny ip
         * sample:
         *   Diagnostic-Code: SMTP; 554 <unknown[111.111.111.000]>: Client host rejected: Access denied
         */
        elseif (\preg_match('/Client host rejected/i', $diag_code)) {
            $result['rule_cat'] = 'antispam';
            $result['rule_no'] = '0102';
        } /* rule: antispam, mismatch ip
         * sample:
         *   Diagnostic-Code: SMTP; 554 Connection refused(mx). MAIL FROM [xxxxx@yourdomain.com] mismatches client IP [111.111.111.000].
         */
        elseif (\preg_match('/MAIL FROM(.*)mismatches client IP/is', $diag_code)) {
            $result['rule_cat'] = 'antispam';
            $result['rule_no'] = '0104';
        } /* rule: antispam, deny ip
         * sample:
         *   Diagnostic-Code: SMTP; 554 Please visit http:// antispam.domain.com/denyip.php?IP=111.111.111.000 (#5.7.1)
         */
        elseif (\stripos($diag_code, 'denyip') !== false) {
            $result['rule_cat'] = 'antispam';
            $result['rule_no'] = '0144';
        } /* rule: antispam, deny ip
         * sample:
         *   Diagnostic-Code: SMTP; 554 Service unavailable; Client host [111.111.111.211] blocked using dynablock.domain.com; Your message could not be delivered due to complaints we received regarding the IP address you're using or your ISP. See http:// blackholes.domain.com/ Error: WS-02
         */
        elseif (\preg_match('/client host.*blocked/is', $diag_code)) {
            $result['rule_cat'] = 'antispam';
            $result['rule_no'] = '0242';
        } /* rule: antispam, reject
         * sample:
         *   Diagnostic-Code: SMTP; 550 Requested action not taken: mail IsCNAPF76kMDARUY.56621S2 is rejected,mx3,BM
         */
        elseif (\preg_match('/mail.*reject/is', $diag_code)) {
            $result['rule_cat'] = 'antispam';
            $result['rule_no'] = '0147';
        } /* rule: antispam
         * sample:
         *   Diagnostic-Code: SMTP; 552 sorry, the spam message is detected (#5.6.0)
         */
        elseif (\preg_match('/spam.*detect/is', $diag_code)) {
            $result['rule_cat'] = 'antispam';
            $result['rule_no'] = '0162';
        } /* rule: antispam
         * sample:
         *   Diagnostic-Code: SMTP; 554 5.7.1 Rejected as Spam see: http:// rejected.domain.com/help/spam/rejected.html
         */
        elseif (\preg_match('/reject.*spam/is', $diag_code)) {
            $result['rule_cat'] = 'antispam';
            $result['rule_no'] = '0216';
        } /* rule: antispam
         * sample:
         *   Diagnostic-Code: SMTP; 553 5.7.1 <xxxxx@yourdomain.com>... SpamTrap=reject mode, dsn=5.7.1, Message blocked by BOX Solutions (www.domain.com) SpamTrap Technology, please contact the domain.com site manager for help: (ctlusr8012).
         */
        elseif (\stripos($diag_code, 'SpamTrap') !== false) {
            $result['rule_cat'] = 'antispam';
            $result['rule_no'] = '0200';
        } /* rule: antispam, mailfrom mismatch
         * sample:
         *   Diagnostic-Code: SMTP; 550 Verify mailfrom failed,blocked
         */
        elseif (\preg_match('/Verify mailfrom failed/i', $diag_code)) {
            $result['rule_cat'] = 'antispam';
            $result['rule_no'] = '0210';
        } /* rule: antispam, mailfrom mismatch
         * sample:
         *   Diagnostic-Code: SMTP; 550 Error: MAIL FROM is mismatched with message header from address!
         */
        elseif (\preg_match('/MAIL.*FROM.*mismatch/is', $diag_code)) {
            $result['rule_cat'] = 'antispam';
            $result['rule_no'] = '0226';
        } /* rule: antispam
         * sample:
         *   Diagnostic-Code: SMTP; 554 5.7.1 Message scored too high on spam scale.  For help, please quote incident ID 22492290.
         */
        elseif (\preg_match('/spam scale/i', $diag_code)) {
            $result['rule_cat'] = 'antispam';
            $result['rule_no'] = '0211';
        } /* rule: antispam
         * sample:
         *   Diagnostic-Code: SMTP; 554 5.7.1 reject: Client host bypassing service provider's mail relay: ds176.domain.com
         */
        elseif (\preg_match('/Client host bypass/i', $diag_code)) {
            $result['rule_cat'] = 'antispam';
            $result['rule_no'] = '0229';
        } /* rule: antispam
         * sample:
         *   Diagnostic-Code: SMTP; 550 sorry, it seems as a junk mail
         */
        elseif (\preg_match('/junk mail/i', $diag_code)) {
            $result['rule_cat'] = 'antispam';
            $result['rule_no'] = '0230';
        } /* rule: antispam
         * sample:
         *   Diagnostic-Code: SMTP; 553-Message filtered. Please see the FAQs section on spam
         */
        elseif (\preg_match('/message filtered/i', $diag_code)) {
            $result['rule_cat'] = 'antispam';
            $result['rule_no'] = '0243';
        } /* rule: antispam, subject filter
         * sample:
         *   Diagnostic-Code: SMTP; 554 5.7.1 The message from (<xxxxx@yourdomain.com>) with the subject of ( *(ca2639) 7|-{%2E* : {2"(%EJ;y} (SBI$#$@<K*:7s1!=l~) matches a profile the Internet community may consider spam. Please revise your message before resending.
         */
        elseif (\preg_match('/subject.*consider.*spam/is', $diag_code)) {
            $result['rule_cat'] = 'antispam';
            $result['rule_no'] = '0222';
        } /* rule: internal_error
         * sample:
         *   Diagnostic-Code: SMTP; 451 Temporary local problem - please try later
         */
        elseif (\preg_match('/Temporary local problem/i', $diag_code)) {
            $result['rule_cat'] = 'internal_error';
            $result['rule_no'] = '0142';
        } /* rule: internal_error
         * sample:
         *   Diagnostic-Code: SMTP; 553 5.3.5 system config error
         */
        elseif (\preg_match('/system config error/i', $diag_code)) {
            $result['rule_cat'] = 'internal_error';
            $result['rule_no'] = '0153';
        } /* rule: delayed
         * sample:
         *   Diagnostic-Code: X-Postfix; delivery temporarily suspended: conversation with
         *   111.111.111.11[111.111.111.11] timed out while sending end of data -- message may be
         *   sent more than once
         */
        elseif (\preg_match('/delivery.*suspend/is', $diag_code)) {
            $result['rule_cat'] = 'delayed';
            $result['rule_no'] = '0213';
        }

        // =========== rules based on the dsn_msg ===============

        /* rule: unknown
         * sample:
         *   ----- The following addresses had permanent fatal errors -----
         *   <xxxxx@yourdomain.com>
         *   ----- Transcript of session follows -----
         *   ... while talking to mta1.domain.com.:
         *   >>> DATA
         *   <<< 503 All recipients are invalid
         *   554 5.0.0 Service unavailable
         */
        elseif (\preg_match('/(?:alias|account|recipient|address|email|mailbox|user)(?:.*)invalid/i', $dsn_msg)) {
            $result['rule_cat'] = 'unknown';
            $result['rule_no'] = '0107';
        } /* rule: unknown
         * sample:
         *   ----- Transcript of session follows -----
         *   xxxxx@yourdomain.com... Deferred: No such file or directory
         */
        elseif (\preg_match('/Deferred.*No such.*(?:file|directory)/i', $dsn_msg)) {
            $result['rule_cat'] = 'unknown';
            $result['rule_no'] = '0141';
        } /* rule: unknown
         * sample:
         *   Failed to deliver to '<xxxxx@yourdomain.com>'
         *   LOCAL module(account xxxx) reports:
         *   mail receiving disabled
         */
        elseif (\preg_match('/mail receiving disabled/i', $dsn_msg)) {
            $result['rule_cat'] = 'unknown';
            $result['rule_no'] = '0194';
        } /* rule: unknown
         * sample:
         *   - These recipients of your message have been processed by the mail server:
         *   xxxxx@yourdomain.com; Failed; 5.1.1 (bad destination mailbox address)
         */
        elseif (\preg_match('/bad.*(?:alias|account|recipient|address|email|mailbox|user)/i', $status_code)) {
            $result['rule_cat'] = 'unknown';
            $result['rule_no'] = '02441';
        } /* rule: unknown
         * sample:
         *   - These recipients of your message have been processed by the mail server:
         *   xxxxx@yourdomain.com; Failed; 5.1.1 (bad destination mailbox address)
         */
        elseif (\preg_match('/bad.*(?:alias|account|recipient|address|email|mailbox|user)/i', $dsn_msg)) {
            $result['rule_cat'] = 'unknown';
            $result['rule_no'] = '0244';
        } /* rule: full
         * sample 1:
         *   This Message was undeliverable due to the following reason:
         *   The user(s) account is temporarily over quota.
         *   <xxxxx@yourdomain.com>
         * sample 2:
         *   Recipient address: xxxxx@yourdomain.com
         *   Reason: Over quota
         */
        elseif (\preg_match('/over.*quota/i', $dsn_msg)) {
            $result['rule_cat'] = 'full';
            $result['rule_no'] = '0131';
        } /* rule: full
         * sample:
         *   Sorry the recipient quota limit is exceeded.
         *   This message is returned as an error.
         */
        elseif (\preg_match('/quota.*exceeded/i', $dsn_msg)) {
            $result['rule_cat'] = 'full';
            $result['rule_no'] = '0150';
        } /* rule: full
         * sample:
         *   The user to whom this message was addressed has exceeded the allowed mailbox
         *   quota. Please resend the message at a later time.
         */
        elseif (\preg_match("/exceed.*\n?.*quota/i", $dsn_msg)) {
            $result['rule_cat'] = 'full';
            $result['rule_no'] = '0187';
        } /* rule: full
         * sample 1:
         *   Failed to deliver to '<xxxxx@yourdomain.com>'
         *   LOCAL module(account xxxxxx) reports:
         *   account is full (quota exceeded)
         * sample 2:
         *   Error in fabiomod_sql_glob_init: no data source specified - database access disabled
         *   [Fri Feb 17 23:29:38 PST 2006] full error for caltsmy:
         *   that member's mailbox is full
         *   550 5.0.0 <xxxxx@yourdomain.com>... Can't create output
         */
        elseif (\preg_match('/(?:alias|account|recipient|address|email|mailbox|user).*full/i', $dsn_msg)) {
            $result['rule_cat'] = 'full';
            $result['rule_no'] = '0132';
        } /* rule: full
         * sample:
         *   gaosong "(0), ErrMsg=Mailbox space not enough (space limit is 10240KB)
         */
        elseif (\preg_match('/space.*not.*enough/i', $dsn_msg)) {
            $result['rule_cat'] = 'full';
            $result['rule_no'] = '0219';
        } /* rule: defer
         * sample 1:
         *   ----- Transcript of session follows -----
         *   xxxxx@yourdomain.com... Deferred: Connection refused by nomail.tpe.domain.com.
         *   Message could not be delivered for 5 days
         *   Message will be deleted from queue
         * sample 2:
         *   451 4.4.1 reply: read error from www.domain.com.
         *   xxxxx@yourdomain.com... Deferred: Connection reset by www.domain.com.
         */
        elseif (\preg_match('/Deferred.*Connection (?:refused|reset)/i', $dsn_msg)) {
            $result['rule_cat'] = 'defer';
            $result['rule_no'] = '0115';
        } /* rule: dns_unknown
         * sample:
         *   ----- The following addresses had permanent fatal errors -----
         *   Tan XXXX SSSS <xxxxx@yourdomain..com>
         *   ----- Transcript of session follows -----
         *   553 5.1.2 XXXX SSSS <xxxxx@yourdomain..com>... Invalid host name
         */
        elseif (\preg_match('/Invalid host name/i', $dsn_msg)) {
            $result['rule_cat'] = 'dns_unknown';
            $result['rule_no'] = '0239';
        } /* rule: dns_unknown
         * sample:
         *   ----- Transcript of session follows -----
         *   xxxxx@yourdomain.com... Deferred: mail.domain.com.: No route to host
         */
        elseif (\preg_match('/Deferred.*No route to host/i', $dsn_msg)) {
            $result['rule_cat'] = 'dns_unknown';
            $result['rule_no'] = '0240';
        } /* rule: dns_unknown
         * sample:
         *   ----- Transcript of session follows -----
         *   550 5.1.2 xxxxx@yourdomain.com... Host unknown (Name server: .: no data known)
         */
        elseif (\preg_match('/Host unknown/i', $dsn_msg)) {
            $result['rule_cat'] = 'dns_unknown';
            $result['rule_no'] = '0140';
        } /* rule: dns_unknown
         * sample:
         *   ----- Transcript of session follows -----
         *   451 HOTMAIL.com.tw: Name server timeout
         *   Message could not be delivered for 5 days
         *   Message will be deleted from queue
         */
        elseif (\preg_match('/Name server timeout/i', $dsn_msg)) {
            $result['rule_cat'] = 'dns_unknown';
            $result['rule_no'] = '0118';
        } /* rule: dns_unknown
         * sample:
         *   ----- Transcript of session follows -----
         *   xxxxx@yourdomain.com... Deferred: Connection timed out with hkfight.com.
         *   Message could not be delivered for 5 days
         *   Message will be deleted from queue
         */
        elseif (\preg_match('/Deferred.*Connection.*tim(?:e|ed).*out/i', $dsn_msg)) {
            $result['rule_cat'] = 'dns_unknown';
            $result['rule_no'] = '0119';
        } /* rule: dns_unknown
         * sample:
         *   ----- Transcript of session follows -----
         *   xxxxx@yourdomain.com... Deferred: Name server: domain.com.: host name lookup failure
         */
        elseif (\preg_match('/Deferred.*host name lookup failure/i', $dsn_msg)) {
            $result['rule_cat'] = 'dns_unknown';
            $result['rule_no'] = '0121';
        } /* rule: dns_loop
         * sample:
         *   ----- Transcript of session follows -----
         *   554 5.0.0 MX list for znet.ws. points back to mail01.domain.com
         *   554 5.3.5 Local configuration error
         */
        elseif (\preg_match('/MX list.*point.*back/i', $dsn_msg)) {
            $result['rule_cat'] = 'dns_loop';
            $result['rule_no'] = '0199';
        } /* rule: internal_error
         * sample:
         *   ----- Transcript of session follows -----
         *   451 4.0.0 I/O error
         */
        elseif (\preg_match("/I\/O error/i", $dsn_msg)) {
            $result['rule_cat'] = 'internal_error';
            $result['rule_no'] = '0120';
        } /* rule: internal_error
         * sample:
         *   Failed to deliver to 'xxxxx@yourdomain.com'
         *   SMTP module(domain domain.com) reports:
         *   connection with mx1.mail.domain.com is broken
         */
        elseif (\preg_match('/connection.*broken/i', $dsn_msg)) {
            $result['rule_cat'] = 'internal_error';
            $result['rule_no'] = '0231';
        } /* rule: other
         * sample:
         *   Delivery to the following recipients failed.
         *   xxxxx@yourdomain.com
         */
        elseif (\preg_match("/Delivery to the following recipients failed.*\n.*\n.*" . \preg_quote($result['email'], '/') . '/i', $dsn_msg)) {
            $result['rule_cat'] = 'other';
            $result['rule_no'] = '0176';
        }

        // Followings are wind-up rule: must be the last one
        //   many other rules msg end up with "550 5.1.1 ... User unknown"
        //   many other rules msg end up with "554 5.0.0 Service unavailable"

        /* rule: unknown
         * sample 1:
         *   ----- The following addresses had permanent fatal errors -----
         *   <xxxxx@yourdomain.com>
         *   (reason: User unknown)
         * sample 2:
         *   550 5.1.1 xxxxx@yourdomain.com... User unknown
         */
        elseif (\preg_match('/(?:User unknown|Unknown user)/i', $dsn_msg)) {
            $result['rule_cat'] = 'unknown';
            $result['rule_no'] = '0193';
        } /* rule: unknown
         * sample:
         *   554 5.0.0 Service unavailable
         */
        elseif (\preg_match('/Service unavailable/i', $dsn_msg)) {
            $result['rule_cat'] = 'unknown';
            $result['rule_no'] = '0214';
        }

        break;

      case 'delayed':
        $result['rule_cat'] = 'delayed';
        $result['rule_no'] = '0110';

        break;

      case 'delivered':
      case 'relayed':
      case 'expanded': // unhandled cases
        break;

      default:
        break;
    }
    }

    if ($result['rule_no'] == '0000') {
        if ($debug_mode) {
            echo 'email: ' . $result['email'] . $bmh_newline;
            echo 'Action: ' . $action . $bmh_newline;
            echo 'Status: ' . $status_code . $bmh_newline;
            echo 'Diagnostic-Code: ' . $diag_code . $bmh_newline;
            echo "DSN Message:<br />\n" . $dsn_msg . $bmh_newline;
            echo $bmh_newline;
        }
    } else {
        if ($result['bounce_type'] === false) {
            $result['bounce_type'] = $rule_categories[$result['rule_cat']]['bounce_type'];
            $result['remove'] = $rule_categories[$result['rule_cat']]['remove'];
        }
    }

    return $result;
}
