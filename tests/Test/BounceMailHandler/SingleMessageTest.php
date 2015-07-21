<?php

namespace Test\BounceMailHandler;

use BounceMailHandler\BounceMailHandler;

/**
 * @group functional
 */
class SingleMessageTest extends \PHPUnit_Framework_TestCase
{
    protected function getMailboxPath($localMailboxPath)
    {
        if (!file_exists($localMailboxPath)) {
            throw new \Exception('Local mailbox doesn\'t exist: '.$localMailboxPath);
        }

        $localMailboxPath = realpath($localMailboxPath);
        
        $homeDirectory = getenv('HOME');
        if (strncmp($localMailboxPath, $homeDirectory.DIRECTORY_SEPARATOR, strlen($homeDirectory)+1)) {
            throw new \Exception('Mailbox must be under home directory: '.$homeDirectory);
        }

        return substr($localMailboxPath, strlen($homeDirectory)+1);
    }

    public function testProcessMailbox()
    {
        $testData = array(
            // 'filename' => array(
            //     $fetched, $processed, $unprocessed, $deleted, $moved,
            //     $ruleNo, $ruleCat, $bounceType, $email,
            // ),

            'bounce-email/tt_1234175799.txt' => array(
                1, 1, 0, 1, 0,
                '0238', 'dns_unknown', 'hard', 'agris.ameriks@amerimailzzz.lv',
            ),
            'bounce-email/tt_1234177688.txt' => array(
                1, 1, 0, 1, 0,
                '0148', 'unknown', 'hard', 'aaaaagggrrriiiizz@inbox.lv',
            ),
            'bounce-email/tt_1234210655.txt' => array(
                1, 1, 0, 1, 0,
                '0103', 'unknown', 'hard', 'this_doesnotexistinAAc@accenture.com',
            ),
/*
            // @todo outofoffice if body matches /out.*of.*office/ ?
            'bounce-email/tt_1234210666.txt' => array(
                1, 0, 1, 0, 0,
                '0000', 'unrecognized', false, 'agris.ameriks@accenture.com',
            ),
            // @todo any header with Autoreply, e.g., X-AutoReply-From, X-Mail-Autoreply, X-Autoreply
            'bounce-email/tt_1234211024.txt' => array(
                1, 0, 1, 0, 0,
                '0000', 'unrecognized', false, 'agris.ameriks@gmail.com',
            ),
 */
            'bounce-email/tt_1234211357.txt' => array(
                1, 1, 0, 1, 0,
                '0205', 'unknown', 'hard', 'agris.ameriksNEEXISTEE@gmail.com',
            ),
            'bounce-email/tt_1234211929.txt' => array(
                1, 1, 0, 1, 0,
                '0105', 'full', 'soft', 'jekaterina@tv5.lv',
            ),
            'bounce-email/tt_1234211931.txt' => array(
                1, 1, 0, 1, 0,
                '0245', 'dns_loop', 'hard', 'info.rietumuradio.lv@mail.studio7.lv',
            ),
            'bounce-email/tt_1234211932.txt' => array(
                1, 1, 0, 1, 0,
                '0123', 'unknown', 'hard', 'dace.balode@rigasvilni.lv',
            ),
            // this is a non-English auto-reply without an Autoreply header
            'bounce-email/tt_1234241664.txt' => array(
                1, 0, 1, 0, 0,
                '0000', 'unrecognized', false, 'ameriks@amerimail.lv',
            ),
            'bounce-email/tt_1234241665.txt' => array(
                1, 1, 0, 1, 0,
                '0128', 'unknown', 'hard', 'annas@sfl.lv',
            ),
            'bounce-email/tt_1234285532.txt' => array(
                1, 1, 0, 1, 0,
                '0127', 'unknown', 'hard', 'doesntexistthisemaill@yahoo.com',
            ),
            'bounce-email/tt_1234285668.txt' => array(
                1, 1, 0, 1, 0,
                '0246', 'full', 'soft', 'agrisa@one.lv',
            ),
            'bounce-email/tt_bounce_01.txt' => array(
                1, 1, 0, 1, 0,
                '0247', 'dns_unknown', 'hard', 'thornborrow@connect4free.net',
            ),
/*
            // duplicates tt_bounce_01.txt
            'bounce-email/tt_bounce_02.txt' => array(
                1, 1, 0, 1, 0,
                '0247', 'dns_unknown', 'hard', 'thornborrow@connect4free.net',
            ),
*/
            'bounce-email/tt_bounce_03.txt' => array(
                1, 1, 0, 1, 0,
                '0248', 'content_reject', 'soft', 'agrisa@apollo.lv',
            ),
/*
            // duplicates tt_bounce_03.txt
            'bounce-email/tt_bounce_04.txt' => array(
                1, 1, 0, 1, 0,
                '0248', 'content_reject', 'soft', 'agrisa@apollo.lv',
            ),
 */
            'bounce-email/tt_bounce_05.txt' => array(
                1, 1, 0, 1, 0,
                '0249', 'unknown', 'hard', 'evor@apollo.lv',
            ),
            'bounce-email/tt_bounce_05.eml' => array(
                1, 1, 0, 1, 0,
                '0193', 'unknown', 'hard', 'evor@apollo.lv',
            ),
            'bounce-email/tt_bounce_06.txt' => array(
                1, 1, 0, 1, 0,
                '0250', 'antispam', 'blocked', 'info@koblenz.lv',
            ),
            'bounce-email/tt_bounce_07.txt' => array(
                1, 1, 0, 1, 0,
                '0236', 'unknown', 'hard', 'ilona.kalnina@citrus.lv',
            ),
            'bounce-email/tt_bounce_08.txt' => array(
                1, 1, 0, 1, 0,
                '0251', 'dns_unknown', 'hard', 'jauatkales@gmial.com',
            ),
/*
            // duplicates tt_bounce_08.txt
            'bounce-email/tt_bounce_09.txt' => array(
                1, 1, 0, 1, 0,
                '0251', 'dns_unknown', 'hard', 'gintsgrube@pop.ml.lv',
            ),
 */
/*
            // @todo review (diag_code contains "X-Postfix; temporary failure)
            'bounce-email/tt_bounce_10.txt' => array(
                1, 0, 1, 0, 0,
                '', '', '', 'info.rietumuradio.lv@mail.studio7.lv',
            ),
 */
/*
            // body contains "did not reach the following recipient(s)"; email unparseable
            // @todo why is $email == $_ENV['USER'].'@'.$_ENV['HOSTNAME'] ?
            'bounce-email/tt_bounce_11.txt' => array(
                1, 0, 1, 0, 0,
                '0000', 'unrecognized', false, '',
            ),
*/
            'bounce-email/tt_bounce_12_soft.txt' => array(
                1, 1, 0, 1, 0,
                '0252', 'delayed', 'temporary', 'info@radioliepaja.lv',
            ),
            'bounce-email/tt_bounce_13.txt' => array(
                1, 1, 0, 1, 0,
                '0013', 'unknown', 'hard', 'ilzeB@lvaei.lv',
            ),
            'bounce-email/tt_bounce_14.txt' => array(
                1, 1, 0, 1, 0,
                '0253', 'full', 'soft', 'jolanta_putnina@ogreszinas.lv',
            ),
            'bounce-email/tt_bounce_15.txt' => array(
                1, 1, 0, 1, 0,
                '0254', 'unknown', 'hard', 'info@rimibaltic.com',
            ),
            'bounce-email/tt_bounce_16.txt' => array(
                1, 1, 0, 1, 0,
                '0182', 'full', 'soft', 'notikums@tv5.lv',
            ),
            'bounce-email/tt_bounce_17.txt' => array(
                1, 1, 0, 1, 0,
                '0157', 'unknown', 'hard', 'agris.brivers@motosports.lv',
            ),
            'bounce-email/tt_bounce_18.txt' => array(
                1, 1, 0, 1, 0,
                '0256', 'delayed', 'temporary', 'welcome6_@one.lv',
            ),
/*
            // @todo review
            'bounce-email/tt_bounce_19.txt' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'bounce-email/tt_bounce_20.txt' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'bounce-email/tt_bounce_21.txt' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'bounce-email/tt_bounce_22.txt' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
*/
            // @todo review
            'bounce-email/tt_bounce_23.txt' => array(
                1, 1, 0, 1, 0,
                '0236', 'unknown', 'hard', 'Rihards_Freimanis@exigengroup.com',
            ),
/*
            // @todo review
            'bouncehammer/cannot-parse.eml' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'bouncehammer/single-message.eml' => array(
                1, 1, 0, 1, 0,
                '', '', '', '',
            ),
            // @todo review
            'instaclick/google-550.eml' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'instaclick/google-dns.eml' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'instaclick/google-permanent-failure.eml' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'instaclick/google-temporary-failure.eml' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'instaclick/dotster-430.eml' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'mail-deliverystatus-bounceparser/aol-senderblock.msg' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'mail-deliverystatus-bounceparser/aol-vacation.msg' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'mail-deliverystatus-bounceparser/aol.attachment.msg' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'mail-deliverystatus-bounceparser/aol.unknown.msg' => array(
                1, 1, 0, 1, 0,
                '', '', '', '',
            ),
            // @todo review
            'mail-deliverystatus-bounceparser/att-via-sendmail.unknown.msg' => array(
                1, 1, 0, 1, 0,
                '', '', '', '',
            ),
            // @todo review
            'mail-deliverystatus-bounceparser/autoreply.msg' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'mail-deliverystatus-bounceparser/badrcptto.msg' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'mail-deliverystatus-bounceparser/bluebottle.msg' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'mail-deliverystatus-bounceparser/boxbe-cr.msg' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'mail-deliverystatus-bounceparser/boxbe-cr2.msg' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'mail-deliverystatus-bounceparser/cam-unknown.msg' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'mail-deliverystatus-bounceparser/comcast-via-sendmail.unknown.msg' => array(
                1, 1, 0, 1, 0,
                '', '', '', '',
            ),
            // @todo review
            'mail-deliverystatus-bounceparser/cox-via-sendmail.unknown.msg' => array(
                1, 1, 0, 1, 0,
                '', '', '', '',
            ),
            // @todo review
            'mail-deliverystatus-bounceparser/deactivated-mailbox.msg' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'mail-deliverystatus-bounceparser/doesnotexist.msg' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'mail-deliverystatus-bounceparser/doesnotexist2.msg' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'mail-deliverystatus-bounceparser/domino.unknown.msg' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'mail-deliverystatus-bounceparser/exchange.unknown.msg' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'mail-deliverystatus-bounceparser/generic-postfix-via-sendmail.unknown.msg' => array(
                1, 1, 0, 1, 0,
                '', '', '', '',
            ),
            // @todo review
            'mail-deliverystatus-bounceparser/gmail-via-sendmail.unknown.msg' => array(
                1, 1, 0, 1, 0,
                '', '', '', '',
            ),
            // @todo review
            'mail-deliverystatus-bounceparser/hotmail-via-sendmail.unknown.msg' => array(
                1, 1, 0, 1, 0,
                '', '', '', '',
            ),
            // @todo review
            'mail-deliverystatus-bounceparser/iis-multiple-bounce.msg' => array(
                1, 1, 0, 1, 0,
                '', '', '', '',
            ),
            // @todo review
            'mail-deliverystatus-bounceparser/junkemailfilter.msg' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'mail-deliverystatus-bounceparser/mailbox-unknown.msg' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'mail-deliverystatus-bounceparser/malformed-dns.msg' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'mail-deliverystatus-bounceparser/me-user-unknown.msg' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'mail-deliverystatus-bounceparser/message-too-large-2.msg' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'mail-deliverystatus-bounceparser/message-too-large-3.msg' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'mail-deliverystatus-bounceparser/message-too-large.msg' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'mail-deliverystatus-bounceparser/misidentified-recipient.msg' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'mail-deliverystatus-bounceparser/msn-via-sendmail.unknown.msg' => array(
                1, 1, 0, 1, 0,
                '', '', '', '',
            ),
            // @todo review
            'mail-deliverystatus-bounceparser/no-message-collected.msg' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'mail-deliverystatus-bounceparser/no-such-domain.msg' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'mail-deliverystatus-bounceparser/nomailbox.msg' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'mail-deliverystatus-bounceparser/non-autoreply.msg' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'mail-deliverystatus-bounceparser/not-a-relay.msg' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'mail-deliverystatus-bounceparser/novell-with-rhs.msg' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'mail-deliverystatus-bounceparser/polish-autoreply.msg' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'mail-deliverystatus-bounceparser/polish-unknown.msg' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'mail-deliverystatus-bounceparser/postfix-host-unknown.msg' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'mail-deliverystatus-bounceparser/postfix-malformed.msg' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'mail-deliverystatus-bounceparser/postfix-orig.msg' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'mail-deliverystatus-bounceparser/postfix-smtp-550.msg' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'mail-deliverystatus-bounceparser/postfix.msg' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'mail-deliverystatus-bounceparser/qmail.unknown.msg' => array(
                1, 1, 0, 1, 0,
                '', '', '', '',
            ),
            // @todo review
            'mail-deliverystatus-bounceparser/quota-2.msg' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'mail-deliverystatus-bounceparser/quota-4.msg' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'mail-deliverystatus-bounceparser/quota-5.msg' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'mail-deliverystatus-bounceparser/quota-6.msg' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'mail-deliverystatus-bounceparser/quota.msg' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'mail-deliverystatus-bounceparser/rcpt-dne.msg' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'mail-deliverystatus-bounceparser/rcpthosts.msg' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'mail-deliverystatus-bounceparser/relaying-denied.msg' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'mail-deliverystatus-bounceparser/sendmail-host-unknown.msg' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'mail-deliverystatus-bounceparser/spam-bogus-email-in-report.msg' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'mail-deliverystatus-bounceparser/spam-lots-of-bogus-addresses.msg' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'mail-deliverystatus-bounceparser/spam-rejection-uribl.msg' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'mail-deliverystatus-bounceparser/spam-rejection.msg' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'mail-deliverystatus-bounceparser/spam-rejection10.msg' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'mail-deliverystatus-bounceparser/spam-rejection11.msg' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'mail-deliverystatus-bounceparser/spam-rejection12.msg' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'mail-deliverystatus-bounceparser/spam-rejection13.msg' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'mail-deliverystatus-bounceparser/spam-rejection14.msg' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'mail-deliverystatus-bounceparser/spam-rejection15.msg' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'mail-deliverystatus-bounceparser/spam-rejection16.msg' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'mail-deliverystatus-bounceparser/spam-rejection17.msg' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'mail-deliverystatus-bounceparser/spam-rejection18.msg' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'mail-deliverystatus-bounceparser/spam-rejection19.msg' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'mail-deliverystatus-bounceparser/spam-rejection2.msg' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'mail-deliverystatus-bounceparser/spam-rejection20.msg' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'mail-deliverystatus-bounceparser/spam-rejection21.msg' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'mail-deliverystatus-bounceparser/spam-rejection22.msg' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'mail-deliverystatus-bounceparser/spam-rejection23.msg' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'mail-deliverystatus-bounceparser/spam-rejection24.msg' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'mail-deliverystatus-bounceparser/spam-rejection25.msg' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'mail-deliverystatus-bounceparser/spam-rejection26.msg' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'mail-deliverystatus-bounceparser/spam-rejection27.msg' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'mail-deliverystatus-bounceparser/spam-rejection3.msg' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'mail-deliverystatus-bounceparser/spam-rejection4.msg' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'mail-deliverystatus-bounceparser/spam-rejection5.msg' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'mail-deliverystatus-bounceparser/spam-rejection6.msg' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'mail-deliverystatus-bounceparser/spam-rejection7.msg' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'mail-deliverystatus-bounceparser/spam-rejection8.msg' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'mail-deliverystatus-bounceparser/spam-rejection9.msg' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'mail-deliverystatus-bounceparser/spam-with-badly-parsed-email.msg' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'mail-deliverystatus-bounceparser/spam-with-image.msg' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'mail-deliverystatus-bounceparser/spamassassin.msg' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'mail-deliverystatus-bounceparser/spambouncer.msg' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'mail-deliverystatus-bounceparser/surfcontrol-extra-newline.msg' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'mail-deliverystatus-bounceparser/user-unknown-disabled.msg' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'mail-deliverystatus-bounceparser/user-unknown-dne.msg' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'mail-deliverystatus-bounceparser/user-unknown-not-active.msg' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'mail-deliverystatus-bounceparser/user-unknown-not.msg' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'mail-deliverystatus-bounceparser/user-unknown-polish.msg' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'mail-deliverystatus-bounceparser/virus-caused-multiple-weird-reports.msg' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'mail-deliverystatus-bounceparser/warning-1.msg' => array(
                1, 1, 0, 1, 0,
                '', '', '', '',
            ),
            // @todo review
            'mail-deliverystatus-bounceparser/warning-2.msg' => array(
                1, 1, 0, 1, 0,
                '', '', '', '',
            ),
            // @todo review
            'mail-deliverystatus-bounceparser/warning-3.msg' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'mail-deliverystatus-bounceparser/warning-4.msg' => array(
                1, 1, 0, 1, 0,
                '', '', '', '',
            ),
            // @todo review
            'mail-deliverystatus-bounceparser/warning-5.msg' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'mail-deliverystatus-bounceparser/warning-6.msg' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'mail-deliverystatus-bounceparser/warning-7.msg' => array(
                1, 1, 0, 1, 0,
                '', '', '', '',
            ),
            // @todo review
            'mail-deliverystatus-bounceparser/warning-8.msg' => array(
                1, 1, 0, 1, 0,
                '', '', '', '',
            ),
            // @todo review
            'mail-deliverystatus-bounceparser/whitelist.msg' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'mail-deliverystatus-bounceparser/yahoo-user-unknown.msg' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'mail-deliverystatus-bounceparser/yahoo-via-sendmail.unknown.msg' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'node-baunsu/auto_earthlink.txt' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'node-baunsu/bounce_aol.txt' => array(
                1, 1, 0, 1, 0,
                '', '', '', '',
            ),
            // @todo review
            'node-baunsu/bounce_att.txt' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'node-baunsu/bounce_charter.txt' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'node-baunsu/bounce_cox.txt' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'node-baunsu/bounce_earthlink.txt' => array(
                1, 1, 0, 1, 0,
                '', '', '', '',
            ),
            // @todo review
            'node-baunsu/bounce_exchange.txt' => array(
                1, 1, 0, 1, 0,
                '', '', '', '',
            ),
            // @todo review
            'node-baunsu/bounce_gmail.txt' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'node-baunsu/bounce_hotmail.txt' => array(
                1, 1, 0, 1, 0,
                '', '', '', '',
            ),
            // @todo review
            'node-baunsu/bounce_me.txt' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'node-baunsu/bounce_postfix.txt' => array(
                1, 1, 0, 1, 0,
                '', '', '', '',
            ),
            // @todo review
            'node-baunsu/bounce_spam.txt' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'node-baunsu/bounce_yahoo.txt' => array(
                1, 1, 0, 1, 0,
                '', '', '', '',
            ),
            // @todo review
            'node-baunsu/encoded_spam.txt' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'PHP-Bounce-Handler/1.eml' => array(
                1, 1, 0, 1, 0,
                '', '', '', '',
            ),
            // @todo review
            'PHP-Bounce-Handler/10.eml' => array(
                1, 1, 0, 1, 0,
                '', '', '', '',
            ),
            // @todo review
            'PHP-Bounce-Handler/11.eml' => array(
                1, 1, 0, 1, 0,
                '', '', '', '',
            ),
            // @todo review
            'PHP-Bounce-Handler/12.eml' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'PHP-Bounce-Handler/13.eml' => array(
                1, 1, 0, 1, 0,
                '', '', '', '',
            ),
            // @todo review
            'PHP-Bounce-Handler/14.eml' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'PHP-Bounce-Handler/15.eml' => array(
                1, 1, 0, 1, 0,
                '', '', '', '',
            ),
            // @todo review
            'PHP-Bounce-Handler/16.eml' => array(
                1, 1, 0, 1, 0,
                '', '', '', '',
            ),
            // @todo review
            'PHP-Bounce-Handler/17.eml' => array(
                1, 1, 0, 1, 0,
                '', '', '', '',
            ),
            // @todo review
            'PHP-Bounce-Handler/18.eml' => array(
                1, 1, 0, 1, 0,
                '', '', '', '',
            ),
            // @todo review
            'PHP-Bounce-Handler/19.eml' => array(
                1, 1, 0, 1, 0,
                '', '', '', '',
            ),
            // @todo review
            'PHP-Bounce-Handler/2.eml' => array(
                1, 1, 0, 1, 0,
                '', '', '', '',
            ),
            // @todo review
            'PHP-Bounce-Handler/20.eml' => array(
                1, 1, 0, 1, 0,
                '', '', '', '',
            ),
            // @todo review
            'PHP-Bounce-Handler/21.eml' => array(
                1, 1, 0, 1, 0,
                '', '', '', '',
            ),
            // @todo review
            'PHP-Bounce-Handler/22.eml' => array(
                1, 1, 0, 1, 0,
                '', '', '', '',
            ),
            // @todo review
            'PHP-Bounce-Handler/23.eml' => array(
                1, 1, 0, 1, 0,
                '', '', '', '',
            ),
            // @todo review
            'PHP-Bounce-Handler/24.eml' => array(
                1, 1, 0, 1, 0,
                '', '', '', '',
            ),
            // @todo review
            'PHP-Bounce-Handler/25.eml' => array(
                1, 1, 0, 1, 0,
                '', '', '', '',
            ),
            // @todo review
            'PHP-Bounce-Handler/26.eml' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'PHP-Bounce-Handler/27.eml' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'PHP-Bounce-Handler/28.eml' => array(
                1, 1, 0, 1, 0,
                '', '', '', '',
            ),
            // @todo review
            'PHP-Bounce-Handler/29.eml' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'PHP-Bounce-Handler/3.eml' => array(
                1, 1, 0, 1, 0,
                '', '', '', '',
            ),
            // @todo review
            'PHP-Bounce-Handler/30.eml' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'PHP-Bounce-Handler/31.eml' => array(
                1, 1, 0, 1, 0,
                '', '', '', '',
            ),
            // @todo review
            'PHP-Bounce-Handler/32.eml' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'PHP-Bounce-Handler/33.eml' => array(
                1, 1, 0, 1, 0,
                '', '', '', '',
            ),
            // @todo review
            'PHP-Bounce-Handler/34.eml' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'PHP-Bounce-Handler/35.eml' => array(
                1, 1, 0, 1, 0,
                '', '', '', '',
            ),
            // @todo review
            'PHP-Bounce-Handler/36.eml' => array(
                1, 1, 0, 1, 0,
                '', '', '', '',
            ),
            // @todo review
            'PHP-Bounce-Handler/37.eml' => array(
                1, 1, 0, 1, 0,
                '', '', '', '',
            ),
            // @todo review
            'PHP-Bounce-Handler/38.eml' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'PHP-Bounce-Handler/39.eml' => array(
                1, 1, 0, 1, 0,
                '', '', '', '',
            ),
            // @todo review
            'PHP-Bounce-Handler/4.eml' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'PHP-Bounce-Handler/40.eml' => array(
                1, 1, 0, 1, 0,
                '', '', '', '',
            ),
            // @todo review
            'PHP-Bounce-Handler/41.eml' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'PHP-Bounce-Handler/42.eml' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'PHP-Bounce-Handler/43.eml' => array(
                1, 1, 0, 1, 0,
                '', '', '', '',
            ),
            // @todo review
            'PHP-Bounce-Handler/44.eml' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'PHP-Bounce-Handler/45.eml' => array(
                1, 1, 0, 1, 0,
                '', '', '', '',
            ),
            // @todo review
            'PHP-Bounce-Handler/46.eml' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'PHP-Bounce-Handler/47.eml' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'PHP-Bounce-Handler/48.eml' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'PHP-Bounce-Handler/49.eml' => array(
                1, 1, 0, 1, 0,
                '', '', '', '',
            ),
            // @todo review
            'PHP-Bounce-Handler/5.1.1.eml' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'PHP-Bounce-Handler/5.eml' => array(
                1, 1, 0, 1, 0,
                '', '', '', '',
            ),
            // @todo review
            'PHP-Bounce-Handler/50.eml' => array(
                1, 1, 0, 1, 0,
                '', '', '', '',
            ),
            // @todo review
            'PHP-Bounce-Handler/51.eml' => array(
                1, 1, 0, 1, 0,
                '', '', '', '',
            ),
            // @todo review
            'PHP-Bounce-Handler/52.eml' => array(
                1, 1, 0, 1, 0,
                '', '', '', '',
            ),
            // @todo review
            'PHP-Bounce-Handler/53.eml' => array(
                1, 1, 0, 1, 0,
                '', '', '', '',
            ),
            // @todo review
            'PHP-Bounce-Handler/54.eml' => array(
                1, 1, 0, 1, 0,
                '', '', '', '',
            ),
            // @todo review
            'PHP-Bounce-Handler/55.eml' => array(
                1, 1, 0, 1, 0,
                '', '', '', '',
            ),
            // @todo review
            'PHP-Bounce-Handler/56.eml' => array(
                1, 1, 0, 1, 0,
                '', '', '', '',
            ),
            // @todo review
            'PHP-Bounce-Handler/57.eml' => array(
                1, 1, 0, 1, 0,
                '', '', '', '',
            ),
            // @todo review
            'PHP-Bounce-Handler/58.eml' => array(
                1, 1, 0, 1, 0,
                '', '', '', '',
            ),
            // @todo review
            'PHP-Bounce-Handler/59.eml' => array(
                1, 1, 0, 1, 0,
                '', '', '', '',
            ),
            // @todo review
            'PHP-Bounce-Handler/6.eml' => array(
                1, 1, 0, 1, 0,
                '', '', '', '',
            ),
            // @todo review
            'PHP-Bounce-Handler/60.eml' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'PHP-Bounce-Handler/7.eml' => array(
                1, 1, 0, 1, 0,
                '', '', '', '',
            ),
            // @todo review
            'PHP-Bounce-Handler/8.eml' => array(
                1, 1, 0, 1, 0,
                '', '', '', '',
            ),
            // @todo review
            'PHP-Bounce-Handler/9.eml' => array(
                1, 1, 0, 1, 0,
                '', '', '', '',
            ),
            // @todo review
            'PHP-Bounce-Handler/arf1.txt' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'PHP-Bounce-Handler/arf2.txt' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'PHP-Bounce-Handler/arf3.txt' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'PHP-Bounce-Handler/arf4.txt' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'PHP-Bounce-Handler/arf5.txt' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'PHP-Bounce-Handler/arf6.txt' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'PHP-Bounce-Handler/exchange1.eml' => array(
                1, 1, 0, 1, 0,
                '', '', '', '',
            ),
            // @todo review
            'PHP-Bounce-Handler/exchange2.eml' => array(
                1, 1, 0, 1, 0,
                '', '', '', '',
            ),
            // @todo review
            'PHP-Bounce-Handler/exchange3.eml' => array(
                1, 1, 0, 1, 0,
                '', '', '', '',
            ),
            // @todo review
            'PHP-Bounce-Handler/hotmailbounce.txt' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'PHP-Bounce-Handler/testfile.eml' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'PHP-Bounce-Handler/unsubscribe.txt' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'tmail_bouncer/aol.eml' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'tmail_bouncer/box_full.eml' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'tmail_bouncer/legit_multipart.eml' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'tmail_bouncer/legit_with_quota.eml' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'tmail_bouncer/out_of_office.eml' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'tmail_bouncer/verizon.eml' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'tmail_bouncer/yahoo.eml' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
            // @todo review
            'tmail_bouncer/yahoo_legit.eml' => array(
                1, 0, 1, 0, 0,
                '', '', '', '',
            ),
 */
        );

        $self = $this;
        $bmh = new BounceMailHandler;
        $bmh->testMode = true;

        foreach ($testData as $testFile => $expected)
        {
            list($fetched, $processed, $unprocessed, $deleted, $moved, $ruleNo, $ruleCat, $bounceType, $email) = $expected;

            ob_start();
            $rc = $bmh->openLocal($this->getMailboxPath(__DIR__.'/../../fixtures/'.$testFile));
            ob_end_clean();

            $this->assertTrue($rc, $testFile.': openLocal');

            $bmh->actionFunction =
                function($msgnum, $bounceType, $email, $subject, $xheader, $remove, $ruleNo, $ruleCat, $totalFetched, $body)
                    use ($self, $testFile, $expected)
                {
                    return $remove === true || $remove === 1;
                };

            ob_start();
            $rc = $bmh->processMailbox();
            $output = ob_get_contents();
            ob_end_clean();

            $this->assertTrue($rc, $testFile.': processMailbox');

            preg_match('/Read: ([0-9]+) messages/', $output, $matches);
            $this->assertEquals($fetched, $matches[1], $testFile.': messages read');

            preg_match('/([0-9]+) action taken/', $output, $matches);
            $this->assertEquals($processed, $matches[1], $testFile.': action taken');

            preg_match('/([0-9]+) no action taken/', $output, $matches);
            $this->assertEquals($unprocessed, $matches[1], $testFile.': no action taken');

            preg_match('/([0-9]+) messages deleted/', $output, $matches);
            $this->assertEquals($deleted, $matches[1], $testFile.': messages deleted');

            preg_match('/([0-9]+) messages moved/', $output, $matches);
            $this->assertEquals($moved, $matches[1], $testFile.': messages moved');

            preg_match('/Match: ([^:]+):([^;]+); ([^;]*); <?([^<]*)/', $output, $matches);
            $this->assertEquals($ruleNo, $matches[1], $testFile.': rule_no');
            $this->assertEquals($ruleCat, $matches[2], $testFile.': rule_cat');
            $this->assertEquals($bounceType, $matches[3], $testFile.': bounce_type');
            $this->assertEquals($email, $matches[4], $testFile.': email');
        }
    }
}
