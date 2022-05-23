Email Piping
==============

HelpDeskZ supports email piping, this allows the auto-creation of tickets from incoming emails to a set email address.

Email configuration
--------------------

- In Staff Panel, go to Setup -> Email addresses.
- Add a new email address or edit the email for Piping configuraiton.
- Go to **Incoming** tab and select **Pipe**

Email forwarding
-----------------

- In your hosting panel, go to email forwarding.
- Enter the email address that you configured in your staff panel.
- For destination, select Pipe
- Enter the path to pipe.php, for example /public_html/helpdeskz/pipe.php

.. note::

    To make it work correctly, verify that pipe.php has executable permissions (CHMOD 755)