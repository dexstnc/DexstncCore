ALTER TABLE `roles` ADD `commandSuggets` INT(1) NOT NULL DEFAULT '0' AFTER `commandRate`;

ALTER TABLE `actions` ADD `userID` INT(10) NOT NULL DEFAULT '0' AFTER `actionDate`;
ALTER TABLE `actions` ADD `value4` VARCHAR(255) NULL DEFAULT NULL AFTER `value3`;

DROP TABLE IF EXISTS `suggests`;
CREATE TABLE `suggests` (
  `suggestID` int NOT NULL,
  `levelID` int NOT NULL,
  `difficulty` int NOT NULL DEFAULT '0',
  `demon` int NOT NULL DEFAULT '0',
  `stars` int NOT NULL DEFAULT '0',
  `suggestDate` int NOT NULL,
  `userID` int NOT NULL,
  `IP` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '127.0.0.1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


ALTER TABLE `suggests`
  ADD PRIMARY KEY (`suggestID`);


ALTER TABLE `suggests`
  MODIFY `suggestID` int NOT NULL AUTO_INCREMENT;
COMMIT;