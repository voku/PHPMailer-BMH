Return-Path: <MAILER-DAEMON@somehost.example.com>
Received: from somehost.example.com (somehost.example.com [192.168.120.120])
	by somehost.example.com (8.12.11.20060308/8.12.11) with ESMTP id k56LrqZ8011857
	(version=TLSv1/SSLv3 cipher=DHE-RSA-AES256-SHA bits=256 verify=NO)
	for <sender@somehost.example.com>; Tue, 6 Jun 2006 14:53:52 -0700
Received: from localhost (localhost)
	by somehost.example.com (8.12.11.20060308/8.12.11) id k56LrqTZ025618;
	Tue, 6 Jun 2006 14:53:52 -0700
Date: Tue, 6 Jun 2006 14:53:52 -0700
From: Mail Delivery Subsystem <MAILER-DAEMON@somehost.example.com>
Message-Id: <200606062153.k56LrqTZ025618@somehost.example.com>
To: <sender@somehost.example.com>
MIME-Version: 1.0
Content-Type: multipart/report; report-type=delivery-status;
	boundary="k56LrqTZ025618.1149630832/somehost.example.com"
Content-Transfer-Encoding: binary
Subject: Returned mail: see transcript for details
Auto-Submitted: auto-generated (failure)

This is a MIME-encapsulated message

--k56LrqTZ025618.1149630832/somehost.example.com

The original message was received at Tue, 6 Jun 2006 14:53:50 -0700
from localhost [127.0.0.1]

   ----- The following addresses had permanent fatal errors -----
<recipient@example.net>
    (reason: 550 Requested action not taken: mailbox unavailable)

   ----- Transcript of session follows -----
... while talking to mx4.hotmail.com.:
>>> DATA
<<< 550 Requested action not taken: mailbox unavailable
550 5.1.1 <recipient@example.net>... User unknown
<<< 503 Need Rcpt command.

--k56LrqTZ025618.1149630832/somehost.example.com
Content-Type: message/delivery-status

Reporting-MTA: dns; somehost.example.com
Received-From-MTA: DNS; localhost
Arrival-Date: Tue, 6 Jun 2006 14:53:50 -0700

Final-Recipient: RFC822; recipient@example.net
Action: failed
Status: 5.1.1
Remote-MTA: DNS; mx4.hotmail.com
Diagnostic-Code: SMTP; 550 Requested action not taken: mailbox unavailable
Last-Attempt-Date: Tue, 6 Jun 2006 14:53:51 -0700

--k56LrqTZ025618.1149630832/somehost.example.com
Content-Type: message/rfc822
Content-Transfer-Encoding: binary

Return-Path: <sender@somehost.example.com>
Received: from somehost.example.com (localhost [127.0.0.1])
	by somehost.example.com (8.12.11.20060308/8.12.11) with ESMTP id k56LpAjJ024010
	for <recipient@example.net>; Tue, 6 Jun 2006 14:53:50 -0700
Received: (from sender@localhost)
	by somehost.example.com (8.12.11.20060308/8.12.11/Submit) id k56LmLDK023033
	for recipient@example.net; Tue, 6 Jun 2006 14:48:21 -0700
Date: Tue, 6 Jun 2006 14:48:21 -0700
Message-Id: <200606062148.k56LmLDK023033@somehost.example.com>
Content-Type: multipart/alternative;
 boundary="----------=_1149630501-3704-9511"
Content-Transfer-Encoding: binary
MIME-Version: 1.0
X-Mailer: MIME-tools 5.415 (Entity 5.415)
From: Sender Address <sender@example.com>
To: Sender Address <sender@example.com>
Subject: Test Bounce Message

This is a multi-part message in MIME format...

------------=_1149630501-3704-9511
Content-Type: text/plain
Content-Disposition: inline
Content-Transfer-Encoding: quoted-printable

Plain text message here.

------------=_1149630501-3704-9511
Content-Type: text/html
Content-Disposition: inline
Content-Transfer-Encoding: quoted-printable

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
        "http://www.w3.org/TR/html4/loose.dtd"><html>
  <head>
 <title>Test Bounce Message</title>

<body>
<b>Test HTML part here.</b>
</body>
</html>

------------=_1149630501-3704-9511--

--k56LrqTZ025618.1149630832/somehost.example.com--


