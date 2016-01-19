SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;


DROP TABLE IF EXISTS `bikes`;
CREATE TABLE IF NOT EXISTS `bikes` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` tinytext COLLATE utf8_polish_ci NOT NULL,
  `description` text COLLATE utf8_polish_ci,
  `user_ID` int(10) unsigned NOT NULL,
  `date_create` datetime NOT NULL,
  `date_update` datetime NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `user_ID` (`user_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `bikes_parts`;
CREATE TABLE IF NOT EXISTS `bikes_parts` (
  `bike_ID` int(10) unsigned NOT NULL,
  `part_ID` int(10) unsigned NOT NULL,
  KEY `bike_ID` (`bike_ID`,`part_ID`),
  KEY `part_ID` (`part_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

DROP TABLE IF EXISTS `bikes_comments`;
CREATE TABLE IF NOT EXISTS `bikes_comments` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `bike_ID` int(10) unsigned NOT NULL,
  `user_ID` int(10) unsigned,
  `date_create` datetime NOT NULL,
  `value` tinytext COLLATE utf8_polish_ci NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `bike_ID` (`bike_ID`,`user_ID`),
  KEY `user_ID` (`user_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `messages`;
CREATE TABLE IF NOT EXISTS `messages` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `from_user` int(11) unsigned NOT NULL,
  `to_user` int(11) unsigned NOT NULL,
  `value` text COLLATE utf8_polish_ci NOT NULL,
  `date_create` datetime NOT NULL,
  `date_update` datetime,
  PRIMARY KEY (`ID`),
  KEY `from_user` (`from_user`,`to_user`),
  KEY `to_user` (`to_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `parts`;
CREATE TABLE IF NOT EXISTS `parts` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` tinytext COLLATE utf8_polish_ci NOT NULL,
  `description` text COLLATE utf8_polish_ci NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `routes`;
CREATE TABLE IF NOT EXISTS `routes` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` tinytext COLLATE utf8_polish_ci,
  `date_create` datetime NOT NULL,
  `date_update` datetime NOT NULL,
  `from_dst` tinytext COLLATE utf8_polish_ci NOT NULL,
  `to_dst` tinytext COLLATE utf8_polish_ci NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `routes_comments`;
CREATE TABLE IF NOT EXISTS `routes_comments` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `route_ID` int(10) unsigned NOT NULL,
  `user_ID` int(10) unsigned NOT NULL,
  `date_create` datetime NOT NULL,
  `value` text COLLATE utf8_polish_ci NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `route_ID` (`route_ID`),
  KEY `user_ID` (`user_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `routes_landmarks`;
CREATE TABLE IF NOT EXISTS `routes_landmarks` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `route_ID` int(10) unsigned NOT NULL,
  `value` tinytext COLLATE utf8_polish_ci NOT NULL,
  `landmark_order` int(11) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `route_ID` (`route_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `first_name` tinytext CHARACTER SET latin1,
  `last_name` tinytext CHARACTER SET latin1,
  `date_create` datetime NOT NULL,
  `date_update` datetime NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `users_auth`;
CREATE TABLE IF NOT EXISTS `users_auth` (
  `user_ID` int(10) unsigned NOT NULL,
  `username` tinytext COLLATE utf8_polish_ci NOT NULL,
  `password` tinytext COLLATE utf8_polish_ci NOT NULL,
  `token` tinytext COLLATE utf8_polish_ci NOT NULL,
  `IP` int(10) unsigned NOT NULL,
  UNIQUE KEY `user_ID` (`user_ID`),
  KEY `user_ID_2` (`user_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

DROP TABLE IF EXISTS `users_routes`;
CREATE TABLE IF NOT EXISTS `users_routes` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_ID` int(10) unsigned NOT NULL,
  `route_ID` int(10) unsigned NOT NULL,
  `bike_ID` int(10) unsigned NOT NULL,
  `date_of_ride` datetime NOT NULL,
  `date_create` datetime NOT NULL,
  `duration_of_ride` int(11) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `user_ID` (`user_ID`,`route_ID`,`bike_ID`),
  KEY `route_ID` (`route_ID`),
  KEY `bike_ID` (`bike_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci AUTO_INCREMENT=1 ;


ALTER TABLE `bikes`
  ADD CONSTRAINT `bikes_ibfk_1` FOREIGN KEY (`user_ID`) REFERENCES `users` (`ID`) ON DELETE CASCADE;

ALTER TABLE `bikes_parts`
  ADD CONSTRAINT `bikes_parts_ibfk_1` FOREIGN KEY (`bike_ID`) REFERENCES `bikes` (`ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `bikes_parts_ibfk_2` FOREIGN KEY (`part_ID`) REFERENCES `parts` (`ID`) ON DELETE CASCADE;

ALTER TABLE `bikes_comments`
  ADD CONSTRAINT `bikes_comments_ibfk_1` FOREIGN KEY (`bike_ID`) REFERENCES `bikes` (`ID`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `bikes_comments_ibfk_2` FOREIGN KEY (`user_ID`) REFERENCES `users` (`ID`) ON DELETE SET NULL ON UPDATE NO ACTION;

ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`from_user`) REFERENCES `users` (`ID`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`to_user`) REFERENCES `users` (`ID`) ON DELETE CASCADE ON UPDATE NO ACTION;

ALTER TABLE `routes_landmarks`
  ADD CONSTRAINT `routes_landmarks_ibfk_1` FOREIGN KEY (`route_ID`) REFERENCES `routes` (`ID`) ON DELETE CASCADE ON UPDATE NO ACTION;

ALTER TABLE `users_auth`
  ADD CONSTRAINT `users_auth_ibfk_1` FOREIGN KEY (`user_ID`) REFERENCES `users` (`ID`) ON DELETE CASCADE;

ALTER TABLE `users_routes`
  ADD CONSTRAINT `users_routes_ibfk_3` FOREIGN KEY (`user_ID`) REFERENCES `users` (`ID`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `users_routes_ibfk_1` FOREIGN KEY (`route_ID`) REFERENCES `routes` (`ID`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `users_routes_ibfk_2` FOREIGN KEY (`bike_ID`) REFERENCES `bikes` (`ID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

DROP FUNCTION IF EXISTS unread_message_count;
CREATE FUNCTION unread_message_count(user_ID INT) RETURNS INT
  BEGIN
    SELECT COUNT * FROM
  END;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
