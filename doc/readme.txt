About PHPMailer-BMH (Bounce Mail Handler):

Bounce mail management is an area that is largely ignored by PHP developers.
There are no comprehensive scripts available, yet it is one area decidedly in
need. It is unclear who the original developer of BMH was, but to that
individual: many many thanks. The quality of the rules, and the concept (and
coding) behind the BMH project are excellent. We are pleased to take over the
project and continue its development and growth.

What is PHPMailer-BMH (Bounce Mail Handler)?

Whenever you send an email campaign, you must prepare for an avalanche of
undeliverable mail.

All the bounced messages to email addresses that are out of date, never
existed, whose mailbox is full or otherwise inaccessible will be returned
undeliverable (bounced).

Sending your email campaigns to these addresses every time is a costly waste
of time and effort. Manually cleaning your list manually takes a lot of time
and effort.

Now PHPMailer-BMH is available. Using a customizable set of rules it detects
and extracts email addresses with delivery failures. We provide an easy way to
deal with the email addresses of these bounced emails through a callback
function that you can customize to your needs. We have included samples for
the three most common processes: echo to screen, update database (and echo to
screen), and create CSV file (and echo to screen). The entire process of
dealing with your bounced emails can be totally automated and within your own
environment and control.

Using PHPMailer-BMH is fairly simple.

The documentation includes all methods, and all properties. There is an
index.php sample script that is comprehensive. In the '/docs/' folder, you
will also find the PHP Documentor created documentation. Please review the
documentation and sample.

We have also included three sample callback functions: one to echo back to the
screen, one to output the PHPMailer-BMH results to a CSV file, and one to
output the PHPMailer-BMH results to a MySQL database (and all echo back to the
screen).

PHPMailer-BMH (Bounce Mail Handler) can be used on remote mailboxes as well as
local directories to process .eml files. See the index.php file for usage.

Enjoy!

Regards,
Andy Prevost
codeworxtech@users.sourceforge.net
