*** XKCD Subscription App ***

--> A simple PHP-based application that allows users to subscribe or unsubscribe via email and receive random XKCD comics in their inbox. Emails are sent locally for testing using MailHog.


*** Features ***

1. Email subscription with verification code.

2. Unsubscribe with email confirmation.

3. Random XKCD comic fetching via XKCD API.

4. Local email testing via MailHog.


*** Requirements ***

1. XAMPP (PHP 8+)

2. MailHog for local email testing


*** Setup Instructions ***

1. Place the project folder in C:/xampp/htdocs/

2. Start Apache using the XAMPP Control Panel.

3. Run MailHog: 1. Open Command Prompt
                2. Navigate to your MailHog directory - cd C:/MailHog
                3. Run: MailHogg

4. Access the application at: http://localhost/xkcd-sarveshp21/src/

5. Open the MailHog inbox at: http://localhost:8025/


*** Usage ***

1. Subscribe:

Enter your email address in the subscription form.

Check MailHog for the verification code.

Enter the code in the verification form to complete the subscription.

2. Send Comics:

Use the “Send Comics to Subscribers” form button to email random XKCD comics to all verified subscribers.

3. Unsubscribe:

Enter your email in the Unsubscribe form.

Check MailHog for the unsubscription verification code.

Enter the code in the confirmation form to unsubscribe.


*** Notes ***

Emails are delivered to MailHog (not actual inboxes) for local testing.

XKCD comic data is fetched from the official XKCD API.


*** Assumptions ***

The app runs on a local XAMPP server with PHP 8+.

MailHog is used for local email testing without external SMTP setup.

No user authentication or database integration is implemented.