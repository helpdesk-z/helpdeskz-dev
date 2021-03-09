ALTER TABLE `{{db_prefix}}articles` ADD `staff_id` INT(11) NOT NULL DEFAULT '0' AFTER `category`;
ALTER TABLE `{{db_prefix}}articles` ADD `last_update` INT(11) NOT NULL DEFAULT '0' AFTER `date`;

ALTER TABLE `{{db_prefix}}canned_response` ADD `date` INT(11) NOT NULL DEFAULT '0' AFTER `position`, ADD `last_update` INT(11) NOT NULL DEFAULT '0' AFTER `date`, ADD `staff_id` INT(11) NOT NULL DEFAULT '0' AFTER `last_update`;

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
ALTER TABLE `{{db_prefix}}config`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `{{db_prefix}}config`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;
INSERT INTO `{{db_prefix}}config` (`id`, `logo`, `site_name`, `windows_title`, `page_size`, `date_format`, `timezone`, `recaptcha`, `recaptcha_sitekey`, `recaptcha_privatekey`, `login_attempt`, `login_attempt_minutes`, `reply_order`, `tickets_page`, `tickets_replies`, `overdue_time`, `ticket_autoclose`, `ticket_attachment`, `ticket_attachment_number`, `ticket_file_size`, `ticket_file_type`, `kb_articles`, `kb_maxchar`, `kb_popular`, `kb_latest`) VALUES
(1, '', 'HelpDeskZ', 'HelpDeskZ Demo', 25, 'd F Y h:i a', 'America/Lima', 0, '', '', 3, 5, 'desc', 15, 15, 48, 96, 1, 3, 2.5, 'a:3:{i:0;s:3:\"jpg\";i:1;s:3:\"png\";i:2;s:3:\"gif\";}', 2, 200, 3, 3);

ALTER TABLE `{{db_prefix}}custom_fields` ADD `departments` MEDIUMTEXT NULL DEFAULT NULL AFTER `required`;

ALTER TABLE `{{db_prefix}}departments` CHANGE `type` `private` INT NOT NULL DEFAULT '0';

RENAME TABLE `{{db_prefix}}emails` TO `{{db_prefix}}emails_tpl`;

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
ALTER TABLE `{{db_prefix}}emails`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `{{db_prefix}}emails`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

ALTER TABLE `{{db_prefix}}emails_tpl` CHANGE `orderlist` `position` SMALLINT NOT NULL;
ALTER TABLE `{{db_prefix}}emails_tpl` ADD `last_update` INT(11) NOT NULL DEFAULT '0' AFTER `message`, ADD `status` TINYINT(1) NOT NULL DEFAULT '1' AFTER `last_update`;

RENAME TABLE `{{db_prefix}}knowledgebase_category` TO `{{db_prefix}}kb_category`;

ALTER TABLE `{{db_prefix}}login_log` ADD `success` TINYINT(1) NOT NULL DEFAULT '0' AFTER `agent`;
TRUNCATE TABLE `{{db_prefix}}login_log`;

ALTER TABLE `{{db_prefix}}staff` ADD `token` VARCHAR(255) NULL DEFAULT NULL AFTER `email`;
ALTER TABLE `{{db_prefix}}staff` ADD `registration` INT(11) NOT NULL DEFAULT '0' AFTER `token`;
ALTER TABLE `{{db_prefix}}staff` ADD `active` TINYINT(1) NOT NULL DEFAULT '1' AFTER `status`;

ALTER TABLE `{{db_prefix}}tickets_messages` ADD `staff_id` INT(11) NOT NULL DEFAULT '0' AFTER `customer`;

ALTER TABLE `{{db_prefix}}users` ADD `registration` INT(11) NOT NULL DEFAULT '0' AFTER `password`, ADD `last_login` INT(11) NOT NULL DEFAULT '0' AFTER `registration`, ADD `token` VARCHAR(255) NULL DEFAULT NULL AFTER `last_login`, ADD `avatar` VARCHAR(255) NULL DEFAULT NULL AFTER `token`;

DROP TABLE IF EXISTS `{{db_prefix}}api`;
CREATE TABLE `{{db_prefix}}api` (
  `id` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `token` varchar(255) NOT NULL,
  `date` int NOT NULL DEFAULT '0',
  `last_update` int NOT NULL,
  `permissions` text,
  `ip_address` mediumtext,
  `active` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE `{{db_prefix}}api`
  ADD PRIMARY KEY (`id`),
  ADD KEY `token` (`token`);


ALTER TABLE `{{db_prefix}}api`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

ALTER TABLE `{{db_prefix}}staff` ADD `two_factor` VARCHAR(255) NULL DEFAULT NULL AFTER `avatar`;

DROP TABLE IF EXISTS `{{db_prefix}}ticket_notes`;
CREATE TABLE `{{db_prefix}}ticket_notes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `ticket_id` int NOT NULL,
  `staff_id` int NOT NULL,
  `date` int NOT NULL,
  `message` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;