SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

CREATE SCHEMA IF NOT EXISTS `lodge` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
USE `lodge` ;

-- -----------------------------------------------------
-- Table `lodge`.`L_USERS`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `lodge`.`L_USERS` ;

CREATE  TABLE IF NOT EXISTS `lodge`.`L_USERS` (
  `id_user` INT(10) NOT NULL AUTO_INCREMENT ,
  `username_user` VARCHAR(50) NOT NULL ,
  `password_user` VARCHAR(255) NOT NULL ,
  `firstname_user` VARCHAR(100) NOT NULL ,
  `lastname_user` VARCHAR(255) NOT NULL ,
  `email_user` VARCHAR(250) NOT NULL ,
  PRIMARY KEY (`id_user`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `lodge`.`L_USER_DATAS`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `lodge`.`L_USER_DATAS` ;

CREATE  TABLE IF NOT EXISTS `lodge`.`L_USER_DATAS` (
  `id_user_data` INT(10) NOT NULL AUTO_INCREMENT ,
  `id_user` INT(10) NOT NULL ,
  `cd_user_data` VARCHAR(20) NOT NULL ,
  `ds_user_data` VARCHAR(255) NOT NULL ,
  PRIMARY KEY (`id_user_data`) ,
  INDEX `fk_L_USERS` (`id_user` ASC) ,
  CONSTRAINT `fk_L_USERS`
    FOREIGN KEY (`id_user` )
    REFERENCES `lodge`.`L_USERS` (`id_user` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `lodge`.`L_GROUPS`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `lodge`.`L_GROUPS` ;

CREATE  TABLE IF NOT EXISTS `lodge`.`L_GROUPS` (
  `id_group` INT(10) NOT NULL AUTO_INCREMENT ,
  PRIMARY KEY (`id_group`) )
ENGINE = InnoDB
COMMENT = 'Groups table contains all the groups';


-- -----------------------------------------------------
-- Table `lodge`.`L_PROFILES`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `lodge`.`L_PROFILES` ;

CREATE  TABLE IF NOT EXISTS `lodge`.`L_PROFILES` (
  `id_user` INT(10) NOT NULL ,
  `id_group` INT(10) NOT NULL ,
  `id_profile` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`id_user`, `id_group`) ,
  INDEX `fk_L_USERS_has_L_GROUPS_L_GROUPS1` (`id_group` ASC) ,
  INDEX `fk_L_USERS_has_L_GROUPS_L_USERS1` (`id_user` ASC) ,
  UNIQUE INDEX `id_profile_UNIQUE` (`id_profile` ASC) ,
  CONSTRAINT `fk_L_USERS_has_L_GROUPS_L_USERS1`
    FOREIGN KEY (`id_user` )
    REFERENCES `lodge`.`L_USERS` (`id_user` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_L_USERS_has_L_GROUPS_L_GROUPS1`
    FOREIGN KEY (`id_group` )
    REFERENCES `lodge`.`L_GROUPS` (`id_group` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `lodge`.`L_EVENTS`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `lodge`.`L_EVENTS` ;

CREATE  TABLE IF NOT EXISTS `lodge`.`L_EVENTS` (
  `id_event` INT(10) NOT NULL AUTO_INCREMENT ,
  `dt_start_event` DATE NOT NULL ,
  `dt_end_event` DATE NOT NULL ,
  `ds_event` VARCHAR(255) NOT NULL ,
  PRIMARY KEY (`id_event`) )
ENGINE = InnoDB
COMMENT = 'Events table contains all the events created by groups\n';


-- -----------------------------------------------------
-- Table `lodge`.`L_LOCATIONS`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `lodge`.`L_LOCATIONS` ;

CREATE  TABLE IF NOT EXISTS `lodge`.`L_LOCATIONS` (
  `id_location` INT(10) NOT NULL AUTO_INCREMENT ,
  `cd_location` VARCHAR(255) NOT NULL ,
  `ds_location` TEXT NOT NULL ,
  PRIMARY KEY (`id_location`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `lodge`.`L_LOCATION_DATAS`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `lodge`.`L_LOCATION_DATAS` ;

CREATE  TABLE IF NOT EXISTS `lodge`.`L_LOCATION_DATAS` (
  `id_location_data` INT(10) NOT NULL AUTO_INCREMENT ,
  `id_location` INT(10) NOT NULL ,
  PRIMARY KEY (`id_location_data`) ,
  INDEX `fk_L_LOCATION_DATAS_L_LOCATIONS1` (`id_location` ASC) ,
  CONSTRAINT `fk_L_LOCATION_DATAS_L_LOCATIONS1`
    FOREIGN KEY (`id_location` )
    REFERENCES `lodge`.`L_LOCATIONS` (`id_location` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `lodge`.`L_EVENT_DATAS`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `lodge`.`L_EVENT_DATAS` ;

CREATE  TABLE IF NOT EXISTS `lodge`.`L_EVENT_DATAS` (
  `id_event_data` INT(10) NOT NULL AUTO_INCREMENT ,
  `id_event` INT(10) NOT NULL ,
  PRIMARY KEY (`id_event_data`) ,
  INDEX `fk_L_EVENT_DATAS_L_EVENTS1` (`id_event` ASC) ,
  CONSTRAINT `fk_L_EVENT_DATAS_L_EVENTS1`
    FOREIGN KEY (`id_event` )
    REFERENCES `lodge`.`L_EVENTS` (`id_event` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `lodge`.`L_MESSAGES`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `lodge`.`L_MESSAGES` ;

CREATE  TABLE IF NOT EXISTS `lodge`.`L_MESSAGES` (
  `id_message` INT(20) NOT NULL AUTO_INCREMENT ,
  PRIMARY KEY (`id_message`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `lodge`.`L_GROUPS_EVENTS`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `lodge`.`L_GROUPS_EVENTS` ;

CREATE  TABLE IF NOT EXISTS `lodge`.`L_GROUPS_EVENTS` (
  `id_group` INT(10) NOT NULL ,
  `id_event` INT(10) NOT NULL ,
  `id_group_event` INT(10) NULL ,
  PRIMARY KEY (`id_group`, `id_event`) ,
  INDEX `fk_L_GROUPS_has_L_EVENTS_L_EVENTS1` (`id_event` ASC) ,
  INDEX `fk_L_GROUPS_has_L_EVENTS_L_GROUPS1` (`id_group` ASC) ,
  UNIQUE INDEX `id_group_event_UNIQUE` (`id_group_event` ASC) ,
  CONSTRAINT `fk_L_GROUPS_has_L_EVENTS_L_GROUPS1`
    FOREIGN KEY (`id_group` )
    REFERENCES `lodge`.`L_GROUPS` (`id_group` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_L_GROUPS_has_L_EVENTS_L_EVENTS1`
    FOREIGN KEY (`id_event` )
    REFERENCES `lodge`.`L_EVENTS` (`id_event` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `lodge`.`L_GROUPS_LOCATIONS`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `lodge`.`L_GROUPS_LOCATIONS` ;

CREATE  TABLE IF NOT EXISTS `lodge`.`L_GROUPS_LOCATIONS` (
  `id_group` INT(10) NOT NULL ,
  `id_location` INT(10) NOT NULL ,
  `id_group_location` INT(10) NULL ,
  PRIMARY KEY (`id_group`, `id_location`) ,
  INDEX `fk_L_GROUPS_has_L_LOCATIONS_L_LOCATIONS1` (`id_location` ASC) ,
  INDEX `fk_L_GROUPS_has_L_LOCATIONS_L_GROUPS1` (`id_group` ASC) ,
  UNIQUE INDEX `id_group_location_UNIQUE` (`id_group_location` ASC) ,
  CONSTRAINT `fk_L_GROUPS_has_L_LOCATIONS_L_GROUPS1`
    FOREIGN KEY (`id_group` )
    REFERENCES `lodge`.`L_GROUPS` (`id_group` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_L_GROUPS_has_L_LOCATIONS_L_LOCATIONS1`
    FOREIGN KEY (`id_location` )
    REFERENCES `lodge`.`L_LOCATIONS` (`id_location` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
COMMENT = 'Location table contains all the location associated to group' /* comment truncated */;


-- -----------------------------------------------------
-- Table `lodge`.`L_EVENTS_LOCATIONS`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `lodge`.`L_EVENTS_LOCATIONS` ;

CREATE  TABLE IF NOT EXISTS `lodge`.`L_EVENTS_LOCATIONS` (
  `id_event` INT(10) NOT NULL ,
  `id_location` INT(10) NOT NULL ,
  `id_event_location` VARCHAR(45) NULL ,
  PRIMARY KEY (`id_event`, `id_location`) ,
  INDEX `fk_L_EVENTS_has_L_LOCATIONS_L_LOCATIONS1` (`id_location` ASC) ,
  INDEX `fk_L_EVENTS_has_L_LOCATIONS_L_EVENTS1` (`id_event` ASC) ,
  UNIQUE INDEX `id_event_location_UNIQUE` (`id_event_location` ASC) ,
  CONSTRAINT `fk_L_EVENTS_has_L_LOCATIONS_L_EVENTS1`
    FOREIGN KEY (`id_event` )
    REFERENCES `lodge`.`L_EVENTS` (`id_event` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_L_EVENTS_has_L_LOCATIONS_L_LOCATIONS1`
    FOREIGN KEY (`id_location` )
    REFERENCES `lodge`.`L_LOCATIONS` (`id_location` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `lodge`.`L_GROUP_DATAS`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `lodge`.`L_GROUP_DATAS` ;

CREATE  TABLE IF NOT EXISTS `lodge`.`L_GROUP_DATAS` (
  `id_group_data` INT(10) NOT NULL AUTO_INCREMENT ,
  `L_GROUPS_id_group` INT(10) NOT NULL ,
  PRIMARY KEY (`id_group_data`) ,
  INDEX `fk_L_GROUP_DATAS_L_GROUPS1` (`L_GROUPS_id_group` ASC) ,
  CONSTRAINT `fk_L_GROUP_DATAS_L_GROUPS1`
    FOREIGN KEY (`L_GROUPS_id_group` )
    REFERENCES `lodge`.`L_GROUPS` (`id_group` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `lodge`.`L_MESSAGES_USERS`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `lodge`.`L_MESSAGES_USERS` ;

CREATE  TABLE IF NOT EXISTS `lodge`.`L_MESSAGES_USERS` (
  `id_message` INT(20) NOT NULL ,
  `id_user` INT(10) NOT NULL ,
  `id_message_user` VARCHAR(45) NULL ,
  PRIMARY KEY (`id_message`, `id_user`) ,
  INDEX `fk_L_MESSAGES_has_L_USERS_L_USERS1` (`id_user` ASC) ,
  INDEX `fk_L_MESSAGES_has_L_USERS_L_MESSAGES1` (`id_message` ASC) ,
  UNIQUE INDEX `id_message_user_UNIQUE` (`id_message_user` ASC) ,
  CONSTRAINT `fk_L_MESSAGES_has_L_USERS_L_MESSAGES1`
    FOREIGN KEY (`id_message` )
    REFERENCES `lodge`.`L_MESSAGES` (`id_message` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_L_MESSAGES_has_L_USERS_L_USERS1`
    FOREIGN KEY (`id_user` )
    REFERENCES `lodge`.`L_USERS` (`id_user` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `lodge`.`L_MESSAGES_GROUPS`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `lodge`.`L_MESSAGES_GROUPS` ;

CREATE  TABLE IF NOT EXISTS `lodge`.`L_MESSAGES_GROUPS` (
  `id_message` INT(20) NOT NULL ,
  `id_group` INT(10) NOT NULL ,
  `id_message_group` VARCHAR(45) NULL ,
  PRIMARY KEY (`id_message`, `id_group`) ,
  INDEX `fk_L_MESSAGES_has_L_GROUPS_L_GROUPS1` (`id_group` ASC) ,
  INDEX `fk_L_MESSAGES_has_L_GROUPS_L_MESSAGES1` (`id_message` ASC) ,
  UNIQUE INDEX `id_message_group_UNIQUE` (`id_message_group` ASC) ,
  CONSTRAINT `fk_L_MESSAGES_has_L_GROUPS_L_MESSAGES1`
    FOREIGN KEY (`id_message` )
    REFERENCES `lodge`.`L_MESSAGES` (`id_message` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_L_MESSAGES_has_L_GROUPS_L_GROUPS1`
    FOREIGN KEY (`id_group` )
    REFERENCES `lodge`.`L_GROUPS` (`id_group` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
