CREATE TABLE `comments` (
  `commentID` int NOT NULL,
  `levelID` int NOT NULL,
  `comment` text COLLATE utf8_unicode_ci NOT NULL,
  `likes` int NOT NULL DEFAULT '0',
  `uploadDate` int NOT NULL,
  `userID` int NOT NULL,
  `IP` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '127.0.0.1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;