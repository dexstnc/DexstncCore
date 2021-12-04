ALTER TABLE `users` ADD `iconType` INT(5) NOT NULL DEFAULT '0' AFTER `creatorPoints`;
ALTER TABLE `users` ADD `coins` INT(5) NOT NULL DEFAULT '0' AFTER `demons`;

ALTER TABLE `levels` ADD `auto` INT(1) NOT NULL DEFAULT '0' AFTER `demon`;
ALTER TABLE `levels` ADD `featured` INT(1) NOT NULL DEFAULT '0' AFTER `rated`;

ALTER TABLE `roles` CHANGE `commandSuggets` `commandSuggest` INT(1) NOT NULL DEFAULT '0';
ALTER TABLE `roles` ADD `commandFeatured` INT(1) NOT NULL DEFAULT '0' AFTER `commandRate`;

ALTER TABLE `suggests` ADD `auto` INT(1) NOT NULL DEFAULT '0' AFTER `demon`;
ALTER TABLE `suggests` ADD `featured` INT(1) NOT NULL DEFAULT '0' AFTER `stars`;

DROP TABLE IF EXISTS `mappacks`;
CREATE TABLE `mappacks` (
  `mapPackID` int NOT NULL,
  `mapPackName` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `mapPackNameColor` varchar(11) COLLATE utf8_unicode_ci NOT NULL DEFAULT '000,000,000',
  `level1` int NOT NULL DEFAULT '0',
  `level2` int NOT NULL DEFAULT '0',
  `level3` int NOT NULL DEFAULT '0',
  `difficulty` int NOT NULL DEFAULT '0',
  `stars` int NOT NULL DEFAULT '0',
  `coins` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


ALTER TABLE `mappacks`
  ADD PRIMARY KEY (`mapPackID`);


ALTER TABLE `mappacks`
  MODIFY `mapPackID` int NOT NULL AUTO_INCREMENT;
COMMIT;