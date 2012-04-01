Return-Path: <>
X-Spam-Checker-Version: SpamAssassin 3.1.1 (2006-03-10) on 
	mta.domain-1.com
X-Spam-Level: 
X-Spam-Status: No, score=-1.1 required=3.4 tests=AWL,BAYES_00,
	RAZOR2_CF_RANGE_51_100,RAZOR2_CF_RANGE_E4_51_100,RAZOR2_CHECK,
	SPF_HELO_PASS autolearn=no version=3.1.1
X-Original-To: sender@domain-1.com
Delivered-To: sender@domain-1.com
Received: from host-2.domain-2.org (host-2.domain-2.org [2.3.4.5])
	by mta.domain-1.com (Postfix) with SMTP id B4D4A2E6069
	for <sender@domain-1.com>; Sat, 27 May 2006 21:34:09 +0000 (UTC)
Received: (qmail 29981 invoked by uid 1014); 27 May 2006 21:34:25 -0000
Delivered-To: sender@domain-2.org
Received: (qmail 29978 invoked from network); 27 May 2006 21:34:25 -0000
Received: from unknown (HELO mta.domain-1.com) (11.22.33.44)
  by host-2.domain-2.org with SMTP; 27 May 2006 21:34:25 -0000
Received: by mta.domain-1.com (Postfix)
	id 68C8B2E6096; Sat, 27 May 2006 17:34:05 -0400 (EDT)
Date: Sat, 27 May 2006 17:34:05 -0400 (EDT)
From: MAILER-DAEMON@mta.domain-1.com (Mail Delivery System)
Subject: Undelivered Mail Returned to Sender
To: sender@domain-2.org
MIME-Version: 1.0
Content-Type: multipart/report; report-type=delivery-status;
	boundary="89BEE2E6069.1148765645/mta.domain-1.com"
Message-Id: <20060527213405.68C8B2E6096@mta.domain-1.com>
Lines: 60

This is a MIME-encapsulated message.

--89BEE2E6069.1148765645/mta.domain-1.com
Content-Description: Notification
Content-Type: text/plain

This is the Postfix program at host mta.domain-1.com.

I'm sorry to have to inform you that your message could not
be delivered to one or more recipients. It's attached below.

For further assistance, please send mail to <postmaster>

If you do so, please include this problem report. You can
delete your own text from the attached returned message.

			The Postfix program

<bounce@dest.example.com>: host mx-all.example.com[208.58.1.198] said: 554
    <bounce@dest.example.com>: Relay access denied (in reply to RCPT TO command)

--89BEE2E6069.1148765645/mta.domain-1.com
Content-Description: Delivery report
Content-Type: message/delivery-status

Reporting-MTA: dns; mta.domain-1.com
X-Postfix-Queue-ID: 89BEE2E6069
X-Postfix-Sender: rfc822; sender@domain-2.org
Arrival-Date: Sat, 27 May 2006 21:34:04 +0000 (UTC)

Final-Recipient: rfc822; bounce@dest.example.com
Action: failed
Status: 5.0.0
Diagnostic-Code: X-Postfix; host mx-all.example.com[208.58.1.198] said: 554
    <bounce@dest.example.com>: Relay access denied (in reply to RCPT TO command)

--89BEE2E6069.1148765645/mta.domain-1.com
Content-Description: Undelivered Message
Content-Type: message/rfc822

Received: by mta.domain-1.com (Postfix, from userid 1001)
	id 89BEE2E6069; Sat, 27 May 2006 21:34:04 +0000 (UTC)
Date: Sat, 27 May 2006 17:34:04 -0400
From: Ricardo SIGNES <sender@domain-2.org>
To: bounce@dest.example.com
Subject: asdf
Message-ID: <20060527213404.GN13012@mta.domain-1.com>
MIME-Version: 1.0
Content-Type: text/plain; charset=us-ascii
Content-Disposition: inline
User-Agent: Mutt/1.5.11+cvs20060126


Test.
-- 
sender

--89BEE2E6069.1148765645/mta.domain-1.com--

