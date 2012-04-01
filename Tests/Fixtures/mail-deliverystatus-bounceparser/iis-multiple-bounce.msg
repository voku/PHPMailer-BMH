Return-Path: <MAILER-DAEMON@somehost.example.com>
Received: from invalid.host.invalid ([10.0.0.61] helo=smtp.example.com)
	by otherhost.otherdomain.invalid with esmtp (Exim 4.63)
	id 1GIX3N-00076U-VJ; Wed, 30 Aug 2006 13:52:56 -0700
From: postmaster@SomeServer
To: mailing@example.com
Date: Wed, 30 Aug 2006 13:54:35 -0700
MIME-Version: 1.0
Content-Type: multipart/report; report-type=delivery-status;
	boundary="9B095B5ADSN=_01C6CA6871B6A9A600000046smtp.childrensna"
Message-ID: <BYXifWoqF00000029@smtp.example.com>
Subject: Delivery Status Notification (Failure)

This is a MIME-formatted message.  
Portions of this message may be unreadable without a MIME-capable mail program.

--9B095B5ADSN=_01C6CA6871B6A9A600000046smtp.childrensna
Content-Type: text/plain; charset=unicode-1-1-utf-7

This is an automatically generated Delivery Status Notification.

Delivery to the following recipients failed.

       bounced1@example.net
       bounced2@example.net
       bounced3@example.net
       bounced4@example.net




--9B095B5ADSN=_01C6CA6871B6A9A600000046smtp.childrensna
Content-Type: message/delivery-status

Reporting-MTA: dns;smtp.example.com
Received-From-MTA: dns;AdminAsst
Arrival-Date: Wed, 30 Aug 2006 13:54:00 -0700

Final-Recipient: rfc822;bounced1@example.net
Action: failed
Status: 5.0.0
Diagnostic-Code: smtp;550 Requested action not taken: mailbox unavailable


Final-Recipient: rfc822;bounced2@example.net
Action: failed
Status: 5.0.0
Diagnostic-Code: smtp;550 Requested action not taken: mailbox unavailable


Final-Recipient: rfc822;bounced3@example.net
Action: failed
Status: 5.0.0
Diagnostic-Code: smtp;550 Requested action not taken: mailbox unavailable


Final-Recipient: rfc822;bounced4@example.net
Action: failed
Status: 5.0.0
Diagnostic-Code: smtp;550 Requested action not taken: mailbox unavailable


--9B095B5ADSN=_01C6CA6871B6A9A600000046smtp.childrensna
Content-Type: message/rfc822

Received: from AdminAsst ([192.168.144.3]) by smtp.example.com with Microsoft SMTPSVC(5.0.2195.6713);
	 Wed, 30 Aug 2006 13:54:00 -0700
Reply-To: <mailing@example.com>
From: "Mailing" <mailing@example.com>
To: <bounced1@example.net>,
	<validaddress@example.net>,
	<bounced2@example.net>,
	<bounced3@example.net>,
	<bounced4@example.net>
Subject: Test
Date: Wed, 30 Aug 2006 13:48:01 -0700
Organization: The Children's Nature Institute
MIME-Version: 1.0
Content-Type: multipart/alternative;
	boundary="----=_NextPart_000_0018_01C6CC3A.E8D59A00"
X-Mailer: Microsoft Office Outlook, Build 11.0.6353
thread-index: AcbMdZUQbmyIksvUQrSuXsdtbeoIDw==
X-MimeOLE: Produced By Microsoft MimeOLE V6.00.2900.2962
Return-Path: mailing@example.com
Message-ID: <CNISERVERTrpwACkWHZ00000098@smtp.example.com>
X-OriginalArrivalTime: 30 Aug 2006 20:54:00.0734 (UTC) FILETIME=[6B6FE3E0:01C6CC76]

This is a multi-part message in MIME format.

------=_NextPart_000_0018_01C6CC3A.E8D59A00
Content-Type: text/plain;
	charset="us-ascii"
Content-Transfer-Encoding: 7bit

This is a test to undeliverable email addresses.


------=_NextPart_000_0018_01C6CC3A.E8D59A00
Content-Type: text/html;
	charset="us-ascii"
Content-Transfer-Encoding: quoted-printable

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
        "http://www.w3.org/TR/html4/loose.dtd"><html>
  <head>
 <title>Test Bounce Message</title>

<body>
<b>Test HTML part here.</b>
</body>
</html>

------=_NextPart_000_0018_01C6CC3A.E8D59A00--



--9B095B5ADSN=_01C6CA6871B6A9A600000046smtp.childrensna--

