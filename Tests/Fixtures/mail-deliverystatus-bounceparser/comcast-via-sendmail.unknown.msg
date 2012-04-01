Return-Path: <MAILER-DAEMON@somehost.example.com>
Received: from somehost.example.com (somehost.example.com [192.168.120.115])
	by somehost.example.com (8.12.11.20060308/8.12.11) with ESMTP id k71HmEtc031797
	(version=TLSv1/SSLv3 cipher=DHE-RSA-AES256-SHA bits=256 verify=NO)
	for <sender@somehost.example.com>; Tue, 1 Aug 2006 10:48:14 -0700
Received: from localhost (localhost)
	by somehost.example.com (8.12.11.20060308/8.12.11) id k71HmEfS028945;
	Tue, 1 Aug 2006 10:48:14 -0700
Date: Tue, 1 Aug 2006 10:48:14 -0700
From: Mail Delivery Subsystem <MAILER-DAEMON@somehost.example.com>
Message-Id: <200608011748.k71HmEfS028945@somehost.example.com>
To: <sender@somehost.example.com>
MIME-Version: 1.0
Content-Type: multipart/report; report-type=delivery-status;
	boundary="k71HmEfS028945.1154454494/somehost.example.com"
Content-Transfer-Encoding: binary
Subject: Returned mail: see transcript for details
Auto-Submitted: auto-generated (failure)

This is a MIME-encapsulated message

--k71HmEfS028945.1154454494/somehost.example.com

The original message was received at Tue, 1 Aug 2006 10:48:04 -0700
from localhost [127.0.0.1]

   ----- The following addresses had permanent fatal errors -----
<recipient@example.net>
    (reason: 551 not our customer)

   ----- Transcript of session follows -----
... while talking to gateway-a.comcast.net.:
<<< 450 busy - please try later
... while talking to gateway-s.comcast.net.:
>>> DATA
<<< 551 not our customer
550 5.1.1 <recipient@example.net>... User unknown
<<< 503 need RCPT command [data]

--k71HmEfS028945.1154454494/somehost.example.com
Content-Type: message/delivery-status

Reporting-MTA: dns; somehost.example.com
Received-From-MTA: DNS; localhost
Arrival-Date: Tue, 1 Aug 2006 10:48:04 -0700

Final-Recipient: RFC822; recipient@example.net
Action: failed
Status: 5.1.6
Remote-MTA: DNS; gateway-s.comcast.net
Diagnostic-Code: SMTP; 551 not our customer
Last-Attempt-Date: Tue, 1 Aug 2006 10:48:14 -0700

--k71HmEfS028945.1154454494/somehost.example.com
Content-Type: message/rfc822
Content-Transfer-Encoding: binary

Return-Path: <sender@somehost.example.com>
Received: from somehost.example.com (localhost [127.0.0.1])
	by somehost.example.com (8.12.11.20060308/8.12.11) with ESMTP id k71HjSW6023431
	for <recipient@example.net>; Tue, 1 Aug 2006 10:48:04 -0700
Received: (from sender@localhost)
	by somehost.example.com (8.12.11.20060308/8.12.11/Submit) id k71HjChr023017
	for recipient@example.net; Tue, 1 Aug 2006 10:45:12 -0700
Date: Tue, 1 Aug 2006 10:45:12 -0700
Message-Id: <200608011745.k71HjChr023017@somehost.example.com>
Content-Type: multipart/alternative;
 boundary="----------=_1154454312-19747-1634"
Content-Transfer-Encoding: binary
MIME-Version: 1.0
X-Mailer: MIME-tools 5.415 (Entity 5.415)
From: Sender Address <sender@example.com>
To: Sender Address <sender@example.com>
Subject: Test Bounce Message

This is a multi-part message in MIME format...

------------=_1154454312-19747-1634
Content-Type: text/plain
Content-Disposition: inline
Content-Transfer-Encoding: quoted-printable

Plaintext stuff here.

------------=_1154454312-19747-1634
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

------------=_1154454312-19747-1634--

--k71HmEfS028945.1154454494/somehost.example.com--

