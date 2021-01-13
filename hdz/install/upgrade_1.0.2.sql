ALTER TABLE `{{db_prefix}}articles` ADD `staff_id` INT(11) NOT NULL DEFAULT '0';

ALTER TABLE `{{db_prefix}}canned_response` ADD `date` INT(11) NOT NULL DEFAULT '0' AFTER `position`, ADD `last_update` INT(11) NOT NULL DEFAULT '0' AFTER `date`, ADD `staff_id` INT(11) NOT NULL DEFAULT '0' AFTER `last_update`;

CREATE TABLE `{{db_prefix}}config` (
  `id` int NOT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `site_name` varchar(255) DEFAULT NULL,
  `windows_title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `page_size` int NOT NULL DEFAULT '0',
  `date_format` varchar(100) DEFAULT NULL,
  `timezone` varchar(100) DEFAULT NULL,
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

ALTER TABLE `{{db_prefix}}departments` CHANGE `type` `private` INT NOT NULL DEFAULT '0';

RENAME TABLE `{{db_prefix}}emails` TO `{{db_prefix}}emails_tpl`;
ALTER TABLE `{{db_prefix}}emails_tpl` CHANGE `orderlist` `position` SMALLINT NOT NULL;
ALTER TABLE `{{db_prefix}}emails_tpl` ADD `last_update` INT(11) NOT NULL DEFAULT '0' AFTER `message`, ADD `status` TINYINT(1) NOT NULL DEFAULT '1' AFTER `last_update`;

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

RENAME TABLE `{{db_prefix}}knowledgebase_category` TO `{{db_prefix}}kb_category`;

ALTER TABLE `{{db_prefix}}login_log` ADD `success` TINYINT(1) NOT NULL DEFAULT '0' AFTER `agent`;

ALTER TABLE `{{db_prefix}}staff` ADD `token` VARCHAR(255) NULL DEFAULT NULL AFTER `email`, ADD `registration` INT(11) NOT NULL DEFAULT '0' AFTER `token`;

ALTER TABLE `{{db_prefix}}staff` ADD `active` TINYINT(1) NOT NULL DEFAULT '1' AFTER `status`;

ALTER TABLE `{{db_prefix}}tickets_messages` ADD `staff_id` INT(11) NOT NULL DEFAULT '0' AFTER `customer`;

ALTER TABLE `{{db_prefix}}users` ADD `last_login` INT(11) NOT NULL DEFAULT '0' AFTER `password`, ADD `token` VARCHAR(255) NULL DEFAULT NULL AFTER `last_login`;