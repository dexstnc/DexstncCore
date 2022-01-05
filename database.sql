SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;


DROP TABLE IF EXISTS `actions`;
CREATE TABLE `actions` (
  `actionID` int NOT NULL,
  `type` int NOT NULL,
  `value1` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `value2` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `IP` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '127.0.0.1',
  `actionDate` int NOT NULL,
  `userID` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `bans`;
CREATE TABLE `bans` (
  `banID` int NOT NULL,
  `IP` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `reason` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `comments`;
CREATE TABLE `comments` (
  `commentID` int NOT NULL,
  `levelID` int NOT NULL,
  `comment` text COLLATE utf8_unicode_ci NOT NULL,
  `likes` int NOT NULL DEFAULT '0',
  `uploadDate` int NOT NULL,
  `userID` int NOT NULL,
  `IP` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '127.0.0.1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `levels`;
CREATE TABLE `levels` (
  `levelID` int NOT NULL,
  `levelName` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `levelDesc` text COLLATE utf8_unicode_ci NOT NULL,
  `downloads` int NOT NULL DEFAULT '0',
  `likes` int NOT NULL DEFAULT '0',
  `difficulty` int NOT NULL DEFAULT '0',
  `stars` int NOT NULL DEFAULT '0',
  `featured` int NOT NULL DEFAULT '0',
  `levelVersion` int NOT NULL,
  `levelLength` int NOT NULL,
  `audioTrack` int NOT NULL DEFAULT '0',
  `gameVersion` int NOT NULL,
  `uploadDate` int NOT NULL,
  `updateDate` int NOT NULL DEFAULT '0',
  `rateDate` int NOT NULL DEFAULT '0',
  `userID` int NOT NULL,
  `IP` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '127.0.0.1',
  `deleted` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `userID` int NOT NULL,
  `userName` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `udid` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `lastActive` int NOT NULL,
  `IP` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '127.0.0.1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


ALTER TABLE `actions`
  ADD PRIMARY KEY (`actionID`);

ALTER TABLE `bans`
  ADD PRIMARY KEY (`banID`);

ALTER TABLE `comments`
  ADD PRIMARY KEY (`commentID`);

ALTER TABLE `levels`
  ADD PRIMARY KEY (`levelID`);

ALTER TABLE `users`
  ADD PRIMARY KEY (`userID`);


ALTER TABLE `actions`
  MODIFY `actionID` int NOT NULL AUTO_INCREMENT;

ALTER TABLE `bans`
  MODIFY `banID` int NOT NULL AUTO_INCREMENT;

ALTER TABLE `comments`
  MODIFY `commentID` int NOT NULL AUTO_INCREMENT;

ALTER TABLE `levels`
  MODIFY `levelID` int NOT NULL AUTO_INCREMENT;

ALTER TABLE `users`
  MODIFY `userID` int NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
