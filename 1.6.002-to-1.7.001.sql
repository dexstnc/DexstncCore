ALTER TABLE `levels` ADD `password` VARCHAR(10) NOT NULL DEFAULT '0' AFTER `levelLength`;
ALTER TABLE `levels` ADD `original` INT(10) NOT NULL DEFAULT '0' AFTER `password`;