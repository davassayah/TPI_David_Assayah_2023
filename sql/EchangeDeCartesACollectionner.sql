SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema echangeDeCartesACollectionner
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema echangeDeCartesACollectionner
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `echangeDeCartesACollectionner` DEFAULT CHARACTER SET utf8 ;
USE `echangeDeCartesACollectionner` ;

-- -----------------------------------------------------
-- Table `echangeDeCartesACollectionner`.`t_user`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `echangeDeCartesACollectionner`.`t_user` (
  `idUser` INT NOT NULL AUTO_INCREMENT,
  `useLogin` VARCHAR(120) NOT NULL,
  `useEmail` VARCHAR(45) NOT NULL,
  `useFirstName` VARCHAR(120) NOT NULL,
  `useName` VARCHAR(120) NOT NULL,
  `useLocality` VARCHAR(120) NOT NULL,
  `usePostalCode` VARCHAR(10) NOT NULL,
  `useStreetName` VARCHAR(255) NOT NULL,
  `useStreetNumber` VARCHAR(45) NOT NULL,
  `usePassword` VARCHAR(64) NOT NULL,
  `useCredits` INT NOT NULL,
  `useRole` ENUM("user", "admin") NOT NULL DEFAULT 'user',
  PRIMARY KEY (`idUser`),
  UNIQUE INDEX `useLogin_UNIQUE` (`useLogin` ASC) VISIBLE,
  UNIQUE INDEX `useEmail_UNIQUE` (`useEmail` ASC) VISIBLE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `echangeDeCartesACollectionner`.`t_order`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `echangeDeCartesACollectionner`.`t_order` (
  `idOrder` INT NOT NULL AUTO_INCREMENT,
  `ordStatus` ENUM("pending", "processed") NOT NULL,
  `fkUser` INT NOT NULL,
  PRIMARY KEY (`idOrder`),
  INDEX `idUser_idx` (`fkUser` ASC) VISIBLE,
  CONSTRAINT `idUser`
    FOREIGN KEY (`fkUser`)
    REFERENCES `echangeDeCartesACollectionner`.`t_user` (`idUser`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `echangeDeCartesACollectionner`.`t_collection`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `echangeDeCartesACollectionner`.`t_collection` (
  `idCollection` INT NOT NULL AUTO_INCREMENT,
  `colName` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`idCollection`),
  UNIQUE INDEX `colName_UNIQUE` (`colName` ASC) VISIBLE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `echangeDeCartesACollectionner`.`t_card`
-- -----------------------------------------------------

-- Table echangeDeCartesACollectionner.t_card

CREATE TABLE IF NOT EXISTS echangeDeCartesACollectionner.t_card (
idCard INT NOT NULL AUTO_INCREMENT,
carName VARCHAR(45) NOT NULL,
carDate YEAR NOT NULL,
carCredits INT NOT NULL,
carCondition VARCHAR(45) NOT NULL,
carDescription VARCHAR(45) NOT NULL,
carIsAvailable TINYINT(1) NOT NULL DEFAULT 1,
carPhoto VARCHAR(45) NOT NULL,
fkUser INT NOT NULL,
fkOrder INT NULL,
fkCollection INT NOT NULL,
PRIMARY KEY (idCard),
INDEX idUser_idx (fkUser ASC) VISIBLE,
INDEX idOrder_idx (fkOrder ASC) VISIBLE,
INDEX idCollection_idx (fkCollection ASC) VISIBLE,
CONSTRAINT fk_user
FOREIGN KEY (fkUser)
REFERENCES echangeDeCartesACollectionner.t_user (idUser)
ON DELETE NO ACTION
ON UPDATE NO ACTION,
CONSTRAINT fk_order
FOREIGN KEY (fkOrder)
    REFERENCES `echangeDeCartesACollectionner`.`t_order` (`idOrder`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `idCollection`
    FOREIGN KEY (`fkCollection`)
    REFERENCES `echangeDeCartesACollectionner`.`t_collection` (`idCollection`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
