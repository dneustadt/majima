DROP TABLE IF EXISTS `users`;

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `salt` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE = InnoDB AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS `users_roles` (
  `userID` int(11) NOT NULL,
  `role` varchar(255) NOT NULL,
  UNIQUE KEY `user_role` (`userID`,`role`)
) ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `plugins` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `version` VARCHAR(255) NOT NULL,
  `active` BOOLEAN NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE = InnoDB AUTO_INCREMENT=1;