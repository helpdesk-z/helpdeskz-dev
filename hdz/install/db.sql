DROP TABLE IF EXISTS `{{db_prefix}}api`;
CREATE TABLE `{{db_prefix}}api` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `date` int NOT NULL DEFAULT '0',
  `last_update` int NOT NULL,
  `permissions` text,
  `ip_address` mediumtext,
  `active` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{{db_prefix}}articles`;
CREATE TABLE `{{db_prefix}}articles` (
  `id` int NOT NULL,
  `title` varchar(200) NOT NULL,
  `content` text,
  `category` int DEFAULT '0',
  `staff_id` int NOT NULL DEFAULT '0',
  `date` int NOT NULL,
  `last_update` int NOT NULL DEFAULT '0',
  `views` int NOT NULL DEFAULT '0',
  `public` int NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{{db_prefix}}attachments`;
CREATE TABLE `{{db_prefix}}attachments` (
  `id` int NOT NULL,
  `name` varchar(200) NOT NULL,
  `enc` varchar(200) NOT NULL,
  `filetype` varchar(200) DEFAULT NULL,
  `article_id` int NOT NULL DEFAULT '0',
  `ticket_id` int NOT NULL DEFAULT '0',
  `msg_id` int NOT NULL DEFAULT '0',
  `filesize` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{{db_prefix}}canned_response`;
CREATE TABLE `{{db_prefix}}canned_response` (
  `id` int NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `message` mediumtext,
  `position` int NOT NULL DEFAULT '1',
  `date` int NOT NULL DEFAULT '0',
  `last_update` int NOT NULL DEFAULT '0',
  `staff_id` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{{db_prefix}}config`;
CREATE TABLE `{{db_prefix}}config` (
  `id` int NOT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `site_name` varchar(255) DEFAULT NULL,
  `windows_title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `page_size` int NOT NULL DEFAULT '0',
  `date_format` varchar(100) DEFAULT NULL,
  `timezone` varchar(100) DEFAULT NULL,
  `maintenance` tinyint(1) NOT NULL DEFAULT '0',
  `maintenance_message` text,
  `recaptcha` tinyint(1) NOT NULL DEFAULT '0',
  `recaptcha_sitekey` varchar(255) DEFAULT NULL,
  `recaptcha_privatekey` varchar(255) DEFAULT NULL,
  `login_attempt` int NOT NULL DEFAULT '0',
  `login_attempt_minutes` int NOT NULL DEFAULT '1',
  `reply_order` enum('asc','desc') NOT NULL DEFAULT 'asc',
  `tickets_page` int NOT NULL DEFAULT '1',
  `tickets_replies` int NOT NULL DEFAULT '1',
  `overdue_time` int NOT NULL DEFAULT '48',
  `ticket_autoclose` int NOT NULL DEFAULT '96',
  `ticket_attachment` tinyint(1) NOT NULL DEFAULT '0',
  `ticket_attachment_number` int NOT NULL DEFAULT '1',
  `ticket_file_size` double NOT NULL DEFAULT '2',
  `ticket_file_type` mediumtext,
  `kb_articles` int NOT NULL DEFAULT '4',
  `kb_maxchar` int NOT NULL DEFAULT '200',
  `kb_popular` int NOT NULL DEFAULT '4',
  `kb_latest` int NOT NULL DEFAULT '4'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{{db_prefix}}custom_fields`;
CREATE TABLE `{{db_prefix}}custom_fields` (
  `id` int NOT NULL,
  `type` varchar(100) NOT NULL,
  `title` varchar(255) NOT NULL,
  `value` text CHARACTER SET utf8 COLLATE utf8_general_ci,
  `required` tinyint(1) NOT NULL DEFAULT '0',
  `departments` mediumtext,
  `display` int NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{{db_prefix}}departments`;
CREATE TABLE `{{db_prefix}}departments` (
  `id` int NOT NULL,
  `dep_order` int NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL,
  `private` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{{db_prefix}}emails`;
CREATE TABLE `{{db_prefix}}emails` (
  `id` int NOT NULL,
  `default` tinyint(1) NOT NULL DEFAULT '0',
  `name` varchar(200) DEFAULT NULL,
  `email` varchar(200) NOT NULL,
  `department_id` int NOT NULL DEFAULT '0',
  `created` int NOT NULL DEFAULT '0',
  `last_update` int NOT NULL DEFAULT '0',
  `outgoing_type` enum('php','smtp') NOT NULL,
  `smtp_host` varchar(200) DEFAULT NULL,
  `smtp_port` varchar(10) DEFAULT NULL,
  `smtp_encryption` varchar(10) DEFAULT NULL,
  `smtp_username` varchar(200) DEFAULT NULL,
  `smtp_password` varchar(200) DEFAULT NULL,
  `incoming_type` varchar(10) DEFAULT NULL,
  `imap_host` varchar(200) DEFAULT NULL,
  `imap_port` varchar(10) DEFAULT NULL,
  `imap_username` varchar(200) DEFAULT NULL,
  `imap_password` varchar(200) DEFAULT NULL,
  `imap_minutes` double NOT NULL DEFAULT '5'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{{db_prefix}}emails_tpl`;
CREATE TABLE `{{db_prefix}}emails_tpl` (
  `id` varchar(255) NOT NULL,
  `position` smallint NOT NULL,
  `name` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` mediumtext NOT NULL,
  `last_update` int NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{{db_prefix}}kb_category`;
CREATE TABLE `{{db_prefix}}kb_category` (
  `id` int NOT NULL,
  `name` varchar(200) NOT NULL,
  `position` int NOT NULL,
  `parent` int NOT NULL DEFAULT '0',
  `public` int NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{{db_prefix}}login_attempt`;
CREATE TABLE `{{db_prefix}}login_attempt` (
  `ip` varchar(200) NOT NULL,
  `attempts` int NOT NULL DEFAULT '0',
  `date` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{{db_prefix}}login_log`;
CREATE TABLE `{{db_prefix}}login_log` (
  `id` int NOT NULL,
  `date` int NOT NULL,
  `staff_id` int NOT NULL DEFAULT '0',
  `ip` varchar(255) NOT NULL,
  `agent` varchar(255) NOT NULL,
  `success` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{{db_prefix}}priority`;
CREATE TABLE `{{db_prefix}}priority` (
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `color` varchar(10) NOT NULL DEFAULT '#000000'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{{db_prefix}}staff`;
CREATE TABLE `{{db_prefix}}staff` (
  `id` int NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `token` varchar(255) DEFAULT NULL,
  `registration` int NOT NULL DEFAULT '0',
  `login` int NOT NULL DEFAULT '0',
  `last_login` int NOT NULL DEFAULT '0',
  `department` mediumtext,
  `timezone` varchar(255) DEFAULT NULL,
  `signature` longtext,
  `avatar` varchar(200) DEFAULT NULL,
  `two_factor` varchar(255) DEFAULT NULL,
  `admin` tinyint(1) NOT NULL DEFAULT '0',
  `active` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{{db_prefix}}tickets`;
CREATE TABLE `{{db_prefix}}tickets` (
  `id` int NOT NULL,
  `department_id` int NOT NULL DEFAULT '0',
  `priority_id` int NOT NULL DEFAULT '0',
  `user_id` int NOT NULL DEFAULT '0',
  `subject` varchar(255) NOT NULL,
  `date` int NOT NULL DEFAULT '0',
  `last_update` int NOT NULL DEFAULT '0',
  `status` smallint NOT NULL DEFAULT '1',
  `replies` int NOT NULL DEFAULT '0',
  `last_replier` tinyint(1) DEFAULT '0',
  `custom_vars` mediumtext
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{{db_prefix}}tickets_messages`;
CREATE TABLE `{{db_prefix}}tickets_messages` (
  `id` int NOT NULL,
  `ticket_id` int NOT NULL DEFAULT '0',
  `date` int NOT NULL DEFAULT '0',
  `customer` int NOT NULL DEFAULT '1',
  `staff_id` int NOT NULL DEFAULT '0',
  `message` text,
  `ip` varchar(255) DEFAULT NULL,
  `email` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{{db_prefix}}ticket_notes`;
CREATE TABLE `{{db_prefix}}ticket_notes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `ticket_id` int NOT NULL,
  `staff_id` int NOT NULL,
  `date` int NOT NULL,
  `message` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{{db_prefix}}users`;
CREATE TABLE `{{db_prefix}}users` (
  `id` int NOT NULL,
  `fullname` varchar(250) NOT NULL DEFAULT 'Guest',
  `email` varchar(250) NOT NULL,
  `password` varchar(150) NOT NULL,
  `registration` int NOT NULL DEFAULT '0',
  `last_login` int NOT NULL DEFAULT '0',
  `token` varchar(255) DEFAULT NULL,
  `timezone` varchar(200) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `status` int NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `{{db_prefix}}api`
  ADD PRIMARY KEY (`id`),
  ADD KEY `token` (`token`);

ALTER TABLE `{{db_prefix}}articles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category` (`category`);

ALTER TABLE `{{db_prefix}}attachments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `article_id` (`article_id`),
  ADD KEY `ticket_id` (`ticket_id`),
  ADD KEY `msg_id` (`msg_id`);

ALTER TABLE `{{db_prefix}}canned_response`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `{{db_prefix}}config`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `{{db_prefix}}custom_fields`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `{{db_prefix}}departments`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `{{db_prefix}}emails`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `{{db_prefix}}emails_tpl`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `{{db_prefix}}kb_category`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `{{db_prefix}}login_attempt`
  ADD UNIQUE KEY `ip` (`ip`);

ALTER TABLE `{{db_prefix}}login_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `staff_id` (`staff_id`);

ALTER TABLE `{{db_prefix}}priority`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `{{db_prefix}}staff`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `{{db_prefix}}tickets`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `{{db_prefix}}tickets_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ticket_id` (`ticket_id`);

ALTER TABLE `{{db_prefix}}users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `email` (`email`);

ALTER TABLE `{{db_prefix}}api`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

ALTER TABLE `{{db_prefix}}articles`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

ALTER TABLE `{{db_prefix}}attachments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

ALTER TABLE `{{db_prefix}}canned_response`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

ALTER TABLE `{{db_prefix}}config`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

ALTER TABLE `{{db_prefix}}custom_fields`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

ALTER TABLE `{{db_prefix}}departments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

ALTER TABLE `{{db_prefix}}emails`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

ALTER TABLE `{{db_prefix}}kb_category`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

ALTER TABLE `{{db_prefix}}login_log`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

ALTER TABLE `{{db_prefix}}priority`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

ALTER TABLE `{{db_prefix}}staff`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

ALTER TABLE `{{db_prefix}}tickets`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

ALTER TABLE `{{db_prefix}}tickets_messages`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

ALTER TABLE `{{db_prefix}}users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;


INSERT INTO `{{db_prefix}}config` (`id`, `logo`, `site_name`, `windows_title`, `page_size`, `date_format`, `timezone`, `recaptcha`, `recaptcha_sitekey`, `recaptcha_privatekey`, `login_attempt`, `login_attempt_minutes`, `reply_order`, `tickets_page`, `tickets_replies`, `overdue_time`, `ticket_autoclose`, `ticket_attachment`, `ticket_attachment_number`, `ticket_file_size`, `ticket_file_type`, `kb_articles`, `kb_maxchar`, `kb_popular`, `kb_latest`) VALUES
(1, '', 'HelpDeskZ', 'HelpDeskZ Demo', 25, 'd F Y h:i a', 'America/Lima', 0, '', '', 3, 5, 'desc', 15, 15, 48, 96, 1, 3, 2.5, 'a:3:{i:0;s:3:\"jpg\";i:1;s:3:\"png\";i:2;s:3:\"gif\";}', 2, 200, 3, 3);
INSERT INTO `{{db_prefix}}departments` (`id`, `dep_order`, `name`, `private`) VALUES
(1, 1, 'General', 0),
(2, 2, 'Advertising', 0),
(3, 3, 'Sales', 0);
INSERT INTO `{{db_prefix}}emails_tpl` (`id`, `position`, `name`, `subject`, `message`, `last_update`, `status`) VALUES
('autoresponse', 4, 'New Message Autoresponse', '[#%ticket_id%] %ticket_subject%', '<p>Dear %client_name%,</p>\r\n<p>Your reply to support request #%ticket_id% has been noted.</p>\r\n<p>Ticket Details <br />--------------------<br />Ticket ID: %ticket_id% <br />Department: %ticket_department% <br />Status: %ticket_status% <br />Priority: %ticket_priority% <br />Helpdesk: %support_url%</p>', 0, 0),
('lost_password', 2, 'Lost password confirmation', 'Password recovery for %company_name%', '<p>We have received a request to reset your account password for the %company_name% helpdesk (%helpdesk_url%).</p>\r\n<p>Your new passsword is: %client_password%</p>\r\n<p>Thank you, <br />%company_name% <br />Helpdesk: %support_url%</p>', 0, 2),
('new_ticket', 3, 'New ticket creation', '[#%ticket_id%] %ticket_subject%', '<p>Dear %client_name%,</p>\r\n<p>Thank you for contacting us. This is an automated response confirming the receipt of your ticket. One of our agents will get back to you as soon as possible.</p>\r\n<p>For your records, the details of the ticket are listed below. When replying, please make sure that the ticket ID is kept in the subject line to ensure that your replies are tracked appropriately.</p>\r\n<p>Ticket ID: %ticket_id% <br />Subject: %ticket_subject% <br />Department: %ticket_department% <br />Status: %ticket_status% <br />Priority: %ticket_priority%</p>\r\n<p>You can check the status of or reply to this ticket online at: %support_url%</p>\r\n<p>Regards, <br />%company_name%</p>', 0, 1),
('new_user', 1, 'Welcome email registration', 'Welcome to %company_name% helpdesk', '<p>Hello,</p>\r\n<p>This email is confirmation that you are now registered at our helpdesk.</p>\r\n<p><strong>Registered email:</strong> %client_email% <br /><strong>Password:</strong> %client_password%</p>\r\n<p>You can visit the helpdesk to browse articles and contact us at any time:</p>\r\n<p>%support_url%</p>\r\n<p>Thank you for registering!</p>\r\n<p>%company_name%<br />Helpdesk: %support_url%</p>', 0, 1),
('staff_reply', 5, 'Staff Reply', 'Re: [#%ticket_id%] %ticket_subject%', '<p>%message% </p>\r\n<p>-------------------------------------------------------------<br />Ticket Details<br />-------------------------------------------------------------<br /><strong>Ticket ID:</strong> %ticket_id% <br /><strong>Department:</strong> %ticket_department% <br /><strong>Status:</strong> %ticket_status% <br /><strong>Priority:</strong> %ticket_priority% <br /><strong>Helpdesk:</strong> %support_url%</p>', 0, 2),
('staff_ticketnotification', 6, 'New ticket notification to staff', 'New ticket notification', '<p>Dear %staff_name%,</p>\r\n<p>A new ticket has been created in department assigned for you, please login to staff panel to answer it.</p>\r\n<p>Ticket Details<br />-------------------<br />Ticket ID: %ticket_id% <br />Department: %ticket_department% <br />Status: %ticket_status% <br />Priority: %ticket_priority% <br />Helpdesk: %support_url%</p>', 0, 0);
INSERT INTO `{{db_prefix}}priority` (`id`, `name`, `color`) VALUES
(1, 'Low', '#8A8A8A'),
(2, 'Medium', '#000000'),
(3, 'High', '#F07D18'),
(4, 'Urgent', '#E826C6'),
(5, 'Emergency', '#E06161'),
(6, 'Critical', '#FF0000');