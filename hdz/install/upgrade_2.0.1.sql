DROP TABLE IF EXISTS `{{db_prefix}}ticket_notes`;
CREATE TABLE `{{db_prefix}}ticket_notes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `ticket_id` int NOT NULL,
  `staff_id` int NOT NULL,
  `date` int NOT NULL,
  `message` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;