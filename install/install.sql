DROP TABLE IF EXISTS `fwlink`;
DROP TABLE IF EXISTS `user`;
DROP TABLE IF EXISTS `blacklist`;
DROP TABLE IF EXISTS `visit`;

CREATE TABLE `fwlink` (
    `id` INT(10) UNSIGNED PRIMARY KEY,
    `url` TEXT NOT NULL,
    `username` VARCHAR(16)
);

CREATE TABLE `user` (
    `username` VARCHAR(16) PRIMARY KEY,
    `password` VARCHAR(32) NOT NULL,
    `admin` BOOLEAN NOT NULL
);

CREATE TABLE `blacklist` (
    `domain` VARCHAR(40) PRIMARY KEY
);

CREATE TABLE `visit` (
    `id` INT(10) UNSIGNED NOT NULL,
    `date` DATE NOT NULL,
    `count` INT UNSIGNED NOT NULL,
    PRIMARY KEY(`id`, date)
);