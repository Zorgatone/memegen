SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

CREATE SCHEMA IF NOT EXISTS `Sql716294_2` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
USE `Sql716294_2` ;

-- -----------------------------------------------------
-- Table `Sql716294_2`.`user`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Sql716294_2`.`user` (
  `ID` INT NOT NULL,
  `age-range-min` INT NULL,
  `age-range-max` INT NULL,
  `birthday` DATETIME NULL,
  PRIMARY KEY (`ID`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Sql716294_2`.`meme`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Sql716294_2`.`meme` (
  `ID` INT NOT NULL AUTO_INCREMENT,
  `nomeutente` VARCHAR(75) NULL,
  `immagine` MEDIUMBLOB NOT NULL,
  `user_ID` INT NOT NULL,
  PRIMARY KEY (`ID`),
  INDEX `fk_meme_user1_idx` (`user_ID` ASC),
  CONSTRAINT `fk_meme_user1`
    FOREIGN KEY (`user_ID`)
    REFERENCES `Sql716294_2`.`user` (`ID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Sql716294_2`.`devices`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Sql716294_2`.`devices` (
  `ID` INT NOT NULL AUTO_INCREMENT,
  `hardware` VARCHAR(75) NOT NULL,
  `os` VARCHAR(75) NOT NULL,
  `user` INT NOT NULL,
  PRIMARY KEY (`ID`),
  INDEX `fk_devices_user1_idx` (`user` ASC),
  CONSTRAINT `fk_devices_user1`
    FOREIGN KEY (`user`)
    REFERENCES `Sql716294_2`.`user` (`ID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Sql716294_2`.`education`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Sql716294_2`.`education` (
  `ID` INT NOT NULL AUTO_INCREMENT,
  `school` VARCHAR(75) NOT NULL,
  `year` INT NULL,
  `type` VARCHAR(75) NULL,
  `user` INT NOT NULL,
  PRIMARY KEY (`ID`),
  INDEX `fk_education_user1_idx` (`user` ASC),
  CONSTRAINT `fk_education_user1`
    FOREIGN KEY (`user`)
    REFERENCES `Sql716294_2`.`user` (`ID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Sql716294_2`.`concentration`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Sql716294_2`.`concentration` (
  `ID` INT NOT NULL,
  `education` INT NOT NULL,
  PRIMARY KEY (`ID`),
  INDEX `fk_concentration_education1_idx` (`education` ASC),
  CONSTRAINT `fk_concentration_education1`
    FOREIGN KEY (`education`)
    REFERENCES `Sql716294_2`.`education` (`ID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
