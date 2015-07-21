Delivered-To: automated-bounces+6d45b302-e65d-11de-a26f-a183dd31f8f5@email.example.com
Received: by 10.224.10.213 with SMTP id q21cs84376qaq;
        Fri, 11 Dec 2009 10:30:50 -0800 (PST)
Received: by 10.213.100.168 with SMTP id y40mr1869916ebn.77.1260556249621;
        Fri, 11 Dec 2009 10:30:49 -0800 (PST)
Return-Path: <>
Received: from relay.b.c (mailout.b.c [212.137.44.177])
        by mx.google.com with ESMTP id 20si2281844ewy.0.2009.12.11.10.30.49;
        Fri, 11 Dec 2009 10:30:49 -0800 (PST)
Received-SPF: neutral (google.com: 212.137.44.177 is neither permitted nor denied by best guess record for domain of relay.b.c) client-ip=212.137.44.177;
Authentication-Results: mx.google.com; spf=neutral (google.com: 212.137.44.177 is neither permitted nor denied by best guess record for domain of relay.b.c) smtp.mail=
Received: from avas-checker
	by relay-outbound-7 with esmtp (Relay-Software 4.52)
	id 1NJAGO-00020P-Pe
	for automated-bounces+6D45B302-E65D-11DE-A26F-A183DD31F8F5@email.example.com; Fri, 11 Dec 2009 18:30:48 +0000
Received: from relay.b.c (rlmta08.swi.contact.example2.com [192.168.129.8])
	by rlavas05.swi.contact.example2.com (MOS 3.8.3-GA)
	with ESMTP id FAH19161;
	Fri, 11 Dec 2009 18:30:48 GMT
Received: from [10.154.5.7] (helo=bsp1.alfred.b.c)
	by relay-inbound-8 with esmtp (Relay-Software 4.52)
	id 1NJAGO-0004Jg-SP
	for automated-bounces+6D45B302-E65D-11DE-A26F-A183DD31F8F5@email.example.com; Fri, 11 Dec 2009 18:30:48 +0000
Date: Fri, 11 Dec 2009 18:30:47 +0000 (GMT)
From: MAILER-DAEMON@alfred.b.c (Mail Delivery System)
Subject: Delayed Mail (still being retried)
To: automated-bounces+6D45B302-E65D-11DE-A26F-A183DD31F8F5@email.example.com
MIME-Version: 1.0
Content-Type: multipart/report; report-type=delivery-status;
	boundary="75381D20071AF20D.1260556247/quarantine.net.alfred.b.c"
Message-Id: <19740413061536.D2502C200B28C860@quarantine.net.alfred.b.c>

This is a MIME-encapsulated message.

--75381D20071AF20D.1260556247/quarantine.net.alfred.b.c
Content-Description: Notification

Content-Type: text/plain; charset=us-ascii

This is an automated message from the BorderWare Quarantine Server
at host quarantine.net.alfred.b.c.

###################################################
#################
# THIS IS A WARNING ONLY.  YOU DO NOT NEED TO RESEND 
YOUR MESSAGE. #
###################################################
#################
Your message could not be delivered for 4.0 hours.
It will be retried until it is 5.0 days old.

For further assistance, please send mail to <postmaster@quarantine.net.alfred.b.c>.

Reason:


--75381D20071AF20D.1260556247/quarantine.net.alfred.b.c

<fred.bloggs@alfred.b.c>: maildir delivery failed: error writing
    message: Disc quota exceeded

--75381D20071AF20D.1260556247/quarantine.net.alfred.b.c
Content-Description: Delivery report
Content-Type: message/delivery-status

Reporting-MTA: dns; quarantine.net.alfred.b.c
X-BorderWare-Quarantine-Server-Queue-ID: 75381D20071AF20D
X-BorderWare-Quarantine-Server-Sender: rfc822; automated-bounces+6D45B302-E65D-11DE-A26F-A183DD31F8F5@email.example.com
Arrival-Date: Fri, 11 Dec 2009 13:59:41 +0000 (GMT)

Final-Recipient: rfc822; fred.bloggs@alfred.b.c
Action: delayed
Status: 4.0.0
Diagnostic-Code: X-BorderWare-Quarantine-Server; maildir delivery failed: error
    writing message: Disc quota exceeded
Will-Retry-Until: Wed, 16 Dec 2009 13:59:41 +0000 (GMT)

--75381D20071AF20D.1260556247/quarantine.net.alfred.b.c
Content-Description: Undelivered Message Headers
Content-Type: text/rfc822-headers
Content-Transfer-Encoding: Quoted-Printable

DKIM-Signature: v=1; a=rsa-sha256; c=simple/simple; d=email.example.com;
	s=default10; t=1260539980;
	bh=UWXXmn7Qye4rWqDNT7T9Yq8iLVOi5PK882Fy/ce6e+M=;
	h=MIME-Version:Content-Transfer-Encoding:Content-Type:Date:From:To:
	 Subject:Message-Id;
	b=MrYiXx2Twf4sCTRAxC2BkIvXkLkEN2gNyVDYfIwiihTHDi8N/K12ukV0Y2AIHcoqW
	 QiPILNZYI2JK99rrn66g23Z85YS0xmnTr+vlqo0AXz/Xn4cfX36ykXkp0kwF/rC7X9
	 6bhNgCuia0h/QBDLoqtv1Jo2KhIQ2O/uEfZ9J1XM=
MIME-Version: 1.0
Content-Transfer-Encoding: 7bit
Content-Type: multipart/related; boundary="_----------=_1260539980181374799"
X-Mailer: MIME::Lite 3.027 (F2.77; T1.28; A2.04; B3.08; Q3.08)
Date: Fri, 11 Dec 2009 13:59:40 +0000
From: Customer Services <customerservices@email.example.com>
To: fred.bloggs@alfred.b.c
Subject: test
Message-Id: <20091211135940.B77941658238@server4.example.co.uk>
X-Junkmail-Status: score=10/50, host=de.f.h.j
X-Junkmail-SD-Raw: score=unknown,
	refid=str=0001.0A0B0206.4B22504D.012B:SCFSTAT9813731,ss=1,fgs=0,
	ip=94.236.45.212,
	so=2006-12-20 11:47:04,
	dmn=5.7.1/2009-08-27
X-Junkmail-IWF: false
X-Mirapoint-RAPID-Raw: score=unknown(0),
	refid=str=0001.0A0B0206.4B22504D.012B:SCFSTAT9813731,ss=1,fgs=0,
	ip=94.236.45.212,
	so=2006-12-20 11:47:04,
	dmn=5.7.1/2009-08-27
X-Mirapoint-Loop-Id: 828e805072be619436a18afb244b665b
X-STA-Metric: 90 (engine=030)
X-STA-NotSpam: url:epl url:uuid spec:dis:2olo? 2010? rules?
X-STA-Spam: subject:Dear spec:usig:3.2.3/4/5 url:xhtml url:pl header:Message-Id:1
X-BTI-AntiSpam: score:90,sta:90/030,dnsbl:off,sw:passed,bsn:none,spf:off,bsctr:off,dk:off,pbmf:none,ipr:0/1,trusted:no,ts:no,bs:no,ubl:off

--75381D20071AF20D.1260556247/quarantine.net.alfred.b.c--

