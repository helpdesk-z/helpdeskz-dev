/* v2.0.1 */
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

/* v2.0.2 */
DROP TABLE IF EXISTS `{{db_prefix}}ticket_notes`;
CREATE TABLE `{{db_prefix}}ticket_notes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `ticket_id` int NOT NULL,
  `staff_id` int NOT NULL,
  `date` int NOT NULL,
  `message` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;