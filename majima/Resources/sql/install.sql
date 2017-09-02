CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `salt` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE = InnoDB;

TRUNCATE TABLE `users`;

CREATE TABLE `plugins` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `version` VARCHAR(255) NOT NULL,
  `active` BOOLEAN NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE = InnoDB;