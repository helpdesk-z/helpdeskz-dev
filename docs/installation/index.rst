Fresh Installation
======================

.. note::

    Be sure that your server meets the :doc:`HelpDeskZ requirements </intro/requirements>`.

Configuration file
------------------

- Unzip the HelpDeskZ script package.
- Browse to directory **/hdz/app/Config/** and rename the file **Helpdesk.new.php** to **Helpdesk.php**.
- Edit this file and complete the required information (Site URL, database information, etc).

Install it!
-----------

- Connect with FTP to the *public folder* of your server.
- Upload all HelpDeskZ in the directory that you will install it (domain directory or subdomain).
- Open **/install** in your browser, for example http://support.mysite.com/install (modify your URL).
- The HelpDeskZ setup script will run. Click INSTALL HELPDESKZ and follow the instructions through installation wizard.
- Now it's time to setup your help desk! Open the staff panel in your browser, for example: http://support.mysite.com/staff Use the login details that you entered in the installation process.
- Take some time and get familiar with all the available settings. Most should be self-explanatory.
- Thanks for using HelpDeskZ!

.. note::

    If you want to make a new installation again, then be sure you removed the file **/hdz/writable/cache/instal.config** to unlock the installation wizard.