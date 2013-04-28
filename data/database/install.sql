CREATE TABLE `alliances` (
	`alliance_id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
	`modified` TIMESTAMP NOT NULL DEFAULT NOW() ON UPDATE NOW(),
	`created` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
	`gameround_id` BIGINT(20) UNSIGNED NOT NULL,
	`tag` VARCHAR(255) NOT NULL,
	`name` VARCHAR(255) NOT NULL,
	`description` TEXT NULL,
	PRIMARY KEY (`alliance_id`),
	UNIQUE KEY `tag` (`gameround_id`, `tag`),
	UNIQUE KEY `name` (`gameround_id`, `name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `allianceavatars` (
	`allianceavatar_id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
	`modified` TIMESTAMP NOT NULL DEFAULT NOW() ON UPDATE NOW(),
	`created` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
	`avatar_id` BIGINT(20) UNSIGNED NOT NULL,
	`alliance_id` BIGINT(20) UNSIGNED NOT NULL,
	`role` ENUM('applicant', 'member', 'leader') NOT NULL,
	PRIMARY KEY (`allianceavatar_id`),
	UNIQUE KEY `avatar_id` (`avatar_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
