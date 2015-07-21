Delivered-To: automated-bounces+c62bb278-7190-11df-95f8-f0d13a824f27@email.example.com
Received: by 10.216.170.84 with SMTP id o62cs134738wel;
        Sun, 6 Jun 2010 10:56:21 -0700 (PDT)
Received: by 10.227.132.65 with SMTP id a1mr12743782wbt.79.1275846980163;
        Sun, 06 Jun 2010 10:56:20 -0700 (PDT)
Return-Path: <>
Received: by mail.a.b.c (8.0.013.3) id 4C07ED5800FBA62E for automated-bounces+C62BB278-7190-11DF-95F8-F0D13A824F27@email.example.com; Sun, 6 Jun 2010 18:56:18 +0100
From: Mail Delivery Service <postmaster@mail.a.b.c>
Subject: Delivery Status Notification
To: automated-bounces+C62BB278-7190-11DF-95F8-F0D13A824F27@email.example.com
Date: Sun, 6 Jun 2010 18:56:18 +0100
Message-ID: <4C07ED5800FBA60B@>
X-CP-Transaction-ID: 4C07ED5800FBA3BD
X-CP-For: fred-bloggs@a.b.c
MIME-Version: 1.0
Content-Type: Multipart/Report; report-type=delivery-status; boundary="========/4C07ED5800FBA3BD/mail.a.b.c"

This multi-part MIME message contains a Delivery Status Notification.
If you can see this text, your mail client may not be able to understand MIME
formatted messages or DSNs (see RFC 2045 through 2049 for general MIME
information and RFC 1891 through 1894 for DSN specific information).

--========/4C07ED5800FBA3BD/mail.a.b.c
Content-Type: text/plain; charset=US-ASCII
Content-Transfer-Encoding: 8bit

 - These recipients of your message have been processed by the mail server:
fred-bloggs@a.b.c; Failed; 5.1.1 (bad destination mailbox address)

    Remote MTA 82.132.141.70: SMTP diagnostic: 550 RCPT TO:<fred-bloggs@a.b.c> max message size exceeded



--========/4C07ED5800FBA3BD/mail.a.b.c
Content-Type: Message/Delivery-Status

Reporting-MTA: dns; mail.a.b.c
Received-from-MTA: dns; server2.example.co.uk (1.2.3.5)
Arrival-Date: Sun, 6 Jun 2010 18:56:18 +0100

Original-Recipient: rfc822;fred-bloggs@a.b.c
Final-Recipient: rfc822; fred-bloggs@a.b.c
Action: Failed
Status: 5.1.1 (bad destination mailbox address)
Remote-MTA: dns; 1.2.3.4
Diagnostic-Code: smtp; 550 RCPT TO:<fred-bloggs@a.b.c> max message size exceeded

--========/4C07ED5800FBA3BD/mail.a.b.c
Content-Type: Message/RFC822

Return-Path: <automated-bounces+C62BB278-7190-11DF-95F8-F0D13A824F27@email.example.com>
Received: from server2.example.co.uk (1.2.3.5) by mail.a.b.c (8.0.013.3)
        id 4C07ED5800FBA3BD for fred-bloggs@a.b.c; Sun, 6 Jun 2010 18:56:11 +0100
Received: by server2.example.co.uk (Postfix, from userid 507)
	id E9197B5820A; Sun,  6 Jun 2010 18:27:25 +0100 (BST)
MIME-Version: 1.0
Content-Transfer-Encoding: 7bit
Content-Type: multipart/related; boundary="_----------=_1275845245261230"
X-Mailer: MIME::Lite 3.027 (F2.77; T1.28; A2.04; B3.08; Q3.08)
Date: Sun, 6 Jun 2010 18:27:25 +0100
From: John Smith <john.smith@email.example.com>
Subject: test
To: Fred Bloggs <fred-bloggs@a.b.c>
Message-Id: <20100606172725.E9197B5820A@server2.example.co.uk>

This is a multi-part message in MIME format.

--_----------=_1275845245261230
Content-Disposition: inline
Content-Transfer-Encoding: quoted-printable
Content-Type: text/html

HTML was here

--_----------=_1275845245261230
Content-Disposition: inline; filename="witanIT.pdf"
Content-Transfer-Encoding: base64
Content-Type: application/pdf; name="witanIT.pdf"

pdfwashere
