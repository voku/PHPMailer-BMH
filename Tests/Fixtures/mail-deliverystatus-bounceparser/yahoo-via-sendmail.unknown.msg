Return-Path: <MAILER-DAEMON@somehost.example.com>
Received: from somehost.example.com (somehost.example.com [192.168.120.120])
	by somehost.example.com (8.12.11.20060308/8.12.11) with ESMTP id k56LsrJq013151
	(version=TLSv1/SSLv3 cipher=DHE-RSA-AES256-SHA bits=256 verify=NO)
	for <sender@somehost.example.com>; Tue, 6 Jun 2006 14:55:06 -0700
Received: from localhost (localhost)
	by somehost.example.com (8.12.11.20060308/8.12.11) id k56LsrTZ025705;
	Tue, 6 Jun 2006 14:54:53 -0700
Date: Tue, 6 Jun 2006 14:54:53 -0700
From: Mail Delivery Subsystem <MAILER-DAEMON@somehost.example.com>
Message-Id: <200606062154.k56LsrTZ025705@somehost.example.com>
To: <sender@somehost.example.com>
MIME-Version: 1.0
Content-Type: multipart/report; report-type=delivery-status;
	boundary="k56LsrTZ025705.1149630893/somehost.example.com"
Content-Transfer-Encoding: binary
Subject: Returned mail: see transcript for details
Auto-Submitted: auto-generated (failure)

This is a MIME-encapsulated message

--k56LsrTZ025705.1149630893/somehost.example.com

The original message was received at Tue, 6 Jun 2006 14:53:59 -0700
from localhost [127.0.0.1]

   ----- The following addresses had permanent fatal errors -----
<recipient@example.net>
    (reason: 554 delivery error: dd Sorry your message to recipient@example.net cannot be delivered. This account has been disabled or discontinued [#102]. - mta330.mail.mud.yahoo.com)

   ----- Transcript of session follows -----
451 4.4.1 reply: read error from mx3.mail.yahoo.com.
... while talking to mx2.mail.yahoo.com.:
>>> DATA
<<< 554 delivery error: dd Sorry your message to recipient@example.net cannot be delivered. This account has been disabled or discontinued [#102]. - mta330.mail.mud.yahoo.com
554 5.0.0 Service unavailable

--k56LsrTZ025705.1149630893/somehost.example.com
Content-Type: message/delivery-status

Reporting-MTA: dns; somehost.example.com
Received-From-MTA: DNS; localhost
Arrival-Date: Tue, 6 Jun 2006 14:53:59 -0700

Final-Recipient: RFC822; recipient@example.net
Action: failed
Status: 5.0.0
Remote-MTA: DNS; mx2.mail.yahoo.com
Diagnostic-Code: SMTP; 554 delivery error: dd Sorry your message to recipient@example.net cannot be delivered. This account has been disabled or discontinued [#102]. - mta330.mail.mud.yahoo.com
Last-Attempt-Date: Tue, 6 Jun 2006 14:54:53 -0700

--k56LsrTZ025705.1149630893/somehost.example.com
Content-Type: message/rfc822
Content-Transfer-Encoding: binary

Return-Path: <sender@somehost.example.com>
Received: from somehost.example.com (localhost [127.0.0.1])
	by somehost.example.com (8.12.11.20060308/8.12.11) with ESMTP id k56LpAk1024010
	for <recipient@example.net>; Tue, 6 Jun 2006 14:53:59 -0700
Received: (from sender@localhost)
	by somehost.example.com (8.12.11.20060308/8.12.11/Submit) id k56LAcCB004251
	for recipient@example.net; Tue, 6 Jun 2006 14:10:38 -0700
Date: Tue, 6 Jun 2006 14:10:38 -0700
Message-Id: <200606062110.k56LAcCB004251@somehost.example.com>
Content-Type: multipart/alternative;
 boundary="----------=_1149628238-3704-269"
Content-Transfer-Encoding: binary
MIME-Version: 1.0
X-Mailer: MIME-tools 5.415 (Entity 5.415)
From: Sender Address <sender@example.com>
To: Sender Address <sender@example.com>
Subject: Test Bounce Message

This is a multi-part message in MIME format...

------------=_1149628238-3704-269
Content-Type: text/plain
Content-Disposition: inline
Content-Transfer-Encoding: quoted-printable

Plaintext message here.

------------=_1149628238-3704-269
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

------------=_1149628238-3704-269--

--k56LsrTZ025705.1149630893/somehost.example.com--


