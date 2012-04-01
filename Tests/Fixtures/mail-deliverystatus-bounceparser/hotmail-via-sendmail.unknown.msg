From MAILER-DAEMON@somehost.example.com Mon Aug 28 13:31:22 2006
Return-Path: <MAILER-DAEMON@somehost.example.com>
Received: from somehost.example.com (somehost.example.com [192.168.120.102])
	by somehost.example.com (8.12.11.20060308/8.12.11) with ESMTP id k7SKVM3o010774
	(version=TLSv1/SSLv3 cipher=DHE-RSA-AES256-SHA bits=256 verify=NO)
	for <sender@somehost.example.com>; Mon, 28 Aug 2006 13:31:22 -0700
Received: from localhost (localhost)
	by somehost.example.com (8.12.11.20060308/8.12.11) id k7SKVMvq012564;
	Mon, 28 Aug 2006 13:31:22 -0700
Date: Mon, 28 Aug 2006 13:31:22 -0700
From: Mail Delivery Subsystem <MAILER-DAEMON@somehost.example.com>
Message-Id: <200608282031.k7SKVMvq012564@somehost.example.com>
To: <sender@somehost.example.com>
MIME-Version: 1.0
Content-Type: multipart/report; report-type=delivery-status;
	boundary="k7SKVMvq012564.1156797082/somehost.example.com"
Content-Transfer-Encoding: binary
Subject: Returned mail: see transcript for details
Auto-Submitted: auto-generated (failure)

This is a MIME-encapsulated message

--k7SKVMvq012564.1156797082/somehost.example.com

The original message was received at Mon, 28 Aug 2006 13:31:22 -0700
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

--k7SKVMvq012564.1156797082/somehost.example.com
Content-Type: message/delivery-status

Reporting-MTA: dns; somehost.example.com
Received-From-MTA: DNS; localhost
Arrival-Date: Mon, 28 Aug 2006 13:31:22 -0700

Final-Recipient: RFC822; recipient@example.net
Action: failed
Status: 5.1.1
Remote-MTA: DNS; mx4.hotmail.com
Diagnostic-Code: SMTP; 550 Requested action not taken: mailbox unavailable
Last-Attempt-Date: Mon, 28 Aug 2006 13:31:22 -0700

--k7SKVMvq012564.1156797082/somehost.example.com
Content-Type: message/rfc822
Content-Transfer-Encoding: binary

Return-Path: <sender@somehost.example.com>
Received: from somehost.example.com (localhost [127.0.0.1])
	by somehost.example.com (8.12.11.20060308/8.12.11) with ESMTP id k7SKRRt4019719
	for <recipient@example.net>; Mon, 28 Aug 2006 13:31:22 -0700
Received: (from sender@localhost)
	by somehost.example.com (8.12.11.20060308/8.12.11/Submit) id k7SKChA2006269
	for recipient@example.net; Mon, 28 Aug 2006 13:12:43 -0700
Date: Mon, 28 Aug 2006 13:12:43 -0700
Message-Id: <200608282012.k7SKChA2006269@somehost.example.com>
Content-Type: multipart/alternative;
 boundary="----------=_1156795963-22574-8063"
Content-Transfer-Encoding: binary
MIME-Version: 1.0
X-Mailer: MIME-tools 5.415 (Entity 5.415)
From: Sender Address <sender@example.com>
To: Sender Address <sender@example.com>
Subject: Test Bounce Message

This is a multi-part message in MIME format...

------------=_1156795963-22574-8063
Content-Type: text/plain
Content-Disposition: inline
Content-Transfer-Encoding: quoted-printable

Plaintext message here

------------=_1156795963-22574-8063
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

------------=_1156795963-22574-8063--

--k7SKVMvq012564.1156797082/somehost.example.com--

