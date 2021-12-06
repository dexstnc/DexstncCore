ALTER TABLE `levels` ADD `password` VARCHAR(10) NOT NULL DEFAULT '0' AFTER `levelLength`;
ALTER TABLE `levels` ADD `original` INT(10) NOT NULL DEFAULT '0' AFTER `password`;

ALTER TABLE `users` ADD `special` INT(1) NOT NULL DEFAULT '0' AFTER `color2`;
ALTER TABLE `users` ADD `gameVersion` INT(5) NOT NULL DEFAULT '0' AFTER `IP`;