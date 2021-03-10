![](/assets/helpdeskz/images/logo.png)

Version: 2.0.2 from March 09th, 2021<br>
Developed by: Andres Mendoza<br>
[Help Desk Software HelpDeskZ](https://www.helpdeskz.com)

HelpDeskZ is a free PHP based software which allows you to manage your site's support with a web-based support ticket system.

## Requirements

HelpDeskZ requires:

- PHP >= 7.3
- MySQL database
- ext-intl
- ext-fileinfo
- ext-iconv
- ext-imap
- ext-mbstring

## Installation steps

- Connect with FTP to the <em>public folder</em> of your server where the rest of your Web site is
- Create a new folder where you will install HelpDeskZ. Name it anything you like, for example "helpdesk" or "support".<br>
Example: /public_html/support<br>
Corresponding URL: http://www.site.com/support
- Upload all HelpDeskZ files to your server.
- Edit the file /hdz/app/Config/Helpdesk.new.php and complete the required information (Site URL, Database information), rename this file to Helpdesk.php 
- Open **/install** in your browser, for example (modify to your URL):<br />
http://www.site.com/support/install
- The HelpDeskZ setup script will run. Click <strong>INSTALL HELPDESKZ</strong> and follow the instructions through License agreement, Check Setup and configuration.
- Before closing the install script **DELETE the folder "/hdz/install" from your server!**
- Now it's time to setup your help desk! Open the <strong>staff</strong> panel in your browser, for example:<br />
http://www.site.com/support/staff
- Use the login details that you entered in the installation process.
- Go to <strong>Setup -&gt; General</strong> to get to the settings page.
- Take some time and get familiar with all of the available settings. Most of them should be self-explanatory.
- Good luck using HelpDeskZ!

## Email Piping
HelpDeskZ supports email piping, this allows the auto-creation of tickets from incoming emails to a set email address.
- To enable email piping for your help desk follow this [email piping tutorial.](https://docs.helpdeskz.com/en/latest/configuration/email_piping/)

## Email IMAP Fetching
HelpDeskZ supports IMAP fetching, this allows the auto-creation of tickets from incoming emails to a set email address.
- Read more about IMAP configuration in [IMAP Fetching Configuration.](https://docs.helpdeskz.com/en/latest/configuration/email_imap/)