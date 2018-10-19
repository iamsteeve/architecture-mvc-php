
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------------
-- category
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `category`;

CREATE TABLE `category`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    `description` VARCHAR(255) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- task
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `task`;

CREATE TABLE `task`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    `description` VARCHAR(255) NOT NULL,
    `date` DATE NOT NULL,
    `priority` INTEGER NOT NULL,
    `status` TINYINT(1) NOT NULL,
    `category_id` INTEGER NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `category_id` (`category_id`),
    CONSTRAINT `task_ibfk_1`
        FOREIGN KEY (`category_id`)
        REFERENCES `category` (`id`)
        ON UPDATE CASCADE
) ENGINE=InnoDB;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
