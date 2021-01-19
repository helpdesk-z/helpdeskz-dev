IMAP Fetching
==============

HelpDeskZ supports IMAP fetching, this allows the auto-creation of tickets from incoming emails to a set email address.

Email configuration
--------------------

- In Staff Panel, go to Setup -> Email addresses.
- Add a new email address or edit the email for IMAP configuration.
- Go to **Incoming** tab and select **IMAP**
- Complete your configuration with the Host, Port, Username and Password

Cron configuration
-----------------

- Add a cron job to execute your command every minute or 5 minutes. It depends the timeframe you want to check emails.
- Command: **/usr/local/bin/php /PATH_TO_HELPDESK/index.php imap_fetcher**

.. note::

    Many hosts uses the IMAP port 993 by default