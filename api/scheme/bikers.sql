SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT = @@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS = @@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION = @@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;


DROP TABLE IF EXISTS `bikes`;
CREATE TABLE IF NOT EXISTS `bikes` (
  `ID`          INT(10) UNSIGNED       NOT NULL AUTO_INCREMENT,
  `name`        TINYTEXT
                COLLATE utf8_polish_ci NOT NULL,
  `description` TEXT
                COLLATE utf8_polish_ci,
  `user_ID`     INT(10) UNSIGNED       NOT NULL,
  `date_create` DATETIME               NOT NULL,
  `date_update` DATETIME               NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `user_ID` (`user_ID`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8
  COLLATE = utf8_polish_ci
  AUTO_INCREMENT = 1;

DROP TABLE IF EXISTS `bikes_parts`;
CREATE TABLE IF NOT EXISTS `bikes_parts` (
  `bike_ID` INT(10) UNSIGNED NOT NULL,
  `part_ID` INT(10) UNSIGNED NOT NULL,
  KEY `bike_ID` (`bike_ID`, `part_ID`),
  KEY `part_ID` (`part_ID`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8
  COLLATE = utf8_polish_ci;

DROP TABLE IF EXISTS `bikes_comments`;
CREATE TABLE IF NOT EXISTS `bikes_comments` (
  `ID`          INT(10) UNSIGNED       NOT NULL AUTO_INCREMENT,
  `bike_ID`     INT(10) UNSIGNED       NOT NULL,
  `user_ID`     INT(10) UNSIGNED,
  `date_create` DATETIME               NOT NULL,
  `value`       TINYTEXT
                COLLATE utf8_polish_ci NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `bike_ID` (`bike_ID`, `user_ID`),
  KEY `user_ID` (`user_ID`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8
  COLLATE = utf8_polish_ci
  AUTO_INCREMENT = 1;

DROP TABLE IF EXISTS `messages`;
CREATE TABLE IF NOT EXISTS `messages` (
  `ID`          INT(10) UNSIGNED       NOT NULL AUTO_INCREMENT,
  `from_user`   INT(11) UNSIGNED       NOT NULL,
  `to_user`     INT(11) UNSIGNED       NOT NULL,
  `value`       TEXT
                COLLATE utf8_polish_ci NOT NULL,
  `date_create` DATETIME               NOT NULL,
  `date_read`   DATETIME,
  PRIMARY KEY (`ID`),
  KEY `from_user` (`from_user`, `to_user`),
  KEY `to_user` (`to_user`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8
  COLLATE = utf8_polish_ci
  AUTO_INCREMENT = 1;

DROP TABLE IF EXISTS `parts`;
CREATE TABLE IF NOT EXISTS `parts` (
  `ID`          INT(10) UNSIGNED       NOT NULL AUTO_INCREMENT,
  `name`        TINYTEXT
                COLLATE utf8_polish_ci NOT NULL,
  `description` TEXT
                COLLATE utf8_polish_ci NOT NULL,
  PRIMARY KEY (`ID`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8
  COLLATE = utf8_polish_ci
  AUTO_INCREMENT = 1;

DROP TABLE IF EXISTS `routes`;
CREATE TABLE IF NOT EXISTS `routes` (
  `ID`          INT(10) UNSIGNED       NOT NULL AUTO_INCREMENT,
  `name`        TINYTEXT
                COLLATE utf8_polish_ci,
  `date_create` DATETIME               NOT NULL,
  `date_update` DATETIME               NOT NULL,
  `from_dst`    TINYTEXT
                COLLATE utf8_polish_ci NOT NULL,
  `to_dst`      TINYTEXT
                COLLATE utf8_polish_ci NOT NULL,
  PRIMARY KEY (`ID`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8
  COLLATE = utf8_polish_ci
  AUTO_INCREMENT = 1;

DROP TABLE IF EXISTS `routes_comments`;
CREATE TABLE IF NOT EXISTS `routes_comments` (
  `ID`          INT(10) UNSIGNED       NOT NULL AUTO_INCREMENT,
  `route_ID`    INT(10) UNSIGNED       NOT NULL,
  `user_ID`     INT(10) UNSIGNED       NOT NULL,
  `date_create` DATETIME               NOT NULL,
  `value`       TEXT
                COLLATE utf8_polish_ci NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `route_ID` (`route_ID`),
  KEY `user_ID` (`user_ID`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8
  COLLATE = utf8_polish_ci
  AUTO_INCREMENT = 1;

DROP TABLE IF EXISTS `routes_landmarks`;
CREATE TABLE IF NOT EXISTS `routes_landmarks` (
  `ID`             INT(10) UNSIGNED       NOT NULL AUTO_INCREMENT,
  `route_ID`       INT(10) UNSIGNED       NOT NULL,
  `value`          TINYTEXT
                   COLLATE utf8_polish_ci NOT NULL,
  `landmark_order` INT(11)                NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `route_ID` (`route_ID`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8
  COLLATE = utf8_polish_ci
  AUTO_INCREMENT = 1;

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `ID`          INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `first_name`  TINYTEXT
                COLLATE utf8_polish_ci,
  `last_name`   TINYTEXT
                COLLATE utf8_polish_ci,
  `date_create` DATETIME         NOT NULL,
  `date_update` DATETIME         NOT NULL,
  PRIMARY KEY (`ID`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8
  COLLATE = utf8_polish_ci
  AUTO_INCREMENT = 1;

DROP TABLE IF EXISTS `users_auth`;
CREATE TABLE IF NOT EXISTS `users_auth` (
  `user_ID`  INT(10) UNSIGNED       NOT NULL,
  `username` TINYTEXT
             COLLATE utf8_polish_ci NOT NULL,
  `password` TINYTEXT
             COLLATE utf8_polish_ci NOT NULL,
  `token`    TINYTEXT
             COLLATE utf8_polish_ci NOT NULL,
  `IP`       INT(10) UNSIGNED       NOT NULL,
  UNIQUE KEY `user_ID` (`user_ID`),
  KEY `user_ID_2` (`user_ID`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8
  COLLATE = utf8_polish_ci;

DROP TABLE IF EXISTS `users_routes`;
CREATE TABLE IF NOT EXISTS `users_routes` (
  `ID`               INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_ID`          INT(10) UNSIGNED NOT NULL,
  `route_ID`         INT(10) UNSIGNED NOT NULL,
  `bike_ID`          INT(10) UNSIGNED NOT NULL,
  `date_of_ride`     DATETIME         NOT NULL,
  `date_create`      DATETIME         NOT NULL,
  `duration_of_ride` INT(11)          NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `user_ID` (`user_ID`, `route_ID`, `bike_ID`),
  KEY `route_ID` (`route_ID`),
  KEY `bike_ID` (`bike_ID`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = utf8
  COLLATE = utf8_polish_ci
  AUTO_INCREMENT = 1;


ALTER TABLE `bikes`
ADD CONSTRAINT `bikes_ibfk_1` FOREIGN KEY (`user_ID`) REFERENCES `users` (`ID`)
  ON DELETE CASCADE;

ALTER TABLE `bikes_parts`
ADD CONSTRAINT `bikes_parts_ibfk_1` FOREIGN KEY (`bike_ID`) REFERENCES `bikes` (`ID`)
  ON DELETE CASCADE,
ADD CONSTRAINT `bikes_parts_ibfk_2` FOREIGN KEY (`part_ID`) REFERENCES `parts` (`ID`)
  ON DELETE CASCADE;

ALTER TABLE `bikes_comments`
ADD CONSTRAINT `bikes_comments_ibfk_1` FOREIGN KEY (`bike_ID`) REFERENCES `bikes` (`ID`)
  ON DELETE CASCADE
  ON UPDATE NO ACTION,
ADD CONSTRAINT `bikes_comments_ibfk_2` FOREIGN KEY (`user_ID`) REFERENCES `users` (`ID`)
  ON DELETE SET NULL
  ON UPDATE NO ACTION;

ALTER TABLE `messages`
ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`from_user`) REFERENCES `users` (`ID`)
  ON DELETE CASCADE
  ON UPDATE NO ACTION,
ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`to_user`) REFERENCES `users` (`ID`)
  ON DELETE CASCADE
  ON UPDATE NO ACTION;

ALTER TABLE `routes_landmarks`
ADD CONSTRAINT `routes_landmarks_ibfk_1` FOREIGN KEY (`route_ID`) REFERENCES `routes` (`ID`)
  ON DELETE CASCADE
  ON UPDATE NO ACTION;

ALTER TABLE `users_auth`
ADD CONSTRAINT `users_auth_ibfk_1` FOREIGN KEY (`user_ID`) REFERENCES `users` (`ID`)
  ON DELETE CASCADE;

ALTER TABLE `users_routes`
ADD CONSTRAINT `users_routes_ibfk_3` FOREIGN KEY (`user_ID`) REFERENCES `users` (`ID`)
  ON DELETE CASCADE
  ON UPDATE NO ACTION,
ADD CONSTRAINT `users_routes_ibfk_1` FOREIGN KEY (`route_ID`) REFERENCES `routes` (`ID`)
  ON DELETE CASCADE
  ON UPDATE NO ACTION,
ADD CONSTRAINT `users_routes_ibfk_2` FOREIGN KEY (`bike_ID`) REFERENCES `bikes` (`ID`)
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;


DROP FUNCTION IF EXISTS unread_message_count;
DELIMITER //
CREATE FUNCTION unread_message_count(user_ID INT)
  RETURNS INT DETERMINISTIC READS SQL DATA
  BEGIN
    RETURN (SELECT COUNT(*)
            FROM bikers.messages msg
            WHERE msg.date_read IS NULL AND msg.to_user = user_ID);
  END //
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE `message_read`(IN message_ID INT, IN user_ID INT)
  BEGIN
    UPDATE bikers.messages
    SET date_read = NOW()
    WHERE ID IN (SELECT m.ID
                 FROM bikers.messages m INNER JOIN bikers.messages mm
                     ON m.ID <= mm.ID AND m.from_user = mm.from_user AND mm.to_user = m.to_user
                 WHERE m.date_read IS NULL AND m.to_user = user_ID AND mm.ID = message_ID);
  END$$
DELIMITER ;


/*!40101 SET CHARACTER_SET_CLIENT = @OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS = @OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION = @OLD_COLLATION_CONNECTION */;
