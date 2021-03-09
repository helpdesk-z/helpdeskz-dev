Upgrading from 2.0.1 to 2.0.2
===========================

Changelog
---------

- Framework update to work with PHP >= 7.3.
- The auto-close of tickets is fixed.
- The staff notification when new ticket is opened is fixed.
- Ticket notes added.

Upgrade process
---------------

If you made custom modifications in your template for client panel, then make a backup of the folder **/hdz/app/Views/client/**
The upgrade process is very simple and just upload these files:

- /index.php
- /.htaccess *[required if you have issues visiting pages in your site]*
- /hdz/app/\*.\*
- /hdz/framework/\*.\*
- /hdz/install/\*.\*


Update your site
----------------

- Open **/install** in your browser, for example http://support.mysite.com/install (modify your URL).
- The HelpDeskZ setup script will run. Click `Upgrade my HelpDeskZ´ and follow the instructions through upgrade wizard.
- Restore the template files of client panel.
- Thanks for using HelpDeskZ!