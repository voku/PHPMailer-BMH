Return-Path: <MAILER-DAEMON@somehost.example.com>
Received: from smith.hpi.net (smith.hpi.net [209.12.9.10])
	by somehost.example.com (8.12.11.20060308/8.12.11) with ESMTP id k56M5rBI022296
	for <someuser@somehost.example.com>; Tue, 6 Jun 2006 15:05:57 -0700
Received: from morpheus.hpi.net (morpheus.hpi.net [209.12.9.12]) by hpi.net
 (Rockliffe SMTPRA 5.3.11) with ESMTP id <B0016903844@smith.hpi.net> for <sender@somehost.example.com>;
 Tue, 6 Jun 2006 15:55:51 -0600
Received: by morpheus.hpi.net (Postfix)
	id 9C43E992D7; Tue,  6 Jun 2006 15:04:30 +0000 (GMT)
Date: Tue,  6 Jun 2006 15:04:30 +0000 (GMT)
From: MAILER-DAEMON@morpheus.hpi.net (Mail Delivery System)
Subject: Undelivered Mail Returned to Sender
To: sender@somehost.example.com
MIME-Version: 1.0
Content-Type: multipart/report; report-type=delivery-status;
	boundary="1B6C1992D2.1149606270/morpheus.hpi.net"
Content-Transfer-Encoding: 8bit
Message-Id: <20060606150430.9C43E992D7@morpheus.hpi.net>

This is a MIME-encapsulated message.

--1B6C1992D2.1149606270/morpheus.hpi.net
Content-Description: Notification
Content-Type: text/plain

This is the Postfix program at host morpheus.hpi.net.

I'm sorry to have to inform you that your message could not
be delivered to one or more recipients. It's attached below.

For further assistance, please send mail to <postmaster>

If you do so, please include this problem report. You can
delete your own text from the attached returned message.

			The Postfix program

<luser@otherhost.invalid>: host 209.12.9.10[209.12.9.10] said: 550 5.1.1
    <luser@otherhost.invalid> is not a valid mailbox (in reply to RCPT TO command)

--1B6C1992D2.1149606270/morpheus.hpi.net
Content-Description: Delivery report
Content-Type: message/delivery-status

Reporting-MTA: dns; morpheus.hpi.net
X-Postfix-Queue-ID: 1B6C1992D2
X-Postfix-Sender: rfc822; sender@somehost.example.com
Arrival-Date: Tue,  6 Jun 2006 15:04:30 +0000 (GMT)

Final-Recipient: rfc822; luser@otherhost.invalid
Action: failed
Status: 5.0.0
Diagnostic-Code: X-Postfix; host 209.12.9.10[209.12.9.10] said: 550 5.1.1
    <luser@otherhost.invalid> is not a valid mailbox (in reply to RCPT TO command)

--1B6C1992D2.1149606270/morpheus.hpi.net
Content-Description: Undelivered Message
Content-Type: message/rfc822
Content-Transfer-Encoding: 8bit

Received: from localhost (localhost.hpi.net [127.0.0.1])
	by morpheus.hpi.net (Postfix) with ESMTP id 1B6C1992D2
	for <luser@otherhost.invalid>; Tue,  6 Jun 2006 15:04:30 +0000 (GMT)
Received: from morpheus.hpi.net ([127.0.0.1])
 by localhost (morpheus.hpi.net [127.0.0.1]) (amavisd-new, port 10024)
 with ESMTP id 30679-02-2 for <luser@otherhost.invalid>;
 Tue,  6 Jun 2006 15:04:13 +0000 (GMT)
Received: from somehost.example.com (X [64.156.13.1])
	by morpheus.hpi.net (Postfix) with ESMTP id C443F992DA
	for <luser@otherhost.invalid>; Tue,  6 Jun 2006 15:04:11 +0000 (GMT)
Received: from somehost.example.com (localhost [127.0.0.1])
	by somehost.example.com (8.12.11.20060308/8.12.11) with ESMTP id k56LpAtb024010
	for <luser@otherhost.invalid>; Tue, 6 Jun 2006 14:55:30 -0700
Received: (from sender@localhost)
	by somehost.example.com (8.12.11.20060308/8.12.11/Submit) id k56LZFWj016815
	for luser@otherhost.invalid; Tue, 6 Jun 2006 14:35:15 -0700
Date: Tue, 6 Jun 2006 14:35:15 -0700
Message-Id: <200606062135.k56LZFWj016815@somehost.example.com>
Content-Type: multipart/alternative;
 boundary="----------=_1149629715-3704-6459"
Content-Transfer-Encoding: binary
MIME-Version: 1.0
X-Mailer: MIME-tools 5.415 (Entity 5.415)
From: Sender Name <address@example.com>
To: Sender Name <sender@example.com>
Subject: Test Bounce Message

This is a multi-part message in MIME format...

------------=_1149629715-3704-6459
Content-Type: text/plain
Content-Disposition: inline
Content-Transfer-Encoding: quoted-printable

Plain text here

------------=_1149629715-3704-6459
Content-Type: text/html
Content-Disposition: inline
Content-Transfer-Encoding: quoted-printable

<HTML>
<b> This is my message </b>
</HTML>

------------=_1149629715-3704-6459--
--1B6C1992D2.1149606270/morpheus.hpi.net--

