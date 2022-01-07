CREATE TABLE `roleassign` (
  `roleassignID` int NOT NULL,
  `roleID` int NOT NULL,
  `userID` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `roles` (
  `roleID` int NOT NULL,
  `roleName` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `cmdDelete` tinyint(1) NOT NULL DEFAULT '0',
  `cmdRate` tinyint(1) NOT NULL DEFAULT '0',
  `cmdUnrate` tinyint(1) NOT NULL DEFAULT '0',
  `cmdFeatured` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `actions` ADD `itemID` INT(10) NOT NULL DEFAULT '0' AFTER `actionDate`;

ALTER TABLE `actions` ADD `value3` VARCHAR(255) NULL DEFAULT NULL AFTER `value2`, ADD `value4` VARCHAR(255) NULL DEFAULT NULL AFTER `value3`;