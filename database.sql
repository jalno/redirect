CREATE TABLE `redirect_addresses` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`source` text NOT NULL,
	`type` smallint(6) NOT NULL,
	`destination` text NOT NULL,
	`hits` int(11) NOT NULL,
	`status` tinyint(4) NOT NULL,
	PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;